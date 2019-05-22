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

//Route::middleware('auth')->group(function () {});

Route::resource('aeronaves','AeronaveController')->middleware('auth');

Route::get('/password','SocioController@password')->middleware('auth');
Route::post('/password','SocioController@passwordUpdate')->middleware('auth');

Route::resource('socios','SocioController')->middleware('auth');

Route::resource('movimentos','MovimentoController')->middleware('auth');

Auth::routes(['verify' => true]);



Route::get('/home', 'HomeController@index')->name('home');



