<?php

namespace App\Plans\Plans;

class AgencyPlan extends Plan
{
    public $id = 'plan_3';
    public $name = 'Agency';
    public $interval = 1;
    public $maxProfiles = 100000;

    public function getPaddleId()
    {
        return config('cashier.plans.agency');
    }
}
