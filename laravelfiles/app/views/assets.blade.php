@include('header')
{{ HTML::script('static/js/asset_id.js') }}

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
							<td><b>* Component Name :</b></td>
							<td><input type="text" name="newLine_component_name" required></td>
						</tr>
						<tr>
							<td><b>* Component Type :</b></td>
							<td><select name="newLine_component_type">
							<?php foreach(AssetType::all()->ToArray() as $option): ?>
							<option value="{{ $option['asset_type'] }}">{{ $option['asset_type'] }}</option>
							<?php endforeach; ?>	
							</select></td>
						</tr>
						<tr>
							<td><b>* Quantity :</b></td>
							<td><input type="number" name="newLine_quantity" step="1" min="1" required></td>
						</tr>
						<tr>
							<td><b>Rough Value of this part :</b></td>
							<td><input type="number" name="newLine_rough_value" step="0.25" placeholder="0.00"></td>
						</tr>
						<tr>
							<td><b>Notes :</b></td>
							<td><input type="text" name="newLine_notes" step="1"></td>
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
					<b>* Asset ID :</b>
				</td>
				<td>
					<input name="asset_id" type="text" value="{{{ Session::get('asset_id') }}}" readonly>
				</td>
				
				<td>
					<b>* Purchase Value :</b>
				</td>
				<td>
					<input type="number" step="0.25" placeholder="0.00" name="purchase_value" value="{{ Session::get('purchase_value') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Asset Name :</b>
				</td>
				<td>
					<input name="asset_name" type="text" value="{{{ Session::get('asset_name') }}}">
				</td>
				
				<td>
					<b>* Purchase Date :</b>
				</td>
				<td>
					<input name="purchase_date" type="date" value="{{{ Session::get('purchase_date') }}}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Asset Type :</b>
				</td>
				<td>
					<select name="asset_type">
						<?php foreach(AssetType::all()->ToArray() as $option)
							{
								if($option['asset_type'] == Session::get('asset_type'))
								{ ?>
									<option value="{{{ $option['asset_type'] }}}" selected>{{{ $option['asset_type'] }}}</option>
						<?php		} 
								else
								{ ?>
									<option value="{{{ $option['asset_type'] }}}">{{{ $option['asset_type'] }}}</option>
						<?php		}
							}
						?>
					</select>
				</td>
				
				<td>
					<b>* Beginning Value :</b>
				</td>
				<td>
					<input name="beginning_value" type="number" step="0.25" min="0" placeholder="0.00 " value="{{ Session::get('beginning_value') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Unit :</b>
				</td>
				<td>
					<input name="unit" type="text" value="{{{ Session::get('unit') }}}">	
				</td>
				
				<td>
					<b>Depreciated Value :</b>
				</td>
				<td>
					<input name="depreciated_value" type="number" step="0.25" min="0" placeholder="0.00" value="{{{ Session::get('depreciated_value') }}}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Yearly Depreciation :</b>
				</td>
				
				<td>
					<input name="yearly_depreciation" type="text" min="0" value="{{{ Session::get('yearly_depreciation') }}}"> %
				</td>
				
				<td>
					<b>Current Value :</b>
				</td>
				
				<td>
					<input name="current_value" type="number" step="0.25" min="0" placeholder="0.00" value="{{ Session::get('current_value') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>Components :</b>
				</td>
			</tr>
		</table>
		
		<table class="lineitem_table table table-bordered" border="1" width="100%">
			<thead>
				<th></th>
				<th>#</th>
				<th>* Component Name</th>
				<th>* Component Type</th>
				<th>* Quantity</th>
				<th>Rough Value of this part</th>
				<th>Notes</th>
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
						{{ $itm['component_name'] }}
					</td>
					<td>
						{{ $itm['component_type']}}
					</td>
					<td>
						{{ $itm['quantity'] }}
					</td>
					<td>
						{{ $itm['rough_value' ]}}
					</td>
					<td>
						{{ $itm['notes'] }}
					</td>
				</tr>
				<?php $i++; ?>
				@endforeach
				<tr>
					<td colspan="7" style="border: none;">
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