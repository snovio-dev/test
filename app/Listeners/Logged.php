<?php

namespace App\Listeners;

use App\Events\RegisteredFinished;
use Illuminate\Support\Facades\Log;

/**
 * Logged success created users
 */
class Logged
{
    /**
     * Handle the event.
     *
     * @param  RegisteredFinished $event
     * @return void
     */
    public function handle(RegisteredFinished $event)
    {
        $user = $event->user;
        Log::info('User: ' . $user->email . ' success registered!');
    }
}
