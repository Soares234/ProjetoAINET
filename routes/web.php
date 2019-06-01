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
Route::put('/aeronaves/','AeronaveController@update')->middleware('auth');
Route::get('/aeronaves/{aeronave}/pilotos','AeronaveController@indexPilotosAutorizados')->middleware('auth');
Route::post('/aeronaves/{aeronave}/pilotos/{piloto_id}','AeronaveController@addPilotoToAeronave')->middleware('auth');
Route::delete('/aeronaves/{aeronave}/pilotos/{piloto_id}','AeronaveController@removePilotoFromAeronave')->middleware('auth');



Route::resource('aeronaves','AeronaveController')->middleware('auth');

Route::get('/email/verify/{id}','VerificationController@verify');

Route::get('/password','SocioController@password')->middleware('auth');

Route::resource('movimentos','MovimentoController')->middleware('auth');

Route::post('/socios','SocioController@store')->middleware('auth');

Route::put('/socios/{id}','SocioController@update')->middleware('auth');

Route::patch('/password','SocioController@passwordUpdate')->middleware('auth');

Route::resource('socios','SocioController')->middleware('auth');

Route::get('/pilotos/{id}/licenca','PilotoController@getLicenca')->middleware('auth');

Route::get('/pilotos/{id}/certificado','PilotoController@getCertificado')->middleware('auth');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/about', function () {
    return view('about');
});
