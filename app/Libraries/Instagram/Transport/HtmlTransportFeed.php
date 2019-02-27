<?php

namespace App\Libraries\Instagram\Transport;

use GuzzleHttp\Client;
use App\Libraries\Instagram\Exception\InstagramException;

class HtmlTransportFeed extends TransportFeed
{
    /**
     * HtmlTransportFeed constructor.
     *
     * @param Client            $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
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
        $endpoint = self::INSTAGRAM_ENDPOINT . $userName . '/';

        $headers = [
            'headers' => [
                'user-agent' => self::USER_AGENT
            ]
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

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
