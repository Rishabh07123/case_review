<!-- file added on 04-04-2024  https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed -->
<div class="example-box-wrapper details_box"  id="reports_box">
    <div class="add_field" style="display:none;">
		<div id="ctax_plus_service_add"></div>
		<input class="form-control submit-field-button" type="button" id="save_field_num_CP" name="save_field_num_CP_data" value="Add"
		   onclick="add_num_Field_rows_CP()">
    </div>
	<table id="BO_CP_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th></th> 
				<th></th> 
				<th>Name</th> 
				<th>Initial Ctax+ Year Included</th> 
				<th>CTAX+ Start Date</th> 
				<th>CTAX+ Intro Call</th> 
				<th>CTAX+ Status</th> 
			</tr>
		</thead>
		<tbody id="tab_BO_CP_tbody">
		</tbody>
	</table>
	<input class="form-control submit-back-office-button" type="submit" id="save_tab_BO_CP" name="save_tab_BO_CP_data" value="Save"
	onclick="save_All_BO_CTAXPLUS()">
</div>
