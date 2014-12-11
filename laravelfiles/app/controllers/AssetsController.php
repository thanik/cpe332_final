<?php

class AssetsController extends BaseController {
	public function showNewItem()
	{
		Session::flush();
		Session::put('asset_id','NEW');
		Session::put('dirtybit','false');
		Session::put('table','asset_id');
		Session::put('mode','new');
		Session::put('lineitem', array());
		return View::make('assets');
	}
	
	public function postNewItem()
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_asset = Asset::where('asset_id','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('asset_id', 'NEW');
				Session::put('asset_name', $select_asset->asset_name);
				Session::put('asset_type', $select_asset->asset_type);
				Session::put('unit', $select_asset->unit);
				Session::put('yearly_depreciation', $select_asset->yearly_depreciation);
				Session::put('purchase_value', $select_asset->purchase_value);
				Session::put('purchase_date', $select_asset->purchase_date);
				Session::put('beginning_value', $select_asset->beginning_value);
				Session::put('depreciated_value', $select_asset->depreciated_value);
				Session::put('current_value', $select_asset->current_value);
				Session::put('total_component', $select_asset->total_component);
				Session::put('total_component_value', $select_asset->total_component_value);
				$lineitems = AssetLineItem::where('asset_id','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				return View::make('assets');
			}
			else if(Input::get('action') == 'save')
			{
				
			}
		}
	}
	
	public function showItem($id)
	{
		$select_asset = Asset::where('asset_id','=',$id)->firstOrFail();
		Session::flush();
		Session::put('asset_id', $select_asset->asset_id);
		Session::put('asset_name', $select_asset->asset_name);
		Session::put('asset_type', $select_asset->asset_type);
		Session::put('unit', $select_asset->unit);
		Session::put('yearly_depreciation', $select_asset->yearly_depreciation);
		Session::put('purchase_value', $select_asset->purchase_value);
		Session::put('purchase_date', $select_asset->purchase_date);
		Session::put('beginning_value', $select_asset->beginning_value);
		Session::put('depreciated_value', $select_asset->depreciated_value);
		Session::put('current_value', $select_asset->current_value);
		Session::put('total_component', $select_asset->total_component);
		Session::put('total_component_value', $select_asset->total_component_value);
		$lineitems = AssetLineItem::where('asset_id','=',$id)->get()->toArray();
		Session::put('lineitem',$lineitems);
		Session::put('dirtybit','false');
		Session::put('table','asset_id');
		return View::make('assets');
	}
	
	public function postItem($id)
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_asset = Asset::where('asset_id','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('asset_id', 'NEW');
				Session::put('asset_name', $select_asset->asset_name);
				Session::put('asset_type', $select_asset->asset_type);
				Session::put('unit', $select_asset->unit);
				Session::put('yearly_depreciation', $select_asset->yearly_depreciation);
				Session::put('purchase_value', $select_asset->purchase_value);
				Session::put('purchase_date', $select_asset->purchase_date);
				Session::put('beginning_value', $select_asset->beginning_value);
				Session::put('depreciated_value', $select_asset->depreciated_value);
				Session::put('current_value', $select_asset->current_value);
				Session::put('total_component', $select_asset->total_component);
				Session::put('total_component_value', $select_asset->total_component_value);
				$lineitems = AssetLineItem::where('asset_id','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				return View::make('assets');	
			}
			else if(Input::get('action') == 'save')
			{
				
			}
		}
	}

}