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
        $profiles = Profile::whereIn('id', [1,2,3,7])->get();

        foreach ($profiles as $profile) {
            // Avoid 429 Rate limit from Instagram
            sleep(.5);

            echo $profile->username;

            try {
                $feed = $this->api->getFeed($profile->username);
            } catch (\Exception $e) {
                // Sentry (couldn't find user on Instagram)
                continue;
            }

            $profile->updateAvatar($feed->profilePicture);

            echo '<pre>';
            print_r($feed);
            echo '</pre>';

            if ($feed->getMediaCount() == 0) {
                continue;
            }

            // Now check if this post is newer than the latest one stored on our end



            echo '<hr/>';
        }
    }
}
