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

/* Route::match(array('GET', 'POST'),'assets_depreciation', array('as' => 'new_assets_depreciation', 'uses' => 'AssetsDepreciationController@newItem'));

Route::match(array('GET', 'POST'),'assets_purchase', array('as' => 'new_assets_purchase', 'uses' => 'AssetsPurchaseController@newItem'));

Route::match(array('GET', 'POST'),'assets_sale', array('as' => 'new_assets_sale', 'uses' => 'AssetsSaleController@newItem'));

Route::match(array('GET', 'POST'),'assets_location', array('as' => 'new_assets_location', 'uses' => 'AssetsLocationController@newItem')); */

Route::get('assets/{id}', array('as' => 'get_assets', 'uses' => 'AssetsController@showItem'));
Route::post('assets/{id}', array('as' => 'post_assets', 'uses' => 'AssetsController@postItem'));

/* Route::match(array('GET', 'POST'),'assets_depreciation/{id}', array('as' => 'view_assets_depreciation', 'uses' => 'AssetsDepreciationController@view'));

Route::match(array('GET', 'POST'),'assets_purchase/{id}', array('as' => 'view_assets_purchase', 'uses' => 'AssetsPurchaseController@view'));

Route::match(array('GET', 'POST'),'assets_sale/{id}', array('as' => 'view_assets_sale', 'uses' => 'AssetsSaleController@view'));

Route::match(array('GET', 'POST'),'assets_location/{id}', array('as' => 'view_assets_location', 'uses' => 'AssetsLocationController@view')); */

/* AJAX Request */
Route::get('ajax/listofvalue', array('as' => 'listofvalue_ajax', 'uses' => 'ListOfValueController@show'));