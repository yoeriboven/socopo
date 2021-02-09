<?php

namespace App\Providers;

use App\Plans\PlanCollection;
use App\Plans\Plans\AgencyPlan;
use App\Plans\Plans\BrandPlan;
use App\Plans\Plans\ProPlan;
use Illuminate\Support\ServiceProvider;

class PlanServiceProvider extends ServiceProvider
{
    protected $plans = [
        ProPlan::class,
        BrandPlan::class,
        AgencyPlan::class,
    ];

    public function register()
    {
        $this->app->singleton('plans', function () {
            return PlanCollection::withPlans($this->plans);
        });
    }
}
