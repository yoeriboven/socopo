<?php

namespace App\Plans;

abstract class Plan
{
    public $id;
    public $name;
    public $stripe_id;
    public $maxProfiles;

    public function __construct()
    {
        $this->id 			= $this->getId();
        $this->name 		= $this->getName();
        $this->stripe_id 	= $this->getStripeId();
        $this->maxProfiles 	= $this->getMaxProfiles();
    }

    abstract public function getId();
    abstract public function getName();
    abstract public function getStripeId();
    abstract public function getMaxProfiles();
}
