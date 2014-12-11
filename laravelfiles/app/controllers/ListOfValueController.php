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
					$itms_obj = $itms->where();
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
						echo '<td><button onclick="post(\'assets\',{action: \'copy\', id: \''.$itm['asset_id'].'\'});" class="btn btn-primary btn-xs" style="width: 100%">select</button></td>';
					}
					echo '<td>';
					echo $itm['asset_id'];
					echo '</td><td>';
					echo $itm['asset_name'];
					echo '</td></tr>';
				}
				echo '</tbody>';
			}
			else if(Input::get('table_name') == '')
			{
				echo '<thead><tr><th>Select</th>';
				echo '<th>Asset ID</th>';
				echo '<th>Asset Name</th></tr></thead>';
				
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
		}
	}
}