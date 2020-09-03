<?php

namespace App\Plans\Plans;

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

    public function getPaddleId()
    {
        return '190301';
    }

    public function getMaxProfiles()
    {
        return 100000;
    }
}
