<?php

namespace App\Services;

use GuzzleHttp\Client;

class LwsSmsService
{
    protected $client;
    protected $apiKey;
    protected $senderID;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://sms.lws.fr/sms/api'
        ]);
        $this->apiKey = env('LWS_SMS_API_KEY');
        $this->senderID = env('LWS_SMS_SENDER_ID');
    }

    public function sendSms($to, $message)
    {
        $response = $this->client->post('', [
            'form_params' => [
                'action' => 'send-sms',
                'api_key' => $this->apiKey,
                'to' => $to,
                'from' => $this->senderID,
                'sms' => $message
            ]
        ]);

        $body = json_decode($response->getBody(), true);

        if ($body['code'] === 'ok') {
            // La respuesta fue exitosa
            return true;
        } else {
            // La respuesta fue un error
            return false;
        }
    }
}
