const chatToggle   = document.getElementById('chat-toggle');
const chatClose    = document.getElementById('chat-close');
const chatWindow   = document.getElementById('chat-window');
const chatInput    = document.getElementById('chat-input');
const chatSend     = document.getElementById('chat-send');
const chatMessages = document.getElementById('chat-messages');

// Conversation history sent to the API each time
const history = [];

function openChat() {
    chatWindow.classList.add('open');
    chatToggle.setAttribute('aria-expanded', 'true');
    chatToggle.setAttribute('hidden', 'true');
    chatInput.focus();
}

function closeChat() {
    chatWindow.classList.remove('open');
    chatToggle.setAttribute('aria-expanded', 'false');
    chatToggle.removeAttribute('hidden');
}

chatToggle.addEventListener('click', () =>
    chatWindow.classList.contains('open') ? closeChat() : openChat()
);
chatClose.addEventListener('click', closeChat);

function addMessage(text, sender) {
    const msg = document.createElement('div');
    msg.classList.add('chat-msg', sender);
    msg.textContent = text;
    chatMessages.appendChild(msg);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function setLoading(loading) {
    chatSend.disabled = loading;
    chatInput.disabled = loading;
    chatSend.textContent = loading ? '...' : 'Send';
}

async function sendMessage() {
    const text = chatInput.value.trim();
    if (!text) return;

    addMessage(text, 'user');
    history.push({ role: 'user', content: text });
    chatInput.value = '';
    setLoading(true);

    try {
        const res = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ messages: history }),
        });

        const data = await res.json();
        const reply = data.reply ?? 'Sorry, I could not get a response.';
        addMessage(reply, 'bot');
        history.push({ role: 'assistant', content: reply });
    } catch {
        addMessage('Something went wrong. Please try again.', 'bot');
    } finally {
        setLoading(false);
    }
}

chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
});
