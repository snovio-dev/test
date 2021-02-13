<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CheckForDisabledIp
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->ip(), config('disabledIps'))) {
            Log::channel('reg-error')
                ->error("`{$request->ip()}` had been DISABLED but tried to register");

            return response('You IP was disabled', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
