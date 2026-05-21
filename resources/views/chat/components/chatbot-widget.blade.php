{{-- Chatbot Widget --}}

<div class="ai-chat-wrapper">
    {{-- Chat Window --}}
    <div id="ai-chat-window" class="ai-chat-window">

        {{-- Header --}}
        <div class="ai-chat-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 12px; display: flex;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 14px; font-weight: 600;">AI Journal Assistant</h3>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                        <span style="width: 6px; height: 6px; background-color: #4ade80; border-radius: 50%;"></span>
                        <p style="margin: 0; font-size: 11px; color: #e0e7ff;">Online</p>
                    </div>
                </div>
            </div>
            <button id="ai-close-btn" style="background: none; border: none; color: white; cursor: pointer; padding: 4px; display: flex;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        {{-- Messages Area --}}
        <div id="ai-chat-messages" class="ai-chat-messages">
            <div class="ai-msg-bubble ai-msg-bot">
                <p style="margin: 0; ">Hello! I am your AI Journal Assistant. Do you want to reflect on any specific entries today, or need help finding something?</p>
                <div style="font-size: 10px; color: #94a3b8; text-align: right; margin-top: 4px;">Just now</div>
            </div>
        </div>

        {{-- Input Area --}}
        <div>
            <div class="ai-chat-input-area">
                <input type="hidden" id="ai-chat-id" value="">
                <input type="text" id="ai-chat-input" class="ai-chat-input" placeholder="Ask your assistant...">
                <button id="ai-send-btn" class="ai-chat-send-btn">
                    <svg id="ai-send-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    <span id="ai-loader" style="display: none; font-size: 10px;">...</span>
                </button>
            </div>
            <div style="background: white; text-align: center; padding-bottom: 12px;">
                <p style="margin: 0; font-size: 10px; color: #94a3b8;">Powered by Groq AI</p>
            </div>
        </div>
    </div>

    {{-- Floating Action Button --}}
    <button id="ai-fab-btn" class="ai-fab-btn">
        <svg id="ai-fab-msg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
        <svg id="ai-fab-close" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
</div>
