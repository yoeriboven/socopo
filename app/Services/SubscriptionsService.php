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

    /**
     * Subscribes the user to the plan
     */
    private function subscribe()
    {
        $plan = $this->getPlan();

        $this->request->user()
            ->newSubscription($plan['name'], $plan['id'])
            ->create($this->request->stripeToken, [
                'email' => $this->request->user()->email
            ]);
    }

    /**
     * Returns whether the user is already subscribed to this plan
     *
     * @return boolean
     */
    public function alreadySubscribedToPlan()
    {
        $plan = $this->getPlan();

        return !! $this->request->user()->subscribed($plan['name']);
    }

    /**
     * Returns the plan the user is trying to subscribe to
     *
     * @return array
     */
    public function getPlan()
    {
        return config('plans')[$this->request->get('plan')];
    }

    /**
     * Set the variable
     *
     * @param Request $request
     */
    public function setRequest($request)
    {
        if (! is_null($request)) {
            $this->request = $request;
        }
    }
}
