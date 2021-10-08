<?php


Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/registros', 'HomeController@index')->name('registros');
Route::get('invoice', function(){
    return view('invoice');
});

Route::get('{path}',"HomeController@index")->where('path','([-a-z0-9_\s]+)');

Route::post('reset_password_without_token', 'AccountsController@validatePasswordRequest');
Route::post('reset_password_with_token', 'AccountsController@resetPassword');