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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

Route::middleware('auth:api')->get('teste', function () {
return response()->json(['msg'=>'U have permitions!! :D :D'], 200);
});

Route::get('users/order', 'UserControllerApi@allUsersOrderByName');
Route::post('user', 'UserControllerApi@store');

Route::get('users/{user}', 'UserControllerApi@show');

//blocked/unblock route
Route::put('user/block/{id}', 'UserControllerApi@block');
Route::put('user/unblock/{id}', 'UserControllerApi@unBlock');

Route::get('users', 'UserControllerApi@allUsers');




Route::get('games', 'GameControllerApi@allGames');
Route::post('games', 'GameControllerApi@store');
Route::put('games', 'GameControllerApi@update');
Route::delete('games/{id}', 'GameControllerApi@destroy');
//Route::put('games/{id}', 'GameControllerApi@update');

Route::delete('user/{id}', 'UserControllerApi@deleteUser');



Route::get('images', 'ImageControllerApi@allImages');

Route::get('statistics', 'StatisticsControllerApi@getStatistics');
