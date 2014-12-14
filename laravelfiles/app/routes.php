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
	return Redirect::action('AssetsController@showNewItem');
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

/* Asset Purchases */
Route::get('assets_purchase', array('as' => 'get_new_assets_pu', 'uses' => 'AssetsPurchaseController@showNewItem'));
Route::post('assets_purchase', array('as' => 'post_new_assets_pu', 'uses' => 'AssetsPurchaseController@postNewItem'));

Route::get('assets_purchase/{id}', array('as' => 'get_assets_pu', 'uses' => 'AssetsPurchaseController@showItem'));
Route::post('assets_purchase/{id}', array('as' => 'post_assets_pu', 'uses' => 'AssetsPurchaseController@postItem'));

/* AJAX Request */
Route::post('ajax/update/assets_purchase', array('as' => 'asset_update_ajax', 'uses' => 'AssetsPurchaseController@ajaxUpdateSession'));

/* Asset Sales */
Route::get('assets_sale', array('as' => 'get_new_assets_sa', 'uses' => 'AssetsSaleController@showNewItem'));
Route::post('assets_sale', array('as' => 'post_new_assets_sa', 'uses' => 'AssetsSaleController@postNewItem'));

Route::get('assets_sale/{id}', array('as' => 'get_assets_sa', 'uses' => 'AssetsSaleController@showItem'));
Route::post('assets_sale/{id}', array('as' => 'post_assets_sa', 'uses' => 'AssetsSaleController@postItem'));

/* AJAX Request */
Route::post('ajax/update/assets_sale', array('as' => 'asset_update_ajax', 'uses' => 'AssetsSaleController@ajaxUpdateSession'));

/* Asset Location Movement */
Route::get('assets_movement', array('as' => 'get_new_assets_mo', 'uses' => 'AssetsMovementController@showNewItem'));
Route::post('assets_movement', array('as' => 'post_new_assets_mo', 'uses' => 'AssetsMovementController@postNewItem'));

Route::get('assets_movement/{id}', array('as' => 'get_assets_mo', 'uses' => 'AssetsMovementController@showItem'));
Route::post('assets_movement/{id}', array('as' => 'post_assets_mo', 'uses' => 'AssetsMovementController@postItem'));

/* AJAX Request */
Route::post('ajax/update/assets_movement', array('as' => 'asset_update_ajax', 'uses' => 'AssetsMovementController@ajaxUpdateSession'));

/* Print */
Route::get('print/asset_id', array('as' => 'print_asset_id', 'uses' => 'PrintController@print_assets'));
Route::get('print/sales', array('as' => 'print_sales', 'uses' => 'PrintController@print_sales'));
Route::get('print/depreciation', array('as' => 'print_depreciation', 'uses' => 'PrintController@print_depreciation'));
Route::get('print/movement', array('as' => 'print_movement', 'uses' => 'PrintController@print_movement'));
Route::get('print/purchases', array('as' => 'print_purchases', 'uses' => 'PrintController@print_purchases'));
