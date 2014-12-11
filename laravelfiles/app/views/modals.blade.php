<div class="modal fade" id="ListOfValueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">List Of Value</h4>
    		</div>

			<div class="modal-body">
				<table id="listofvalue" class="table table-bordered table-hover" style="display: none" width="100%">
					
				</table>
    		</div>
			
			<div class="modal-footer">
				
    		</div>
    		
    		<form>
	    		<input type="hidden" name="listofvalue_table">
    		</form>
    	</div>
	</div>
</div>

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
						<td><input type="text" name="newLine_component_type"></td>
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
				<button class="btn btn-primary btn-lg">Add</button>
				<button class="btn btn-default btn-lg" onclick="$('#newLineItemModal').modal('hide');">Cancel</button>
    		</div>
    		
    		<form>
    		</form>
    	</div>
	</div>
</div>