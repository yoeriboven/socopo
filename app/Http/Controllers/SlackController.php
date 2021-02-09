<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SlackController extends Controller
{
    /**
     * Redirects the user to the Slack authorization page.
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

            if (!$url = $this->getWebhookUrl($user)) {
                return redirect()->route('settings')->withErrors(['slack' => 'Authorization failed']);
            }

            auth()->user()->setSlackUrl($url);

            return redirect()->route('settings')->with('slack.success', 'Slack authorization succeeded.');
        } catch (\Exception $e) {
            return redirect()->route('settings')->withErrors(['slack' => 'Authorization failed']);
        }
    }

    /**
     * Resets the slack URL and undoes the Slack connection.
     */
    public function logout()
    {
        auth()->user()->setSlackUrl(null);

        return redirect()->route('settings')->with('slack.success', 'Removed authorization');
    }

    /**
     * Returns the url from Slack, needed to send messages to Slack.
     *
     * @return string
     */
    private function getWebhookUrl($user)
    {
        return $user->accessTokenResponseBody['incoming_webhook']['url'];
    }
}
