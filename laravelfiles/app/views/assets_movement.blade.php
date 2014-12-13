@include('header')
{{ HTML::script('static/js/asset_movement.js') }}

<div class="modal fade" id="newLineItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add new lineitem</h4>
    		</div>
			<form method="post" id="addLineForm">
				<div class="modal-body">
					<table width="100%">
						<tr>
							<td><b>* Asset ID :</b></td>
							<td><input type="text" name="newLine_asset_id" required><button type="button" onclick="addSearchColumn('asset'); openListOfValue('asset_id','selectAsset'); $('#newLineItemModal').modal('hide');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="newLine_asset_name" readonly></td>
						</tr>
						<tr>
							<td><b>Current Location :</b></td>
							<td><input type="text" name="newLine_currentLocation" readonly></td>
						</tr>
						<tr>
							<td><b>* New Location :</b></td>
							<td><input type="text" name="newLine_newLocation" required><button type="button" onclick="addSearchColumn('location'); openListOfValue('location','selectLocation'); $('#newLineItemModal').modal('hide');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
					</table>
	    		</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-lg" name="action" value="insertLine">Add</button>
					<button type="button" class="btn btn-default btn-lg" onclick="$('#addLineForm')[0].reset(); $('#newLineItemModal').modal('hide');">Cancel</button>
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
				<td width="25%">
					<b>* Asset Movement No. :</b>
				</td>
				<td>
					<input name="assetmoveNo" type="text" value="{{{ Session::get('assetmoveNo') }}}" readonly>
				</td>
			</tr>
			<tr>
				<td width="25%">
					<b>* Movement Date :</b>
				</td>
				<td>
					<input type="date" name="movementDate" value="{{ Session::get('movementDate') }}">
				</td>
			</tr>
			<tr>
				<td width="25%">
					<b>* Reason :</b>
				</td>
				<td>
					<select name="assetmoveReason">
						<option value="purchase" <?php if(Session::get('assetmoveReason') == 'purchase') echo 'selected'; ?>>Purchase</option>
						<option value="transfer" <?php if(Session::get('assetmoveReason') == 'transfer') echo 'selected'; ?>>Transfer</option>
						<option value="sales" <?php if(Session::get('assetmoveReason') == 'sales') echo 'selected'; ?>>Sales</option>
					</select>
				</td>
			</tr>
		</table>
		
		<table class="lineitem_table table table-bordered" border="1" width="100%">
			<thead>
				<th></th>
				<th>#</th>
				<th>Asset ID</th>
				<th>Asset Name</th>
				<th>Current Location</th>
				<th>New Location</th>
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
						{{ $itm['asset_name'] }}
					</td>
					<td>
						{{ $itm['currentLocation'] }}
					</td>
					<td>
						{{ $itm['newLocation'] }}
					</td>
				</tr>
				<?php $i++; ?>
				@endforeach
				<tr>
					<td colspan="6" style="border: none;">
						<button type="button" class="btn btn-success btn-lg btn-block" onclick="sendFormDataAjax(); $('#newLineItemModal').modal();"><span class="glyphicon glyphicon-plus"></span> add new lineitem</button>
					</td>
				</tr>
			</tbody>
		</table>
		
		<input type="hidden" name="dirtybit" value="{{ Session::get('dirtybit') }}">
		<input type="hidden" name="table" value="assetid">
	</form>
</div>
@include('footer')