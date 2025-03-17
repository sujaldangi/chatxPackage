<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('vendor/chat-package/css/dashboard.css') }}">



</head>

<body data-theme="">
    <div id="loaderDiv">
        <div id="loader" class="loader"></div>
    </div>
    <header>

        <!--<h1>Welcome to Dashboard</h1>-->
        <img src="{{ asset('vendor/chat-package/images/chatXlogo.png') }}" alt="" width="7%">


        <header>
            <img src="{{ asset('vendor/chat-package/images/chatXlogo.png') }}" alt="" width="7%">
            <div class="user-menu">
                <button id="menu-button" onclick="toggleMenu()">☰</button>
                <div id="menu-dropdown" class="dropdown-content">
                    <a href="#" onclick="openProfileModal()">Profile Details</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" id="logoutButton">Logout</button>
                    </form>
                    <button id="theme-toggle">Toggle Theme</button>
                </div>
            </div>
        </header>
    </header>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- Profile Modal -->
    <div id="profileModal">
        <div class="modal-content">
            <span onclick="closeProfileModal()">&times;</span>
            <h2>Profile Details</h2>
            <div id="profile-details">
                <img id="profile-picture" src="" alt="Profile Picture">
                <p><strong>Name:</strong> <span id="profile-name"></span></p>
                <p><strong>Email:</strong> <span id="profile-email"></span></p>
                <p><strong>Phone Number:</strong> <span id="profile-phone"></span></p>
                <p><strong>Status:</strong> <span id="profile-status"></span></p>
            </div>
        </div>
    </div>

    <!-- Sidebar for Users and Chat List -->
    <div id="sidebar">
        <button id="new-chat-btn" onclick="openUserModal()">New Chat</button>
        <div id="user-list"></div>
        <div id="chat-list"></div>
    </div>

    <!-- Modal for User List -->
    <div id="userModal">
        <div class="modal-content">
            <span onclick="closeUserModal()">&times;</span>
            <h2>Select a User</h2>
            <div id="user-list-modal"></div>
        </div>
    </div>

    <!-- Chat Box (Visible after selecting a chat) -->
    <div id="chat-box">
        <div id="chat-header">
            <a href="#" onclick="openProfileModalUser()">
                <div id="imagePpf"></div>
            </a>
            <div id="staus-bar"></div>
        </div>

        <div id="messages"></div>
        <emoji-picker id="emoji-picker"></emoji-picker>
        <div style="display: flex; align-items: center;">
            <input type="text" id="message-input" placeholder="Type a message..." />
            <button onclick="toggleEmojiPicker()" id="chatboxButtonEmoji" style="display:flex !important;">😀</button>
            <button onclick="document.getElementById('file-input').click()" id="chatboxButtonFile"><i
                    class="material-icons">attachment</i></button>
            <button id="gif-picker-button"
                style="display: flex; padding: 10px; background-color: black; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px;">
                GIF
            </button>
        </div>


        <input type="file" id="file-input" style="display:none;" />
        <button onclick="sendMessage()" id="chatboxButton">Send</button>

        <div id="gif-picker"
            style="display: none; position: absolute; bottom: 19%; background-color: #333; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); z-index: 1000;">
            <span onclick="closeGifPicker()" style="color: red;">&times;</span>

            <input type="text" id="gif-search" placeholder="Search GIFs..."
                style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <div id="gif-results"
                style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-height: 300px; overflow-y: auto;">
                <!-- GIFs will be displayed here -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchChats(); // Fetch chats on page load
        });
        document.getElementById('message-input').addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });
        Pusher.logToConsole = true;
        var pusher = new Pusher('24c0536b2bb45e29a90e', {
            cluster: 'ap2'
        });
        const userId = "{{ auth()->user()->id }}";
        const chatListChannel = pusher.subscribe(`chat-list.${userId}`);

        chatListChannel.bind('MessageSent', function (data) {
            console.log('New message sent:', data);
            fetchChats(); // Refresh the chat list
        });


        function openUserModal() {
            document.getElementById("userModal").style.display = "block";
            fetchUsers();
        }

        function closeUserModal() {
            document.getElementById("userModal").style.display = "none";
        }

        function fetchUsers() {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';
            fetch('{{ route('fetch.users') }}')
                .then(response => response.json())
                .then(data => {
                    const userListContainer = document.getElementById("user-list-modal");
                    userListContainer.innerHTML = "";

                    data.users.forEach(user => {
                        const userDiv = document.createElement("div");
                        userDiv.classList.add("user");
                        const imageUrl = user.picture ? `/storage/${user.picture}` :
                            '{{ asset('vendor/chat-package/images/default.webp') }}';
                        userDiv.innerHTML = `
                        <img src="${imageUrl}" alt="User"/>
                        <span>${user.first_name} ${user.last_name}</span>
                    `;
                        userDiv.addEventListener("click", () => startChat(user.id));
                        userListContainer.appendChild(userDiv);
                    });
                })
                .catch(error => console.log('Error fetching users:', error))
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        function startChat(receiverId) {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';
            fetch('{{ route('start.chat') }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sender_id: "{{ auth()->user()->id }}",
                    receiver_id: receiverId
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    closeUserModal();
                    fetchChats();
                })
                .catch(error => console.log("Error starting chat:", error))
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        function fetchChats() {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';
            fetch('{{ route('get.chats') }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id: "{{ auth()->user()->id }}"
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.chats.length > 0) {
                        displayChats(data.chats);
                    } else {
                        document.getElementById("chat-list").innerHTML = "<p>No chats available.</p>";
                    }
                })
                .catch(error => console.log("Error fetching chats:", error))
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        function displayChats(chats) {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';
            const chatListContainer = document.getElementById("chat-list");
            chatListContainer.innerHTML = "";
            const currentUserName = "{{ auth()->user()->first_name }}";
            chats.forEach(chat => {
                const chatDiv = document.createElement("div");
                chatDiv.classList.add("chat");
                let chatName = chat.name_p1;
                if (currentUserName === chat.name_p1) {
                    chatName = chat.name_p2;
                } else if (currentUserName === chat.name_p2) {
                    chatName = chat.name_p1;
                }
                let content = chat.last_message;
                if (content.startsWith('http')) {
                    content = `File shared`;
                }
                const receiverId = chat.participants[0] == "{{ auth()->user()->id }}" ? chat.participants[1] : chat
                    .participants[0];
                chatDiv.innerHTML = `
                <p><strong>Chat with:</strong> ${chatName}</p>
                <p><strong>Last Message:</strong> ${content}</p>
                <button onclick="openChat(${chat.id}, ${receiverId})">Open Chat</button>
            `;
                chatListContainer.appendChild(chatDiv);
                loader.style.display = 'none';
            });
        }

        function openChat(chatId, receiverId) {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';

            window.currentReceiverId = receiverId;

            // Show the chat box, input field, and send button
            document.getElementById("chat-box").style.display = "flex";
            document.getElementById("message-input").style.display = "block";
            document.querySelector("#chatboxButton").style.display = "block";
            document.querySelector("#chatboxButtonFile").style.display = "block";

            const userId = "{{ auth()->user()->id }}";

            fetch(`/get-chat-messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    userId: "{{ auth()->user()->id }}",
                    receiverId: receiverId
                })
            })
                .then(response => response.json())
                .then(data => {
                    const imageDiv = document.getElementById("imagePpf");
                    const imageUrl = data.chats.picture ? `/storage/${data.chats.picture}` : '{{ asset('vendor/chat-package/images/default.webp') }}';
                    imageDiv.innerHTML = `
                        <img src="${imageUrl}" alt="User"/>
                    `;

                    const chatUserName = data.chats.user_name_chat;
                    const statusBarContainer = document.getElementById("staus-bar");
                    fetch('{{ route('check.user.status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            user_id: receiverId
                        })
                    })
                        .then(response => response.json())
                        .then(statusData => {
                            const status = statusData.isOnline ? 'Online' : 'Offline';
                            if (statusData.isOnline) {
                                statusBarContainer.innerHTML =
                                    ` ${chatUserName} <span style="color: green;">${status}</span>`;
                            } else {
                                statusBarContainer.innerHTML =
                                    ` ${chatUserName} <span style="color: gray;">${status}</span>`;
                            }

                        })
                        .catch(error => {
                            console.log("Error checking user status:", error);
                            statusBarContainer.innerHTML = ` ${chatUserName} Offline`;
                        });

                    displayMessages(data.chats);
                    const messagesContainer = document.getElementById("messages");
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    subscribeToPusherChannel(receiverId);

                })
                .catch(error => console.log("Error fetching messages:", error))
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        function subscribeToPusherChannel(receiverId) {
            const senderId = {{ auth()->user()->id }};
            const channelName = 'chat.' + Math.min(senderId, receiverId) + '.' + Math.max(senderId, receiverId);
            console.log('Subscribing to channel:', channelName);

            // Unbind any existing event listeners
            if (window.pusherChannel) {
                window.pusherChannel.unbind('MessageSent');
            }

            // Subscribe to the channel
            window.pusherChannel = pusher.subscribe(channelName);

            // Bind the event listener
            window.pusherChannel.bind('MessageSent', function (data) {
                console.log('Message received via Pusher:', data);
                const message = data.message;
                console.log(message);

                const messageElement = document.createElement('div');
                const timestamp = new Date().toISOString().slice(0, 19).replace("T", " ");

                if (message.sender_id === "{{ auth()->user()->id }}") {
                    messageElement.classList.add('message', 'sent');
                } else {
                    messageElement.classList.add('message', 'received');
                }
                let content = message.content;
                let contentFinal = message.content;
                if (content.startsWith('http')) {
                    contentFinal = `<a href="${content}" target="_blank">Download File</a>`;
                }
                if (content.endsWith('png') || content.endsWith('jpg') || content.endsWith('jpeg') || content.endsWith('avif') || content.endsWith('svg') || content.endsWith('webp') || content.endsWith('gif') || content.includes('giphy.com')) {
                    const imageUrl = `${message.content}`;

                    contentFinal = `<a href="${content}" target="_blank"><img src="${imageUrl}" width="185" height="160" alt="Img"/></a>`;
                }

                messageElement.innerHTML = `
            <p><strong>${message.sender_id === "{{ auth()->user()->id }}" ? "You" : 'User'}:</strong> ${contentFinal}</p>
            <p class="timestamp">${timestamp}</p>
        `;

                document.getElementById('messages').appendChild(messageElement);
                fetchChats();
            });
        }

        function sendMessage() {
            const loader = document.getElementById('loaderDiv');
            document.getElementById('message-input').readOnly = false;
            const messageInput = document.getElementById('message-input');
            const fileInput = document.getElementById('file-input');
            const senderId = "{{ auth()->user()->id }}";
            const receiverId = window.currentReceiverId;
            loader.style.display = 'block';

            const formData = new FormData();
            formData.append('sender_id', senderId);
            formData.append('receiver_id', receiverId);
            formData.append('chat_type', 'individual');

            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            } else if (messageInput.value.trim() !== '') {
                formData.append('message', messageInput.value);
            } else {
                alert('Please enter a message or select a file.');
                return;
            }

            fetch('{{ route('send.message') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Message sent:', data);
                    messageInput.value = '';
                    fileInput.value = '';
                    fetchChats();
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    if (error.errors && error.errors.file) {
                        alert(error.errors.file[0]); // Display the validation error message
                        document.getElementById('file-input').value = null;
                        document.getElementById('message-input').value = null;
                    } else {
                        alert('An error occurred while sending the message.');
                        document.getElementById('file-input').value = null;
                        document.getElementById('message-input').value = null;
                    }
                })
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        function displayMessages(chats) {
            const messagesContainer = document.getElementById("messages");
            messagesContainer.innerHTML = "";

            const messages = chats.messages || {};

            if (Object.keys(messages).length === 0) {
                messagesContainer.innerHTML = "<p>No messages yet.</p>";
            } else {
                Object.entries(messages).forEach(([key, message]) => {
                    const messageDiv = document.createElement("div");
                    messageDiv.classList.add("message");

                    if (message.sender_id === "{{ auth()->user()->id }}") {
                        messageDiv.classList.add("sent");
                    } else {
                        messageDiv.classList.add("received");
                    }
                    const timestamp = message.timestamp;

                    let content = message.content;
                    let contentFinal = message.content;
                    if (content.startsWith('http')) {
                        contentFinal = `<a href="${content}" target="_blank">Download File</a>`;
                    }
                    if (content.endsWith('png') || content.endsWith('jpg') || content.endsWith('jpeg') || content.endsWith('avif') || content.endsWith('svg') || content.endsWith('webp') || content.endsWith('gif') || content.includes('giphy.com')) {
                        const imageUrl = `${message.content}`;

                        contentFinal = `<a href="${content}" target="_blank"><img src="${imageUrl}" width="185" height="160" alt="Img"/></a>`;
                    }

                    messageDiv.innerHTML = `
                <p><strong>${message.sender_id === "{{ auth()->user()->id }}" ? "You" : "User"}:</strong> ${contentFinal}</p>
                <p class="timestamp">${timestamp}</p>
            `;
                    messagesContainer.appendChild(messageDiv);
                });
            }
        }

        function toggleMenu() {
            const dropdown = document.getElementById("menu-dropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function openProfileModal() {
            fetchUserDetails();
            document.getElementById("profileModal").style.display = "block";
            document.getElementById("menu-dropdown").style.display = "none";
        }
        function openProfileModalUser() {
            const receiverId = window.currentReceiverId;
            fetchUserDetails(receiverId);
            document.getElementById("profileModal").style.display = "block";
            document.getElementById("menu-dropdown").style.display = "none";
        }

        function closeProfileModal() {
            document.getElementById("profileModal").style.display = "none";
        }

        function fetchUserDetails(userId = null) {
            const loader = document.getElementById('loaderDiv');
            loader.style.display = 'block';
            const finalUserId = userId || "{{ auth()->user()->id }}";

            fetch('{{ route('user.details') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id: finalUserId,
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById("profile-picture").src = data.picture ? `/storage/${data.picture}` :
                        '{{ asset('vendor/chat-package/images/default.webp') }}';
                    document.getElementById("profile-name").textContent = `${data.first_name} ${data.last_name}`;
                    document.getElementById("profile-email").textContent = data.email;
                    document.getElementById("profile-phone").textContent = data.phone_number;
                    document.getElementById("profile-status").textContent = data.status;
                })
                .catch(error => console.error('Error sending message:', error))
                .finally(() => {
                    // Hide the loader regardless of success or failure
                    loader.style.display = 'none';
                });
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function (event) {
            if (!event.target.matches('#menu-button')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }
        function toggleEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
        }

        document.getElementById('emoji-picker').addEventListener('emoji-click', event => {
            const input = document.getElementById('message-input');
            input.value += event.detail.unicode;
        });
        document.getElementById('file-input').addEventListener('change', function (event) {
            const fileInput = event.target;
            const messageInput = document.getElementById('message-input');

            if (fileInput.files.length > 0) {
                // Set the value of the message input to the file name
                messageInput.value = fileInput.files[0].name;
                document.getElementById('message-input').readOnly = true;
            } else {
                // Clear the message input if no file is selected
                messageInput.value = '';
            }
        });
        const GIPHY_API_KEY = 'KEz6vyIJMrLVGPMfwXYHRG7bzH4UjWyw'; // Replace with your Giphy API key

        // Function to fetch and display GIFs
        async function fetchGifs(query) {
            const limit = 9; // Number of GIFs to display
            const offset = 0; // Pagination offset
            const url = `https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_API_KEY}&q=${query}&limit=${limit}&offset=${offset}`;

            try {
                const response = await fetch(url);
                const result = await response.json();
                const gifs = result.data;

                const gifResults = document.getElementById('gif-results');
                gifResults.innerHTML = ''; // Clear previous results

                gifs.forEach(gif => {
                    const gifElement = document.createElement('img');
                    gifElement.src = gif.images.fixed_height.url; // Use the GIF URL
                    gifElement.style.width = '100%';
                    gifElement.style.cursor = 'pointer';
                    gifElement.addEventListener('click', () => sendGif(gif.images.fixed_height.url));
                    gifResults.appendChild(gifElement);
                });
            } catch (error) {
                console.error('Error fetching GIFs:', error);
            }
        }

        // Function to send a GIF
        function sendGif(gifUrl) {
            const messageInput = document.getElementById('message-input');
            messageInput.value = gifUrl; // Set the GIF URL in the input field

            closeGifPicker(); // Close the GIF picker
        }

        // Function to open the GIF picker
        function openGifPicker() {
            const gifPicker = document.getElementById('gif-picker');
            gifPicker.style.display = 'block';
            fetchGifs(''); // Fetch trending GIFs initially
        }

        // Function to close the GIF picker
        function closeGifPicker() {
            const gifPicker = document.getElementById('gif-picker');
            gifPicker.style.display = 'none';
        }

        // Event listener for the GIF picker button
        document.getElementById('gif-picker-button').addEventListener('click', openGifPicker);

        // Event listener for the GIF search input
        document.getElementById('gif-search').addEventListener('input', (event) => {
            fetchGifs(event.target.value); // Fetch GIFs based on search query
        });
        // Function to toggle and save the theme
        function toggleTheme() {
            const body = document.body;
            if (body.getAttribute('data-theme') === 'light') {
                body.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark'); // Save preference
            } else {
                body.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light'); // Save preference
            }
        }
        


        // Apply saved theme on page load
        function applySavedTheme() {
            const savedTheme = localStorage.getItem('theme') || 'dark'; // Default to dark if no preference is saved
            document.body.setAttribute('data-theme', savedTheme);
        }

        // Initialize
        applySavedTheme(); // Apply the saved theme when the page loads
        document.getElementById('theme-toggle').addEventListener('click', toggleTheme); // Add event listener to the toggle button
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>