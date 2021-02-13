<?php

namespace App\Services;

use App\Mail\ConfirmationEmail;
use App\Models\User;
use App\Models\UserList;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterUserService
{
    /**
     * @var ConfirmationEmail
     */
    private $confirmationEmail;

    /**
     * RegisterUserService constructor.
     * @param ConfirmationEmail $confirmationEmail
     */
    public function __construct(ConfirmationEmail $confirmationEmail)
    {
        $this->confirmationEmail = $confirmationEmail;
    }

    /**
     * @param array $data
     * @return User
     * @throws Exception
     */
    public function execute(array $data): User
    {
        DB::beginTransaction();

        try {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            Mail::to($user->email)->send($this->confirmationEmail);

            $this->createUserList($user);

            Log::channel('reg-success')
                ->info("user ID:{$user->id} was successfully created");

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param User $user
     */
    private function createUserList(User $user): void
    {
        UserList::create([
            'user_id' => $user->id,
            'name' => 'First email addresses list',
        ]);
    }
}
