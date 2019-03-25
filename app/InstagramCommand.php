<?php

namespace App;

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
        $profiles = $this->getProfilesToNotify();

        $profiles->each(function ($profile) {
            $profile->updateAvatar($profile->feed->profilePicture);

            $this->posts->create($profile->feed->getLatestMedia(), $profile);
        });
    }

    /**
     * Gets profiles which have a new post on IG
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProfilesToNotify()
    {
        $profiles = $this->getProfilesWithFeeds();
        $latestPosts = $this->posts->latestForProfiles($profiles);

        return $this->getProfilesWithNewPosts($profiles, $latestPosts);
    }

    /**
     * Gets profiles which have a new post on IG
     *
     * @param  \Illuminate\Database\Eloquent\Collection $profiles
     * @param  \Illuminate\Database\Eloquent\Collection $latestPosts [Returns the latest stored posts]
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProfilesWithNewPosts($profiles, $latestPosts)
    {
        return $profiles->filter(function ($profile) use ($latestPosts) {
            /**
             * If a profiile has a latest post it means we need to check
             * whether the post found on IG is newer or not
             *
             * No post stored? Then the post found on IG is newest and should be stored
             */
            if ($latestPosts->has($profile->id)) {
                $latestPost = $latestPosts->get($profile->id);

                // Checks if the latest IG post is newer than what we have stored
                return $profile->feed->getLatestMedia()->id != $latestPost->ig_post_id;
            }

            return true;
        });
    }

    /**
     * Filters out profiles without a feed and those feeds without media
     * Also attaches the feed to the profile
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProfilesWithFeeds()
    {
        // $profiles = Profile::whereIn('id', [1,10])->latest()->get();
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
