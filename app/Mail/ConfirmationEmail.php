<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/**
 * Confirmation email class
 */
class ConfirmationEmail extends Mailable
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auth.registered');
    }
}
