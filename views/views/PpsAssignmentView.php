<style>

.select2-container--classic .select2-selection--multiple .select2-selection__rendered {
				
	max-width: 100% !important;
	overflow: hidden !important;
	white-space: normal !important;
}

#page-content-wrapper ul {
    display: inline-block;
}
</style>

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>
	
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
<script type="text/javascript">
	
	/* Datatables export */
	$( function() {
		$( "#start_date" ).datepicker({
			dateFormat: 'mm-dd-yy',
			minDate: ''
		});
		$( "#end_date" ).datepicker({
			dateFormat: 'mm-dd-yy',
			minDate: ''
		});
	} );

	$(document).ready(function() {
		
		$('.select2').select2();
		
		var buttonCommon = {
			exportOptions: {
				format: {
					body: function ( data, row, column, node ) {
						// Strip $ from salary column to make it numeric
						return column === 5 ?
							data.replace( /[$,]/g, '' ) :
							data;
					}
				},
				columns: [1,2,3,4,5,6,7,8,9,10]
			}
		};
		
		$('table.pps_assign').DataTable( {
        
			dom: 'Blfrtip',
			lengthMenu: [
               [50, 25, 10, -1],
               ['50 rows', '25 rows', '10 rows', 'Show all']
            ],
			buttons: [
				$.extend( true, {}, buttonCommon, {
					extend: 'excelHtml5',
					className: 'btn btn-primary'
				} )
			]
		} );
	} );

	/* Datatables reorder */
	$(document).ready(function() {
		$('#datatable-reorder').DataTable( {
			dom: 'Rlfrtip'
		});

		$('#datatable-reorder_length').hide();
		$('#datatable-reorder_filter').hide();

	});
	
	$(document).ready(function() {
		$('.dataTables_filter input').attr("placeholder", "Search...");
	});
	
	$(document).ready(function() {
		
		$('#selectAllPPS').change(function(){
			
			if($(this).prop('checked')){
				
				$('#pps_assign_tbody').find('td input:checkbox').prop('checked', 'checked');
			}else{
				
				$('#pps_assign_tbody').find('td input:checkbox').prop('checked', false);
			}
		});
		
		renderSelect2();
	}); 
	
	function assignPPS(){
		
		var error = 0;		
		var case_array = [];
		var table=$('.pps_assign').DataTable();
		
		$(':checkbox:checked').each(function(i){
			
		  case_array[i] = $(this).val();
		});
		
		var assign_user_id = $('#selected_user_id_pps').val();
		
		if(case_array.length < 1){
			
			error = 1;
			alert('Please select a case.');
			return false;
		}
		if(assign_user_id == ''){
			
			error = 1;
			alert('Please select a user.');
			return false;
		}
		
		if(error == 0){
			
			swal('Assign selected pps to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					$('#mass_assign_to_btn_pps').attr("disabled", true);
					$("#reg_js_img").css('display','block');
					
					jQuery.ajax({
			
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/PpsAssignment/updatePPSAssignment",
						
						data: {assign_user_id: assign_user_id, case_array: case_array},
						success: function(response) 
						{	
							$('#selected_user_id_pps').val('');
							$('#selected_user_id_pps').trigger('change');
							
							response = JSON.parse(response);
							
							if(response.status == 'done'){
								
								$.jGrowl("PPS assignment updated successfully.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
								$("#reg_js_img").css('display','none');
								
								$.each( case_array, function( key, value ) {
						
									table.row("#"+value).remove().draw();
								});
								$('#mass_assign_to_btn_pps').attr("disabled", false);
								//location.reload();								
							}
							else{
								
								$.jGrowl("Unable to update pps assignment. Error - " + response , {
									sticky: false,
									position: 'top-right',
									theme: 'bg-red'
								}); 
								$("#reg_js_img").css('display','none');
							}
							$('#mass_assign_to_btn_pps').attr("disabled", false);
						}
					})
				}
			})
		}
	}
		
	//Filter Code
	function filterPPS(data){
		
		var select_lang = $('#language_filter_case :selected').text();
		var select_pay_status = $('#pay_status_filter_case :selected').text();
		   
		var folder = $('#folder_filter :selected').text();
		var stage = $('#inves_stage_filter :selected').text();
		var call_type = $('#call_type_filter :selected').text();
		var pps_type = $('#pps_type_filter :selected').text();
		var mstatus = $('#mstatus_filter').val();
		var fstatus = $('#fstatus_filter').val();
		var start_date = $('#start_date').datepicker('getDate'); //$('#start_date').val();
		start_date = $.datepicker.formatDate('yy-mm-dd', start_date);
		var end_date = $('#end_date').datepicker('getDate'); // $('#end_date').val();
		end_date = $.datepicker.formatDate('yy-mm-dd', end_date);
		
		//alert(start_date + ', ' + end_date ); //+ ', ' + call_type + ', ' +  pps_type);
		//return true;
		$("#load_img").css('display','block');		
		
		jQuery.ajax({
		
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/PpsAssignment/getFilteredPPSData",
			
			data: {language: select_lang, pay_status: select_pay_status, folder: folder, stage: stage, call_type: call_type, pps_type: pps_type, mstatus: mstatus, fstatus: fstatus, start_date: start_date, end_date: end_date}, //, team_id: team_id  user_ids: user_ids,
			success: function(response) 
			{	
				var table=$('.pps_assign').DataTable();

				if(response != ''){
					
					var tbody = '';
					var response_array = $.parseJSON(response);				
					var len = response_array.length;
					
					var table = $('.pps_assign').dataTable();
					table.fnClearTable();
					
					for (var i = 0; i < len; i++) {

						if( response_array[i]['transcripts_only_eligible_c']  == 1) 
						{
							var yes_no = 'Yes';
						}
						else{

							var yes_no = '';
						}
						
						var case_link = '<a href="<?php echo base_url();?>index.php/casedetail/records?id=' +  response_array[i]['case_id_c'] + '" target="_blank"><font color="8399E9">' +  response_array[i]['case_id_c'] + '</font></a> ';
						var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['pps_id'] + "' class='assignment_cases' value='" + response_array[i]['pps_id'] + "' class='assignment_cases'>", case_link, response_array[i]['client_name'], response_array[i]['case_user'],  response_array[i]['pps_type'], response_array[i]['pps_call_type_c'],  response_array[i]['state_c'], response_array[i]['investigation_advocate_statu_c'] , response_array[i]['investigation_type_c'], response_array[i]['language_prequal_c'],  response_array[i]['income_type_c'], response_array[i]['spouse_income_type_c'] , response_array[i]['username'], response_array[i]['billing_status_c'], response_array[i]['date_created'], yes_no, response_array[i]['logics_folder_c'], response_array[i]['marital_status_c'], response_array[i]['filing_status_state_c'], response_array[i]['split_pps_call_c']]);  
												
						var theNode = table.fnSettings().aoData[rowNode].nTr;
						theNode.setAttribute('id',response_array[i]['id']);
					}
					$("#load_img").css('display','none');
				}
				else
				{
					swal('No records found with following criteria.', {
						buttons: {
							cancel: true
						},
						icon: "warning"
					})
					$("#load_img").css('display','none');
				}
			}
		})
	}
	
	function renderSelect2(){
		//alert('renderSelect2')	
		//$('.js-example-basic-single').prop('disabled', true);
		$('.js-example-basic-single').select2();
		$(".js-example-basic-single").select2({
		  theme: "classic"
		});
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
	<div class="container" style="margin-top:20px;">
	
<div class="panel" id="my_cases_div" style="display: block;">
	<div class="panel-body">
	
		<div class="col-sm-12">
			<label class="control-label" style="font-size: medium;">PPS Assignment</label>
			<div id="load_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/spinner.gif" /></div>
		</div>
		<div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">
			<label class="control-label" style="padding-right: 40px; font-size: medium;">Filters</label>   
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Select Language</font>
			<select id="language_filter_case" class="form-control" style="width: 10%; margin-right: 40px;">
				<option value=""></option>
				<option value="English">English</option>
				<option value="Spanish">Spanish</option>
			</select>
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Payment Status</font>
			<select id="pay_status_filter_case" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Current">Current</option>
				<option value="Paid In Full">Paid In Full</option>
				<option value="Past Due">Past Due</option>
			</select>

			<font color="black" style="padding-right: 20px; padding-top: 3px;">PPS Type</font>
			<select id="pps_type_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Federal Personal">Federal Personal</option>
				<option value="State Personal">State Personal</option>
				<option value="Federal Business">Federal Business</option>
				<option value="State Business">State Business</option>
			</select>

			<font color="black" style="padding-right: 20px; padding-top: 3px;">Call Type</font>
			<select id="call_type_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Standard">Standard</option>
				<option value="Transcripts Only">Transcripts Only</option>
			</select>
		</div>
		
		<div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">

			<font color="black" style="padding-right: 20px; padding-top: 3px; margin-left: 85px;">Investigation Type</font>
			<select id="inves_stage_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Traditional">Traditional</option>
				<option value="Low Debt">Low Debt</option>
				<option value="Tax Relief Package">Tax Relief Package</option>
				<option value="Free Report">Free Report</option>
				<option value="State Rep">State Rep</option>
				<option value="Add Service">Add Service</option>
				<option value="Tax Prep Only">Tax Prep Only</option>
				<option value="No Cost">No Cost</option>
				<option value="Tax Prep plus Investigation">Tax Prep plus Investigation</option>
				<option value="Business Investigation">Business Investigation</option>
				<option value="Financial Privacy Plan">Financial Privacy Plan</option>
				<option value="Finance Pal">Finance Pal</option>
				<option value="Anthem">Anthem</option>
			</select>

			<font color="black" style="padding-right: 20px; padding-top: 3px; ">Logics Folder</font>
			<select id="folder_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="FREEZONE">FREEZONE</option>
				<option value="NON QUALIFIED">NON QUALIFIED</option>
				<option value="Terminated">Terminated</option>
				<option value="Settled">Settled</option>
				<option value="QUALIFIED">QUALIFIED</option>
				<option value="Negotiations">Negotiations</option>
				<option value="SCREENERS">SCREENERS</option>
				<option value="Analyst Call">Analyst Call</option>
				<option value="BAM">BAM</option>
				<option value="Activation">Activation</option>
				<option value="Compliance">Compliance</option>

				<option value="NEW Call: No Contact">NEW Call: No Contact</option>
				<option value="FreeIRSreport">FreeIRSreport</option>
				<option value="DOCS">DOCS</option>
				<option value="Investigation">Investigation</option>
				<option value="Monitoring">Monitoring</option>
				<option value="Tax Prep Only">Tax Prep Only</option>
				<option value="NEW WEB Lead: No Contact">NEW WEB Lead: No Contact</option>
				<option value="Suspended">Suspended</option>

				<option value="Billing Reset">Billing Reset</option>
				<option value="Accounting Prospect">Accounting Prospect</option>
				<option value="Paused">Paused</option>
				<option value="Accounting">Accounting</option>
				<option value="Tax Prep Seasonal (TPS)">Tax Prep Seasonal (TPS)</option>
				<option value="Pending Approval">Pending Approval</option>
			</select>
					
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Marital Status</font>
			<select id="mstatus_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="single">Single</option>
				<option value="married">Married</option>
				<option value="separated">Separated</option>
				<option value="divorced">Divorced</option>
			</select>

			<font color="black" style="padding-right: 20px; padding-top: 3px;">Filing Status</font>
			<select id="fstatus_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Single">S</option>
				<option value="Jointly">MFJ</option>
				<option value="Separately">MFS</option>
				<option value="HOH">HOH</option>
				<option value="QW">QW</option>
			</select>
			
		</div>
		
		<div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">

			<font color="black" style="padding-right: 20px; padding-top: 3px; margin-left: 85px; width: max-content;">Date Case Created:</font>
			
			<font color="black" style="padding-right: 20px; padding-top: 3px; ">Start Date</font>
			<input type='text' class="form-control"  name='start_date' id='start_date' style="width: 13%;" value="">

			<font color="black" style="padding-right: 20px; padding-top: 3px; margin-left: 55px;">End Date</font>
			<input type='text' class="form-control" name='end_date' id='end_date' style="width: 13%;" value="">
			

			

			<button type="button" id="filter_button" class="btn btn-primary" onclick="filterPPS(this);" style="margin-left: 20px;">Filter</button>
		</div>

		<div class="col-sm-12">
			<div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
			
			<?php if($pps_assignments == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered pps_assign" cellspacing="0" width="100%" style="display: block;
			overflow-x: auto;">
				<!-- style="float: left;  max-height: 400px; overflow-y: scroll;" -->
				<thead>
					<tr style="text-align: center">
						<th>Action <input type="checkbox" id="selectAllPPS" class="assignment_cases"/></th>
						<th>Case Id.</th>
						<th>Client Name</th>
						<th>Case Assigned To</th>
						<th>PPS Type</th> 
						<th>PPS Call Type</th> 
						<th>PPS State</th> 
						<th>Investigation Stage</th> 
						<th>Investigation Type</th>
						<th>Language</th>
						<th>Client Income Type</th>
						<th>Spouse Income Type</th>						
						<th>Assigned To</th>
						<th>Billing Status</th>
						<th>Date Created</th>
						<th>Transcripts Only Eligible</th>								
						<th>Logics Folder</th> 
						<th>Marital Status</th>
						<th>Filing Status</th>
						<th>Split PPS Call</th>
					</tr>
				</thead>
				<tbody id="pps_assign_tbody">
				
				<?php	
				foreach($pps_assignments as $val){ ?>
				
					<tr id="<?php echo $val->pps_id;?>" >
						<td><input type="checkbox" name="selector[]" id="<?php echo $val->pps_id;?>" class="assignment_cases" value="<?php echo $val->pps_id;?>" class="assignment_cases"></td>
						
						<!--<td><?php //echo $val->case_id_c;?></td> -->
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail" target="_blank"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td>
						
						<td><?php echo $val->client_name;?></td>
						<td><?php echo $val->case_user;?></td>
						<td><?php echo $val->pps_type;?></td>
						<td><?php echo $val->pps_call_type_c;?></td> 
						<td><?php echo $val->state_c;?></td> 
						<td><?php echo $val->investigation_advocate_statu_c; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->language_prequal_c; ?></td>
						<td><?php echo $val->income_type_c;?></td>
						<td><?php echo $val->spouse_income_type_c;?></td>
						<td><?php echo $val->username;?></td>
						<td><?php echo $val->billing_status_c; ?></td>
						<td><?php echo $val->date_created; ?></td>						
						<td><?php if($val->transcripts_only_eligible_c == 1) { echo "Yes"; }  //echo $val->transcripts_only_eligible_c; ?></td>
						<td><?php echo $val->logics_folder_c; ?></td>  
						<td><?php echo $val->marital_status_c; ?></td>	
						<td><?php echo $val->filing_status_state_c; ?></td>	
						<td><?php echo $val->split_pps_call_c; ?></td>
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
			
			
			<div id="assign_div">				
				
				<select class="form-control select2" id="selected_user_id_pps">
					<option></option>
					<?php	foreach($user_list as $val){ ?>
					
						<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
					<?php }?>
				</select>
				
				<button type="button" id="mass_assign_to_btn_pps" class="btn btn-primary" onclick="assignPPS();">Assign </button>
				
			</div>
			<?php } ?>	
		</div>
	
		
	</div>
	
	
		
</div>


		
</div>
</div>
</div>