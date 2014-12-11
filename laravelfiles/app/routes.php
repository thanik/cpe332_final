<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('sample');
});

Route::get('assets', array('as' => 'get_new_assets', 'uses' => 'AssetsController@showNewItem'));
Route::post('assets', array('as' => 'post_new_assets', 'uses' => 'AssetsController@postNewItem'));

Route::get('assets/{id}', array('as' => 'get_assets', 'uses' => 'AssetsController@showItem'));
Route::post('assets/{id}', array('as' => 'post_assets', 'uses' => 'AssetsController@postItem'));

/* AJAX Request */
Route::get('ajax/listofvalue', array('as' => 'listofvalue_ajax', 'uses' => 'ListOfValueController@show'));