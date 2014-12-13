@include('header')
{{ HTML::script('static/js/asset_depreciation.js') }}

<div class="modal fade" id="newLineItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add new lineitem</h4>
    		</div>
			<form method="post">
				<div class="modal-body">
					<table width="100%">
						<tr>
							<td><b>* Asset ID :</b></td>
							<td><input type="text" name="newLine_AssetID" required><button type="button" onclick="addSearchColumn('asset'); openListOfValue('asset_id','selectAsset'); $('#newLineItemModal').modal('hide');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="newLine_AssetName" readonly></td>
						</tr>
						<tr>
							<td><b>Asset Type :</b></td>
							<td><input type="text" name="newLine_AssetType" readonly></td>
						</tr>
						<tr>
							<td><b>Depreciation Percent :</b></td>
							<td><input type="number" name="newLine_DepreciationPercent" step="0.25" min="0.25" placeholder="0.00" readonly> %</td>
						</tr>
						<tr>
							<td><b>Purchase Value :</b></td>
							<td><input type="number" name="newLine_PurchaseValue" step="0.25" min="0.25" placeholder="0.00" readonly></td>
						</tr>
						<tr>
							<td><b>Beginning Value :</b></td>
							<td><input type="number" name="newLine_BeginningValue" step="0.25" min="0.25" placeholder="0.00" readonly></td>
						</tr>
						<tr>
							<td><b>Depreciation Value :</b></td>
							<td><input type="number" name="newLine_DepreciationValue" step="0.25" min="0.25" placeholder="0.00" readonly></td>
						</tr>
						<tr>
							<td><b>Current Value :</b></td>
							<td><input type="number" name="newLine_CurrentValue" step="0.25" min="0.25" placeholder="0.00" readonly></td>
						</tr>
						<tr>
							<td><b>Depreciation This Month :</b></td>
							<td><input type="number" name="newLine_DepreciationValueMonth" step="0.25" min="0.25" placeholder="0.00"></td>
						</tr>
						<tr>
							<td><b>New Value After This Month :</b></td>
							<td><input type="number" name="newLine_NewDepreciationValueMonth" step="0.25" min="0.25" placeholder="0.00" readonly></td>
						</tr>
					</table>
	    		</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-lg" name="action" value="insertLine">Add</button>
					<button type="button" class="btn btn-default btn-lg" onclick="$('#newLineItemModal').modal('hide');">Cancel</button>
	    		</div>
			</form>
    	</div>
	</div>
</div>

<div class="modal fade" id="editLineItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Edit lineitem</h4>
    		</div>
			<form method="post">
				<div class="modal-body">
					<div id="edit">
						
					</div>
	    		</div>
				<input type="hidden" name="item">
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-lg" name="action" value="editLine">Edit</button>
					<button type="button" class="btn btn-default btn-lg" onclick="$('#editLineItemModal').modal('hide');">Cancel</button>
	    		</div>
			</form>
    	</div>
	</div>
</div>

<form method="post" id="mainform">
	<div class="form container">
		<table width="100%">
			<tr>
				<td>
					<b>* Depreciation No. :</b>
				</td>
				<td>
					<input name="depreciation_no" type="text" value="{{{ Session::get('depreciation_no') }}}" readonly>
				</td>
				
				<td>
					<b>* Depreciation Date :</b>
				</td>
				<td>
					<input name="depreciation_date" type="date" value="{{ Session::get('depreciation_date') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* For Month/Year :</b>
				</td>
				<td>
					<input style="width:100px" name="for_month" type="number" value="{{{ Session::get('for_month') }}}"> / 
					<input style="width:100px" name="for_year" type="number" value="{{{ Session::get('for_year') }}}">
				</td>
				
			</tr>
			
			<tr>
				<td>
					<b>Asset List :</b>
				</td>
				<td>
				</td>
				<td>
				</td>
				<td>
					<button type="button" class="btn btn-success btn-nm btn-block" onclick=""> Load All Asset</button>
				</td>
			</tr>
		</table>
		
		<table class="lineitem_table table table-bordered" border="1" width="100%">
			<thead>
				<th></th>
				<th>#</th>
				<th>* Asset ID</th>
				<th>Asset Name</th>
				<th>Asset Type</th>
				<th>Depreciation Percent</th>
				<th>Purchase Value</th>
				<th>Beginning Value</th>
				<th>Depreciation Value</th>
				<th>Current Value</th>
				<th>Depreciation This Month</th>
				<th>New Value After This Month</th>
			</thead>
			<tbody>
				<?php $i = 0; ?>
				@foreach (Session::get('lineitem') as $itm)
				<tr>
					<td style="text-align: center">
						<button type="button" class="btn btn-info btn-xs" onclick="sendFormDataAjax(); editLineItem({{ $i }});"><span class="glyphicon glyphicon-pencil"></span></button>
						<button type="button" class="btn btn-danger btn-xs" onclick="sendFormDataAjax(); deleteLineItem({{ $i }});"><span class="glyphicon glyphicon-trash"></span></button>
					</td>
					<td>
						{{ $i+1 }}
					</td>
					<td>
						{{ $itm['asset_id'] }}
					</td>
					<td>
						{{ $itm['asset_name']}}
					</td>
					<td>
						{{ $itm['asset_type'] }}
					</td>
					<td>
						{{ $itm['depreciation_percent'] }}
					</td>
					<td>
						{{ $itm['purchase_value' ]}}
					</td>
					<td>
						{{ $itm['beginning_value'] }}
					</td>
					<td>
						{{ $itm['depreciation_value'] }}
					</td>
					<td>
						{{ $itm['current_value'] }}
					</td>
					<td>
						{{ $itm['depreciation_value_month'] }}
					</td>
					<td>
						{{ $itm['new_depreciation_value_month'] }}
					</td>
				</tr>
				<?php $i++; ?>
				@endforeach
				<tr>
					<td colspan="12" style="border: none;">
						<button type="button" class="btn btn-success btn-lg btn-block" onclick="sendFormDataAjax(); $('#newLineItemModal').modal();"><span class="glyphicon glyphicon-plus"></span> add new lineitem</button>
					</td>
				</tr>
			</tbody>
		</table>

		<table width="100%" style="text-align: right; margin-bottom: 20px;">
			<tr>
				<td style="width: 55%"></td>
				<td>Total Depreciation Value :</td>
				<td><input type="text" name="Total" style="text-align:right;" value="{{{ Session::get('total_depreciation') }}}" readonly></td>
			</tr>
		</table>
		
		<input type="hidden" name="dirtybit" value="{{ Session::get('dirtybit') }}">
		<input type="hidden" name="table" value="assetid">
	</form>
</div>
@include('footer')