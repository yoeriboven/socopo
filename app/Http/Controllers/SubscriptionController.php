<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\Subscription;
use App\Services\SubscriptionsService;
use App\Http\Requests\SubscriptionRequest;
use App\Exceptions\AlreadySubscribedToPlanException;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payLink = auth()->user()->newSubscription('default', 627813)
            ->returnTo(route('home'))
            ->create();

        return view('upgrade.upgrade', compact('payLink'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionRequest $request, SubscriptionsService $subscriptions)
    {
        try {
            $subscriptions->upgrade($request);
        } catch (AlreadySubscribedToPlanException $e) {
            return back()->withErrors(['You are already subscribed to this plan.']);
        } catch (\Exception $e) {
            app('sentry')->captureException($e);

            return back()->withErrors(['Upgrading your account has failed. Please try again later.']);
        }

        return redirect()->route('settings')->with(['success' => 'Your account has been upgraded.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->cancel();

        return back()->with('success', 'Subscription cancelled.');
    }
}
