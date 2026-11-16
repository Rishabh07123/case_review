<style>

.select2-container--classic .select2-selection--multiple .select2-selection__rendered {
				
	max-width: 100% !important;
	overflow: hidden !important;
	white-space: normal !important;
}
.select_batch {
				
	background-color: #fff;
    border: 1px solid #aaa;
	border-radius: 4px;
	margin-right: 32px;
	height: 29px;
    margin-top: 4px;
}
#assign_to_filter{
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
		
		$('#selectAllSO').change(function(){
			
			if($(this).prop('checked')){
				
				$('#so_assign_tbody').find('td input:checkbox').prop('checked', 'checked');
			}else{
				
				$('#so_assign_tbody').find('td input:checkbox').prop('checked', false);
			}
		});
		
		renderSelect2();
	}); 
	
	function final_assignment(){

		var error = 0;	
		var i=0;	
		var lead_array = [];
		var main_lead_array = [];
		
		var check = true;
		var check_case = false;
		var col_user = '';

		data_json = '';

		$(':checkbox:checked').each(function(i){
			
			lead_array[i] = [];
			 lead_array[i]['id'] = $(this).val();
			 main_lead_array[i] = $(this).val();
			data_json += '{"id": "' + $(this).val(); + '"';
			  
			var currentRow = $(this).closest("tr"); 
         
			col_user = currentRow.find("td:eq(2)").attr('id');
			data_json += '","user":"' + col_user + '"';
			data_json += '};';
			lead_array[i]['user'] = col_user;

			check_case = currentRow.find("td:eq(6)").text();
			
			//console.log(col_user + ', ' + check_case);
			if(col_user != '' && col_user != undefined){

				//console.log('check' + check);
			}
			else{

				check = false;				
			}
			//console.log(lead_array);
			i = i+1;
		});
		//alert(col_user + ', ' + check);
		//return false;
		//sconsole.log(data_json);

		if(check == false){
			
			error = 1;
			alert('No temporary user found for selected case - ' + check_case);
			return false;
		}
		//var assign_user_id = col_user; //$('#final_assign_user_id').val();
		var batch_id = $('#batch_id').val();
		
		if(lead_array.length < 1){
			
			error = 1;
			alert('Please select a lead.');
			return false;
		}
		/*if(assign_user_id == ''){
			
			error = 1;
			alert('No temporary user found for selected case.');
			return false;
		}*/
		if(batch_id == ''){
			
			error = 1;
			alert('Please select a batch.');
			return false;
		}

		if(error == 0){
			
			swal('Assign selected leads to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					$('#final_assign_to').attr("disabled", true);
					$("#reg_js_img").css('display','block');
					//lead_json = JSON.stringify(lead_array);
					jQuery.ajax({
			
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/update_lead_assignment", 
						
						data: { lead_array: data_json, batch_id: batch_id}, //assign_user_id: assign_user_id,
						success: function(response) 
						{	
							$('#final_assign_user_id').val('');
														
							//response = JSON.parse(response);
							
							if(response == 1){ // .status == 'done'
								
								update_lead_status(main_lead_array);
								
								$("#reg_js_img").css('display','none');
								
								//location.reload();								
							}
							else{
								
								$.jGrowl("Unable to update lead assignment. Error - " + response, {
									sticky: false,
									position: 'top-right',
									theme: 'bg-red'
								}); 
								$("#reg_js_img").css('display','none');
							}
							$('#final_assign_to').attr("disabled", false);
						}
					})
				}
			})
		}
	}

	//Code from SOAssignment
	function assign_cases(){
		
		var error = 0;		
		var lead_array = [];
		
		
		$(':checkbox:checked').each(function(i){
			
		  lead_array[i] = $(this).val();
		});
		
		var assign_user_id = $('#selected_assign_user_id').val();
		
		if(lead_array.length < 1){
			
			error = 1;
			alert('Please select a lead.');
			return false;
		}
		if(assign_user_id == ''){
			
			error = 1;
			alert('Please select a user.');
			return false;
		}
		
		if(error == 0){
			
			swal('Assign selected leads to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					$('#mass_assign_to').attr("disabled", true);
					$("#reg_js_img").css('display','block');
					
					jQuery.ajax({
			
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/tempAssignment", //update_lead_assignment
						
						data: {assign_user_id: assign_user_id, lead_array: lead_array},
						success: function(response) 
						{	
							$('#selected_assign_user_id').val('');
							$('#selected_assign_user_id').trigger('change');
							
							response = JSON.parse(response);
							
							if(response.status == 'done'){ 
								
								$.jGrowl("Temporary assignment updated successfully.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
								$("#reg_js_img").css('display','none');
								
								location.reload();								
							}
							else{
								
								$.jGrowl("Unable to update lead assignment. Error - " + response, {
									sticky: true,
									position: 'top-right',
									theme: 'bg-red'
								}); 
								$("#reg_js_img").css('display','none');
							}
							$('#mass_assign_to').attr("disabled", false);
						}
					})
				}
			})
		}
	}
	
	function update_lead_status(lead_array){
		
		$('#final_assign_to').attr("disabled", true);
		//$('#complete_assignment').attr("disabled", true);
		$("#reg_js_img").css('display','block');
		
		var table=$('.case_assign').DataTable();
		
		jQuery.ajax({
		
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/update_lead_assignment_status",
			
			data: {lead_array: lead_array},
			success: function(response) 
			{	
				//$('#selected_assign_user_id').val('');
				//$('#selected_assign_user_id').trigger('change');
				//console.log(response);
				response = JSON.parse(response);
				//console.log(response.status);
				if(response.status == 'done'){
					
					$.jGrowl("Lead assignment updated successfully.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
					$("#reg_js_img").css('display','none');
					
					//location.reload(); 
					
					$.each( lead_array, function( key, value ) {
						
						table.row("#"+value).remove().draw();
					});
				}
				else{
					
					$.jGrowl("Unable to update lead assignment. Error - " + response, {
						sticky: false,
						position: 'top-right',
						theme: 'bg-red'
					}); 
					$("#reg_js_img").css('display','none');
				}
				$('#final_assign_to').attr("disabled", false);
				$('#batch_id').val('');
				//$('#complete_assignment').attr("disabled", false);
			}
		})
	}
	
	//Filter Code
	function get_filtered_data(data){
		
		var select_lang = $('#language_filter :selected').text();
		var select_pay_status = $('#pay_status_filter :selected').text();
		var user_ids = $('.js-example-basic-single').val();
		var team_id = $('#team_filter :selected').val();
		//alert(team_id);
		//return 1;
		//$.cookie('language', select_lang);
		//$.cookie('pay_status', select_pay_status);
		$("#load_img").css('display','block');
		console.log('user_ids - ' + user_ids);
		//return true;
		jQuery.ajax({
		
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/get_filtered_data",
			
			data: {language: select_lang, pay_status: select_pay_status, user_ids: user_ids, team_id: team_id},
			success: function(response) 
			{	
				var table=$('.case_assign').DataTable();
				
				if(response != ''){
					
					var tbody = '';
					var response_array = $.parseJSON(response);				
					var len = response_array.length;
					//console.log(response_array);
					var table = $('.case_assign').dataTable();
					table.fnClearTable();
					
					for (var i = 0; i < len; i++) {
						
						var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['username'],  response_array[i]['lead_level_c'] , response_array[i]['logics_status_c'], response_array[i]['name'],  response_array[i]['case_id_c'], response_array[i]['cr_fee_c'] , response_array[i]['language_prequal_c'], response_array[i]['billing_status_c'], response_array[i]['percentage_paid_c'], response_array[i]['lending_partner_eligibility_c'],  response_array[i]['net_approved'], response_array[i]['investigation_type_c'], response_array[i]['spl_campaign'] ]);
						
						//response_array[i]['lender'],
						
						var theNode = table.fnSettings().aoData[rowNode].nTr;
						theNode.setAttribute('id',response_array[i]['id']);

						$('td', theNode)[2].setAttribute( 'id', response_array[i]['user_id7_c']);
					}
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
			<label class="control-label" style="font-size: medium;">Ready For Assignment</label>
			<div id="load_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/spinner.gif" /></div>
		</div>
		<div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 20px;">
			<label class="control-label" style="padding-right: 40px; font-size: medium;">Filters</label>   
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Select Language</font>
			<select id="language_filter" class="form-control" style="width: 10%; margin-right: 40px;">
				<option value=""></option>
				<option value="English">English</option>
				<option value="Spanish">Spanish</option>
			</select>
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Payment Status</font>
			<select id="pay_status_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="Current">Current</option>
				<option value="Paid In Full">Paid In Full</option>
				<option value="Past Due">Past Due</option>
			</select>
			
			<font color="black" style="padding-right: 20px; padding-top: 3px;">Team</font>
			<select id="team_filter" class="form-control" style="width: 10%;  margin-right: 40px;">
				<option value=""></option>
				<option value="892ac4d6-fc79-11e8-950a-9eff2f4a401f">California</option>
				<option value="13b9e7a4-920b-11e9-8132-0a1db36945e2">Chicago Sr. CA's English</option>
				<option value="14810e12-ae47-11e9-8c8b-0a1db36945e2">Finance Pal (Sales)</option>
				<option value="1">Global</option>
				<option value="892ab2ac-fc79-11e8-806d-9eff2f4a401f">Sugar</option>
				<option value="892abff4-fc79-11e8-8d9a-9eff2f4a401f">Texas</option>
				
			</select>
			
			<font color="black" style="padding-right: 20px; padding-top: 3px; ">Assigned To</font>
			<!--<select id="pay_status_filter" class="form-control" style="width: 10%;  margin-right: 40px;"> -->
			<select class="js-example-basic-single form-control" id="assign_to_filter" name="assigned_filter[]" multiple="multiple" style="border: #dfe8f1 solid 1px; margin-right: 20px;" >	
			<!--<select class="js-example-basic-single form-control" id="form_name" name="form_names[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >-->
				<option value=""></option>
				<?php	foreach($user_list as $val){ ?>
					
						<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
				<?php }?>
			</select>  
			
			<button type="button" id="filter_button" class="btn btn-primary" onclick="get_filtered_data(this);" style="margin-left: 20px;">Filter</button>
		</div>
		
		<div class="col-sm-12">
			<div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
			
			<?php if($so_assignments == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered case_assign" cellspacing="0" width="100%">
				<!-- style="float: left;  max-height: 400px; overflow-y: scroll;" -->
				<thead>
					<tr style="text-align: center">
						<th>Action <input type="checkbox" id="selectAllSO" class="assignment_cases"/></th>
						<th>Assigned To</th>						
						<th>Lead Level</th>
						<th>Logics Status</th>
						<th>Client Name</th>
						<th>Client Id.</th> 
						<th>CR Fee</th>	
						<th>Client Language</th>
						<th>Billing Status</th>
						<th>Percentage Paid</th>
						<th>Lending Partner Eligibility</th>
						
						<th>Spl. Campaign</th>
					</tr>
				</thead>
				<tbody id="so_assign_tbody">
				
				<?php	
				foreach($so_assignments as $val){ ?>
				
					<tr id="<?php echo $val->id;?>" >
						<td><input type="checkbox" name="selector[]" id="<?php echo $val->id;?>" class="assignment_cases" value="<?php echo $val->id;?>" class="assignment_cases"></td>						
						<td><?php echo $val->username;?></td>						
						<td><?php echo $val->lead_level_c;?></td>
						<td><?php echo $val->logics_status_c; ?></td>
						<td><?php echo $val->name;?></td>
						<td><?php echo $val->case_id_c;?></td>
						<td><?php echo $val->cr_fee_c; ?></td>
						<td><?php echo $val->language_prequal_c; ?></td>
						<td><?php echo $val->billing_status_c; ?></td>
						<td><?php echo $val->percentage_paid_c; ?></td>
						<td><?php echo $val->lending_partner_eligibility_c; ?></td>
						
						<td><?php echo $val->spl_campaign; ?></td>
						
						<!--<td><?php //if($val->lead_src == "Emersive Marketing") { //echo "Bobby"; } ?></td>-->
						
						
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
			
			
			<div id="assign">				
				
				<select class="form-control select2" id="selected_assign_user_id">
					<option></option>
					<?php	foreach($user_list as $val){ ?>
					
						<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
					<?php }?>
				</select>
				
				<!--<button type="button" id="mass_so_assign_to" class="btn btn-primary" onclick="move_to_assignment();">Move To SO Assignment</button> -->
				
				<button type="button" id="mass_assign_to" class="btn btn-primary" onclick="assign_cases();">Temp Assignment</button>
				
			</div>

			<div id="final_assign">				
				
				<select class="form-control select_batch" id="batch_id">
					<option></option>
					<option value="2020Week30">20-Week 30</option>
					<option value="2020Week31">20-Week 31</option>
					<option value="2020Week32">20-Week 32</option>
					<option value="2020Week33">20-Week 33</option>
					<option value="2020Week34">20-Week 34</option>
					
				</select>

				<!--<select class="form-control select2" id="final_assign_user_id">
					<option></option>
					<?php	//foreach($user_list as $val){ ?>
					
						<option value="<?php //echo $val->id; ?>"><?php //echo $val->uname; ?></option>
					<?php //}?>
				</select> -->
				
				<!--<button type="button" id="mass_so_assign_to" class="btn btn-primary" onclick="move_to_assignment();">Move To SO Assignment</button> -->
				
				<button type="button" id="final_assign_to" class="btn btn-primary" onclick="final_assignment();">Finalize Assignment</button>
				
			</div>

			<?php } ?>	
		</div>
	
		
	</div>
	
	
		
</div>


		
</div>
</div>
</div>