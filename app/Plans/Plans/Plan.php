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
        $this->id 			= $this->getId();
        $this->name 		= $this->getName();
        $this->interval     = $this->getInterval();
        $this->paddle_id 	= $this->getPaddleId();
        $this->maxProfiles 	= $this->getMaxProfiles();
    }

    abstract public function getId();
    abstract public function getName();
    abstract public function getInterval();
    abstract public function getPaddleId();
    abstract public function getMaxProfiles();
}
