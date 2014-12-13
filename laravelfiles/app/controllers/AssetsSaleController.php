<?php

class AssetsSaleController extends BaseController {
	public $page = array(
		'table_name' => 'sales',
		'form_name' => 'Asset Sales Form',
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
				Session::put('dirtybit','true');
				Session::put('table','purchases');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('newLine_AssetID'),
					'Price' => Input::get('newLine_Price'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['Price'] = Input::get('Price');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
				return View::make('assets_purchase', $this->page);
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
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				/* get new asset_id */
				$newid = sprintf("IN%04d",intval(substr(Purchase::max('InvoiceNo'), 2)) + 1);
				
				$purchase = new Purchase;
				$purchase->InvoiceNo = $newid;
				$purchase->InvoiceDate = Session::get('InvoiceDate');
				$purchase->SupplierCode = Session::get('SupplierCode');
				$purchase->PaymentDueDate = Session::get('PaymentDueDate');
				$purchase->PaymentTerm = Session::get('PaymentTerm');
				$purchase->Total = Session::get('Total');
				$purchase->VAT = Session::get('VAT');
				$purchase->AmountDue = Session::get('AmountDue');

				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$purchaselineitem = new PurchaseLineItem();
					$purchaselineitem->InvoiceNo = $newid;
					$purchaselineitem->ItemNo = $i;
					$purchaselineitem->AssetID = $itm['AssetID'];
					$purchaselineitem->Price = $itm['Price'];
					$purchaselineitem->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','purchases');
				$purchase->save();
				return Redirect::action('AssetsPurchaseController@showItem', array($newid));
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
				Session::put('dirtybit','true');
				Session::put('table','purchases');
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('newLine_AssetID'),
					'Price' => Input::get('newLine_Price'),
				);
				array_push($temp_lineitem, $data);
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
				return View::make('assets_purchase', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['Price'] = Input::get('Price');
				Session::put('lineitem', $temp_lineitem);
				Session::put('dirtybit','true');
				/* recalculate */
				$Total = 0;
				foreach($temp_lineitem as $itm)
				{
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
				return View::make('assets_purchase', $this->page);
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
					$Total += floatval($itm['Price']);
				}
				Session::put('Total', $Total);
				Session::put('VAT', $Total * 7 / 100);
				Session::put('AmountDue', $Total + ($Total * 7 / 100));
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
				$purchase = Purchase::where('InvoiceNo','=',Session::get('InvoiceNo'))->first();
				$purchase->InvoiceDate = Session::get('InvoiceDate');
				$purchase->SupplierCode = Session::get('SupplierCode');
				$purchase->PaymentDueDate = Session::get('PaymentDueDate');
				$purchase->PaymentTerm = Session::get('PaymentTerm');
				$purchase->Total = Session::get('Total');
				$purchase->VAT = Session::get('VAT');
				$purchase->AmountDue = Session::get('AmountDue');
				
				PurchaseLineItem::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$purchaselineitem = new PurchaseLineItem();
					$purchaselineitem->InvoiceNo = Session::get('InvoiceNo');
					$purchaselineitem->ItemNo = $i;
					$purchaselineitem->AssetID = $itm['AssetID'];
					$purchaselineitem->Price = $itm['Price'];
					$purchaselineitem->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','purchases');
				$purchase->save();
				return View::make('assets_purchase', $this->page);
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('InvoiceNo', Input::get('InvoiceNo'));
		Session::put('InvoiceDate', Input::get('InvoiceDate'));
		Session::put('SupplierCode', Input::get('SupplierCode'));
		Session::put('SupplierName', Input::get('SupplierName'));
		Session::put('SupplierAddress', Input::get('SupplierAddress'));
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