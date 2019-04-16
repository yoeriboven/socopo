<?php

namespace App\Services;

use Illuminate\Http\Request;

class SubscriptionsService
{
    protected $userDetailsService;

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
        $this->userDetailsService->store($request);

        $request->user()->newSubscription('Pro', 'plan_ErRIL8fIR4sfRt')->create(request('stripeToken'));
    }
}
