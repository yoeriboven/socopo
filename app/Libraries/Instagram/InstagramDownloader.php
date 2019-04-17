<?php

namespace App\Libraries\Instagram;

use GuzzleHttp\Client;
use App\Libraries\Instagram\Exception\InstagramException;
use App\Libraries\Instagram\Hydrator\HtmlHydrator;
use App\Libraries\Instagram\Transport\TransportFeed;

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
     * Returns the feed for the given username
     *
     * @return Hydrator\Component\Feed
     *
     * @param  $userName Username
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getFeed($userName)
    {
        $this->checkUsername($userName);

        $data = $this->getData();

        $hydrator = new HtmlHydrator();
        $hydrator->setData($data);

        return $hydrator->getHydratedData();
    }

    /**
     * Returns the profile picture for the given username
     *
     * @param  $userName Username
     *
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getAvatar($userName)
    {
        $this->checkUsername($userName);

        $data = $this->getData();

        return $data->profile_pic_url_hd;
    }

    /**
     * Returns the profile data fetched from Instagram
     *
     * @return \stdClass
     */
    private function getData()
    {
        $this->avoidRateLimit();

        $feed = app(TransportFeed::class);
        $feed->setClient($this->client);
        return $feed->fetchData($this->userName);
    }

    /**
     * Throws an exception if the userName is not set
     *
     * @param  String $userName
     *
     * @throws  InstagramException
     */
    private function checkUsername($userName)
    {
        if (empty($this->userName = $userName)) {
            throw new InstagramException('Username cannot be empty');
        }
    }

    /**
     * Wait for a little while to avoid an 429 Rate limit from Instagram
     */
    private function avoidRateLimit()
    {
        sleep(.5);
    }
}
