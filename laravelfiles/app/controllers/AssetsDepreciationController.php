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
		Session::put('total_depreciation','0.00');
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
					if(Input::get('newLine_AssetID') == $itm['asset_id'])
					{
						echo '<script type="text/javascript">alert("Error: Duplicated lineitem");</script>';
						return View::make('assets_depreciation', $this->page);
					}
				}
				/* add to lineitem session */
				$data = array(
					'asset_id' => Input::get('newLine_AssetID'),
					'asset_name' => Input::get('newLine_AssetName'),
					'asset_type' => Input::get('newLine_AssetType'),
					'depreciation_percent' => floatval(Input::get('newLine_DepreciationPercent')),
					'purchase_value' => Input::get('newLine_PurchaseValue'),
					'beginning_value' => Input::get('newLine_BeginningValue'),
					'depreciation_value' => Input::get('newLine_DepreciationValue'),
					'current_value' => Input::get('newLine_CurrentValue'),
					'depreciation_value_month' => Input::get('newLine_DepreciationValueMonth'),
					'new_depreciation_value_month' => Input::get('newLine_NewDepreciationValueMonth'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);

				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['asset_id'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['asset_name'] = Input::get('AssetName');
				$temp_lineitem[intval(Input::get('item'))]['asset_type'] = Input::get('AssetType');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_percent'] = Input::get('DepreciationPercent');
				$temp_lineitem[intval(Input::get('item'))]['purchase_value'] = Input::get('PurchaseValue');
				$temp_lineitem[intval(Input::get('item'))]['beginning_value'] = Input::get('BeginningValue');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_value'] = Input::get('DepreciationValue');
				$temp_lineitem[intval(Input::get('item'))]['current_value'] = Input::get('CurrentValue');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_value_month'] = Input::get('DepreciationValueMonth');
				$temp_lineitem[intval(Input::get('item'))]['new_depreciation_value_month'] = Input::get('NewDepreciationValueMonth');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				/* get new asset_id */
				$newid = sprintf("D%04d",intval(substr(Depreciation::max('depreciation_no'), 1)) + 1);
				
				$depreciation = new Depreciation;
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets_depreciation', $this->page);
				}
				$depreciation->depreciation_no = $newid;
				$depreciation->depreciation_date = Session::get('depreciation_date');
				$depreciation->for_month = Session::get('for_month');
				$depreciation->for_year = Session::get('for_year');

				$i = 0;
				$total_depreciation_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$depreciationlineitem = new DepreciationLineItem();
					$depreciationlineitem->item_no = $i;
					$depreciationlineitem->depreciation_no = $newid;
					$depreciationlineitem->asset_id = $itm['asset_id'];
					$depreciationlineitem->asset_name = $itm['asset_name'];
					$depreciationlineitem->asset_type = $itm['asset_type'];
					$depreciationlineitem->depreciation_percent = $itm['depreciation_percent'];
					$depreciationlineitem->purchase_value = $itm['purchase_value'];
					$depreciationlineitem->beginning_value = $itm['beginning_value'];
					$depreciationlineitem->depreciation_value = $itm['depreciation_value'];
					$depreciationlineitem->current_value = $itm['current_value'];
					$depreciationlineitem->depreciation_value_month = $itm['depreciation_value_month'];
					$depreciationlineitem->new_depreciation_value_month = $itm['new_depreciation_value_month'];
					$total_depreciation_value += floatval($itm['depreciation_value_month']);
					$depreciationlineitem->save();
				}
				$depreciation->total_depreciation = $total_depreciation_value;
				
				Session::put('dirtybit','false');
				Session::put('table','depreciation');
				$depreciation->save();
				return Redirect::action('AssetsDepreciationController@showItem', array($newid));
			}
			else if(Input::get('action') == 'getallasset')
			{
				$temp_lineitem = array();
				foreach(Asset::all()->toArray() as $asset)
				{
					$data = array(
						'asset_id' => $asset['asset_id'],
						'asset_name' => $asset['asset_name'],
						'asset_type' => $asset['asset_type'],
						'depreciation_percent' => $asset['yearly_depreciation'],
						'purchase_value' => $asset['purchase_value'],
						'beginning_value' => $asset['beginning_value'],
						'depreciation_value' => $asset['depreciated_value'],
						'current_value' => $asset['current_value'],
						'depreciation_value_month' => round((floatval($asset['yearly_depreciation']) * floatval($asset['purchase_value']) / 100) / 12,2),
						'new_depreciation_value_month' => round((floatval($asset['current_value']) - (floatval($asset['yearly_depreciation']) * floatval($asset['purchase_value']) / 100) / 12),2),
					);
					array_push($temp_lineitem, $data);
				}
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
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
					if(Input::get('newLine_AssetID') == $itm['asset_id'])
					{
						echo '<script type="text/javascript">alert("Error: Duplicated lineitem");</script>';
						return View::make('assets_depreciation', $this->page);
					}
				}
				/* add to lineitem session */
				$data = array(
					'asset_id' => Input::get('newLine_AssetID'),
					'asset_name' => Input::get('newLine_AssetName'),
					'asset_type' => Input::get('newLine_AssetType'),
					'depreciation_percent' => floatval(Input::get('newLine_DepreciationPercent')),
					'purchase_value' => Input::get('newLine_PurchaseValue'),
					'beginning_value' => Input::get('newLine_BeginningValue'),
					'depreciation_value' => Input::get('newLine_DepreciationValue'),
					'current_value' => Input::get('newLine_CurrentValue'),
					'depreciation_value_month' => Input::get('newLine_DepreciationValueMonth'),
					'new_depreciation_value_month' => Input::get('newLine_NewDepreciationValueMonth'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['asset_id'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['asset_name'] = Input::get('AssetName');
				$temp_lineitem[intval(Input::get('item'))]['asset_type'] = Input::get('AssetType');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_percent'] = Input::get('DepreciationPercent');
				$temp_lineitem[intval(Input::get('item'))]['purchase_value'] = Input::get('PurchaseValue');
				$temp_lineitem[intval(Input::get('item'))]['beginning_value'] = Input::get('BeginningValue');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_value'] = Input::get('DepreciationValue');
				$temp_lineitem[intval(Input::get('item'))]['current_value'] = Input::get('CurrentValue');
				$temp_lineitem[intval(Input::get('item'))]['depreciation_value_month'] = Input::get('DepreciationValueMonth');
				$temp_lineitem[intval(Input::get('item'))]['new_depreciation_value_month'] = Input::get('NewDepreciationValueMonth');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
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
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets_depreciation', $this->page);
				}
				$depreciation = Depreciation::where('depreciation_no','=',Session::get('depreciation_no'))->first();
				$depreciation->depreciation_date = Session::get('depreciation_date');
				$depreciation->for_month = Session::get('for_month');
				$depreciation->for_year = Session::get('for_year');
				
				DepreciationLineItem::where('depreciation_no','=',Session::get('depreciation_no'))->delete();
				$i = 0;
				$total_depreciation_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$depreciationlineitem = new DepreciationLineItem();
					$depreciationlineitem->item_no = $i;
					$depreciationlineitem->depreciation_no = Session::get('depreciation_no');
					$depreciationlineitem->asset_id = $itm['asset_id'];
					$depreciationlineitem->asset_name = $itm['asset_name'];
					$depreciationlineitem->asset_type = $itm['asset_type'];
					$depreciationlineitem->depreciation_percent = $itm['depreciation_percent'];
					$depreciationlineitem->purchase_value = $itm['purchase_value'];
					$depreciationlineitem->beginning_value = $itm['beginning_value'];
					$depreciationlineitem->depreciation_value = $itm['depreciation_value'];
					$depreciationlineitem->current_value = $itm['current_value'];
					$depreciationlineitem->depreciation_value_month = $itm['depreciation_value_month'];
					$depreciationlineitem->new_depreciation_value_month = $itm['new_depreciation_value_month'];
					$total_depreciation_value += floatval($itm['depreciation_value_month']);
					$depreciationlineitem->save();
				}
				$depreciation->total_depreciation = $total_depreciation_value;
				
				Session::put('dirtybit','false');
				Session::put('table','depreciation');
				$depreciation->save();
				return View::make('assets_depreciation', $this->page);
			}
			else if(Input::get('action') == 'getallasset')
			{
				$temp_lineitem = array();
				foreach(Asset::all()->toArray() as $asset)
				{
					$data = array(
						'asset_id' => $asset['asset_id'],
						'asset_name' => $asset['asset_name'],
						'asset_type' => $asset['asset_type'],
						'depreciation_percent' => $asset['yearly_depreciation'],
						'purchase_value' => $asset['purchase_value'],
						'beginning_value' => $asset['beginning_value'],
						'depreciation_value' => $asset['depreciated_value'],
						'current_value' => $asset['current_value'],
						'depreciation_value_month' => round((floatval($asset['yearly_depreciation']) * floatval($asset['purchase_value']) / 100) / 12,2),
						'new_depreciation_value_month' => round((floatval($asset['current_value']) - (floatval($asset['yearly_depreciation']) * floatval($asset['purchase_value']) / 100) / 12),2),
					);
					array_push($temp_lineitem, $data);
				}
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['depreciation_value_month']);
				}
				Session::put('total_depreciation', $Total);
				return View::make('assets_depreciation', $this->page);
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('depreciation_no', Input::get('depreciation_no'));
		Session::put('depreciation_date', Input::get('depreciation_date'));
		Session::put('for_month', Input::get('for_month'));
		Session::put('for_year', Input::get('for_year'));
		Session::put('dirtybit',Input::get('dirtybit'));
		Session::put('table',Input::get('table'));
		echo 'Success';
	}

}