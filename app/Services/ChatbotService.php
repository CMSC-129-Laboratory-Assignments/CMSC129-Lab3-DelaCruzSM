<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Journal;
use Illuminate\Support\Facades\Http; // For making HTTP requests to the Groq API`

class ChatbotService {

    // Saves a message to the database
    public function saveMessage(int $chatId, string $role, string $content) {
        return Message::create([
            'chat_id' => $chatId,
            'role' => $role,
            'content' => $content
        ]);
    }

    // Generates a response from the Groq API based on the user's message
    public function generateResponse(int $userId, int $chatId, string $userMessage) {

        $this->saveMessage($chatId, 'user', $userMessage);

        // System prompt that instructs the AI to use the API route
        $systemPrompt =
            "You are an empathetic Journal Assistant. \n" .
            "IMPORTANT: You do NOT have the user's journal entries yet.\n" .
            "If the user asks a question that requires reading their journal entries, you MUST reply with this exact phrase and absolutely nothing else:\n" .
            "[CALL_API: /api/users/journals]\n\n" .
            "Once the system provides you with the raw JSON data from that API endpoint, formulate your final answer to the user based on that data.\n" .
            "STRICT RULES: \n" .
            "1. Base your answers ONLY on the API data provided. Do not make assumptions.\n" .
            "2. Suggest what to do with their entries based on app features (favorite, delete).\n" .
            "3. Be empathetic and supportive.\n" .
            "4. Match the user's language (English, Tagalog, Hiligaynon, or Taglish).\n" .
            "5. DO NOT use markdown formatting (no asterisks). Use plain text only.\n";

        // Fetch the chat history
        $chatHistory = Message::where('chat_id', $chatId)->orderBy('created_at', 'asc')->get();

        // Format the conversation history for the Groq API
        $formattedHistory = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];
        foreach($chatHistory as $msg) {
            $roleStr = $msg->role === 'user' ? 'user' : 'assistant';
            $formattedHistory[] = ['role' => $roleStr, 'content' => $msg->content];
        }

        try {
            // First call to the Groq API with the initial conversation history
            $response = Http::withToken(env('GROQ_API_KEY'))
                ->timeout(15)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $formattedHistory,
                    'temperature' => 0.5,
                ]);

            if (!$response->successful()) {
                throw new \Exception("Groq API Error: " . $response->body());
            }

            // Extracts the AI reply from the API response
            $aiReply = $response->json('choices.0.message.content');

            /**
             * API INTERCEPT (Context Injection)
             * We intercept the AI's request for data so it doesn't accidentally show
             * the "[CALL_API...]" text to the human user.
             */
            // Checks if the AI reply contains the special API call phrase
            if (str_contains($aiReply, '[CALL_API: /api/users/journals]')) {

                // Fetches the actual database data using Eloquent
                $entries = Journal::where('user_id', $userId)->latest()->take(20)->get();
                $journalJsonData = $entries->toJson();

                // Adds the AI API request to the conversation history array
                $formattedHistory[] = ['role' => 'assistant', 'content' => $aiReply];

                // Adds the JSON response to the history array so the AI can read it
                $secondPrompt = "Here is the JSON data from the API endpoint:\n" . $journalJsonData .
                                "\n\nPlease provide your final answer to the user now.";
                $formattedHistory[] = ['role' => 'user', 'content' => $secondPrompt];

                // Makes a second call to the Groq API with the updated conversation history
                // that now includes the API data
                $response2 = Http::withToken(env('GROQ_API_KEY'))
                    ->timeout(15)
                    ->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'llama-3.1-8b-instant',
                        'messages' => $formattedHistory,
                        'temperature' => 0.7,
                    ]);

                // Overwrites the reply with the final, data-aware answer
                $aiReply = $response2->json('choices.0.message.content');
            }

            // Saves the final reply to the database and returns it to the controller
            $this->saveMessage($chatId, 'assistant', $aiReply);
            return $aiReply;    // Returns the AI assistant's reply back to the controller

        } catch (\Throwable $e) {
            return "I'm having trouble connecting right now. Error: " . $e->getMessage();
        }
    }
}
