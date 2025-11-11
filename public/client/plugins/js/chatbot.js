const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessageButton = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");
let chatHistory = [];

const userData = {
    message: null,
    file: {
        data: null,
        mime_type: null,
    },
};

const initialInputHeight = messageInput.scrollHeight;

// Tạo element message
const createMessageElement = (content, ...classes) => {
    const div = document.createElement("div");
    div.classList.add("message", ...classes);
    div.innerHTML = content;
    return div;
};

// Gọi API backend để lấy bot response
const generateBotResponse = async (incomingMessageDiv) => {
    const messageElement = incomingMessageDiv.querySelector(".message-text");

    try {
        const response = await fetch("/chatbot/send", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                message: userData.message,
                file: userData.file.data
                    ? {
                        data: userData.file.data,
                        mime_type: userData.file.mime_type,
                    }
                    : null,
                history: chatHistory
            }),
        });

        const data = await response.json();
        chatHistory.push({
            role: "model",
            text: data.reply
        });

        if (!response.ok) throw new Error(data.message || "Lỗi server");

        // Hiển thị phản hồi bot
        messageElement.innerText = data.reply;
    } catch (error) {
        console.error(error);
        messageElement.innerText = error.message;
        messageElement.style.color = "#ff0000";
    } finally {
        incomingMessageDiv.classList.remove("thinking");
        chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
    }
};

// Xử lý tin nhắn user
const handleOutgoingMessage = (e) => {
    e.preventDefault();
    userData.message = messageInput.value.trim();
    if (!userData.message && !userData.file.data) return;

    // Lưu lịch sử hội thoại
    chatHistory.push({
        role: "user",
        text: userData.message
    });

    messageInput.value = "";
    messageInput.dispatchEvent(new Event("input"));

    // Hiển thị tin nhắn user
    const messageContent = `<div class="message-text"></div>
    ${userData.file.data
            ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />`
            : ""
        }`;

    const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
    outgoingMessageDiv.querySelector(".message-text").textContent = userData.message;
    chatBody.appendChild(outgoingMessageDiv);
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

    // Tạo khung bot "đang nghĩ"
    setTimeout(() => {
        const botMessage = `<div class="bot-avatar">
        <img src="/client/images/logo-icon.png" alt="logo" width="30px" height="30px" class="logo">
      </div>
      <div class="message-text">
        <div class="thinking-indicator">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
        </div>
      </div>`;

        const incomingMessageDiv = createMessageElement(botMessage, "bot-message", "thinking");
        chatBody.appendChild(incomingMessageDiv);
        chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

        generateBotResponse(incomingMessageDiv);
    }, 500);
};

// Enter để gửi
messageInput.addEventListener("keydown", (e) => {
    const userMessage = e.target.value.trim();
    if (e.key === "Enter" && userMessage && !e.shiftKey && window.innerWidth > 768) {
        handleOutgoingMessage(e);
    }
});

// Auto resize input
messageInput.addEventListener("input", () => {
    messageInput.style.height = `${initialInputHeight}px`;
    messageInput.style.height = `${messageInput.scrollHeight}px`;
});

// Upload file
fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        const base64String = e.target.result.split(",")[1];
        userData.file = { data: base64String, mime_type: file.type };
        fileInput.value = "";
    };
    reader.readAsDataURL(file);
});

// Nút gửi
sendMessageButton.addEventListener("click", (e) => handleOutgoingMessage(e));

// Toggle chatbot
chatbotToggler.addEventListener("click", () => {
    document.body.classList.toggle("show-chatbot");
});
closeChatbot.addEventListener("click", () => {
    document.body.classList.remove("show-chatbot");
});


//Xử lý khi chọn câu hỏi có sẵn
document.querySelectorAll('.quick-question').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.querySelector('.message-input');
        input.value = btn.textContent; // gán câu hỏi vào ô nhập
        document.getElementById('send-message').click(); // tự động gửi
    });
});
