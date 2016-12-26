<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
//

Route::get('get',[
   'uses' => 'ResultController@getResult',
    ]);

Route::get('form', function() {
   return view('results.form');
});

Route::get('getResult',[
   'uses' => 'ResultController@getResult',
    ]);





