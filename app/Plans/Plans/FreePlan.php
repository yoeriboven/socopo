<?php

namespace App\Plans\Plans;

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

    public function getInterval()
    {
        return 10;
    }

    public function getPaddleId()
    {
        return null;
    }

    public function getMaxProfiles()
    {
        return 10;
    }
}
