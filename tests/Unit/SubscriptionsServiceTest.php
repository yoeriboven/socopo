<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserDetailsService;
use App\Services\SubscriptionsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\AlreadySubscribedToPlanException;

class SubscriptionsServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_an_exception_when_a_user_is_already_subscribed_to_a_plan()
    {
        $this->expectException(AlreadySubscribedToPlanException::class);
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $planKey = array_key_first(config('plans'));
        $plan = config('plans')[$planKey];

        factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => $plan['name']]);

        $service = $this->createService(['plan' => $planKey]);

        $service->upgrade();
    }

    /** @test */
    public function it_returns_true_when_a_user_is_already_subscribed_to_a_plan()
    {
        $user = $this->signIn();

        $planKey = array_key_first(config('plans'));
        $plan = config('plans')[$planKey];

        factory('App\Billing\Subscription')->create(['user_id' => $user->id, 'name' => $plan['name']]);

        $service = $this->createService(['plan' => $planKey]);

        $this->assertTrue($service->alreadySubscribedToPlan($plan));
    }

    private function createService($overrides = [])
    {
        $service = new SubscriptionsService(new UserDetailsService());
        $service->setRequest($this->createRequest($overrides));

        return $service;
    }

    private function createRequest($overrides)
    {
        $request = new \Illuminate\Http\Request();
        $request->merge(['user' => $this->user ]);
        $request->merge(array_merge($this->getData(), $overrides));
        $request->setUserResolver(function () {
            return $this->user;
        });

        return $request;
    }

    private function getData()
    {
        return [
            'vat_id' => 'NL852924574B01',
            'name' => 'Yoeri.me',
            'address' => 'De Werf 9',
            'postal' => '9514CN',
            'city' => 'Gasselternijveen',
            'country' => 'NL',
            'plan' => 'plan_1',
            'stripeToken' => 'placeholder_stripe_token'
        ];
    }
}
