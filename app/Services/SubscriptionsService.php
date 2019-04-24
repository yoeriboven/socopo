<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Exceptions\AlreadySubscribedToPlanException;

class SubscriptionsService
{
    /**
     * The current request
     *
     * @var Illuminate\Http\Request
     */
    private $request;

    /**
     * Handles all events related to the user_details
     *
     * @var App\Services\UserDetailsService
     */
    private $userDetailsService;

    /**
     * Creates a new instance of this class
     */
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

        $plan = $this->getPlan();

        if ($this->alreadySubscribedToPlan($plan)) {
            throw new AlreadySubscribedToPlanException();
        }

        $this->userDetailsService->store($this->request);

        $this->subscribe($plan);
    }

    /**
     * Subscribes the user to the plan
     */
    private function subscribe($plan)
    {
        $this->request->user()->subscribeToPlan($this->request->stripeToken, $plan);
    }

    /**
     * Returns whether the user is already subscribed to this plan
     *
     * @return boolean
     */
    public function alreadySubscribedToPlan($plan)
    {
        return !! $this->request->user()->subscribed($plan['name']);
    }

    /**
     * Returns the plan the user is trying to subscribe to
     *
     * @return array
     */
    public function getPlan()
    {
        return config('plans')[$this->request->plan];
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
