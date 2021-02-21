<?php

namespace App\Services;

use App\User;

class UserService extends Service
{
    /**
     * UserService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = bcrypt($data['password']);

        $user = new User();
        $user->fill($data);
        $user->save();

        return User::find($user->id);
    }
}
