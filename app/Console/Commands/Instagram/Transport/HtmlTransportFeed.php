<?php

namespace App\Console\Commands\Instagram\Transport;

use GuzzleHttp\Client;
use App\Console\Commands\Instagram\Exception\InstagramException;
use App\Console\Commands\Instagram\Storage\Cache;
use App\Console\Commands\Instagram\Storage\CacheManager;

class HtmlTransportFeed extends TransportFeed
{
    /**
     * HtmlTransportFeed constructor.
     *
     * @param Client            $client
     * @param CacheManager|null $cacheManager
     */
    public function __construct(Client $client, CacheManager $cacheManager = null)
    {
        parent::__construct($client, $cacheManager);
    }

    /**
     * @param $userName
     *
     * @return mixed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Instagram\Exception\CacheException
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

        if ($this->cacheManager instanceof CacheManager) {
            $newCache = new Cache();
            $newCache->setRhxGis($data->rhx_gis);
            $newCache->setUserId($data->entry_data->ProfilePage[0]->graphql->user->id);
            if ($res->hasHeader('Set-Cookie')) {
                $newCache->setCookie($res->getHeaders()['Set-Cookie']);
            }

            $this->cacheManager->set($newCache, $userName);
        }

        return $data->entry_data->ProfilePage[0]->graphql->user;
    }
}
