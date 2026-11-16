<style>

.select2-container--classic .select2-selection--multiple .select2-selection__rendered {
				
	max-width: 100% !important;
	overflow: hidden !important;
	white-space: normal !important;
}
#assign_to_filter_case{
	width: 255px !important;
	overflow: hidden !important;
    margin-right: 20px !important;
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
				columns: [1,2,3,4,5,6,7,8,9,10,11]
			}
		};
		
		$('table.case_assign').DataTable( {
        
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
		
		$('#selectAllWrite').change(function(){
			
			if($(this).prop('checked')){
				
				$('#write_assign_tbody').find('td input:checkbox').prop('checked', 'checked');
			}else{
				
				$('#write_assign_tbody').find('td input:checkbox').prop('checked', false);
			}
		});
		
		renderSelect2();
	}); 
	
	//Code from WritingAssignment
	function assign_cases_to(){
		
		var error = 0;		
		var case_array = [];
		var table=$('.case_assign').DataTable();
		
		$(':checkbox:checked').each(function(i){
			
		  case_array[i] = $(this).val();
		});
		
		var assign_user_id = $('#selected_user_id').val();
		
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
			
			swal('Assign selected cases to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					$('#mass_assign_to_btn').attr("disabled", true);
					$("#reg_js_img").css('display','block');
					
					jQuery.ajax({
			
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/writingassignment/update_case_assignment",
						
						data: {assign_user_id: assign_user_id, case_array: case_array},
						success: function(response) 
						{	
							$('#selected_user_id').val('');
							$('#selected_user_id').trigger('change');
							
							response = JSON.parse(response);
							
							if(response.status == 'done'){
								
								$.jGrowl("Case assignment updated successfully.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
								$("#reg_js_img").css('display','none');
								
								$.each( case_array, function( key, value ) {
						
									table.row("#"+value).remove().draw();
								});
								$('#mass_assign_to_btn').attr("disabled", false);
								//location.reload();								
							}
							else{
								
								$.jGrowl("Unable to update case assignment", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-red'
								}); 
								$("#reg_js_img").css('display','none');
							}
							$('#mass_assign_to_btn').attr("disabled", false);
						}
					})
				}
			})
		}
	}
		
	//Filter Code
	function get_filtered_data_case(data){
		
		var select_lang = $('#language_filter_case :selected').text();
		var select_pay_status = $('#pay_status_filter_case :selected').text();
		//var user_ids = $('.js-example-basic-single').val();
		var team_id = $('#team_filter_case :selected').val();
		$("#load_img").css('display','block');
		
		//alert(team_id);
		//return 1;
		//$.cookie('language', select_lang);
		//$.cookie('pay_status', select_pay_status);
		
		//console.log('user_ids - ' + user_ids);
		//return true;
		
		jQuery.ajax({
		
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/writingassignment/get_filtered_data_case",
			
			data: {language: select_lang, pay_status: select_pay_status, team_id: team_id}, //user_ids: user_ids,
			success: function(response) 
			{	
				var table=$('.case_assign').DataTable();
				//console.log('filter response - ' + response);
				if(response != ''){
					
					var tbody = '';
					var response_array = $.parseJSON(response);				
					var len = response_array.length;
					
					var table = $('.case_assign').dataTable();
					table.fnClearTable();
					//console.log(response_array);
					for (var i = 0; i < len; i++) {
						
						/*if(response_array[i]['net_approved'] == '' || response_array[i]['net_approved'] == null){
							
							response_array[i]['net_approved'] = 1;
						}
						if(response_array[i]['financial_servicing_eligibil_c'] == '' || response_array[i]['financial_servicing_eligibil_c'] == null){
							
							response_array[i]['financial_servicing_eligibil_c'] = 1;
						}*/
						/*
						<td><?php echo $val->case_id_c;?></td>
						<td><?php echo $val->investigation_advocate_statu_c; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->language_prequal_c; ?></td>
						<td><?php echo $val->income_type_c;?></td>
						<td><?php echo $val->spouse_income_type_c;?></td>
						<td><?php echo $val->username;?></td>
						<td><?php echo $val->billing_status_c; ?></td>
						<td><?php echo $val->date_created; ?></td>	
						*/
						
						// Aded on 26-02-2024 
						if(response_array[i]['ths_report_date_c'] == null || response_array[i]['ths_report_date_c'] == undefined)
						{ 
							var transcript_present =  "No"; 
						}
						else
						{ 
							var transcript_present =   "Yes";  
						} 
						
						if(response_array[i]['lead_src'] == 'tpg')
						{ 
							var leadSRC =  "TPG"; 
						}
						else if(response_array[i]['lead_src'] == 'liberty-tax')
						{ 
							var leadSRC =  "Liberty"; 
						}
						else if(response_array[i]['lead_src'] == 'anthem-tax-relief')
						{ 
							var leadSRC =  "Anthem"; 
						}
						else if(response_array[i]['lead_src'] == 'pathward')
						{ 
							var leadSRC =  "Pathward"; 
						}
						else if(response_array[i]['lead_src'] == 'toro')
						{ 
							var leadSRC =  "TORO"; 
						}
						else if(response_array[i]['lead_src'] == 'tra')
						{ 
							var leadSRC =  "TRA"; 
						}
						else if(response_array[i]['lead_src'] == 'negozee')
						{ 
							var leadSRC =  "Negozee"; 
						}
						else if(response_array[i]['lead_src'] == 'Tax Status')
						{ 
							var leadSRC =  "Tax Status"; 
						}
						else 
						{ 
							var leadSRC =  ""; 
						} // end added on 26-02-2024
												
						//var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['case_id_c'],response_array[i]['client_name'],response_array[i]['investigation_advocate_statu_c'] , response_array[i]['investigation_type_c'], response_array[i]['language_prequal_c'],  response_array[i]['income_type_c'], response_array[i]['spouse_income_type_c'] , response_array[i]['username'], response_array[i]['billing_status_c'], response_array[i]['date_created'], transcript_present , response_array[i]['ths_report_date_c'], leadSRC ]);  // Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments

						// added on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
						var transcriptEligible = '';
						if(response_array[i]['transcripts_only_eligible_c'] == 1) 
						{ 
							transcriptEligible = "Yes"; 
						}

						var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['case_id_c'],response_array[i]['client_name'],response_array[i]['case_user'],response_array[i]['pps_type'],response_array[i]['pps_call_type_c'],response_array[i]['state_c'],response_array[i]['investigation_advocate_statu_c'] , response_array[i]['investigation_type_c'], response_array[i]['language_prequal_c'],  response_array[i]['income_type_c'], response_array[i]['spouse_income_type_c'] , response_array[i]['username'], response_array[i]['billing_status_c'], response_array[i]['date_created'], transcriptEligible , response_array[i]['logics_folder_c'], response_array[i]['marital_status_c'], response_array[i]['filing_status_state_c'], response_array[i]['split_pps_call_c']]);
						// end added on 30-05-2024
						
						//response_array[i]['lender'],
						
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
			<label class="control-label" style="font-size: medium;">Case Assignment</label>
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
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Team</font>
			<select id="team_filter_case" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="892ac4d6-fc79-11e8-950a-9eff2f4a401f">California</option>
				<option value="13b9e7a4-920b-11e9-8132-0a1db36945e2">Chicago Sr. CA's English</option>
				<option value="14810e12-ae47-11e9-8c8b-0a1db36945e2">Finance Pal (Sales)</option>
				<option value="1">Global</option>
				<option value="892ab2ac-fc79-11e8-806d-9eff2f4a401f">Sugar</option>
				<option value="892abff4-fc79-11e8-8d9a-9eff2f4a401f">Texas</option>
				
			</select>
			
			<!--<font color="black" style="padding-right: 20px; padding-top: 3px; ">Assigned To</font>
			
			<select class="js-example-basic-single form-control" id="assign_to_filter_case" name="assigned_filter[]" multiple="multiple" style="border: #dfe8f1 solid 1px; margin-right: 20px;" >	
			
				<option value=""></option>
				<?php	//foreach($user_list as $val){ ?>
					
						<option value="<?php //echo $val->id; ?>"><?php //echo $val->uname; ?></option>
				<?php //}?>
			</select>  -->
			
			<button type="button" id="filter_button" class="btn btn-primary" onclick="get_filtered_data_case(this);" style="margin-left: 20px;">Filter</button>
		</div>
		
		<div class="col-sm-12">
			<div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
			
			<?php if($write_assignments == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered case_assign" cellspacing="0" width="100%">
				<!-- style="float: left;  max-height: 400px; overflow-y: scroll;" -->
				<thead>
					<tr style="text-align: center">
						<th>Action <input type="checkbox" id="selectAllWrite" class="assignment_cases"/></th>
						<!--<th>Case Id.</th> 
						<th>Client Name</th> 
						<th>Investigation Stage</th> 
						<th>Investigation Type</th>
						<th>Language</th>
						<th>Client Income Type</th>
						<th>Spouse Income Type</th>						
						<th>Assigned To</th>
						<th>Billing Status</th>
						<th>Date Created</th>
						<th>Transcripts Available</th>
						<th>Transcripts Date</th>
						<th>Channel Partner</th> --><!-- added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related --> <!-- Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments -->
						
						<!-- Added on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments -->
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
						<!-- end Added on 30-05-2024 -->
					</tr>
				</thead>
				<tbody id="write_assign_tbody">
				
				<?php	
				foreach($write_assignments as $val){ 
					//added on 26-02-2024  https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
					if ($val->lead_src == 'tpg') {
						$leadSRC = "TPG";
					} elseif ($val->lead_src == 'liberty-tax') {
						$leadSRC = "Liberty";
					} elseif ($val->lead_src == 'anthem-tax-relief') {
						$leadSRC = "Anthem";
					} elseif ($val->lead_src == 'pathward') {
						$leadSRC = "Pathward";
					} elseif ($val->lead_src == 'toro') {
						$leadSRC = "Toro";
					} elseif ($val->lead_src == 'tra') {
						$leadSRC = "TRA";
					} elseif ($val->lead_src == 'negozee') {
						$leadSRC = "Negozee";
					} elseif ($val->lead_src == 'Tax Status') {
						$leadSRC = "Tax Status";
					} else {
						$leadSRC = "";
					}
					//end added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
					?>
				
					<tr id="<?php echo $val->id;?>" >
						<td><input type="checkbox" name="selector[]" id="<?php echo $val->id;?>" class="assignment_cases" value="<?php echo $val->id;?>" class="assignment_cases"></td>
						
						<td><?php echo $val->case_id_c;?></td>
						<td><?php echo $val->client_name;?></td>

						<!-- Added on 30-05-2024 -->
						<td><?php echo $val->case_user;?></td>
						<td><?php echo $val->pps_type;?></td>
						<td><?php echo $val->pps_call_type_c;?></td> 
						<td><?php echo $val->state_c;?></td> 
						<!-- end Added on 30-05-2024 -->

						<td><?php echo $val->investigation_advocate_statu_c; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->language_prequal_c; ?></td>
						<td><?php echo $val->income_type_c;?></td>
						<td><?php echo $val->spouse_income_type_c;?></td>
						<td><?php echo $val->username;?></td>
						<td><?php echo $val->billing_status_c; ?></td>
						<td><?php echo $val->date_created; ?></td>

						<!-- Added on 30-05-2024 -->
						<td><?php if($val->transcripts_only_eligible_c == 1) { echo "Yes"; }  //echo $val->transcripts_only_eligible_c; ?></td>
						<td><?php echo $val->logics_folder_c; ?></td>  
						<td><?php echo $val->marital_status_c; ?></td>	
						<td><?php echo $val->filing_status_state_c; ?></td>	
						<td><?php echo $val->split_pps_call_c; ?></td>
						<!-- end Added on 30-05-2024 -->

						<!-- <?php //if(!empty($val->ths_report_date_c) && $val->ths_report_date_c != null) 						{ ?>
							<td><?php //echo "Yes"; ?></td>
							
						<?php //}
						//else 						{?>
							<td><?php //echo "No"; ?></td>
						<?php // } ?>	
						
						<td><?php //echo $val->ths_report_date_c; ?></td>
						<td><?php // echo $leadSRC ; ?></td> --><!-- added on 26-02-2024   https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related --> <!-- Commented on 30-05-2024 -->
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
			
			
			<div id="assign">				
				
				<select class="form-control select2" id="selected_user_id">
					<option></option>
					<?php	foreach($user_list as $val){ ?>
					
						<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
					<?php }?>
				</select>
				
				<!--<button type="button" id="mass_so_assign_to" class="btn btn-primary" onclick="move_to_assignment();">Move To SO Assignment</button> -->
				
				<button type="button" id="mass_assign_to_btn" class="btn btn-primary" onclick="assign_cases_to();">Assign </button>
				
			</div>
			<?php } ?>	
		</div>
	
		
	</div>
	
	
		
</div>


		
</div>
</div>
</div>