<?php

namespace App\Plans;

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

    public function getStripeId()
    {
        return config('services.stripe.plans.brand');
    }

    public function getMaxProfiles()
    {
        return 100;
    }
}
