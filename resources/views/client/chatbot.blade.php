<div>
    <button id="chatbot-toggler">
        <img src="{{ asset('client/images/logo-icon-white.png') }}" alt="logo" width="30px" height="30px" class="logo">

    </button>

    <div class="chatbot-popup">
        <!-- Chatbot Header -->
        <div class="chat-header">
            <div class="header-info align-item-center">
                <div class="chatbot-logo">
                    <img src="{{ asset('client/images/logo-icon.png') }}" alt="logo" width="30px" height="30px"
                        class="logo">
                </div>

                <div class="logo-text">Trợ lý AI</div>
            </div>
            <button id="close-chatbot" class="btn-icon">
                <i class="fa-solid fa-angle-down fs-6"></i>
            </button>
        </div>

        <!-- Chatbot Body -->
        <div class="chat-body">
            <div class="message bot-message">
                <div class="bot-avatar">
                    <img src="{{ asset('client/images/logo-icon.png') }}" alt="logo" width="30px" height="30px"
                        class="logo">
                </div>
                <div class="message-text">
                    Xin chào 👋 <br />
                    Tôi có thể giúp gì cho bạn?
                </div>
            </div>
            <!-- Các câu hỏi gợi ý -->
            <div class="quick-questions">
                <p>👉 Bạn có thể chọn nhanh một câu hỏi sau:</p>
                @foreach (DB::table('suggested_questions')->get() as $item)
                    <button class="quick-question">{{ $item->question }}</button>
                @endforeach
            </div>
        </div>

        <!-- Chatbot Footer -->
        <div class="chat-footer">
            <form action="#" class="chat-form">
                <textarea placeholder="Message..." class="message-input" required></textarea>
                <div class="chat-controls">
                    {{-- <button type="button" id="emoji-picker" class="btn-icon">
                        <i class="fa-regular fa-face-smile"></i>
                    </button> --}}
                    <div class="file-upload-wrapper">
                        <input type="file" accept="images/*" id="file-input" hidden>
                        {{-- <button type="button" id="file-upload" class="btn-icon">
                            <i class="fa-solid fa-paperclip"></i>
                        </button> --}}
                    </div>
                    <button type="submit" id="send-message" class="btn-icon">
                        <i class="fa-solid fa-arrow-up fs-6"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- linking emoji mart script for emoji picker -->
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
</div>
