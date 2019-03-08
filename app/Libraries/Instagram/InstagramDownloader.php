<?php

namespace App\Libraries\Instagram;

use GuzzleHttp\Client;
use App\Libraries\Instagram\Exception\InstagramException;
use App\Libraries\Instagram\Hydrator\HtmlHydrator;
use App\Libraries\Instagram\Transport\HtmlTransportFeed;

class InstagramDownloader
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

    public function getAvatar()
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }

        $feed     = new HtmlTransportFeed($this->client);
        $dataFetched = $feed->fetchData($this->userName);

        return $dataFetched->profile_pic_url_hd;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
}
