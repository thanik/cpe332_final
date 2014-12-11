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
	
	public function showSession()
	{
		var_dump(Session::all());
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
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$data = array(
					'component_name' => Input::get('newLine_component_name'),
					'component_type' => Input::get('newLine_component_type'),
					'quantity' => intval(Input::get('newLine_quantity')),
					'rough_value' => floatval(Input::get('newLine_rough_value')),
					'notes' => Input::get('newLine_notes'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets');
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['component_name'] = Input::get('component_name');
				$temp_lineitem[intval(Input::get('item'))]['component_type'] = Input::get('component_type');
				$temp_lineitem[intval(Input::get('item'))]['quantity'] = intval(Input::get('quantity'));
				$temp_lineitem[intval(Input::get('item'))]['rough_value'] = floatval(Input::get('rough_value'));
				$temp_lineitem[intval(Input::get('item'))]['notes'] = Input::get('notes');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets');
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				unset($temp_lineitem[intval(Input::get('item'))]);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
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
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$data = array(
					'component_name' => Input::get('newLine_component_name'),
					'component_type' => Input::get('newLine_component_type'),
					'quantity' => intval(Input::get('newLine_quantity')),
					'rough_value' => floatval(Input::get('newLine_rough_value')),
					'notes' => Input::get('newLine_notes'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets');
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['component_name'] = Input::get('component_name');
				$temp_lineitem[intval(Input::get('item'))]['component_type'] = Input::get('component_type');
				$temp_lineitem[intval(Input::get('item'))]['quantity'] = intval(Input::get('quantity'));
				$temp_lineitem[intval(Input::get('item'))]['rough_value'] = floatval(Input::get('rough_value'));
				$temp_lineitem[intval(Input::get('item'))]['notes'] = Input::get('notes');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets');
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				unset($temp_lineitem[intval(Input::get('item'))]);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets');
			}
			else if(Input::get('action') == 'delete')
			{
				AssetLineItem::where('asset_id','=',Session::get('asset_id'))->delete();
				Asset::where('asset_id','=',Session::get('asset_id'))->delete();
				return Redirect::action('AssetsController@showNewItem'); 
			}
			else if(Input::get('action') == 'save')
			{
				$asset = Asset::where('asset_id','=',Session::get('asset_id'))->first();
				$asset->asset_name = Session::get('asset_name');
				$asset->asset_type = Session::get('asset_type');
				$asset->unit = Session::get('unit');
				$asset->yearly_depreciation = Session::get('yearly_depreciation');
				$asset->purchase_value = Session::get('purchase_value');
				$asset->purchase_date = Session::get('purchase_date');
				$asset->beginning_value = Session::get('beginning_value');
				$asset->depreciated_value = Session::get('depreciated_value');
				$asset->current_value = Session::get('current_value');
				
				AssetLineItem::where('asset_id','=',Session::get('asset_id'))->delete();
				$i = 0;
				$total_rough_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$assetlineitem = new AssetLineItem();
					$assetlineitem->no = $i;
					$assetlineitem->asset_id = Session::get('asset_id');
					$assetlineitem->component_name = $itm['component_name'];
					$assetlineitem->component_type = $itm['component_type'];
					$assetlineitem->quantity = $itm['quantity'];
					$assetlineitem->rough_value = $itm['rough_value'];
					$assetlineitem->notes = $itm['notes'];
					$total_rough_value += floatval($itm['rough_value']);
					$assetlineitem->save();
				}
				$asset->total_component = $i;
				$asset->total_component_value = $total_rough_value;
				
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				$asset->save();
				return View::make('assets');
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('asset_id', Input::get('asset_id'));
		Session::put('asset_name', Input::get('asset_name'));
		Session::put('asset_type', Input::get('asset_type'));
		Session::put('unit', Input::get('unit'));
		Session::put('yearly_depreciation', Input::get('yearly_depreciation'));
		Session::put('purchase_value', Input::get('purchase_value'));
		Session::put('purchase_date', Input::get('purchase_date'));
		Session::put('beginning_value', Input::get('beginning_value'));
		Session::put('depreciated_value', Input::get('depreciated_value'));
		Session::put('current_value', Input::get('current_value'));
		Session::put('total_component', Input::get('total_component'));
		Session::put('total_component_value', Input::get('total_component_value'));
		Session::put('dirtybit',Input::get('dirtybit'));
		Session::put('table',Input::get('table'));
		echo 'Success';
	}

}