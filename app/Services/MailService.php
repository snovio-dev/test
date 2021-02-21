<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationEmail;
use App\User;

class MailService extends Service
{
    /**
     * MailService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param User $user
     */
    public function sendRegisterConfirmationEmail(User $user)
    {
        Mail::to($user)->send(new ConfirmationEmail());
    }
}
