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

/* Asset ID with components */
Route::get('assets', array('as' => 'get_new_assets', 'uses' => 'AssetsController@showNewItem'));
Route::post('assets', array('as' => 'post_new_assets', 'uses' => 'AssetsController@postNewItem'));

Route::get('assets/{id}', array('as' => 'get_assets', 'uses' => 'AssetsController@showItem'));
Route::post('assets/{id}', array('as' => 'post_assets', 'uses' => 'AssetsController@postItem'));

Route::get('debug', array('as' => 'debug_assets', 'uses' => 'AssetsController@showDebug'));

/* AJAX Request */
Route::get('ajax/listofvalue', array('as' => 'listofvalue_ajax', 'uses' => 'ListOfValueController@show'));
Route::post('ajax/update/assets', array('as' => 'asset_update_ajax', 'uses' => 'AssetsController@ajaxUpdateSession'));
Route::get('ajax/editlistofvalue', array('as' => 'edit_listofvalue_ajax', 'uses' => 'ListOfValueController@edit'));


/* Asset Depreciation */
Route::get('assets_depreciation', array('as' => 'get_new_assets_de', 'uses' => 'AssetsDepreciationController@showNewItem'));
Route::post('assets_depreciation', array('as' => 'post_new_assets_de', 'uses' => 'AssetsDepreciationController@postNewItem'));

Route::get('assets_depreciation/{id}', array('as' => 'get_assets_de', 'uses' => 'AssetsDepreciationController@showItem'));
Route::post('assets_depreciation/{id}', array('as' => 'post_assets_de', 'uses' => 'AssetsDepreciationController@postItem'));

/* AJAX Request */
Route::post('ajax/update/assets_depreciation', array('as' => 'asset_update_ajax', 'uses' => 'AssetsDepreciationController@ajaxUpdateSession'));