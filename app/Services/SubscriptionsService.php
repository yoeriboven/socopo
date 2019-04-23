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
        $this->setTaxRate();

        $plan = $this->getPlan();

        $this->request->user()
            ->newSubscription($plan['name'], $plan['id'])
            ->create($this->request->stripeToken, [
                'email' => $this->request->user()->email
            ]);
    }

    /**
     * Set tax rate based on the country
     * and whether the user is an individual or a company
     */
    public function setTaxRate()
    {
        $user = $this->request->user();

        $user->setTaxForCountry($user->details->country, $user->isBusiness());
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
