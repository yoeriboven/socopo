<?php

namespace App\Plans;

use Illuminate\Support\Collection;

class PlanCollection extends Collection
{
    public static function withPlans($plans)
    {
        $plans = collect($plans)->map(function ($plan) {
            return new $plan();
        });

        return new static($plans);
    }

    /**
     * Returns the plan with a certain id.
     *
     * @param  string $id
     * @return Plan
     */
    public function withId($id)
    {
        return $this->first(function ($plan) use ($id) {
            return $plan->id == $id;
        });
    }

    /**
     * Returns the plan with a certain paddle_id.
     *
     * @param  string $paddle_id
     * @return Plan
     */
    public function withPaddleId($paddle_id)
    {
        return $this->first(function ($plan) use ($paddle_id) {
            return $plan->paddle_id == $paddle_id;
        });
    }
}
