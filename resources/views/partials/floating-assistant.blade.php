<a href="javascript:void(0);" id="floatingAssistantBtn" class="floating-assistant-btn" aria-label="Assistant">
    <span class="assistant-icon-wrap">
        <svg viewBox="0 0 64 64" class="assistant-icon" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="14" y="18" width="36" height="28" rx="14" stroke="white" stroke-width="4"/>
            <circle cx="32" cy="32" r="8" stroke="white" stroke-width="4"/>
            <path d="M10 28H16" stroke="white" stroke-width="4" stroke-linecap="round"/>
            <path d="M48 28H54" stroke="white" stroke-width="4" stroke-linecap="round"/>
            <path d="M24 46L20 52" stroke="white" stroke-width="4" stroke-linecap="round"/>
            <path d="M40 46L44 52" stroke="white" stroke-width="4" stroke-linecap="round"/>
            <path d="M52 14L53.5 17.5L57 19L53.5 20.5L52 24L50.5 20.5L47 19L50.5 17.5L52 14Z" fill="white"/>
        </svg>
    </span>
    <span class="assistant-label">assistant</span>
</a>

<div class="assistant-chat-panel" id="assistantChatPanel">
    <div class="assistant-chat-card">
        <div class="assistant-chat-header">
            <div class="assistant-chat-brand">
                <div class="assistant-chat-logo">
                    <svg viewBox="0 0 64 64" class="assistant-chat-logo-icon" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="14" y="18" width="36" height="28" rx="14" stroke="white" stroke-width="4"/>
                        <circle cx="32" cy="32" r="8" stroke="white" stroke-width="4"/>
                        <path d="M10 28H16" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        <path d="M48 28H54" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        <path d="M24 46L20 52" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        <path d="M40 46L44 52" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        <path d="M52 14L53.5 17.5L57 19L53.5 20.5L52 24L50.5 20.5L47 19L50.5 17.5L52 14Z" fill="white"/>
                    </svg>
                </div>

                <div>
                    <div class="assistant-chat-title">Bluesky Assistant</div>
                    <div class="assistant-chat-subtitle">Online now</div>
                </div>
            </div>

            <button type="button" class="assistant-chat-close" id="assistantChatClose" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>

        <div class="assistant-chat-body" id="assistantChatBody">
            <div class="assistant-message assistant-message-bot">
                <div class="assistant-message-bubble">
                    Hi there 👋 Welcome to Bluesky Mart. How can I help you today?
                </div>
                <div class="assistant-message-time">Just now</div>
            </div>

            <div class="assistant-quick-actions">
                <button type="button" class="assistant-quick-btn">Track my order</button>
                <button type="button" class="assistant-quick-btn">Payment issue</button>
                <button type="button" class="assistant-quick-btn">Refund help</button>
                <button type="button" class="assistant-quick-btn">Talk to support</button>
            </div>
        </div>

        <div class="assistant-chat-footer">
            <form id="assistantChatForm" class="assistant-chat-form">
                <textarea
                    id="assistantChatInput"
                    class="assistant-chat-input"
                    rows="1"
                    placeholder="Type your message..."
                ></textarea>

                <button type="submit" class="assistant-send-btn" aria-label="Send">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 20L21 12L3 4L3 10L15 12L3 14L3 20Z" fill="white"/>
                    </svg>
                </button>
            </form>

            <div class="assistant-footer-note">
                Ask about orders, payments, delivery, and support.
            </div>
        </div>
    </div>
</div>