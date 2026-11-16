<style>
	input#save_tab_BO_RS {
		width: 6%;
		background: #2cb31c;
		color: #fff;
		margin-left: 50%;
		margin-top: 15px;
	}
	/* th.th_escalation_status {
		display: none;
	} */
</style>

<div class="example-box-wrapper details_box"  id="reports_box">
	<div class="add_field  right-btn" style = "display:none">		
		<input class="form-control submit-field-button" type="button" id="save_field_num_RS" name="save_field_num_RS_data" value="Add"
		   onclick="addResolutionRow()">
    </div>
	<table id="BO_RS_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th></th> 
				<th></th> 
				<th>Resolution Type</th>
				<th>Collection Division</th>
				<th>Advocate</th> 
				<th>Practitioner</th>
				<th>Service Status</th>
				<th>State</th> 
				<th>Business Contact</th>
				<th>Resolution Escalation Status</th>
			</tr>
		</thead>
		<tbody id="tab_BO_RS_tbody">
		</tbody>
	</table>
	<input class="form-control" type="submit" id="save_tab_BO_RS" name="save_tab_BO_RS_data" value="Save" onclick="saveAllBOResolution()">
</div>
