<style>
	#BO_Mn_table{
		display: none;
	}
</style>
<div class="example-box-wrapper details_box"  id="reports_box">
	<div class="add_field  right-btn" style = "display:none">		
		<input class="form-control submit-field-button" type="button" id="save_field_num_MN" name="save_field_num_MN_data" value="Add"
		   onclick="addMonitoringRow()">
    </div>
	<table id="BO_Mn_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th></th>				 
				<th></th>				 
				<!-- <th>TAP Service</th> -->
				<th>TAP Service Started</th>
				<th>Service Status</th>	
				<th>Practitoner</th>	
			</tr>
		</thead>
		<tbody id="tab_BO_Mn_tbody">
		</tbody>
	</table>
	<input class="form-control submit-back-office-button" type="submit" id="save_tab_BO_Mn" name="save_tab_BO_Mn_data" value="Save" onclick="save_All_BO_mntrg()">
</div>
