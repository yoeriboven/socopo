<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SlackController extends Controller
{
    public function login()
    {
        return Socialite::with('slack')
            ->scopes(['incoming-webhook'])
            ->redirect();
    }

    public function webhook()
    {
        try {
            $user = Socialite::driver('slack')->user();

            if (!$url = $user->accessTokenResponseBody['incoming_webhook']['url']) {
                dd('No url found');
            }

            auth()->user()->settings()->update(['slack_url' => $url]);

            dd('Done! Return naar settings');
        } catch (\Exception $e) {
            dd($e);
            // Show error message

            /*
            Unsuccessful request: `POST https://slack.com/api/oauth.access` resulted in a `200 OK` response:\n
    {"ok":false,"error":"invalid_code"}\n
             */
        }
    }
}
