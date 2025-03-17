<?php

namespace Sujal\Chatx\Providers;

use Illuminate\Support\ServiceProvider;

class ChatPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'chat-package');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/chat-package'),
        ], 'chat-package-assets');
        

        // Publish config
        $this->publishes([
            __DIR__.'/../config/chatX.php' => config_path('chatX.php'),
        ], 'chatX-config');
        $this->publishes([
            __DIR__.'/../Models' => app_path('Models'),
        ], 'chatx-models');
        $this->publishes([
            __DIR__ . '/../resources/css' => public_path('vendor/chat-package/css'), __DIR__ . '/../resources/images' => public_path('vendor/chat-package/images')
        ], 'chat-package-assets');
       
        
    }

    public function register()
    {
        // Register any bindings or services
        if (!class_exists(\Laravel\Passport\Passport::class)) {
            throw new \Exception('Please install Laravel Passport: composer require laravel/passport');
        }

        // Check if Firebase package is installed
        if (!class_exists(\Kreait\Laravel\Firebase\FirebaseServiceProvider::class)) {
            throw new \Exception('Please install Firebase package: composer require kreait/laravel-firebase');
        }

        // Check if Pusher package is installed
        if (!class_exists(\Pusher\Pusher::class)) {
            throw new \Exception('Please install Pusher package: composer require pusher/pusher-php-server');
        }
        
    }
}