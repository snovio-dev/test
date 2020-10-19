<?php

namespace App\Listeners;

use App\Services\Notifications\NotificationService;

/**
 * Class RegistredUserListener
 * @package App\Listeners
 */
class RegistredUserListener
{
    /**
     * @var NotificationService
     */
    protected $service;

    /**
     * PasswordChangedListener constructor.
     * @param NotificationService $service
     */
    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $user
     */
    public function handle($user)
    {
        $this->service->send($user);
    }
}
