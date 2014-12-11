function checkRequiredField()
{
	if($('input[name="purchase_value"]').val() == '')
	{
		alert('Error: Please enter purchase value.');
		return false;
	}
	
	if($('input[name="asset_name"]').val() == '')
	{
		alert('Error: Please enter asset name.');
		return false;
	}
	
	if($('input[name="purchase_date"]').val() == '')
	{
		alert('Error: Please enter purchase date.');
		return false;
	}
	
	if($('input[name="asset_type"]').val() == '')
	{
		alert('Error: Please enter asset type.');
		return false;
	}
	
	if($('input[name="beginning_value"]').val() == '')
	{
		alert('Error: Please enter beginning value.');
		return false;
	}
	
	if($('input[name="unit"]').val() == '')
	{
		alert('Error: Please enter unit.');
		return false;
	}
	
	if($('input[name="depreciated_value"]').val() == '')
	{
		alert('Error: Please enter depreciated value.');
		return false;
	}
	
	if($('input[name="yearly_depreciation"]').val() == '')
	{
		alert('Error: Please enter yearly depreciation.');
		return false;
	}
	
	if($('input[name="current_value"]').val() == '')
	{
		alert('Error: Please enter current value.');
		return false;
	}
	
	return true;
}


