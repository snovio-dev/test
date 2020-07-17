<?php

namespace App\Service;

class IpAddressService
{
    public function getIpAddress(): string
    {
        $ip = '';
        foreach ([
                     'HTTP_CF_CONNECTING_IP',
                     'REMOTE_ADDR',
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED'
                 ] as $key) {
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
}
