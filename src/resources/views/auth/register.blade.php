<!-- resources/views/auth/register.blade.php -->
<style>
    /* Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #333;
        color: black;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: aliceblue;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: black;
        margin-bottom: 30px;
    }

    /* Form Styles */
    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form input[type="file"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid black;
        color: black;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form input[type="password"]:focus {
        border-color: darkgray;
        outline: none;
    }

    form input[type="file"] {
        padding: 5px;
        background-color: #f1f1f1;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: black;
        color: azure;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: darkgray;
    }

    /* Validation Error Styles */
    .text-danger {
        font-size: 14px;
        color: #dc3545;
        margin-top: 5px;
    }

    /* Alert for validation errors */
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .alert-danger ul {
        list-style-type: none;
    }

    .alert-danger li {
        margin: 5px 0;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
    }

    /* HTML: <div class="loader"></div> */
    .loader {
        width: fit-content;
        font-size: 17px;
        font-family: monospace;
        line-height: 1.4;
        font-weight: bold;
        --c: no-repeat linear-gradient(#000 0 0);
        background: var(--c), var(--c), var(--c), var(--c), var(--c), var(--c), var(--c);
        background-size: calc(1ch + 1px) 100%;
        border-bottom: 10px solid #0000;
        position: relative;
        animation: l8-0 3s infinite linear;
        clip-path: inset(-20px 0);
        margin: auto;
        margin-top: 25%;
    }

    .loader::before {
        content: "Loading";
    }

    .loader::after {
        content: "";
        position: absolute;
        width: 10px;
        height: 14px;
        background: #25adda;
        left: -10px;
        bottom: 100%;
        animation: l8-1 3s infinite linear;
    }

    #loaderDiv {
        display: block;
        position: fixed;
        margin: 0px;
        padding: 0px;
        right: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background-color: #ddd;
        z-index: 30001;
        opacity: 0.8;
    }

    @keyframes l8-0 {

        0%,
        12.5% {
            background-position: calc(0*100%/6) 0, calc(1*100%/6) 0, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        25% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 0, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        37.5% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        50% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        62.5% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        75% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 0, calc(6*100%/6) 0
        }

        87.4% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 40px, calc(6*100%/6) 0
        }

        100% {
            background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 40px, calc(6*100%/6) 40px
        }
    }

    @keyframes l8-1 {
        100% {
            left: 115%
        }
    }
</style>
<div id="loaderDiv">
    <div id="loader" class="loader"></div>
</div>
<div class="container">
    <h2>Register</h2>



    <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- User Information -->
        @error('first_name')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">

        @error('last_name')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">

        @error('email')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">

        @error('phone_number')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="text" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">

        @error('status')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="text" name="status" placeholder="Status" value="{{ old('status') }}">

        @error('password')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="password" name="password" placeholder="Password">

        @error('password_confirmation')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        @error('picture')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        <input type="file" name="picture">

        <!-- Hidden input to hold Firebase device token -->
        <input type="hidden" name="device_token" id="device_token" value="{{ old('device_token') }}">

        <button type="submit">Register</button>
    </form>
</div>


<script type="module">
    window.onload = function () {
        setTimeout(function () {
            document.getElementById('loaderDiv').style.display = 'none';
        }, 1500);
    };
    import {
        initializeApp
    } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js';
    import {
        getMessaging,
        getToken
    } from 'https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging.js';

    const firebaseConfig = {
        apiKey: "AIzaSyB7gZBD1Vw-lW0vPkC22vAuN8oqCcIZJHA",
        authDomain: "lumenapi-11b39.firebaseapp.com",
        projectId: "lumenapi-11b39",
        storageBucket: "lumenapi-11b39.firebasestorage.app",
        messagingSenderId: "220455378166",
        appId: "1:220455378166:web:ba8a176de522a48f60e0d7",
        measurementId: "G-SX11TL34RF",
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    // Register the service worker when the document is ready
    document.addEventListener('DOMContentLoaded', () => {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register('/firebase-messaging-sw.js') // Path to the service worker
                .then(function (registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch(function (err) {
                    console.log('Service Worker registration failed:', err);
                });
        }

        // Get device token
        getDeviceToken();
    });

    // Function to get device token
    async function getDeviceToken() {
        try {
            const token = await getToken(messaging, {
                vapidKey: 'BHctJ1-cs9u8_VVSuhsBGwFXLUpaz6apaBXutBuKrbTICYqI3ZJzo8zZv1_gfZtQ6W3sERouJj7T1pbrTlfAM5g',
            });
            // Set token in hidden input field
            document.getElementById('device_token').value = token;
            console.log('Device token:', token);
        } catch (error) {
            console.error('Error getting device token:', error);
        }
    }
    
</script>