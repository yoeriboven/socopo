<?php

namespace App\Plans;

class AgencyPlan extends Plan
{
    public function getId()
    {
        return 'plan_3';
    }

    public function getName()
    {
        return 'Agency';
    }

    public function getInterval()
    {
        return 1;
    }

    public function getStripeId()
    {
        return config('services.stripe.plans.agency');
    }

    public function getMaxProfiles()
    {
        return 100000;
    }
}
