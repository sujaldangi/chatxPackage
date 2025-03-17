<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | This section contains the Firebase credentials for realtime database and
    | authentication.
    |
    */
    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY', 'your-firebase-api-key'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'your-firebase-auth-domain'),
        'database_url' => env('FIREBASE_DATABASE_URL', 'your-firebase-database-url'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'your-firebase-project-id'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'your-firebase-storage-bucket'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', 'your-firebase-messaging-sender-id'),
        'app_id' => env('FIREBASE_APP_ID', 'your-firebase-app-id'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pusher Configuration
    |--------------------------------------------------------------------------
    |
    | This section contains the Pusher credentials for realtime chat functionality.
    |
    */
    'pusher' => [
        'key' => env('PUSHER_APP_KEY', 'your-pusher-key'),
        'secret' => env('PUSHER_APP_SECRET', 'your-pusher-secret'),
        'app_id' => env('PUSHER_APP_ID', 'your-pusher-app-id'),
        'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
        'use_tls' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Chat Default Settings
    |--------------------------------------------------------------------------
    |
    | This section contains default settings for the chat application.
    |
    */
    'chat' => [
        'max_message_length' => 1000, // Maximum allowed characters in a message
        'message_pagination' => 15, // Number of messages to load per page
        'default_avatar' => 'vendor/chatX/images/default-avatar.png', // Default user avatar
    ],
];