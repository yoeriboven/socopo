<?php

namespace App\Plans\Plans;

abstract class Plan
{
    public $id;
    public $name;
    public $interval;
    public $paddle_id;
    public $maxProfiles;

    public function __construct()
    {
        $this->paddle_id 	= $this->getPaddleId();
    }

    abstract public function getPaddleId();
}
