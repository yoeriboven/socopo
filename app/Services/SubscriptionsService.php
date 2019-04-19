<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Exceptions\AlreadySubscribedToPlanException;

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
    public function upgrade(Request $request = null)
    {
        $this->setRequest($request);

        if ($this->alreadySubscribedToPlan()) {
            throw new AlreadySubscribedToPlanException();
        }

        $this->userDetailsService->store($this->request);

        $this->subscribe();
    }

    private function subscribe()
    {
        $plan = $this->getPlan();

        $this->request->user()
            ->newSubscription($plan['name'], $plan['id'])
            ->create($this->request->stripeToken, [
                'email' => $this->request->user()->email
            ]);
    }

    public function alreadySubscribedToPlan()
    {
        $plan = $this->getPlan();

        return !! $this->request->user()->subscribed($plan['name']);
    }

    public function getPlan()
    {
        return config('plans')[$this->request->get('plan')];
    }

    public function setRequest($request)
    {
        if (! is_null($request)) {
            $this->request = $request;
        }
    }
}
