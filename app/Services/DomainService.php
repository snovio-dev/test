<?php

declare(strict_types=1);

namespace App\Services;

use App\BannedDomain;

class DomainService
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
     * @param string $email
     *
     * @return bool
     */
    public function checkAllowIpAndDomain(string $email): bool
    {
        return $this->isIpAllow() && !$this->isDomainBanned($email);
    }

    public function isDomainBanned(string $email): bool
    {
        $domain = $this->getDomainFromEmail($email);
        return (bool)BannedDomain::whereDomain($domain)->first();
    }

    /**
     * @return bool
     */
    public function isIpAllow(): bool
    {
        return !in_array($this->getIpAddress(), $this->getDisabledIps(), true);
    }

    /**
     * @param string $email
     *
     * @return null|string
     */
    public function getDomainFromEmail(string $email): ?string
    {
        $parts = explode('@', $email);
        return $parts[1] ?? null;
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
