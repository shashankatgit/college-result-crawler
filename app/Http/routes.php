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


Route::get('home', ['as' =>'results.home',function() {
   return view('results.home');
}]);

Route::get('getSingleResult',[
   'uses' => 'ResultsController@postGetSingleResult',
    'as' => 'results.getSingleResult'
    ]);

Route::post('getBulkResult',[
    'uses' => 'ResultsController@postGetBulkResult',
    'as' => 'results.getBulkResult'
]);

Route::get('getPhoto',[
    'uses' => 'PhotosController@getPhoto',
    'as' => 'results.getPhoto'
]);

Route::get('ajaxResponse',[
   'uses' => 'ResultsController@postGetResultJSON',
    'as' => 'results.ajaxResponse'
]);




