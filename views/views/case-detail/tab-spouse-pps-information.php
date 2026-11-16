<div class="example-box-wrapper details_box"  id="reports_box">
<!-- Commented on 03-11-2022 
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium;">
<form action="javascript:saveTabSpousePPSDetails();">
<table id="tab_spouse_pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="tab_spouse_pps_tbody">
		
	</tbody>
</table>
<input class="form-control" type="submit" id="save_tab_spouse_pps_data" name="save_tab_spouse_pps_data" value="Save">
</form>
</div>
-->
<!-- Added on 03-11-2022 -->
<form action="javascript:ppsInfoSp();" id="pps-info-form-sp">
	<div class="row">
		<!-- Added on 18-04-2024  https://trello.com/c/YsTzk5k6/1215-cr-portal-add-required-tax-status-field-in-pps-tab -->
		<div class="col-md-3">
			<table id="pps-completed-spouse" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>PPS Completed Using</b></td>
						<td>
							<select class="form-control" id="pps-completed-using-spouse" style="width:auto" required>
							</select>
							<div class='pps-completed-error' style="display:none; color:red;">
								Please select PPS Completed Using
							</div>
						</td>
					<tr>
				</tbody>
			</table>
		</div><!-- end Added on 18-04-2024   -->


		<div class="col-md-3">
			<table id="tin" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>TIN</b></td>
						<td id="ssn-tin-sp"></td>
					<tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<table id="filling-status" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Filing Status</b></td>
						<td id="marital-status-sp"></td>
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
							<select class="form-control" id="cross-reference-sp" style="width:auto" required>
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
							<select class="form-control" id="civil-penalties-sp" style="width:auto" required>
								
							</select>
							<div class='civil-penalties-sp-error' style="display:none; color:red;">
								Please select Civil Penalties
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Shared Responsibility</b></td>
						<td>
							<select class="form-control" id="shared-responsibility-sp" style="width:auto" required>
								
							</select>							
							<div class='shared-responsibility-sp-error' style="display:none; color:red;">
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
							<select class="form-control" id="passport-cretification-sp" style="width:auto" required>
								
							</select>						
							<div class='passport-error' style="display:none; color:red;">
								Please select Passport Certification Year
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Passport Certification Years<b></td>
						<td>
							<select id="passport-cretification-year-sp" class="form-control" multiple>

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
							<select class="form-control" id="isssue_by_POA-sp"  style="width:auto" required>
								
							</select>
							<div class='poa-error' style="display:none; color:red;">
								Please select POA is covered or not.
							</div>
						</td>
					<tr>
					<tr>
						<td><b>FTA Eligible<b></td>
						<td>
							<select id="FTA-eligible-year-sp" class="form-control" multiple>
								
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<input class="form-control" type="submit" id="save_tab_spouse_pps_info_data" name="save_tab_spouse_pps_info_data" value="Save">
</form>
</div>
<h3>PPS Details</h3>
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium;">
	<h3 style="margin-left:2%"><b>1040</b></h3>

<form action="javascript:saveTabSpousePPSDetails();">
<table id="tab_spouse_pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Tax Period</th> 
			<th>Balance</th>
			<!-- <th>Accrued/Assessed</th> -->
			<th>CSED</th>
			<th>Multiple CSED</th> <!-- added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator-->
			<th>Filing Status</th>	
			<th>Account Status</th>
			<th>Required/Original Recommended</th> 
			<th>Liens</th>
			<th>Levy/Garnishment</th>
			<th>Separate Assessment</th>	 
			<th>Credits/ETPs</th> 
			<!-- <th>CSED</th> 
			<th>FTA Eligible</th>
			
			<th>Reported Wages</th>
			<th>Reported SE</th>
			<th>Reported Other</th> 
			<th>Gross Monthly Income</th>
			<th>Taxes Withheld</th>
			<th>Tin</th>-->
			<th>Action</th>
		</tr>
	</thead>
	<tbody class="tab_spouse_pps_tbody">
		
	</tbody>
</table>
<!-- <input class="form-control" type="submit" id="save_tab_spouse_pps_data" name="save_tab_spouse_pps_data" value="Save">
</form> -->
<!-- </div> -->
<p>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" style="margin-bottom:20px;" >
   Income Information
  </button>
</p>
<div class="collapse" id="collapseExample2">
	<!-- <form action="javascript:saveIncomeInfoDetails();"> -->
	<table id="income_info_table_sp" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
		<tbody class="income_info_tbody_sp">
		</tbody>
	</table>
</div>
<input class="form-control" type="submit" id="save_tab_spouse_pps_data" name="save_tab_spouse_pps_data" value="Save">
</form>
<div class="example-box-wrapper details_box civil_penalties_sp"  id="reports_box" style="display:none">
	<h3> Civil Penalties </h3>
	<form action="javascript:saveCivilPenaltiesDetailsSP();">
	<table id="civil_penalties_sp_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_spouse_pps_cp_data" name="save_tab_spouse_pps_cp_data" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box shared_responsibility_sp"  id="reports_box" style="display:none">
	<h3>Shared Responsibility </h3>
	<form action="javascript:saveSharedResponsibilityDetailsSP();">
	<table id="shared_responsibility_sp_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_spouse_pps_sr_data" name="save_tab_spouse_pps_sr_data" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box resolution_present_div_sp"  id="reports_box" style="display:none">
	<h3>Resolution Present </h3>
	<form action="javascript:saveResolutionPresentDetailsSpouse();">
	<table id="resolution_present_sp_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_spouse_pps_rp_data" name="save_tab_spouse_pps_rp_data" value="Save">
</form>
</div>

<div class="example-box-wrapper details_box"  id="reports_box">
<h3> All Case PPS </h3>

<!--<div id="more_pps" style="display: none; float: left; margin-left: 180px; margin-top: -27px; margin-bottom: 10px;">

</div> -->

<table id="tab_all_case_pps_table_sp" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="tab_all_case_pps_tbody_sp">
	</tbody> 
</table>
</div>

<br>

<h3 style="color: black;">PPS Instructions</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box">
		<div class="box-comment" id="instruct_pps2"> 
			
			<div class="col-md-12">
				
					
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps"></textarea> -->
					
					<div class="col-md-10"> 
						<textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="tab_pps_spouse_instruct_note" readonly></textarea> 
					</div>
					
					<div class="col-md-2"> 
					<button class="btn btn-edit editppsinstruct" id="tab_editpps_spinstruct" title="Edit"  style="background-color: #48C6AC;" onclick="tab_edit_pps_spinstruction();">Edit</button>  
					<button class="btn btn-save saveppsinstruct" id="tab_savepps_spinstruct" title="Save"  style="display: none; background-color: #48C6AC;" onclick="tab_save_pps_spinstruction(this);">Save</button>  
					</div>
					
				
				</div>
			
		</div>
	</div>
</div>

<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="tab_spouse_pps_note_box">
		<div class="box-comment" id="tab_spouse_note_pps2"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="tab_add_note_pps_spouse" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps_spouse" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps_spouse"></textarea>
					<input class="form-control" type="hidden" id="pps_id_tab_spouse" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPSSpouseTab" class="btn btn-primary" onclick="tab_add_note_pps_spouse();"> Add Note</button>					
					</div>
				</form>
				<!--<p id="tab_note_pps_msg_spouse"></p> -->
				<div id="tab_pps_spouse_notes">
					
				</div>
				
			</div>
		</div>
	</div>
</div>