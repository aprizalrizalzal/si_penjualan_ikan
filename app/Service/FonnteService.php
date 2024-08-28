<?php

namespace App\Service;

use GuzzleHttp\Client;

class FonnteService
{

    protected $client;
    protected $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('fonnte.api_key');
    }

    public function sendMessage($to, $message, $options = [])
    {
        $response = $this->client->post('https://api.fonnte.com/send', [
            'headers' => [
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ],
            'form_params' => array_merge([
                'target' => $to,
                'message' => $message,
            ], $options),
        ]);

        return json_decode($response->getBody(), true);
    }
}
