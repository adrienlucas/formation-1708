<?php

declare(strict_types=1);

namespace App\Gateway;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NasaApiGateway
{
    const NASA_API_URL = 'https://api.nasa.gov/neo/rest/v1/feed/today?detailed=true&api_key=DEMO_KEY';

    /** @var HttpClientInterface */
    private $clientHttp;

    public function __construct(HttpClientInterface $clientHttp)
    {
        $this->clientHttp = $clientHttp;
    }

    public function isEarthInDanger(): bool
    {
        $nasaResponse = $this->clientHttp->request(Request::METHOD_GET, self::NASA_API_URL);

        $deserializedResponse = json_decode($nasaResponse->getContent(), true);

        $todayAsteroids = array_pop($deserializedResponse['near_earth_objects']);
        $danger = false;
        foreach($todayAsteroids as $asteroid) {
            if($asteroid['is_potentially_hazardous_asteroid'] === true) {
                $danger = true;
                break;
            }
        }

        return $danger;
    }
}
