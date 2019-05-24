<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('posts.posts', function ($view) {
            $view->with('hasProfiles', auth()->user()->profiles()->count());
            $view->with('hasSlack', auth()->user()->settings->slack_url);
        });

        View::composer([
            'settings._subscription_inactive',
            'settings._subscription_active'
        ], function ($view) {
            $view->with('plan', auth()->user()->plan());
        });
    }
}
