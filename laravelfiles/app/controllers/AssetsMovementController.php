<?php

class AssetsMovementController extends BaseController {
	public $page = array(
		'table_name' => 'movement',
		'form_name' => 'Asset Location Movement Form',
	);
	
	public function showNewItem()
	{
		Session::flush();
		Session::put('assetmoveNo','NEW');
		Session::put('dirtybit','false');
		Session::put('table','movement');
		Session::put('mode','new');
		Session::put('lineitem', array());
		return View::make('assets_movement', $this->page);
	}
	
	public function postNewItem()
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsMovementController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_movement = Movement::where('assetmoveNo','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('assetmoveNo', 'NEW');
				Session::put('movementDate', $select_movement->movementDate);
				Session::put('assetmoveReason', $select_movement->assetmoveReason);

				$lineitems = MovementLineItem::where('assetmoveNo','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','movement');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* check for duplicated lineitem */
				foreach($temp_lineitem as $itm)
				{
					if(Input::get('newLine_asset_id') == $itm['asset_id'] || Input::get('newLine_asset_name') == $itm['asset_name'])
					{
						echo '<script type="text/javascript">alert("Error: Duplicated lineitem");</script>';
						return View::make('assets_movement', $this->page);
					}
				}
				/* add to lineitem session */
				$data = array(
					'asset_id' => Input::get('newLine_asset_id'),
					'asset_name' => Input::get('newLine_asset_name'),
					'currentLocation' => Input::get('newLine_currentLocation'),
					'newLocation' => Input::get('newLine_newLocation'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['asset_id'] = Input::get('asset_id');
				$temp_lineitem[intval(Input::get('item'))]['asset_name'] = Input::get('asset_name');
				$temp_lineitem[intval(Input::get('item'))]['currentLocation'] = Input::get('currentLocation');
				$temp_lineitem[intval(Input::get('item'))]['newLocation'] = Input::get('newLocation');
				
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets_movement', $this->page);
				}
				/* get new asset_id */
				$newid = sprintf("M%04d",intval(substr(Movement::max('assetmoveNo'), 1)) + 1);
				
				$movement = new Movement;
				$movement->assetmoveNo = $newid;
				$movement->movementDate = Session::get('movementDate');
				$movement->assetmoveReason = Session::get('assetmoveReason');

				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$movementlineitem = new MovementLineItem();
					$movementlineitem->assetmoveNo = $newid;
					$movementlineitem->moveList = $i;
					$movementlineitem->asset_id = $itm['asset_id'];
					$movementlineitem->asset_name = $itm['asset_name'];
					$movementlineitem->currentLocation = $itm['currentLocation'];
					$movementlineitem->newLocation = $itm['newLocation'];
					$movementlineitem->save();
					
					$asset_edit = Asset::where('asset_id','=',$itm['asset_id'])->first();
					$asset_edit->current_location = $itm['newLocation'];
					$asset_edit->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','movement');
				$movement->save();
				return Redirect::action('AssetsMovementController@showItem', array($newid));
			}
		}
	}
	
	public function showItem($id)
	{
		$select_movement = Movement::where('assetmoveNo','=',$id)->firstOrFail();
		Session::flush();
		Session::put('assetmoveNo', $select_movement->assetmoveNo);
		Session::put('movementDate', $select_movement->movementDate);
		Session::put('assetmoveReason', $select_movement->assetmoveReason);

		$lineitems = MovementLineItem::where('assetmoveNo','=',$id)->get()->toArray();
		Session::put('lineitem',$lineitems);
		Session::put('dirtybit','false');
		Session::put('table','movement');
		return View::make('assets_movement', $this->page);
	}
	
	public function postItem($id)
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsMovementController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_movement = Movement::where('assetmoveNo','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('assetmoveNo', 'NEW');
				Session::put('movementDate', $select_movement->movementDate);
				Session::put('assetmoveReason', $select_movement->assetmoveReason);

				$lineitems = MovementLineItem::where('assetmoveNo','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','movement');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'asset_id' => Input::get('newLine_asset_id'),
					'asset_name' => Input::get('newLine_asset_name'),
					'currentLocation' => Input::get('newLine_currentLocation'),
					'newLocation' => Input::get('newLine_newLocation'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['asset_id'] = Input::get('asset_id');
				$temp_lineitem[intval(Input::get('item'))]['asset_name'] = Input::get('asset_name');
				$temp_lineitem[intval(Input::get('item'))]['currentLocation'] = Input::get('currentLocation');
				$temp_lineitem[intval(Input::get('item'))]['newLocation'] = Input::get('newLocation');
				
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_movement', $this->page);
			}
			else if(Input::get('action') == 'delete')
			{
				MovementLineItem::where('assetmoveNo','=',Session::get('assetmoveNo'))->delete();
				Movement::where('assetmoveNo','=',Session::get('assetmoveNo'))->delete();
				return Redirect::action('AssetsMovementController@showNewItem'); 
			}
			else if(Input::get('action') == 'save')
			{
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets_movement', $this->page);
				}
				$movement = Movement::where('assetmoveNo','=',Session::get('assetmoveNo'))->first();
				$movement->movementDate = Session::get('movementDate');
				$movement->assetmoveReason = Session::get('assetmoveReason');

				MovementLineItem::where('assetmoveNo','=',Session::get('assetmoveNo'))->delete();
				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$movementlineitem = new MovementLineItem();
					$movementlineitem->assetmoveNo = Session::get('assetmoveNo');
					$movementlineitem->moveList = $i;
					$movementlineitem->asset_id = $itm['asset_id'];
					$movementlineitem->asset_name = $itm['asset_name'];
					$movementlineitem->currentLocation = $itm['currentLocation'];
					$movementlineitem->newLocation = $itm['newLocation'];
					$movementlineitem->save();
					
					$asset_edit = Asset::where('asset_id','=',$itm['asset_id'])->first();
					$asset_edit->current_location = $itm['newLocation'];
					$asset_edit->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','purchases');
				$movement->save();
				return View::make('assets_movement', $this->page);
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('assetmoveNo', Input::get('assetmoveNo'));
		Session::put('movementDate', Input::get('movementDate'));
		Session::put('assetmoveReason', Input::get('assetmoveReason'));
		echo 'Success';
	}

}