<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\ConfirmationEmail;
use App\User;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function sendConfirmation(User $user): bool
    {
        return Mail::to($user)->send(new ConfirmationEmail());
    }
}
