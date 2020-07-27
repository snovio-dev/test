<?php

namespace App\Listeners;

use App\Events\RegisteredFinished;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;

/**
 * Send email to user
 */
class SendEmail
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
        Mail::to($user->email)->send(new ConfirmationEmail());
    }
}
