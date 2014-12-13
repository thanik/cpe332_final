@include('header')
{{ HTML::script('static/js/asset_purchase.js') }}

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
							<td><input type="text" name="newLine_AssetID" required><button type="button" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button></td>
						</tr>
						<tr>
							<td><b>Asset Name :</b></td>
							<td><input type="text" name="newLine_AssetName" readonly></td>
						</tr>
						<tr>
							<td><b>Units :</b></td>
							<td><input type="text" name="newLine_Units" readonly></td>
						</tr>
						<tr>
							<td><b>Price :</b></td>
							<td><input type="number" name="newLine_rough_value" step="0.25" placeholder="0.00" required></td>
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
					<b>* Invoice No. :</b>
				</td>
				<td>
					<input name="InvoiceNo" type="text" value="{{{ Session::get('InvoiceNo') }}}" readonly>
				</td>
				
				<td>
					<b>* Invoice Date :</b>
				</td>
				<td>
					<input type="date" name="InvoiceDate" value="{{ Session::get('InvoiceDate') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Supplier Code :</b>
				</td>
				<td>
					<input name="SupplierCode" type="text" value="{{{ Session::get('SupplierCode') }}}" required readonly>
					<button type="button" onclick="addSearchColumn('supplier'); openListOfValue('supplier','chooseSupplier');" class="form_button btn"><span class="glyphicon glyphicon-search"></span></button>
				</td>
				
				<td>
					<b>Supplier Name :</b>
				</td>
				<td>
					<input name="SupplierName" type="text" value="{{{ Session::get('SupplierName') }}}" readonly>
				</td>
			</tr>
			
			<tr>
				<td>
					<b>Address :</b>
				</td>
				<td colspan="3">
					<input name="SupplierAddress" style="width: 100%" type="text" value="{{{ Session::get('SupplierAddress') }}}" readonly>
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Payment Due Date :</b>
				</td>
				<td>
					<input name="PaymentDueDate" type="date" value="{{{ Session::get('PaymentDueDate') }}}">	
				</td>
				
				<td>
					<b>Payment Term :</b>
				</td>
				<td>
					<select name="PaymentTerm">
						<option value="cash" <?php if(Session::get('PaymentTerm') == 'cash') echo 'selected'; ?>>Cash</option>
						<option value="check" <?php if(Session::get('PaymentTerm') == 'check') echo 'selected'; ?>>Check</option>
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
				<th>Units</th>
				<th>Price</th>
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
						{{ $itm['AssetID'] }}
					</td>
					<td>
						{{ Asset::where('asset_id','=',$itm['AssetID'])->first()->asset_name }}
					</td>
					<td>
						{{ Asset::where('asset_id','=',$itm['AssetID'])->first()->unit }}
					</td>
					<td>
						{{ $itm['Price'] }}
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
		
		<table width="100%" style="text-align: right; margin-bottom: 20px;">
			<tr>
				<td style="width: 65%"></td>
				<td>Total :</td>
				<td><input type="text" name="Total" style="text-align:right;" value="{{{ Session::get('Total') }}}" readonly></td>
			</tr>
			<tr>
				<td style="width: 65%"></td>
				<td>VAT :</td>
				<td><input type="text" name="VAT" style="text-align:right;" value="{{{ Session::get('VAT') }}}" readonly></td>
			</tr>
			<tr>
				<td style="width: 65%"></td>
				<td>Amount Due :</td>
				<td><input type="text" name="AmountDue" style="text-align:right;" value="{{{ Session::get('AmountDue') }}}" readonly></td>
			</tr>
		</table>
		
		<input type="hidden" name="dirtybit" value="{{ Session::get('dirtybit') }}">
		<input type="hidden" name="table" value="assetid">
	</form>
</div>
@include('footer')