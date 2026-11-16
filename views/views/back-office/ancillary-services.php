<style>
	#BO_AS_table{
		display: none;
	}
	/* th.th_misc_resolutuin_escalation_status {
		display: none;
	} */
</style>
<div class="example-box-wrapper details_box"  id="reports_box">
	 <div class="add_field right-btn" style = "display:none">
		<input class="form-control submit-field-button" type="button" id="save_field_num_AS" name="save_field_num_AS_data" value="Add"
		   onclick="add_BO_ancl_srvc_Row()">
    </div>
	<table id="BO_AS_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th></th> 
				<th></th> 
				<th>Service Status</th>	
				<th>Advocate</th>  
				<th>Practitioner</th> 
				<th>Service</th>  
				<th>Misc Resolution Escalation Status</th>	
				<!-- <th>Tax Preparer</th> -->
			</tr>
		</thead>
		<tbody id="tab_BO_AS_tbody">
		</tbody>
	</table>
	<input class="form-control submit-back-office-button" type="submit" id="save_tab_BO_AS" name="save_tab_BO_AS_data" value="Save" onclick="saveAllBOAncillaryServices()">
</div>
