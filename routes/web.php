<?php

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('posts', 'PostController@index')->name('home');

    /* Profile routes (return JSON) */
    Route::group(['middleware' => 'ajax'], function () {
        Route::get('api/profiles', 'ProfileController@index');
        Route::post('api/profiles', 'ProfileController@store');
        Route::delete('api/profiles/{profile}', 'ProfileController@destroy');
    });

    /* Setting routes */
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::post('settings/details', 'UserDetailsController@store')->name('user_details.store');
    Route::post('settings/change_password', 'Auth\ChangePasswordController@store')->name('change_password.store');

    /* Upgrade Plan */
    Route::get('upgrade', 'SubscriptionController@index')->name('upgrade');
    Route::delete('subscription/cancel/{subscription}', 'SubscriptionController@destroy')->name('subscription.destroy');
    Route::view('subscription/waiting-for-confirmation', 'upgrade.waiting-for-confirmation')->name('subscription.waiting-for-confirmation');

    /* Slack authorization */
    Route::get('slack/login', 'SlackController@login')->name('slack.login');
    Route::get('slack/webhook', 'SlackController@webhook');
    Route::get('slack/logout', 'SlackController@logout')->name('slack.logout');
});

/* Authorization */
Auth::routes(['verify' => true]);

/* Front page */
Route::view('/', 'front');
