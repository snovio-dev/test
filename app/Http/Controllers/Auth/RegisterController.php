<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserRegistrationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Services\UserRegistrationService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    private $userRegistrationService;

    public function __construct(UserRegistrationService $userRegistrationService)
    {
        $this->userRegistrationService = $userRegistrationService;
    }

    public function create(UserRequest $request)
    {
        try {
            $data = $request->all();
            if ($this->userRegistrationService->register($data)) {
                return new JsonResponse('ok', Response::HTTP_CREATED);
            }
        } catch (UserRegistrationException $exception) {
            // do something
        }

        return new JsonResponse('error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
