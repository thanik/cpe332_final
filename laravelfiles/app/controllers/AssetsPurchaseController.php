<?php

class AssetsPurchaseController extends BaseController {
	public $page = array(
		'table_name' => 'purchases',
		'form_name' => 'Asset Purchase Form',
	);
	
	public function showNewItem()
	{
		Session::flush();
		Session::put('InvoiceNo','NEW');
		Session::put('Total', '0.00');
		Session::put('VAT', '0.00');
		Session::put('AmountDue', '0.00');
		Session::put('dirtybit','false');
		Session::put('table','purchases');
		Session::put('mode','new');
		Session::put('lineitem', array());
		return View::make('assets_purchase', $this->page);
	}
	
	public function postNewItem()
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsPurchaseController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_purchase = Purchase::where('InvoiceNo','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('InvoiceNo', 'NEW');
				Session::put('InvoiceDate', $select_purchase->InvoiceDate);
				Session::put('SupplierCode', $select_purchase->SupplierCode);
				Session::put('SupplierName', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Name);
				Session::put('SupplierAddress', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Address);
				Session::put('PaymentDueDate', $select_purchase->PaymentDueDate);
				Session::put('PaymentTerm', $select_purchase->PaymentTerm);
				Session::put('Total', $select_purchase->Total);
				Session::put('VAT', $select_purchase->VAT);
				Session::put('AmountDue', $select_purchase->AmountDue);
				$lineitems = PurchaseLineItem::where('InvoiceNo','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','purchases');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('AssetID'),
					'Price' => Input::get('Price'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['Price'] = Input::get('Price');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				/* get new asset_id
				$newid = sprintf("A%04d",intval(substr(Asset::max('asset_id'), 1)) + 1);
				
				$asset = new Asset;
				$asset->asset_id = $newid;
				$asset->asset_name = Session::get('asset_name');
				$asset->asset_type = Session::get('asset_type');
				$asset->unit = Session::get('unit');
				$asset->yearly_depreciation = Session::get('yearly_depreciation');
				$asset->purchase_value = Session::get('purchase_value');
				$asset->purchase_date = Session::get('purchase_date');
				$asset->beginning_value = Session::get('beginning_value');
				$asset->depreciated_value = Session::get('depreciated_value');
				$asset->current_value = Session::get('current_value');

				$i = 0;
				$total_rough_value = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$assetlineitem = new AssetLineItem();
					$assetlineitem->no = $i;
					$assetlineitem->asset_id = $newid;
					$assetlineitem->component_name = $itm['component_name'];
					$assetlineitem->component_type = $itm['component_type'];
					$assetlineitem->quantity = $itm['quantity'];
					$assetlineitem->rough_value = $itm['rough_value'];
					$assetlineitem->notes = $itm['notes'];
					$total_rough_value += (floatval($itm['rough_value']) * floatval($itm['quantity']));
					$assetlineitem->save();
				}
				$asset->total_component = $i;
				$asset->total_component_value = $total_rough_value;
				
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				$asset->save();
				return Redirect::action('AssetsPurchaseController@showItem', array($newid)); */
			}
		}
	}
	
	public function showItem($id)
	{
		$select_purchase = Purchase::where('InvoiceNo','=',$id)->firstOrFail();
		Session::flush();
		Session::put('InvoiceNo', $select_purchase->InvoiceNo);
		Session::put('InvoiceDate', $select_purchase->InvoiceDate);
		Session::put('SupplierCode', $select_purchase->SupplierCode);
		Session::put('SupplierName', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Name);
		Session::put('SupplierAddress', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Address);
		Session::put('PaymentDueDate', $select_purchase->PaymentDueDate);
		Session::put('PaymentTerm', $select_purchase->PaymentTerm);
		Session::put('Total', $select_purchase->Total);
		Session::put('VAT', $select_purchase->VAT);
		Session::put('AmountDue', $select_purchase->AmountDue);
		$lineitems = PurchaseLineItem::where('InvoiceNo','=',$id)->get()->toArray();
		Session::put('lineitem',$lineitems);
		Session::put('dirtybit','false');
		Session::put('table','asset_id');
		return View::make('assets_purchase', $this->page);
	}
	
	public function postItem($id)
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsPurchaseController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_purchase = Purchase::where('InvoiceNo','=',$id)->firstOrFail();
				Session::flush();
				Session::put('InvoiceNo', 'NEW');
				Session::put('InvoiceDate', $select_purchase->InvoiceDate);
				Session::put('SupplierCode', $select_purchase->SupplierCode);
				Session::put('SupplierName', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Name);
				Session::put('SupplierAddress', Supplier::where('Code','=',$select_purchase->SupplierCode)->first()->Address);
				Session::put('PaymentDueDate', $select_purchase->PaymentDueDate);
				Session::put('PaymentTerm', $select_purchase->PaymentTerm);
				Session::put('Total', $select_purchase->Total);
				Session::put('VAT', $select_purchase->VAT);
				Session::put('AmountDue', $select_purchase->AmountDue);
				$lineitems = PurchaseLineItem::where('InvoiceNo','=',$id)->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','false');
				Session::put('table','purchases');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('AssetID'),
					'Price' => Input::get('Price'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['Price'] = Input::get('Price');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'deleteLine')
			{
				$temp_lineitem = Session::get('lineitem');
				//unset($temp_lineitem[intval(Input::get('item'))]);
				array_splice($temp_lineitem, intval(Input::get('item')), 1);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'delete')
			{
				PurchaseLineItem::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				Purchase::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				return Redirect::action('AssetsPurchaseController@showNewItem'); 
			}
			else if(Input::get('action') == 'save')
			{
				/* $asset = Purchase::where('InvoiceID','=',Session::get('InvoiceID'))->first();
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
					$total_rough_value += (floatval($itm['rough_value']) * floatval($itm['quantity']));
					$assetlineitem->save();
				}
				$asset->total_component = $i;
				$asset->total_component_value = $total_rough_value;
				
				Session::put('dirtybit','false');
				Session::put('table','asset_id');
				$asset->save();
				return View::make('assets_purchase', $this->page); */
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('InvoiceNo', Input::get('InvoiceNo'));
		Session::put('InvoiceDate', Input::get('InvoiceDate'));
		Session::put('SupplierCode', Input::get('SupplierCode'));
		Session::put('PaymentDueDate', Input::get('PaymentDueDate'));
		Session::put('PaymentTerm', Input::get('PaymentTerm'));
		Session::put('Total', Input::get('Total'));
		Session::put('VAT', Input::get('VAT'));
		Session::put('AmountDue', Input::get('AmountDue'));
		Session::put('dirtybit',Input::get('dirtybit'));
		Session::put('table',Input::get('table'));
		echo 'Success';
	}

}