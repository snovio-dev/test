<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegisterService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param RegisterService $registerService
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RegisterRequest $request, RegisterService $registerService)
    {
        $data = $request->all();

        try {
            $registerService->create($data);

            Log::channel('registration-success')->info('succes', $data);
        } catch (\Exeption $e) {
            Log::channel('registration-error')->error($e->getMessage(), $data);

            return response()->json('error', Response::HTTP_BAD_REQUEST);
        }

        return response()->json('ok', Response::HTTP_OK);
    }
}
