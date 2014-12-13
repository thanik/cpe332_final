function checkRequiredField()
{
	
	if($('input[name="movementDate"]').val() == '')
	{
		alert('Error: Please enter movement date.');
		$('input[name="movementDate"]').focus();
		return false;
	}
		
	return true;
}

function sendFormDataAjax()
{
	var form_data = $('#mainform').serialize();
	$.ajax(
		{
			url: '/ajax/update/assets_movement',
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
			data: {item: num,table_name: 'movement'},
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
	if($('input[name="InvoiceNo"').val() != 'NEW') 
	{ 
		var ans = confirm('Are you sure to delete this movement?'); 
		return ans;
	} 
	else 
	{
		alert('Error: Please select movement to delete!');
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
				url: '/ajax/update/assets_movement',
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

function getListOfValueSearch()
{
	if($('#listCondition').val() == "startwith")
	{
		var search_query = '(' + $('#columnName').val() + ' LIKE "' + $('#searchQuery1').val() + '%")';
	}
	else if($('#listCondition').val() == "has")
	{
		var search_query = '(' + $('#columnName').val() + ' LIKE "%' + $('#searchQuery1').val() + '%")';
	}
	else if($('#listCondition').val() == "between")
	{
		var search_query = '(' + $('#columnName').val() + ' BETWEEN "' + $('#searchQuery1').val() + '" AND "' + $('#searchQuery2').val() + '")';
	}
	else
	{
		var search_query = '(' + $('#columnName').val() + ' ' + $('#listCondition').val() + ' "' + $('#searchQuery1').val() + '")';
	}
	
	$.ajax(
		{
			url: '/ajax/listofvalue',
			data: {
				table_name: $('input[name="listofvalue_table"]').val(),
				mode: $('input[name="listofvalue_mode"]').val(),
				search: search_query,
			},
			type: 'GET',
			dataType: 'html',
			success: function (data) {
				$('#listofvalue').html(data);
				$('#listofvalue').slideDown('slow');
			},
			error: function (xhr, status) {
				alert("Sorry, there is an error while getting list of value.");
			},
		}
	);
}

function getListOfValueAll()
{
	$.ajax(
		{
			url: '/ajax/listofvalue',
			data: {
				table_name: $('input[name="listofvalue_table"]').val(),
				mode: $('input[name="listofvalue_mode"]').val(),
			},
			type: 'GET',
			dataType: 'html',
			success: function (data) {
				$('#listofvalue').html(data);
				$('#listofvalue').slideDown('slow');
			},
			error: function (xhr, status) {
				alert("Sorry, there is an error while getting list of value.");
			},
		}
	);
}

function addSearchColumn(table_name)
{
	if(table_name == 'movement')
	{
		$('#columnName').empty();
		selectValues = {"assetmoveNo": "Asset Movement No.", "movementDate": "Movement Date"};
		$.each(selectValues, function(key, value) {   
	     $('#columnName')
	          .append($('<option>', { value : key })
	          .text(value)); 
		});
	}
	else if(table_name == 'location')
	{
		$('#columnName').empty();
		selectValues = {"location": "Location Code", "description": "Description"};
		$.each(selectValues, function(key, value) {   
	     $('#columnName')
	          .append($('<option>', { value : key })
	          .text(value)); 
		});
	}
}

function selectLocation(location)
{
	$('input[name="dirtybit"]').val('true');
	$('input[name="newLine_newLocation"]').val(location);
	$('#ListOfValueModal').modal('hide');
	$('#newLineItemModal').modal('show');
}

function selectEditLocation(location)
{
	$('input[name="dirtybit"]').val('true');
	$('input[name="newLocation"]').val(location);
	$('#ListOfValueModal').modal('hide');
	$('#editLineItemModal').modal('show');
}

function selectAsset(asset_id,asset_name,unit,price,currentLocation)
{
	$('input[name="newLine_asset_id"]').val(asset_id);
	$('input[name="newLine_asset_name"]').val(asset_name);
	$('input[name="newLine_currentLocation"]').val(currentLocation);
	$('#ListOfValueModal').modal('hide');
	$('#newLineItemModal').modal('show');
}

function selectEditAsset(asset_id,asset_name,unit,price,currentLocation)
{
	$('input[name="asset_id"]').val(asset_id);
	$('input[name="asset_name"]').val(asset_name);
	$('input[name="currentLocation"]').val(currentLocation);
	$('#ListOfValueModal').modal('hide');
	$('#editLineItemModal').modal('show');
}