<?php

namespace App\Providers;

use App\Plans\Plans;
use App\Plans\ProPlan;
use App\Plans\BrandPlan;
use App\Plans\AgencyPlan;
use Illuminate\Support\ServiceProvider;

class PlansServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('plans', function () {
            $plans = new Plans();

            $plans->push(new ProPlan());
            $plans->push(new BrandPlan());
            $plans->push(new AgencyPlan());

            return $plans;
        });
    }
}
