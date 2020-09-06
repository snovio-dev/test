<?php

declare(strict_types=1);

namespace App\Services;

class AllowIpService
{
    private const DISABLED_IPS = [
        '123.12.12.342',
        '121.1.5.11',
    ];

    private const SCAN_KEYS = [
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
    ];

    /**
     * @return bool
     */
    public function isIpAllow(): bool
    {
        return !in_array($this->getIpAddress(), $this->getDisabledIps(), true);
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        $ip = '';

        foreach (self::SCAN_KEYS as $key) {
            $ips = request()->server($key);
            if ($ips === null) {
                continue;
            }
            foreach (explode(',', $ips) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }

        return $ip;
    }

    /**
     * @return array
     */
    public function getDisabledIps(): array
    {
        return self::DISABLED_IPS;
    }
}
