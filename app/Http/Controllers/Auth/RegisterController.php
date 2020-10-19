<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Mail;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * RegisterController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param RegisterRequest $request
     * @return mixed
     */
    public function create(RegisterRequest $request)
    {
        if ($user = $this->authService->register($request)) {
            return \Response::json('success', 200);
        } else {
            return \Response::json('error', 400);
        }
    }
}
