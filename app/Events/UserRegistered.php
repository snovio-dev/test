<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $requestParams;

    /**
     * Create a new event instance.
     * @param User $user
     * @param array $requestParams
     */
    public function __construct(User $user, array $requestParams)
    {
        $this->user = $user;
        $this->requestParams = $requestParams;
    }
}
