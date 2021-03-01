<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Traits\ResponseMessages;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckClientIp
{
    use ResponseMessages;

    private const DISABLED_IPS = [
        '123.12.12.342',
        '121.1.5.11',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->getClientIp(), self::DISABLED_IPS, true)) {
            Log::channel('register_error')->error('Registration error', $request->all());

            return $this->error();
        }

        return $next($request);
    }
}
