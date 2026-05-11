<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBotController extends Controller
{
    protected $chatbotService;

    // Injects the ChatbotService into the controller
    public function __construct(ChatbotService $chatbotService) {
        $this->chatbotService = $chatbotService;
    }
    // Handles incoming chat message from the floating widget and returns the AI response
    public function chat(Request $request) {
        // Validates the incoming request to ensure it has the required fields
        // Fields: message (string), chat_id (optional, for existing chats)
        $request->validate([
            'message' => 'required|string',
            'chat_id' => 'nullable|exists:chats,id' // chat_id is optional for the first message, but if provided it must exist in the chats table
        ]);

        $chatId = $request->chat_id;
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'reply' => 'User not authenticated',
                'chat_id' => null
            ]);
        }

        // If there is no active chat, create one for the logged-in user
        if (!$chatId) {
            $chat = Chat::create([
                'user_id' => $userId, // Assumes the user is logged in to the journal app
                'title' => 'Journal Assistant Chat'
            ]);
            $chatId = $chat->id;
        }

        // Calls the chatbot service to get the AI response
        $reply = $this->chatbotService->generateResponse($userId, $chatId, $request->message);

        // Returns the data back to the floating widget
        return response()->json([
            'reply' => $reply,
            'chat_id' => $chatId
        ]);
    }
}
