<?php

namespace Tests\Feature;

use Tests\TestCase;

class EndpointsTest extends TestCase
{
    /**
     * These test are to test whether endpoints use the 'auth' middleware
     *
     * These endpoints don't belong to another test file so they are tested in here
     */

    /** @test */
    public function slack_endpoints_require_authorization()
    {
        $this->get('slack/login')->assertRedirect(route('login'));
        $this->get('slack/webhook')->assertRedirect(route('login'));
        $this->get('slack/logout')->assertRedirect(route('login'));
    }

    /** @test */
    public function settings_page_requires_authorization()
    {
        $this->get('settings')->assertRedirect(route('login'));
    }
}
