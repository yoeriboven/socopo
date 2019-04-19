<?php

namespace App\Services;

use Illuminate\Http\Request;

class SubscriptionsService
{
    private $request;
    private $userDetailsService;

    public function __construct(UserDetailsService $userDetailsService)
    {
        $this->userDetailsService = $userDetailsService;
    }

    /**
     * Upgrades the user to a plan
     *
     * @param  Request $request
     */
    public function upgrade(Request $request)
    {
        $this->request = $request;

        $this->userDetailsService->store($this->request);

        $this->subscribe();
    }

    private function subscribe()
    {
        $plan = config('plans')[$this->request->get('plan')];

        $this->request->user()
            ->newSubscription($plan['name'], $plan['id'])
            ->create($this->request->get('stripeToken'));
    }
}
