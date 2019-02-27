<?php

namespace App\Console\Commands\Instagram;

use GuzzleHttp\Client;
use App\Console\Commands\Instagram\Exception\InstagramException;
use App\Console\Commands\Instagram\Hydrator\HtmlHydrator;
use App\Console\Commands\Instagram\Transport\HtmlTransportFeed;

class Api
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var string
     */
    private $userName;

    /**
     * Api constructor.
     * @param Client|null       $client
     */
    public function __construct(Client $client = null)
    {
        $this->client       = $client ?: new Client();
    }

    /**
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getFeed()
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }

        $feed     = new HtmlTransportFeed($this->client);
        $hydrator = new HtmlHydrator();

        $dataFetched = $feed->fetchData($this->userName);

        $hydrator->setData($dataFetched);

        return $hydrator->getHydratedData();
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
}
