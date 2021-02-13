<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Services\RegisterUserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Create User
     *
     * @param RegisterUserRequest $request
     * @param RegisterUserService $registerUserService
     * @return JsonResponse
     */
    public function create(
        RegisterUserRequest $request,
        RegisterUserService $registerUserService
    ): JsonResponse {
        try {
            $data = $request->validated();

            $user = $registerUserService->execute($data);

            return response()->json($user, Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' catch error ' . $e->getMessage());

            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
