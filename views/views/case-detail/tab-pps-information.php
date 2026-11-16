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
<form action="javascript:ppsInfo();" id="pps-info-form">
	<div class="row">
		<!-- Added on 18-04-2024  https://trello.com/c/YsTzk5k6/1215-cr-portal-add-required-tax-status-field-in-pps-tab -->
		<div class="col-md-3">
			<table id="pps-completed" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>PPS Completed Using</b></td>
						<td>
							<select class="form-control" id="pps-completed-using" style="width:auto" required>
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
						<td id="ssn-tin"></td>
					<tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<table id="filling-status" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td><b>Filing Status</b></td>
						<td id="marital-status"></td>
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
							<select class="form-control" id="cross-reference" style="width:auto" required>
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
							<select class="form-control" id="civil-penalties" style="width:auto" required>
								
							</select>
							<div class='civil-penalties-error' style="display:none; color:red;">
								Please select Civil Penalties
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Shared Responsibility</b></td>
						<td>
							<select class="form-control" id="shared-responsibility" style="width:auto" required>
								
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
							<select class="form-control" id="passport-cretification" style="width:auto" required>
								
							</select>						
							<div class='passport-error' style="display:none; color:red;">
								Please select Passport Certification Year
							</div>
						</td>
					<tr>
					<tr>
						<td><b>Passport Certification Years<b></td>
						<td>
							<select id="passport-cretification-year" class="form-control" multiple>

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
							<select class="form-control" id="isssue_by_POA"  style="width:auto" required>
								
							</select>
							<div class='poa-error' style="display:none; color:red;">
								Please select POA is covered or not.
							</div>
						</td>
					<tr>
					<tr>
						<td><b>FTA Eligible<b></td>
						<td>
							<select id="FTA-eligible-year" class="form-control" multiple>
								
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<input class="form-control" type="submit" id="save_tab_pps_info_data" name="save_tab_pps_info_data" value="Save">
</form>
</div>
<h3>PPS Details</h3>
<!-- end added on 07-10-2022 -->
<!-- <div class="example-box-wrapper details_box"  id="reports_box"> Commented on 07-10-2022 -->
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium; margin-right: 180px;">
<h3 style="margin-left:2%"><b>1040</b></h3>  <!-- added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
<form action="javascript:saveTabPPSDetails();">

<table id="tab_pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="tab_pps_tbody">
	</tbody>
</table>
<!-- <input class="form-control" type="submit" id="save_tab_pps_data" name="save_tab_pps_data" value="Save">
</form> Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
<!-- </div>-->

<!--  Addeed on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul  -->
<p>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="margin-bottom:20px;">
   Income Information
  </button>
</p>
<div class="collapse" id="collapseExample">
	<!-- <form action="javascript:saveIncomeInfoDetails();"> -->
	<table id="income_info_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
		<tbody class="income_info_tbody">
		</tbody>
	</table>
</div>
<input class="form-control" type="submit" id="save_tab_pps_details_data" name="save_tab_pps_details_data" value="Save">
</form>
<div class="example-box-wrapper details_box civil_penalties"  id="reports_box" style="display:none">
	<h3> Civil Penalties </h3>
	<form action="javascript:saveCivilPenaltiesDetails();">
	<table id="civil_penalties_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_pps_cp_data" name="save_tab_pps_cp_data" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box shared_responsibility"  id="reports_box" style="display:none">
	<h3>Shared Responsibility </h3>
	<form action="javascript:saveSharedResponsibilityDetails();">
	<table id="shared_responsibility_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_pps_sr_data" name="save_tab_pps_sr_data" value="Save">
</form>
</div>
<div class="example-box-wrapper details_box resolution_present_div"  id="reports_box" style="display:none">
	<h3>Resolution Present </h3>
	<form action="javascript:saveResolutionPresentDetails();">
	<table id="resolution_present_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<input class="form-control" type="submit" id="save_tab_pps_rp_data" name="save_tab_pps_rp_data" value="Save">
</form>
</div>
<!-- end added on 07-10-2022 -->

<div class="example-box-wrapper details_box"  id="reports_box">
<h3> All Case PPS </h3>

<!--<div id="more_pps" style="display: none; float: left; margin-left: 180px; margin-top: -27px; margin-bottom: 10px;">

</div> -->

<table id="tab_all_case_pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="tab_all_case_pps_tbody">
	</tbody> 
</table>
</div>
<br>

<!-- Appointment Start //added on 23-06-2022 https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah -->
<button type="button" id="appointment_list" class="btn btn-primary" onclick="myAppointmentdiv();"> My Appointment</button>
<div class="panel panel-default servc_module_tabs showDivAppointment" style="display: none;">
	<!-- <div class="panel-heading" role="tab" id="headingRes">
		<h4 class="panel-title">
		
		</h4>
	</div> -->
	<div id="" >
		<div class="panel-body" style="color:black">
		
			<div class="example-box-wrapper details_box"  id="appointment_listing">

			<div class="row" style="padding: 0px 10px 15px 10px;" id="add-appointment-button-div">
				<button class="btn btn-primary appointment" onclick="create_appointment()">Create Appointment</button>
				<button type="button" class="btn btn-xs btn-danger" id="appointment_div_close" title="Close" onclick="appointment_close_div();" style="float: right;"><i class="glyph-icon icon-remove"></i></button>
			</div>

				<table id="" class="table table-striped table-bordered " cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Name</th> 
							<th>Status</th>
							<th>Appointment Start Date</th>
							<th>Action</th>
							<!--<th>Assign User</th> -->
						</tr>
					</thead>
					<tbody id="tab_assignment_list_tbody">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Appointment End -->

<h3 style="color: black;">PPS Instructions</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box">
		<div class="box-comment" id="instruct_pps2"> 
			
			<div class="col-md-12">
				
					
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps"></textarea> -->
					
					<div class="col-md-10"> 
						<textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="tab_pps_instruct_note" readonly></textarea> 
					</div>
					
					<div class="col-md-2"> 
					<button class="btn btn-edit editppsinstruct" id="tab_editppsinstruct" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_instruction();">Edit</button>  
					<button class="btn btn-save saveppsinstruct" id="tab_saveppsinstruct" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_instruction(this);">Save</button>  
					</div>
					
				
				</div>
			
		</div>
	</div>
</div>

<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box">
		<div class="box-comment" id="note_pps2"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="tab_add_note_pps" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps"></textarea>
					<input class="form-control" type="hidden" id="pps_id_tab" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPSNote" class="btn btn-primary" onclick="tab_add_note_pps();"> Add Note</button>					
					</div>
				</form>
				<!-- <p id="tab_note_pps_msg"></p> -->
				<div id="tab_pps_notes">
					
				</div>
			</div>
		</div>
	</div>
</div>

<!--Create Appointment-->
<div class="modal" tabindex="-1" id="myModalAppointment" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Schedule</h4>
        </div>
        <div class="modal-body row" id="getAssign">
		<form role="form" id="appointmentForm" >
          <div class='col-md-12 space_bottom'>
		  	<input type='text' id='editMedalId' style='display:none'> <!-- added on 07-07-2022 -->
			  <div class='col-md-3'><label>Start Date</label><input type="text" placeholder='mm-dd-yyyy'  id="startDate" class="form-control dateFormatChange" /></div>
			  <div class='col-md-2'><label>&nbsp;</label>
			  
			  <select class="form-control" id="startTime">
			  <option>--:-- --</option>
			  <?php
				  $arrSecond = array('00','15','30','45');
				  $arrHour = array('12','01','02','03','04','05','06','07','08','09','10','11','12','01','02','03','04','05','06','07','08','09','10','11');
				   
				 	foreach($arrHour as $kee => $hours)
					{
						if($kee<12)  
						{
							$eve_morn = 'am';
						}
						else{
							$eve_morn = 'pm';
						}

						foreach($arrSecond as $second)
						{
				  ?>
				<option value="<?php echo $hours.':'.$second.' '.$eve_morn; ?>"><?php echo $hours.':'.$second.' '.$eve_morn; ?></option>
				<?php } } ?>
			  </select>
			</div>
			  <div class='col-md-3'><label>End Date</label><input type="text" placeholder='mm-dd-yyyy' id="endDate" class="form-control dateFormatChange" /></div>
			  <div class='col-md-2'><label>&nbsp;</label>
			  <select class="form-control" id="endTime">
			  <option>--:-- --</option>
			  <?php
				  $arrSecond = array('00','15','30','45');
				  $arrHour = array('12','01','02','03','04','05','06','07','08','09','10','11','12','01','02','03','04','05','06','07','08','09','10','11');
				   
				 	foreach($arrHour as $kee => $hours)
					{
						if($kee<12)  
						{
							$eve_morn = 'am';
						}
						else{
							$eve_morn = 'pm';
						}

						foreach($arrSecond as $second)
						{
				  ?>
				<option value="<?php echo $hours.':'.$second.' '.$eve_morn; ?>"><?php echo $hours.':'.$second.' '.$eve_morn; ?></option>
				<?php } } ?>
				</select>
			</div>
			<div class='col-md-2'>
				  	<label>Repeat Type</label>
			
					<select class="form-control" id="sel_repeat_type" onchange="change_div()">
						<option value="">None</option>
						<option value="Daily">Daily</option>
						<option value="Weekly">Weekly</option>
						<option value="Monthly">Monthly</option>
						<option value="Yearly">Yearly</option>
					</select>
				</div>
		  </div>

		  	<div class='col-md-12 space_bottom' style="margin-top: 13px;">
				

				<div class='col-md-3 showDiv'>
				  	<label>Repeat Interval</label>
			
					<select class="form-control select2" id="sel_repeat_interval">
						<?php for($no=1;$no<100;$no++){ ?>
						<option value="<?php echo $no; ?>"><?php echo $no; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class='col-md-3 showWeekDiv weekDiv'> <!-- updated on 07-04-2022 -->
				  	<label>Repeat on Days of Week</label>
					<select class="form-control selectpicker" id="sel_repeat_dow" multiple="multiple" data-live-search="true">
						<option value="0" <?php if(date('w')==0){ echo "selected"; } ?>>Sun</option>
						<option value="1" <?php if(date('w')==1){ echo "selected"; } ?>>Mon</option>
						<option value="2" <?php if(date('w')==2){ echo "selected"; } ?>>Tue</option>
						<option value="3" <?php if(date('w')==3){ echo "selected"; } ?>>Wed</option>
						<option value="4" <?php if(date('w')==4){ echo "selected"; } ?>>Thu</option>
						<option value="5" <?php if(date('w')==5){ echo "selected"; } ?>>Fri</option>
						<option value="6" <?php if(date('w')==6){ echo "selected"; } ?>>Sat</option>
						
					</select>

				</div>
				<div class='col-md-3 showDiv'>
					<label>Repeat Until</label>
					<input type="text" placeholder='mm-dd-yyyy' id="repeat_until" class="form-control dateFormatChange"/>
				</div>
				
				<div class='col-md-3 showDiv'>
					<label>Repeat Occurrences</label>
					<input type="number" id="repeat_occurance" class="form-control" value='10'/>
				</div>
		  	</div>
			  <div class='col-md-12 space_bottom' style="margin-top: 13px;">
			  <div class='col-md-6'>
				  	<label>Popup Reminder Time</label>
			
			      	<select class="form-control" id="popup_repeat_type">
					  	<?php foreach($reminder_time as $kee=>$time){ ?>
							<option value="<?php echo $kee; ?>"><?php echo $time; ?></option>
						<?php } ?>
				  	</select>
				</div>
			  	<div class='col-md-6'><label>Email Reminder Time</label>
			  		<select class="form-control" id="email_reminder_time">
					  	<?php foreach($reminder_time as $ekee=>$etime){ ?>
							<option value="<?php echo $ekee; ?>"><?php echo $etime; ?></option>
						<?php } ?>
				  	</select>
				</div>
			  </div>

			<div class='col-md-12 space_bottom' style="margin-top: 13px;">
				<div class='col-md-12'>
					<label>Description</label>
					<textarea class="form-control col-md-12" id="description" placeholder=""></textarea></div>
			</div>
			<div class='col-md-12 space_bottom' style="margin-top: 13px;">
				<div class='col-md-4'>
				  	<label>Assign To</label>
			
					<select  id="assign_to"  class="form-control" style="width: 100%;">
						<option>Select</option>
						<?php foreach($user_list as $users){ ?>
							<option value="<?php echo $users->id; ?>"><?php echo $users->uname; ?></option>
						<?php } ?>
					</select>
				</div>
			  	<div class='col-md-4'>
				  	<label>TimeZone</label>
			
			      	<select class="form-control" id="timezone" style="width: 100%;">
						<option>Select</option>
						<?php 
						foreach($time_zone as $kee=>$value)
						{
						?>
						<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
					  <?php } ?>
				  	</select>
				</div>

				<!-- Added on 21-07-2022 -->
				<div class='col-md-4 statusDiv' style='display:none;'>
					<label>Status</label>
			
					<select class="form-control" id="schedule_status" style="width: 100%;">
						<option value='Scheduled' >Scheduled</option>
						<option value='Completed' >Completed</option>
						<option value='Cancelled' >Cancelled</option>
						
					</select>
				</div>
				<!-- end -->
			</div>
			
			
			<!--<div class='col-md-12 space_bottom'>
				<div class='col-md-4'>
					<label>Home Phone</label>
					<input type='text' id='home_phone' class='form-control phone_us' maxlength="12" />
				</div>
				<div class='col-md-4'>
					<label>Mobile Phone</label>
					<input type='text' id='mobile_phone' class='form-control phone_us' maxlength="12"/>
				</div>
				<div class='col-md-4'>
					<label>Work Phone</label>
					<input type='text' id='work_phone' class='form-control phone_us' maxlength="12"/>
				</div>
			</div>-->
			</form>
        </div> 
        <div class="modal-footer">
			<div class="row" style= "margin-right:20px;margin-top:-10px">
            <button type="button" onclick="saveAssign();" class="btn btn-primary" >Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" >Cancel</button>
			</div>
        </div>
      </div>
    </div>
</div>

<!--<script type = 'text/javascript' src = "<?php //echo base_url();?>assets/js/case-detail/assignment.js"></script> -->