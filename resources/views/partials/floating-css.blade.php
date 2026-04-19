<style>
    .floating-assistant-btn {
    position: fixed;
    right: 18px;
    bottom: 90px;
    width: 75px;
    height: 100px;
    background: #156CF7;
    border-radius: 20px;
    box-shadow: 0 14px 32px rgba(21, 108, 247, 0.30);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    z-index: 9998;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.floating-assistant-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 36px rgba(21, 108, 247, 0.35);
    text-decoration: none;
}

.assistant-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.assistant-icon {
    width: 42px;
    height: 42px;
}

.assistant-label {
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    line-height: 1;
    text-transform: lowercase;
}

.assistant-chat-panel {
    position: fixed;
    right: 18px;
    bottom: 90px;
    width: 360px;
    max-width: calc(100vw - 20px);

    height: auto;
    max-height: calc(100vh - 140px); /* 🔥 prevents overflow */

    display: none;
    z-index: 9999;
}

.assistant-chat-panel.is-open {
    display: flex;
}

.assistant-chat-card {
    width: 100%;
    height: 100%;
    max-height: inherit;

    display: flex;
    flex-direction: column;

    background: #fff;
    border-radius: 22px;
    overflow: hidden;
}

.assistant-chat-header {
    background: linear-gradient(180deg, #156CF7 0%, #0E5DE0 100%);
    color: #fff;
    padding: 18px 18px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.assistant-chat-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.assistant-chat-logo {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.16);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.assistant-chat-logo-icon {
    width: 28px;
    height: 28px;
}

.assistant-chat-title {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.2;
}

.assistant-chat-subtitle {
    font-size: 12px;
    opacity: 0.9;
    margin-top: 3px;
}

.assistant-chat-close {
    border: 0;
    background: rgba(255, 255, 255, 0.14);
    color: #fff;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
}

.assistant-chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f6f9ff;
}
.assistant-message {
    margin-bottom: 14px;
    display: flex;
    flex-direction: column;
}

.assistant-message-bot {
    align-items: flex-start;
}

.assistant-message-user {
    align-items: flex-end;
}

.assistant-message-bubble {
    max-width: 82%;
    padding: 12px 14px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.45;
    word-break: break-word;
}

.assistant-message-bot .assistant-message-bubble {
    background: #ffffff;
    color: #1f2937;
    border-top-left-radius: 8px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
}

.assistant-message-user .assistant-message-bubble {
    background: #156CF7;
    color: #ffffff;
    border-top-right-radius: 8px;
    box-shadow: 0 10px 18px rgba(21, 108, 247, 0.22);
}

.assistant-message-time {
    font-size: 11px;
    color: #8c97ab;
    margin-top: 6px;
    padding: 0 4px;
}

.assistant-quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.assistant-quick-btn {
    border: 1px solid #d7e5ff;
    background: #ffffff;
    color: #156CF7;
    padding: 9px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.assistant-quick-btn:hover {
    background: #156CF7;
    color: #ffffff;
    border-color: #156CF7;
}

.assistant-chat-footer {
    flex-shrink: 0;
    background: #fff;
    padding: 12px;
    border-top: 1px solid #eee;
}

.assistant-chat-form {
    display: flex;
    align-items: center;
    gap: 8px;
}

.assistant-chat-input {
    flex: 1;
    resize: none;
    min-height: 48px;
    max-height: 120px;
    border: 1px solid #dbe7ff;
    border-radius: 16px;
    padding: 12px 14px;
    font-size: 14px;
    line-height: 1.4;
    outline: none;
    background: #f9fbff;
}
 
/* .assistant-chat-input {
    flex: 1;
    height: 44px;
    border-radius: 12px;
} */

.assistant-chat-input:focus {
    border-color: #156CF7;
    box-shadow: 0 0 0 3px rgba(21, 108, 247, 0.10);
    background: #ffffff;
}

.assistant-send-btn {
    width: 44px;
    height: 44px;
    border: 0;
    border-radius: 12px;
    background: #156CF7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 10px 20px rgba(21, 108, 247, 0.24);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex-shrink: 0;
}

.assistant-send-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(21, 108, 247, 0.30);
}

.assistant-footer-note {
    font-size: 11px;
    color: #8b95a7;
    margin-top: 10px;
    padding-left: 4px;
}

@media (max-width: 768px) {
    .floating-assistant-btn {
        right: 14px;
        bottom: 76px;
        width: 88px;
        height: 118px;
        border-radius: 18px;
    }

    .assistant-chat-panel {
        right: 12px;
        left: 12px;
        bottom: 12px;
        width: auto;
        height: 72vh;
        max-height: 72vh;
    }

    .assistant-chat-card {
        border-radius: 22px;
    }
}
</style>