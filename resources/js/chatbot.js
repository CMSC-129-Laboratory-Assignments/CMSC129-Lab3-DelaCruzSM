// Chatbot Widget JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const fabBtn = document.getElementById('ai-fab-btn');
    const closeBtn = document.getElementById('ai-close-btn');
    const chatWindow = document.getElementById('ai-chat-window');
    const chatMessages = document.getElementById('ai-chat-messages');
    const chatInput = document.getElementById('ai-chat-input');
    const sendBtn = document.getElementById('ai-send-btn');
    const chatIdInput = document.getElementById('ai-chat-id');
    const sendIcon = document.getElementById('ai-send-icon');
    const loader = document.getElementById('ai-loader');

    // State variable to track if we're waiting for an AI response
    let isWaitingForAi = false;

    // Grab the CSRF token from the meta tag we just added
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Function to toggle the chat window open/closed
    function toggleChat() {
        chatWindow.classList.toggle('active');
        fabBtn.classList.toggle('active');

        if (chatWindow.classList.contains('active')) {
            document.getElementById('ai-fab-msg').style.display = 'none';
            document.getElementById('ai-fab-close').style.display = 'block';
            setTimeout(() => chatInput.focus(), 300);
        } else {
            document.getElementById('ai-fab-msg').style.display = 'block';
            document.getElementById('ai-fab-close').style.display = 'none';
        }
    }

    fabBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    function getCurrentTime() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Main function to send a message to the server and handle the response
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message || isWaitingForAi) return; 

        const userHtml = `
            <div class="ai-msg-bubble ai-msg-user">
                <p style="margin: 0;">${message}</p>
                <div style="font-size: 10px; color: #c7d2fe; text-align: right; margin-top: 4px;">${getCurrentTime()}</div>
            </div>`;
        chatMessages.insertAdjacentHTML('beforeend', userHtml);
        scrollToBottom();
        chatInput.value = '';

        isWaitingForAi = true;
        chatInput.disabled = true;
        sendIcon.style.display = 'none';
        loader.style.display = 'block';

        const loadingId = 'loading-' + Date.now();
        chatMessages.insertAdjacentHTML('beforeend', `
            <div id="${loadingId}" class="ai-msg-bubble ai-msg-bot">
                <p style="margin: 0; font-style: italic;">Typing...</p>
            </div>`);
        scrollToBottom();

        fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers for Laravel to validate
            },
            body: JSON.stringify({
                message: message,
                chat_id: chatIdInput.value
            })
        })
        .then(async response => {
            if (!response.ok) throw new Error(await response.text());
            return response.json();
        })
        .then(data => {
            chatIdInput.value = data.chat_id;
            document.getElementById(loadingId).remove();

            chatMessages.insertAdjacentHTML('beforeend', `
                <div class="ai-msg-bubble ai-msg-bot">
                    <p style="margin: 0;">${data.reply}</p>
                    <div style="font-size: 10px; color: #94a3b8; text-align: right; margin-top: 4px;">${getCurrentTime()}</div>
                </div>`);
        })
        .catch(error => {
            console.error(error);
            document.getElementById(loadingId).remove();

            chatMessages.insertAdjacentHTML('beforeend', `
                <div class="ai-msg-bubble ai-msg-error">
                    <p style="margin: 0; font-weight: bold;">Connection Error</p>
                    <p style="margin: 4px 0 0 0;">Server Error. Please try again.</p>
                </div>`);
        })
        .finally(() => {
            isWaitingForAi = false;
            chatInput.disabled = false;
            sendIcon.style.display = 'block';
            loader.style.display = 'none';
            scrollToBottom();
            chatInput.focus();
        });
    }
});
