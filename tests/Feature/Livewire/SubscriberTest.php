<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Plans\Facades\Plans;
use App\Http\Livewire\Subscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();

        // Some parts of the component can't be reached without
        // the userDetails set. Setting it through here with
        // the mount function on the component.
        $this->user->details()->update(
            factory('App\UserDetails')->make()->toArray()
        );
    }

    /** @test */
    public function it_emits_an_event_when_it_succeeds()
    {
        Livewire::test(Subscriber::class)
            ->set('plan_id', Plans::first()->id)
            ->set('userDetails', factory('App\UserDetails')->make()->toArray())
            ->call('submit')
            ->assertEmitted('readyForPaddle');
    }

    /** @test */
    public function it_requires_a_plan_id()
    {
        Livewire::test(Subscriber::class)
            ->set('plan_id', '')
            ->call('submit')
            ->assertHasErrors(['plan_id' => 'required']);
    }

    /** @test */
    public function it_requires_a_valid_plan_id()
    {
        Livewire::test(Subscriber::class)
            ->set('plan_id', 'invalid_plan_id')
            ->call('submit')
            ->assertHasErrors('plan_id');
    }

    /** @test */
    public function it_saves_the_user_details()
    {
        $userDetails = factory('App\UserDetails')->make();

        Livewire::test(Subscriber::class)
            ->set('plan_id', Plans::first()->id)
            ->set('userDetails', $userDetails->toArray())
            ->call('submit');

        $this->assertEquals($this->user->details->name, $userDetails->name);
    }

    /** @test */
    public function it_mounts_with_the_existing_plan()
    {
        $plan = Plans::first();

        $this->subscribe($plan->paddle_id);

        Livewire::test(Subscriber::class)
            ->assertSet('plan_id', $plan->id);
    }

    /** @test */
    public function it_mounts_with_the_existing_user_details()
    {
        Livewire::test(Subscriber::class)
            ->assertSet('userDetails.name', $this->user->details->name)
            ->assertSet('userDetails.address', $this->user->details->address)
            ->assertSet('userDetails.postal', $this->user->details->postal)
            ->assertSet('userDetails.city', $this->user->details->city)
            ->assertSet('userDetails.country', $this->user->details->country);
    }

    /** @test */
    public function it_swaps_the_subscription_if_the_user_is_already_subscribed()
    {
        Http::fake([
            'https://vendors.paddle.com/api/2.0/subscription/users/update' => Http::response([
                'success' => true,
                'response' => null,
            ]),
        ]);

        $oldPlan = Plans::first();
        $newPlan = Plans::last();

        $this->subscribe($oldPlan->paddle_id);

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
        $plan = Plans::first();

        $this->subscribe($plan->paddle_id);

        Livewire::test(Subscriber::class)
            ->set('plan_id', $plan->id)
            ->call('submit')
            ->assertHasErrors('plan_id');
    }
}
