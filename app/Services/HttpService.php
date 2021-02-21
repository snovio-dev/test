<?php

namespace App\Services;

use App\BannedDomain;
use App\Ip;

class HttpService extends Service
{
    private const IP_HEADERS = [
        'HTTP_CF_CONNECTING_IP',
        'REMOTE_ADDR',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED'
    ];

    /**
     * HttpService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $domain
     * @return bool
     */
    private function isBannedDomain(string $domain): bool
    {
        $bannedDomain = BannedDomain::where('domain', $domain)->first();

        return !empty($bannedDomain);
    }

    /**
     * @param string $ip
     * @return bool
     */
    private function isDisabledIp(string $ip): bool
    {
        $disabledIp = Ip::where('ip', $ip)->where('disabled', 1)->first();

        return !empty($disabledIp);
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        $ip = '';

        foreach (self::IP_HEADERS as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $ip;
    }

    /**
     * @return bool
     */
    public function isValidIp(): bool
    {
        return !$this->isDisabledIp($this->getIpAddress());
    }

    /**
     * @param string $email
     * @return bool
     */
    public function isValidEmailDomain(string $email): bool
    {
        return !$this->isBannedDomain(explode('@', $email)[1]);
    }
}
