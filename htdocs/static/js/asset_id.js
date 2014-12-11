function checkRequiredField()
{
	
	if($('input[name="asset_name"]').val() == '')
	{
		alert('Error: Please enter asset name.');
		$('input[name="asset_name"]').focus();
		return false;
	}
	
	if($('input[name="unit"]').val() == '')
	{
		alert('Error: Please enter unit.');
		$('input[name="unit"]').focus();
		return false;
	}
	
	if($('input[name="yearly_depreciation"]').val() == '')
	{
		alert('Error: Please enter yearly depreciation.');
		$('input[name="yearly_depreciation"]').focus();
		return false;
	}
	
	if($('input[name="purchase_value"]').val() == '')
	{
		alert('Error: Please enter purchase value.');
		$('input[name="purchase_value"]').focus();
		return false;
	}
	
	if($('input[name="purchase_date"]').val() == '')
	{
		alert('Error: Please enter purchase date.');
		$('input[name="purchase_date"]').focus();
		return false;
	}
	
	if($('input[name="beginning_value"]').val() == '')
	{
		alert('Error: Please enter beginning value.');
		$('input[name="beginning_value"]').focus();
		return false;
	}
	
	return true;
}

function sendFormDataAjax()
{
	var form_data = $('#mainform').serialize();
	$.ajax(
		{
			url: '/ajax/update/assets',
			data: form_data,
			type: 'POST',
			dataType: 'html',
			success: function (data) {
				console.log(data);
			},
			error: function (xhr, status) {
				alert("Sorry, there is an error while updating data.");
			},
		}
	);
}

function deleteLineItem(num)
{
	var ans = confirm('Are you sure to delete this item?');
	if(ans)
	{
		post('', {action: 'deleteLine',item: num});
	}
}

function editLineItem(num)
{
	$('input[name="item"]').val(num);
	$('#edit').hide();
	$.ajax(
		{
			url: '/ajax/editlistofvalue',
			type: 'GET',
			data: {item: num,table_name: 'assets'},
			dataType: 'html',
			success: function (data) {
				$('#edit').html(data);
				$('#edit').slideDown('slow');
			},
			error: function (xhr,status) {
				alert("Sorry, there is an error while loading data.");
			},
		}
	);
	$('#editLineItemModal').modal()
}

function delete_asset()
{
	if($('input[name="asset_id"').val() != 'NEW') 
	{ 
		var ans = confirm('Are you sure to delete this asset?'); 
		return ans;
	} 
	else 
	{
		alert('Error: Please select asset to delete!');
		return false;
	}
}

function save_asset()
{
	if($('input[name="dirtybit"]').val() == 'true')
	{
		var form_data = $('#mainform').serialize();
		$.ajax(
			{
				url: '/ajax/update/assets',
				data: form_data,
				type: 'POST',
				dataType: 'html',
				success: function (data) {
					console.log(data);
				},
				error: function (xhr, status) {
					alert("Sorry, there is an error while updating data.");
				},
				complete: function (xhr, status) {
					post('',{action: 'save'});
					return true;
				},
			}
		);
	}
	else
	{
		return false;
	}
}
