<?php

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dash', function () {
    return view('adminpanel.dash');
});


Route::get('/user', 'AuthenticateController@check')->middleware('jwt.auth');
/*
Route::get('/test/{pass}', function (Request $request) {
    return bcrypt($request->pass);
});

Route::get('/auth/exp', 'AuthenticateController@expTime');
*/

//** Guests **/

Route::get('/guests/retrive', 'GuestsController@index');

Route::get('/guests/guest/{id}', 'GuestsController@show');

Route::patch('/guests/update/{id}', 'GuestsController@update');

Route::get('/find/oldGuest/{guest}' , ['uses' =>'GuestsController@showGuests'])->middleware('checkAdminOrEditor');;



/**	User **/

Route::post('/user/authentificate', ['uses'=>'AuthenticateController@authenticate']);

Route::post('/refreshToken', ['uses'=>'AuthenticateController@refreshToken']);

Route::post('/newUser', ['uses'=>'UsersController@create'])->middleware('checkAdminOrEditor');;



/**	Rezervacije **/

Route::post('/rezervacije/nova', ['uses'=>'ReservationController@store'])->middleware('checkAdminOrEditor');;	//Stvori novu

Route::post('/rezervacije/novaWthGuest', 'ReservationController@storeWithoutGuest')->middleware('checkAdminOrEditor');;	//Stvori novu

Route::post('/rezervacije/retrive', 'ReservationController@index');	// Dohvati sve

Route::patch('/rezervacije/update/{id}', 'ReservationController@update')->middleware('checkAdminOrEditor');	// Dohvati sve

Route::post('/statistika/byYear/{year}', ['uses' => 'ReservationController@getRezByYear']);	// Dohvati sve