<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegisteredFinished;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\JsonResponse;

/**
 * Register controller
 */
class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function create(RegisterRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::create($request->only(['name', 'email', 'password']));
        $user->userLists()->create(['name' => 'first']);

        event(new RegisteredFinished($user));

        return response()->json(['message' => 'user was created']);
    }
}
