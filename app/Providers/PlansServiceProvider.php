<?php

namespace App\Providers;

use App\Plans\Plans;
use App\Plans\ProPlan;
use App\Plans\EnterprisePlan;
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
            $plans->push(new EnterprisePlan());

            return $plans;
        });
    }
}
