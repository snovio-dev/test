<?php

namespace App\Http\Middleware;

use App\Http\Traits\ResponseMessages;
use Closure;
use App\BannedDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckBannedDomain
{
    use ResponseMessages;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $domain = \strstr($request->get('email'), '@', true);

        if ($this->isBannedDomain($domain)) {
            Log::channel('register_error')->error('Registration error', $request->all());

            return $this->error();
        }

        return $next($request);
    }

    private function isBannedDomain(string $domain): bool
    {
        return BannedDomain::get(['domain'])->contains($domain);
    }
}
