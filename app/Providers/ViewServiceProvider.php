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
        View::composer('posts', function ($view) {
            $view->with('hasProfiles', auth()->user()->profiles()->count());
            $view->with('hasSlack', auth()->user()->settings->slack_url);
        });
    }
}
