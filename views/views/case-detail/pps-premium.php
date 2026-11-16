<div class="example-box-wrapper details_box"  id="reports_premium_box">
<p class="ssn_itin_alert" style="text-align: center; color: red; font-size: medium;">
<form action="javascript:savePPSPremiumDetails();">

<table id="pps_premium_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="pps_premium_tbody">
		
	</tbody>
</table>
<input class="form-control" type="submit" id="save_pps_premium_data" style="width: 6%;background: #2cb31c;color: #fff;margin-left: 50%;margin-top: 15px;" name="save_pps_premium_data" value="Save">
</form>
</div>


<br>
<div class="example-box-wrapper details_box"  id="reports_premium_box">
<h3> All Case PPS </h3>
<table id="tab_all_case_pps_premium_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
	<tbody class="all_case_pps_premium_tbody">
	</tbody> 
</table>
</div>
<br>
 
<h3 style="color: black;">PPS Instructions</h3>
<div class="example-box-wrapper details_box">
    <div class="box-comments row" id="pps_premium_note_box">
        <div class="box-comment" id="instruct_pps_premium"> 
            
            <div class="col-md-12">
                
                    
                    <!--<input class="form-control" placeholder="Add a note" type="text" id="tab_note_txt_pps" required="required">
                    <textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="tab_note_txt_pps"></textarea> -->
                    
                    <div class="col-md-10"> 
                        <textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="pps_instruct_note_premium" readonly></textarea> 
                    </div>
                    
                    <div class="col-md-2"> 
                    <button class="btn btn-edit editppsinstruct" id="editppsinstruct" title="Edit"  style="background-color: #48C6AC;" onclick="acc_edit_pps_instruction_premium();">Edit</button>  
                    <button class="btn btn-save saveppsinstruct" id="saveppsinstruct_premium" title="Save"  style="display: none; background-color: #48C6AC;" onclick="acc_save_pps_instruction_premium(this);">Save</button>  
                    </div>
                    
                
                </div>
            
        </div>
    </div>
</div>
<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_premium_note_box">
		<div class="box-comment" id="note_pps_premium"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="add_note_pps_premium" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="note_txt_pps" required="required">-->
					<textarea rows="7" cols="80" class="form-control" placeholder="Add a note" id="note_txt_pps_premium"></textarea>
					<input class="form-control" type="hidden" id="pps_premium_id" value="">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPSPremium" class="btn btn-primary" onclick="add_note_pps_premium();"> Add Note</button>					
					</div>
				</form>
				<!--<p id="note_pps_msg"></p>--> 
				<div id="pps_premium_notes">
					
				</div>
				
			</div>
		</div>
	</div>
</div>