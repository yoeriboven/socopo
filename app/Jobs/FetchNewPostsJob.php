<?php

namespace App\Jobs;

use App\Post;
use App\Profile;
use App\Services\Instagram\Hydrator\Component\Feed;
use Facades\App\Repositories\PostRepository;
use Facades\App\Services\Instagram\InstagramDownloader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $this->profile->updateAvatar($this->profile->feed->profilePicture);

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

        $storedPost = PostRepository::latestForProfile($this->profile);

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
     * @return App\Services\Instagram\Hydrator\Component\Feed or null
     */
    private function getFeed()
    {
        try {
            return InstagramDownloader::getFeed($this->profile->username);
        } catch (\Exception $e) {
            return new Feed();
        }
    }
}
