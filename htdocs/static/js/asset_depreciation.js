function checkRequiredField()
{
	
	if($('input[name="depreciation_date"]').val() == '')
	{
		alert('Error: Please enter Depreciation Date.');
		$('input[name="asset_name"]').focus();
		return false;
	}
	
	if($('input[name="for_month"]').val() == '' || parseInt($('input[name="for_month"]').val()) <= 0 || parseInt($('input[name="for_month"]').val()) > 12)
	{
		alert('Error: Please enter valid Depreciation For Month.');
		$('input[name="yearly_depreciation"]').focus();
		return false;
	}
	
	if($('input[name="for_year"]').val() == '' || parseInt($('input[name="for_year"]').val()) <= 1900)
	{
		alert('Error: Please enter valid Depreciation For Year.');
		$('input[name="yearly_depreciation"]').focus();
		return false;
	}
	
	return true;
}

function sendFormDataAjax()
{
	var form_data = $('#mainform').serialize();
	$.ajax(
		{
			url: '/ajax/update/assets_depreciation',
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

function getallasset()
{
	var form_data = $('#mainform').serialize();
	$.ajax(
		{
			url: '/ajax/update/assets_depreciation',
			data: form_data,
			type: 'POST',
			dataType: 'html',
			success: function (data) {
				console.log(data);
			},
			error: function (xhr, status) {
				alert("Sorry, there is an error while updating data.");
			},
			complete: function(xhr, status) {
				post('',{action: 'getallasset'});
			}
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
			data: {item: num,table_name: 'depreciation'},
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
	if($('input[name="depreciation_no"').val() != 'NEW') 
	{ 
		var ans = confirm('Are you sure to delete this Depreciation No?'); 
		return ans;
	} 
	else 
	{
		alert('Error: Please select Depreciation No to delete!');
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
				url: '/ajax/update/assets_depreciation',
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
	if(table_name == 'depreciation')
	{
		$('#columnName').empty();
			selectValues = {"depreciation_no": "Depreciation No", "depreciation_date": "Depreciation Date","for_month": "For Month","for_year":"For Year"};
		$.each(selectValues, function(key, value) {   
			$('#columnName')
          .append($('<option>', { value : key })
          .text(value)); 
		});
	}
	else if(table_name == 'asset')
	{
		$('#columnName').empty();
			selectValues = {"asset_id": "Asset ID", "asset_name": "Asset Name"};
		$.each(selectValues, function(key, value) {   
			$('#columnName')
          .append($('<option>', { value : key })
          .text(value)); 
		});
	}
}

function selectAsset(asset_id,asset_name,asset_type,depreciation,purchase_value,beginning_value,depreciation_value,current_value)
{
	$('input[name="newLine_AssetID"]').val(asset_id);
	$('input[name="newLine_AssetName"]').val(asset_name);
	$('input[name="newLine_AssetType"]').val(asset_type);
	$('input[name="newLine_DepreciationPercent"]').val(depreciation);
	$('input[name="newLine_PurchaseValue"]').val(purchase_value);
	$('input[name="newLine_BeginningValue"]').val(beginning_value);
	$('input[name="newLine_DepreciationValue"]').val(depreciation_value);
	$('input[name="newLine_CurrentValue"]').val(current_value);
	var depreciationpermonth = ((parseFloat($('input[name="newLine_DepreciationPercent"]').val()) * parseFloat($('input[name="newLine_PurchaseValue"]').val())) / 100) / 12;
	$('input[name="newLine_DepreciationValueMonth"').val(depreciationpermonth.toFixed(2));
	$('input[name="newLine_NewDepreciationValueMonth"').val(parseFloat($('input[name="newLine_CurrentValue"]').val()) - depreciationpermonth.toFixed(2));
	$('#ListOfValueModal').modal('hide');
	$('#newLineItemModal').modal('show');
}


function selectEditAsset(asset_id,asset_name,asset_type,depreciation,purchase_value,beginning_value,depreciation_value,current_value)
{
	$('input[name="AssetID"]').val(asset_id);
	$('input[name="AssetName"]').val(asset_name);
	$('input[name="AssetType"]').val(asset_type);
	$('input[name="DepreciationPercent"]').val(depreciation);
	$('input[name="PurchaseValue"]').val(purchase_value);
	$('input[name="BeginningValue"]').val(beginning_value);
	$('input[name="DepreciationValue"]').val(depreciation_value);
	$('input[name="CurrentValue"]').val(current_value);
	var depreciationpermonth = ((parseFloat($('input[name="DepreciationPercent"]').val()) * parseFloat($('input[name="PurchaseValue"]').val())) / 100) / 12;
	$('input[name="DepreciationValueMonth"').val(depreciationpermonth.toFixed(2));
	$('input[name="NewDepreciationValueMonth"').val(parseFloat($('input[name="CurrentValue"]').val()) - depreciationpermonth.toFixed(2));
	$('#ListOfValueModal').modal('hide');
	$('#editLineItemModal').modal('show');
}

function calculateNewValue()
{
	$('input[name="newLine_NewDepreciationValueMonth"]').val((parseFloat($('input[name="newLine_CurrentValue"]').val()) - parseFloat($('input[name="newLine_DepreciationValueMonth"]').val())).toFixed(2));
}

function calculateNewValueEdit()
{
	$('input[name="NewDepreciationValueMonth"]').val((parseFloat($('input[name="CurrentValue"]').val()) - parseFloat($('input[name="DepreciationValueMonth"]').val())).toFixed(2));
}