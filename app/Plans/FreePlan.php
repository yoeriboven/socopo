<?php

namespace App\Plans;

class FreePlan
{
    public $id = null;
    public $stripe_id = null;
    public $name = 'Free';
    public $maxProfiles = 10;
}
