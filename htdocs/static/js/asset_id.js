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
	
	if($('input[name="yearly_depreciation"]').val() == '' || parseFloat($('input[name="yearly_depreciation"]').val()) < 0 || parseFloat($('input[name="yearly_depreciation"]').val()) > 100)
	{
		alert('Error: Please enter valid yearly depreciation.');
		$('input[name="yearly_depreciation"]').focus();
		return false;
	}
	
	if($('input[name="purchase_value"]').val() == ''  || parseFloat($('input[name="purchase_value"]').val()) < 0)
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
	
	if($('input[name="beginning_value"]').val() == '' || parseFloat($('input[name="beginning_value"]').val()) < 0)
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

function getListOfValueAll(table_name)
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

function addSearchColumn()
{
	$('#columnName').empty();
	selectValues = {"asset_id": "Asset ID", "asset_name": "Asset Name"};
	$.each(selectValues, function(key, value) {   
     $('#columnName')
          .append($('<option>', { value : key })
          .text(value)); 
	});
}

function calculateDepreciated()
{
	$('input[name="depreciated_value"]').val(parseFloat($('input[name="yearly_depreciation"]').val()) * parseFloat($('input[name="beginning_value"]').val()) / 100);
	$('input[name="current_value"]').val(parseFloat($('input[name="beginning_value"]').val()) - parseFloat($('input[name="depreciated_value"]').val()));
}