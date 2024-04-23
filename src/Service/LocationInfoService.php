<?php
use Symfony\Component\HttpClient\HttpClient;

class LocationInfoService
{
    public static function getLocationInfo(): ?array
    {
        $httpClient = HttpClient::create();
        $ipifyResponse = $httpClient->request('GET', 'https://api.ipify.org');
        $ipAddress = $ipifyResponse->getContent();

        $ipApiResponse = $httpClient->request('GET', 'http://ip-api.com/json/' . $ipAddress);
        $locationData = $ipApiResponse->toArray();

        return $locationData;
    }
}
?>