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
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getFeed()
    {
        $data = $this->getData();

        $hydrator = new HtmlHydrator();
        $hydrator->setData($data);

        return $hydrator->getHydratedData();
    }

    /**
     * Returns the profile picture for the given username
     *
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getAvatar()
    {
        $data = $this->getData();

        return $data->profile_pic_url_hd;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Returns the profile data fetched from Instagram
     *
     * @return \stdClass
     */
    protected function getData()
    {
        $this->checkUsername();

        $feed = new TransportFeed($this->client);
        return $feed->fetchData($this->userName);
    }

    /**
     * Checks wether the username is set
     *
     * @throws InstagramException
     */
    protected function checkUsername()
    {
        if (empty($this->userName)) {
            throw new InstagramException('Username cannot be empty');
        }
    }
}
