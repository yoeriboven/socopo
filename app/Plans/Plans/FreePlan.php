<?php

namespace App\Plans\Plans;

class FreePlan extends Plan
{
    public $id = null;
    public $name = 'Free';
    public $interval = 10;
    public $maxProfiles = 10;

    public function getPaddleId()
    {
        return null;
    }
}
