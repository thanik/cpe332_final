<?php

class AssetsSaleController extends BaseController {
	public $page = array(
		'table_name' => 'sales',
		'form_name' => 'Asset Sale Form',
	);
	
	public function showNewItem()
	{
		Session::flush();
		Session::put('InvoiceNo','NEW');
		Session::put('Total', '0.00');
		Session::put('VAT', '0.00');
		Session::put('AmountDue', '0.00');
		Session::put('dirtybit','false');
		Session::put('table','sales');
		Session::put('mode','new');
		Session::put('lineitem', array());
		return View::make('assets_sale', $this->page);
	}
	
	public function postNewItem()
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsSaleController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_sale = Sale::where('InvoiceNo','=',Input::get('id'))->firstOrFail();
				Session::flush();
				Session::put('InvoiceNo', 'NEW');
				Session::put('InvoiceDate', $select_sale->InvoiceDate);
				Session::put('CustomerCode', $select_sale->CustomerCode);
				Session::put('CustomerName', Customer::where('Code','=',$select_sale->CustomerCode)->first()->Name);
				Session::put('CustomerAddress', Customer::where('Code','=',$select_sale->CustomerCode)->first()->Address);
				Session::put('PaymentDueDate', $select_sale->PaymentDueDate);
				Session::put('Total', $select_sale->Total);
				Session::put('VAT', $select_sale->VAT);
				Session::put('AmountDue', $select_sale->AmountDue);
				$lineitems = SaleLineItem::where('InvoiceNo','=',Input::get('id'))->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','true');
				Session::put('table','sales');
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('newLine_AssetID'),
					'AssetName' => Input::get('newLine_AssetName'),
					'Units' => Input::get('newLine_Unit'),
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
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['AssetName'] = Input::get('AssetName');
				$temp_lineitem[intval(Input::get('item'))]['Units'] = Input::get('Units');
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
				return View::make('assets_sale', $this->page);
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
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'save')
			{
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets', $this->page);
				}
				/* get new asset_id */
				$newid = sprintf("IN%04d",intval(substr(Sale::max('InvoiceNo'), 2)) + 1);
				
				$sale = new Sale;
				$sale->InvoiceNo = $newid;
				$sale->InvoiceDate = Session::get('InvoiceDate');
				$sale->CustomerCode = Session::get('CustomerCode');
				$sale->PaymentDueDate = Session::get('PaymentDueDate');
				$sale->Total = Session::get('Total');
				$sale->VAT = Session::get('VAT');
				$sale->AmountDue = Session::get('AmountDue');

				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$salelineitem = new SaleLineItem();
					$salelineitem->InvoiceNo = $newid;
					$salelineitem->ItemNo = $i;
					$salelineitem->AssetID = $itm['AssetID'];
					$salelineitem->AssetName = $itm['AssetName'];
					$salelineitem->Units = $itm['Units'];
					$salelineitem->Price = $itm['Price'];
					$salelineitem->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','sales');
				$sale->save();
				return Redirect::action('AssetsSaleController@showItem', array($newid));
			}
		}
	}
	
	public function showItem($id)
	{
		$select_sale = Sale::where('InvoiceNo','=',$id)->firstOrFail();
		Session::flush();
		Session::put('InvoiceNo', $select_sale->InvoiceNo);
		Session::put('InvoiceDate', $select_sale->InvoiceDate);
		Session::put('CustomerCode', $select_sale->CustomerCode);
		Session::put('CustomerName', Customer::where('Code','=',$select_sale->CustomerCode)->first()->Name);
		Session::put('CustomerAddress', Customer::where('Code','=',$select_sale->CustomerCode)->first()->Address);
		Session::put('PaymentDueDate', $select_sale->PaymentDueDate);
		Session::put('Total', $select_sale->Total);
		Session::put('VAT', $select_sale->VAT);
		Session::put('AmountDue', $select_sale->AmountDue);
		$lineitems = SaleLineItem::where('InvoiceNo','=',$id)->get()->toArray();
		Session::put('lineitem',$lineitems);
		Session::put('dirtybit','false');
		Session::put('table','asset_id');
		return View::make('assets_sale', $this->page);
	}
	
	public function postItem($id)
	{
		if(Input::has('action'))
		{
			if(Input::get('action') == 'new')
			{
				return Redirect::action('AssetsSaleController@showNewItem');
			}
			else if(Input::get('action') == 'copy')
			{
				$select_sale = Sale::where('InvoiceNo','=',$id)->firstOrFail();
				Session::flush();
				Session::put('InvoiceNo', 'NEW');
				Session::put('InvoiceDate', $select_sale->InvoiceDate);
				Session::put('CustomerCode', $select_sale->CustomerCode);
				Session::put('CustomerName', Sale::where('Code','=',$select_sale->CustomerCode)->first()->Name);
				Session::put('CustomerAddress', Sale::where('Code','=',$select_sale->CustomerCode)->first()->Address);
				Session::put('PaymentDueDate', $select_sale->PaymentDueDate);
				Session::put('Total', $select_sale->Total);
				Session::put('VAT', $select_sale->VAT);
				Session::put('AmountDue', $select_sale->AmountDue);
				$lineitems = SaleLineItem::where('InvoiceNo','=',$id)->get()->toArray();
				Session::put('lineitem',$lineitems);
				Session::put('dirtybit','true');
				Session::put('table','sales');
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'insertLine')
			{
				$temp_lineitem = Session::get('lineitem');
				/* add to lineitem session */
				$data = array(
					'AssetID' => Input::get('newLine_AssetID'),
					'AssetName' => Input::get('newLine_AssetName'),
					'Units' => Input::get('newLine_Unit'),
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
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'editLine')
			{
				$temp_lineitem = Session::get('lineitem');
				$temp_lineitem[intval(Input::get('item'))]['AssetID'] = Input::get('AssetID');
				$temp_lineitem[intval(Input::get('item'))]['AssetName'] = Input::get('AssetName');
				$temp_lineitem[intval(Input::get('item'))]['Units'] = Input::get('Units');
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
				return View::make('assets_sale', $this->page);
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
				return View::make('assets_sale', $this->page);
			}
			else if(Input::get('action') == 'delete')
			{
				SaleLineItem::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				Sale::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				return Redirect::action('AssetsSaleController@showNewItem'); 
			}
			else if(Input::get('action') == 'save')
			{
				if(count(Session::get('lineitem')) == 0)
				{
					echo '<script type="text/javascript">alert("Error: There isn\'t any items in lineitem.");</script>';
					return View::make('assets', $this->page);
				}
				$sale = Sale::where('InvoiceNo','=',Session::get('InvoiceNo'))->first();
				$sale->InvoiceDate = Session::get('InvoiceDate');
				$sale->CustomerCode = Session::get('CustomerCode');
				$sale->PaymentDueDate = Session::get('PaymentDueDate');
				$sale->Total = Session::get('Total');
				$sale->VAT = Session::get('VAT');
				$sale->AmountDue = Session::get('AmountDue');
				
				SaleLineItem::where('InvoiceNo','=',Session::get('InvoiceNo'))->delete();
				$i = 0;
				foreach(Session::get('lineitem') as $itm)
				{
					$i++;
					$salelineitem = new SaleLineItem();
					$salelineitem->InvoiceNo = Session::get('InvoiceNo');
					$salelineitem->ItemNo = $i;
					$salelineitem->AssetID = $itm['AssetID'];
					$salelineitem->AssetName = $itm['AssetName'];
					$salelineitem->Units = $itm['Units'];
					$salelineitem->Price = $itm['Price'];
					$salelineitem->save();
				}
				
				Session::put('dirtybit','false');
				Session::put('table','sales');
				$sale->save();
				return View::make('assets_sale', $this->page);
			}
		}
	}
	
	public function ajaxUpdateSession()
	{
		Session::put('InvoiceNo', Input::get('InvoiceNo'));
		Session::put('InvoiceDate', Input::get('InvoiceDate'));
		Session::put('CustomerCode', Input::get('CustomerCode'));
		Session::put('CustomerName', Input::get('CustomerName'));
		Session::put('CustomerAddress', Input::get('CustomerAddress'));
		Session::put('PaymentDueDate', Input::get('PaymentDueDate'));
		Session::put('Total', Input::get('Total'));
		Session::put('VAT', Input::get('VAT'));
		Session::put('AmountDue', Input::get('AmountDue'));
		Session::put('dirtybit',Input::get('dirtybit'));
		Session::put('table',Input::get('table'));
		echo 'Success';
	}

}