<div class="example-box-wrapper details_box"  id="reports_box">
<form action="javascript:saveExpenseDetails();">
<font style="color: red;">* Please hit save button at bottom after entering values</font>
	<div class="row">
		<div class="col-md-6"  id="general_exp_info_acc">			
			<!--<h5><div class="col-md-8">Persons in Household (Under 65):</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="under65" id="under65" value="">
			</div></h5>
			<h5><div class="col-md-8">Persons in Household (Over 65):</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="over65" id="over65" value="">
			</div></h5>
			<h5><div class="col-md-8">Filing Status:</div><div class="col-md-4"> 
				<p class="filing_stat" style="text-align: center;"></p>				
			</div></h5>	-->
		</div> 
		<div class="col-md-6">			
			<h5><div class="col-md-8">State:</div><div class="col-md-4"> <p class="state" style="text-align: center;"></p></div></h5>			
			
			<h5><div class="col-md-8">Client Birthday:</div><div class="col-md-4"> 
				<p class="client_birthday" style="text-align: center;"></p>				
			</div></h5>	
			<h5><div class="col-md-8">Spouse Birthday:</div><div class="col-md-4"> 
				<p class="sp_birthday" style="text-align: center;"></p>				
			</div></h5>	
			
			<h5><div class="col-md-5"> Food, Clothing, Misc:</div>
			<div class="col-md-3"> 
				<p class="food_cloth_misc" style="text-align: center;"></p>
			</div>
			<div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="food_cloth_misc" id="food_cloth_misc" value="" disabled>
			</div></h5>
		</div> 
	</div>
	<br>
	
	<div class="row">
		<div class="col-md-6">
			<!--<h5 style="text-align: center;"><b>Housing Expense</b></h5>-->
			<h5 style="text-align: center;"><b>Housing Expense</b></h5>
			<h5> <b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; IRS Standard     &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Actual</b></h5>
			
			<h5><div class="col-md-8">Mortgage Payment:</div><div class="col-md-4"> 
			<!--&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;  <p class="mortgage_payment" style="text-align: center;"></p>-->
				<input class="form-control expense_input" type="text" name="mortgage_payment" id="mortgage_payment" value="" disabled>
			</div></h5>
			<h5><div class="col-md-8">Rent Payment:</div><div class="col-md-4"> 
			<!--<p class="rent_pay" style="text-align: center;"></p>-->
				<input class="form-control expense_input" type="text" name="rent_pay" id="rent_pay" value="">
			</div></h5>
			<h5><div class="col-md-8">Utilities:</div><div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="utilities_input" id="utilities_input" value="">
			</div></h5>
			<h5><div class="col-md-8">Phone, Internet, Cable:</div><div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="pic_pay" id="pic_pay" value="">
			</div></h5>
			<h5><div class="col-md-5"> Stated Housing Expense:</div>
			<div class="col-md-3"> 
				<p class="tab_irs_stand" style="text-align: center;"></p>
			</div>
			<div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="stated_housing_exp" id="stated_housing_exp" value="" disabled>
			</div></h5>
			<!--<h5><div class="col-md-8"> IRS Housing Standard:</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="irs_stand" id="irs_stand" value="" disabled>
			</div></h5>-->
			<h5><div class="col-md-8"> Allowable Housing Expense:</div><div class="col-md-4"> 
			<!--<p class="housing_exp" style="text-align: center;"></p>-->
				<input class="form-control expense_input" type="text" name="housing_exp" id="housing_exp" value="" disabled>
			</div></h5>
		<!--</div> 
		<div class="col-md-6"> <br>-->
			
			<h5 style="text-align: center;"><b>Transportation Expense</b></h5>
			<h5> <b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; IRS Standard     &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Actual</b></h5>
			
			<!--
			<h5><div class="col-md-5">Public Transportation:</div>
			<div class="col-md-3"> 
				<p class="public_trans_irs" style="text-align: center;"></p>
			</div>
			<div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="public_trans" id="public_trans" value="" disabled>
			</div></h5> -->
			
			<h5><div class="col-md-8">No. of Vehicles:</div><div class="col-md-4"> 
			<!--<p class="num_vehicle" style="text-align: center;"></p>-->
				<input class="form-control expense_input" type="text" name="num_vehicle" id="num_vehicle" value="" disabled>
			</div>
			</h5>
			<h5><div class="col-md-5">Public Transportation Client:</div>
				<div class="col-md-3"> 
					<p class="irs_public_trans" style="text-align: center;"></p>
				</div>
			
				<div class="col-md-4"> 			
					<input class="form-control expense_input" type="text" name="pt_client" id="pt_client" value="">
				</div>
			</h5>
			<h5><div class="col-md-8">Public Transportation Spouse:</div>
				<div class="col-md-4"> 			
					<input class="form-control expense_input" type="text" name="pt_spouse" id="pt_spouse" value="">
				</div>
			</h5>
			<h5 id='vehicle1div'><div class="col-md-5">Auto Ownership 1:</div>
				<div class="col-md-3"> 
					<p class="auto_own_irs" style="text-align: center;"></p>
				</div>
				<div class="col-md-4"> 			
					<input class="form-control expense_input" type="text" name="other_vehicle_exp1" id="other_vehicle_exp1" value="">
				</div>
			</h5>
			<h5 id='vehicle2div'><div class="col-md-5">Auto Ownership 2:</div>
				<div class="col-md-3"> 
					<p class="auto_own_irs" style="text-align: center;"></p>
				</div>
				<div class="col-md-4"> 			
					<input class="form-control expense_input" type="text" name="other_vehicle_exp2" id="other_vehicle_exp2" value="">
				</div>
			</h5>
			<h5><div class="col-md-5">Auto Expense/Insurance:</div>
				<div class="col-md-3"> 
					<p class="auto_ei_irs" style="text-align: center;"></p>
				</div>
				<div class="col-md-4"> 			
					<input class="form-control expense_input" type="text" name="auto_exp_ins" id="auto_exp_ins" value="" disabled>
				</div>
			</h5>
			<h5><div class="col-md-5"> Total Auto Expenses:</div>
			<div class="col-md-3"> 
				<p class="tot_auto_exp_irs" style="text-align: center;"></p>
			</div>
			<div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="tot_auto_exp" id="tot_auto_exp" value="" disabled>
			</div></h5>									
		</div>
		
		<div class="col-md-6">   
			<h5 style="text-align: center;"><b>Health Care</b></h5>
			<h5> <b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; IRS Standard     &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Actual</b></h5>
			<h5><div class="col-md-5">Prescription & Copays:</div>
			<div class="col-md-3"> 
				<p class="prescp_copay_irs" style="text-align: center;"></p>
			</div>
			<div class="col-md-3">
				<input class="form-control expense_input" type="text" name="prescp_copay" id="prescp_copay" value="">
			</div></h5>
			<h5><div class="col-md-8">Health Insurance:</div>
			<!--<div class="col-md-3"> 
				<p class="health_ins_irs" style="text-align: center;"></p>
			</div> -->
			<div class="col-md-4">
				<input class="form-control expense_input" type="text" name="health_ins" id="health_ins" value="">
			</div></h5>
			
			<h5><div class="col-md-5"> Total Health Care Expense:</div>
			<div class="col-md-3"> 
				<p class="total_health_allowable" style="text-align: center;"></p>
			</div>
			<div class="col-md-4"> 
				<input class="form-control expense_input" type="text" name="total_health" id="total_health" value="" disabled>
			</div></h5>									
		</div>
		
		<div class="col-md-6" id='other_add_expense'>
			
		
			<!-- <h5><div class="col-md-8">Taxes:</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="taxes" id="taxes" value="">
			</div></h5> 
			<h5><div class="col-md-8">Court Ordered Payments:</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="court_ordered" id="court_ordered" value="">
			</div></h5>
			<h5><div class="col-md-8"> Child/Dependent Care:</div><div class="col-md-4"> 
			
				<input class="form-control expense_input" type="text" name="child_care" id="child_care" value="">
			</div></h5>
			<h5><div class="col-md-8"> Life Insurance Policy (Whole + Term):</div><div class="col-md-4">
				<input class="form-control expense_input" type="text" name="whole_life_ins" id="whole_life_ins" value="">
			</div></h5>
			
			<div id="div_other_expense1" class="whitish_bg">
				<h5><div class="col-md-6"> <p class="other_exp1"></p> </div>
				<div class="col-md-6"> 				
				<input class="form-control input_oth_exp1" type="text" name="input_oth_exp1" value="" >
				</div></h5>
			</div>
			
			<div id="div_other_expense2" class="whitish_bg">
				<h5><div class="col-md-6"> <p class="other_exp2"></p> </div>
				<div class="col-md-6"> 				
				
				<input class="form-control input_oth_exp2" type="text" name="input_oth_exp2" value="" >
				
				</div></h5>
			</div>
			
			<div id="div_other_expense3" class="whitish_bg">
				<h5><div class="col-md-6"> <p class="other_exp3"></p> </div>
				<div class="col-md-6"> 
				
				
				<input class="form-control input_oth_exp3" type="text" name="input_oth_exp3" value="" >
				
				</div></h5>
			</div>
			
			<div id="div_other_expense4" class="whitish_bg">
				<h5><div class="col-md-6"> <p class="other_exp4"></p> </div>
				<div class="col-md-6"> 
				<input class="form-control input_oth_exp4" type="text" name="input_oth_exp4" value="" >
				</div></h5>
			</div>
			
			<h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4"> <p class="total_exp"  style="text-align: center;"></p></div></h5> -->
		</div>
		<div class="col-md-6" id='tab_total_expense'>
			<h5 style="text-align: center;"><b>Total Expenses</b></h5> 
			<h5>
				<div class="col-md-8">Total Expenses:</div>
				<div class="col-md-4 tot_expensenew"></div>
			</h5>
		</div>
		
		<div class="col-md-6"  style="float: right;">			
			<h5 style="width: 50%; float: left;">			
				<p style="padding-bottom:10px; padding-left: 15px;">Household Notes</p>
				<textarea rows="5" cols="50" class="form-control" id="si_personal" style="width: 95%; margin-left: 15px;" disabled></textarea>				
			</h5>
			<h5 style="width: 50%; float: right; border-top: 1px solid #cfe3f0; background: #f9f9f9;">			
				<p style="padding-bottom:10px; padding-left: 15px;">Income Notes</p> 
				<textarea rows="5" cols="50" class="form-control" id="si_income" style="width: 95%; margin-left: 15px;" disabled></textarea>			
			</h5>					
		</div>
		
	</div>
	<input class="form-control save_expenses_id" type="submit" id="save_expenses" name="save_expenses" value="Save">
	
</form>
</div>