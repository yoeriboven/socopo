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
        // $profiles = Profile::whereIn('id', [1,3,10,11])->latest()->get();
        $posts = $this->posts->latestForProfiles($profiles);

        foreach ($profiles as $profile) {
            $this->avoidRateLimit();

            // echo $profile->username;

            try {
                $feed = $this->api->getFeed($profile->username);
            } catch (\Exception $e) {
                // Sentry (couldn't find user on Instagram)
                continue;
            }

            $profile->updateAvatar($feed->profilePicture);

            echo '<pre>';
            // print_r($feed);
            echo '</pre>';

            if ($feed->getMediaCount() == 0) {
                continue;
            }

            // Now check if this post is newer than the latest one stored on our end
            $latestPostId = 0;

            $newPost = $feed->getLatestMedia();
            if ($posts->has($profile->id)) {
                $latestPostId = $posts->get($profile->id)->ig_post_id;
            }

            // We have the newest post stored already
            // echo $newPost->id. ' - '.$latestPostId;
            if ($newPost->id == $latestPostId) {
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
