<?php

namespace App\Plans\Plans;

class ProPlan extends Plan
{
    public $id = 'plan_1';
    public $name = 'Pro';
    public $interval = 5;
    public $maxProfiles = 25;

    public function getPaddleId()
    {
        return config('cashier.plans.pro');
    }
}
