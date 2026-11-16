<div class="example-box-wrapper details_box"  id="reports_box">
<form action="javascript:savePPSSugarDetails();">
<table id="pps_sugar_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
			<th>Action</th>
		</tr>
	</thead>
	<tbody class="pps_sugar_tbody">
		<tr id="2017">
			<td><input class="form-control1" type="text" name="year" value="2017" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="Single">Single</option>
					<option value="HOH">HOH</option>
					<option value="MFJ">MFJ</option>
					<option value="MFS">MFS</option>					
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>				
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="Defaulted IA">Defaulted IA</option>
					<option value="First Notice">First Notice</option>
					<option value="2nd NOITL">2nd NOITL</option>
					<option value="3rd FNOITL">3rd FNOITL</option>
					<option value="4th NOL">4th NOL</option>
					<option value="ACS">ACS</option>
					<option value="3rd Party">3rd Party</option>
					<option value="CNC">CNC</option>
					<option value="RO">RO</option>
					<option value="Exam">Exam</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>	
				<option value="No">No</option>
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				<option value="2">No</option>
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				<option value="2">No</option>
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
			
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row"  style="display: none;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		<tr id="2016">
			<td><input class="form-control1" type="text" name="year" value="2016" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="MFJ">MFJ</option>
					<option value="Single">Single</option>
					<option value="MFS">MFS</option>
					<option value="HOH">HOH</option>
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="CNC">CNC</option>
					<option value="Exam">Exam</option>
					<option value="RO">RO</option>
					<option value="NOITL">NOITL</option>
					<option value="FNOITL">FNOITL</option>
					<option value="NOL">NOL</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>				
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
			
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;"  style="display: none;" onClick="addNew(this);">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: none;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		<tr id="2015">
			<td><input class="form-control1" type="text" name="year" value="2015" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="MFJ">MFJ</option>
					<option value="Single">Single</option>
					<option value="MFS">MFS</option>
					<option value="HOH">HOH</option>
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="CNC">CNC</option>
					<option value="Exam">Exam</option>
					<option value="RO">RO</option>
					<option value="NOITL">NOITL</option>
					<option value="FNOITL">FNOITL</option>
					<option value="NOL">NOL</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>				
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
			
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: none;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		<tr id="2014">
			<td><input class="form-control1" type="text" name="year" value="2014" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="MFJ">MFJ</option>
					<option value="Single">Single</option>
					<option value="MFS">MFS</option>
					<option value="HOH">HOH</option>
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="CNC">CNC</option>
					<option value="Exam">Exam</option>
					<option value="RO">RO</option>
					<option value="NOITL">NOITL</option>
					<option value="FNOITL">FNOITL</option>
					<option value="NOL">NOL</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>				
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
			
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: none;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		<tr id="2013">
			<td><input class="form-control1" type="text" name="year" value="2013" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="MFJ">MFJ</option>
					<option value="Single">Single</option>
					<option value="MFS">MFS</option>
					<option value="HOH">HOH</option>
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="CNC">CNC</option>
					<option value="Exam">Exam</option>
					<option value="RO">RO</option>
					<option value="NOITL">NOITL</option>
					<option value="FNOITL">FNOITL</option>
					<option value="NOL">NOL</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>				
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
	
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: none;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		
		<tr id="2012">
			<td><input class="form-control1" type="text" name="year" value="2012" id="psyear" readonly></td> 
			<td><input class="form-control1" type="text" name="balance" value="" id="defaultbal"></td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="filingstat" id="filing_stat">
					<option value="0">Select</option>
					<option value="MFJ">MFJ</option>
					<option value="Single">Single</option>
					<option value="MFS">MFS</option>
					<option value="HOH">HOH</option>
					<option value="SFR">SFR</option>
					<option value="Unfiled">Unfiled</option>
				</select>
			</td>
			<td>
				<!-- <input type ="text" name="filingstat" value="" id="filing_stat"> -->
				<select class="form-control1" name="accountstat" id="account_stat">
					<option value="0">Select</option>
					<option value="IA">IA</option>
					<option value="CNC">CNC</option>
					<option value="Exam">Exam</option>
					<option value="RO">RO</option>
					<option value="NOITL">NOITL</option>
					<option value="FNOITL">FNOITL</option>
					<option value="NOL">NOL</option>
				</select>
			</td>
			<td><input class="form-control1" type="text" name="csed" value="" id="csedid"></td>
			<td>
			<select class="form-control1" name="ro_recommend" id="ro">
				<option value="0">Select</option>
				<option value="Required">Required</option>
				<option value="Original Recommended">Original Recommended</option>				
			</select>
			</td>
			<td>
			<select class="form-control1" name="fta_eligible" id="fta_eligible">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td>
			<select class="form-control1" name="liens" id="liens">
				<option value="0"></option>
				<option value="1">Yes</option>
				
			</select>
			</td>
			<td><input class="form-control1" type="text" name="rep_wage" value="" id="repinc"></td>
			<td><input class="form-control1" type="text" name="rep_se" value="" id="repse"></td>
			<td><input class="form-control1" type="text" name="rep_other" value="" id="rep_other"></td>
			
			<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>
			<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td>
			
			<td id="actionbtn">
			<!--<input type ="button" name="savedata" value="Save" onClick="savePPS(this)">
			<input type ="button" name="addnew" value="Add" onClick="addNew(this)"></td> onclick="cancelLCD();"
			
			
			<button class="btn btn-xs btn-success" id="save_pps" title="Save" onclick="savePPS(this);"><i class="glyph-icon icon-check"></i></button>-->
			
			<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNew(this);">  <i class="glyph-icon icon-plus"></i> </button>
			
			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> 
									
			</td>
		</tr>
		<tr id="total">
			<td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 
			<td colspan="13"><input class="form-control1" type="text" name="total_balance" value="" id="total_balance"></td>
		</tr>
	</tbody>
</table>
<input class="form-control" type="submit" id="save_pps_data" name="save_pps_data" value="Save">
</form>
</div>
<br>

<h3 style="color: black;">Notes</h3>
<div class="example-box-wrapper details_box">
	<div class="box-comments row" id="pps_note_box">
		<div class="box-comment" id="note_pps"> 
			<div class="col-md-12">
				
				<form action="#" class="form-horizontal bordered-row" id="add_note_pps" method="post">
					<div class="col-md-10"> 
					<!--<input class="form-control" placeholder="Add a note" type="text" id="note_txt_pps" required="required">-->
					<textarea rows="4" cols="80" class="form-control" placeholder="Add a note" id="note_txt_pps"></textarea>
					<input class="form-control" type="hidden" id="case_id" value="<?php //echo $case_id;?>">
					</div> 
					<div class="col-md-2"> 
					<button type="button" id="btnSavePPS" class="btn btn-primary" onclick="add_note_pps();"> Add Note</button>					
					</div>
				</form>
				<!--<p id="note_pps_msg"></p>--> 
				<div id="pps_notes">
					
				</div>
				
			</div>
		</div>
	</div>
</div>