<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Plans\Facades\Plans;
use App\Plans\PlanCollection;
use App\Http\Livewire\Subscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_emits_an_event_when_it_finishes()
    {
        $this->signIn();

        $userDetails = factory('App\UserDetails')->make();

        Livewire::test(Subscriber::class)
            ->set('plan_id', Plans::first()->id)
            ->set('userDetails', $userDetails->toArray())
            ->call('submit')
            ->assertEmitted('readyForPaddle');
    }

    /** @test */
    public function it_requires_a_plan_id()
    {
        $this->signIn();

        Livewire::test(Subscriber::class)
            ->set('plan_id', '')
            ->call('submit')
            ->assertHasErrors(['plan_id' => 'required']);
    }

    /** @test */
    public function it_requires_a_valid_plan_id()
    {
        $this->signIn();

        Livewire::test(Subscriber::class)
            ->set('plan_id', 'invalid_plan_id')
            ->call('submit')
            ->assertHasErrors('plan_id');
    }

    /** @test */
    public function it_saves_the_user_details()
    {
        $user = $this->signIn();

        $userDetails = factory('App\UserDetails')->make();

        Livewire::test(Subscriber::class)
            ->set('plan_id', Plans::first()->id)
            ->set('userDetails', $userDetails->toArray())
            ->call('submit');

        $this->assertEquals($user->details->name, $userDetails->name);
    }

    /** @test */
    public function it_mounts_with_the_existing_plan()
    {
        $user = $this->signIn();

        $plan = Plans::first();

        $this->subscribe($plan->paddle_id);

        Livewire::test(Subscriber::class)
            ->assertSet('plan_id', $plan->id);
    }

    /** @test */
    public function it_mounts_with_the_existing_user_details()
    {
        $user = $this->signIn();

        $userDetails = factory('App\UserDetails')->make()->toArray();
        $user->details()->update($userDetails);

        Livewire::test(Subscriber::class)
            ->assertSet('userDetails.name', $user->details->name)
            ->assertSet('userDetails.address', $user->details->address)
            ->assertSet('userDetails.postal', $user->details->postal)
            ->assertSet('userDetails.city', $user->details->city)
            ->assertSet('userDetails.country', $user->details->country);
    }

    /** @test */
    public function it_swaps_the_subscription_if_the_user_is_already_subscribed()
    {
        $this->signIn();

        Http::fake();

        $oldPlan = Plans::first();
        $newPlan = Plans::last();

        $this->subscribe($oldPlan->paddle_id);

        $userDetails = factory('App\UserDetails')->make()->toArray();
        $this->user->details()->update($userDetails);

        Livewire::test(Subscriber::class)
            ->set('plan_id', $newPlan->id)
            ->call('submit')
            ->assertRedirect(route('settings'));

        $this->assertTrue(
            $this->user->subscribedToPlan($newPlan->paddle_id)
        );
    }

    /** @test */
    public function it_returns_an_error_if_the_user_is_already_subscribed_to_the_plan()
    {
        $this->signIn();

        $plan = Plans::first();

        $this->subscribe($plan->paddle_id);

        $userDetails = factory('App\UserDetails')->make()->toArray();
        $this->user->details()->update($userDetails);

        Livewire::test(Subscriber::class)
            ->set('plan_id', $plan->id)
            ->call('submit')
            ->assertHasErrors('plan_id');
    }
}
