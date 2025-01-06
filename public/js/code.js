// app.js
document.addEventListener('DOMContentLoaded', function () {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('messages');

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        // Get the message from the input
        const messageText = messageInput.value.trim();
        
        if (messageText) {
            // Display the user's message
            const userMessage = document.createElement('div');
            userMessage.classList.add('message');
            userMessage.innerText = `You: ${messageText}`;
            messagesContainer.appendChild(userMessage);

            // Scroll to the latest message
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Send the message to the server
            fetch('/send-message', {
                method: 'POST',
                body: JSON.stringify({ message: messageText }),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  // Display the ChatGPT response
                  const replyMessage = document.createElement('div');
                  replyMessage.classList.add('message');
                  replyMessage.innerText = `ChatGPT: ${data.reply}`;
                  messagesContainer.appendChild(replyMessage);
                  messagesContainer.scrollTop = messagesContainer.scrollHeight;
              });

            messageInput.value = '';  // Clear input
        }
    });
});
