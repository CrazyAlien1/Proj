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

//Route::get('/login', function () {
//    return view('popups.login');//Chamar controlador que devolva ajax
//})->name('login');


Route::get('/', function(){
    return view('vue.lobby');
});

Route::get('/home', function(){
    return view('vue.lobby');
});

Route::get('/administration', function(){
    return view('vue.administration');
});
//Route::resource('users', 'AdministratorController');
//Route::resource('games', 'AdministratorController');
//Route::resource('uploadImg', 'AdministratorController');

//Auth::routes();
//
//Route::get('/', 'HomeController@index')->name('home');
//Route::get('/home', 'HomeController@index')->name('home');
