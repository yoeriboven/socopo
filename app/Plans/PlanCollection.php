<?php

namespace App\Plans;

use Illuminate\Support\Collection;

class PlanCollection extends Collection
{
    protected $plans = [
        ProPlan::class,
        BrandPlan::class,
        AgencyPlan::class,
    ];

    public function __construct($items = [])
    {
        $plans = collect($this->plans)->map(function ($plan) {
            return new $plan();
        });

        parent::__construct($plans);
    }

    /**
     * Returns the plan with a certain paddle_id
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