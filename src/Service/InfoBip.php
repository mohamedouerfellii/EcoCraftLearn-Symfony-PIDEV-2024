<?php

use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;

class InfoBip
{
    public function sendAlertSMS($phoneNumber, $message)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'https://n89252.api.infobip.com/sms/2/text/advanced', [
            'headers' => [
                'Authorization' => 'App adac923e648a46110102aa7a42ce72ae-d0ace92f-0d09-4529-a0fc-05a0a2dbbea1',
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
?>
