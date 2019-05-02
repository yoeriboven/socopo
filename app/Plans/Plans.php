<?php

namespace App\Plans;

use Illuminate\Support\Collection;

class Plans extends Collection
{
    /**
     * Returns the Plan with a certain id
     *
     * @param  string $id
     * @return Plan
     */
    public function withId($id)
    {
        return $this->first(function ($plan) use ($id) {
            return $plan->id == $id;
        }, new FreePlan());
    }

    /**
     * Returns the plan with a certain stripe_id
     *
     * @param  string $stripe_id
     * @return Plan
     */
    public function withStripeId($stripe_id)
    {
        return $this->first(function ($plan) use ($stripe_id) {
            return $plan->stripe_id == $stripe_id;
        }, new FreePlan());
    }
}
