<?php
namespace App\Services;

use App\DisabledIp;

class IpService
{
    /**
     * @param string $ip
     * @return bool
     */
    public function isEnabled(string $ip): bool
    {
        return !in_array($ip, DisabledIp::all()->pluck('ip')->toArray(), true);
    }
}
