<?php

namespace App\Services\Instagram\Transport;

use App\Exceptions\InstagramException;
use GuzzleHttp\Client;

class TransportFeed
{
    const INSTAGRAM_ENDPOINT = 'https://www.instagram.com/';
    const USER_AGENT         = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36';

    /**
     * Proxy to circumvent Instagram block.
     * @var string
     */
    protected $proxy = '';

    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        if (app()->environment('production')) {
            $this->proxy = 'https://app.scrapingbee.com/api/v1/?api_key='.config('services.scrapingbee.key').'&url=';
        }
    }

    /**
     * @param $userName
     *
     * @return mixed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchData($userName)
    {
        $endpoint = $this->proxy . self::INSTAGRAM_ENDPOINT . $userName . '/';

        $headers = [
            'headers' => [
                'user-agent' => self::USER_AGENT,
            ],
        ];

        $res = $this->client->request('GET', $endpoint, $headers);

        $html = (string)$res->getBody();

        preg_match('/<script type="text\/javascript">window\._sharedData\s?=(.+);<\/script>/', $html, $matches);

        if (!isset($matches[1])) {
            throw new InstagramException('Unable to extract JSON data');
        }

        $data = json_decode($matches[1]);

        if ($data === null) {
            throw new InstagramException(json_last_error_msg());
        }

        $profile = $data->entry_data->ProfilePage[0]->graphql->user;

        return $profile;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
