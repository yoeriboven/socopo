<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Traits\InteractsWithStripe;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CashierTest extends TestCase
{
    use RefreshDatabase, InteractsWithStripe;

    public function test_marking_as_cancelled_from_webhook()
    {
        $user = $this->signIn();

        $plan = app('plans')->first();

        $user->newSubscription($plan->name, $plan->stripe_id)
            ->create($this->getStripeToken());

        $subscription = $user->subscription($plan->name);

        $request = Request::create('/', 'POST', [], [], [], [], json_encode([
            'id' => 'foo',
            'type' => 'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'id' => $subscription->stripe_id,
                    'customer' => $user->stripe_id,
                ],
            ],
        ]));

        $controller = new CashierTestControllerStub;
        $response = $controller->handleWebhook($request);
        $this->assertEquals(200, $response->getStatusCode());

        $user = $user->fresh();
        $subscription = $user->subscription($plan->name);

        $this->assertTrue($subscription->cancelled());
    }

    public function test_data_is_updated_after_subscription_updates_from_webhook()
    {
        $user = $this->signIn();

        $plan = app('plans')->first();

        $user->newSubscription($plan->name, $plan->stripe_id)
            ->create($this->getStripeToken());

        $subscription = $user->subscription($plan->name);

        $request = Request::create('/', 'POST', [], [], [], [], json_encode([
            'id' => 'foo',
            'type' => 'customer.subscription.updated',
            'data' => [
                'object' => [
                    'id' => $subscription->stripe_id,
                    'customer' => $user->stripe_id,
                    'quantity' => 5,
                    'plan' => [
                        'id' => 'plan_testPlan'
                    ],
                    'trial_end' => 1557751000,
                    'current_period_end' => 1557758805,
                    'cancel_at_period_end' => true
                ],
            ],
        ]));

        $controller = new CashierTestControllerStub;
        $response = $controller->handleWebhook($request);
        $this->assertEquals(200, $response->getStatusCode());

        $user = $user->fresh();
        $subscription = $user->subscription($plan->name);

        $this->assertEquals(5, $subscription->quantity);
        $this->assertEquals('plan_testPlan', $subscription->stripe_plan);
        $this->assertEquals(1557751000, $subscription->trial_ends_at->timestamp);
        $this->assertEquals(1557758805, $subscription->current_period_end->timestamp);
    }
}

class CashierTestControllerStub extends StripeWebhookController
{
    public function __construct()
    {
        // Prevent setting middleware...
    }
}
