<?php

namespace App\Events;

use App\User;

/**
 * Event when register finished and user created
 */
class RegisteredFinished
{
    /**
     * @var User
     */
    public $user;

    /**
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
