<?php 

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class IpHelper
{
    public static function getPublicIp()
    {
        try {
            $client = new Client(['verify' => false]);
            $response = $client->request('GET', 'https://api.ipify.org?format=json');
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['ip'];
        } catch (RequestException $e) {
            // Manejar la excepción o devolver una IP por defecto
            return '0.0.0.0';
        }
    }
}
