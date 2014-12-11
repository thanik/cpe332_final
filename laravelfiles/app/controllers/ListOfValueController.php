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
}