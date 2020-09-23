<?php

namespace App\Plans\Facades;

use Illuminate\Support\Facades\Facade;

class Plans extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'plans';
    }
}
