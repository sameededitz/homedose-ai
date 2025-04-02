<?php

namespace App\Listeners;

use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;

class SendWelcomeEmailListener
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
    public function handle(Verified $event): void
    {
        $user = $event->user;
        Log::info("Dispatching Welcome Email Job for: " . $user->email);
        if ($user->hasVerifiedEmail()) {
            SendWelcomeEmail::dispatch($user)->delay(now()->addSeconds(5));
        }
    }
}
