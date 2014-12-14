<?php

class AssetsDepreciationController extends BaseController {
	public $page = array(
		'table_name' => 'depreciation',
		'form_name' => 'Asset Depreciation Form',
	);
	
	public function showNewItem()
	{
		Session::flush();
		Session::put('depreciation_no','NEW');
		Session::put('dirtybit','false');
		Session::put('table','depreciation');
		Session::put('mode','new');
		Session::put('lineitem', array());
		return View::make('assets_depreciation', $this->page);
	}
	
	public function postNewItem()
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsDepreciationController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_depreciation = Depreciation::where('depreciation_no','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('depreciation_no', 'NEW');
				Session::put('depreciation_date', $select_depreciation->depreciation_date);
				Session::put('for_month', $select_depreciation->for_month);
				Session::put('for_year', $select_depreciation->for_year);
				Session::put('total_depreciation', $select_depreciation->total_depreciation);

				$lineitems = DepreciationLineItem::where('depreciation_no','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','true');
				Session::put('table','depreciation');
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* check for duplicated lineitem */
				foreach($temp_lineitem as $itm)
				{
					if(Input::get('newLine_AssetID') == $itm['component_name'])
					{
						echo '<script type="text/javascript">alert("Error: Duplicated lineitem");</script>';
						return View::make('assets_depreciation', $this->page);
					}
				}
				/* add to lineitem session */
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
				return View::make('assets_depreciation', $this->page);
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
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				/* get new asset_id */
				$newid = sprintf("D%04d",intval(substr(Depreciation::max('asset_id'), 1)) + 1);
				
				$depreciation = new Depreciation;
				$depreciation->asset_id = $newid;
				$depreciation->asset_name = Session::get('asset_name');
				$depreciation->asset_type = Session::get('asset_type');
				$depreciation->unit = Session::get('unit');
				$depreciation->yearly_depreciation = Session::get('yearly_depreciation');
				$depreciation->purchase_value = Session::get('purchase_value');
				$depreciation->purchase_date = Session::get('purchase_date');
				$depreciation->beginning_value = Session::get('beginning_value');
				$depreciation->depreciated_value = Session::get('depreciated_value');
				$depreciation->current_value = Session::get('current_value');

				$i = 0;
				$total_rough_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$depreciationlineitem = new DepreciationLineItem();
					$depreciationlineitem->no = $i;
					$depreciationlineitem->asset_id = $newid;
					$depreciationlineitem->component_name = $itm['component_name'];
					$depreciationlineitem->component_type = $itm['component_type'];
					$depreciationlineitem->quantity = $itm['quantity'];
					$depreciationlineitem->rough_value = $itm['rough_value'];
					$depreciationlineitem->notes = $itm['notes'];
					$total_rough_value += (floatval($itm['rough_value']) * floatval($itm['quantity']));
					$depreciationlineitem->save();
				}
				$depreciation->total_component = $i;
				$depreciation->total_component_value = $total_rough_value;
				
				Session::put('dirtybit','false');
				Session::put('table','depreciation');
				$depreciation->save();
				return Redirect::action('AssetsDepreciationController@showItem', array($newid));
			}
		}
	}
	
	public function showItem($id)
	{
		$select_depreciation = Depreciation::where('depreciation_no','=',$id)->firstOrFail();
		Session::flush();
		Session::put('depreciation_no', $select_depreciation->depreciation_no);
		Session::put('depreciation_date', $select_depreciation->depreciation_date);
		Session::put('for_month', $select_depreciation->for_month);
		Session::put('for_year', $select_depreciation->for_year);
		Session::put('total_depreciation', $select_depreciation->total_depreciation);
		
		$lineitems = DepreciationLineItem::where('depreciation_no','=',$id)->get()->toArray();
		Session::put('lineitem',$lineitems);
		Session::put('dirtybit','false');
		Session::put('table','asset_id');
		return View::make('assets_depreciation', $this->page);
	}
	
	public function postItem($id)
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsDepreciationController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_depreciation = Depreciation::where('depreciation_no','=',$id)->firstOrFail();
				Session::flush();
				Session::put('depreciation_no', 'NEW');
				Session::put('depreciation_date', $select_depreciation->depreciation_date);
				Session::put('for_month', $select_depreciation->for_month);
				Session::put('for_year', $select_depreciation->for_year);
				Session::put('total_depreciation', $select_depreciation->total_depreciation);
				
				$lineitems = DepreciationLineItem::where('depreciation_no','=',$id)->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','true');
				Session::put('table','asset_id');
				return View::make('assets_depreciation', $this->page);	
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* check for duplicated lineitem */
				foreach($temp_lineitem as $itm)
				{
					if(Input::get('newLine_component_name') == $itm['component_name'] || Input::get('newLine_component_type') == $itm['component_type'])
					{
						echo '<script type="text/javascript">alert("Error: Duplicated lineitem");</script>';
						return View::make('assets_depreciation', $this->page);
					}
				}
				/* add to lineitem session */
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
				return View::make('assets_depreciation', $this->page);
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
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'delete')
			{
				DepreciationLineItem::where('depreciation_no','=',Session::get('depreciation_no'))->delete();
				Depreciation::where('depreciation_no','=',Session::get('depreciation_no'))->delete();
				return Redirect::action('AssetsDepreciationController@showNewItem'); 
			}
			else if(Input::get('action') == 'save')
			{
				$depreciation = Depreciation::where('asset_id','=',Session::get('asset_id'))->first();
				$depreciation->asset_name = Session::get('asset_name');
				$depreciation->asset_type = Session::get('asset_type');
				$depreciation->unit = Session::get('unit');
				$depreciation->yearly_depreciation = Session::get('yearly_depreciation');
				$depreciation->purchase_value = Session::get('purchase_value');
				$depreciation->purchase_date = Session::get('purchase_date');
				$depreciation->beginning_value = Session::get('beginning_value');
				$depreciation->depreciated_value = Session::get('depreciated_value');
				$depreciation->current_value = Session::get('current_value');
				
				DepreciationLineItem::where('asset_id','=',Session::get('asset_id'))->delete();
				$i = 0;
				$total_rough_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$depreciationlineitem = new DepreciationLineItem();
					$depreciationlineitem->no = $i;
					$depreciationlineitem->asset_id = Session::get('asset_id');
					$depreciationlineitem->component_name = $itm['component_name'];
					$depreciationlineitem->component_type = $itm['component_type'];
					$depreciationlineitem->quantity = $itm['quantity'];
					$depreciationlineitem->rough_value = $itm['rough_value'];
					$depreciationlineitem->notes = $itm['notes'];
					$total_rough_value += (floatval($itm['rough_value']) * floatval($itm['quantity']));
					$depreciationlineitem->save();
				}
				$depreciation->total_component = $i;
				$depreciation->total_component_value = $total_rough_value;
				
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				$depreciation->save();
				return View::make('assets_depreciation', $this->page);
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