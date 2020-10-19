<?php

namespace App\Services\Notifications;

use App\Mail\ConfirmationEmail;

/**
 * Class NotificationService
 * @package App\Services\Notifications
 */
class NotificationService
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * AuthService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $user
     */
    public function send($user)
    {
        Mail::to($user)->send(new ConfirmationEmail());
    }
}
