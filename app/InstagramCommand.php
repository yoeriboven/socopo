<?php

namespace App;

use Illuminate\Support\Carbon;
use App\Repositories\PostRepository;
use App\Libraries\Instagram\InstagramDownloader;

class InstagramCommand
{
    protected $api;

    public function __construct()
    {
        $this->api = new InstagramDownloader();
        $this->posts = new PostRepository();
    }

    public function handle()
    {
        $profiles = Profile::all();
        $posts = $this->posts->latestForProfiles($profiles->pluck('id'));

        foreach ($profiles as $profile) {
            $this->avoidRateLimit();

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
            $newPost = $feed->getLatestMedia();
            $latestPost = $posts->get($profile->id);

            // We have the newest post stored already
            if ($newPost->id == $latestPost->ig_post_id) {
                echo 'niet nieuwer';
                continue;
            }

            // Store new post
            Post::create([
                'profile_id' => $profile->id,
                'ig_post_id' => $newPost->id,
                'caption' => $newPost->caption,
                'type' => $newPost->typeName,
                'image_url' => $newPost->displaySrc,
                'post_url' => $newPost->link,
                'posted_at' => Carbon::now()
            ]);
        }
    }

    /**
     * Wait for a little while to avoid an 429 Rate limit from Instagram
     */
    protected function avoidRateLimit()
    {
        sleep(.5);
    }
}
