<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SlackController extends Controller
{
    /**
     * Redirects the user to the Slack authorization page
     */
    public function login()
    {
        return Socialite::with('slack')
            ->scopes(['incoming-webhook'])
            ->redirect();
    }

    /**
     * Accepts the data coming from Slack and stores
     * the relevant information before returning back to the settings page.
     */
    public function webhook()
    {
        try {
            $user = Socialite::driver('slack')->user();

            if (!$url = $user->accessTokenResponseBody['incoming_webhook']['url']) {
                return redirect()->route('settings')->withErrors(['Authorization failed']);
            }

            auth()->user()->setSlackUrl($url);

            return redirect()->route('settings')->with('success', 'Slack authorization succeeded.');
        } catch (\Exception $e) {
            return redirect()->route('settings')->withErrors(['Authorization failed']);
        }
    }

    public function logout()
    {
        auth()->user()->setSlackUrl(null);

        return redirect()->route('settings')->with('success', 'Removed authorization');
    }
}
