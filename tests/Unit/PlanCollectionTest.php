<?php

namespace Tests\Unit;

use App\Plans\Facades\Plans;
use Tests\TestCase;

class PlanCollectionTest extends TestCase
{
    /** @test */
    public function it_returns_the_plan_by_an_id()
    {
        $plan = Plans::withId('plan_2');
        $this->assertInstanceOf('\App\Plans\Plans\BrandPlan', $plan);
    }

    /** @test */
    public function it_returns_the_plan_by_a_paddle_id()
    {
        $plan = Plans::withPaddleId('629570');
        $this->assertInstanceOf('\App\Plans\Plans\ProPlan', $plan);
    }
}
