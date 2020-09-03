<?php

namespace App\Plans;

use Illuminate\Support\Facades\Facade;

class PlansFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PlanCollection::class;
    }
}
