<?php

namespace App\Services\Instagram;

use App\Exceptions\InstagramException;
use App\Services\Instagram\Hydrator\HtmlHydrator;
use App\Services\Instagram\Transport\TransportFeed;
use GuzzleHttp\Client;

class InstagramDownloader
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @var string
     */
    private $username;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Returns the feed for the given username.
     *
     * @param  string $username
     *
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getFeed($username)
    {
        $this->assertUsernameAllowed($username);

        $data = $this->getData();

        $hydrator = new HtmlHydrator();
        $hydrator->setData($data);

        return $hydrator->getHydratedData();
    }

    /**
     * Returns the profile picture for the given username.
     *
     * @param  string $username
     *
     * @return Hydrator\Component\Feed
     *
     * @throws InstagramException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getAvatar($username)
    {
        $this->assertUsernameAllowed($username);

        $data = $this->getData();

        return $data->profile_pic_url_hd;
    }

    /**
     * Returns a fake object to run Instagram tests on.
     *
     * @return FakeInstagramDownloder
     */
    public static function fake()
    {
        return new FakeInstagramDownloader();
    }

    /**
     * Returns the profile data fetched from Instagram.
     *
     * @return \stdClass
     */
    private function getData()
    {
        $this->avoidRateLimit();

        $feed = app(TransportFeed::class);
        $feed->setClient($this->client);
        return $feed->fetchData($this->username);
    }

    /**
     * Throws an exception if the username is not set.
     *
     * @param  string $username
     *
     * @throws  InstagramException
     */
    private function assertUsernameAllowed($username)
    {
        if (empty($this->username = $username)) {
            throw new InstagramException('Username cannot be empty');
        }
    }

    /**
     * Wait for a little while to avoid an 429 Rate limit from Instagram.
     */
    private function avoidRateLimit()
    {
        sleep(.5);
    }
}
