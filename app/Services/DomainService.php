<?php
namespace App\Services;

use App\BannedDomain;

class DomainService
{
    /**
     * @param string $domain
     * @return bool
     */
    public function isBanned(string $domain): bool
    {
        return in_array($domain, BannedDomain::all()->pluck('domain')->toArray(), true);
    }
}
