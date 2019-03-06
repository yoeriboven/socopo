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

    Route::post('/profiles', 'ProfileController@store')->name('profiles.store');

    Route::get('api/profiles', 'ProfileController@index');
    Route::delete('api/profiles/{profile}', 'ProfileController@destroy');
});

Auth::routes();

Route::get('ig', 'Controller@instagramTester');
