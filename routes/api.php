<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('storage/{fileType}/{id}/{ext}/{thumbnail?}', 'StorageController@getFile'); // no auth check as some photos must be available public. TODO add auth check in controller
Route::delete('storage/{fileType}/{id}', 'StorageController@deleteFile')->middleware('auth:api');

Route::post('auth/login', 'LoginController@login');
Route::post('auth/refresh', 'LoginController@refreshToken');
Route::post('auth/logout', 'LoginController@logout');

Route::post('users', 'UserController@store');
Route::get('users', 'UserController@index')->middleware('auth:api', 'role:owner|admin');         
Route::get('users/{id}', 'UserController@show');
Route::put('users/{id}', 'UserController@update')->middleware('auth:api');
Route::delete('users/{id}', 'UserController@delete')->middleware('auth:api', 'role:owner|admin');

Route::post('users/{userId}/photo', 'ProfilePhotoController@upload')->middleware('auth:api');
Route::get('users/{userId}/photo', 'ProfilePhotoController@show')->middleware('auth:api');
Route::put('users/{userId}/photo/rotate/{degree}', 'ProfilePhotoController@rotate')->middleware('auth:api');
Route::delete('users/{userId}/photo', 'ProfilePhotoController@destroy')->middleware('auth:api');


Route::get('users/{id}/surveys', 'SurveyController@getAvailableSurveysbyUser')->middleware('auth:api');
Route::get('surveys/{id}', 'SurveyController@show');
Route::get('surveys', 'SurveyController@index')->middleware('auth:api');
Route::post('surveys', 'SurveyController@store')->middleware('auth:api');
Route::put('surveys/{id}', 'SurveyController@update')->middleware('auth:api');
Route::delete('surveys/{id}', 'SurveyController@destroy')->middleware('auth:api');
Route::get('shared/surveys', 'SurveyController@getSharedSurveys');
Route::get('surveys/company/{company}', 'SurveyController@getAvailableSurveysByCompany');