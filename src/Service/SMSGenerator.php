<?php

use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;

class SMSGenerator
{
    public function sendAlertSMS($phoneNumber, $message)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'https://n8lyd5.api.infobip.com/sms/2/text/advanced', [
            'headers' => [
                'Authorization' => 'App 059747ab4c869d6a7bd61303c766b66f-7b65660a-3d81-4099-91d9-aba8339dc77e',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'json' => [
                'messages' => [
                    [
                        'destinations' => [['to' => $phoneNumber]],
                        'from' => 'ServiceSMS',
                        'text' => $message
                    ]
                ]
            ]
        ]);
        try {
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                // Le SMS a été envoyé avec succès
                return true;
            } else {
                // L'envoi du SMS a échoué
                return false;
            }
        } catch (TransportExceptionInterface $e) {
            // Erreur lors de l'envoi du SMS
            return false;
        }
    }
}