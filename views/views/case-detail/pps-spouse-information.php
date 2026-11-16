<div class="example-box-wrapper details_box"  id="reports_box">
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium;">
<form action="javascript:saveSpousePPSDetails();">
<table id="spouse_pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="spouse_pps_tbody">
		
	</tbody>
</table>
<input class="form-control" type="submit" id="save_spouse_pps_data" name="save_spouse_pps_data" value="Save">
</form>
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
						<textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="pps_spouse_instruct_note" readonly></textarea> 
					</div>
					
					<div class="col-md-2"> 
					<button class="btn btn-edit editppsinstruct" id="editpps_spinstruct" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_spinstruction();">Edit</button>  
					<button class="btn btn-save saveppsinstruct" id="savepps_spinstruct" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_spinstruction(this);">Save</button>  
					</div>
					
				
				</div>
			
		</div>
	</div>
</div>

<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="spouse_pps_note_box">
		<div class="box-comment" id="spouse_note_pps"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="add_note_spouse_pps" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="note_txt_pps_spouse" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="note_txt_pps_spouse"></textarea>
					<input class="form-control" type="hidden" id="pps_id_spouse" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSaveSpousePPS" class="btn btn-primary" onclick="add_note_pps_spouse();"> Add Note</button>					
					</div>
				</form>
				<!--<p id="note_spouse_pps_msg"></p>-->
				<div id="pps_spouse_notes">
					
				</div>
				
			</div>
		</div>
	</div>
</div>