<?php

namespace App\Observers;

use App\User;

/**
 * Eloquent Events for user model
 */
class UserObserver
{
    /**
     * @param User $user
     */
    public function creating(User $user)
    {
        $user->password = bcrypt($user->password);
    }
}
