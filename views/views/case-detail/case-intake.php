<div class="example-box-wrapper details_box"  id="reports_box">
<form>
	<div class="row">
		<div class="col-md-6">			
			<h5>
				<div class="col-md-8">Federal Debt Amount:</div>
				<div class="col-md-4"> <p class="fed_debt" style="text-align: center;"></p> </div>
			</h5>
			<h5>
				<div class="col-md-5">Missing Personal Tax Returns:</div>
				<div class="col-md-7"> <p class="missing_tax_years" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<p style="padding-bottom:10px;">Federal Intake Notes</p>
				<textarea rows="4" cols="50" class="form-control fed_note" id="fed_note" disabled></textarea>				
			</h5>
		</div> 
		
		<div class="col-md-6">			
			<h5>
				<div class="col-md-8">State Debt Amount:</div>
				<div class="col-md-4"> <p class="state_debt" style="text-align: center;"></p> </div>
			</h5>
			<h5>
				<div class="col-md-8"> Business Tax Case:</div>
				<div class="col-md-4"> <p class="bix_tax_case" style="text-align: center;"></p></div>
			</h5>
			<h5>
				<p style="padding-bottom:10px; padding-left: 15px;">State Intake Notes </p>
				<textarea rows="4" cols="50" class="form-control state_note" id="state_note" style="width: 95%; margin-left: 15px;" disabled></textarea>				
			</h5>
		</div> 
	</div>
	<br>
	
	<div class="row">
		
		<div class="col-md-6">						
			<h5>
				<div class="col-md-12" style="text-align:center;"><b>Federal Case Issues</b></div>				
			</h5>
		
			<h5>
				<div class="col-md-8">Federal Wage/SSI/1099 Garnishment:</div>
				<div class="col-md-4"> <p class="fed_garnishment" style="text-align: center;"></p> </div>
			</h5>
			<h5>
				<div class="col-md-8">Federal Bank Levy:</div>
				<div class="col-md-4"> <p class="fed_levy" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">Federal Audit Issues:</div>
				<div class="col-md-4"> <p class="fed_audit_issue" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">Passport Issues:</div>
				<div class="col-md-4"> <p class="passport_issue" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">Immigration/Citizenship:</div>
				<div class="col-md-4"> <p class="immigration" style="text-align: center;"> </p> </div>
			</h5>
		</div>
		
		<div class="col-md-6">						
			<h5>
				<div class="col-md-12" style="text-align:center;"><b>State Case Issues</b></div>				
			</h5>
			<h5>
				<div class="col-md-8">State Wage/1099 Garnishment:</div>
				<div class="col-md-4"> <p class="state_garnishment" style="text-align: center;"></p> </div>
			</h5>
			
			<h5>
				<div class="col-md-8"> State Bank Levy:</div>
				<div class="col-md-4"> <p class="state_levy" style="text-align: center;"></p></div>
			</h5>
			
			<h5>
				<div class="col-md-8">State Audit Issues:</div>
				<div class="col-md-4"> <p class="state_audit_issue" style="text-align: center;"> </p> </div>
			</h5>
			
			<h5>
				<div class="col-md-8">State Driver's License/Tag Issues:</div>
				<div class="col-md-4"> <p class="st_driver_lic_issue" style="text-align: center;"> </p> </div>
			</h5>
				
			<h5>
				<div class="col-md-8">State Professional License Issues:</div>
				<div class="col-md-4"> <p class="st_prof_lic_issue" style="text-align: center;"> </p> </div>
			</h5>			
		</div> 
	</div>
	<br>
	
	<div class="row">	
		
		<div class="col-md-6"> <!--style="padding-top: 45px;"-->	
			
			<h5>
				<div class="col-md-12" style="text-align:center;">
					<b>Revenue Officer & Deadline Information Federal</b> 
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_editrevenue_federal"  title="Edit"  style="background-color: #48C6AC;" onclick="acc_edit_revenue_federal();"><i class="glyph-icon icon-pencil" style="color: #000;"></i></a> 
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_saverevenue_federal"  title="Save"  style="background-color: #2cb31c; display: none; color: #333;     font-size: 12px; padding: 3px 13px 22px; font-weight: 600;" onclick="acc_save_revenue_federal();">Save</a>
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_cancelrevenue_federal"  title="Cancel"  style="background-color: #2cb31c; display: none; color: #333;     font-size: 12px; padding: 3px 13px 22px; font-weight: 600;" onclick="acc_cancel_revenue_federal();">Cancel</a>	
				</div>				
			</h5>
			<h5>
				<div class="col-md-8">Federal Deadline Reason:</div>
				<div class="col-md-4"> 
					<!--<p class="fed_dead_reason" style="text-align: center;"></p>-->
					<select class="form-control acc_revenue_federal" id = "acc_federal_deadline_reason" disabled>
						<option value=""></option>
						<option value="RO">RO</option>
						<option value="Levy">Levy</option>
						<option value="Audit">Audit</option>
						<option value="Immigration">Immigration</option>
						<option value="Passport">Passport</option>
						<option value="Professional License Suspension">Professional License Suspension</option>
						<option value="Security Clearance Suspension">Security Clearance Suspension</option>
						<option value="Other">Other</option>
					</select>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">Federal Deadline Date:</div>
				<div class="col-md-4"> 
					<!--<p class="fed_dead_date" style="text-align: center;"> </p> -->
					<input class="form-control acc_revenue_federal" type="text" name="acc_federal_deadline_date" id="acc_federal_deadline_date" value="" disabled>
				</div>
			</h5>
			
			<!--<h5>
				<div class="col-md-8">Federal RO:</div>
				<div class="col-md-4"> 
					<p class="fed_ro" style="text-align: center;"> </p>
					<input class="form-control revenue_federal" type="text" name="federal_ro" id="federal_ro" value="" disabled>
				</div>
			</h5> -->
			
			<h5>
				<div class="col-md-8">Federal RO Name:</div>
				<div class="col-md-4"> 
					<!--<p class="fed_ro_name" style="text-align: center;"> </p> -->
					<input class="form-control acc_revenue_federal" type="text" name="acc_federal_ro_name" id="acc_federal_ro_name" value="" disabled>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">Federal RO Phone:</div>
				<div class="col-md-4"> 
					<!--<p class="fed_ro_phone" style="text-align: center;"> </p>-->
					<input class="form-control acc_revenue_federal" type="text" name="acc_federal_ro_phone" id="acc_federal_ro_phone" value="" disabled>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">Federal RO Fax:</div>
				<div class="col-md-4"> 
					<!--<p class="fed_ro_fax" style="text-align: center;"> </p>-->
					<input class="form-control acc_revenue_federal" type="text" name="acc_federal_ro_fax" id="acc_federal_ro_fax" value="" disabled>
				</div>
			</h5>
		</div>
		
		<div class="col-md-6">
			<h5>
				<div class="col-md-12" style="text-align:center;"><b>Revenue Officer & Deadline Information State</b> 
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_editrevenue_state"  title="Edit"  style="background-color: #48C6AC;" onclick="acc_edit_revenue_state();"><i class="glyph-icon icon-pencil" style="color: #000;"></i></a> 
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_saverevenue_state"  title="Save"  style="background-color: #2cb31c; display: none; color: #333;     font-size: 12px; padding: 3px 13px 22px; font-weight: 600;" onclick="acc_save_revenue_state();">Save</a>
					&nbsp;&nbsp;&nbsp;<a class="btn btn-xs btn-edit acc_cancelrevenue_state"  title="Cancel"  style="background-color: #2cb31c; display: none; color: #333;     font-size: 12px; padding: 3px 13px 22px; font-weight: 600;" onclick="acc_cancel_revenue_state();">Cancel</a>					
				</div>				
			</h5>
			
			<h5>
				<div class="col-md-8"> State Deadline Reason:</div>
				<div class="col-md-4"> 
					<!--<p class="state_dead_reason" style="text-align: center;"></p>-->
					<select class="form-control acc_revenue_state" id = "acc_state_deadline_reason" disabled>
						<option value=""></option>
						<option value="RO">RO</option>						
						<option value="Levy">Levy</option>
						<option value="Audit">Audit</option>
						<option value="Immigration">Immigration</option>
						<option value="Passport">Passport</option>
						<option value="Professional License Suspension">Professional License Suspension</option>
						<option value="Security Clearance Suspension">Security Clearance Suspension</option>
						<option value="Other">Other</option>
					</select>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">State Deadline Dates:</div>
				<div class="col-md-4"> 
					<!--<p class="state_dead_date" style="text-align: center;"> </p> -->
					<input class="form-control acc_revenue_state" type="text" name="acc_state_deadline_date" id="acc_state_deadline_date" value="" disabled>
				</div>
			</h5>
			
			<!--<h5>
				<div class="col-md-8">State RO:</div>
				<div class="col-md-4"> 
					
					<input class="form-control revenue_state" type="text" name="state_ro" id="state_ro" value="" disabled>
				</div> 
			</h5>-->
			
			<h5>
				<div class="col-md-8">State RO Name:</div>
				<div class="col-md-4"> 
					<!--<p class="state_ro_name" style="text-align: center;"> </p> -->
					<input class="form-control acc_revenue_state" type="text" name="acc_state_ro_name" id="acc_state_ro_name" value="" disabled>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">State RO Phone:</div>
				<div class="col-md-4"> 
					<!--<p class="state_ro_phone" style="text-align: center;"> </p> -->
					<input class="form-control acc_revenue_state" type="text" name="acc_state_ro_phone" id="acc_state_ro_phone" value="" disabled>
				</div>
			</h5>
			
			<h5>
				<div class="col-md-8">State RO Fax:</div>
				<div class="col-md-4"> 
					<!--<p class="state_ro_fax" style="text-align: center;"> </p>-->
					<input class="form-control acc_revenue_state" type="text" name="acc_state_ro_fax" id="acc_state_ro_fax" value="" disabled>
				</div>
			</h5>
		</div> 
	</div>
<br>

	<div class="row">
		<div class="col-md-6"> <!--style="padding-top: 45px;"-->	
			<h5>
				<div class="col-md-12" style="text-align:center;"><b>Lead Survey</b></div>				
			</h5>
			<h5>
				<div class="col-md-8">FR survey Reason:</div>
				<div class="col-md-4"> <p class="fr_survey_reason" style="text-align: center;"></p> </div>
			</h5>
			<h5>
				<div class="col-md-8">FR Survey IncomeType:</div>
				<div class="col-md-4"> <p class="fr_urvey_incometype" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">FR Survey TaxProHelp:</div>
				<div class="col-md-4"> <p class="fr_survey_taxprohelp" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">FR Survey Complete:</div>
				<div class="col-md-4"> <p class="fr_survey_complete" style="text-align: center;"> </p> </div>
			</h5>
		</div> 

		<div class="col-md-6"> <!--style="padding-top: 45px;"-->	
			<h5>
				<div class="col-md-12" style="text-align:center;"><b>Lead Survey</b></div>				
			</h5>
			<h5>
				<div class="col-md-8">FR Survey Unfiled:</div>
				<div class="col-md-4"> <p class="fr_survey_unfiled" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">FR Survey IncomeRange:</div>
				<div class="col-md-4"> <p class="fr_survey_incomerange" style="text-align: center;"> </p> </div>
			</h5>
			<h5>
				<div class="col-md-8">FR Survey AddNotes:</div>
				<div class="col-md-4"> <p class="fr_survey_addnotes" style="text-align: center;"> </p> </div>
			</h5>
		</div>
		
	</div>	
	
	
</form>
</div>