<style>

#acc_services_table th{
	width: 23px !important;
	vertical-align: middle !important;
}
#acc_services_table td{
	width: 30px !important;
	vertical-align: middle !important;
}
</style>
<div class="example-box-wrapper details_box"  id="reports_box">

	<div class="row">
		
		<div class="col-md-4">			
			<h5><div class="col-md-8">Stated Income:</div><div class="col-md-4"> <p class="si" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Expenses:</div><div class="col-md-4"> <p class="se" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Stated Assets:</div><div class="col-md-4"> <p class="sa" style="text-align: center;"></p></div></h5> 
			<h5><div class="col-md-8"> Client THS Balance:</div><div class="col-md-4"> <p class="ths_bal"  style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Spouse THS Balance:</div><div class="col-md-4"> <p class="ths_spbal"  style="text-align: center;"></p></div></h5>
			<!--<h5><div class="col-md-8">Account Status:</div>
				<div class="col-md-4 ac_stat_div" id="ac_stat_div">
					<p class="ac_stat" style="text-align: center;"></p>
				</div>
			</h5> -->
			
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
					<p class="spec_service" style="text-align: center;"></p>
				</div>
			</h5>				
		</div> 
		<div class="col-md-4">			
			<h5><div class="col-md-8">Reported Income:</div><div class="col-md-4"> <p id="ri" style="text-align: center;"></p></div></h5>			
			<h5><div class="col-md-8"> Reported Expenses:</div><div class="col-md-4"><p class="re" style="text-align: center;"></p></div></h5>
			<h5><div class="col-md-8"> Reported Assets:</div><div class="col-md-4"> <p class="ra" style="text-align: center;"></p></div></h5>   
			<h5><div class="col-md-8"> PPS Balance:</div><div class="col-md-4"> <p class="cur_bal"  style="text-align: center;"></p></div></h5>
			<!--<h5><div class="col-md-7">Compliance Summary:</div> 
				<div class="col-md-5 comp_sum" id="comp_sum">
					<p class="compliance_sum"></p>
				</div>
			</h5> -->
			
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
					<p class="prelim_rec" style="text-align: center;"></p>
				</div>
			</h5>
			<h5><div class="col-md-4">Final Rec Type:</div>
				<div class="col-md-8" id="final_rec">
					<p class="final_rec" style="text-align: center;"></p>
				</div>
			</h5>
		</div> 
		<div class="col-md-4">			
			<h5><div class="col-md-8">Income Analysis Used:</div>
				<div class="col-md-4" id="ana1">
					<p class="analysis1" style="text-align: center;"></p>
				</div>
			</h5>
			<h5><div class="col-md-8">Expense Analysis Used:</div>
				<div class="col-md-4" id="ana2">
					<p class="analysis2" style="text-align: center;"></p>
				</div>
			</h5>
			<h5><div class="col-md-8">Asset Analysis Used:</div>
				<div class="col-md-4" id="ana3">
					<p class="analysis3" style="text-align: center;"></p>
				</div>
			</h5> 
			<div id="di_div" class="whitish_bg">
				<h5><div class="col-md-8"> Disposable Income::</div><div class="col-md-4"> <b>			
					<p class="di"  style="text-align: center;"></p> </b></div>
				</h5>
			</div>	
			<h5><div class="col-md-8">Account Status:</div>
				<div class="col-md-4 ac_stat_div" id="ac_stat_div">
					<p class="ac_stat" style="text-align: center;"></p>
				</div>
			</h5>
			<h5><div class="col-md-7">Compliance Summary:</div> 
				<div class="col-md-5 comp_sum" id="comp_sum">
					<p class="compliance_sum"></p>
				</div>
			</h5>
			<h5><div class="col-md-6"> Prelim Fee:</div>
				<div class="col-md-6"> <b>					
					<p class="prelim_fee"  style="text-align: center;"></p> </b>
				</b></div>
			</h5>
			
			<h5><div class="col-md-6"> CR Fee:</div>
				<div class="col-md-6"> <b>				
					<p class="input_cr_fee"  style="text-align: center;"></p> </b>
					
				</b></div>
			</h5>
			
		</div> 
	</div>
	
</div>

<br>

<h3 style="color: #000;margin-bottom: 10px; margin-top: 20px;">Services</h3>
<div class="row" id="acc_show_services_div" style="padding: 10px 10px;">
	<table id="acc_services_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%" style="display: block; overflow-x: auto;">
		<thead>
			<tr>
				<th>Sale Type</th> 
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
	
<br>
	
<h3 style="color: #000;margin-bottom: 10px; margin-top: 20px;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="review_box">
		<div class="box-comment" id="note"> 
			<div class="col-md-12">
				<h4 style="color: black;">Review Notes</h4>				
				<p id="note_msg2"></p>
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