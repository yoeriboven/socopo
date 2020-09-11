<?php

namespace App\Plans\Plans;

class BrandPlan extends Plan
{
    public function getId()
    {
        return 'plan_2';
    }

    public function getName()
    {
        return 'Brand';
    }

    public function getInterval()
    {
        return 3;
    }

    public function getPaddleId()
    {
        return config('cashier.plans.brand');
    }

    public function getMaxProfiles()
    {
        return 100;
    }
}
