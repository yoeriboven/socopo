<?php

namespace Tests\Unit;

use App\Plans\PlanCollection;
use PHPUnit\Framework\TestCase;

class PlanCollectionTest extends TestCase
{
    /** @test */
    public function it_has_items()
    {
        $collection = new PlanCollection();

        $this->assertNotEmpty($collection);
    }

    /** @test */
    public function it_returns_the_plan_by_an_id()
    {
        $collection = new PlanCollection();

        $plan = $collection->withId('plan_2');
        $this->assertInstanceOf('\App\Plans\Plans\BrandPlan', $plan);
    }

    /** @test */
    public function it_returns_the_plan_by_a_paddle_id()
    {
        $collection = new PlanCollection();

        $plan = $collection->withPaddleId('627813');
        $this->assertInstanceOf('\App\Plans\Plans\ProPlan', $plan);
    }
}
