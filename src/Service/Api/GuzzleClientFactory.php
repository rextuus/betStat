<?php


namespace App\Service\Api;


use GuzzleHttp\Client;

class GuzzleClientFactory
{
    /**
     * @param array $headers
     * @param string $baseUri
     *
     * @return Client
     */
    public function createClient(array $headers, string $baseUri){
        return new Client(['base_uri' => $baseUri, 'headers' => $headers]);
    }
}
