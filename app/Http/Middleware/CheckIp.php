<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\HttpService;
use Illuminate\Http\Response;

class CheckIp
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!(new HttpService())->isValidIp()) {
            response()->json('error', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
