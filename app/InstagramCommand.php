<?php

namespace App;

use App\Libraries\Instagram\InstagramDownloader;

class InstagramCommand
{
    protected $api;

    public function __construct()
    {
        $this->api = new InstagramDownloader();
    }

    public function handle()
    {
        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            $feed = $this->getFeed($profile->username);

            echo '<pre>';
            print_r($feed);
            echo '</pre>';
            echo "<img src='{$feed->medias[0]->displaySrc}' />";

            // Avoid 429 Rate limit from Instagram
            sleep(.5);
        }
    }

    protected function getFeed($username)
    {
        $this->api->setUserName($username);

        return $this->api->getFeed();
    }
}
