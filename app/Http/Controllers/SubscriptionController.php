<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Paddle\Subscription;
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
        return view('upgrade.upgrade');
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

        return back()->with('success', 'Your subscription has been cancelled.');
    }
}
