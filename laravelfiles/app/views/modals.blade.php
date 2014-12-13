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
				Search:
                <select id="columnName">
                    
                </select>
                <select id="listCondition">
                    <option value="startwith">start with</option>
                    <option value="has">has</option>
                    <option value="between">between</option>
                    <option value="=">=</option>
                    <option value="&gt;">&gt;</option>
                    <option value="&gt;=">&gt;=</option>
                    <option value="&lt;">&lt;</option>
                    <option value="&lt;=">&lt;=</option>
                </select>
                <input type="text" id="searchQuery1" placeholder="Search query" />
                <input type="text" id="searchQuery2" />

                <a id="cmdEmployeeSearch" href="#" onclick="getListOfValueSearch();" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></a>
                <a id="cmdEmployeeAll" href="#" onclick="getListOfValueAll();" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span></a>
    		</div>
    		
    		<form>
	    		<input type="hidden" name="listofvalue_table">
	    		<input type="hidden" name="listofvalue_mode">
    		</form>
    	</div>
	</div>
</div>