<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\{Log, Mail};

class UserRegisterSendMail
{
    /**
     * Handle the event.
     *
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user)->send(new ConfirmationEmail());

        Log::channel('register_success')->error('Registration success', $event->requestParams);
    }
}
