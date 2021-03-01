<?php
declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\{UserList, User};
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseMessages;
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Requests\Auth\RegisterFormRequest;

class RegisterController extends Controller
{
    use ResponseMessages;

    public function create(RegisterFormRequest $request): array
    {
        $data = [
            'email'    => $request->get('email'),
            'name'     => $request->get('name'),
            'password' => $request->get('password'),
        ];

        DB::beginTransaction();
        try {
            $user = User::create($data);

            $data = [
                'user_id' => $user->id,
                'name'    => 'First email addresses list',
            ];

            UserList::create($data);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            Log::channel('register_error')->error('Registration error', $request->all());

            return $this->error();
        }

        event(new UserRegistered($user, $request->all()));

        return $this->success();
    }
}
