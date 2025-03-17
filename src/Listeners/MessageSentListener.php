<?php

namespace Sujal\Chatx\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Sujal\Chatx\Events\MessageSent;

class MessageSentListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event)
    {
        // Handle the event logic, e.g., send an email, log the event, etc.
        \Log::info("Message received: " . $event->message);
    }
}


