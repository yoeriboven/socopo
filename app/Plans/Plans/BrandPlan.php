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
        return 'eiaoeia';
    }

    public function getPaddleId()
    {
        return '391030';
    }

    public function getMaxProfiles()
    {
        return 100;
    }
}
