<?php

namespace Tests\Feature\Commands;

use App\Jobs\FetchNewPostsJob;
use Facades\App\Console\Commands\FetchNewPostsCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FetchNewPostsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_jobs_for_profiles()
    {
        Queue::fake();

        factory('App\Profile', 4)->create();

        FetchNewPostsCommand::handle();

        Queue::assertPushed(FetchNewPostsJob::class, 4);
    }

    /** @test */
    public function it_dispatches_no_jobs_if_there_are_no_profiles()
    {
        Queue::fake();

        FetchNewPostsCommand::handle();

        Queue::assertNothingPushed();
    }
}
