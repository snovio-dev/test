<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\BannedDomain;
use App\Mail\ConfirmationEmail;
use App\UserList;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    private const DISABLED_IPS = [
        '123.12.12.342',
        '121.1.5.11'
    ];

    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email|unique:user|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails() ||
            in_array($request->ip(), self::DISABLED_IPS) ||
            $this->isBannedDomain(substr($data['email'], strpos($data['email'], '@')))
        ) {
            return $this->returnErrorResponse();
        }

        try {
            DB::transaction(function () use ($data) {
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->save();

                $user_list = new UserList();
                $user_list->user_id = $user->id;
                $user_list->name = 'First email addresses list';
                $user_list->save();
                Mail::to($user)->send(new ConfirmationEmail());
            });
        } catch (\Exception $e) {
            return $this->returnErrorResponse();
        }

        file_put_contents(storage_path("logs/registration-success" . date('Y-m-d') . '.log'),
            print_r($data, true), FILE_APPEND | LOCK_EX);

        return response()->json('ok', 200);

    }


    private function isBannedDomain($domain): bool
    {
        return in_array($domain, BannedDomain::all()->toArray(), true);
    }

    private function returnErrorResponse() {
        file_put_contents(storage_path("logs/registration-error" . date('Y-m-d') . '.log'), print_r($data, true),
            FILE_APPEND | LOCK_EX);

        return response()->json('error', 500);
    }
}
