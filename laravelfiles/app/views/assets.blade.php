@include('header')
{{ HTML::script('static/js/asset_id.js') }}

<div class="modal fade" id="newLineItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add new lineitem</h4>
    		</div>

			<div class="modal-body">
				<table width="100%">
					<tr>
						<td><b>* Component Name :</b></td>
						<td><input type="text" name="newLine_component_name"></td>
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
						<td><input type="number" name="newLine_component_type" step="1" min="1"></td>
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
				<button class="btn btn-primary btn-lg" name="action" value="addLineItem">Add</button>
				<button class="btn btn-default btn-lg" onclick="$('#newLineItemModal').modal('hide');">Cancel</button>
    		</div>
    		
    		<form>
    		</form>
    	</div>
	</div>
</div>

<div class="form container">
		<table width="100%">
			<tr>
				<td>
					<b>* Asset ID :</b>
				</td>
				<td>
					<input name="asset_id" type="text" value="{{ Session::get('asset_id') }}" disabled>
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
					<input name="asset_name" type="text" value="{{ Session::get('asset_name') }}">
				</td>
				
				<td>
					<b>* Purchase Date :</b>
				</td>
				<td>
					<input name="purchase_date" type="date" value="{{ Session::get('purchase_date') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Asset Type :</b>
				</td>
				<td>
					<input name="asset_type" type="text" value="{{ Session::get('asset_type') }}" disabled><button class="btn form_button"><span class="glyphicon glyphicon-search"></span></button>
				</td>
				
				<td>
					<b>* Beginning Value :</b>
				</td>
				<td>
					<input name="beginning_value" type="number" step="0.25" placeholder="0.00 " value="{{ Session::get('beginning_value') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Unit :</b>
				</td>
				<td>
					<input name="unit" type="text" value="{{ Session::get('unit') }}">	
				</td>
				
				<td>
					<b>Depreciated Value :</b>
				</td>
				<td>
					<input name="depreciated_value" type="number" step="0.25" placeholder="0.00" value="{{ Session::get('depreciated_value') }}">
				</td>
			</tr>
			
			<tr>
				<td>
					<b>* Yearly Depreciation :</b>
				</td>
				
				<td>
					<input name="yearly_depreciation" type="text" value="{{ Session::get('yearly_depreciation') }}"> %
				</td>
				
				<td>
					<b>Current Value :</b>
				</td>
				
				<td>
					<input name="current_value" type="number" step="0.25" placeholder="0.00" value="{{ Session::get('current_value') }}">
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
				<?php $i = 1; ?>
				@foreach (Session::get('lineitem') as $itm)
				<tr>
					<td style="text-align: center">
						<button class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>
						<button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button>
					</td>
					<td>
						{{ $i }}
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
						<button type="button" class="btn btn-success btn-lg btn-block" onclick="$('#newLineItemModal').modal();"><span class="glyphicon glyphicon-plus"></span> add new lineitem</button>
					</td>
				</tr>
			</tbody>
		</table>
		
		<input type="hidden" name="dirtybit" value="{{ Session::get('dirtybit') }}">
		<input type="hidden" name="mode">
		<input type="hidden" name="table" value="assetid">
		
	</form>
</div>
@include('footer')