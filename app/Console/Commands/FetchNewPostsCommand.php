<?php

namespace App\Console\Commands;

use App\Jobs\FetchNewPostsJob;
use App\Profile;
use Illuminate\Console\Command;

class FetchNewPostsCommand extends Command
{
    protected $signature = 'posts:fetch';
    protected $description = 'Fetches posts from Instagram and alerts users if there are any new posts';

    public function handle()
    {
        Profile::each(function ($profile) {
            FetchNewPostsJob::dispatch($profile);
        }, $chunk = 100);
    }
}
