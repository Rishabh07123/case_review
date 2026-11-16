<style>
.modal-backdrop.in {
    /* opacity: .5; */
    z-index: 0;
}

.select2-container--default
	{			
		width: 100% !important;
	}
</style>

<!-- Added on 07-10-2022  https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
<div class="example-box-wrapper details_box pps_info"  id="reports_box">
<form action="javascript:ppsInfoPremiumSpouse();" id="pps-info-form-premium-spouse">
	<div class="row">
		<!-- Added on 18-04-2024  https://trello.com/c/YsTzk5k6/1215-cr-portal-add-required-tax-status-field-in-pps-tab -->
		<div class="col-md-3">
			<table id="pps-completed" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>PPS Completed Using</b></td>
						<td>
							<select class="form-control" id="pps-completed-using-premium-spouse" style="width:auto" required>
							</select>
							<div class='pps-completed-error' style="display:none; color:red;">
								Please select PPS Completed Using
							</div>
						</td>
					<tr>
				</tbody>
			</table>
		</div><!-- end Added on 18-04-2024  -->

		<div class="col-md-3">
			<table id="tin" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>TIN</b></td>
						<td id="ssn-tin-premium-spouse"></td>
					<tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<table id="filling-status" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Filing Status</b></td>
						<td id="marital-status-premium-spouse"></td>
					<tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<table id="filling-status" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>MFJ Cross Reference</b></td>
						<td>
							<select class="form-control" id="cross-reference-premium-spouse" style="width:auto" required>
							</select>
							<div class='mfj-error' style="display:none; color:red;">
								Please select MFJ Cross Reference
							</div>
						</td>
					<tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<table class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Civil Penalties</b></td>
						<td>
							<select class="form-control" id="civil-penalties-premium-spouse" style="width:auto" required>
								
							</select>
							<div class='civil-penalties-error' style="display:none; color:red;">
								Please select Civil Penalties
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Shared Responsibility</b></td>
						<td>
							<select class="form-control" id="shared-responsibility-premium-spouse" style="width:auto" required>
								
							</select>							
							<div class='shared-responsibility-error' style="display:none; color:red;">
								Please select Shared Responsibility
							</div>
						</td>
					<tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<table class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Passport Certification</b></td>
						<td>
							<select class="form-control" id="passport-cretification-premium-spouse" style="width:auto" required>
								
							</select>						
							<div class='passport-error' style="display:none; color:red;">
								Please select Passport Certification Year
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Passport Certification Years<b></td>
						<td>
							<select id="passport-cretification-year-premium-spouse" class="form-control" multiple>

							</select>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<table class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Issues not covered by POA</b></td>
						<td>
							<select class="form-control" id="isssue_by_POA_premium_spouse"  style="width:auto" required>
								
							</select>
							<div class='poa-error' style="display:none; color:red;">
								Please select POA is covered or not.
							</div>
						</td>
					<tr>
					<tr>
						<td><b>FTA Eligible<b></td>
						<td>
							<select id="FTA-eligible-year-premium-spouse" class="form-control" multiple>
								
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<input class="form-control" type="submit" id="save_tab_pps_premium_spouse_data" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;"  name="save_tab_pps_premium_spouse_data" value="Save">
</form>
</div>
<h3>PPS Details</h3>
<!-- end added on 07-10-2022 -->
<!-- <div class="example-box-wrapper details_box"  id="reports_box"> Commented on 07-10-2022 -->
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium; margin-right: 180px;">
<h3 style="margin-left:2%"><b>1040</b></h3>  <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
<form action="javascript:saveTabPPSDetailsPremiumSpouse();">

<table id="tab_pps_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Tax Period</th> 
			<th>Balance</th>
			<!--<th>Accrued/Assessed</th> commented on 03-11-2022--> <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			<th>CSED</th> <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			<th>Multiple CSED</th> <!-- added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator-->
			<th>Filing Status</th>
			<th>Account Status</th>
			<th>Required/Original Recommended</th>  
			<th>Liens</th>
			<th>Levy/Garnishment</th> <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->			
			<th>Separate Assessment</th> <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			<th>Credits/ETPs</th>	 <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			
			<!-- <th>CSED</th>  commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			
		
			<!-- <th>Required/Original Recommended</th>  
			<th>FTA Eligible</th>			
			<th>Reported Wages</th>
			<th>Reported SE</th>
			<th>Reported Other</th> 
			<th>Gross Monthly Income</th>
			<th>Taxes Withheld</th>
			<th>TIN</th> Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			<th>Action</th>
		</tr>
	</thead>
	<tbody class="tab_pps_premium_spouse_tbody">
	</tbody>
</table>
<!-- <input class="form-control" type="submit" id="save_tab_pps_data" name="save_tab_pps_data" value="Save">
</form> Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
<!-- </div>-->

<!--  Addeed on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul  -->
<p>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExamplePremiumSpouse" aria-expanded="false" aria-controls="collapseExamplePremiumSpouse" style="margin-bottom:20px;">
   Income Information
  </button>
</p>
<div class="collapse" id="collapseExamplePremiumSpouse">
	<!-- <form action="javascript:saveIncomeInfoDetails();"> -->
	<table id="income_info_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Tax Period</th>
				<th>Reported Wages</th>
				<th>Reported SE</th>
				<th>Reported Other</th> 
				<th>Gross Monthly Income</th>
				<th>Taxes Withheld</th>
				<!-- <th>Action</th> -->
			</tr>
		</thead>
		<tbody class="income_info_premium_spouse_tbody">
		</tbody>
	</table>
</div>
<input class="form-control" type="submit" id="save_tab_pps_details_data_premium_spouse" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;"  name="save_tab_pps_details_data_premium_spouse" value="Save">
</form>
<div class="example-box-wrapper details_box civil_penalties_premium_spouse"  id="reports_box_premium_spouse" style="display:none">
	<h3> Civil Penalties </h3>
	<form action="javascript:saveCivilPenaltiesDetailsPremiumSpouse();">
	<table id="civil_penalties_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td>Tax Period</td>
				<td>Quarter</td>
				<td>Balance</td>
				<td>Accrued/Assessed</td>
				<td>CSED</td>
				<td>Filing Status</td>
				<td>Account Status</td>
				<td>FTA Eligible</td>
				<td>Liens</td>
				<td>Levy/Garnishment</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<input class="form-control" type="submit" id="save_tab_pps_cp_data_premium_spouse" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;"  name="save_tab_pps_cp_data_premium_spouse" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box shared_responsibility_premium_spouse"  id="reports_box_premium_spouse" style="display:none">
	<h3>Shared Responsibility </h3>
	<form action="javascript:saveSharedResponsibilityDetailsPremiumSpouse();">
	<table id="shared_responsibility_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td>Tax Period</td>
				<td>Balance</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<input class="form-control" type="submit" id="save_tab_pps_sr_data_premium_spouse" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;"  name="save_tab_pps_sr_data_premium_spouse" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box resolution_present_div_premium_spouse"  id="reports_box_premium_spouse" style="display:none">
	<h3>Resolution Present </h3>
	<form action="javascript:saveResolutionPresentDetailsPremiumSpouse();">
	<table id="resolution_present_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<td>Resolution</td>
				<td>Years</td>
				<td>Date Entered</td>
				<td>Monthly Payment Amount</td>
				<td>Monthly Due Date</td>
				<td>Last Payment Date</td>
				<td>Default Date</td>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<input class="form-control" type="submit" id="save_tab_pps_rp_data_premium_spouse" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;" name="save_tab_pps_rp_data_premium_spouse" value="Save">
</form>
</div>
<!-- end added on 07-10-2022 -->

<div class="example-box-wrapper details_box"  id="reports_box_premium_spouse">
<h3> All Case PPS </h3>

<!--<div id="more_pps" style="display: none; float: left; margin-left: 180px; margin-top: -27px; margin-bottom: 10px;">

</div> -->

<table id="tab_all_case_pps_premium_spouse_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			
			<th>Name</th> 
			<th>PPS Status</th>
			<th>PPS Type</th>
			<th>POA Issues</th>	
			<th>Business POA Issues</th>	 
			<th>Modified By</th>
			<th>Assigned To</th>
			<th>Date Modified</th>	
			<th>Action</th> 
		</tr>
	</thead>
	<tbody class="tab_all_case_pps_premium_spouse_tbody">
	</tbody> 
</table>
</div>
<br>

<h3 style="color: black;">PPS Instructions</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box_premium_spouse">
		<div class="box-comment" id="instruct_pps2_premium_spouse"> 
			
			<div class="col-md-12">
				
					
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps"></textarea> -->
					
					<div class="col-md-10"> 
						<textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="tab_pps_instruct_note_premium_spouse" readonly></textarea> 
					</div>
					
					<div class="col-md-2"> 
					<button class="btn btn-edit editppsinstruct" id="tab_editppsinstruct_premium_spouse" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_instruction_premium_spouse();">Edit</button>  
					<button class="btn btn-save saveppsinstruct" id="tab_saveppsinstruct_premium_spouse" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_instruction_premium_spouse(this);">Save</button>  
					</div>
					
				
				</div>
			
		</div>
	</div>
</div>

<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box_premium_spouse">
		<div class="box-comment" id="note_pps2_premium_spouse"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="tab_add_note_pps_premium_spouse" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps_premium_spouse"></textarea>
					<input class="form-control" type="hidden" id="pps_premium_spouse_id_tab" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPSNotePremiumSpouse" class="btn btn-primary" onclick="tab_add_note_pps_premium_spouse();"> Add Note</button>					
					</div>
				</form>
				<!-- <p id="tab_note_pps_msg"></p> -->
				<div id="tab_pps_notes_premium_spouse">
					
				</div>
			</div>
		</div>
	</div>
</div>
