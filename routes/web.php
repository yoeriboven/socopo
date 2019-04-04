<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });
//

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'PostController@index')->name('posts.index');

    /* Profile routes (return JSON) */
    Route::group(['middleware' => 'ajax'], function () {
        Route::get('api/profiles', 'ProfileController@index');
        Route::post('api/profiles', 'ProfileController@store');
        Route::delete('api/profiles/{profile}', 'ProfileController@destroy');
    });

    /* Setting routes */
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::post('settings/details', 'UserDetailsController@store');

    /* Slack authorization */
    Route::get('slack/login', 'SlackController@login')->name('slack.login');
    Route::get('slack/webhook', 'SlackController@webhook');
    Route::get('slack/logout', 'SlackController@logout')->name('slack.logout');
});

Auth::routes();

Route::get('ig', 'Controller@instagramTester');
