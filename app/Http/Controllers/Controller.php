<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Libraries\Instagram\InstagramDownloader;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function instagramTester()
    {
        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            $api   = new InstagramDownloader();
            $api->setUserName($profile->username);

            $feed = $api->getFeed();

            echo '<pre>';
            print_r($feed);
            echo '</pre>';
            echo "<img src='{$feed->medias[0]->displaySrc}' />";

            // Avoid 429 Rate limit from Instagram
            sleep(.5);
        }
    }
}
