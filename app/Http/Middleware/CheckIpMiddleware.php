<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check allow ip
 */
class CheckIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @throws HttpResponseException
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->getClientIp();

        $disabledIps = config('app.disabled_ip_register');
        if ($disabledIps) {
            $disabledIps = explode('|', $disabledIps);
        }

        if (!$clientIp || ($clientIp && $disabledIps && in_array($clientIp, $disabledIps))){
            Log::warning('Ip is not allow or system can not check your ip: ' . $clientIp);
            throw new HttpResponseException(new Response(
                'Your ip is not allow or system can not check your ip',
                403
            ));
        }

        return $next($request);
    }
}
