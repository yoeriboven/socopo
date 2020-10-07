<?php

namespace App\Plans\Plans;

class BrandPlan extends Plan
{
    public $id = 'plan_2';
    public $name = 'Brand';
    public $interval = 3;
    public $maxProfiles = 100;

    public function getPaddleId()
    {
        return config('cashier.plans.brand');
    }
}
