function checkRequiredField()
{
	
	if($('input[name="InvoiceNo"]').val() == '')
	{
		alert('Error: Please enter invoice no.');
		$('input[name="InvoiceNo"]').focus();
		return false;
	}
	
	if($('input[name="InvoiceDate"]').val() == '')
	{
		alert('Error: Please enter invoice date.');
		$('input[name="InvoiceDate"]').focus();
		return false;
	}
	
	if($('input[name="CustomerCode"]').val() == '')
	{
		alert('Error: Please enter customer code.');
		$('input[name="CustomerCode"]').focus();
		return false;
	}
	
	if($('input[name="PaymentDueDate"]').val() == '')
	{
		alert('Error: Please enter payment due date.');
		$('input[name="PaymentDueDate"]').focus();
		return false;
	}
		
	return true;
}

function sendFormDataAjax()
{
	var form_data = $('#mainform').serialize();
	$.ajax(
		{
			url: '/ajax/update/assets_sale',
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
			data: {item: num,table_name: 'purchases'},
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
		var ans = confirm('Are you sure to delete this invoice?'); 
		return ans;
	} 
	else 
	{
		alert('Error: Please select invoice to delete!');
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
				url: '/ajax/update/assets_purchase',
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
	if(table_name == 'purchases')
	{
		$('#columnName').empty();
		selectValues = {"InvoiceNo": "Invoice No", "InvoiceDate": "Invoice Date"};
		$.each(selectValues, function(key, value) {   
	     $('#columnName')
	          .append($('<option>', { value : key })
	          .text(value)); 
		});
	}
	else if(table_name == 'supplier')
	{
		$('#columnName').empty();
		selectValues = {"Code": "Supplier Code", "Name": "Supplier Name"};
		$.each(selectValues, function(key, value) {   
	     $('#columnName')
	          .append($('<option>', { value : key })
	          .text(value)); 
		});
	}
}

function chooseSupplier(code,name,address)
{
	$('input[name="dirtybit"]').val('true');
	$('input[name="SupplierCode"]').val(code);
	$('input[name="SupplierName"]').val(name);
	$('input[name="SupplierAddress"]').val(address);
}

function selectAsset(asset_id,asset_name,unit)
{
	$('input[name="newLine_AssetID"]').val(asset_id);
	$('input[name="newLine_AssetName"]').val(asset_name);
	$('input[name="newLine_Unit"]').val(unit);
	$('#ListOfValueModal').modal('hide');
	$('#newLineItemModal').modal('show');
}

function selectEditAsset(asset_id,asset_name,unit)
{
	$('input[name="AssetID"]').val(asset_id);
	$('input[name="AssetName"]').val(asset_name);
	$('input[name="Unit"]').val(unit);
	$('#ListOfValueModal').modal('hide');
	$('#editLineItemModal').modal('show');
}