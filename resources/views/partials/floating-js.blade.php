<script>
document.addEventListener('DOMContentLoaded', function () {
    const assistantBtn = document.getElementById('floatingAssistantBtn');
    const assistantPanel = document.getElementById('assistantChatPanel');
    const assistantClose = document.getElementById('assistantChatClose');
    const assistantForm = document.getElementById('assistantChatForm');
    const assistantInput = document.getElementById('assistantChatInput');
    const assistantBody = document.getElementById('assistantChatBody');
    const quickButtons = document.querySelectorAll('.assistant-quick-btn');

    function openAssistant() {
        if (!assistantPanel || !assistantBtn) return;
        assistantPanel.classList.add('is-open');
        assistantBtn.style.display = 'none';
        setTimeout(() => {
            if (assistantInput) assistantInput.focus();
        }, 150);
    }

    function closeAssistant() {
        if (!assistantPanel || !assistantBtn) return;
        assistantPanel.classList.remove('is-open');
        assistantBtn.style.display = 'flex';
    }

    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function appendUserMessage(message) {
        if (!assistantBody) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'assistant-message assistant-message-user';
        wrapper.innerHTML = `
            <div class="assistant-message-bubble"></div>
            <div class="assistant-message-time">${getCurrentTime()}</div>
        `;
        wrapper.querySelector('.assistant-message-bubble').textContent = message;
        assistantBody.appendChild(wrapper);
        assistantBody.scrollTop = assistantBody.scrollHeight;
    }

    function appendBotMessage(message) {
        if (!assistantBody) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'assistant-message assistant-message-bot';
        wrapper.innerHTML = `
            <div class="assistant-message-bubble"></div>
            <div class="assistant-message-time">${getCurrentTime()}</div>
        `;
        wrapper.querySelector('.assistant-message-bubble').textContent = message;
        assistantBody.appendChild(wrapper);
        assistantBody.scrollTop = assistantBody.scrollHeight;
    }

    if (assistantBtn) {
        assistantBtn.addEventListener('click', openAssistant);
    }

    if (assistantClose) {
        assistantClose.addEventListener('click', closeAssistant);
    }

    if (quickButtons.length) {
        quickButtons.forEach(button => {
            button.addEventListener('click', function () {
                const text = this.textContent.trim();
                appendUserMessage(text);

                setTimeout(() => {
                    appendBotMessage('Thanks. We received your request about "' + text + '". A support response can be connected here next.');
                }, 500);
            });
        });
    }

    if (assistantForm) {
        assistantForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const text = assistantInput.value.trim();
            if (!text) return;

            appendUserMessage(text);
            assistantInput.value = '';
            assistantInput.style.height = '48px';

            setTimeout(() => {
                appendBotMessage('Your message has been captured. The next step is to connect this UI to your support backend.');
            }, 500);
        });
    }

    if (assistantInput) {
        assistantInput.addEventListener('input', function () {
            this.style.height = '48px';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && assistantPanel && assistantPanel.classList.contains('is-open')) {
            closeAssistant();
        }
    });
});
</script>