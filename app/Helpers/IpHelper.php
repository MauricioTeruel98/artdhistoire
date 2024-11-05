<?php

namespace App\Helpers;

class IpHelper
{
    public static function getPublicIp()
    {
        $ip = request()->ip();
        
        if (!$ip) {
            $ip = request()->server('HTTP_X_FORWARDED_FOR');
        }
        
        if (!$ip) {
            $ip = request()->server('REMOTE_ADDR');
        }

        // Si hay múltiples IPs (proxy), tomar la primera
        if (strpos($ip, ',') !== false) {
            $ips = explode(',', $ip);
            $ip = trim($ips[0]);
        }

        return $ip ?: '0.0.0.0';
    }
}