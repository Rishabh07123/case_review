<div class="example-box-wrapper details_box"  id="reports_box">
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium;">
<form action="javascript:saveTabPPSStatePersDetails();">
<table id="tab_pps_state_pers_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Tax Period</th> 
			<th>Balance</th>
			<th>Filing Status</th>	 
			<th>Account Status</th>
			<th>CSED</th>
			<th>Required/Original Recommended</th> 
			<th>FTA Eligible</th>
			<th>Liens</th>
			<th>Reported Wages</th>
			<th>Reported SE</th>
			<th>Reported Other</th> 
			<th>Gross Monthly Income</th>
			<th>Taxes Withheld</th>
			<th>Tin</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody class="tab_pps_state_pers_tbody">
		
	</tbody>
</table>
<input class="form-control" type="submit" id="save_tab_pps_state_pers_data" name="save_tab_pps_state_pers_data" value="Save">
</form>
</div>

<div class="example-box-wrapper details_box"  id="reports_box">
<h3> All Case PPS </h3>

<!--<div id="more_pps" style="display: none; float: left; margin-left: 180px; margin-top: -27px; margin-bottom: 10px;">

</div> -->

<table id="tab_all_case_pps_table_state" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			
			<th>Name</th> 
			<th>PPS Status</th>
			<th>PPS Type</th>
			<th>POA Issues</th>	
			<th>Business POA Issues</th>
			<th>Verbal POA</th>
			<th>State</th>	 
			<th>PPS Instructions</th>	 
			<th>Modified By</th>
			<th>Assigned To</th>
			<th>Date Modified</th>	
			<th>Action</th> 
		</tr>
	</thead>
	<tbody class="tab_all_case_pps_tbody_state">
	</tbody> 
</table>
</div>

<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="tab_pps_state_pers_note_box">
		<div class="box-comment" id="tab_pps_state_pers_note_pps"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="tab_add_note_pps_state_pers" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_state_pers_note_txt_pps"></textarea>
					<input class="form-control" type="hidden" id="pps_state_pers_id_tab" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPSStatePersNote" class="btn btn-primary" onclick="tabAddNotePpsStatePers();"> Add Note</button>					
					</div>
				</form>
				<!-- <p id="tab_note_pps_msg"></p> -->
				<div id="tab_pps_state_pers_notes">
					
				</div>
			</div>
		</div>
	</div>
</div>