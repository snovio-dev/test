<?php

namespace App\Http\Controllers\Auth;

use App\BannedDomain;
use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmail;
use App\User;
use App\UserList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    private const SERVER_PARAMS = [
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED'
    ];
    //TODO: use as env variable or store in database
    private const DISABLED_IPS = [
        '123.12.12.342',
        '121.1.5.11'
    ];

    //TODO: Add responses according to rest api
    public function create(Request $request)
    {
        $data = $request->validate(
            [
                'email' => 'required|email',
                //TODO: add strong password validation
                'password' => 'required|string',
                'name' => 'required|string',
            ]
        );

        $email = substr($data['email'], strpos($data['email'], '@') + 1);

        if (
            !in_array($this->getIpAddress($request), self::DISABLED_IPS)
            && false === $this->isBannedDomain($email)
        ) {
            //TODO: use generative patterns
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            //TODO: see https://laravel.com/docs/7.x/hashing to generate password
            $user->password = bcrypt($data['password']);

            if (true === $user->save()) {
                //TODO: add event dispatcher
                Mail::to($user)->send(new ConfirmationEmail());

                //TODO: add relation to user or make transaction
                $userList = new UserList();
                $userList->user_id = $user->id;
                //TODO: use constant
                $userList->name = 'First email addresses list';
                $userList->save();

                $this->log('logs/registration-success', $data);

                return response()->json('ok', Response::HTTP_CREATED);
            }
        }

        $this->log('logs/registration-error', $data);

        //TODO: add errors to response
        return response()->json('error', Response::HTTP_BAD_REQUEST);
    }

    //TODO: use middleware
    private function getIpAddress(Request $request): string
    {
        $ip = '';

        foreach (
            array_intersect_key(
                $request->server->getHeaders(),
                array_flip(self::SERVER_PARAMS)
            ) as $header
        ) {
            foreach (explode(',', $header) as $ip) {
                if (false !== filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $ip;
    }

    //TODO: use middleware
    private function isBannedDomain($domain): bool
    {
        //TODO: add repository method & use mysql "exists" method to best performance
        return in_array($domain, BannedDomain::all()->toArray(), true);
    }

    //TODO: add logger according to https://laravel.com/docs/7.x/logging
    private function log(string $filename, array $data): void
    {
        file_put_contents(
            storage_path($filename . date('Y-m-d') . '.log'),
            print_r($data, true),
            FILE_APPEND | LOCK_EX
        );
    }
}
