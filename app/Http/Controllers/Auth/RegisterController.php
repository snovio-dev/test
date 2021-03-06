<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCreateRequest;
use App\Mail\ConfirmationEmail;
use App\Services\DomainService;
use App\Services\IpService;
use App\UserList;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function create(RegisterCreateRequest $request, IpService $ipService): JsonResponse
    {
        $data = $request->all();
        if ($ipService->isEnabled($this->getIpAddress())) {
            $emailParts = explode('@', $data['email']);
            if (!$this->isBannedDomain($emailParts[1])) {
                $user = new User();
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $isSaved = $user->save();

                if ($isSaved) {
                    Mail::to($user)->queue(new ConfirmationEmail());

                    $userList = new UserList();
                    $userList->user_id = $user->id;
                    $userList->name = 'First email addresses list';
                    $userList->save();

                    Log::channel('registration-success')->info('Registration success!', $data);

                    return response()->json('ok');
                }
            }
        }

        Log::channel('registration-error')->error('Registration error!', $data);

        return response()->json('error', 500);
    }

    private function getIpAddress(): string
    {
        $ip = '';
        foreach ([
                     'HTTP_CF_CONNECTING_IP',
                     'REMOTE_ADDR',
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED'
                 ] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $ip;
    }

    private function isBannedDomain(string $domain): bool
    {
        /** @var DomainService $domainService */
        $domainService = resolve(DomainService::class);
        return $domainService->isBanned($domain);
    }
}
