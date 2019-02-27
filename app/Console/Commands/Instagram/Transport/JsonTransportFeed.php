<?php

namespace App\Console\Commands\Instagram\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use App\Console\Commands\Instagram\Exception\InstagramException;
use App\Console\Commands\Instagram\Storage\Cache;
use App\Console\Commands\Instagram\Storage\CacheManager;

class JsonTransportFeed extends TransportFeed
{
    /**
     * @var string
     */
    private $endCursor;

    /**
     * JsonTransportFeed constructor.
     *
     * @param Client            $client
     * @param                   $endCursor
     * @param CacheManager|null $cacheManager
     */
    public function __construct(Client $client, $endCursor, CacheManager $cacheManager = null)
    {
        $this->endCursor = $endCursor;
        parent::__construct($client, $cacheManager);
    }

    /**
     * @param $rhxgis
     * @param $variables
     *
     * @return string
     */
    private function generateGis($rhxgis, $variables)
    {
        return md5($rhxgis . ':' . json_encode($variables));
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
        /** @var Cache $cache */
        $cache = $this->cacheManager->getCache($userName);

        $variables = [
            'id'    => $cache->getUserId(),
            'first' => '12',
            'after' => $this->endCursor,
        ];

        $cookieJar = CookieJar::fromArray($cache->getCookie(), 'www.instagram.com');

        $headers = [
            'headers' => [
                'user-agent'       => self::USER_AGENT,
                'x-requested-with' => 'XMLHttpRequest',
                'x-instagram-gis'  => $this->generateGis($cache->getRhxGis(), $variables)
            ],
            'cookies' => $cookieJar
        ];

        $endpoint = self::INSTAGRAM_ENDPOINT . 'graphql/query/?query_hash=' . self::QUERY_HASH . '&variables=' . json_encode($variables);

        $res = $this->client->request('GET', $endpoint, $headers);

        $data = (string)$res->getBody();
        $data = json_decode($data);

        if ($data === null) {
            throw new InstagramException(json_last_error_msg());
        }

        // save to cache for next request
        $newCache = new Cache();
        $newCache->setRhxGis($cache->getRhxGis());
        $newCache->setUserId($cache->getUserId());
        if ($res->hasHeader('Set-Cookie')) {
            $newCache->setCookie($res->getHeaders()['Set-Cookie']);
        }

        $this->cacheManager->set($newCache, $userName);

        return $data->data->user;
    }
}
