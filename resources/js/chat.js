const chatToggle   = document.getElementById('chat-toggle');
const chatClose    = document.getElementById('chat-close');
const chatDiagnostic = document.getElementById('chat-diagnostic');
const chatWindow   = document.getElementById('chat-window');
const chatInput    = document.getElementById('chat-input');
const chatSend     = document.getElementById('chat-send');
const chatMessages = document.getElementById('chat-messages');
const chatDebug = true;

// Conversation history sent to the API each time
const history = [];

function browserLog(label, payload = null) {
    if (!chatDebug) return;

    if (payload === null) {
        console.info(`[chat] ${label}`);
        return;
    }

    console.info(`[chat] ${label}`, payload);
}

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

function formatDiagnosticCheck(checks = {}) {
    return Object.entries(checks)
        .map(([key, value]) => `${key}: ${value ? 'ok' : 'missing'}`)
        .join(' • ');
}

async function runDiagnostic() {
    addMessage('Checking the AI assistant setup…', 'bot');
    browserLog('running diagnostic request');

    try {
        const res = await fetch('/chat/diagnostic', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        });

        const data = await res.json();
        browserLog('diagnostic response', { status: res.status, data });
        const message = data.message ?? 'Diagnostic check complete.';
        addMessage(message, 'bot');

        if (data.checks) {
            addMessage(formatDiagnosticCheck(data.checks), 'bot');
        }
    } catch (error) {
        console.error('[chat] diagnostic failed', error);
        addMessage(`Diagnostic failed: ${error.message ?? 'unknown error'}`, 'bot');
    }
}

async function sendMessage() {
    const text = chatInput.value.trim();
    if (!text) return;

    addMessage(text, 'user');
    history.push({ role: 'user', content: text });
    chatInput.value = '';
    setLoading(true);
    browserLog('sending message payload', { messages: history });

    try {
        const res = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ messages: history }),
        });

        const responseText = await res.text();
        let data = {};

        try {
            data = responseText ? JSON.parse(responseText) : {};
            browserLog('chat response', { status: res.status, data });
        } catch {
            throw new Error('The server returned an invalid response. Check the app logs or API key.');
        }

        if (!res.ok) {
            throw new Error(data.message ?? 'The AI request failed.');
        }

        const reply = data.reply ?? 'Sorry, I could not get a response.';
        addMessage(reply, 'bot');
        history.push({ role: 'assistant', content: reply });
    } catch (error) {
        console.error('[chat] send failed', error);
        addMessage(error.message || 'Something went wrong. Please try again.', 'bot');
    } finally {
        setLoading(false);
    }
}

chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
});
chatDiagnostic.addEventListener('click', runDiagnostic);

