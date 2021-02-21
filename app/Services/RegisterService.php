<?php

namespace App\Services;

class RegisterService extends Service
{
    /**
     * RegisterService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $userService = new UserService();
        $user = $userService->create($data);

        if ($user) {
            $mailService = new MailService();
            $mailService->sendRegisterConfirmationEmail($user);
        }
    }
}
