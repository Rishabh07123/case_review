<div class="example-box-wrapper details_box"  id="reports_box">

<table id="pps_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th style="text-align: center;">Tax Period</th> 
			<th style="text-align: center;">Balance</th>
			<th style="text-align: center;">Filing Status</th>	 
			<th style="text-align: center;">Account Status</th>
			<th style="text-align: center;">CSED</th>
			<!-- <th style="text-align: center;">Required/Original Recommended</th> 
			<th style="text-align: center;">FTA Eligible</th> Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul -->
			<th style="text-align: center;">Liens</th>
			<th style="text-align: center;">Reported Wages</th>
			<th style="text-align: center;">Reported SE</th>
			<th style="text-align: center;">Reported Other</th> 
			<th style="text-align: center;">Gross Monthly Income</th>
			<th style="text-align: center;">Taxes Withheld</th>	
			<!--  <th style="text-align: center;">Tin</th>	Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul	-->	
		</tr>
	</thead>
	<tbody class="pps_tbody">		
		
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
                    
                    
                        <textarea rows="7" cols="80" class="form-control pps_instruct_notes" id="pps_instruct_note" readonly style="margin-bottom: 10px;
						margin-top: 10px;"></textarea> 
                    
                    
                    <!--<div class="col-md-2"> 
                    <button class="btn btn-edit editppsinstruct" id="editppsinstruct" title="Edit"  style="background-color: #48C6AC;" onclick="acc_edit_pps_instruction();">Edit</button>  
                    <button class="btn btn-save saveppsinstruct" id="saveppsinstruct" title="Save"  style="display: none; background-color: #48C6AC;" onclick="acc_save_pps_instruction(this);">Save</button>  
                    </div> -->
                    
                
                </div>
            
        </div>
    </div>
</div>
<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box">
		<div class="box-comment" id="note_pps"> 
			<div class="col-md-12">
				
				<!--<form action="#" class="form-horizontal bordered-row" id="add_note_pps" method="post">
					<div class="col-md-10"> 
					
					<textarea rows="4" cols="80" class="form-control" placeholder="Add a note" id="note_txt_pps"></textarea>
					<input class="form-control" type="hidden" id="case_id" value="<?php //echo $case_id;?>">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPS" class="btn btn-primary" onclick="add_note_pps();"> Add Note</button>					
					</div>
				</form> --> 
				<p id="note_pps_msg"></p>
				<!--<div id="pps_notes">
					
				</div>--> 
				
			</div>
		</div>
	</div>
</div>