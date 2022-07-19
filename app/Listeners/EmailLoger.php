<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class EmailLoger
{

    /**
     * Handle the event.
     *
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        $message = $event->message;
        // Log::info($message->getBody());
        // echo ($message->getBody());
    }
}
