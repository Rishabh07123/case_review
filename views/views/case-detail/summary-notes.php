<div class="example-box-wrapper details_box"  id="reports_box">
<form action="javascript:saveSummaryDetails();">
	<div class="row">
	<input type="hidden" id="inves_pps_id" value="">
	<input type="hidden" id="aggregate_id" value="">	
		<div class="col-md-4"> 
			<!-- <h5><div class="col-md-8">THS/PPS Wages:</div><div class="col-md-4"> <p class="wg" style="text-align: center;"></p></div></h5> -->
			<h5><div class="col-md-8">Stated Income:</div><div class="col-md-4"> <p class="si" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Expenses:</div><div class="col-md-4"> <p class="se" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Assets:</div><div class="col-md-4"> <p class="sa" style="text-align: center;"></p></div></h5> 
			<h5><div class="col-md-8"> Client THS Balance:</div><div class="col-md-4"> <p class="ths_bal"  style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Spouse THS Balance:</div><div class="col-md-4"> <p class="ths_spbal"  style="text-align: center;"></p></div></h5>
			
			<h5><div class="col-md-8">Income Type:</div>
				<div class="col-md-4 tab_income_type_div">
					<p class="income_type_client" style="text-align: center;"></p>
				</div>
			</h5>
			<h5><div class="col-md-7">Name of Current Employer:</div>
				<div class="col-md-5 tab_current_emp_div">
					<p class="cur_emp_client" style="text-align: center;"></p> 
				</div>
			</h5>
			<h5><div class="col-md-7">Length at Current Employer:</div>
				<div class="col-md-5 tab_len_emp_div">
					<p class="len_cur_emp_client" style="text-align: center;"></p>  
				</div>
			</h5>			
			<h5><div class="col-md-7">Specified Servicess:</div>
				<div class="col-md-5" id="spec_service">
					
				</div>
			</h5>														
		</div> 
		<div class="col-md-4">
			<!-- <h5><div class="col-md-8">THS/PPS SE:</div><div class="col-md-4"><p class="npc" style="text-align: center;"></p></div></h5> 
			<h5><div class="col-md-8">Verified Income:</div><div class="col-md-4"> <p class="vi" style="text-align: center;"></p></div></h5> -->
			<h5><div class="col-md-8">Reported Income:</div><div class="col-md-4"> <p id="ri" style="text-align: center;"></p></div></h5>			
			<h5><div class="col-md-8"> Reported Expenses:</div><div class="col-md-4"><p class="re" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Reported Assets:</div><div class="col-md-4"> <p class="ra" style="text-align: center;"></p></div></h5>   
			<h5><div class="col-md-8"> PPS Balance:</div><div class="col-md-4"> <p class="cur_bal"  style="text-align: center;"></p></div></h5>
			
			<h5><div class="col-md-8">Spouse Income Type:</div>
				<div class="col-md-4 tab_sp_income_type_div">
					<p class="income_type_sp" style="text-align: center;"></p>
				</div>
			</h5>
			<h5 class="emp_name"><div class="col-md-7">Name of Current Employer Spouse:</div>
				<div class="col-md-5 tab_sp_current_emp_div">
					<p class="cur_emp_sp" style="text-align: center;"></p>  
				</div>
			</h5>
			<h5><div class="col-md-7">Length at Current Employer Spouse:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="len_cur_emp_sp" style="text-align: center;"></p>   
				</div>
			</h5>
			<h5><div class="col-md-7">Prelim Rec Type:</div>
				<div class="col-md-5" id="prelim_rec">
					
				</div>
			</h5>
			<h5><div class="col-md-7">Final Rec Type:</div>
				<div class="col-md-5" id="final_rec">
					
				</div>
			</h5>
		</div> 
		<div class="col-md-4">
			<!-- <h5><div class="col-md-8">THS/PPS Other Income:</div><div class="col-md-4"><p class="oth" style="text-align: center;"></p></div></h5> -->
			<h5><div class="col-md-7">Income Analysis Used:</div>
				<div class="col-md-5" id="ana1">
					
				</div>
			</h5>
			<h5><div class="col-md-7">Expense Analysis Used:</div>
				<div class="col-md-5" id="ana2">
					
				</div>
			</h5>
			<h5><div class="col-md-7">Asset Analysis Used:</div>
				<div class="col-md-5" id="ana3">
					
				</div>
			</h5> 
			<div id="di_div" class="whitish_bg">
				<h5><div class="col-md-6"> Disposable Income:</div><div class="col-md-6"> <b>				
				<!-- <p class="di"  style="display: inline"></p> -->
				<input class="form-control input_di" type="text" name="di" value="">
				
				</b></div></h5>
			</div>	
			<h5><div class="col-md-5">Account Status:</div>
				<div class="col-md-7 ac_stat_div" id="ac_stat_div">
					
				</div>
			</h5>
			<h5><div class="col-md-6">Compliance Summary:</div> 
				<div class="col-md-6 comp_sum" id="comp_sum">
					
				</div>
			</h5>
			
			<h5><div class="col-md-5"> Prelim Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control prelim_fee" type="text" name="prelim_fee" value="">
				</b></div>
			</h5>
			
			<!--<div id="cr_fee_div" class="whitish_bg">-->
				<h5><div class="col-md-6"> CR Fee:</div><div class="col-md-6"> <b>				
				
					<input class="form-control input_cr_fee" type="text" name="cr_fee" value="">
				
				</b></div></h5>
				
				<h5><div class="col-md-5"> Add Service Fee:</div>
					<div class="col-md-7"> <b>
						<input class="form-control add_service_fee" type="text" name="add_serv_fee" value=""> </b>
					</div>
				</h5>
				
			<!--</div>	-->
			
			<!--<div id="cr_recmd_div" class="whitish_bg">
				<h5><div class="col-md-6"> Recommendation:</div><div class="col-md-6"> <b>				
				
					<input class="form-control input_cr_recmd" type="text" name="cr_recmd" value="">
				
				</b></div></h5>
			</div>	-->
			
			
		</div> 
	</div>
	<input class="form-control" type="submit" id="save_summary" name="save_summary" value="Save">
</form>
</div>

<br>
<!--<button type="button" id="acc_services_div" class="btn btn-primary" onclick="acc_show_services_div();"> View Services</button> -->

<div class="example-box-wrapper details_box" id="acc_show_services_div" style="display: none; border: 1px solid #dfe8f1; padding: 15px;">
	
	<div class="row" style="padding: 0px 10px;">
		<button type="button" id="acc_add_services" class="btn btn-primary" onclick="acc_show_add_services_div();"> Add Services</button>
		<!--<button type="button" id="services_div_close" class="btn btn-primary" onclick="close_services_div();" style="float: right;"> Close</button> -->
		<button type="button" class="btn btn-xs btn-danger" id="acc_services_div_close" title="Close" onclick="acc_close_services_div();" style="float: right;"><i class="glyph-icon icon-remove"></i></button>
	</div>
	
	<div id="acc_add_services_div">
	
		<div class="row">
			<div class="col-md-3">
				<div class="record-label"  style="color: #000;">Servicer</div>
				<div id="acc_servicer_div">
					<select class="form-control services" id = "acc_servicer" >
						<option value="CTax" selected>CTax</option>
						<option value="FPal">FPal</option>
						<option value="BAM">BAM</option>
						<option value="Monitoring">Monitoring</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="record-label"  style="color: #000;">Entity</div>
				<div id="acc_entity_div">
					<select class="form-control services" id = "acc_entity" >
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Sale Level</div>
				<div id="acc_sale_type_div">
					<select class="form-control services" id = "acc_sale_type" >
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Service Type</div>
				<div id="acc_service_type">
					<select class="form-control services" id = "acc_services_type" >
					</select>
				</div>
			</div>	
		</div>
		
		<div class="row">
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Service Name</div>
				<div id="acc_service_name_div">
					<select class="form-control service_name" id = "acc_service_name" >
						
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Description</div>
				<input class="form-control services" type="text" id="acc_description" value="">
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">List Price</div>
				<input class="form-control services" type="text" id="acc_list-price" value="">
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Quantity</div>
				<input class="form-control services" type="number" min="0" step="any" id="acc_quantity">
			</div>
		</div>	
		<div class="row">
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Discount Reason</div>
				<div id="acc_disc_reason">
					<select class="form-control services" id="acc_discount_reason">
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Discount Amount</div>
				<input class="form-control services" type="number" min="0" step="any" id="acc_discount-amount">
			</div>
			<div class="col-md-3">
				<div class="record-label" style="color: #000;">Calculated Amount</div>
				<input class="form-control services" type="text" id="acc_calculated-amount" readonly="readonly">
			</div>
			<div class="col-md-3">
				<div class="record-label"  style="color: #000;">Billed To</div>
				<div id="acc_billed_to_div">
					<select class="form-control services" id = "acc_billed_to" >
					</select>
				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="record-label"  style="color: #000;">Override Commission</div>
				<input class="form-control services" type="number" id="acc_commission">
			</div>
			<div class="col-md-3" id="acc_year_main_div" style="display: none;">
				<div class="record-label" style="color: #000;">Years</div>
				<div id="acc_year_div">
					<select class="js-example-basic-single form-control" id="acc_years" name="acc_years[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			<div class="col-md-3" id="acc_rec_type_div" style="display: none;">
				<div class="record-label"  style="color: #000;">Recurring Type</div>
				<div id="acc_rec_type_div">
					<select class="form-control" id="acc_rec_type" name="acc_rec_type">
						<option value="annual">Annual</option>
						<option value="monthly">Monthly</option>
					</select>
				</div>
			</div>
			<div class="col-md-3" id="acc_service_date_main_div" style="display: none;">
				<div class="record-label"  style="color: #000;">Service Start Date</div>
				<div id="acc_service_date_div">
					<input type="date" id="acc_service_date" class="form-control" value="">
				</div>
			</div>
			<div class="col-md-3" id="acc_quarter_div" style="display: none;">
				<div class="record-label"  style="color: #000;">Period/Quarter</div>
				<div id="acc_period_q_div">
					
					<select class="js-example-basic-single form-control" id="acc_quarter" name="acc_quarter[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			<div class="col-md-3" id="acc_roll_in_div" style="display: none;">
				<div class="record-label" style="color: #000;">Reset/Roll-In Type</div>
				<div id="acc_roll_div">
					<select class="form-control" id="acc_reset_rollin" name="acc_reset_rollin">
						
					</select>
				</div>
			</div>
			<div class="col-md-3" id="acc_flag_div" style="display: none;">
				<div class="record-label"  style="color: #000;">Service Flags</div>
				<div id="acc_service_flag_div">
					
					<select class="js-example-basic-single form-control" id="acc_service_flag" name="acc_service_flag[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			
		</div>
		<div id="acc_services_button" style="margin-top: 10px;">
			<button type="button" id="acc_save_services" class="btn btn-primary" onclick="acc_save_rli();"> Save</button>
			<button type="button" id="acc_update_services" class="btn btn-primary" onclick="acc_update_rli(this);"> Update</button>
			<button type="button" id="acc_cancel_services" class="btn btn-primary" onclick="acc_cancelServices();"> Cancel</button>
		</div>
	</div>	
	
	<div class="row" id="acc_show_services_div" style="padding: 10px 10px;">
		<table id="acc_services_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Action</th> 
					<th>Sale Type</th> 
					<th>Related To</th> 
					<th>Service Name</th>
					<th>Qty</th>	 
					<th>List Price</th>
					<th>Discount Reason</th>
					<th>Discount Amt</th> 
					<th>Calc Amount</th>
					<th>Created By</th>
					<th>Created Date</th>
					<th>Referred By</th>
					<th>Sold By</th> 
					<th>Approved By</th>
					<th>Approved Date</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody class="acc_services_tbody">
			
			</tbody>
		</table>
	</div>
</div> 

<h3 style="color: #000;margin-bottom: 10px; margin-top: 20px;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="review_box">
		<div class="box-comment" id="note"> 
			<div class="col-md-12">
				<h4 style="color: black;">Review Notes</h4>
				<form action="#" class="form-horizontal bordered-row" id="add_note" method="post">
					<div class="col-md-10"> 
						<div class="col-md-2" style="padding: 0;  font-size: 16px; color: #000"> 
							Note Type
						</div>
						<div class="col-md-10"> 
							<select class="form-control"  id="note_type" style="width: 135px; margin-bottom: 15px;">
								<option value="Case Review">Case Review</option>
								<option value="Add Service">Add Service</option>
							</select>
						</div>
					</div>
					<div class="col-md-2"> </div>	
					
					<div class="col-md-10"> 
						<textarea rows="7" cols="50" class="form-control" placeholder="Add a note" id="note_txt2"></textarea>
					
						<input class="form-control" type="hidden" id="case_id" value="<?php //echo $case_id;?>">
					</div> 
					<div class="col-md-2"> 
						<button type="button" id="btnSaveUserAcc" class="btn btn-primary" onclick="add_note_accordion();"> Add Note</button>					
					</div>
				</form>
				
				<div class="col-md-12">
					<h4 style="color: black;">Case Review Notes</h4>
					<div id="example">
						
					</div>
				</div>
				
				<div class="col-md-12">
					<h4 style="color: black;">Add Service Notes</h4>
					<div id="servicenote">
						
					</div>
				</div>
				
			</div>
			<div class="box-comment" id="note_finance_div">  
				<div class="col-md-12">
					<h4 style="color: black;">Finance Notes</h4>				
					<p id="note_finance"></p>
				</div>			
			</div>
		</div>
	</div> 
</div>