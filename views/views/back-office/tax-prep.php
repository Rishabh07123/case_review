<style>
	/* th.th_tax_prep_escalation_status {
		display: none;
	} */
	
</style>
<div class="example-box-wrapper details_box"  id="reports_box">
    <div class="add_field" style="display:none;">
		<div id="tax_prep_service_add"></div>
		<input class="form-control submit-field-button" type="button" id="save_field_num_TP" name="save_field_num_TP_data" value="Add"
		   onclick="add_num_Field_rows_TP()">
    </div>
	<div style="width: 100%; overflow-x: auto;">
	<table id="BO_TP_table" class="table table-striped table-bordered caselist" cellspacing="0"  style="min-width: 100%; white-space: nowrap;">
		<thead>
			<tr>
				<th></th> 
				<th></th> 
				<th>Return Type</th> 
				<th>Year</th> 
				<th>Advocate</th> 
				<th>Tax Preparer</th>
				<th>Service Status</th>
				<th>Tax Prep Escalation Status</th>	
			</tr>
		</thead>
		<tbody id="tab_BO_TP_tbody">
		</tbody>
	</table>
	</div>
	<input class="form-control submit-back-office-button" type="submit" id="save_tab_BO_TP" name="save_tab_BO_TP_data" value="Save"
	onclick="save_All_BO_TaxPrepration()">
</div>
