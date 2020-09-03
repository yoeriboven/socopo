<?php

namespace Tests\Unit;

use App\Plans\Plans;
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
    public function it_returns_the_plan_by_a_paddle_id()
    {
        $collection = new PlanCollection();

        $plan = $collection->withPaddleId(null);
        $this->assertInstanceOf('\App\Plans\FreePlan', $plan);

        $plan = $collection->withPaddleId('627813');
        $this->assertInstanceOf('\App\Plans\ProPlan', $plan);
    }
}
