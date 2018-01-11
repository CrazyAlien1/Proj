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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users/order', 'UserControllerApi@allUsersOrderByName');

//blocked/unblock route
Route::put('user/block/{id}', 'UserControllerApi@block');
Route::put('user/unblock/{id}', 'UserControllerApi@unBlock');

Route::get('users/{user}', 'UserControllerApi@show');
Route::get('users', 'UserControllerApi@allUsers');




Route::get('games', 'GameControllerApi@allGames');
Route::post('games', 'GameControllerApi@store');
Route::delete('games/{id}', 'GameControllerApi@destroy');

Route::delete('user/{id}', 'UserControllerApi@deleteUser');



Route::get('images', 'ImageControllerApi@allImages');

Route::get('statistics', 'StatisticsControllerApi@getStatistics');