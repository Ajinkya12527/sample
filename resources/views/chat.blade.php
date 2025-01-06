<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Retro Futuristic ChatGPT</title>
    <link rel="stylesheet" href="{{ asset('css/code.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="retro-container scanline-effect">
        <header>
            <h1>ChatGPT</h1>
        </header>

        <main>
            <section id="screen-1">
                <article>
                    <div class="content">
                        <div id="chat-box">
                            <div id="messages" class="message-container"></div>
                            <form id="chat-form">
                                <input type="text" id="message-input" placeholder="Type a message" required>
                                <button type="submit">Send</button>
                            </form>
                        </div>
                    </div>
                </article>
            </section>
        </main>

        <footer>
            <p>Powered by Laravel & ChatGPT</p>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.getElementById('chat-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const messageInput = document.getElementById('message-input');
            const userMessage = messageInput.value.trim();

            if (userMessage) {
                // Check if the message is "clear"
                if (userMessage.toLowerCase() === 'clear') {
                    // Clear chat history when "clear" is typed
                    clearChatHistory();
                    return; // Exit early so the "clear" message isn't sent
                }

                // Display user message in the chat
                displayMessage(userMessage, 'user');
                
                // Clear input field
                messageInput.value = '';

                // Send the message to the backend for processing
                fetch('{{ route('chat.api') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: userMessage })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.reply) {
                        // Display AI response in the chat
                        displayMessage(data.reply, 'ai');
                    } else {
                        displayMessage("Sorry, something went wrong.", 'ai');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    displayMessage("Error: Could not fetch response.", 'ai');
                });
            }
        });

        // Function to display messages in the chat
        function displayMessage(message, sender) {
            const messageContainer = document.getElementById('messages');
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', sender);
            messageElement.textContent = message;
            messageContainer.appendChild(messageElement);
            messageContainer.scrollTop = messageContainer.scrollHeight; // Scroll to bottom
        }

        // Function to clear the chat history
        function clearChatHistory() {
            const messageContainer = document.getElementById('messages');
            messageContainer.innerHTML = ''; // This removes all previous messages
        }
    </script>
</body>
</html>
