<?php

namespace App\Plans;

class FreePlan extends Plan
{
    public function getId()
    {
        return null;
    }

    public function getName()
    {
        return 'Free';
    }

    public function getStripeId()
    {
        return null;
    }

    public function getMaxProfiles()
    {
        return 10;
    }
}
