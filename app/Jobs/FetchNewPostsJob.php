<?php

namespace App\Jobs;

use App\Post;
use App\Profile;
use App\Events\NewPostFound;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Instagram\Hydrator\Component\Feed;
use Facades\App\Services\Instagram\InstagramDownloader;

class FetchNewPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Profile for which we will fetch new posts.
     *
     * @var \App\Profile
     */
    private $profile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->profile->feed = $this->getFeed();

        if ($this->hasNewPost()) {
            NewPostFound::dispatch($this->profile);

            $post = Post::storeFromInstagram($this->profile);

            $this->profile->notifyFollowers($post);
        }
    }

    /**
     * Checks if Instagram data is more recent than database data.
     *
     * @return bool
     */
    private function hasNewPost()
    {
        if (! $this->profile->feed->hasMedia()) {
            return false;
        }

        $storedPost = Post::latestForProfile($this->profile);

        if ($storedPost) {
            // Checks if the latest IG post is different (newer) to what we have stored
            return $this->profile->feed->getLatestMedia()->id != $storedPost->ig_post_id;
        }

        return true;
    }

    /**
     * Fetches the feed from Instagram.
     *
     * @param  string $username
     * @return App\Services\Instagram\Hydrator\Component\Feed
     */
    private function getFeed()
    {
        try {
            return InstagramDownloader::getFeed($this->profile->username);
        } catch (\Exception $e) {
            app('sentry')->captureException($e);
            return new Feed();
        }
    }
}
