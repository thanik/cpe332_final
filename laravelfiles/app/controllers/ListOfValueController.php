<?php

class ListOfValueController extends BaseController {
	public function show()
	{
		if(Input::has('table_name'))
		{
			/* asset_id */
			if(Input::get('table_name') == 'asset_id')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Asset ID</th>';
				echo '<th>Asset Name</th></tr></thead>';
				$itms_obj = Asset::all();
				if(Input::has('search'))
				{
					$itms_obj = Asset::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					if(Input::get('mode') == 'edit')
					{
						echo '<td><a href="'.URL::action('AssetsController@showItem',$itm['asset_id']).'" class="btn btn-primary btn-xs" style="width: 100%">select</a></td>';
					}
					else if(Input::get('mode') == 'copy')
					{
						echo '<td><button onclick="post(\'/assets\',{action: \'copy\', id: \''.$itm['asset_id'].'\'});" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					else
					{
						echo '<td><button onclick="'.Input::get('mode').'(\''.$itm['asset_id'].'\',\''.$itm['asset_name'].'\',\''.$itm['unit'].'\',\''.$itm['purchase_value'].'\',\''.$itm['current_location'].'\');" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					echo '<td>';
					echo $itm['asset_id'];
					echo '</td><td>';
					echo $itm['asset_name'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'purchases')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Invoice No</th>';
				echo '<th>Invoice Date</th>';
				echo '<th>Supplier Name</th></tr></thead>';
				$itms_obj = Purchase::all();
				if(Input::has('search'))
				{
					$itms_obj = Purchase::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					if(Input::get('mode') == 'edit')
					{
						echo '<td><a href="'.URL::action('AssetsPurchaseController@showItem',$itm['InvoiceNo']).'" class="btn btn-primary btn-xs" style="width: 100%">select</a></td>';
					}
					else if(Input::get('mode') == 'copy')
					{
						echo '<td><button onclick="post(\'/assets_purchase\',{action: \'copy\', id: \''.$itm['InvoiceNo'].'\'});" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					else
					{
						echo '<td><button onclick="'.Input::get('mode').'();" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					echo '<td>';
					echo $itm['InvoiceNo'];
					echo '</td><td>';
					echo $itm['InvoiceDate'];
					echo '</td><td>';
					echo Supplier::where('Code','=',$itm['SupplierCode'])->firstOrFail()->Name;
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'supplier')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Supplier Code</th>';
				echo '<th>Supplier Name</th></tr></thead>';
				$itms_obj = Supplier::all();
				if(Input::has('search'))
				{
					$itms_obj = Supplier::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					echo '<td><button onclick="'.Input::get('mode').'(\''.$itm['Code'].'\',\''.$itm['Name'].'\',\''.$itm['Address'].'\'); $(\'#ListOfValueModal\').modal(\'hide\');" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					echo '<td>';
					echo $itm['Code'];
					echo '</td><td>';
					echo $itm['Name'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'sales')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Invoice No</th>';
				echo '<th>Invoice Date</th>';
				echo '<th>Customer Name</th></tr></thead>';
				$itms_obj = Sale::all();
				if(Input::has('search'))
				{
					$itms_obj = Sale::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					if(Input::get('mode') == 'edit')
					{
						echo '<td><a href="'.URL::action('AssetsSaleController@showItem',$itm['InvoiceNo']).'" class="btn btn-primary btn-xs" style="width: 100%">select</a></td>';
					}
					else if(Input::get('mode') == 'copy')
					{
						echo '<td><button onclick="post(\'/assets_sale\',{action: \'copy\', id: \''.$itm['InvoiceNo'].'\'});" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					else
					{
						echo '<td><button onclick="'.Input::get('mode').'();" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					echo '<td>';
					echo $itm['InvoiceNo'];
					echo '</td><td>';
					echo $itm['InvoiceDate'];
					echo '</td><td>';
					echo Customer::where('Code','=',$itm['CustomerCode'])->firstOrFail()->Name;
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'customer')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Customer Code</th>';
				echo '<th>Customer Name</th></tr></thead>';
				$itms_obj = Customer::all();
				if(Input::has('search'))
				{
					$itms_obj = Customer::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					echo '<td><button onclick="'.Input::get('mode').'(\''.$itm['Code'].'\',\''.$itm['Name'].'\',\''.$itm['Address'].'\'); $(\'#ListOfValueModal\').modal(\'hide\');" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					echo '<td>';
					echo $itm['Code'];
					echo '</td><td>';
					echo $itm['Name'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'movement')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Asset Movement No</th>';
				echo '<th>Movement Date</th>';
				echo '<th>Movement Reason</th></tr></thead>';
				$itms_obj = Movement::all();
				if(Input::has('search'))
				{
					$itms_obj = Movement::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					if(Input::get('mode') == 'edit')
					{
						echo '<td><a href="'.URL::action('AssetsMovementController@showItem',$itm['assetmoveNo']).'" class="btn btn-primary btn-xs" style="width: 100%">select</a></td>';
					}
					else if(Input::get('mode') == 'copy')
					{
						echo '<td><button onclick="post(\'/assets_movement\',{action: \'copy\', id: \''.$itm['assetmoveNo'].'\'});" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					else
					{
						echo '<td><button onclick="'.Input::get('mode').'();" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					echo '<td>';
					echo $itm['assetmoveNo'];
					echo '</td><td>';
					echo $itm['movementDate'];
					echo '</td><td>';
					echo $itm['assetmoveReason'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == 'location')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Location</th>';
				echo '</tr></thead>';
				$itms_obj = Location::all();
				if(Input::has('search'))
				{
					$itms_obj = Location::whereRaw(Input::get('search'))->get();
				}
				$itms = $itms_obj->toArray();
				echo '<tbody>';
				foreach($itms as $itm)
				{
					echo '<tr>';
					echo '<td><button onclick="'.Input::get('mode').'(\''.$itm['location'].'\'); $(\'#ListOfValueModal\').modal(\'hide\');" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					echo '<td>';
					echo $itm['location'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
		}
	}
	
	public function edit()
	{
		if(Input::has('table_name'))
		{
			if(Input::get('table_name') == 'assets')
			{
			echo '<table width="100%">
				<tr>
					<td><b>* Component Name :</b></td>
					<td><input type="text" name="component_name" required value="'.Session::get('lineitem')[intval(Input::get('item'))]['component_name'].'"></td>
				</tr>
				<tr>
					<td><b>* Component Type :</b></td>
					<td><select name="component_type">';
					foreach(AssetType::all()->ToArray() as $option)
					{
						if(Session::get('lineitem')[intval(Input::get('item'))]['component_type'] == $option['asset_type'])
						{
							echo '<option value="'.$option['asset_type'].'" selected>'.$option['asset_type'].'</option>';
						}
						else
						{
							echo '<option value="'.$option['asset_type'].'">'.$option['asset_type'].'</option>';
						}
					}
			echo '	</select></td>
				</tr>
				<tr>
					<td><b>* Quantity :</b></td>
					<td><input type="number" name="quantity" step="1" min="1" required value="'.Session::get('lineitem')[intval(Input::get('item'))]['quantity'].'"></td>
				</tr>
				<tr>
					<td><b>Rough Value of this part :</b></td>
					<td><input type="number" name="rough_value" step="0.25" placeholder="0.00" value="'.Session::get('lineitem')[floatval(Input::get('item'))]['rough_value'].'"></td>
				</tr>
				<tr>
					<td><b>Notes :</b></td>
					<td><input type="text" name="notes" step="1" value="'.Session::get('lineitem')[intval(Input::get('item'))]['notes'].'"></td>
				</tr>
			</table>';
			}
			else if(Input::get('table_name') == 'purchases')
			{
				echo '<table width="100%">
						<tr>
							<td><b>* Asset ID :</b></td>
							<td><input type="text" name="AssetID" value="'.Session::get('lineitem')[intval(Input::get('item'))]['AssetID'].'" required><button type="button" onclick="openListOfValue(\'asset_id\',\'selectEditAsset\'); $(\'#editLineItemModal\').modal(\'hide\');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="AssetName" value="'.Session::get('lineitem')[intval(Input::get('item'))]['AssetName'].'" readonly></td>
						</tr>
						<tr>
							<td><b>Unit :</b></td>
							<td><input type="text" name="Units" value="'.Session::get('lineitem')[intval(Input::get('item'))]['Units'].'" readonly></td>
						</tr>
						<tr>
							<td><b>Price :</b></td>
							<td><input type="number" name="Price" step="0.25" placeholder="0.00" min="0.25" value="'.Session::get('lineitem')[intval(Input::get('item'))]['Price'].'" required></td>
						</tr>
					</table>';
			}
			else if(Input::get('table_name') == 'sales')
			{
				echo '<table width="100%">
						<tr>
							<td><b>* Asset ID :</b></td>
							<td><input type="text" name="AssetID" value="'.Session::get('lineitem')[intval(Input::get('item'))]['AssetID'].'" required><button type="button" onclick="openListOfValue(\'asset_id\',\'selectEditAsset\'); $(\'#editLineItemModal\').modal(\'hide\');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="AssetName" value="'.Session::get('lineitem')[intval(Input::get('item'))]['AssetName'].'" readonly></td>
						</tr>
						<tr>
							<td><b>Unit :</b></td>
							<td><input type="text" name="Units" value="'.Session::get('lineitem')[intval(Input::get('item'))]['Units'].'" readonly></td>
						</tr>
						<tr>
							<td><b>Price :</b></td>
							<td><input type="number" name="Price" step="0.25" placeholder="0.00" min="0.25" value="'.Session::get('lineitem')[intval(Input::get('item'))]['Price'].'" required></td>
						</tr>
					</table>';
			}
			else if(Input::get('table_name') == 'movement')
			{
				echo '<table width="100%">
						<tr>
							<td><b>* Asset ID :</b></td>
							<td><input type="text" name="asset_id" value="'.Session::get('lineitem')[intval(Input::get('item'))]['asset_id'].'" required><button type="button" onclick="openListOfValue(\'asset_id\',\'selectEditAsset\'); $(\'#editLineItemModal\').modal(\'hide\');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="asset_name" value="'.Session::get('lineitem')[intval(Input::get('item'))]['asset_name'].'" readonly></td>
						</tr>
						<tr>
							<td><b>Current Location :</b></td>
							<td><input type="text" name="currentLocation" value="'.Session::get('lineitem')[intval(Input::get('item'))]['currentLocation'].'" readonly></td>
						</tr>
						<tr>
							<td><b>New Location :</b></td>
							<td><input type="text" name="newLocation" value="'.Session::get('lineitem')[intval(Input::get('item'))]['newLocation'].'" required><button type="button" onclick="openListOfValue(\'location\',\'selectEditLocation\'); $(\'#editLineItemModal\').modal(\'hide\');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
					</table>';
			}
		}
	}
}