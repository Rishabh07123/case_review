<div class="example-box-wrapper details_box"  id="reports_box">
<form action="javascript:saveTabSummaryDetails();">
	<div class="row">
		<input type="hidden" id="tab_inves_pps_id" value="">
		<input type="hidden" id="tab_aggregate_id" value="">
		<div class="col-md-4">
			<!-- <h5><div class="col-md-8">THS/PPS Wages:</div><div class="col-md-4"> <p class="wg" style="text-align: center;"></p></div></h5> -->
			<h5><div class="col-md-8">Stated Income:</div><div class="col-md-4"> <p class="si" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Expenses:</div><div class="col-md-4"> <p class="se" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Assets:</div><div class="col-md-4"> <p class="sa" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Client THS Balance:</div><div class="col-md-4"> <p class="ths_bal"  style="text-align: center;"></p></div></h5>
			
			<h5><div class="col-md-8 clientLabel">  Client Gross Income:</div><div class="col-md-4"> <p class="tot_stat_inc_cl_new"  style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8">Income Type ak:</div>
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
			<h5><div class="col-md-7">Specified Services:</div>
				<div class="col-md-5" id="tab_spec_service">
				</div>
			</h5>	
			<h5><div class="col-md-8"> Assigned To:</div><div class="col-md-4"> <p class="ass_to"  style="text-align: center;"></p></div></h5>
					<h5><div class="col-md-8"> Language:</div><div class="col-md-4"> <p class="lang"  style="text-align: center;"></p></div></h5>
				<h5><div class="col-md-8"> Stated Debt Amount:</div><div class="col-md-4"> <p class="stat_debt_amnt"  style="text-align: center;"></p></div></h5>											
		</div> 
		<div class="col-md-4">
			<!-- <h5><div class="col-md-8">THS/PPS SE:</div><div class="col-md-4"><p class="npc" style="text-align: center;"></p></div></h5> -->
			<h5><div class="col-md-8">Reported Income:</div><div class="col-md-4"> 
				<!-- <p class="vi" style="text-align: center;"></p> commented and added on 26-03-2024 -->
				<input class="form-control vi" type="text" name="vi" id="tab_reported_income"  value="">
			</div></h5>
			<h5><div class="col-md-8"> Reported Expenses:</div><div class="col-md-4"><p class="re" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Reported Assets:</div><div class="col-md-4"> 
				<!-- <p class="ra"  style="text-align: center;"></p> commented and added on 26-03-2024 -->
				<input class="form-control ra" type="text" name="ra" id="tab_reported_assets" value="">
			</div></h5>
			<h5><div class="col-md-8"> Spouse THS Balance:</div><div class="col-md-4"> <p class="ths_spbal"  style="text-align: center;"></p></div></h5>
			
			<h5><div class="col-md-8 spouseLabel"> Spouse Gross Income:</div><div class="col-md-4"> <p class="tot_stat_inc_sp"  style="text-align: center;"></p></div></h5>
			
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
			<h5><div class="col-md-8"> PPS Balance:</div><div class="col-md-4"> <p class="cur_bal"  style="text-align: center;"></p></div></h5>
			<h5>
				<div class="col-md-7">Additional Income Source 1:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ais1" style="text-align: center;"></p>   
				</div>
			</h5>
			<h5>
				<div class="col-md-7" style="margin-bottom: 10px;">Add. Inc. 1 Detail:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ai_amt1" style="text-align: center;"></p>   
					
				</div>
				<textarea rows="2" cols="30" class="form-control" id="ai_detail1" style="width: 95%; margin-left: 15px;" disabled=""></textarea>
			</h5>
  			<h5>
			  	<div class="col-md-7">Additional Income Source 2:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ais2" style="text-align: center;"></p>   
				</div>
			</h5>
			<h5>
				<div class="col-md-7" style="margin-bottom: 10px;">Add. Inc. 2 Detail:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ai_amt2" style="text-align: center;"></p>   
					
				</div>
				<textarea rows="2" cols="30" class="form-control" id="ai_detail2" style="width: 95%; margin-left: 15px;" disabled=""></textarea>
			</h5>
			
			<h5><div class="col-md-7">Prelim Rec Type:</div>
				<div class="col-md-5" id="tab_prelim_rec">
					
				</div>
			</h5>
			<h5><div class="col-md-8"> Total PPS Balance:</div><div class="col-md-4"> <p class="total_pps_bal"  style="text-align: center;"></p></div></h5>

			<h5><div class="col-md-7">Additional PPS Info Needed:</div>
				<div class="col-md-5" id="tab_add_pps_info">
					<!--<select class="form-select form-control" id="additional_pps_info" name="additional_pps_info">
						<option value="" selected></option>
						<option value="No">No</option>
						<option value="Request Completed">Request Completed</option>
						<option value="Request Incomplete">Request Incomplete</option>
					</select> -->

				</div>
			</h5>
			
			
			
		</div>
		<div class="col-md-4">
			<!-- <h5><div class="col-md-8">THS/PPS Other Income:</div><div class="col-md-4"><p class="oth"></p></div></h5> -->
			<h5 style="height: 50px;"><div class="col-md-7">Income Analysis Used:</div>
				<div class="col-md-5" id="tab_ana1">
					
				</div>
			</h5>
			<h5 style="height: 50px;"><div class="col-md-7">Expense Analysis Used:</div>
				<div class="col-md-5" id="tab_ana2">
					
				</div>
			</h5>
			<h5 style="height: 50px;"><div class="col-md-7">Asset Analysis Used:</div>
				<div class="col-md-5" id="tab_ana3">
					
				</div>
			</h5>		 
			<!--<div id="di_div" class="whitish_bg"> -->
				<h5 style="height: 50px;"><div class="col-md-5"> Disposable Income:</div><div class="col-md-7"> <b>				
				<!-- <p class="tab_di"  style="display: inline"></p> -->
				<input class="form-control tab_input_di" type="text" name="di" value="">
								
				
				</b></div></h5>
			<!-- </div> -->
			<h5 style="height: 50px;"><div class="col-md-5">Account Status:</div>
				<div class="col-md-7 tab_ac_stat_div" id="tab_ac_stat_div">
					 
				</div>
			</h5>
			<h5 style="height: 50px;"><div class="col-md-6">Compliance Summary:</div>
				<div class="col-md-6 tab_comp_sum" id="tab_comp_sum">
					
				</div>
			</h5>
			<h5 style="height: 50px;"><div class="col-md-5"> Prelim Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_prelim_fee" type="text" name="prelim_fee" value="">
				</b></div>
			</h5>
			<!--<div id="cr_fee_div" class="whitish_bg">-->
			<h5 style="height: 50px;"><div class="col-md-5"> CR Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_input_cr_fee" type="text" name="cr_fee" value=""> </b>
				</div>
			</h5>
			<!-- <h5 style="height: 50px;"><div class="col-md-5"> Anthem Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_input_anthem_fee" type="text" name="anthem_fee" value="" disabled> </b>
				</div>
			</h5> --> <!-- Commented on 23-11-23 https://trello.com/c/RVmA8RNV/691-tra-anthem-fee-field-name-updates -->

			<!-- Added on 23-11-23 https://trello.com/c/RVmA8RNV/691-tra-anthem-fee-field-name-updates -->
			<h5 style="height: 50px;"><div class="col-md-5"> Service Partner Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_input_service_partner_fee" type="text" name="service_partner_fee" value="" disabled> </b>
				</div>
			</h5>
			<!-- end added on 23-11-2023 -->
			<!-- Added on 23-11-23 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record -->
			<h5 style="height: 50px;"><div class="col-md-6">Add Service Source:</div>
				<div class="col-md-6 tab_service_src" id="tab_service_src">
					
				</div>
			</h5>
			<!-- end added on 23-11-2023 -->

			<h5 style="height: 50px;"><div class="col-md-5"> Add Service Fee:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_add_service_fee" type="text" name="tab_add_serv_fee" value=""> </b>
				</div>
			</h5>
			<h5><div class="col-md-7">Additional Income Source 3:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ais3" style="text-align: center;"></p>   
				</div>
			</h5>			
			
			<h5>
				<div class="col-md-7" style="margin-bottom: 10px;">Add. Inc. 3 Detail:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ai_amt3" style="text-align: center;"></p>   
					
				</div>
				<textarea rows="2" cols="30" class="form-control" id="ai_detail3" style="width: 95%; margin-left: 15px;" disabled=""></textarea>
			</h5>
			<h5><div class="col-md-7">Additional Income Source 4:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ais4" style="text-align: center;"></p>   
				</div>
			</h5>
			<h5>
				<div class="col-md-7" style="margin-bottom: 10px;">Add. Inc. 4 Detail:</div>
				<div class="col-md-5 tab_sp_len_emp_div">
					<p class="ai_amt4" style="text-align: center;"></p>   
					
				</div>
				<textarea rows="2" cols="30" class="form-control" id="ai_detail4" style="width: 95%; margin-left: 15px;" disabled=""></textarea>
			</h5>
			<h5><div class="col-md-7">Final Rec Type:</div>
				<div class="col-md-5" id="tab_final_rec">
					
				</div>
			</h5>
			<h5><div class="col-md-7">CR Template:</div>
				<div class="col-md-5" id="cr_template_div">					
				</div>
			</h5> <!-- Added on 27-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field -->
			<!--</div>-->
			<!--<div id="cr_recommend_div" class="whitish_bg">
				<h5><div class="col-md-5"> Recommendation:</div>
				<div class="col-md-7"> <b>
					<input class="form-control tab_input_cr_recmd" type="text" name="cr_recmd" value="">
				</b></div></h5>
			</div>	-->
			
			
		</div> 
	</div>
	<input class="form-control" type="submit" id="tab_save_summary" name="tab_save_summary" value="Save">
</form>
</div>

<br>
<button type="button" id="services_div" class="btn btn-primary" onclick="show_services_div();"> View Services</button>

 <div class="example-box-wrapper details_box" id="show_services_div" style="display: none; border: 1px solid #dfe8f1; padding: 15px;">
	
	<div class="row" style="padding: 0px 10px;">

		<button type="button" id="add_services" class="btn btn-primary" onclick="show_add_services_div();"> Add Services</button> 
		
		<button type="button" id="add_business" class="btn btn-primary" onclick="add_business();"> Add Empty Business</button> 
		
		<button type="button" class="btn btn-xs btn-danger" id="services_div_close" title="Close" onclick="close_services_div();" style="float: right;"><i class="glyph-icon icon-remove"></i></button>
		<p id="service_error_msg" style="color: red;"></p>
	</div>
	
	<div id="add_services_div">

		<div id="loader" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/saving.gif" alt="Saving ... "/></div>

		<div class="row">
			<div class="tab_services">
				<div class="record-label">Billed By</div>
				<div id="servicer_div">
					<select class="form-control services" id = "servicer" >
						<option value="CTax" selected>CTax</option>
						<option value="FPal">FPal</option>
						<option value="BAM">BAM</option>
						<option value="Monitoring">Monitoring</option>
						<option value="Anthem">Anthem</option>
					</select>
				</div>
			</div>
			<div class="tab_services">
				<div class="record-label">Billed For</div>
				<div id="entity_div">
					<select class="form-control services" id = "entity" >
					</select>
				</div>
			</div>
			<div class="tab_services">
				<div class="record-label">Billed To</div>
				<div id="billed_to_div">
					<select class="form-control services" id = "billed_to" >
					</select>
				</div>
			</div>
			<div class="tab_services">
				<div class="record-label">Sale Level</div>
				<div id="sale_type_div">
					<select class="form-control services" id = "sale_type" >
					</select>
				</div>
			</div>			
				
		<!--</div>
		
		<div class="row">-->

			<div class="tab_services">
				<div class="record-label">Service Type</div>
				<div id="service_type">
					<select class="form-control services" id = "services_type" >
					</select>
				</div>
			</div>

			<div class="tab_services">
				<div class="record-label">Service Name</div>
				<div id="service_name_div">
					<select class="form-control service_name" id = "service_name" >
						
					</select>
				</div>
			</div>
		
		<!--</div>
		<div class="row">-->
			
			<div class="tab_services" id="year_main_div" style="display: none;">
				<div class="record-label">Years</div>
				<div id="year_div">
					<select class="js-example-basic-single form-control" id="years" name="years[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			<div class="tab_services" id="rec_type_div" style="display: none;">
				<div class="record-label">Recurring Type</div>
				<div id="rec_type_div">
					<select class="form-control" id="rec_type" name="rec_type">
						<option value=""></option>
						<option value="annual">Annual</option>
						<option value="monthly">Monthly</option>
					</select>
				</div>
			</div>
			<div class="tab_services" id="service_date_main_div" style="display: none;">
				<div class="record-label">Service Start Date</div>
				<div id="service_date_div">
					<input type="date" id="service_date" class="form-control" value="">
				</div>
			</div>
			<div class="tab_services" id="quarter_div" style="display: none;">
				<div class="record-label">Period/Quarter</div>
				<div id="period_q_div">
					<!--<select class="form-control" id="quarter" name="quarter">						
					</select>-->
					<select class="js-example-basic-single form-control" id="quarter" name="quarter[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			<div class="tab_services" id="roll_in_div" style="display: none;">
				<div class="record-label">Reset/Roll-In Type</div>
				<div id="roll_div">
					<select class="form-control" id="reset_rollin" name="reset_rollin">						
					</select>
				</div>
			</div>
			<div class="tab_services" id="flag_div" style="display: none;">
				<div class="record-label">Service Flags</div>
				<div id="service_flag_div">
					<!--<select class="form-control" id="service_flag" name="service_flag">
						
					</select> -->
					<select class="js-example-basic-single form-control" id="service_flag" name="service_flag[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
		
			<div class="tab_services">
				<div class="record-label">Description</div>
				<div>
					<input class="form-control services" type="text" id="rli_description" value="">
			</div>
			</div>
			<div class="tab_services">
				<div class="record-label">List Price</div>
				<input class="form-control services" type="text" id="list-price" value="0">
			</div>
			<div class="tab_services">
				<div class="record-label">Quantity</div>
				<input class="form-control services" type="number" min="0" step="any" id="quantity" value="1">
			</div>
			<div class="tab_services">
				<div class="record-label">Discount Reason</div>
				<div id="disc_reason">
					<select class="form-control services" id="discount_reason">
					</select>
				</div>
			</div>
		<!--</div>
		<div class="row">	-->		
		
			<div class="tab_services">
				<div class="record-label">Discount Amount</div>
				<input class="form-control services" type="number" min="0" step="any" id="discount-amount">
			</div>		
			
			<div class="tab_services" id="state_main_div" style="display: none;">
				<div class="record-label">State</div>
				<div id="state_div">
					<select class="js-example-basic-single form-control" id="service_state" name="service_state[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
					</select>
				</div>
			</div>
			
			<div class="tab_services">
				<div class="record-label">Calculated Amount</div>
				<input class="form-control services" type="text" id="calculated-amount" readonly="readonly">
			</div>
			
			<div class="tab_services">
				<div class="record-label">Override Commission</div>
				<input class="form-control services" type="number" id="commission">
			</div>			
		</div>
		
		
		<div id="services_button" style="margin-top: 10px;">
			<button type="button" id="save_services" class="btn btn-primary" onclick="save_rli();"> Save</button>
			<button type="button" id="update_services" class="btn btn-primary" onclick="update_rli(this);"> Update</button>
			<button type="button" id="cancel_services" class="btn btn-primary" onclick="cancelServices();"> Cancel</button>
		</div>

		<div id="pkg_button_div" style="margin-top: 10px; display: none;">
			
			<button type="button" id="add_package" class="btn btn-primary" onclick="addPackage();"> Add Service</button>
			<!--<button type="button" id="add_child_service" class="btn btn-primary" style="display: none;" onclick="addNewChildService();"> Add Service</button>-->
		
		</div>

			<div class="example-box-wrapper details_box"  id="child_service_box" style="display: none; overflow-x: hidden;">

				<input type="hidden" id="package_parent_id" value="">

				<!-- <div id="add_child_services_div" style="margin-top: 20px; margin-bottom: 20px; margin-left: 10px; margin-right: 10px; border: 2px solid black; border-radius: 5px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; padding-top: 20px;">
				
					<div class="row">

						<div class="tab_services">
							<div class="record-label">Service Type</div>
							<div id="child_service_type">
								<select class="form-control services" id = "child_services_type" >
								</select>
							</div>
						</div>	
				
						<div class="tab_services">
							<div class="record-label">Service Name</div>
							<div id="child_service_name_div">
								<select class="form-control service_name" id = "child_service_name" >
									
								</select>
							</div>
						</div>

						
						<div class="tab_services" id="child_year_main_div" style="display: none;">
							<div class="record-label">Years</div>
							<div id="child_year_div">
								<select class="js-example-basic-single form-control" id="child_years" name="child_years[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
								</select>
							</div>
						</div>
						
						<div class="tab_services" id="child_quarter_div" style="display: none;">
							<div class="record-label">Period/Quarter</div>
							<div id="child_period_q_div">
								<select class="js-example-basic-single form-control" id="child_quarter" name="child_quarter[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
								</select>
							</div>
						</div>
						<div class="tab_services" id="child_roll_in_div" style="display: none;">
							<div class="record-label">Reset/Roll-In Type</div>
							<div id="child_roll_div">
								<select class="form-control" id="child_reset_rollin" name="child_reset_rollin">
									
								</select>
							</div>
						</div>
						<div class="tab_services" id="child_flag_div" style="display: none;">
							<div class="record-label">Service Flags</div>
							<div id="child_service_flag_div">
								<select class="js-example-basic-single form-control" id="child_service_flag" name="child_service_flag[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
								</select>
							</div>
						</div>

						<div class="tab_services" id="child_state_main_div" style="display: none;">
							<div class="record-label">State</div>
							<div id="child_state_div">
								<select class="js-example-basic-single form-control" id="child_service_state" name="child_service_state[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;">
								</select>
							</div>
						</div>
						<div class="tab_services">
                			<div class="record-label">Description</div>
                			<input class="form-control services" type="text" id="child_description" value="">
						</div>
						
						<div class="tab_services">
							<div class="record-label">List Price</div>
							<input class="form-control services" type="text" id="child-list-price" value="0">
						</div>
						
						<div class="tab_services">
							<div class="record-label">Quantity</div>
							<input class="form-control services" type="number" min="0" step="any" id="child-quantity" value="1">
						</div>
			
					</div>

					<div id="services_button" style="margin-top: 10px;">
						<button type="button" id="child_save_services" class="btn btn-primary" onclick="save_child_rli();"> Save</button>
						<button type="button" id="child_update_services" class="btn btn-primary" style="display:none;" onclick="update_child_rli(this);"> Update</button>
						<button type="button" id="child_cancel_services" class="btn btn-primary" onclick="cancelChildServices();"> Cancel</button>
					</div>
				</div> -->

				<div class="row" id="show_child_services_div" style="padding: 10px 10px;">
					<table id="child_services_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%" style="display: none;">
						<thead>
							<tr> 
								<th>Action</th> 
								<th>Service Type</th> 
								<th>Service Name</th>
								<th>Description</th> 
								<th>List Price</th>
								<th>Qty</th> 								
								<th>Years</th>
								<th>Quarters</th>									 
								<th>Reset/Roll-In Type</th> 
								<th>Service Flags</th> 
								<th>State</th> 								
							</tr>
						</thead>
						<tbody class="child_services_tbody">
						
						</tbody>
					</table>
				</div>

			</div>


		<!--</div> -->

		
	</div>	
	
	

	<div class="row" id="show_services_div_table" style="padding: 10px 10px;">
		<table id="services_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
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
			<tbody class="services_tbody">
			
			</tbody>
		</table>
	</div>
</div> 


<!-- <div class="col-md-10 lead_campaign_div">
	
</div> -->

<div>

</div>
<h3 style="margin-top: 20px; margin-bottom: 5px; padding-bottom: 10px;">Notes
<span id="lcampaign" style="color: red; margin-top: -20px; margin-left: 10%; margin-bottom: 5px;"></span></h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="review_box">
		<div class="box-comment" id="note"> 
			<div class="col-md-12">
				<h4>Review Notes</h4>
				<form action="#" class="form-horizontal bordered-row" id="add_note" method="post">
					<div class="col-md-10"> 
						<div class="col-md-2" style="padding: 0;  font-size: 16px;"> 
							Note Type
						</div>
						<div class="col-md-10"> 
							<select class="form-control"  id="tab_note_type" style="width: 135px; margin-bottom: 15px;">
								<option value="Case Review">Case Review</option>
								<option value="Add Service">Add Service</option>
								<option value="Assignment Note">Assignment Note</option>
								<option value="AI Generated Note">AI Generated Note</option>
							</select>
						</div>
					</div>
					<div class="col-md-2"> </div>					
					
					<div class="col-md-10"> 	
						<textarea rows="7" cols="50" class="form-control" placeholder="Add a note" id="note_txt"></textarea>
						<input class="form-control" type="hidden" id="case_id" value="<?php //echo $case_id;?>">
					</div> 
					<div class="col-md-2"> 
						<button type="button" id="btnSaveUser" class="btn btn-primary" onclick="add_note();"> Add Note</button>	
					</div>
				</form>
				
				
			</div>
			
			<div class="col-md-12">
				<h4 style="color: black;">Case Review Notes</h4>
				<div id="tabsumnote">
					
				</div>				
			</div>
			
			<div class="col-md-12">
				<h4 style="color: black;">Add Service Notes</h4>
				<div id="tabservicenote">
					
				</div>
			</div>

			<div class="col-md-12">
				<h4 style="color: black;">Assignment Notes</h4>
				<div id="tabassignmentnote">
					
				</div>
			</div>
			
			<div class="col-md-12">
				<h4 style="color: black;">AI Summary Notes</h4>
				<div id="tabainote">
					
				</div>
			</div>
			
			<div class="box-comment" id="note_finance_div_tab"> 
				<div class="col-md-12">
					<h4 style="color: black;">Finance Notes</h4>				
					<p id="note_finance_tab"></p>  
				</div>			
			</div>
		</div>
		
	</div> 
</div>