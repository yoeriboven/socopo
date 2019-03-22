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
        $profiles = $this->getProfilesWithFeeds();
        $posts = $this->posts->latestForProfiles($profiles);

        foreach ($profiles as $profile) {
            echo $profile->username;
            $feed = $profile->feed;

            $profile->updateAvatar($feed->profilePicture);

            echo '<pre>';
            print_r($feed);
            echo '</pre>';

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
     * Filters out profiles without a feed and those feeds without media
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProfilesWithFeeds()
    {
        return Profile::all()->filter(function ($profile) {
            $feed = $this->getFeedForUsername($profile->username);
            $profile->setRelation('feed', $feed);
            return $feed;
        })->filter(function ($profile) {
            return $profile->feed->hasMedia();
        });
    }

    /**
     * Fetches the feed from Instagram
     *
     * @param  string $username
     * @return App\Libraries\Instagram\Hydrator\Component\Feed or null
     */
    private function getFeedForUsername($username)
    {
        try {
            return $this->api->getFeed($username);
        } catch (\Exception $e) {
            return null;
        }
    }
}
