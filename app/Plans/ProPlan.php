<?php

namespace App\Plans;

class ProPlan extends Plan
{
    public function getId()
    {
        return 'plan_1';
    }

    public function getName()
    {
        return 'Pro';
    }

    public function getInterval()
    {
        return 5;
    }

    public function getStripeId()
    {
        return config('services.stripe.plans.pro');
    }

    public function getMaxProfiles()
    {
        return 20;
    }
}
