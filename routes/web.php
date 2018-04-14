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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// OAuth Routes
Route::get('auth/{provider}', 'AuthController@redirectToProvider')->name('OAuthStaffConnect');
Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback');
Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');