<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Authentication routes.
Route::prefix('v1')->group(function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('getUser', 'Api\AuthController@getUser');
        Route::post('logout', 'Api\AuthController@logout');
        Route::post('logoutAllDevices', 'Api\AuthController@logoutFromAllDevices');
    });
});


// Trips CRUD routes.
Route::group(['middleware' => ['auth:api']], function ()
{
    Route::post('trips', 'Api\TripController@store');
    Route::put('trips/{trip}', 'Api\TripController@update');
    Route::delete('trips/{trip}', 'Api\TripController@destroy');
});
Route::get('trips', 'Api\TripController@index');
Route::get('trips/{trip}', 'Api\TripController@show');


// Offers routes
Route::group(['middleware' => ['auth:api']], function ()
{
    Route::post('offers', 'Api\OfferController@store');
    Route::put('offers/{offer}', 'Api\OfferController@update');
    Route::delete('offers/{offer}', 'Api\OfferController@destroy');
});
