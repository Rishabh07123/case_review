<?php $userid = $this->session->userdata('User_id'); session_write_close(); $id = $_GET['id']; //echo $sugar_url; //echo $this->session->userdata('username');//echo  base_url();; 
//print_r($this->config->item('sugar_url')); //sugar_url;?>

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datepicker-ui/datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script type="text/javascript">
	var refresh = 0;
	var main_initial_services_dropdown;
	var main_business_services;
	var main_business_exist = 0;
	var main_service_names;
	var main_child_service_names;
	var main_service_names_array;
	var statelist;
	var extra_expense = 0;
	var total_ths_balance = 0;
	var total_ths_spbalance = 0;
	var main_lead_id;
	var sugar_dropdowns;

	$(function() { "use strict";
		$('#trcdval').datepicker({});
		$('#lcdval').datepicker({});
		$('#indval').datepicker({});
	});	
	
	$(document).ready(function() {
		$(".form-group").show();
		$('.tabs').hide();
		$('#accordion').hide();
		$('.accordion_set').hide(); 
		$('#hide_caseid_data').hide();
		$('.accordion-view').hide();
		$('.tabs-view').hide();       
		//$('.casenum').hide();
		$('.clientname').hide();
		$('.casestatus').hide(); 
		$('#PPSspousetab').hide(); 
		$('#PPSspouse').hide(); 
		$('#PPSspousetab_label').hide();
		
		$('#pps_accordion').hide();
		$('#spouse_accordion').hide();
		
		$('#ThsSummarytab').hide();
		$('#ThsSummary_label').hide();
		
		$('#PPStab_label').hide();
		$('#PPStab').hide(); 
		
		$('#PPSstatepersonaltab').hide();
		$('#PPSstatepersonaltab_label').hide();	
		$('#pps_state_accordion').hide();		
		
		$('#refresh_caseid_data').hide();
		
		getStateList();
		init()
		
		
		//$('.datepick').datepicker();	
	});	
	
	
	function getStateList(){
		
		var self = this;
		
		var data = '{ "dropdowns":{"name": "state_dom"}}'; //new Array(2);
		//arry.push([1,2]);
		//data['dropdowns']['name'] = 'state_dom';
		jQuery.ajax({
			
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/getStateList",
		
			//dataType: 'json',
			data: {data},
			async:false,
			success: function(response) 
			{	
				//console.log('RESPONSE SUGAR - ' + response);			
				if(response != ''){
					//console.log('RESPONSE SUGAR - ' + response);
					self.state_list = response;
					//console.log('RESPONSE SUGAR - ' + self.state_list);
				}
			}
		})

		var states = JSON.parse(self.state_list);
		//console.log(states['state_dom']);
		statelist = states['state_dom']; //self.state_list;
		
	}

	function hide_data(){
		
		$('#accordion').hide(); 
		$('#my_cases_div').show(); 
        location.reload();		
	}
	
	function refresh_data(){
		
		refresh = 1;
		total_ths_balance = 0;
		total_ths_spbalance = 0;
		
		var case_id = $('#cid').val();
		$('#caseno').remove();
		$('#cname').remove();
		$('#case_status').remove();
		$('#view_cr').remove();
		
		show_data(case_id);		
	}
	
	function checkMask(data){
		
		var date = data.value;
		if (date.match(/^\d{2}$/) !== null) {
			data.value = date + '/';
		} else if (date.match(/^\d{2}\/\d{2}$/) !== null) {
			data.value = date + '/';
		}
		
	}
	/*function showCalendar(data){
		console.log('DATA - ' + data);
		 $(data).datepicker();
	}*/
	/*$(document.body).on('mouseover','.datepick',function(){
		
		$('.datepick').datepicker();		
	})
	
	$("input").on("change", function() {
		this.setAttribute(
			"data-date",
			moment(this.value, "YYYY-MM-DD")
			.format( this.getAttribute("data-date-format") )
		)
	}).trigger("change")*/
	
	//Tax Case Intake
	function edit_revenue_federal(){
		//alert('hello');
		$('.revenue_federal').attr("disabled", false);
		$('.editrevenue_federal').hide();
		$('.saverevenue_federal').show();
		$('.cancelrevenue_federal').show();
		$('#federal_deadline_date').datepicker({});
	}
	
	function cancel_revenue_federal(){
		//alert('hello');
		$('.revenue_federal').attr("disabled", true);
		$('.editrevenue_federal').show();
		$('.saverevenue_federal').hide();
		$('.cancelrevenue_federal').hide();
	}
	
	function edit_revenue_state(){
		//alert('hello');
		$('.revenue_state').attr("disabled", false);
		$('.editrevenue_state').hide();
		$('.saverevenue_state').show();
		$('.cancelrevenue_state').show();
		$('#state_deadline_date').datepicker({});
	}
	
	function cancel_revenue_state(){
		//alert('hello');
		$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();
	}
	
	function save_revenue_federal(){
		
		var cid = document.getElementById("case_num").value;
		/*$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();*/
		
		var deadline_reason = $('#federal_deadline_reason').val();
		var deadline_date = $('#federal_deadline_date').val();
		//var ro = $('#federal_ro').val();
		var ro_name = $('#federal_ro_name').val();
		var ro_phone = $('#federal_ro_phone').val();
		var ro_fax = $('#federal_ro_fax').val();
		
		$('.revenue_federal').attr("disabled", true);
		$('.editrevenue_federal').show();
		$('.saverevenue_federal').hide();
		$('.cancelrevenue_federal').hide();
		
		//alert(deadline_reason + ', ' + deadline_date + ', ' + ro + ', ' + ro_name + ', ' + ro_phone + ', ' + ro_fax);
		
		if(cid != '' && (deadline_reason != '' || deadline_date != '' || ro_name != '' || ro_phone != '' || ro_fax != '')){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/save_case_intake_federal",
				dataType: 'json',
				
				data: {caseid: cid, deadline_reason: deadline_reason, deadline_date: deadline_date, ro_name: ro_name, ro_phone: ro_phone, ro_fax: ro_fax},
				
				success: function(res) 
				{
					//console.log('res success111- '  +res);
					if(res == true){
						
						$.jGrowl("Data updated successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update data.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			})
		}
	}
	
	function save_revenue_state(){
		
		var cid = document.getElementById("case_num").value;
		/*$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();*/
		
		var deadline_reason = $('#state_deadline_reason').val();
		var deadline_date = $('#state_deadline_date').val();
		//var ro = $('#state_ro').val();
		var ro_name = $('#state_ro_name').val();
		var ro_phone = $('#state_ro_phone').val();
		var ro_fax = $('#state_ro_fax').val();
		
		//alert(deadline_reason + ', ' + deadline_date + ', ' + ro + ', ' + ro_name + ', ' + ro_phone + ', ' + ro_fax);
		
		$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();
		
		if(cid != '' && (deadline_reason != '' || deadline_date != '' || ro_name != '' || ro_phone != '' || ro_fax != '')){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/save_case_intake_state",
				dataType: 'json',
				
				data: {caseid: cid, deadline_reason: deadline_reason, deadline_date: deadline_date, ro_name: ro_name, ro_phone: ro_phone, ro_fax: ro_fax},
				
				success: function(res) 
				{
					if(res == true){
						
						$.jGrowl("Data updated successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update data.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			})
		}
	}
	
	//Accordian Case Intake
	function acc_edit_revenue_federal(){
		//alert('hello');
		$('.acc_revenue_federal').attr("disabled", false);
		$('.acc_editrevenue_federal').hide();
		$('.acc_saverevenue_federal').show();
		$('.acc_cancelrevenue_federal').show();
		$('#acc_federal_deadline_date').datepicker();
	}
	
	function acc_cancel_revenue_federal(){
		//alert('hello');
		$('.acc_revenue_federal').attr("disabled", true);
		$('.acc_editrevenue_federal').show();
		$('.acc_saverevenue_federal').hide();
		$('.acc_cancelrevenue_federal').hide();
	}
	
	function acc_edit_revenue_state(){
		//alert('hello');
		$('.acc_revenue_state').attr("disabled", false);
		$('.acc_editrevenue_state').hide();
		$('.acc_saverevenue_state').show();
		$('.acc_cancelrevenue_state').show();
		$('#acc_state_deadline_date').datepicker({});
	}
	
	function acc_cancel_revenue_state(){
		//alert('hello');
		$('.acc_revenue_state').attr("disabled", true);
		$('.acc_editrevenue_state').show();
		$('.acc_saverevenue_state').hide();
		$('.acc_cancelrevenue_state').hide();
	}
	
	function acc_save_revenue_federal(){
		
		var cid = document.getElementById("case_num").value;
		/*$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();*/
		
		var deadline_reason = $('#acc_federal_deadline_reason').val();
		var deadline_date = $('#acc_federal_deadline_date').val();
		//var ro = $('#federal_ro').val();
		var ro_name = $('#acc_federal_ro_name').val();
		var ro_phone = $('#acc_federal_ro_phone').val();
		var ro_fax = $('#acc_federal_ro_fax').val();
		
		$('.acc_revenue_federal').attr("disabled", true);
		$('.acc_editrevenue_federal').show();
		$('.acc_saverevenue_federal').hide();
		$('.acc_cancelrevenue_federal').hide();
		
		//alert(deadline_reason + ', ' + deadline_date + ', ' + ro + ', ' + ro_name + ', ' + ro_phone + ', ' + ro_fax);
		
		if(cid != '' && (deadline_reason != '' || deadline_date != '' || ro_name != '' || ro_phone != '' || ro_fax != '')){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/save_case_intake_federal",
				dataType: 'json',
				
				data: {caseid: cid, deadline_reason: deadline_reason, deadline_date: deadline_date, ro_name: ro_name, ro_phone: ro_phone, ro_fax: ro_fax},
				
				success: function(res) 
				{
					//console.log('res success111- '  +res);
					if(res == true){
						
						$.jGrowl("Data updated successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update data.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			})
		}
	}
	
	function acc_save_revenue_state(){
		
		var cid = document.getElementById("case_num").value;
		/*$('.revenue_state').attr("disabled", true);
		$('.editrevenue_state').show();
		$('.saverevenue_state').hide();
		$('.cancelrevenue_state').hide();*/
		
		var deadline_reason = $('#acc_state_deadline_reason').val();
		var deadline_date = $('#acc_state_deadline_date').val();
		//var ro = $('#state_ro').val();
		var ro_name = $('#acc_state_ro_name').val();
		var ro_phone = $('#acc_state_ro_phone').val();
		var ro_fax = $('#acc_state_ro_fax').val();
		
		//alert(deadline_reason + ', ' + deadline_date + ', ' + ro + ', ' + ro_name + ', ' + ro_phone + ', ' + ro_fax);
		
		$('.acc_revenue_state').attr("disabled", true);
		$('.acc_editrevenue_state').show();
		$('.acc_saverevenue_state').hide();
		$('.acc_cancelrevenue_state').hide();
		
		if(cid != '' && (deadline_reason != '' || deadline_date != '' || ro_name != '' || ro_phone != '' || ro_fax != '')){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/save_case_intake_state",
				dataType: 'json',
				
				data: {caseid: cid, deadline_reason: deadline_reason, deadline_date: deadline_date, ro_name: ro_name, ro_phone: ro_phone, ro_fax: ro_fax},
				
				success: function(res) 
				{
					//console.log('res success111- '  +res);
					if(res == true){
						
						$.jGrowl("Data updated successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update data.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			})
		}
	}
	
	
	//SERVICES
	function show_services_div(){
		//console.log('show_services_div');
		$('#show_services_div').show();
		$('#services_div').hide();
		$('#add_services_div').hide();
		$('#show_services_div_table').show();	
		showServicesList();
		getInitialServicesDropdown();
	}
	
	function close_services_div(){
		
		$('#show_services_div').hide();
		$('#services_div').show();
		$('#add_services').show();

		//empty child services table
		$('#child_services_table tbody tr').remove(); 
		//$('#services_tbody tr').remove();
	}
	
	function show_add_services_div(){
		//console.log('show_services_div');
		
		$('#quantity').val(1);
		$('#list-price').val(0);
		
		$('#add_services_div').show();
		$('#add_services').hide();
		$('#update_services').hide();
		$('#show_services_div_table').hide();
		//getInitialServicesDropdown();
		
	}
	
	function cancelServices(){
		
		//$('#show_services_div').hide();
		$('#add_services').show();
		
		$('#service_name').val('');
		$('#service_name').empty();
		$('#sale_type').val('Case Review');
		$('#servicer').val(''); //CTax
		$('#entity').val(''); //Individual
		$('#billed_to').val('');
		$('#commission').val('');
		$('#service_date').val('');
		$('#rec_type').val('');
		
		$('#quarter').val(null).trigger('change');
		$('#quarter').val('');
		$('#reset_rollin').val('');
		//$('#reset_rollin').empty();
		$('#reset_rollin').val(null).trigger('change');
		
		$('#service_flags').val(null).trigger('change');
		$('#service_flags').val('');
		$('#services_type').val('');
		$('#services_type').empty();
		$('#description').val('');
		$('#list-price').val('');
		$('#list-price').attr("disabled", false);
		$('#quantity').val('');
		$('#discount-amount').val('');
		$('#discount_reason').val('');
		$('#years').val(null).trigger('change');
		$('#years').val('');
		$('#service_state').val(null).trigger('change');
		$('#service_state').val('');		
		//$('#years').empty();		
		$('#calculated-amount').val('');
		$('#add_services_div').hide();
		$('#save_services').show();	
		$('#year_main_div').hide();
		$('#state_main_div').hide();
		$('#rec_type_div').hide();
		$('#quarter_div').hide();
		$('#roll_in_div').hide();
		$('#flag_div').hide();
		$('#service_date_main_div').hide();
		
		$('#servicer').attr("disabled", false);
		$('#services_type').attr("disabled", false);
		$('#service_name').attr("disabled", false);
		$('#entity').attr("disabled", false);
		$('#discount-amount').attr("disabled", false);
		$('#quantity').attr("disabled", false); 

		$('#show_services_div_table').show();
		showServicesList();
		$('#pkg_button_div').hide(); 
		$('#child_service_box').hide();
		$('#package_parent_id').val('');

		$('#servicer').attr("disabled", false);
		$('#entity').attr("disabled", false);
		$('#services_type').attr("disabled", false);
		$('#service_name').attr("disabled", false);

		//empty child services table
		$('#child_services_table tbody tr').remove(); 

		var cl = $('#update_services').attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];
		
		if(rid != ''){
			
			$('#update_services').removeClass(rid); 
		}
	}
	
	function add_business()	{

		var cid = document.getElementById("case_num").value;

		if(cid != ''){

			swal('Confirm add business for this case?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					jQuery.ajax({
					
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_business",
						dataType: 'json',
						//async: false,
						data: {caseid: cid},
						
						success: function(res) 
						{
							if(res != 0){

								$.jGrowl("Business Added Successfully.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
								
								/*var entity_div = '<option value="' + res + '">TBD, na</option>'; 
					
								$('#entity').append(entity_div);
								$('#billed_to').append(entity_div);*/

								$('#add_business').hide();
								
							}
							else{

								alert('Unable to add Business');
							}
							//var service_len = res.length;	
						}				
					})
					checkBusinessServiceLink();
					getInitialServicesDropdown();
				}
			})
		}
	}

	//Accordion View
	
	function acc_show_services_div(){
		//console.log('acc_show_services_div');
		$('#acc_show_services_div').show();
		$('#acc_services_div').hide();
		$('#acc_add_services_div').hide();
		showServicesList();
		getInitialServicesDropdown();
	}
	
	function acc_close_services_div(){
		
		$('#acc_show_services_div').hide();
		$('#acc_services_div').show();
		$('#acc_add_services').show();
	}
	
	function acc_show_add_services_div(){
		//console.log('acc_show_services_div');
		
		$('#acc_quantity').val(1);
		$('#acc_list-price').val(0);
		
		$('#acc_add_services_div').show();
		$('#acc_add_services').hide();
		$('#acc_update_services').hide();
		
		//getInitialServicesDropdown();
		
	}
	
	function acc_cancelServices(){
		
		//$('#show_services_div').hide();
		$('#acc_add_services').show();
		
		$('#acc_servicer').val('');
		$('#acc_entity').val('');
		$('#acc_billed_to').val('');
		$('#acc_commission').val('');
		$('#acc_service_date').val('');
		$('#acc_quarter').val(null).trigger('change');
		$('#acc_quarter').val('');
		//$('#acc_quarter').empty();
		$('#acc_reset_rollin').val(null).trigger('change');
		$('#acc_reset_rollin').val('');
		//$('#acc_reset_rollin').empty();
		$('#acc_service_flags').val(null).trigger('change');
		$('#acc_service_flags').val('');		
		//$('#acc_service_flags').empty();	
		$('#rec_type').val('');
		$('#acc_service_name').val('');
		$('#acc_service_name').empty();
		$('#acc_sale_type').val('');
		$('#acc_services_type').val('');
		$('#acc_services_type').empty();
		$('#acc_description').val('');
		$('#acc_list-price').val('');
		$('#acc_quantity').val('');
		$('#acc_discount-amount').val('');
		$('#acc_discount_reason').val('');
		$('#acc_years').val(null).trigger('change');
		$('#acc_years').val('');
		//$('#acc_years').empty();
		$('#acc_calculated-amount').val('');
		$('#acc_add_services_div').hide();
		$('#acc_year_main_div').hide();
		$('#acc_save_services').show();	
		
		$('#acc_rec_type_div').hide();
		$('#acc_quarter_div').hide();
		$('#acc_roll_in_div').hide();
		$('#acc_flag_div').hide();
		$('#acc_service_date_main_div').hide();
		
		var cl = $('#acc_update_services').attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];
		
		if(rid != ''){
			
			$('#acc_update_services').removeClass(rid); 
		}
	}
	
	/*function edit_tab_service(data){
		
		var rid = $(data).closest('tr').attr('id'); //$(data).parent().attr('id');
				
		//alert('rid-' + rid);
		$('#add_services_div').show();
		$('#add_services').hide();
		getInitialServicesDropdown();
		setTimeout(xxx, 1000);
		
		update_edit_tab_service(rid);
	}*/
	
	
	function edit_tab_service(data){
		
		var cl = $('#update_services').attr("class");
		var classes = cl.split(" ");
		var rid1 = classes[2];
		//console.log(cl + '  ---  ' + rid1)
		$('#update_services').removeClass(rid1);  
		
		var rid = $(data).closest('tr').attr('id');  
		
		$('#update_services').addClass(rid);
		$('#update_services').show();
		
		$('#save_services').hide();
		getInitialServicesDropdown();
		$('#add_services_div').show();
		$('#add_services').hide();
		
		//empty child services table
		$('#child_services_table tbody tr').remove(); 

		updateData(rid);	
		//getNonPackageServiceTypes();	
	}
	
	//accordion
	function edit_acc_service(data){
		
		var cl = $('#acc_update_services').attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];
		
		$('#acc_update_services').removeClass(rid);
		
		var rid = $(data).closest('tr').attr('id'); 
		
		$('#acc_update_services').addClass(rid);
		$('#acc_update_services').show();
		
		$('#acc_save_services').hide();
		getInitialServicesDropdown();
		$('#acc_add_services_div').show();
		$('#acc_add_services').hide();
		
		updateData(rid,acc=1);		
	}
	
	function updateData(rid,acc=0){
		//console.log('updateData - rid - '  +rid);
		var self = this;

		if(rid != ''){
			//console.log('rid123 - '  +rid);
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_revenue_details_with_id",
				dataType: 'json',
				async: false,
				data: {rowid: rid},
				
				success: function(res) 
				{
					var service_len = res.length;					
					var tbody = '';
					//alert(res[0].revenue_biz_id);
					
										
					updateServicerRelatedDropdown(res[0].servicer,res[0].revenue_biz_id, res[0].service_status_c);
					
					var tempid = res[0].product_template_id;
					//alert(res[0].service_type);
					//self.esrvice_type_id = res[0].service_type;
					var check = checkRecurring(tempid);
					checkYearDiscount(tempid);
					
					var catid = res[0].category_id;
					var lp = res[0].list_price;
					
					if(service_len) {
						
						if(res[0]['list_price_editable_c'] == 1){
							
							$('#list-price').attr("disabled", false);
						}
						else
						{
							$('#list-price').attr("disabled", true);
						}
						
						if(res[0].period_quarter_c != '^^' && res[0].period_quarter_c != null && acc == 0){
							
							var quarters = res[0].period_quarter_c;
							var selectedQuarters = [];
							if(quarters){
								
								var arrQuarter = quarters.split(',');
								
								for(var i in arrQuarter) {
									
									//var qval = arrQuarter[i].split('^').join('');
									//console.log(qval);
									selectedQuarters.push(arrQuarter[i]);
									
								}
								//console.log(selectedYears);
								var multiSelect = $("#quarter").select2();
								multiSelect.val(selectedQuarters).trigger("change");
							}	
						}

						if(res[0].resservice_flag_c != '^^' && res[0].resservice_flag_c != null && acc == 0){
							
							var flags = res[0].resservice_flag_c;
							var selectedFlags = [];
							if(flags){
								
								var arrFlags = flags.split(',');
								
								for(var i in arrFlags) {
									
									//var fval = arrFlags[i].split('^').join('');
									//console.log(fval);
									selectedFlags.push(arrFlags[i]);
									
								}
								//console.log(selectedFlags);
								var multiSelectF = $("#service_flag").select2();
								multiSelectF.val(selectedFlags).trigger("change");
							}
//console.log('567');							
						}					
						
						if(res[0].years_list_c != '^^' && res[0].years_list_c != null && acc == 0){
							
							var years = res[0].years_list_c;
							var selectedYears = [];
							if(years){
								arrYears = years.split(',');
								
								for(var i in arrYears) {
									
									selectedYears.push(arrYears[i]);
									
								}
								
								var multiSelect = $("#years").select2();
								multiSelect.val(selectedYears).trigger("change");

								

								//$(".select2").val(selectedYears).trigger('change');
							}							
						}

						if(res[0].state_list_c != '^^' && res[0].state_list_c != null && acc == 0){
							
							var states = res[0].state_list_c;
							var selectedStates = [];
							if(states){
								arrStates = states.split(',');
								
								for(var i in arrStates) {
									
									selectedStates.push(arrStates[i]);
									
								}
								
								var multiSelect = $("#service_state").select2();
								multiSelect.val(selectedStates).trigger("change");

								//$(".select2").val(selectedYears).trigger('change');
							}							
						}
						
						//Accordian
						if(res[0].period_quarter_c != '^^' && res[0].period_quarter_c != null && acc == 1){
							
							var quarters = res[0].period_quarter_c;
							var selectedQuarters = [];
							if(quarters){
								
								var arrQuarter = quarters.split(',');
								
								for(var i in arrQuarter) {
									
									//var qval = arrQuarter[i].split('^').join('');
									//console.log(qval);
									selectedQuarters.push(arrQuarter[i]);
									
								}
								//console.log(selectedYears);
								var multiSelect = $("#acc_quarter").select2();
								multiSelect.val(selectedQuarters).trigger("change");
							}	
						}

						if(res[0].resservice_flag_c != '^^' && res[0].resservice_flag_c != null && acc == 1){
							
							var flags = res[0].resservice_flag_c;
							var selectedFlags = [];
							if(flags){
								
								var arrFlags = flags.split(',');
								
								for(var i in arrFlags) {
									
									//var fval = arrFlags[i].split('^').join('');
									//console.log(fval);
									selectedFlags.push(arrFlags[i]);
									
								}
								//console.log(selectedFlags);
								var multiSelectF = $("#acc_service_flag").select2();
								multiSelectF.val(selectedFlags).trigger("change");
							}
//console.log('567');							
						}
						if(res[0].years_list_c != '^^' && res[0].years_list_c != null && acc == 1){
							
							var years = res[0].years_list_c;
							var selectedYears = [];
							if(years){
								arrYears = years.split(',');
								
								for(var i in arrYears) {
									selectedYears.push(arrYears[i]);
								}
								//$('#acc_years').select2('val', selectedYears); 
								var multiSelect = $("#acc_years").select2();
								multiSelect.val(selectedYears).trigger("change");
							}							
						}
						
						var calc = res[0].list_price * res[0].quantity;
							
						if(res[0].discount_amount != ''){
							
							calc = calc - res[0].discount_amount;
						}
						
						//var service_name = '{"id": "' + tempid + '","lp":"' + lp + '","cat":"' + catid + '"}';	
						var service_name = '{"id": "' + tempid + '","lp":"' + lp + '"}';						
						
						if(acc == 0){
							
							if(res[0].service_status_c == "Inactive"){
								
								$('#servicer').attr("disabled", true);
								$('#services_type').attr("disabled", true);
								$('#service_name').attr("disabled", true);
								$('#entity').attr("disabled", true);
							}
							else{
								
								$('#servicer').attr("disabled", false);
								$('#services_type').attr("disabled", false);
								$('#service_name').attr("disabled", false);
								$('#entity').attr("disabled", false);
							}
							
							if(res[0].editable_c == 1){

								$('#discount-amount').attr("disabled", false);
							}
							else{

								$('#discount-amount').attr("disabled", true);
							}

							$('#sale_type').val(res[0].sale_type_c); 
							$('#servicer').val(res[0].servicer); 
							
							if(res[0].revenue_biz_id != '' && res[0].revenue_biz_id != null){
								
								$('#entity').val(res[0].revenue_biz_id); 
							}
							else{
								$('#entity').val("Individual"); 
							}
							
							$('#billed_to').val(res[0].billed_to); 
							$('#commission').val(res[0].commission); 
							$('#services_type').val(res[0].service_type);			
							$('#rec_type').val(res[0].recurring_type_c); 		
							$('#service_date').val(res[0].recurring_date_c); 						
							//$('#service_name').val(service_name); 
							$("#service_name").val(tempid);
							$('#list-price').val(res[0].list_price);
							$('#quantity').val(res[0].quantity);
							$('#description').val(res[0].description);
							$('#discount-amount').val(res[0].discount_amount);
							$('#discount_reason').val(res[0].discount_reason_c);
							$('#calculated-amount').val(calc);
							
							if(res[0].res_reset_c != ''){
							
								$('#reset_rollin').val(res[0].res_reset_c);
								$('#roll_in_div').show;
							}
						}
						else{
							
							if(res[0].service_status_c == "Inactive"){
								
								$('#acc_servicer').attr("disabled", true);
								$('#acc_services_type').attr("disabled", true);
								$('#acc_service_name').attr("disabled", true);
							}
							else{
								
								$('#acc_servicer').attr("disabled", false);
								$('#acc_services_type').attr("disabled", false);
								$('#acc_service_name').attr("disabled", false);
							}
							
							$('#acc_sale_type').val(res[0].sale_type_c); 
							$('#acc_servicer').val(res[0].servicer); 
							
							if(res[0].revenue_biz_id != '' && res[0].revenue_biz_id != null){
								
								$('#acc_entity').val(res[0].revenue_biz_id); 
							}
							else{
								$('#acc_entity').val("Individual"); 
							}
							
							$('#acc_billed_to').val(res[0].billed_to); 
							$('#acc_commission').val(res[0].commission); 
									
							$('#acc_rec_type').val(res[0].recurring_type_c); 		
							$('#acc_service_date').val(res[0].recurring_date_c);
							$("#acc_service_name").val(tempid);
							
							
							if(res[0].res_reset_c != ''){
							
								$('#acc_reset_rollin').val(res[0].res_reset_c);
								$('#acc_roll_in_div').show;
							}
														
							$('#acc_services_type').val(res[0].service_type);
							$('#acc_list-price').val(res[0].list_price);
							$('#acc_quantity').val(res[0].quantity);
							$('#acc_description').val(res[0].description);
							$('#acc_discount-amount').val(res[0].discount_amount);
							$('#acc_discount_reason').val(res[0].discount_reason_c);
							$('#acc_calculated-amount').val(calc);
						}
						
						//console.log(res[0].product_catalog_type_c + ', ' +   res[0].service_type +  ', 1, ' + tempid + ', ' +  catid)
						
						if(acc == 0){
							
							if(res[0].service_status_c == "Inactive"){
								
								getServiceNameDropdownValues(res[0].product_catalog_type_c, res[0].service_type, set=1, tempid, catid, status_check=1);
							}
							else{
								
								getServiceNameDropdownValues(res[0].product_catalog_type_c, res[0].service_type, set=1, tempid, catid);
							}
						}
						else{
							
							if(res[0].service_status_c == "Inactive"){
								
								getServiceNameDropdownValuesAcc(res[0].product_catalog_type_c, res[0].service_type, set=1, tempid, catid, status_check=1);
							}
							else{
								
								getServiceNameDropdownValuesAcc(res[0].product_catalog_type_c, res[0].service_type, set=1, tempid, catid);
							}
						}
					}
					
					var is_package = checkPackage(res[0].service_type, 1);
					
					if(is_package == 1){
						
						$('#package_parent_id').val(rid);
						$('#servicer').attr("disabled", true);
						$('#services_type').attr("disabled", true);
						$('#service_name').attr("disabled", true);
						$('#entity').attr("disabled", true);
						$('#list-price').attr("disabled", true);
					}
					else{
						
						$('#package_parent_id').val('');
						$('#servicer').attr("disabled", false);
						$('#services_type').attr("disabled", false);
						$('#service_name').attr("disabled", false);
						$('#entity').attr("disabled", false);
						$('#list-price').attr("disabled", false);
					}
				}
			})
			
			
			checkChildServices(rid);
		}
	}
	
	function edit_tab_service_child(data){

		var cid = document.getElementById("case_num").value;
		var rid = $(data).closest('tr').attr('id');
		//alert('rid - ' + rid);
		if(rid != ''){
			
			//if(response_array[0]['year_enable_c'] == 1){
			jQuery.ajax({
					
					type: "POST",
					url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_revenue_details_with_id",
					dataType: 'json',
					async: false,
					data: {rowid: rid},
					
					success: function(res) 
					{
						var tempid = res[0].product_template_id;
						var check_fields_array = checkYearDiscount(tempid,child=1);
		//console.log(check_fields_array);
						//list-price
						if(check_fields_array[0]['list_price_editable_c'] == 1){
							
							$(data).closest('tr').find('#cr_lp').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('#cr_lp').attr("disabled",true);
						}
						//years
						if(check_fields_array[0]['year_enable_c'] == 1){ 
							
							$(data).closest('tr').find('.child_row_years').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('.child_row_years').attr("disabled",true);
						}
						//quarters
						if(check_fields_array[0]['period_quarter_c'] == 1){
							
							$(data).closest('tr').find('.child_row_quarters').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('.child_row_quarters').attr("disabled",true);
						}
						//reset  
						if(check_fields_array[0]['res_reset_type_c'] == 1){
							
							$(data).closest('tr').find('#cr_reset').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('#cr_reset').attr("disabled",true);
						}
						//service flag
						if(check_fields_array[0]['resservice_flag_c'] != '' && check_fields_array[0]['resservice_flag_c'] != null){
							
							$(data).closest('tr').find('.child_row_service_flag').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('.child_row_service_flag').attr("disabled",true);
						}
						//state
						if(check_fields_array[0]['state_enable_c'] == 1){
							
							$(data).closest('tr').find('.child_row_state').attr("disabled",false);
						}
						else
						{
							$(data).closest('tr').find('.child_row_state').attr("disabled",true);
						}
					}
			})

			$(data).closest('tr').find('#cr_service_type, #cr_service_name, #cr_desc, #cr_qty').attr("disabled",false);
			
			$(data).closest('tr').find('#tab_edit_child_service').hide();
			$(data).closest('tr').find('#tab_save_child_service').show();
		}
		//getNonPackageServiceTypes();
		/*var cl = $('#child_update_services').attr("class");
		var classes = cl.split(" ");
		var rid1 = classes[2];
		//console.log();
		//console.log(cl + '  ---  ' + rid1)
		$('#child_update_services').removeClass(rid1);  

		var rid = $(data).closest('tr').attr('id');  

		$('#child_update_services').addClass(rid);
		//console.log(rid);

		$('#add_child_services_div').show();

		$('#child_save_services').hide();
		$('#child_update_services').show();

		$('#add_child_service').hide();

		getNonPackageServiceTypes();

		updateChildData(rid);  */
}

$('#show_child_services_div table tbody tr td input').change(function() {

	var rowEdit = $(this).parents('tr');
	console.log(rowEdit);
	//rowEdit.children('.sub').html('Success');
})



function updateChildData(rid){

	if(rid != ''){
		//console.log('rid123 - '  +rid);
		jQuery.ajax({
				
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_revenue_details_with_id",
			dataType: 'json',
			//async: false,
			data: {rowid: rid},
			
			success: function(res) 
			{
				//console.log('updateChildData ');
				//console.log(res);
				//console.log('------------------');
				var service_len = res.length;					
				var tbody = '';
				
				var tempid = res[0].product_template_id;
				checkYearDiscount(tempid,child=1);
				
				var catid = res[0].category_id;
				var lp = res[0].list_price;					
				
				if(service_len) {
					
					if(res[0]['list_price_editable_c'] == 1){
						
						$('#child-list-price').attr("disabled", false);
					}
					else
					{
						$('#child-list-price').attr("disabled", true);
					}
					
					if(res[0].period_quarter_c != '^^' && res[0].period_quarter_c != null ){
						
						var quarters = res[0].period_quarter_c;
						var selectedQuarters = [];
						if(quarters){
							
							var arrQuarter = quarters.split(',');
							
							for(var i in arrQuarter) {
								
								//var qval = arrQuarter[i].split('^').join('');
								//console.log(qval);
								selectedQuarters.push(arrQuarter[i]);
								
							}
							//console.log(selectedYears);
							var multiSelect = $("#child_quarter").select2();
							multiSelect.val(selectedQuarters).trigger("change");
						}	
					}

					if(res[0].resservice_flag_c != '^^' && res[0].resservice_flag_c != null){
						
						var flags = res[0].resservice_flag_c;
						var selectedFlags = [];
						if(flags){
							
							var arrFlags = flags.split(',');
							
							for(var i in arrFlags) {
								
								//var fval = arrFlags[i].split('^').join('');
								//console.log(fval);
								selectedFlags.push(arrFlags[i]);
								
							}
							//console.log(selectedFlags);
							var multiSelectF = $("#child_service_flag").select2();
							multiSelectF.val(selectedFlags).trigger("change");
						}
	//console.log('567');							
					}					
					
					if(res[0].years_list_c != '^^' && res[0].years_list_c != null){
						
						var years = res[0].years_list_c;
						var selectedYears = [];
						if(years){
							arrYears = years.split(',');
							
							for(var i in arrYears) {
								
								selectedYears.push(arrYears[i]);
								
							}
							
							var multiSelect = $("#child_years").select2();
							multiSelect.val(selectedYears).trigger("change");

							

							//$(".select2").val(selectedYears).trigger('change');
						}							
					}

					if(res[0].state_list_c != '^^' && res[0].state_list_c != null){
						
						var states = res[0].state_list_c;
						var selectedStates = [];
						if(states){
							arrStates = states.split(',');
							
							for(var i in arrStates) {
								
								selectedStates.push(arrStates[i]);
								
							}
							
							var multiSelect = $("#child_service_state").select2();
							multiSelect.val(selectedStates).trigger("change");

							//$(".select2").val(selectedYears).trigger('change');
						}							
					}
					
					var calc = res[0].list_price * res[0].quantity;
						
					if(res[0].discount_amount != ''){
						
						calc = calc - res[0].discount_amount;
					}
					
					//var service_name = '{"id": "' + tempid + '","lp":"' + lp + '","cat":"' + catid + '"}';	
					var service_name = '{"id": "' + tempid + '","lp":"' + lp + '"}';						
					console.log(tempid);
					$('#child_services_type').val(res[0].service_type);			
					
					$('#child-list-price').val(res[0].list_price);
					$('#child_quantity').val(res[0].quantity);
					$('#child_description').val(res[0].description);
					
					if(res[0].res_reset_c != ''){
					
						$('#child_reset_rollin').val(res[0].res_reset_c);
						$('#child_roll_in_div').show;
					}						
					
					if(res[0].service_status_c == "Inactive"){
						
						//getChildServiceNameDropdownValues(select_val, select_id, set=0);
						getChildServiceNameDropdownValues(res[0].product_catalog_type_c, res[0].service_type, set=0);
					}
					else{
						
						getChildServiceNameDropdownValues(res[0].product_catalog_type_c, res[0].service_type);
					}
					
					$("#child_service_name").val(tempid);
				}
			}
		})			
	}
}

function checkChildServices(rid){

	var main_service_type_id = $('#services_type').val();

	var is_package = checkPackage(main_service_type_id, 1);

	if(is_package != 1){

		$('#show_child_services_div').hide();
	}
	else{

		$('#show_child_services_div').show();

		jQuery.ajax({
					
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/checkChildRLI",
			dataType: 'json',
			
			data: {parent_id: rid},
			
			success: function(res) 
			{	//console.log(res);
				//var jsonData = $.parseJSON(res);
				var service_len = res.length;
				//console.log(service_len + ' showServicesList- '+res);
				var tbody = '';
				
				$('#pkg_button_div').show();
				$('#add_package').hide();
				//$('#add_child_service').show();
				$('#child_service_box').show();
				$('#add_child_services_div').hide();
				$('#child_services_table').show();
				$('#package_parent_id').val(rid); 
				$('#show_services_div_table').hide(); 

				$('#servicer').attr("disabled", true);
				$('#entity').attr("disabled", true);
				$('#services_type').attr("disabled", true);
				$('#service_name').attr("disabled", true);

				var non_package_services = getNonPackageServiceTypes();	
				
				if(main_initial_services_dropdown != ''){

					var response = $.parseJSON(main_initial_services_dropdown);
					
					var years = response.years;
					var quarters = response.quarters;
					var rollin = response.reset_rollin;
					var state_list = response.state_list;
				}			

				if(service_len) {									

					for (var i = 0; i < service_len; i++) {
						
						var rowid = res[i]['id'];
						$('#child_services_table #' + rowid).remove(); 
					}

					for (var i = 0; i < service_len; i++) {
						
						if(res[i]['description'] == null){
							
							res[i]['description'] = '';
						}

						tbody += "<tr id=" + res[i]['id'] + " disabled>";
						
						if(i == service_len - 1){

							if(res[i]['sales_stage'] != 'Closed Won' && (res[i]['service_reject_date_c'] == '' || res[i]['service_reject_date_c'] == null) ){
							
								tbody += '<td><button class="btn btn-xs btn-edit taddservice_child"  id="tab_add_child_service" title="Add"  style="background-color: #48C6AC; display: inline;" onclick="add_new_child_service(this);"><i class="glyph-icon icon-plus"></i></button>  <button class="btn btn-xs btn-edit teditservice_child"  id="tab_edit_child_service" title="Edit"  style="background-color: #48C6AC;  display: inline;" onclick="edit_tab_service_child(this);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-edit tsaveservice_child"  id="tab_save_child_service" title="Edit"  style="background-color: #48C6AC; display: none;" onclick="save_child_service(this);"><i class="glyph-icon icon-save"></i></button> <button class="btn btn-xs btn-danger tdelservice_child"  title="Delete"  style="background-color: #e74c3c;  display: inline;" onclick="delete_tab_service_child(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
							}
							else{
								
								tbody += '<td></td>';
							}
						}
						else{

							if(res[i]['sales_stage'] != 'Closed Won' && (res[i]['service_reject_date_c'] == '' || res[i]['service_reject_date_c'] == null) ){
							
								tbody += '<td><button class="btn btn-xs btn-edit taddservice_child"  id="tab_add_child_service" title="Add"  style="background-color: #48C6AC; display: none;" onclick="add_new_child_service(this);"><i class="glyph-icon icon-plus"></i></button>  <button class="btn btn-xs btn-edit teditservice_child"  id="tab_edit_child_service" title="Edit"  style="background-color: #48C6AC; display: inline;" onclick="edit_tab_service_child(this);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-edit tsaveservice_child"  id="tab_save_child_service" title="Edit"  style="background-color: #48C6AC; display: none;" onclick="save_child_service(this);"><i class="glyph-icon icon-save"></i></button> <button class="btn btn-xs btn-danger tdelservice_child"  title="Delete"  style="background-color: #e74c3c; display: inline;" onclick="delete_tab_service_child(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
							}
							else{
								
								tbody += '<td></td>';
							}
						}
						
						//Service Type
						tbody += '<td><select class="form-control1 child_row_service_type" name="cr_service_type" id="cr_service_type" onChange="childServiceOnChange(this);" disabled><option value="">Select</option>';

						for(j=0; j< non_package_services.length; j++){

							if(res[i]['service_type'] == non_package_services[j]['id']){
								
								
								tbody += '<option value="' + res[i]['service_type'] + '" selected>' + res[i]['service_name'] + '</option>';
							}
							else{
								
								tbody += '<option value="' + non_package_services[j]['id'] + '">' + non_package_services[j]['name'] + '</option>';
							}
						}
						
						tbody += '</select></td>';

						//Service Name

						var sevice_name_arr = getChildServiceNameDropdownValues(res[i]['service_name'], res[i]['service_type'], set=0);
						//console.log(sevice_name_arr);

						tbody += '<td><select class="form-control1 child_row_service_name" name="cr_service_name" id="cr_service_name" onChange="childServiceOnChange(this);" disabled><option value="">Select</option>';
						
						//var list_price_editable = '';
						var allowed_service_flags = '';
						for(j=0; j< sevice_name_arr.length; j++){

							if(res[i]['service_name_id'] == sevice_name_arr[j]['id']){
								
								allowed_service_flags = sevice_name_arr[j]['resservice_flag_c'] ;
								//list_price_editable = sevice_name_arr[j]['list_price_editable_c'];
								tbody += '<option value="' + res[i]['service_name_id'] + '" selected>' + res[i]['child_service_name'] + '</option>';
							}
							else{
								
								tbody += '<option value="' + sevice_name_arr[j]['id'] + '">' + sevice_name_arr[j]['name'] + '</option>';
							}
						}

						tbody += '</select></td>';

						
						tbody += '<td><input class="form-control1 child_row_desc" type ="text" name="cr_desc" id="cr_desc" value="' + res[i]['description'] + '" disabled></td> ';
						tbody += '<td><input class="form-control1 child_row_list_price" type ="text" name="cr_lp" id="cr_lp" value="' + res[i]['list_price'] + '" disabled></td> ';
						tbody += '<td><input class="form-control1 child_row_qty" type ="number" name="cr_qty" id="cr_qty" value="' + res[i]['quantity'] + '" disabled></td> ';

						//Years					
						tbody += '<td><select class="js-example-basic-single form-control1 child_row_years" name="cr_years[]" id="cr_years' + i + '" multiple="multiple" disabled><option value=""></option>';
						
						var selected_years = '';
						selected_years = res[i]['years_list_c'];

						if(years != ''){
														
							$.each(Object.keys(years).reverse(),function(i,key){
								
								var value = years[key];
								
								/*if(selected_years == value){

									if(value != ''){
									
										tbody += '<option value="' + value + '" selected>' + value + '</option>'; 
									}
								}
								else{*/

									if(value != ''){
									
										tbody += '<option value="' + value + '">' + value + '</option>'; 
									}
								//}
								
							});
						}

						tbody += '</select></td>';

						//Quarters 
						tbody += '<td><select class="js-example-basic-single form-control1 child_row_quarters" name="cr_quarters[]" id="cr_quarters' + i + '" multiple="multiple" disabled>';
						
						var selected_quarters = '';
						selected_quarters = res[i]['period_quarter_c'];
						
						if(quarters != ''){
							
							for (var key in quarters) {
					
								if (quarters.hasOwnProperty(key)){
									
									tbody += '<option value="' + key + '">' + quarters[key] + '</option>';									
								}
							}							
						}

						tbody += '</select></td>';

						//Reset RollIn 
						
						tbody += '<td><select class="form-control1 child_row_reset" name="cr_reset" id="cr_reset" disabled>';
						
						var selected_reset = '';
						selected_reset = res[i]['res_reset_c'];

						if(rollin != ''){
							
							for (var key in rollin) {
					
								if (rollin.hasOwnProperty(key)){
									
									if(selected_reset == key){

										tbody += '<option value="' + key + '" selected>' + rollin[key] + '</option>';
									}
									else{

										tbody += '<option value="' + key + '">' + rollin[key] + '</option>';
									}
								}
							}							
						}

						tbody += '</select></td>';

						//Service Flag  
					
						tbody += '<td><select class="js-example-basic-single form-control1 child_row_service_flag" name="cr_service_flag[]" id="cr_service_flag' + i + '" multiple="multiple" disabled><option value=""></option>';
						
						var selected_flag = '';
						selected_flag = res[i]['resservice_flag_c'];

						//console.log(allowed_service_flags);
						if(allowed_service_flags != '' && allowed_service_flags != null){
							
							var service_flag_text = allowed_service_flags.split('^').join('');

							if(service_flag_text != ''){
									
								var service_flag_array = service_flag_text.split(',');

								if(service_flag_array.length > 0){

									for(var j=0; j< service_flag_array.length; j++){

										/*if(selected_flag == key){

											tbody += '<option value="' + service_flag_array[j] + '" selected>' + service_flag_array[j] + '</option>';
										}
										else{*/

											tbody += '<option value="' + service_flag_array[j] + '">' + service_flag_array[j] + '</option>';
										//}
									}
								}
							}						
						}

						tbody += '</select></td>';
						
						//State
						
						var selected_state = '';
						selected_state = res[i]['state_list_c'];

						tbody += '<td><select class="js-example-basic-single form-control1 child_row_state" name="cr_state[]" id="cr_state' + i + '" multiple="multiple" disabled>';

						if(state_list != ''){
							
							
							for (var key in state_list) {								
								
								if (state_list.hasOwnProperty(key)){
									
									/*if(selected_state == key){

										tbody += '<option value="' + key + '" selected>' + state_list[key] + '</option>';
									}
									else{ */

										tbody += '<option value="' + key + '">' + state_list[key] + '</option>';
									//}
								}
							}							
						}

						tbody += '</select></td>';
						
						tbody += '</tr>';											
					}

					if(tbody != ''){
						
						//$("#child_services_table").append(tbody);
						$("#child_services_table").append(tbody);
					}
					renderServicesSelect2();
					
					for (var i = 0; i < service_len; i++) {

						//Years
						var selected_years = '';
						selected_years = res[i]['years_list_c'];

						if(selected_years != '' && selected_years != null){

							var arrYears = selected_years.split(',');
							var selectedYears = [];

							for(var j in arrYears) {
									
								//var qval = arrQuarter[i].split('^').join('');
								//console.log(qval);
								selectedYears.push(arrYears[j]);
								
							}
										
							$('#cr_years'+i).select2().val(selectedYears).trigger('change'); 
						}

						//Quarters
						var selected_quarters = '';
						selected_quarters = res[i]['period_quarter_c'];

						if(selected_quarters != '' && selected_quarters != null){

							var arrQuarter = selected_quarters.split(',');
							var selectedQuarters = [];

							for(var j in arrQuarter) {
									
								//var qval = arrQuarter[i].split('^').join('');
								//console.log(qval);
								selectedQuarters.push(arrQuarter[j]);
								
							}
										
							$('#cr_quarters'+i).select2().val(selectedQuarters).trigger('change'); 
						}

						//Service Flag
						var selected_flag = '';
						selected_flag = res[i]['resservice_flag_c'];

						if(selected_flag != '' && selected_flag != null){

							var arrFlag = selected_flag.split(',');
							var selectedFlag = [];

							for(var j in arrFlag) {
									
								//var qval = arrQuarter[i].split('^').join('');
								//console.log(qval);
								selectedFlag.push(arrFlag[j]);
								
							}
										
							$('#cr_service_flag'+i).select2().val(selectedFlag).trigger('change'); 
						}

						//State
						var selected_state = '';
						selected_state = res[i]['state_list_c'];

						if(selected_state != '' && selected_state != null){

							var arrState = selected_state.split(',');
							var selectedState = [];

							for(var j in arrState) {
									
								//var qval = arrQuarter[i].split('^').join('');
								//console.log(qval);
								selectedState.push(arrState[j]);
								
							}
										
							$('#cr_state'+i).select2().val(selectedState).trigger('change'); 
						}						
					}
					
				} 
				else{				
					
					add_new_child_service(data = '');						
				}				
				
			}
			})
		}
	}

	function renderServicesSelect2(){
		//alert('renderSelect2')	
		$('.js-example-basic-single').prop('disabled', true);
		$('.js-example-basic-single').select2();
		$(".js-example-basic-single").select2({
		theme: "classic"
		});
	}

	function add_new_child_service(data){

		if(data != ''){

			var previous_row = $(data).parent().parent().attr('id');
			$('#child_services_table #' + previous_row + ' .taddservice_child').hide();
		}		

		var non_package_services = getNonPackageServiceTypes();	
					
		if(main_initial_services_dropdown != ''){

			var response = $.parseJSON(main_initial_services_dropdown);
			
			var years = response.years;
			var quarters = response.quarters;
			var rollin = response.reset_rollin;
			var state_list = response.state_list;
		}

		var tbody = "<tr id='new_cr' >";

		tbody += '<td><button class="btn btn-xs btn-edit taddservice_child"  id="tab_add_child_service" title="Add"  style="background-color: #48C6AC; display: none;" onclick="add_new_child_service(this);"><i class="glyph-icon icon-plus"></i></button>  <button class="btn btn-xs btn-edit teditservice_child" id="tab_edit_child_service"  title="Edit"  style="background-color: #48C6AC;  display: none;" onclick="edit_tab_service_child(this);"><i class="glyph-icon icon-pencil"></i></button> <button class="btn btn-xs btn-edit tsaveservice_child"  id="tab_save_child_service" title="Save"  style="background-color: #48C6AC; display: inline;" onclick="save_child_service(this);"><i class="glyph-icon icon-save"></i></button>  <button class="btn btn-xs btn-danger tdelservice_child"  title="Delete"  style="background-color: #e74c3c;  display: inline;" onclick="delete_tab_service_child(this);"><i class="glyph-icon icon-remove"></i></button></td>'; 

		//Service Type
		tbody += '<td><select class="form-control1 child_row_service_type" name="cr_service_type" id="cr_service_type" onChange="childServiceOnChange(this);"><option value="">Select</option>';

		for(j=0; j< non_package_services.length; j++){

			tbody += '<option value="' + non_package_services[j]['id'] + '">' + non_package_services[j]['name'] + '</option>';
			
		}

		tbody += '</select></td>';					

		//Service Name
		tbody += '<td><select class="form-control1 child_row_service_name" name="cr_service_name" id="cr_service_name" onChange="childServiceOnChange(this);"><option value="">Select</option></select></td>';
		tbody += '<td><input class="form-control1 child_row_desc" type ="text" name="cr_desc" id="cr_desc" value="" ></td> ';

		tbody += '<td><input class="form-control1 child_row_list_price" type ="text" name="cr_lp" id="cr_lp" value="0" disabled></td> ';
		tbody += '<td><input class="form-control1 child_row_qty" type ="text" name="cr_qty" id="cr_qty" value="1" onChange="childServiceOnChange(this);"></td> ';

		//Years

		tbody += '<td><select class="js-example-basic-single form-control1 child_row_years" name="cr_years[]" id="cr_years" multiple="multiple" disabled><option value=""></option>';

		if(years != ''){
														
			$.each(Object.keys(years).reverse(),function(i,key){
				
				var value = years[key];
				if(value != ''){
				
					tbody += '<option value="' + value + '">' + value + '</option>'; 
				}
			});
		}

		tbody += '</select></td>';
							
		//Quarters
		tbody += '<td><select class="js-example-basic-single form-control1 child_row_quarters" name="cr_quarters[]" id="cr_quarters" multiple="multiple" disabled>';						

		if(quarters != ''){
			
			for (var key in quarters) {

				if (quarters.hasOwnProperty(key)){
				
					tbody += '<option value="' + key + '">' + quarters[key] + '</option>';					
				}
			}							
		}

		tbody += '</select></td>';

		//Reset RollIn 
						
		tbody += '<td><select class="form-control1 child_row_reset" name="cr_reset" id="cr_reset" disabled>';

		if(rollin != ''){
			
			for (var key in rollin) {

				if (rollin.hasOwnProperty(key)){

					tbody += '<option value="' + key + '">' + rollin[key] + '</option>';									
				}
			}							
		}

		tbody += '</select></td>';

		//Service Flag
		tbody += '<td><select class="js-example-basic-single form-control1 child_row_service_flag" name="cr_service_flag[]" id="cr_service_flag" multiple="multiple" disabled><option value=""></option></select></td>';
		//State

		tbody += '<td><select class="js-example-basic-single form-control1 child_row_state" name="cr_state" id="cr_state[]" multiple="multiple" disabled>';

		if(state_list != ''){
			
			for (var key in state_list) {

				if (state_list.hasOwnProperty(key)){
					
					tbody += '<option value="' + key + '">' + state_list[key] + '</option>';					
				}
			}							
		}

		tbody += '</select></td>';


		//tbody += '<td><select class="form-control1 child_row_quarters" name="cr_quarters" id="cr_quarters" ><option value="">Select</option></select></td>';
		//tbody += '<td><select class="form-control1 child_row_reset" name="cr_reset" id="cr_reset" ><option value="">Select</option></select></td>';

		//tbody += '<td><select class="form-control1 child_row_state" name="cr_state" id="cr_state" ><option value="">Select</option></select></td>';

		tbody += '</tr>';
						

		if(tbody != ''){
			
			$("#child_services_table").append(tbody);
		}

		$('.js-example-basic-single').select2();
		$(".js-example-basic-single").select2({
		theme: "classic"
		});	
	}

	function childServiceOnChange(data){ 

		var rid = $(data).parent().parent().attr('id');
		var td_id =  $(data).attr('id');
		var td_val =  $(data).val();
		//alert(rid + ', ' + td_id + ', ' + td_val);

		if(td_id == 'cr_service_type'){

			var service_type_id = td_val;
			
			var select_name = $(data).find('option:selected').text(); //$('#child_services_type :selected').text();

			//alert(service_type_id + ', ' + select_name);
			var sevice_name_arr = getChildServiceNameDropdownValues(select_name, service_type_id, set=0);
			
			console.log(sevice_name_arr);

			tbody = '<option value="">Select</option>';
			
			//var list_price_editable = '';
			var allowed_service_flags = '';
			for(j=0; j< sevice_name_arr.length; j++){
					
				tbody += '<option value="' + sevice_name_arr[j]['id'] + '">' + sevice_name_arr[j]['name'] + '</option>';				
			}
			//console.log(td_id);
			
			$('tr#' + rid + ' td:nth-child(3)').find('select').empty();
			$('tr#' + rid + ' td:nth-child(3)').find('select').append(tbody);

				//empty other values
			$('#years').val(null).trigger('change');

			$(data).closest('tr').find('#cr_lp').val(0);
			$(data).closest('tr').find('.child_row_years').val('').trigger('change');
			$(data).closest('tr').find('.child_row_quarters').val('').trigger('change');
			$(data).closest('tr').find('#cr_reset').val('');
			$(data).closest('tr').find('.child_row_service_flag').val('').trigger('change');
			$(data).closest('tr').find('.child_row_state').val('').trigger('change');
		}

		//Service Name Change
		if(td_id == 'cr_service_name'){

			/*$(data).closest('tr').find('.child_row_years').val('').trigger('change');
			$(data).closest('tr').find('.child_row_quarters').val('').trigger('change');
			$(data).closest('tr').find('#cr_reset').val('');
			$(data).closest('tr').find('.child_row_service_flag').val('').trigger('change');
			$(data).closest('tr').find('.child_row_state').val('').trigger('change');*/

			var service_name_id = td_val;

			var check_fields_array = checkYearDiscount(service_name_id,child=1);

			if(service_name_id != ''){
				
				var filter_val = main_service_names_array.filter(x => x.id === service_name_id);
				var price = filter_val[0]['list_price'];
			}			

			if(check_fields_array[0]['list_price_editable_c'] == 1){
							
				$(data).closest('tr').find('#cr_lp').attr("disabled",false);
				$(data).closest('tr').find('#cr_lp').val(price);
			}
			else
			{
				$(data).closest('tr').find('#cr_lp').attr("disabled",true);
				$(data).closest('tr').find('#cr_lp').val(price);
			}
			//years
			if(check_fields_array[0]['year_enable_c'] == 1){
				
				$(data).closest('tr').find('.child_row_years').attr("disabled",false);
			}
			else
			{
				$(data).closest('tr').find('.child_row_years').val('').trigger('change');
				$(data).closest('tr').find('.child_row_years').attr("disabled",true);				
			}
			//quarters
			if(check_fields_array[0]['period_quarter_c'] == 1){
				
				$(data).closest('tr').find('.child_row_quarters').attr("disabled",false);
			}
			else
			{
				$(data).closest('tr').find('.child_row_quarters').val('').trigger('change');
				$(data).closest('tr').find('.child_row_quarters').attr("disabled",true);				
			}
			//reset  
			if(check_fields_array[0]['res_reset_type_c'] == 1){
				
				$(data).closest('tr').find('#cr_reset').attr("disabled",false);
			}
			else
			{
				$(data).closest('tr').find('#cr_reset').val('');
				$(data).closest('tr').find('#cr_reset').attr("disabled",true);				
			}
			//service flag
			if(check_fields_array[0]['resservice_flag_c'] != '' && check_fields_array[0]['resservice_flag_c'] != null){
				
				$(data).closest('tr').find('.child_row_service_flag').attr("disabled",false);

				var service_flag_text = check_fields_array[0]['resservice_flag_c'].split('^').join('');
								
				if(service_flag_text != ''){
					
					var service_flag_array = service_flag_text.split(',');
					//console.log(reason_array);
					if(service_flag_array.length > 0){
						
						var html = '<option value=""></option>';
						
						for(var i=0; i< service_flag_array.length; i++){
							
							html += '<option value="' + service_flag_array[i] + '">' + service_flag_array[i] + '</option>';
						}	
						$('tr#' + rid + ' td:nth-child(10)').find('select').empty();
						$('tr#' + rid + ' td:nth-child(10)').find('select').append(html);					
					}					
				}				
			}
			else
			{
				$(data).closest('tr').find('.child_row_service_flag').val('').trigger('change');
				$(data).closest('tr').find('.child_row_service_flag').attr("disabled",true);				
			}
			//state
			if(check_fields_array[0]['state_enable_c'] == 1){
					
					$(data).closest('tr').find('.child_row_state').attr("disabled",false);
			}
			else
			{
				$(data).closest('tr').find('.child_row_state').val('').trigger('change');
				$(data).closest('tr').find('.child_row_state').attr("disabled",true);				
			}

			//console.log(service_name_id);
			//console.log(check_fields_array);
			//alert('change - ' + service_id);
				
				
				//console.log(price);
		}

		//Quantity Change
		/*if(td_id == 'cr_qty'){

			var quantity = td_val;
			var list_price = $(data).closest('tr').find('#cr_lp').val();
			alert(quantity);
			var total = 
		}*/

	}

	function showServicesList(){
		
		var cid = document.getElementById("case_num").value;
		
		if(cid != ''){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_services_list",
				dataType: 'json',
				async: false,
				data: {caseid: cid},
				
				success: function(res) 
				{	
					//var jsonData = $.parseJSON(res);
					var service_len = res.length;
					//console.log(service_len + ' showServicesList- '+res);
					var tbody = '';
					var acc_tbody = '';
				
					if(service_len) {
						
						for (var i = 0; i < service_len; i++) {
							
							var rowid = res[i]['id'];
							$('#services_table #' + rowid).remove(); 
							$('#acc_services_table #' + rowid).remove(); 
						}
						
						for (var i = 0; i < service_len; i++) {
							
							if(res[i]['discount_amount'] == null){
								
								res[i]['discount_amount'] = '';
							}
														
							tbody += "<tr id=" + res[i]['id'] + ">";
							acc_tbody += "<tr id=" + res[i]['id'] + ">";
							
							//Check If Package 
							if(res[i]['is_package_c'] == 1){
								//console.log(res[i]['childServices']); 
								var child_array = [];
								var child_list = '';
								if(res[i]['childServices'] != '' && res[i]['childServices'] != null){

									child_array = res[i]['childServices'] .split(','); //.join('<br>'); 
									//console.log(child_array);
									//console.log(child_array.length);
									
									for(j=0; j<child_array.length; j++){

										child_list += child_array[j] + "\n"; 
									}
									
								}
								//console.log(child_list);
								if(child_list != ''){
								
									var info_icon = '<span class="fa fa-info-circle" data-toggle="tooltip" title="' + child_list + '"></span>';
									//var info_icon = '<i class="glyph-icon icon-pencil" data-toggle="tooltip" title="' + child_list + '"></i>';
								}
								else{

									var info_icon = '<span class="fa fa-info-circle" data-toggle="tooltip" title="No Service"></span>';
								}
							}
							else{

								var info_icon = '';
							}

							if(res[i]['sales_stage'] != 'Closed Won' && (res[i]['service_reject_date_c'] == '' || res[i]['service_reject_date_c'] == null) ){
								
								//tbody += '<td><button class="btn btn-xs btn-edit teditservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service(this);"><i class="glyph-icon icon-pencil"></i></button> &nbsp; &nbsp;<button class="btn btn-xs btn-danger tdelservice"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button> &nbsp; ' + info_icon + '</td>'; //id="' + res[i]['id'] + '"
								
								tbody += '<td ><button class="btn btn-xs btn-edit teditservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service(this);"><i class="glyph-icon icon-pencil"></i></button>&nbsp;<button class="btn btn-xs btn-danger tdelservice"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button>&nbsp;' + info_icon + '</td>'; //id="' + res[i]['id'] + '"

								acc_tbody += '<td><button class="btn btn-xs btn-edit editservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_acc_service(this);"><i class="glyph-icon icon-pencil"></i></button>&nbsp; &nbsp;<button class="btn btn-xs btn-danger delservice"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></td>'; 
								
								//tbody += '<td><button class="btn btn-xs btn-danger tdelservice"  title="Delete"  style="background-color: #48C6AC;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
								
								//acc_tbody += '<td><button class="btn btn-xs btn-danger delservice"  title="Delete"  style="background-color: #48C6AC;" onclick="delete_acc_service(this);"><i class="glyph-icon icon-remove"></i></button></td>';
							}
							else{
								
								tbody += '<td></td>';
								acc_tbody += '<td></td>';
							}
							var calc = (res[i]['list_price'] * res[i]['quantity']) - res[i]['discount_amount'];
							
							/*if(res[i]['discount_amount'] != ''){
								
								calc = calc - res[i]['discount_amount'];
							}*/
							if(res[i]['sold_by'] == null){
								
								res[i]['sold_by'] = '';
							}
							if(res[i]['approved_by'] == null){
								
								res[i]['approved_by'] = '';
							}
							if(res[i]['description'] == null){
								
								res[i]['description'] = '';
							}
							if(res[i]['discount_reason_c'] == null){
								
								res[i]['discount_reason_c'] = '';
							}
							//alert(res[i]['name']);
							tbody += '<td>' + res[i]['sale_type'] + '</td> ';
							
							if(res[i]['lead_from_c'] != null){
								
								tbody += '<td>' + res[i]['lead_from_c'] + '</td> ';
							}
							else{
								
								tbody += '<td></td>';
							}
							tbody += '<td>' + res[i]['name'] + '</td> '
									+ '<td>' + res[i]['quantity'] + '</td> '
									+ '<td>' + res[i]['list_price'] + '</td> '
									+ '<td>' + res[i]['discount_reason_c'] + '</td> ';
									
							if(res[i]['discount_amount'] != null){
								
								tbody += '<td>' + res[i]['discount_amount'] + '</td> ';
							}
							else{
								
								tbody += '<td></td> ';
							}
							tbody += '<td>' + calc + '</td> '
									+ '<td>' + res[i]['created_by'] + '</td> '
									+ '<td>' + res[i]['date_entered'] + '</td> '
									+ '<td></td>'
									+ '<td>' + res[i]['sold_by'] + '</td>'
									+ '<td>' + res[i]['approved_by'] + '</td>';
									
							acc_tbody += '<td>' + res[i]['sale_type'] + '</td> ';
							
							if(res[i]['lead_from_c'] != null){
								
								acc_tbody += '<td>' + res[i]['lead_from_c'] + '</td> ';
							}
							else{
								
								acc_tbody += '<td></td>';
							}
							acc_tbody += '<td>' + res[i]['name'] + '</td> '
									+ '<td>' + res[i]['quantity'] + '</td> '
									+ '<td>' + res[i]['list_price'] + '</td> '
									+ '<td>' + res[i]['discount_reason_c'] + '</td> '
									+ '<td>' + res[i]['discount_amount'] + '</td> '
									+ '<td>' + calc + '</td> '
									+ '<td>' + res[i]['created_by'] + '</td> '
									+ '<td>' + res[i]['date_entered'] + '</td> '
									+ '<td></td>'
									+ '<td>' + res[i]['sold_by'] + '</td>'
									+ '<td>' + res[i]['approved_by'] + '</td>';
									
							if(res[i]['dateapproved_c']){
								
								tbody +=  '<td>' + res[i]['dateapproved_c'] + '</td> ';
								acc_tbody += '<td>' + res[i]['dateapproved_c'] + '</td> ';
							}
							else{
								
								tbody += '<td></td>';
								acc_tbody += '<td></td>';
							}
							tbody += '<td>' + res[i]['description'] + '</td> '
									+ '</tr>';
							acc_tbody += '<td>' + res[i]['description'] + '</td> '
									+ '</tr>';
						}
						if(tbody != ''){
							
							$("#services_table").append(tbody);
							$("#acc_services_table").append(acc_tbody);
						}
						
					}
				}
			})
		}
	}	
	
	function delete_tab_service(data){
		
		var rid = $(data).closest('tr').attr('id'); 
		
		if(rid != ''){
			
			swal('Are you sure to delete this service?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					delete_rli(rid);
				}
			})
		}
	}
	
	function delete_tab_service_child(data){

		var rid = $(data).closest('tr').attr('id'); 
		var parent_id = $('#package_parent_id').val();

		var previous = $(data).parent().parent().prev('tr').attr('id');
		
		if(rid != ''){
			
			swal('Are you sure to delete this service?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					delete_rli_child(rid, parent_id, previous);					
				}
			})
		}
	}
	
	function delete_rli(rid, child=''){
		
		var parent_id = '';	

		if(rid != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/delete_revenue_line_item",
				//dataType: 'json',
				data: {revenue_id: rid, child: 0, parent_id: parent_id}, //, child: child
				success: function(response) 
				{	
		
					if(response == true){
						

						$("#services_table #" + rid).remove();
						$("#acc_services_table #" + rid).remove(); 
											
						
						$.jGrowl("Service Deleted Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to delete service", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-red'
						}); 
					}
				}
			})
		}
	}

	function delete_rli_child(rid, parent_id, previous){	

		if(rid != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/delete_revenue_line_item",
				async: false,
				data: {revenue_id: rid, child: 1, parent_id: parent_id}, //, child: child
				success: function(response) 
				{	
		
					if(response == true){
						
						$("#child_services_table #" + rid).remove(); 						

						//var nextid = $(rid).parent().parent().next('tr').attr('id');
						
						if(previous != ''){
							
							$('#child_services_table #' + previous + ' #tab_add_child_service').show();
						}
						

						$.jGrowl("Service Deleted Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});

						var total_parent = checkTotalChildServiceAmt(parent_id);
						$('#list-price').val(total_parent);
						$('#calculated-amount').val(total_parent); 
					}
					else{
						
						$.jGrowl("Unable to delete service", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-red'
						}); 
					}
				}
			})
		}

		checkChildServices(parent_id);
	}
	
	function getInitialServices(){
		
		var self = this;
		jQuery.ajax({
			
			type: "POST",
			//url: "<?php echo $sugar_url;?>" + "index.php?entryPoint=CaseReviewServices",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/getCRServicesDD",
			//dataType: 'json',
			data: {func: 'initial_dd'},
			async:false,
			success: function(response) 
			{				
				if(response != ''){
					//console.log('RESPONSE SUGAR - ' + response);
					self.initial_services_dropdown = response;
				}
			}
		})
		main_initial_services_dropdown = self.initial_services_dropdown;
		
	}
	
	function checkBusinessServiceLink(){
		
		var cid = document.getElementById("case_num").value;
		var self = this;
		
		if(cid != ''){
			
			jQuery.ajax({
				
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/check_business_exist",
				data: {caseid: cid},
				async:false,
				success: function(response) 
				{ //alert('response - ' + response);				
					if(response != '' && response != 0){
						
						self.business_services = response;
						self.business_exist = 1;
					}
				}
			})
			main_business_services = self.business_services;
			main_business_exist = self.business_exist;
		}
	}
	
	function getInitialServicesDropdown(){
					
		if(main_initial_services_dropdown != ''){ 
			//console.log('main_initial_services_dropdown - '+main_initial_services_dropdown);
			var response = $.parseJSON(main_initial_services_dropdown);
			
			var sale_type = response.sale_type;
			//var discount_reason = response.discount_reason;
			var years = response.years;
			var service_type = response.service_type;
			var quarters = response.quarters;
			var rollin = response.reset_rollin;
			var state_list = response.state_list;
			//var users  = response.users;
			//console.log(main_business_services);
			
			var entity_div = '<option value="Individual">Individual</option>'; 
			
			if(main_business_services != null && main_business_services != '' && main_business_services != 0){
				
				var biz_array = JSON.parse(main_business_services);
				
				
				var i=0;
				
				for (i=0; i<biz_array.length; i++) {
					
					entity_div += '<option value="' + biz_array[i]['id'] + '">' + biz_array[i]['name'] + '</option>';
				}				
			}
			
			$('#entity').html(entity_div);
			$('#billed_to').html(entity_div);
			
			$('#acc_entity').html(entity_div);
			$('#acc_billed_to').html(entity_div);
				
			if(sale_type != ''){
				
				var sale_type_div = ''; 
				
				for (var key in sale_type) {
					
					if (sale_type.hasOwnProperty(key)){
						
						if(sale_type[key] == 'Case Review')
						{
							sale_type_div += '<option value="' + key + '" selected>' + sale_type[key] + '</option>';
						}
						else
						{
							sale_type_div += '<option value="' + key + '">' + sale_type[key] + '</option>';
						}
					}
				}
				
				$('#sale_type').html(sale_type_div);
				$('#acc_sale_type').html(sale_type_div);
			}
			
			if(service_type != ''){
				//console.log('service_type - ' + service_type);
				var service_type_div = '<option value=""></option>'; 
				
				for (var key in service_type) {
					
					if (service_type.hasOwnProperty(key)){
						
						/*if(service_type[key] == 'Investigation')
						{
							service_type_div += '<option value="' + key + '" selected>' + service_type[key] + '</option>';
						}
						else
						{
							service_type_div += '<option value="' + key + '">' + service_type[key] + '</option>';
						}*/
						service_type_div += '<option value="' + key + '">' + service_type[key] + '</option>';
						//console.log(key + '   -----   ' + service_type[key]);
					}
				}
				
				$('#services_type').html(service_type_div);
				$('#acc_services_type').html(service_type_div);
			}	
			
			
			if(rollin != ''){
				//console.log('service_type - ' + service_type);
				var rollin_div = ''; //'<option value=""></option>'; 
				
				for (var key in rollin) {
					
					if (rollin.hasOwnProperty(key)){
						
						rollin_div += '<option value="' + key + '">' + rollin[key] + '</option>';
					}
				}
				
				$('#reset_rollin').html(rollin_div);
				$('#child_reset_rollin').html(rollin_div);
				$('#acc_reset_rollin').html(rollin_div);
			}
			
			if(quarters != ''){
				//console.log('quarters - ' + quarters);
				var quarters_div = '<option value=""></option>'; 
				
				for (var key in quarters) {
					
					if (quarters.hasOwnProperty(key)){
						
						quarters_div += '<option value="' + key + '">' + quarters[key] + '</option>';
					}
				}
				
				$('#quarter').html(quarters_div);
				$('#child_quarter').html(quarters_div);
				$('#acc_quarter').html(quarters_div);
			}
			
			if(state_list != ''){
				//console.log('quarters - ' + quarters);
				var state_div = '<option value=""></option>'; 
				
				for (var key in state_list) {
					
					if (state_list.hasOwnProperty(key)){
						
						state_div += '<option value="' + key + '">' + state_list[key] + '</option>';
					}
				}
				
				$('#service_state').html(state_div);
				$('#child_service_state').html(state_div);
				//$('#acc_quarter').html(quarters_div);
			}
			/*if(discount_reason != ''){
				
				var discount_reason_div = ''; 
				
				for (var key in discount_reason) {
					
					if (discount_reason.hasOwnProperty(key)){
						
						discount_reason_div += '<option value="' + key + '">' + discount_reason[key] + '</option>';
					}
				}
				
				$('#discount_reason').html(discount_reason_div);
				$('#acc_discount_reason').html(discount_reason_div);
			}*/
			
			if(years != ''){
				
				var years_div = ''; 
				/*for (var key in years) {
					
					if (years.hasOwnProperty(key)){
						
						//years_div += '<option value="' + key + '">' + years[key] + '</option>';
					}
				}*/
				$.each(Object.keys(years).reverse(),function(i,key){
						
					var value = years[key];
						
					if(value != ''){
							
						years_div += '<option value="' + value + '">' + value + '</option>'; 
					}
				});

				$('#years').html(years_div);
				$('#child_years').html(years_div);
				$('#acc_years').html(years_div);
				
			}
		} 
					
		$('.js-example-basic-single').select2();
		$(".js-example-basic-single").select2({
			theme: "classic"
		});			
		
		//console.log('outside -  ' + this.initial_services_dropdown);
		//console.log('outside2 -  ' + self.initial_services_dropdown);
	}
	
	$(document.body).on('change','#services_type',function(){
		//alert('change - services_type'); 
		var select_val = $('#services_type :selected').text();
		var select_id = this.value;
		$('#service_name').html('');
		$('#discount_reason').html('');
				
		//$('#years').empty();
		//$('#quarter').empty();
		//$('#reset_rollin').empty();
		//$('#service_flag').empty();
		$('#years').val(null).trigger('change');
		$('#quarter').val(null).trigger('change');
		$('#reset_rollin').val(null).trigger('change');
		$('#service_flag').val(null).trigger('change');
		$('#service_date').val('');
		$('#discount-amount').val('');
		$('#calculated-amount').val('');
		$('#rec_type').val('');
		
		$('#rec_type_div').hide();
		$('#year_main_div').hide();
		$('#service_date_main_div').hide();
		$('#quarter_div').hide();
		$('#roll_in_div').hide();
		$('#flag_div').hide();
		
		$('#list-price').val(0);
		$('#quantity').val(1);
		$('#discount-amount').val('');
		$('#discount_reason').val('');
		$('#calculated-amount').val('');
		
		/*if(select_val == 'Preparation')
		{
			$('#year_main_div').show();
		}
		else
		{
			$('#year_main_div').hide();
		}*/
		
		var ret = checkPackage(select_id);


		if(select_id != ''){
			
			getServiceNameDropdownValues(select_val, select_id, set=0);
		}		
	})
	
	$(document.body).on('change','#servicer',function(){
		//alert('servicer');
		var select_val = $('#servicer :selected').text();
		var entity = $('#entity :selected').text();
		//alert(entity + ', ' + select_val);
		//return false;
		$('#service_name').empty();
		$('#years').val(null).trigger('change');
		$('#quarter').val(null).trigger('change');
		$('#reset_rollin').val(null).trigger('change');
		$('#service_flag').val(null).trigger('change');
		$('#service_date').val('');
		$('#discount-amount').val('');
		$('#calculated-amount').val('');
		$('#rec_type').val('');
		$('#list-price').val(0);
		$('#quantity').val(1);
		$('#discount_reason').val('');

		$('#rec_type_div').hide();
		$('#year_main_div').hide();
		$('#service_date_main_div').hide();
		$('#quarter_div').hide();
		$('#roll_in_div').hide();
		$('#flag_div').hide();
		
		if(select_val != ''){
			
			checkServicer(select_val, entity);
	
		}		
	})
	
	function checkPackage(service_type_id, edit=''){

		var self = this;
		self.result = 0;

		if(service_type_id != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/checkPackageOption",
				data: {service_type: service_type_id}, 
				async: false,
				success: function(response) 
				{
					self.result = response;	
					if(response == 1){
						//console.log(response)
						$('#pkg_button_div').show();

						if(edit == 1){

							$('#add_package').hide();
							$('#add_child_service').show();
						}
						else{

							$('#add_package').show();
							$('#add_child_service').hide();
						}
						
							
						$('#show_services_div_table').hide();	
						$('#quantity').attr("disabled", true); 											
					}
					else{

						$('#add_package').hide();
						$('#show_services_div_table').hide();
						$('#quantity').attr("disabled", false); 		
					}
				}
			})
			return self.result;
		}
	}

	function checkTotalChildServiceAmt(parent_id){

		var self = this;
		//self.total_amt = 0;

		if(parent_id != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/getParentServicePrice",
				data: {parent_id: parent_id}, 
				async: false,
				success: function(response) 
				{
					console.log(response);

					if(response != ''){

						var amt_array = $.parseJSON(response);
						//console.log(amt_array);
						
						self.total_amt = amt_array['price'];
					}
					else{

						self.total_amt = 0;
					}										
				}
			})
			//alert(self.total_amt);
			//var amt = parseFloat(self.total_amt);
			//alert(amt); 
			console.log('self.total_amt' + self.total_amt);	
			return self.total_amt;
		}
	}

	function checkServicer(select_val, entity){
		
		if(select_val != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_service_types",
				data: {related_to: select_val, status_check: 0, entity: entity}, 
				async: false,
				success: function(response) 
				{
					
					if(response != ''){
						//console.log(response)
						var service_names = $.parseJSON(response);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
																
									
									/*if(stid == id && set == 1){ 
									
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{*/
																				
										service_names_div += '<option value="' + service_names[key]['id'] + '">' + service_names[key]['name'] + '</option>';
									//}
								}
							}
							
							$('#services_type').html(service_names_div);
							//$('#acc_services_type').html(service_names_div);
						}
					}
				}
			})
		}
	}
	
	$(document.body).on('change','#entity',function(){
		
		var entity = $('#entity :selected').val();
		var servicer = $('#servicer :selected').val();
		var services_type = $('#services_type :selected').val();
		
		$('#service_name').empty();
		$('#years').val(null).trigger('change');
		$('#quarter').val(null).trigger('change');
		$('#reset_rollin').val(null).trigger('change');
		$('#service_flag').val(null).trigger('change');
		$('#service_date').val('');
		$('#discount-amount').val('');
		$('#calculated-amount').val('');
		$('#rec_type').val('');
		
		$('#rec_type_div').hide();
		$('#year_main_div').hide();
		$('#service_date_main_div').hide();
		$('#quarter_div').hide();
		$('#roll_in_div').hide();
		$('#flag_div').hide();
		
		checkServicer(servicer, entity);
		
		//alert(entity + ', ' + services_type);
		//return false;
		//if(entity != 'Individual' && services_type != ''){
			
			//getServiceNameDropdownValues(sval='',services_type, set='')
			//alert(status_check);
			/*var self = this;
			var related_to = $('#servicer').val();
			var entity = $('#entity').val();
			alert(sid + ', ' + related_to + ', ' + status_check + ', ' + entity );*/
			
	
		//}		
	})
	
	function updateServicerRelatedDropdown(servicer, entity, status){
		
		//console.log('updateServicerRelatedDropdown - ' + servicer);
		if(entity == '' || entity == null){
			
			entity = 'Individual';
		}
		if(status == "Inactive"){
			
			status = 1;
		}
		else{
			
			status = 0;
		}
		//alert(entity);
		if(servicer != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_service_types",
				data: {related_to: servicer, status_check: status, entity: entity}, 
				async: false,
				success: function(response) 
				{
					//console.log('updateServicerRelatedDropdown - ' );
					if(response != ''){
						//console.log(response)
						var service_names = $.parseJSON(response);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
																
									
									/*if(stid == id && set == 1){ 
									
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{*/
																				
										service_names_div += '<option value="' + service_names[key]['id'] + '">' + service_names[key]['name'] + '</option>';
									//}
								}
							}
							
							$('#services_type').html(service_names_div);
							$('#acc_services_type').html(service_names_div);
						}
					}
				}
			})
	
		}
	}
	
	//accordion
	$(document.body).on('change','#acc_services_type',function(){
		
		var select_val = $('#acc_services_type :selected').text();
		var select_id = this.value;
		$('#acc_service_name').html('');
		$('#acc_service_name').html('');
		$('#acc_discount_reason').html('');
		$('#cc_year_main_div').hide();
		/*if(select_val == 'Preparation')
		{
			$('#acc_year_main_div').show();
		}
		else{
			$('#acc_year_main_div').hide();
		}*/
		
		if(select_id != ''){
			
			getServiceNameDropdownValuesAcc(select_val, select_id, set=0);
		}		
	})
	
	$(document.body).on('change','#acc_servicer',function(){
		
		var select_val = $('#acc_servicer :selected').text();
		
		if(select_val != ''){
			
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_service_types",
				data: {related_to: select_val, status_check: 0}, 
				async: false,
				success: function(response) 
				{
					
					if(response != ''){
						//console.log(response)
						var service_names = $.parseJSON(response);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
																
									
									/*if(stid == id && set == 1){ 
									
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{*/
																				
										service_names_div += '<option value="' + service_names[key]['id'] + '">' + service_names[key]['name'] + '</option>';
									//}
								}
							}
							
							//$('#services_type').html(service_names_div);
							$('#acc_services_type').html(service_names_div);
						}
					}
				}
			})
	
		}		
	})
	
	$(document.body).on('change','#acc_entity',function(){
		
		var entity = $('#acc_entity :selected').val();
		var services_type = $('#acc_services_type :selected').val();
		//alert(entity + ', ' + services_type);
		//return false;
		//if(entity != 'Individual' && services_type != ''){
			
			getServiceNameDropdownValuesAcc(sval='',services_type, set='')
			//alert(status_check);
			/*var self = this;
			var related_to = $('#servicer').val();
			var entity = $('#entity').val();
			alert(sid + ', ' + related_to + ', ' + status_check + ', ' + entity );*/
			
	
		//}		
	})
	
	function getServiceNameDropdownValuesAcc(sval,sid, set, stid, scat, status_check = 0){
		//alert(status_check);
		var self = this;
		var related_to = $('#acc_servicer').val();
		var entity = $('#acc_entity').val();
		//alert(sid + ', ' + related_to + ', ' + status_check + ', ' + entity );
		//return false;
		if(sid != ''){
			//console.log('sid - ' + sid);
			jQuery.ajax({
			
				type: "POST",
				//url: "<?php echo $sugar_url;?>" + "index.php?entryPoint=CaseReviewServices",
				//dataType: 'json',
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_all_services",
				data: {type_id: sid, related_to: related_to, status_check: status_check, entity: entity}, 
				async: false,
				success: function(response) 
				{
					//alert('RESPONSE - ' + response)
					if(response != ''){
						
						var service_names = $.parseJSON(response);
						self.service_name_arr = service_names;
						//console.log('self.service_name_arr1 - ' + self.service_name_arr);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
									
									var id = service_names[key]['id'];
									var lp = service_names[key]['list_price'];
									var cat = service_names[key]['category_id'];
									//var opt = '{"id": "' + id + '","lp":"' + lp + '","cat":"' + cat + '"}';
									var opt = '{"id": "' + id + '","lp":"' + lp + '"}';
									
									//console.log('opt ' +opt);
									
									
									if(stid == id && set == 1){ //&& scat == cat
									//console.log('if');
										
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{
										
										service_names_div += '<option value="' + id + '">' + service_names[key]['name'] + '</option>';
									}
								}
							}
							
							//$('#service_name').html(service_names_div);
							$('#acc_service_name').html(service_names_div);
						}
					}
				}
			})
			main_service_names = self.service_name_arr;
			//console.log('self.service_name_arr2 - '+main_service_names);
		}
	}
	
	function getServiceNameDropdownValues(sval,sid, set, stid, scat, status_check = 0){
		//alert(status_check);
		var self = this;
		var related_to = $('#servicer').val();
		var entity = $('#entity').val();
		//alert(sid + ', ' + related_to + ', ' + status_check + ', ' + entity );
		//return false;
		if(sid != ''){
			//console.log('sid - ' + sid);
			jQuery.ajax({
			
				type: "POST",
				//url: "<?php echo $sugar_url;?>" + "index.php?entryPoint=CaseReviewServices",
				//dataType: 'json',
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_all_services",
				data: {type_id: sid, related_to: related_to, status_check: status_check, entity: entity}, 
				async: false,
				success: function(response) 
				{
					//alert('RESPONSE - ' + response)
					if(response != ''){
						
						var service_names = $.parseJSON(response);
						self.service_name_arr = service_names;
						//console.log('self.service_name_arr1 - ' + self.service_name_arr);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
									
									var id = service_names[key]['id'];
									var lp = service_names[key]['list_price'];
									var cat = service_names[key]['category_id'];
									//var opt = '{"id": "' + id + '","lp":"' + lp + '","cat":"' + cat + '"}';
									var opt = '{"id": "' + id + '","lp":"' + lp + '"}';
									
									//console.log('opt ' +opt);
									
									
									if(stid == id && set == 1){ //&& scat == cat
									//console.log('if');
										//alert('hello - ' + service_names[key]['name']);
										//console.log('CHECK- '+stid + ', ' + id + ' and ' + scat + ', ' + cat)
										
										//service_names_div += '<option value=\'' + opt + '\' selected>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										
										//service_names_div += '<option value="' + id + '" selected>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{
										//console.log('else');
										//alert(service_names[key]['name']);
										/*if(service_names[key]['name'] == 'Federal Personal'){
											
											service_names_div += '<option value=\'' + opt + '\' selected>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										}
										else{
											
											service_names_div += '<option value=\'' + opt + '\'>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										}*/
										//service_names_div += '<option value=\'' + opt + '\'>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										
										service_names_div += '<option value="' + id + '">' + service_names[key]['name'] + '</option>';
										
										//service_names_div += '<option value="' + id + '">' + sval + ' - ' +service_names[key]['name'] + '</option>';
									}
								}
							}
							
							$('#service_name').html(service_names_div);
							//$('#acc_service_name').html(service_names_div);
						}
					}
				}
			})
			main_service_names = self.service_name_arr;
			//console.log('self.service_name_arr2 - '+main_service_names);
		}
	}
	
	function mainServiceNameValues(){
		
		var self = this;
		
		jQuery.ajax({
		
			type: "POST",
			
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_all_services_without_type",
			data: {}, 	
			async: false,
			success: function(response) 
			{
				
				if(response != ''){
					
					var service_names = $.parseJSON(response);
					//console.log('mainServiceNameValues - ' + response);
					self.main_service_name_arr = service_names;
				}
			}
		})
		//console.log('mainServiceNameValues2 - ' + self.main_service_name_arr);
		main_service_names_array = self.main_service_name_arr;
	}
	
	$(document.body).on('change','#service_name',function(){
		
		var select_val = $('#services_type :selected').text();
		var service_id = this.value;
		
		//$('#years').empty();
		//$('#quarter').empty();
		//$('#reset_rollin').empty();
		//$('#service_flag').empty();
		
		$('#years').val(null).trigger('change');
		$('#quarter').val(null).trigger('change');
		$('#reset_rollin').val(null).trigger('change');
		$('#service_flag').val(null).trigger('change');
		
		$('#discount-amount').val('');
		$('#calculated-amount').val('');
		$('#rec_type').val('');
		
		checkRecurring(service_id);
		checkYearDiscount(service_id);
		//alert('change - ' + service_id);
		
		if(service_id != ''){
			
			var filter_val = main_service_names_array.filter(x => x.id === service_id);
			var price = filter_val[0]['list_price'];
		}
		
		//var options = JSON.parse(select_id);
		//var price = options.lp;
		//console.log(options.lp);
		if(price > 0){
			$('#list-price').val(price);
			$('#calculated-amount').val(price);
		}
		else{
			$('#list-price').val(0);
			$('#calculated-amount').val(0);
		}

		/*var ret = checkPackage(service_id);

		if(ret == 1){

			$('#list-price').attr('disabled',true);
		}
		else{

			$('#list-price').attr('disabled',false);
		}*/

		$('#quantity').val(1);
		$('#discount-amount').val('');
		$('#discount_reason').val('');
	})

	$(document.body).on('change','#quantity',function(){
		
		var qty = $('#quantity').val();
		var price = $('#list-price').val(); 
		
		if(price > 0){

			var total = qty * price;
			$('#calculated-amount').val(total);
		}
		else{
			$('#calculated-amount').val(0);
		}		
	})

	$(document.body).on('change','#list-price',function(){
		
		var qty = $('#quantity').val();
		var price = $('#list-price').val(); 
		
		if(price > 0){

			var total = qty * price;
			$('#calculated-amount').val(total);
		}
		else{
			$('#calculated-amount').val(0);
		}		
	})
	
	$(document.body).on('change','#discount_reason',function(){
		
		var select_val = $('#discount_reason :selected').text();
		var qty = $('#quantity').val();
		var lp = $('#list-price').val();
		
		if(select_val != ''){

			setDiscountAmount();
		}
		
	})
	
	function setDiscountAmount(){

		var select_val = $('#discount_reason :selected').text();
		var qty = $('#quantity').val();
		var lp = $('#list-price').val();

		if(select_val != ''){

			jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/check_discount",
                
                data: {discount_reason: select_val},
				
                success: function(response) 
                {
					console.log(response);
					var response_array = $.parseJSON(response);
					var len = response_array.length;

					if(len){ //discount_type_c

						if(response_array[0]['editable_c'] == 1){
							
							$('#discount-amount').attr("disabled", false); 
						}
						else{

							$('#discount-amount').attr("disabled", true);
						}

						/*if(response_array[0]['discount_amount'] > 0){

							var disc_amt = response_array[0]['discount_amount'] * qty;
							$('#discount-amount').val(disc_amt);
						}
						else{

							$('#discount-amount').val('');
						}*/
						if(response_array[0]['discount_type_c'] == "Flat"){
							
							if(response_array[0]['discount_amount'] > 0){

								//if(lp > 0 && qty > 0)
								{

									var cal_amt = (lp * qty) - (response_array[0]['discount_amount']  * qty);
								}
								/*else{

									var cal_amt = '';
								}*/
								var disc_amt = response_array[0]['discount_amount'] * qty;
								$('#discount-amount').val(disc_amt);								
							}
							else{

								//if(lp > 0 && qty > 0)
								{

									var cal_amt = (lp * qty);
								}
								/*else{

									var cal_amt = '';
								}*/
								$('#discount-amount').val('');
							}
						}
						else{							

							if(response_array[0]['discount_amount'] > 0){

								//if(lp > 0 && qty > 0)
								{

									var main_amt = (lp * qty);
									var disc = ((response_array[0]['discount_amount'] / 100) * main_amt)  ;//* qty)
									var cal_amt = main_amt - disc;
								}
								/*else{

									var cal_amt = '';
								}*/
								//var disc_amt = response_array[0]['discount_amount'] * qty;
								$('#discount-amount').val(disc);
							}
							else{

								//if(lp > 0 && qty > 0)
								{

									var cal_amt = (lp * qty);
								}
								/*else{

									var cal_amt = '';
								}*/
								$('#discount-amount').val('');
							}
						}
						$('#calculated-amount').val(cal_amt);
					}
					else{

						//if(lp > 0 && qty > 0)
						{

							var cal_amt = (lp * qty);
						}
						/*else{

							var cal_amt = '';
						}*/
						$('#discount-amount').val('');
						$('#calculated-amount').val(cal_amt);
					}
				}
			});
		}
	}

	function checkRecurring(service_id){ //, child = ''
		
		var self = this;
		self.result = 0;
		if(service_id != ''){
			
			jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/checkRecurringType",
                
                data: {service_ype: service_id},
				
                success: function(res) 
                {
					/*if(child == 1){

						if(res == 1){
						
							self.result = 1;
							$('#child_rec_type_div').show();
							$('#child_service_date_main_div').show();							
						}
						else{
							
							self.result = 0;
							//$('#rec_type').val('');
							$('#child_rec_type_div').hide();
							$('#child_service_date_main_div').hide();
						}
					}
					else{*/

						if(res == 1){
						
							self.result = 1;
							$('#rec_type_div').show();
							$('#service_date_main_div').show();
							
							$('#acc_rec_type_div').show();
							$('#acc_service_date_main_div').show();
						}
						else{
							
							self.result = 0;
							//$('#rec_type').val('');
							$('#rec_type_div').hide();
							$('#service_date_main_div').hide();
							
							$('#acc_rec_type_div').hide();
							$('#acc_service_date_main_div').hide();
						}
					//}
					
				}
			});
			return self.result;
		}
	}
	
	function checkYearDiscount(service_id,child=''){
		
		var self = this;
		//self.result = 0;
		if(service_id != ''){
			
			jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/checkServiceNameField",
                async: false,
                data: {service_ype: service_id},
				
                success: function(res) 
                {
					if(res != '' && res != 0)
					{
						var response_array = $.parseJSON(res); 
						self.result = response_array;

						if(child == ''){

							if(response_array[0]['year_enable_c'] == 1){
							
								$('#year_main_div').show();
								$('#acc_year_main_div').show();
								$('#years').attr("disabled", false);
							}
							else
							{
								$('#year_main_div').hide();
								$('#acc_year_main_div').hide();
							}
						}
						/*else{

							if(response_array[0]['year_enable_c'] == 1){
							
								$('#child_year_main_div').show();
								$('#child_years').attr("disabled", false);
							}
							else
							{
								$('#child_year_main_div').hide();
							}
						}*/
						
						if(child == ''){

							if(response_array[0]['state_enable_c'] == 1){
								
								$('#state_main_div').show();
								$('#service_state').attr("disabled", false);
							}
							else
							{
								$('#state_main_div').hide();
							}
						}
						/*else{

							if(response_array[0]['state_enable_c'] == 1){
								
								$('#child_state_main_div').show();
								$('#child_service_state').attr("disabled", false);
							}
							else
							{
								$('#child_state_main_div').hide();
							}
						}*/

						if(child == ''){

							if(response_array[0]['period_quarter_c'] == 1){
								
								$('#quarter_div').show();
								$('#acc_quarter_div').show();
								$('#quarter').attr("disabled", false);
							}
							else
							{
								$('#quarter_div').hide();
								$('#acc_quarter_div').hide();
							}
						}
						/*else{

							if(response_array[0]['period_quarter_c'] == 1){
								
								$('#child_quarter_div').show();
								$('#child_quarter').attr("disabled", false);
							}
							else
							{
								$('#child_quarter_div').hide();
							}
						}*/

						if(child == ''){

							if(response_array[0]['res_reset_type_c'] == 1){
								
								//alert(response_array[0]['res_reset_type_c']);
								$('#roll_in_div').show();
								$('#acc_roll_in_div').show();
							}
							else
							{
								$('#roll_in_div').hide();
								$('#acc_roll_in_div').hide();
							}
						}
						/*else{

							if(response_array[0]['res_reset_type_c'] == 1){
								
								//alert(response_array[0]['res_reset_type_c']);
								$('#child_roll_in_div').show();
							}
							else
							{
								$('#child_roll_in_div').hide();
							}
						}*/

						if(child == ''){
							//alert(response_array[0]['list_price_editable_c']);
							if(response_array[0]['list_price_editable_c'] == 1){
								
								$('#list-price').attr("disabled", false);
							}
							else
							{
								$('#list-price').attr("disabled", true);
							}
						}
						/*else{

							if(response_array[0]['list_price_editable_c'] == 1){
								
								$('#child-list-price').attr("disabled", false);
							}
							else
							{
								$('#child-list-price').attr("disabled", true);
							}
						}*/

						if(child == ''){

							if(response_array[0]['discount_reason_c'] != '' && response_array[0]['discount_reason_c'] != null){
								
								//var reason_text = response_array[0]['discount_reason_c'].replace(/^/g, '');;
								var reason_text = response_array[0]['discount_reason_c'].split('^').join('');
								
								if(reason_text != ''){
									
									var reason_array = reason_text.split(',');
									//console.log(reason_array);
									if(reason_array.length > 0){
										
										var html = '<option value=""></option>';
										
										for(var i=0; i< reason_array.length; i++){
											
											html += '<option value="' + reason_array[i] + '">' + reason_array[i] + '</option>';
										}
										
										$('#discount_reason').html(html);
										$('#acc_discount_reason').html(html);
									}
								}
								
							}
							else{
								
								$('#discount_reason').html('');
								$('#acc_discount_reason').html('');
							}
						}

						if(child == ''){

							if(response_array[0]['resservice_flag_c'] != '' && response_array[0]['resservice_flag_c'] != null){
								
								var service_flag_text = response_array[0]['resservice_flag_c'].split('^').join('');
								
								if(service_flag_text != ''){
									
									var service_flag_array = service_flag_text.split(',');
									//console.log(reason_array);
									if(service_flag_array.length > 0){
										
										var html = '<option value=""></option>';
										
										for(var i=0; i< service_flag_array.length; i++){
											
											html += '<option value="' + service_flag_array[i] + '">' + service_flag_array[i] + '</option>';
										}
										//console.log(html);
										
										$('#service_flag').html(html);
										$('#flag_div').show();
										$('#service_flag').attr("disabled", false);
										
										$('#acc_flag_div').show();
										$('#acc_service_flag').html(html);
									}
									//console.log('123');
								}
								
							}
							else{
								
								$('#flag_div').hide();
								$('#service_flag').html('');
								
								$('#acc_flag_div').hide();
								$('#acc_service_flag').html('');
							}
						}
						/*else{

							if(response_array[0]['resservice_flag_c'] != '' && response_array[0]['resservice_flag_c'] != null){
								
								var service_flag_text = response_array[0]['resservice_flag_c'].split('^').join('');
								
								if(service_flag_text != ''){
									
									var service_flag_array = service_flag_text.split(',');
									//console.log(reason_array);
									if(service_flag_array.length > 0){
										
										var html = '<option value=""></option>';
										
										for(var i=0; i< service_flag_array.length; i++){
											
											html += '<option value="' + service_flag_array[i] + '">' + service_flag_array[i] + '</option>';
										}
										//console.log(html);
										
										$('#child_service_flag').html(html);
										$('#child_flag_div').show();
										$('#child_service_flag').attr("disabled", false);
										
									}
									//console.log('123');
								}
								
							}
							else{
								
								$('#child_flag_div').hide();
								$('#child_service_flag').html('');
							}
						}*/
					}					
				}
			});
			return self.result;
		}
	}
	
	//accordion
	$(document.body).on('change','#acc_service_name',function(){
		
		var select_val = $('#acc_services_type :selected').text();
		//var select_id = this.value;
		var service_id = this.value;
		
		if(service_id != ''){
			
			var filter_val = main_service_names_array.filter(x => x.id === service_id);
			var price = filter_val[0]['list_price'];
		}
		
		//var options = JSON.parse(select_id);
		//var price = options.lp;
		//console.log(options.lp);
		if(price > 0){
			$('#acc_list-price').val(price);
		}
		else{
			$('#acc_list-price').val(0);
		}
	})
	
	$(document.body).on('change','#quantity',function(){
		
		var qty = $('#quantity').val();
		var lp = $('#list-price').val();
		var dp = $('#discount-amount').val();		
		//alert(qty);
		
		setDiscountAmount();

		/*if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#calculated-amount').val(calc);
		}*/
		
	})
	
	//accordion
	$(document.body).on('change','#acc_quantity',function(){
		
		var qty = $('#acc_quantity').val();
		var lp = $('#acc_list-price').val();
		var dp = $('#acc_discount-amount').val();		
		//alert(qty);
		
		if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#acc_calculated-amount').val(calc);
		}
		
	})
	
	$(document.body).on('change','#discount-amount',function(){
		
		var qty = $('#quantity').val();
		var lp = $('#list-price').val();
		var dp = $('#discount-amount').val();		
		//alert(qty);
		
		if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#calculated-amount').val(calc);
		}
		
	})
	
	//accordion
	$(document.body).on('change','#acc_discount-amount',function(){
		
		var qty = $('#acc_quantity').val();
		var lp = $('#acc_list-price').val();
		var dp = $('#acc_discount-amount').val();		
		//alert(qty);
		
		if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#acc_calculated-amount').val(calc);
		}
		
	})
	
	$(document.body).on('change','#list-price',function(){
		
		var qty = $('#quantity').val();
		var lp = $('#list-price').val();
		var dp = $('#discount-amount').val();		
		//alert(qty);
		
		setDiscountAmount();

		/*if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#calculated-amount').val(calc);
		}*/
		
	})
	
	$(document.body).on('change','#acc_list-price',function(){
		
		var qty = $('#acc_quantity').val();
		var lp = $('#acc_list-price').val();
		var dp = $('#acc_discount-amount').val();		
		//alert(qty);
		
		if(qty > 0 && lp > 0){
			
			var calc = parseFloat(lp) * parseInt(qty);
			
			if(dp != ''){
				
				calc = calc - dp;
			}
			
			//alert(calc);
			$('#acc_calculated-amount').val(calc);
		}		
	})
	
	function getAndSaveNewServiceValues(){

		var error = 0;
		var self = this;
		var cid = document.getElementById("case_num").value;
		var uid = '<?php echo $userid ; ?>';
		var uname = '<?php echo $this->session->userdata('username'); ?>';

		var sale_level = $('#sale_type').val();
		var servicer = $('#servicer').val();
		var entity = $('#entity').val();
		var billed_to = $('#billed_to').val();
		var commission = $('#commission').val();
		var service_type = $('#services_type').val();
		var recurring_type = $('#rec_type').val();
		var service_start_date = $('#service_date').val();

		var service_name_id = $('#service_name').val();		
		var service_id = service_name_id; //service_name_id_arr.id;		
		var category_id = '';

		if(service_id != '' && service_id != null){
			//console.log('service_id - ' + service_id)
			//console.log('main_service_names - ' + main_service_names)
			var filter_val = main_service_names.filter(x => x.id === service_id);
			category_id = filter_val[0]['category_id'];
		}
		
		var service_name = $('#service_name :selected').text();
		var service_type_txt = $('#services_type :selected').text();
		var serv_name = '';
		if(service_name != ''){
			
			var sn = service_name.split('- ');
			serv_name = sn[1];
		}

		var desc = $('#description').val();
		var price = $('#list-price').val();
		var qty = $('#quantity').val();
		var disc_reason = $('#discount_reason').val();
		var disc_amount = $('#discount-amount').val();
		var calc_amt = $('#calculated-amount').val();
		var years_list = $('#years').val();
		var state_list = $('#service_state').val(); 
		var quarter_list = $('#quarter').val();
		var rollin = $('#reset_rollin').val();
		var flag_list = $('#service_flag').val();

		var years_list2 = '';
		if(years_list){
			years_list2 = years_list.toString();
		}
		var state_list2 = '';
		if(state_list){
			state_list2 = state_list.toString();
		}
		if(quarter_list){
			quarter_list = quarter_list.toString();
		}
		if(flag_list){
			flag_list = flag_list.toString();
		}

		if(sale_level == ''){
			
			error = 1;
			alert('Please select Sale Type');
			return false;
		}
		if(servicer == ''){
			
			error = 1;
			alert('Please select Servicer');
			return false;
		}
		if(entity == ''){
			
			error = 1;
			alert('Please select Entity');
			return false;
		}
		if(desc == ''){
			desc = 'null';
		}
		if(disc_reason == ''){
			disc_reason = 'null';
		}
		if(disc_amount == ''){
			disc_amount = 'null';
		}

		if(uid == ''){
			
			error = 1;
			alert('User Id not found');
			return false;
		}

		if(service_type == ''){
			
			error = 1;
			alert('Please select Service Type');
			return false;
		}

		if(service_name == ''){
			
			error = 1;
			alert('Please select Service Name');
			return false;
		}

		if(qty < 1){
			
			error = 1;
			alert('Quantity cannot be zero');
			return false;
		}

		if(price < 0){
			
			error = 1;
			alert('Price cannot be less than zero');
			return false;
		}

		if(serv_name == ''){
			
			error = 1;
			alert('Please select service name');
			return false;
		}

		if(error < 1){
			
			jQuery.ajax({
				
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_service",
				dataType: 'json',
				async: false,
				data: {caseid: cid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, state_list: state_list2, category_id: category_id, commission: commission, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list},
				
				success: function(res) 
				{
					self.result = res;
				}

			});
			return self.result;
		}
		else {

			return 0;
		}
	}

	function cancelChildServices(){

		$('#child_service_name').val('');
		$('#child_service_name').empty();

		$('#child_quarter').val(null).trigger('change');
		$('#child_quarter').val('');
		$('#reset_rollin').val('');
		//$('#reset_rollin').empty();
		$('#child_reset_rollin').val(null).trigger('change');

		$('#child_service_flags').val(null).trigger('change');
		$('#child_service_flags').val('');
		$('#child_services_type').val('');
		$('#child_services_type').empty();
		$('#child_description').val('');
		$('#child-list-price').val('');
		$('#child-list-price').attr("disabled", false);
		$('#child-quantity').val('');
		$('#child_years').val(null).trigger('change');
		$('#child_years').val('');
		$('#child_service_state').val(null).trigger('change');
		$('#child_service_state').val('');		

		$('#child_year_main_div').hide();
		$('#child_state_main_div').hide();
		$('#child_quarter_div').hide();
		$('#child_roll_in_div').hide();
		$('#child_flag_div').hide(); 

		$('#add_child_services_div').hide();	
		$('#add_child_service').show();		


		var cl = $('#child_update_services').attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];

		if(rid != ''){
			
			$('#child_update_services').removeClass(rid);    
		}
	}

	function addPackage(){

		var res = getAndSaveNewServiceValues();
		//console.log('addPackage - ' + res);
		if(res.id != '' && res != false){
			//console.log('in if');
			$('#package_parent_id').val(res.id);		

			$('#services_type').attr("disabled", true);
			$('#servicer').attr("disabled", true);
			$('#entity').attr("disabled", true);
			$('#service_name').attr("disabled", true);

			$('#child_service_box').show();
			//$('#child_update_services').hide();

			$('#add_package').hide();
			//$('#add_child_service').show();

			$('#save_services').hide();
			$('#update_services').show();

			checkChildServices(res.id);
			//getNonPackageServiceTypes();
		}
	}

	function addNewChildService(){

		$('#child_service_box').show();
		$('#add_child_services_div').show();

		$('#child_update_services').hide();
		$('#child_save_services').show();

		$('#child-list-price').val(0);
		$('#child-quantity').val(1);
		//getNonPackageServiceTypes();
	}

	function getNonPackageServiceTypes(){		

		var self = this;
		var related = $('#servicer').val();
		var entity = $('#entity').val();
		jQuery.ajax({ 

			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/getChildServiceTypes",
			async: false,
			data: {related: related, entity: entity},
			success: function(response) 
			{
				if(response != 0 ){
					
					var service_type = JSON.parse(response);
					self.result = service_type;
					//console.log(service_type)
					var service_type_div = '<option value=""></option>'; 
					//console.log()
					for(i=0; i<service_type.length; i++){		

						service_type_div += '<option value="' + service_type[i]['id'] + '">' + service_type[i]['name'] + '</option>';
					}					
					
					//$('#child_services_type').html(service_type_div);	

					$("#child_services_table tr ").each(function() {
					
						//$('.child_row_service_type').html(service_type_div);				
						
					})
											
				}
				else{

					self.result = 0;
				}
			}
		})

		return self.result;
	}

	function save_child_service(data){

		var rid = $(data).closest('tr').attr('id');
		var error = 0;
		var self = this;
		var cid = document.getElementById("case_num").value;
		var uid = '<?php echo $userid ; ?>';
		var uname = '<?php echo $this->session->userdata('username'); ?>';

		var sale_level = $('#sale_type').val();
		var servicer = $('#servicer').val();
		var entity = $('#entity').val();
		var billed_to = $('#billed_to').val();

		var parent_id = $('#package_parent_id').val();

		if(parent_id == ''){

			error = 1;
			alert('Package Id not found');
			return false;
		}

		if(sale_level == ''){
			
			error = 1;
			alert('Please select Sale Type');
			return false;
		}
		if(servicer == ''){
			
			error = 1;
			alert('Please select Servicer');
			return false;
		}
		if(entity == ''){
			
			error = 1;
			alert('Please select Entity');
			return false;
		}

		var recurring_type = '';
		var service_start_date = '';
		var disc_reason = 'Service Package';
		var disc_amount = 100;
		var calc_amt = 0;
		var price = 0;		
		var parent_price = 0;
		parent_price = $('#list-price').val();
		var desc = '';
		var qty =  1;

		//alert(rid); ------------------------------------------------
		$("#child_services_table tr#" + rid).find("input").each(function()			
		{					
			var td_id = $(this).attr("id"); 
			var td_value = $(this).val();
			if(td_id == 'cr_desc'){

				desc =  $(this).val();
			}
			if(td_id == 'cr_lp'){

				price =  $(this).val();				
			}				
			if(td_id == 'cr_qty'){

				qty =  $(this).val();
			}
			//alert(td_id + ', ' + td_value);
		})

		var updated_price= price + parent_price;
				
		var service_name_id =  '';
		var years_list =  '';
		var quarter_list =  '';
		var rollin =  '';
		var state_list =  '';
		var flag_list =  '';
		var service_name = ''; 
		var service_type_txt = '';

		$("#child_services_table tr#" + rid).find("select").each(function()		
		{					
			var td_id = $(this).attr("id"); 
			//var td_id = $(this).attr("class"); 
			var td_value = $(this).val();
						
			if(td_id == 'cr_service_type'){

				service_type =  $(this).val();
				service_type_txt =  $(this).find('option:selected').text(); 
			}
			if(td_id == 'cr_service_name'){

				service_name_id =  $(this).val();
				service_name = $(this).find('option:selected').text(); 
			}
			//if(td_id == 'cr_years'){
			if(td_id.indexOf('cr_years') != -1){
				
				years_list =  $(this).val();
			}
			//if(td_id == 'cr_quarters'){
			if(td_id.indexOf('cr_quarters') != -1){

				quarter_list =  $(this).val();
			}
			if(td_id == 'cr_reset'){

				rollin =  $(this).val();
			}
			//if(td_id == 'cr_service_flag'){
			if(td_id.indexOf('cr_service_flag') != -1){

				flag_list =  $(this).val();
			}
			//if(td_id == 'cr_state'){
			if(td_id.indexOf('cr_state') != -1){

				state_list =  $(this).val();
			}
			
		});		 
		//alert('years_list - ' + years_list );
		//return false;
		//alert(service_type + ', service_name_id - ' + service_name_id + ', years_list - ' + years_list + ', quarter_list - ' + quarter_list + ', rollin - ' + rollin + ', state_list - ' + state_list);


		var service_id = service_name_id; 
		var category_id = '';
		if(service_type == ''){
			
			error = 1;
			alert('Please select Service Type');
			return false;
		}

		if(service_name_id == ''){
			
			error = 1;
			alert('Please select Service Name');
			return false;
		}


		if(service_id != '' && service_id != null){

			getChildServiceNameDropdownValues(service_name, service_type, set=0);
			
			var filter_val = main_child_service_names.filter(x => x.id === service_id);
			category_id = filter_val[0]['category_id'];
			//console.log('category_id - ' +  category_id);
		}

		var serv_name = '';
		if(service_name != ''){
			//alert(service_name);
			var sn = service_name.split('-');
			serv_name = sn[1];
		}

		if(!rollin){
			rollin= null;
		}

		var years_list2 = '';
		if(years_list){
			years_list2 = years_list.toString();
		}

		if(state_list){
			state_list = state_list.toString();
		}
		else{
			state_list = null;
		}

		if(quarter_list){
			quarter_list = quarter_list.toString();
		}
		if(flag_list){
			flag_list = flag_list.toString();
		}
		else{
			flag_list = null;
		}

		if(desc == ''){
			desc = 'null';
		}
		if(disc_reason == ''){
			disc_reason = 'null';
		}
		if(disc_amount == ''){
			disc_amount = 'null';
		}

		if(uid == ''){
			
			error = 1;
			alert('User Id not found');
			return false;
		}		

		if(qty < 1){
			
			error = 1;
			alert('Quantity cannot be zero');
			return false;
		}

		if(price < 0){
			
			error = 1;
			alert('Price cannot be less than zero');
			return false;
		}

		if(serv_name == ''){ 
			
			error = 1;
			alert('Please select service name');
			return false;
		}

		/*var total_parent = checkTotalChildServiceAmt(parent_id);
		alert(total_parent)
		var new_total = total_parent + (price * qty);*/
		//state_list = "CO";
		//alert(price);
		if(error < 1){

			if(rid == 'new_cr'){

				var new_id = createNewChildService(cid, sale_level, service_type, serv_name, service_id, desc, price, qty, disc_reason, disc_amount, calc_amt, years_list2, state_list, category_id, commission, billed_to, entity, servicer, recurring_type, service_start_date, quarter_list, rollin, flag_list, parent_id);
				//alert(new_id);
				$('#child_services_table #' + rid + ' #tab_add_child_service').show();
				$('#child_services_table #' + rid + ' #tab_edit_child_service').show();
				$('#child_services_table #' + rid + ' #tab_save_child_service').hide();

				$(data).closest('tr').attr('id', new_id);
				//$(selector).attr('id', new_id);
			}	
			else{

				saveChildService(rid, service_type, serv_name, service_id, desc, price, qty, years_list2, state_list, category_id, quarter_list, rollin, flag_list, parent_id);
				//$('#child_services_table #' + rid + ' #tab_add_child_service').show();
				$('#child_services_table #' + rid + ' #tab_edit_child_service').show();
				$('#child_services_table #' + rid + ' #tab_save_child_service').hide();
			}	
			
			$(data).closest('tr').find('input,select').attr("disabled",true);

			var total_parent = checkTotalChildServiceAmt(parent_id);
			$('#list-price').val(total_parent);
			$('#calculated-amount').val(total_parent); 
		}
	}

	function createNewChildService(cid, sale_level, service_type, serv_name, service_id, desc, price, qty, disc_reason, disc_amount, calc_amt, years_list2, state_list, category_id, commission, billed_to, entity, servicer, recurring_type, service_start_date, quarter_list, rollin, flag_list, parent_id){

		var self = this;
		jQuery.ajax({
				
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_service",
			dataType: 'json',
			async: false,
			data: {caseid: cid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, state_list: state_list, category_id: category_id, commission: 0, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list, parent_id: parent_id},
			//processData: false,
			success: function(res) 
			{
				console.log(res);
				
				if(res.id != 0){

					self.new_id = res.id;
										
					$.jGrowl("New Service Created Successfully.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					}); 
				}
				else{
					
					self.new_id = 0;
					$.jGrowl("Unable to add service.", {  
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}				
			}				
		});	
		return self.new_id;
	}

	function saveChildService(rid, service_type, serv_name, service_id, desc, price, qty, years_list2, state_list, category_id, quarter_list, rollin, flag_list, parent_id){

		jQuery.ajax({
					
			type: "POST",					
			url: "<?php echo base_url(); ?>" + "index.php/casedetail/update_child_service",
			dataType: 'json',
			aync: false,
			data: {rid: rid, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, years_list: years_list2, state_list: state_list, category_id: category_id, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list, parent_id: parent_id},
			
			success: function(res) 
			{
				if(res == true){

					//checkChildServices(parent_id);
					$.jGrowl("Service Updated Successfully", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				
					//showServicesList();
				}
				
			}
		})
	}

	function save_child_rli(){

		var error = 0;
		var self = this;
		var cid = document.getElementById("case_num").value;
		var uid = '<?php echo $userid ; ?>';
		var uname = '<?php echo $this->session->userdata('username'); ?>';

		var sale_level = $('#sale_type').val();
		var servicer = $('#servicer').val();
		var entity = $('#entity').val();
		var billed_to = $('#billed_to').val();
		var service_type = $('#child_services_type').val();
		var recurring_type = '';
		var service_start_date = '';

		var service_name_id = $('#child_service_name').val();		
		var service_id = service_name_id; //service_name_id_arr.id;		
		var category_id = '';

		var parent_id = $('#package_parent_id').val();

		if(parent_id == ''){

			error = 1;
			alert('Package Id not found');
			return false;
		}

		if(service_id != '' && service_id != null){
			//console.log('service_id - ' + service_id)
			//console.log('main_service_names - ' + main_service_names)
			var filter_val = main_child_service_names.filter(x => x.id === service_id);
			category_id = filter_val[0]['category_id'];
		}
		
		var service_name = $('#child_service_name :selected').text();
		var service_type_txt = $('#child_services_type :selected').text();
		var serv_name = '';
		if(service_name != ''){
			//alert(service_name);
			var sn = service_name.split('-');
			serv_name = sn[1];
		}

		var desc = $('#child_description').val();
		var price = 0;
		price = $('#child-list-price').val();
		var parent_price = 0;
		parent_price = $('#list-price').val();

		var updated_price= price + parent_price;

		var qty = $('#child-quantity').val();
		var disc_reason = 'Service Package';
		var disc_amount = 100;
		var calc_amt = 0;
		var years_list = $('#child_years').val();
		var state_list = $('#child_service_state').val(); 
		var quarter_list = $('#child_quarter').val();
		var rollin = $('#child_reset_rollin').val();
		var flag_list = $('#child_service_flag').val();

		if(!rollin){
			rollin= null;
		}

		var years_list2 = '';
		if(years_list){
			years_list2 = years_list.toString();
		}

		if(state_list){
			state_list = state_list.toString();
		}
		else{
			state_list = null;
		}

		var quarter_list = '';
		if(quarter_list){
			quarter_list = quarter_list.toString();
		}
		if(flag_list){
			flag_list = flag_list.toString();
		}
		else{
			flag_list = null;
		}
		if(sale_level == ''){
			
			error = 1;
			alert('Please select Sale Type');
			return false;
		}
		if(servicer == ''){
			
			error = 1;
			alert('Please select Servicer');
			return false;
		}
		if(entity == ''){
			
			error = 1;
			alert('Please select Entity');
			return false;
		}
		if(desc == ''){
			desc = 'null';
		}
		if(disc_reason == ''){
			disc_reason = 'null';
		}
		if(disc_amount == ''){
			disc_amount = 'null';
		}

		if(uid == ''){
			
			error = 1;
			alert('User Id not found');
			return false;
		}

		if(service_type == ''){
			
			error = 1;
			alert('Please select Service Type');
			return false;
		}

		if(service_name == ''){
			
			error = 1;
			alert('Please select Service Name');
			return false;
		}

		if(qty < 1){
			
			error = 1;
			alert('Quantity cannot be zero');
			return false;
		}

		if(price < 0){
			
			error = 1;
			alert('Price cannot be less than zero');
			return false;
		}

		if(serv_name == ''){ 
			
			error = 1;
			alert('Please select service name');
			return false;
		}

		/*var total_parent = checkTotalChildServiceAmt(parent_id);
		alert(total_parent)
		var new_total = total_parent + (price * qty);*/
		//state_list = "CO";

		if(error < 1){
						
			jQuery.ajax({
				
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_service",
				dataType: 'json',
				async: false,
				data: {caseid: cid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, state_list: state_list, category_id: category_id, commission: 0, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list, parent_id: parent_id},
				//processData: false,
				success: function(res) 
				{
					console.log(res);
					if(res.id != 0){
						
						var now     = new Date(); 
						var year    = now.getFullYear();
						var month   = now.getMonth()+1; 
						var day     = now.getDate();
						var hour    = now.getHours();
						var minute  = now.getMinutes();
						var second  = now.getSeconds(); 
						if(month.toString().length == 1) {
							month = '0'+month;
						}
						if(day.toString().length == 1) {
							day = '0'+day;
						}   
						if(hour.toString().length == 1) {
							hour = '0'+hour;
						}
						if(minute.toString().length == 1) {
							minute = '0'+minute;
						}
						if(second.toString().length == 1) {
							second = '0'+second;
						}   
						var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;  

						tbody = '';
						acc_tbody = '';
						common = '';
						
						tbody +=  "<tr id= '" + res.id + "'>" ;
						acc_tbody += "<tr id= '" + res.id + "'>" ;
						
						tbody += '<td><button class="btn btn-xs btn-edit teditservice_child"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service_child(this);"><i class="glyph-icon icon-pencil"></i></button>  &nbsp; &nbsp;<button class="btn btn-xs btn-danger tdelservice_child"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service_child(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
								
											
						common +=	'<td>' +service_name + '</td> ';

						if(desc != 'null'){
									
							common +=	'<td>' + desc + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}
						
						if(years_list2 != 'null' && years_list2 != ''){
									
							common +=	'<td>' + years_list2 + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}

						if(quarter_list != 'null' && quarter_list != ''){
									
							common +=	'<td>' + quarter_list + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}

						common +=	 '<td>' + price + '</td> '
									+ '<td>' + qty + '</td> ';	
												
						common +=  "</tr>"; 
						
						tbody += common;					
						
						$('#child_services_table').show();
						$('#child_services_table').prepend(tbody);
						$('#show_child_services_div').show();
						
						$.jGrowl("New Service Created Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						}); 
					}
					else{
						
						$.jGrowl("Unable to add service.", {  
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}

					
				}		
				
			});			

			//$('#child_add_services').show();		
			$('#child_service_name').val('');
			$('#child_services_type').val('');			
			$('#child_description').val('');
			$('#child-list-price').val('');
			
			$('#child-quantity').val('');
			$('#child_years').val(''); 
			$('#child_year_main_div').hide();
			$('#child_state_main_div').hide();
			
			$('#child_quarter').val('');
			$('#child_reset_rollin').val('');
			$('#child_service_flag').val('');
			
			$('#child_service_name').empty();
			$('#child_services_type').empty();
			//$('#reset_rollin').empty();
			$('#child_service_flag').val(null).trigger('change');
			$('#child_reset_rollin').val(null).trigger('change');
			$('#child_quarter').val(null).trigger('change');
			$('#child_years').val(null).trigger('change');
			$('#child_service_state').val(null).trigger('change');

			$('#child_flag_div').hide();
			$('#child_roll_in_div').hide();
			$('#child_quarter_div').hide();
			$('#child-list-price').attr("disabled", false);
			$('#add_child_services_div').hide();

			var total_parent = checkTotalChildServiceAmt(parent_id);
			$('#list-price').val(total_parent);
			$('#calculated-amount').val(total_parent); 

			//$('#list-price').val(new_total); 
			//$('#calculated-amount').val(new_total); 
		}	

	}



	function update_child_rli(data){   

		var cl = $(data).attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];

		$('#child_update_services').removeClass(rid);


		if(rid != ''){
			
			var error = 0;			
			var uid = '<?php echo $userid ; ?>';
			
			var parent_id = $('#package_parent_id').val();
			var service_type = $('#child_services_type').val();
			var service_name_id = $('#child_service_name').val();
			
			var service_id = service_name_id; 		
			var category_id = '';
			
			if(service_id != '' && service_id != null){
				
				//console.log('service_id - ' + service_id)
				//console.log('main_service_names - ' + main_service_names)
				var filter_val = main_child_service_names.filter(x => x.id === service_id);
				category_id = filter_val[0]['category_id'];
			}

			var service_name = $('#service_name :selected').text();
			
			var serv_name = '';
			var serv_type = '';
			if(service_name != ''){
				
				var sn = service_name.split('- ');
				serv_type = sn[0];
				serv_name = sn[1];
			}
			var desc = $('#child_description').val();
			var price = $('#child-list-price').val();
			var qty = $('#child-quantity').val();

			var years_list = $('#child_years').val(); 
			var state_list = $('#child_service_state').val(); 
			var quarter_list = $('#child_quarter').val();
			var rollin = $('#child_reset_rollin').val();
			var flag_list = $('#child_service_flag').val();

			var years_list2 = '';
			if(years_list){
				years_list2 = years_list.toString();
			} 
			
			var state_list2 = '';
			if(state_list){
				state_list2 = state_list.toString();
			}
			if(quarter_list){
				quarter_list = quarter_list.toString();
			}
			if(flag_list){
				flag_list = flag_list.toString();
			}
			
			if(desc == ''){
				desc = 'null';
			}
			
			if(uid == ''){
				
				error = 1;
				alert('User Id not found');
				return false;
			}

			if(service_type == ''){
				
				error = 1;
				alert('Please select Service Type');
				return false;
			}
			
			if(serv_name == ''){
				
				error = 1;
				alert('Please select Service Name');
				return false;
			}
			
			if(qty < 1){
				
				error = 1;
				alert('Quantity cannot be zero');
				return false;
			}
			
			if(price < 0  || price == ''){
				
				price = 0;				
			}
			
			if(error < 1){
			
				jQuery.ajax({
					
					type: "POST",					
					url: "<?php echo base_url(); ?>" + "index.php/casedetail/update_child_service",
					dataType: 'json',
					aync: false,
					data: {rid: rid, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, years_list: years_list2, state_list: state_list2, category_id: category_id, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list, parent_id: parent_id},
					
					success: function(res) 
					{
						if(res == true){
							

							/*tbody =  "<tr id= '" + rid + "'>" ;
							
							tbody += '<td><button class="btn btn-xs btn-edit teditservice_child"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service_child(this);"><i class="glyph-icon icon-pencil"></i></button>  &nbsp; &nbsp;<button class="btn btn-xs btn-danger tdelservice_child"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service_child(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
									
												
							tbody +=	'<td>' +service_name + '</td> ';

							if(desc != 'null'){
										
								tbody +=	'<td>' + desc + '</td> ';
							}
							else{
								
								tbody +=	'<td></td> ';
							}
							
							tbody +=	+ '<td>' + years_list2 + '</td> '
										+ '<td>' + quarter_list + '</td> '									
										+ '<td>' + price + '</td> '
										+ '<td>' + qty + '</td> ';	
													
							tbody +=  "</tr>"; */

							//$('#child_services_table').prepend(tbody);
							checkChildServices(parent_id);
							$.jGrowl("Service Updated Successfully", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
						
							//showServicesList();
						}
						
					}
				})

				$('#add_services').show();		
				
				$('#child_service_name').val('');
				$('#child_description').val('');
				$('#child-list-price').val('');
				$('#child-quantity').val('');
				$('#child_years').val('');
				$('#child_quarter').val('');
				$('#reset_rollin').val('');
				$('#service_flag').val('');

				$('#child_service_name').empty();
				$('#child_services_type').empty();
				//$('#reset_rollin').empty();
				
				$('#child_service_flag').val(null).trigger('change');
				$('#child_reset_rollin').val(null).trigger('change');
				$('#child_quarter').val(null).trigger('change');
				$('#child_years').val(null).trigger('change');
				
				$('#child_flag_div').hide();
				$('#child_roll_in_div').hide(); 
				$('#child_year_main_div').hide();
				$('#child_state_main_div').hide();
				$('#child_quarter_div').hide();

				//$('#add_services_div').hide();
				$('#child_update_services').hide();
				$('#child_save_services').show();
				$('#add_child_services_div').hide();  
				$('#add_child_service').show();

				$('#child_services_type').attr("disabled", false);
				$('#child_service_name').attr("disabled", false);
				
				$('#child-list-price').attr("disabled", false);

				var total_parent = checkTotalChildServiceAmt(parent_id);
				
				$('#list-price').val(total_parent);
				$('#calculated-amount').val(total_parent); 
			}
		}
	}

	$(document.body).on('change','#child_services_type',function(){
		//alert('change - services_type'); 
		var select_val = $('#child_services_type :selected').text();
		var select_id = this.value;
		$('#child_service_name').html('');

		$('#child_years').val(null).trigger('change');
		$('#child_quarter').val(null).trigger('change');
		$('#child_reset_rollin').val(null).trigger('change');
		$('#child_service_flag').val(null).trigger('change');

		$('#child_year_main_div').hide();
		$('#child_quarter_div').hide();
		$('#child_roll_in_div').hide();
		$('#child_flag_div').hide();

		$('#list-price').val(0);
		$('#quantity').val(1);

		if(select_id != ''){
			
			getChildServiceNameDropdownValues(select_val, select_id, set=0);
		}		
	})

	function getChildServiceNameDropdownValues(sval, sid, set, status_check = 0, stid){
		//alert(status_check);
		var self = this;
		var related_to = $('#servicer').val();
		var entity = $('#entity').val();
		//alert(sid + ', ' + related_to + ', ' + status_check + ', ' + entity );
		//return false;
		if(sid != ''){
			//console.log('sid - ' + sid);
			jQuery.ajax({
			
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_all_services",
				data: {type_id: sid, related_to: related_to, status_check: status_check, entity: entity}, 
				async: false,
				success: function(response) 
				{
					//alert('RESPONSE - ' + response)
					if(response != ''){
						
						var service_names = $.parseJSON(response);
						self.service_name_arr = service_names;
						//console.log('self.service_name_arr1 - ' + self.service_name_arr);
						
						if(service_names != ''){
							
							var service_names_div = '<option value=""></option>'; 
							//console.log(service_names);
							for (var key in service_names) {
								
								if (service_names.hasOwnProperty(key)){
									
									var id = service_names[key]['id'];
									var lp = service_names[key]['list_price'];
									var cat = service_names[key]['category_id'];
									var opt = '{"id": "' + id + '","lp":"' + lp + '"}';
									
									if(stid == id && set == 1){ //&& scat == cat
									//console.log('if');
										//alert('hello - ' + service_names[key]['name']);
										//console.log('CHECK- '+stid + ', ' + id + ' and ' + scat + ', ' + cat)
										
										//service_names_div += '<option value=\'' + opt + '\' selected>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										
										//service_names_div += '<option value="' + id + '" selected>' + sval + ' - ' +service_names[key]['name'] + '</option>';
										
										service_names_div += '<option value="' + id + '" selected>' + service_names[key]['name'] + '</option>';
									}
									else{

										service_names_div += '<option value="' + id + '">' + service_names[key]['name'] + '</option>';
									}
									
										
								}
							}
							
							//$('#child_service_name').html(service_names_div);
						}
					}
				}
			})
			main_child_service_names = self.service_name_arr;
			//console.log('self.service_name_arr2 - '+main_service_names);
		}
		//console.log('main_child_service_names - '+main_child_service_names);
		return main_child_service_names;
	}

	$(document.body).on('change','#child_service_name',function(){

		var select_val = $('#child_services_type :selected').text();
		var service_id = this.value;
		//alert(select_val + ', ' + service_id);
		$('#child_years').val(null).trigger('change');
		$('#child_quarter').val(null).trigger('change');
		$('#child_reset_rollin').val(null).trigger('change');
		$('#child_service_flag').val(null).trigger('change');

		//checkRecurring(service_id,child=1);
		checkYearDiscount(service_id,child=1);
		//alert('change - ' + service_id);

		if(service_id != ''){
			
			var filter_val = main_service_names_array.filter(x => x.id === service_id);
			var price = filter_val[0]['list_price'];
		}

		if(price > 0){
			$('#child-list-price').val(price);
		}
		else{
			$('#child-list-price').val(0);
		}
		$('#child-quantity').val(1);
	})


	function save_rli(){	
		
		//$("#loader").css('display','block');
		//$('#save_services').attr("disabled", true);
		
		var error = 0;
		var self = this;
		var cid = document.getElementById("case_num").value;
		var uid = '<?php echo $userid ; ?>';
		var uname = '<?php echo $this->session->userdata('username'); ?>';
		
		var sale_level = $('#sale_type').val();
		var servicer = $('#servicer').val();
		var entity = $('#entity').val();
		var billed_to = $('#billed_to').val();
		var commission = $('#commission').val();
		var service_type = $('#services_type').val();
		var recurring_type = $('#rec_type').val();
		var service_start_date = $('#service_date').val();
		
		var service_name_id = $('#service_name').val();		
		var service_id = service_name_id; //service_name_id_arr.id;		
		var category_id = '';
		
		if(service_id != '' && service_id != null){
			//console.log('service_id - ' + service_id)
			//console.log('main_service_names - ' + main_service_names)
			var filter_val = main_service_names.filter(x => x.id === service_id);
			category_id = filter_val[0]['category_id'];
		}
		 
		var service_name = $('#service_name :selected').text();
		var service_type_txt = $('#services_type :selected').text();
		var serv_name = '';
		if(service_name != ''){
			
			var sn = service_name.split('- ');
			serv_name = sn[1];
		}
		
		var desc = $('#description').val();
		var price = $('#list-price').val();
		var qty = $('#quantity').val();
		var disc_reason = $('#discount_reason').val();
		var disc_amount = $('#discount-amount').val();
		var calc_amt = $('#calculated-amount').val();
		var years_list = $('#years').val();
		var state_list = $('#service_state').val(); 
		var quarter_list = $('#quarter').val();
		var rollin = $('#reset_rollin').val();
		var flag_list = $('#service_flag').val();
		
		var years_list2 = '';
		if(years_list){
			years_list2 = years_list.toString();
		}
		var state_list2 = '';
		if(state_list){
			state_list2 = state_list.toString();
		}
		if(quarter_list){
			quarter_list = quarter_list.toString();
		}
		if(flag_list){
			flag_list = flag_list.toString();
		}
		
		if(sale_level == ''){
			
			error = 1;
			alert('Please select Sale Type');
			return false;
		}
		if(servicer == ''){
			
			error = 1;
			alert('Please select Servicer');
			return false;
		}
		if(entity == ''){
			
			error = 1;
			alert('Please select Entity');
			return false;
		}
		if(desc == ''){
			desc = 'null';
		}
		if(disc_reason == ''){
			disc_reason = 'null';
		}
		if(disc_amount == ''){
			disc_amount = 'null';
		}
		
		if(uid == ''){
			
			error = 1;
			alert('User Id not found');
			return false;
		}
		
		if(service_type == ''){
			
			error = 1;
			alert('Please select Service Type');
			return false;
		}
		
		if(service_name == ''){
			
			error = 1;
			alert('Please select Service Name');
			return false;
		}
		
		if(qty < 1){
			
			error = 1;
			alert('Quantity cannot be zero');
			return false;
		}
		
		if(price < 0){
			
			error = 1;
			alert('Price cannot be less than zero');
			return false;
		}
		
		if(serv_name == ''){
			
			error = 1;
			alert('Please select service name');
			return false;
		}
		var  parent_id = '';

		if(error < 1){
			
			
		
			jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_service",
                dataType: 'json',
                async: false,
                data: {caseid: cid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, state_list: state_list2, category_id: category_id, commission: commission, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list, parent_id: parent_id},
				
                success: function(res) 
                {
					if(res.id != 0){
						
						var now     = new Date(); 
						var year    = now.getFullYear();
						var month   = now.getMonth()+1; 
						var day     = now.getDate();
						var hour    = now.getHours();
						var minute  = now.getMinutes();
						var second  = now.getSeconds(); 
						if(month.toString().length == 1) {
							 month = '0'+month;
						}
						if(day.toString().length == 1) {
							 day = '0'+day;
						}   
						if(hour.toString().length == 1) {
							 hour = '0'+hour;
						}
						if(minute.toString().length == 1) {
							 minute = '0'+minute;
						}
						if(second.toString().length == 1) {
							 second = '0'+second;
						}   
						var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;  
	
						tbody = '';
						acc_tbody = '';
						common = '';
						
						tbody +=  "<tr id= '" + res.id + "'>" ;
						acc_tbody += "<tr id= '" + res.id + "'>" ;
						
						tbody += '<td><button class="btn btn-xs btn-edit teditservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service(this);"><i class="glyph-icon icon-pencil"></i></button>  &nbsp; &nbsp;<button class="btn btn-xs btn-danger tdelservice"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></td>'; //id="' + res[i]['id'] + '"
								
						acc_tbody += '<td><button class="btn btn-xs btn-edit editservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_acc_service(this);"><i class="glyph-icon icon-pencil"></i></button>&nbsp; &nbsp;<button class="btn btn-xs btn-danger delservice"  title="Delete"  style="background-color: #e74c3c;;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></td>';
						
						common +=	'<td>' + sale_level + '</td> '
									
									+ '<td>' + servicer + '</td> '
									+ '<td>' + service_name + '</td> '
									+ '<td>' + qty + '</td> '
									+ '<td>' + price + '</td> ';
									
						if(disc_reason != 'null' && disc_reason != ''){
									
							common +=	'<td>' + disc_reason + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}								
						
						if(disc_amount > 0){
									
							common +=	'<td>' + disc_amount + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}
						
						common +=	'<td>' + calc_amt + '</td> '
									+ '<td>' + uname + '</td> '
									+ '<td>' + dateTime + '</td> '									
									+ '<td></td> '
									+ '<td></td> '
									+ '<td></td> '
									+ '<td></td> ';
									
						if(desc != 'null'){
									
							common +=	'<td>' + desc + '</td> ';
						}
						else{
							
							common +=	'<td></td> ';
						}						
						common +=  "</tr>"; 
						
						tbody += common;
						acc_tbody += common;						
						
						//$('#services_table').prepend(tbody);
						//$('#acc_services_table').prepend(acc_tbody);
						
						
						$.jGrowl("New Service Created Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						}); 
					}
					else{
						
						$.jGrowl("Unable to add service.", {  
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			}) 

			//$("#loader").css('display','none');
			//$('#save_rli').attr("disabled", false);

			$('#add_services').show();		
			$('#service_name').val('');
			$('#servicer').val('');
			$('#entity').val('');
			$('#sale_type').val('Case Review');
			$('#services_type').val('');			
			$('#description').val('');
			$('#list-price').val('');
			$('#quantity').val('');
			$('#discount-amount').val('');
			$('#discount_reason').val('');
			$('#years').val('');
			$('#calculated-amount').val('');
			$('#add_services_div').hide();
			$('#year_main_div').hide();
			$('#state_main_div').hide();
			
			$('#service_date').val('');
			$('#quarter').val('');
			$('#reset_rollin').val('');
			$('#service_flag').val('');
			$('#rec_type').val('');
			
			$('#service_name').empty();
			$('#services_type').empty();
			//$('#reset_rollin').empty();
			$('#service_flag').val(null).trigger('change');
			$('#reset_rollin').val(null).trigger('change');
			$('#quarter').val(null).trigger('change');
			$('#years').val(null).trigger('change');
			$('#service_state').val(null).trigger('change');

			$('#flag_div').hide();
			$('#roll_in_div').hide();
			$('#quarter_div').hide();
			$('#rec_type_div').hide();
			$('#service_date_main_div').hide();
			$('#list-price').attr("disabled", false);
			$('#discount-amount').attr("disabled", false);
			$('#quantity').attr("disabled", false); 
			$('#show_services_div_table').show(); 

			//empty child services table
			$('#child_services_table tbody tr').remove(); 

			showServicesList();
		}
	}
	
	
	function acc_save_rli(){
		
		var error = 0;
		var cid = document.getElementById("case_num").value;
		var uid = '<?php echo $userid ; ?>';
		var uname = '<?php echo $this->session->userdata('username'); ?>';
		
		var sale_level = $('#acc_sale_type').val();
		
		var servicer = $('#acc_servicer').val();
		var entity = $('#acc_entity').val();
		var billed_to = $('#acc_billed_to').val();
		var commission = $('#acc_commission').val();
		var recurring_type = $('#acc_rec_type').val();
		var service_start_date = $('#acc_service_date').val();		
		var service_type = $('#acc_services_type').val();
		var service_name_id = $('#acc_service_name').val();
		
		var service_id = service_name_id; 	
		var category_id = '';
		
		if(service_id != '' && service_id != null){
			
			var filter_val = main_service_names.filter(x => x.id === service_id);
			category_id = filter_val[0]['category_id'];
		}
		
		var service_name = $('#acc_service_name :selected').text();
		
		var service_type_txt = $('#services_type :selected').text();
		
		var serv_name = '';
		if(service_name != ''){
			
			var sn = service_name.split('- ');
			serv_name = sn[1];
		}
		
		var desc = $('#acc_description').val();
		var price = $('#acc_list-price').val();
		var qty = $('#acc_quantity').val();
		var disc_reason = $('#acc_discount_reason').val();
		var disc_amount = $('#acc_discount-amount').val();
		var calc_amt = $('#acc_calculated-amount').val();
		var years_list = $('#acc_years').val();
		
		var quarter_list = $('#acc_quarter').val();
		var rollin = $('#acc_reset_rollin').val();
		var flag_list = $('#acc_service_flag').val();
		
		var years_list2 = '';
		if(years_list){
			years_list2 = years_list.toString();
		}
		
		if(quarter_list){
			quarter_list = quarter_list.toString();
		}
		if(flag_list){
			flag_list = flag_list.toString();
		}
		
		if(sale_level == ''){
			
			error = 1;
            alert('Please select Sale Type');
            return false;
		}
		if(servicer == ''){
			
			error = 1;
			alert('Please select Servicer');
			return false;
		}
		if(entity == ''){
			
			error = 1;
			alert('Please select Entity');
			return false;
		}
		if(desc == ''){
			desc = 'null';
		}
		if(disc_reason == ''){
			disc_reason = 'null';
		}
		if(disc_amount == ''){
			disc_amount = 'null';
		}
		if(uid == ''){
			
			error = 1;
			alert('User Id not found');
			return false;
		}
		
		if(service_type == ''){
			
			error = 1;
			alert('Please select Service Type');
			return false;
		}
		
		if(service_name == ''){
			
			error = 1;
			alert('Please select Service Name');
			return false;
		}
		
		if(qty < 1){
			
			error = 1;
			alert('Quantity cannot be zero');
			return false;
		}
		
		if(price < 0){
			
			error = 1;
			alert('Price cannot be less than zero');
			return false;
		}
		
		if(serv_name == ''){
			
			error = 1;
			alert('Please select service name');
			return false;
		}
		
		if(error < 1){
			
			//alert('success');
			jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_new_service",
                dataType: 'json',
                
                data: {caseid: cid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, category_id: category_id, commission: commission, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list},
								
                success: function(res) 
                {	
					//console.log('new_rli- '+res);
					
					if(res.id != 0){
												
						var now     = new Date(); 
						var year    = now.getFullYear();
						var month   = now.getMonth()+1; 
						var day     = now.getDate();
						var hour    = now.getHours();
						var minute  = now.getMinutes();
						var second  = now.getSeconds(); 
						if(month.toString().length == 1) {
							 month = '0'+month;
						}
						if(day.toString().length == 1) {
							 day = '0'+day;
						}   
						if(hour.toString().length == 1) {
							 hour = '0'+hour;
						}
						if(minute.toString().length == 1) {
							 minute = '0'+minute;
						}
						if(second.toString().length == 1) {
							 second = '0'+second;
						}   
						var dateTime = year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second;  
	
						tbody = '';
						acc_tbody = '';
						common = '';
						
						tbody +=  "<tr id= '" + res.id + "'>" ;
						acc_tbody += "<tr id= '" + res.id + "'>" ;
						
						tbody += '<td><button class="btn btn-xs btn-edit teditservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_service(this);"><i class="glyph-icon icon-pencil"></i>&nbsp; &nbsp;<button class="btn btn-xs btn-danger tdelservice"  title="Delete"  style="background-color: #e74c3c;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></button></td>'; //id="' + res[i]['id'] + '"
								
						acc_tbody += '<td><button class="btn btn-xs btn-edit editservice"  title="Edit"  style="background-color: #48C6AC;" onclick="edit_acc_service(this);"><i class="glyph-icon icon-pencil"></i></button>&nbsp; &nbsp;<button class="btn btn-xs btn-danger delservice"  title="Delete"  style="background-color: #e74c3c;" onclick="delete_tab_service(this);"><i class="glyph-icon icon-remove"></i></button></td>'; 
						
						common +=	'<td>' + sale_level  + '</td> '
									+ '<td>' + servicer + '</td> '
									+ '<td>' + service_name + '</td> '
									+ '<td>' + qty + '</td> '
									+ '<td>' + price + '</td> ';
									
						if(disc_reason != 'null'){
                                    
                            common +=   '<td>' + disc_reason + '</td> ';
                        }
                        else{
                            
                            common +=   '<td></td> ';
                        }                               
                        
                        if(disc_amount > 0){
                                    
                            common +=   '<td>' + disc_amount + '</td> ';
                        }
                        else{
                            
                            common +=   '<td></td> ';
                        }
                        
                        common +=   '<td>' + calc_amt + '</td> '									
									+ '<td>' + uname + '</td> '
									+ '<td>' + dateTime + '</td> '									
									+ '<td></td> '
									+ '<td></td> '
									+ '<td></td> '
									+ '<td></td> ';
									
						if(desc != 'null'){
                                    
                            common +=   '<td>' + desc + '</td> ';
                        }
                        else{
                            
                            common +=   '<td></td> ';
                        }
						
						common +=  "</tr>";
						
						tbody += common;
						acc_tbody += common;						
						
						$('#services_table').prepend(tbody);
						$('#acc_services_table').prepend(acc_tbody);
						
						$.jGrowl("New Service Created Successfully.", {
                            sticky: false,
                            position: 'top-right',
                            theme: 'bg-green'
                        }); 
					}
					else{                        
 
                        $.jGrowl("Unable to add service.", {  
                            sticky: true,
                            position: 'top-right',
                            theme: 'bg-red'
                        });
                    }
				}
			})
			$('#acc_add_services').show();		
			$('#acc_service_name').val('');
			$('#acc_sale_type').val('');
			$('#acc_services_type').val('');			
			$('#acc_description').val('');
			$('#acc_list-price').val('');
			$('#acc_quantity').val('');
			$('#acc_discount-amount').val('');
			$('#acc_years').val('');
			$('#acc_calculated-amount').val('');
			$('#acc_year_main_div').hide();
			$('#acc_add_services_div').hide();
			$('#rec_type').val('');
			$('#acc_service_date').val('');
			$('#acc_quarter').val('');
			$('#acc_reset_rollin').val('');
			$('#acc_service_flag').val('');
			
			$('#acc_service_name').empty();
			$('#acc_services_type').empty();
			//$('#acc_reset_rollin').empty();
			
			$('#acc_service_flag').val(null).trigger('change');
			$('#acc_reset_rollin').val(null).trigger('change');
			$('#acc_quarter').val(null).trigger('change');
			$('#acc_years').val(null).trigger('change');
			
			$('#acc_flag_div').hide();
			$('#acc_roll_in_div').hide();
			$('#acc_quarter_div').hide();
			$('#acc_rec_type_div').hide();
			$('#acc_service_date_main_div').hide();
			
			
		}
	}
	
	function update_rli(data){   
		
		var cl = $(data).attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];
		
		$('#update_services').removeClass(rid);
		
		
		if(rid != ''){
			
			var error = 0;			
			var uid = '<?php echo $userid ; ?>';
			
			var sale_level = $('#sale_type').val();
			var service_type = $('#services_type').val();
			var service_name_id = $('#service_name').val();
			
			var service_id = service_name_id; 		
			var category_id = '';
			
			var servicer = $('#servicer').val();
			var entity = $('#entity').val();			
			var billed_to = $('#billed_to').val();
			var commission = $('#commission').val();
			var recurring_type = $('#rec_type').val();
			var service_start_date = $('#service_date').val();
		
			if(service_id != '' && service_id != null){
				
				//console.log('service_id - ' + service_id)
				//console.log('main_service_names - ' + main_service_names)
				var filter_val = main_service_names.filter(x => x.id === service_id);
				category_id = filter_val[0]['category_id'];
			}
		
			var service_name = $('#service_name :selected').text();
			
			var serv_name = '';
			var serv_type = '';
			if(service_name != ''){
				
				var sn = service_name.split('- ');
				serv_type = sn[0];
				serv_name = sn[1];
			}
			var desc = $('#description').val();
			var price = $('#list-price').val();
			var qty = $('#quantity').val();
			var disc_reason = $('#discount_reason').val();
			
			var disc_amount = $('#discount-amount').val();			
			var calc_amt = $('#calculated-amount').val();
			var years_list = $('#years').val(); 
			var state_list = $('#service_state').val(); 
			var quarter_list = $('#quarter').val();
			var rollin = $('#reset_rollin').val();
			var flag_list = $('#service_flag').val();
		//alert(disc_amount + ', ' + calc_amt);
		//return false;
			var years_list2 = '';
			if(years_list){
				years_list2 = years_list.toString();
			} 
			
			var state_list2 = '';
			if(state_list){
				state_list2 = state_list.toString();
			}
			if(quarter_list){
				quarter_list = quarter_list.toString();
			}
			if(flag_list){
				flag_list = flag_list.toString();
			}
			
			if(sale_level == ''){
				sale_level = 'null';
			}
			
			if(desc == ''){
				desc = 'null';
			}
			if(disc_reason == ''){
				disc_reason = 'null';
			}
			if(disc_amount == ''){
				disc_amount = 'null';
			}
			
			if(uid == ''){
				
				error = 1;
				alert('User Id not found');
				return false;
			}
		
			if(service_type == ''){
				
				error = 1;
				alert('Please select Service Type');
				return false;
			}
			
			if(serv_name == ''){
				
				error = 1;
				alert('Please select Service Name');
				return false;
			}
			if(servicer == ''){
			
				error = 1;
				alert('Please select Servicer');
				return false;
			}
			if(entity == ''){
				
				error = 1;
				alert('Please select Entity');
				return false;
			}
			if(qty < 1){
				
				error = 1;
				alert('Quantity cannot be zero');
				return false;
			}
			
			if(price < 0  || price == ''){
				
				price = 0;				
			}
			/*$.jGrowl("Updating Service", {
				sticky: false,
				position: 'center',
				theme: 'bg-green'
			});*/
			if(error < 1){
			
				jQuery.ajax({
					
					type: "POST",					
					url: "<?php echo base_url(); ?>" + "index.php/casedetail/update_service",
					dataType: 'json',
					async: false,
					data: {rid: rid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, state_list: state_list2, category_id: category_id, commission: commission, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list},
					
					success: function(res) 
					{
						if(res == true){
							
							$.jGrowl("Service Updated Successfully", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
						
							//showServicesList();
						}
						
					}
				})
				$('#add_services').show();		
				$('#sale_type').val('Case Review');
				$('#service_name').val('');
				$('#servicer').val('');
				$('#entity').val('');
				$('#description').val('');
				$('#list-price').val('');
				$('#quantity').val('');
				$('#discount-amount').val('');
				$('#discount_reason').val('');
				$('#years').val('');				
				$('#calculated-amount').val('');
				
				$('#service_date').val('');
				$('#quarter').val('');
				$('#reset_rollin').val('');
				$('#service_flag').val('');
				$('#commission').val('');

				$('#service_name').empty();
				$('#services_type').empty();
				//$('#reset_rollin').empty();
				
				$('#service_flag').val(null).trigger('change');
				$('#reset_rollin').val(null).trigger('change');
				$('#quarter').val(null).trigger('change');
				$('#years').val(null).trigger('change');
				$('#service_state').val(null).trigger('change');
				
				$('#flag_div').hide();
				$('#roll_in_div').hide(); 
				$('#year_main_div').hide();
				$('#state_main_div').hide();
				$('#quarter_div').hide();
				$('#rec_type_div').hide();
				$('#rec_type').val('');
				$('#service_date_main_div').hide();
				$('#add_services_div').hide();
				$('#update_services').hide();
				$('#save_services').show();

				$('#servicer').attr("disabled", false);
				$('#services_type').attr("disabled", false);
				$('#service_name').attr("disabled", false);
				$('#entity').attr("disabled", false);
				$('#list-price').attr("disabled", false);
				$('#discount-amount').attr("disabled", false);
				$('#show_services_div_table').show();

				$('#servicer').attr("disabled", false);
				$('#entity').attr("disabled", false);
				$('#services_type').attr("disabled", false);
				$('#service_name').attr("disabled", false);

				//empty child services table
				$('#child_services_table tbody tr').remove(); 

				showServicesList();
			}
		}
	}
	
	//accordion
	function acc_update_rli(data){
		
		var cl = $(data).attr("class");
		var classes = cl.split(" ");
		var rid = classes[2];
		
		$('#acc_update_services').removeClass(rid); 
		 
		if(rid != ''){
			
			var error = 0;			
			var uid = '<?php echo $userid ; ?>';
			
			var sale_level = $('#acc_sale_type').val();
			var service_type = $('#acc_services_type').val();
			var service_name_id = $('#acc_service_name').val();
			
			var servicer = $('#acc_servicer').val();
			var entity = $('#acc_entity').val();			
			var billed_to = $('#acc_billed_to').val();
			var commission = $('#acc_commission').val();
			var recurring_type = $('#acc_rec_type').val();
			var service_start_date = $('#acc_service_date').val();
			
			var service_id = service_name_id; 	
			var category_id = '';
			
			if(service_id != '' && service_id != null){
				
				var filter_val = main_service_names.filter(x => x.id === service_id);
				category_id = filter_val[0]['category_id'];
			}
			
			var service_name = $('#acc_service_name :selected').text();
			var serv_name = '';
			var serv_type = '';
			 
			if(service_name != ''){
				
				var sn = service_name.split('- ');
				serv_type = sn[0];
				serv_name = sn[1];
			}
			var desc = $('#acc_description').val();
			var price = $('#acc_list-price').val();
			var qty = $('#acc_quantity').val();
			var disc_reason = $('#acc_discount_reason').val();
			var disc_amount = $('#acc_discount-amount').val();
			var calc_amt = $('#acc_calculated-amount').val();			
			var quarter_list = $('#acc_quarter').val();
			var rollin = $('#acc_reset_rollin').val();
			var flag_list = $('#acc_service_flag').val();
			var years_list = $('#acc_years').val();
			
			if(quarter_list){
				quarter_list = quarter_list.toString();
			}
			if(flag_list){
				flag_list = flag_list.toString();
			}		
			
			var years_list2 = '';
			if(years_list){
				years_list2 = years_list.toString();
			}
			
			if(sale_level == ''){
				sale_level = 'null';
			}
			if(desc == ''){
				desc = 'null';
			}
			if(disc_reason == ''){
				disc_reason = 'null';
			}
			if(disc_amount == ''){
				disc_amount = 'null';
			}
			
			if(uid == ''){
				
				error = 1;
				alert('User Id not found');
				return false;
			}
		
			if(service_type == ''){
				
				error = 1;
				alert('Please select Service Type');
				return false;
			}
			if(servicer == ''){
			
				error = 1;
				alert('Please select Servicer');
				return false;
			}
			if(entity == ''){
				
				error = 1;
				alert('Please select Entity');
				return false;
			}
			if(serv_name == ''){
				
				error = 1;
				alert('Please select Service Name');
				return false;
			}
			
			if(qty < 1){
				
				error = 1;
				alert('Quantity cannot be zero');
				return false;
			}
			
			if(price < 0 || price == ''){
				
				price = 0;
			}
			
			if(error < 1){
			
				jQuery.ajax({
					
					type: "POST",					
					url: "<?php echo base_url(); ?>" + "index.php/casedetail/update_service",
					dataType: 'json',
					
					data: {rid: rid, sale_level: sale_level, service_type: service_type, service_name: serv_name, service_id: service_id, desc: desc, price: price, qty: qty, disc_reason: disc_reason, disc_amount: disc_amount, calc_amt: calc_amt, years_list: years_list2, category_id: category_id, commission: commission, billed_to: billed_to, entity: entity, servicer: servicer, recurring_type: recurring_type, service_start_date: service_start_date, quarter_list: quarter_list, rollin: rollin, flag_list: flag_list},
					
					success: function(res) 
					{
						if(res == true){
							
							 $.jGrowl("Service Updated Successfully", {
                                sticky: false,
                                position: 'top-right',
                                theme: 'bg-green'
                            });
                        
                            showServicesList(); 
						}
					}
				})
				$('#acc_add_services').show();		
				$('#acc_service_name').val('');
				$('#acc_description').val('');
				$('#acc_list-price').val('');
				$('#acc_quantity').val('');
				$('#acc_discount-amount').val('');
				$('#acc_years').val('');
				$('#acc_calculated-amount').val('');
				$('#acc_add_services_div').hide();
				$('#acc_update_services').hide();
				$('#acc_save_services').show();

				$('#acc_service_date').val('');
				$('#acc_quarter').val('');
				$('#acc_reset_rollin').val('');
				$('#acc_service_flag').val('');
				$('#acc_commission').val('');
				
				$('#acc_service_name').empty();
				$('#acc_services_type').empty();
				//$('#acc_reset_rollin').empty();
				
				$('#acc_quarter').val(null).trigger('change');
				$('#acc_years').val(null).trigger('change');
				$('#acc_reset_rollin').val(null).trigger('change');
				$('#acc_service_flag').val(null).trigger('change');
				
				$('#acc_flag_div').hide();
				$('#acc_roll_in_div').hide();
				$('#acc_quarter_div').hide();
				$('#acc_rec_type_div').hide();
			}
		}
		//console.log('cl '+rid[2]);
	}
	
	//Expense detail
	function saveExpenseDetails(){
		
		var error = 0;
		var cid = document.getElementById("case_num").value;       
		var other_exp_array = [];
		var extra_exp_array = []; 
		var extra_exp_json_arr = {}; 
		var total_expense = 0;
		var i = 0; 
		var j = 0;
				
		$(".extra_expense_fields").each(function(){
			
			var add_expense = $(this).attr('id')
			var add_expense1 = add_expense.split("_");
			
			if(add_expense1[0] == 'extraexpensetext')
			{
				extra_exp_array[j] = {};
				extra_exp_array[j]['text'] = $("#"+add_expense).val();
				
				if(extra_exp_array[j]['text'].length <= 0){
					
					alert('Extra expense string cannot be empty. Data not saved');
					$('#' + add_expense).focus();					
				}
			}
			if(add_expense1[0] == 'extraexpenseval')
			{
				extra_exp_array[j]['val'] = $("#"+add_expense).val();
				if(extra_exp_array[j]['val'] > 0){
					
					total_expense += parseFloat(extra_exp_array[j]['val']);
				}
				j++; 
			}
		});
		
		var extra_exp_json = '';
		var len = Object.keys(extra_exp_array).length; 
		//alert('len - ' + len)
		if(len){	
			
			extra_exp_json = JSON.stringify(extra_exp_array);	
			//console.log('extra_exp_json123 - ' + extra_exp_json);
		}		
		if(extra_exp_json == ''){
			
			extra_exp_json = 'null';
		}
		
		var k=0;
		$(".oth_expense_input_text").each(function(){
			
			other_exp_array[k] = {};
			
			var oid = $(this).attr('id')
			
			var oid1 = oid.split("_");
			var text = $("#"+oid).val();
			
			other_exp_array[k]['id'] = oid1[0];
			other_exp_array[k]['text'] = text;
			
			//console.log('oid1 - ' + other_exp_array[k]['id'] + 'text - ' + other_exp_array[k]['text']);
			k++;
		});
		
		var len_text= other_exp_array.length;
		//alert('len_text - ' + len_text);
		$(".oth_expense_input_val").each(function(){
			
			var oid = $(this).attr('id')
			var oid1 = oid.split("_");	
			//console.log('oid - ' + oid);
			for(l=0; l<len_text; l++){
				
				if(other_exp_array[l]['id'] == oid1[0]){
					
					var amt = $("#"+oid).val();
					other_exp_array[l]['value'] = amt;
					//console.log('other_exp_array - ' + other_exp_array[l]['id'] + ', ' +other_exp_array[l]['text'] + ', ' +other_exp_array[l]['value']);
				}
			}
		});		
		
		var other_exp_json = '';
		var len = Object.keys(other_exp_array).length; 
		
		if(len){	
			
			other_exp_json = JSON.stringify(other_exp_array);	
			//console.log('other_exp_json123 - ' + other_exp_json);
		}		
		if(other_exp_json == ''){
			
			other_exp_json = 'null';
		}
				
		var under65 = $("#under65").val();
		var over65 = $("#over65").val();
		var fcm = 0; 
		var mortgage_pay = $("#mortgage_payment").val();
		var rent_pay = $("#rent_pay").val();
		var utilities_pay = $("#utilities_input").val(); 
		var pic_pay = $("#pic_pay").val();
		var stated_housing = $("#stated_housing_exp").val();
		var irs_stand = $("#irs_stand").val();
		var housing_exp = $("#housing_exp").val();
		var public_trans = $("#public_trans").val();
		var num_vehicle = $("#num_vehicle").val();		
		var prescp_copay = $("#prescp_copay").val();
		var health_ins = $("#health_ins").val();
		var total_health = $("#total_health").val();
		var taxes = 0; 
		var court_ordered = 0; 
		var child_care = 0;//$("#child_care").val();
		var whole_life_ins = 0; 
		var public_trans_client = $("#pt_client").val();
		var public_trans_spouse = $("#pt_spouse").val();
		
		//new
		var other_vehicle_exp1 = $("#other_vehicle_exp1").val();
		var other_vehicle_exp2 = $("#other_vehicle_exp2").val();
		
		var vehicle_exp1 = $("#other_vehicle_exp1").val();
		var vehicle_exp2 = $("#other_vehicle_exp2").val(); 
		
		var exp_id_1 = $('#other_vehicle_exp1').data('vehicle_id'); 
		var exp_id_2 = $('#other_vehicle_exp2').data('vehicle_id'); 
		
		var public_transportation = parseInt(public_trans_client) + parseInt(public_trans_spouse);
		
		//get IRS values
		var irs_public_trans = $('.irs_public_trans').text();
		irs_public_trans = irs_public_trans.substring(0,3);
		
		var irs_auto_ownership = $('.auto_own_irs').text();
		irs_auto_ownership = irs_auto_ownership.substring(0,3);
		
		var irs_expense_ins = $('.auto_ei_irs').text();
		irs_expense_ins = irs_expense_ins.substring(0,3);
		
		if(public_transportation > parseInt(irs_public_trans)){
			
			public_transportation = irs_public_trans;
		}
		if(other_vehicle_exp1 > parseInt(irs_auto_ownership)){
			
			other_vehicle_exp1 = irs_auto_ownership;
		}
		if(other_vehicle_exp2 > parseInt(irs_auto_ownership)){
			
			other_vehicle_exp2 = irs_auto_ownership;
		}
		
		if(other_vehicle_exp1 > 0 || other_vehicle_exp2 > 0){
			
			if(other_vehicle_exp2 != ''){
				
				if(irs_expense_ins != ''){
				
					var tot_auto_exp = parseInt(other_vehicle_exp1) + parseInt(other_vehicle_exp2)  + parseInt(irs_expense_ins); 
				}
				else{
					
					var tot_auto_exp = parseInt(other_vehicle_exp1) + parseInt(other_vehicle_exp2) ;
				}
				
			}
			else{
				
				if(irs_expense_ins != ''){
					var tot_auto_exp = parseFloat(other_vehicle_exp1) + parseFloat(irs_expense_ins);
				}
				else{
					
					var tot_auto_exp = parseFloat(other_vehicle_exp1);
				}
				
			}
		}
		else{
			
			if(irs_expense_ins != ''){
				
				var tot_auto_exp = parseFloat(irs_expense_ins) + parseFloat(public_transportation);
			}
			else{
				if(public_transportation != ''){
					
					var tot_auto_exp = parseFloat(public_transportation);
				}
				else{
					
					var tot_auto_exp = 0;
				}
			}
		}
		
		total_expense += parseInt(prescp_copay) + parseInt(health_ins) 
							+ parseInt(housing_exp) + parseInt(tot_auto_exp) ;
		
		var total_health_new = parseInt(prescp_copay) + parseInt(health_ins);
		
        if(cid == ''){
            error = 1;
            alert('Caseid not found');
        }
        
		//return true;
        if(error < 1){ 
            
			$('.save_expenses_id').attr("disabled", true);
			
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_expense_details",
                dataType: 'json',
				data: {caseid: cid, under65: under65, over65: over65, rent_pay: rent_pay, prescp_copay: prescp_copay, health_ins: health_ins, total_health: total_health_new, other_exp_json: other_exp_json, public_trans_client: public_trans_client,  public_trans_spouse: public_trans_spouse, utilities: utilities_pay, pic: pic_pay, other_vehicle_exp1: vehicle_exp1, other_vehicle_exp2: vehicle_exp2, exp_id_1: exp_id_1, exp_id_2: exp_id_2, extra_exp_json: extra_exp_json},
				
                success: function(res) 
                {				
					if(res == -1){
						
						$.jGrowl("Stated Interview Not Created In Sugar. Save county in Stated Interview.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-red'
						}); 
						$('.save_expenses_id').attr("disabled", false);
					}
					else{
						$.jGrowl("Data updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						refresh_data();
						$('.save_expenses_id').attr("disabled", false);
					}						
                }
            });
        } 
	}
		
	function saveTabExpenseDetails(){
		
		var error = 0;
		var cid = document.getElementById("case_num").value;
		var other_exp_array = []; 
		var extra_exp_array = []; 
		var extra_exp_json_arr = {}; 
		var total_expense = 0;
		var i = 0;
		var j = 0;
		
		$(".extra_expense_fields").each(function(){
			
			var add_expense = $(this).attr('id')
			var add_expense1 = add_expense.split("_");
			
			if(add_expense1[0] == 'extraexpensetext')
			{
				extra_exp_array[j] = {};
				extra_exp_array[j]['text'] = $("#"+add_expense).val();
				
				if(extra_exp_array[j]['text'].length <= 0){
					
					alert('Extra expense string cannot be empty. Data not saved');
					$('#' + add_expense).focus();					
				}
			}
			if(add_expense1[0] == 'extraexpenseval')
			{
				extra_exp_array[j]['val'] = $("#"+add_expense).val();
				if(extra_exp_array[j]['val'] > 0){
					
					total_expense += parseFloat(extra_exp_array[j]['val']);
				}
				j++; 
			}
		});
		
		var extra_exp_json = '';
		var len = Object.keys(extra_exp_array).length; 
		
		if(len){	
			
			extra_exp_json = JSON.stringify(extra_exp_array);	
			//console.log('extra_exp_json - ' + extra_exp_json);
		}		
		if(extra_exp_json == ''){
			
			extra_exp_json = 'null';
		}
		
		var k=0;
		$(".oth_expense_input_tab_text").each(function(){
			
			other_exp_array[k] = {};
			
			var oid = $(this).attr('id')
			
			var oid1 = oid.split("_");
			var text = $("#"+oid).val();
			
			other_exp_array[k]['id'] = oid1[0];
			other_exp_array[k]['text'] = text;
			
			k++;
		});
		
		var len_text= other_exp_array.length;
		
		$(".oth_expense_input_tab_val").each(function(){
			
			var oid = $(this).attr('id')
			var oid1 = oid.split("_");	
			
			for(l=0; l<len_text; l++){
				
				if(other_exp_array[l]['id'] == oid1[0]){
					
					var amt = $("#"+oid).val();
					other_exp_array[l]['value'] = amt;
				}
			}
		});		
		
		var other_exp_json = '';
		var len = Object.keys(other_exp_array).length; 
		
		if(len){	
			
			other_exp_json = JSON.stringify(other_exp_array);	
			//console.log('other_exp_json - ' + other_exp_json);
		}		
		if(other_exp_json == ''){
			
			other_exp_json = 'null';
		}
		
		var under65 = $("#tab_under65").val();
		var over65 = $("#tab_over65").val();
		var fcm = 0; 
		var mortgage_pay = $("#tab_mortgage_payment").val();
		var rent_pay = $("#tab_rent_pay").val();
		var utilities_pay = $("#tab_utilities_input").val(); 
		var pic_pay = $("#tab_pic_pay").val();
		var stated_housing = $("#tab_stated_housing_exp").val();
		var irs_stand = $("#tab_irs_stand").val();
		var housing_exp = $("#tab_housing_exp").val();
		var public_trans = $("#tab_public_trans").val();		
				
		var num_vehicle = $("#tab_num_vehicle").val();		
		var prescp_copay = $("#tab_prescp_copay").val();
		var health_ins = $("#tab_health_ins").val();
		var total_health = $("#tab_total_health").val();
		var taxes = 0; 
		var court_ordered = 0; 
		var child_care = 0; 
		var whole_life_ins = 0; 
		var public_trans_client = $("#tab_pt_client").val();
		var public_trans_spouse = $("#tab_pt_spouse").val();
		
		var other_vehicle_exp1 = $("#tab_other_vehicle_exp1").val();
		var other_vehicle_exp2 = $("#tab_other_vehicle_exp2").val();
		
		var vehicle_exp1 = $("#tab_other_vehicle_exp1").val();
		var vehicle_exp2 = $("#tab_other_vehicle_exp2").val();
		
		var exp_id_1 = $('#tab_other_vehicle_exp1').data('vehicle_id'); 
		var exp_id_2 = $('#tab_other_vehicle_exp2').data('vehicle_id');  
		var public_transportation = parseInt(public_trans_client) + parseInt(public_trans_spouse);
		
		//get IRS values
		var irs_public_trans = $('.irs_public_trans').text();
		irs_public_trans = irs_public_trans.substring(0,3);
		
		var irs_auto_ownership = $('.auto_own_irs').text();
		irs_auto_ownership = irs_auto_ownership.substring(0,3);
		
		var irs_expense_ins = $('.auto_ei_irs').text();
		irs_expense_ins = irs_expense_ins.substring(0,3);		
		
		if(public_transportation > parseInt(irs_public_trans)){
			
			public_transportation = irs_public_trans;
		}
		if(other_vehicle_exp1 > parseInt(irs_auto_ownership)){
			
			other_vehicle_exp1 = irs_auto_ownership;
		}
		if(other_vehicle_exp2 > parseInt(irs_auto_ownership)){
			
			other_vehicle_exp2 = irs_auto_ownership;
		}
		
		if(other_vehicle_exp1 > 0 || other_vehicle_exp2 > 0){
			
			if(other_vehicle_exp2 != ''){
				
				if(irs_expense_ins != ''){
				
					var tot_auto_exp = parseInt(other_vehicle_exp1) + parseInt(other_vehicle_exp2)  + parseInt(irs_expense_ins); 
				}
				else{
					
					var tot_auto_exp = parseInt(other_vehicle_exp1) + parseInt(other_vehicle_exp2) ;
				}
				
			}
			else{
				
				if(irs_expense_ins != ''){
					var tot_auto_exp = parseFloat(other_vehicle_exp1) + parseFloat(irs_expense_ins);
				}
				else{
					
					var tot_auto_exp = parseFloat(other_vehicle_exp1);
				}
				
			}
		}
		else{
			
			if(irs_expense_ins != ''){
				
				var tot_auto_exp = parseFloat(irs_expense_ins) + parseFloat(public_transportation);
			}
			else{
				if(public_transportation != ''){
					
					var tot_auto_exp = parseFloat(public_transportation);
				}
				else{
					
					var tot_auto_exp = 0;
				}
			}
		}
		
		total_expense += parseInt(prescp_copay) + parseInt(health_ins) 
							+ parseInt(housing_exp) + parseInt(tot_auto_exp) ;

		var total_health_new = parseInt(prescp_copay) + parseInt(health_ins);
		      
        if(cid == ''){
            error = 1;
            alert('Caseid not found');
        }
        		
        if(error < 1){
            
			$('.save_expenses_tabid').attr("disabled", true);
			
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_expense_details",
                dataType: 'json',
                data: {caseid: cid, under65: under65, over65: over65, rent_pay: rent_pay, prescp_copay: prescp_copay, health_ins: health_ins, total_health: total_health_new, other_exp_json: other_exp_json, public_trans_client: public_trans_client,  public_trans_spouse: public_trans_spouse, utilities: utilities_pay, pic: pic_pay, other_vehicle_exp1: vehicle_exp1, other_vehicle_exp2: vehicle_exp2, exp_id_1: exp_id_1, exp_id_2: exp_id_2, extra_exp_json: extra_exp_json},		
                success: function(res) 
                {
					if(res == -1){
						
						$.jGrowl("Stated Interview Not Created In Sugar. Save county in Stated Interview.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-red'
						}); 
						$('.save_expenses_tabid').attr("disabled", false);
					}
					else{
						$.jGrowl("Data updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						refresh_data();
						$('.save_expenses_tabid').attr("disabled", false);
					}
                }
            });
        }
	}
	
	//Summary Details
	function saveSummaryDetails(){
		
		var error = 0;
		var cid = document.getElementById("case_num").value;
		var inves_pps_id = $("#inves_pps_id").val();
		var account_stat = $("#ac_stat").val();
		var compliance_val = $("#compliant").val();
		var analysis1_val = $("#analysis1").val();
		var analysis2_val = $("#analysis2").val();
		var analysis3_val = $("#analysis3").val();
		var di_val = $(".input_di").val();
		var cr_fee_val = $(".input_cr_fee").val();
		var add_service_fee_val = $(".add_service_fee").val();			
		//var cr_rec_val = $(".input_cr_recmd").val();
		
		var aggregate_id = $('#aggregate_id').val();
		var spec_service = $("#specified_service").val();     
        var prelim_rec_type = $("#prelim_rec_type").val();        
        var final_rec_type = $("#final_rec_type").val();      
        var prelim_fee = $(".prelim_fee").val();
		
		//alert(aggregate_id + ',' + account_stat + ', ' + compliance_val + ', ' +  analysis1_val  + ', ' + analysis2_val+ ', ' +  analysis3_val + ', ' + di_val);
		//return 1;
		if(cid == ''){
            error = 1;
            alert('Caseid not found');
			return false;
        }
		if(inves_pps_id == ''){
            //error = 1;
            alert('PPS Id not found in Sugar  or PPS not created. Only CR fee and Recommendationwill be saved. ');
			//return false;
        }
        
        if(error < 1){
                                        
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_summary_details",
                dataType: 'json',
                data: {caseid: cid, inves_pps_id: inves_pps_id, acc_stat: account_stat, compliance: compliance_val, analysis1: analysis1_val, analysis2: analysis2_val, analysis3: analysis3_val, di: di_val, cr_fee_val: cr_fee_val, spec_service: spec_service, prelim_rec_type: prelim_rec_type, final_rec_type: final_rec_type, prelim_fee: prelim_fee, aggregate_id: aggregate_id, add_service_fee_val: add_service_fee_val},
                success: function(res) 
                {									
					//dynamically update tab view
					//alert('hello - ' + res);
					//di
					$('.tab_input_di').val(di_val); 
					$('.tab_input_cr_fee').val(cr_fee_val); 
					$('.tab_add_service_fee').val(add_service_fee_val); 
										
					//account status
					$('#tab_ac_stat').remove(); 
					//$('#save_ac_stat').remove();
					var ac_status = ''; 
					
					ac_status += '<select class="form-control" name="tab_ac_stat" id="tab_ac_stat">		<option value="">Select</option>';
					if(account_stat == 'IA'){
						ac_status += '<option value="IA" selected>IA</option>';	
					}
					else{
						ac_status += '<option value="IA">IA</option>';
					}
					if(account_stat == 'Defaulted IA'){
                        ac_status += '<option value="Defaulted IA" selected>Defaulted IA</option>';   
                    }
                    else{
                        ac_status += '<option value="Defaulted IA">Defaulted IA</option>';
                    }
                    if(account_stat == 'First Notice'){
                        ac_status += '<option value="First Notice" selected>First Notice</option>';   
                    }
                    else{
                        ac_status += '<option value="First Notice">First Notice</option>';
                    }
                    if(account_stat == '2nd NOITL'){
                        ac_status += '<option value="2nd NOITL" selected>2nd NOITL</option>'; 
                    }
                    else{
                        ac_status += '<option value="2nd NOITL">2nd NOITL</option>';
                    }
                    if(account_stat == '3rd FNOITL'){
                        ac_status += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';   
                    }
                    else{
                        ac_status += '<option value="3rd FNOITL">3rd FNOITL</option>';
                    }
					if(account_stat == '4th NOL'){
                        ac_status += '<option value="4th NOL" selected>4th NOL</option>'; 
                    }
                    else{
                        ac_status += '<option value="4th NOL">4th NOL</option>';
                    }
                    if(account_stat == 'ACS'){
                        ac_status += '<option value="ACS" selected>ACS</option>'; 
                    }
                    else{
                        ac_status += '<option value="ACS">ACS</option>';
                    }
                    if(account_stat == '3rd Party'){
                        ac_status += '<option value="3rd Party" selected>3rd Party</option>'; 
                    }
                    else{
                        ac_status += '<option value="3rd Party">3rd Party</option>';
                    }
					if(account_stat == 'CNC'){
						ac_status += '<option value="CNC" selected>CNC</option>';	
					}
					else{
						ac_status += '<option value="CNC">CNC</option>';
					}					
					if(account_stat == 'RO'){
						ac_status += '<option value="RO" selected>RO</option>';	
					}
					else{
						ac_status += '<option value="RO">RO</option>';
					}
					if(account_stat == 'Exam'){
						ac_status += '<option value="Exam" selected>Exam</option>';	
					}
					else{
						ac_status += '<option value="Exam">Exam</option>';
					}					
					ac_status += '</select>';
					
					$('.tab_ac_stat_div').html(ac_status);
					
					//compliance
					$('#tab_compliant').remove(); 
					$('.tab_compl_sum').remove();
					
					var compliance_div = ''; 
					compliance_div += '<select class="form-control" name="tab_compliant" id="tab_compliant">		<option value="">Select</option>';
					if(compliance_val == 'Compliant'){
						compliance_div += '<option value="Compliant" selected>Compliant</option>';	
					}
					else{
						compliance_div += '<option value="Compliant">Compliant</option>';
					}
					if(compliance_val == 'Non Compliant'){
						compliance_div += '<option value="Non Compliant" selected>Non Compliant</option>	';	
					}
					else{
						compliance_div += '<option value="Non Compliant">Non Compliant</option>	';
					}					
					compliance_div += '</select>';
					
					$('.tab_comp_sum').html(compliance_div);
					
					//analysis 1
					$('#tab_analysis1').remove(); 
					//$('#tab_save_ana1').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="tab_analysis1" id="tab_analysis1">	<option value="">Select</option>';
					if(analysis1_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis1_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#tab_ana1').append(analysis_div);
					
					//analysis 2
					$('#tab_analysis2').remove(); 
					//$('#tab_save_ana2').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="tab_analysis2" id="tab_analysis2">	<option value="">Select</option>';
					if(analysis2_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis2_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#tab_ana2').append(analysis_div);
					
					//analysis 3
					$('#tab_analysis3').remove(); 
					//$('#tab_save_ana3').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="tab_analysis3" id="tab_analysis3">	<option value="">Select</option>';
					if(analysis3_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis3_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#tab_ana3').append(analysis_div);		
					
					
                    $.jGrowl("Data updated.", {
                        sticky: false,
                        position: 'top-right',
                        theme: 'bg-green'
                    });                    
                }
            });
        }
	}

	function saveTabSummaryDetails(){
		
		var error = 0;
		var cid = document.getElementById("case_num").value;
		
		var inves_pps_id = $("#tab_inves_pps_id").val();
		var account_stat = $("#tab_ac_stat").val();
		var compliance_val = $("#tab_compliant").val();
		var analysis1_val = $("#tab_analysis1").val();
		var analysis2_val = $("#tab_analysis2").val();
		var analysis3_val = $("#tab_analysis3").val();
		var di_val = $(".tab_input_di").val();	
		var cr_fee_val = $(".tab_input_cr_fee").val();		
		var add_service_fee_val = $(".tab_add_service_fee").val();		
		//var cr_rec_val = $(".tab_input_cr_recmd").val();	
		
		var aggregate_id = $('#tab_aggregate_id').val();
		var spec_service = $("#tab_specified_service").val();     
        var prelim_rec_type = $("#tab_prelim_rec_type").val();        
        var final_rec_type = $("#tab_final_rec_type").val();      
        var prelim_fee = $(".tab_prelim_fee").val();   
		
		//alert(aggregate_id + ', ' + spec_service + ', ' + prelim_rec_type + ', ' +  final_rec_type  + ', ' + prelim_fee+ ', ' +  inves_pps_id );
		//return 1;
		if(cid == ''){
            error = 1;
            alert('Caseid not found');
			return false;
        }
		
		if(inves_pps_id == ''){
            //error = 1;
            alert('PPS Id of Sugar not found or PPS not created. Only CR fee and Recommendationwill be saved');
			//return false;
        }
        //alert('hello');
        if(error < 1){
                                        
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_summary_details",
                dataType: 'json',
                data: {caseid: cid, inves_pps_id: inves_pps_id, acc_stat: account_stat, compliance: compliance_val, analysis1: analysis1_val, analysis2: analysis2_val, analysis3: analysis3_val, di: di_val, cr_fee_val: cr_fee_val, spec_service: spec_service, prelim_rec_type: prelim_rec_type, final_rec_type: final_rec_type, prelim_fee: prelim_fee, aggregate_id: aggregate_id, add_service_fee_val: add_service_fee_val},
                async: false,
				success: function(res) 
                {									
					//dynamically update tab view
					//alert('hello 2');
					//di
					$('.input_di').val(di_val);
					$('.input_cr_fee').val(cr_fee_val);
					$('.add_service_fee').val(add_service_fee_val);
					
					//account status
					$('#ac_stat').remove(); 
					//$('#save_ac_stat').remove();
					var ac_status = ''; 
					
					ac_status += '<select class="form-control" name="ac_stat" id="ac_stat">		<option value="">Select</option>';
					if(account_stat == 'IA'){
						ac_status += '<option value="IA" selected>IA</option>';	
					}
					else{
						ac_status += '<option value="IA">IA</option>';
					}
					if(account_stat == 'Defaulted IA'){
                        ac_status += '<option value="Defaulted IA" selected>Defaulted IA</option>';   
                    }
                    else{
                        ac_status += '<option value="Defaulted IA">Defaulted IA</option>';
                    }
                    if(account_stat == 'First Notice'){
                        ac_status += '<option value="First Notice" selected>First Notice</option>';   
                    }
                    else{
                        ac_status += '<option value="First Notice">First Notice</option>';
                    }
                    if(account_stat == '2nd NOITL'){
                        ac_status += '<option value="2nd NOITL" selected>2nd NOITL</option>'; 
                    }
                    else{
                        ac_status += '<option value="2nd NOITL">2nd NOITL</option>';
                    }
                    if(account_stat == '3rd FNOITL'){
                        ac_status += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';   
                    }
                    else{
                        ac_status += '<option value="3rd FNOITL">3rd FNOITL</option>';
                    }
					if(account_stat == '4th NOL'){
                        ac_status += '<option value="4th NOL" selected>4th NOL</option>'; 
                    }
                    else{
                        ac_status += '<option value="4th NOL">4th NOL</option>';
                    }
                    if(account_stat == 'ACS'){
                        ac_status += '<option value="ACS" selected>ACS</option>'; 
                    }
                    else{
                        ac_status += '<option value="ACS">ACS</option>';
                    }
                    if(account_stat == '3rd Party'){
                        ac_status += '<option value="3rd Party" selected>3rd Party</option>'; 
                    }
                    else{
                        ac_status += '<option value="3rd Party">3rd Party</option>';
                    }
                    if(account_stat == 'CNC'){
                        ac_status += '<option value="CNC" selected>CNC</option>'; 
                    }
                    else{
                        ac_status += '<option value="CNC">CNC</option>';
                    }                   
                    if(account_stat == 'RO'){
                        ac_status += '<option value="RO" selected>RO</option>';   
                    }
                    else{
                        ac_status += '<option value="RO">RO</option>';
                    }
                    if(account_stat == 'Exam'){
                        ac_status += '<option value="Exam" selected>Exam</option>';   
                    }
                    else{
                        ac_status += '<option value="Exam">Exam</option>';
                    }
					ac_status += '</select>';
					
					$('.ac_stat_div').html(ac_status);
					
					//compliance
					$('#compliant').remove(); 
					$('.compl_sum').remove();
					
					var compliance_div = ''; 
					compliance_div += '<select class="form-control" name="compliant" id="tab_compliant">		<option value="">Select</option>';
					if(compliance_val == 'Compliant'){
						compliance_div += '<option value="Compliant" selected>Compliant</option>';	
					}
					else{
						compliance_div += '<option value="Compliant">Compliant</option>';
					}
					if(compliance_val == 'Non Compliant'){
						compliance_div += '<option value="Non Compliant" selected>Non Compliant</option>	';	
					}
					else{
						compliance_div += '<option value="Non Compliant">Non Compliant</option>	';
					}					
					compliance_div += '</select>';
					
					$('.comp_sum').html(compliance_div);
					
					//analysis 1
					$('#analysis1').remove(); 
					//$('#tab_save_ana1').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="analysis1" id="analysis1">	<option value="">Select</option>';
					if(analysis1_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis1_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#ana1').append(analysis_div);
					
					//analysis 2
					$('#analysis2').remove(); 
					//$('#tab_save_ana2').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="analysis2" id="analysis2">	<option value="">Select</option>';
					if(analysis2_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis2_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#ana2').append(analysis_div);
					
					//analysis 3
					$('#analysis3').remove(); 
					//$('#tab_save_ana3').remove();
					var analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="analysis3" id="analysis3">	<option value="">Select</option>';
					if(analysis3_val == 'Stated'){
						analysis_div += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						analysis_div += '<option value="Stated">Stated</option>';
					}
					if(analysis3_val == 'Verified'){
						analysis_div += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						analysis_div += '<option value="Verified">Verified</option>';
					}					
					analysis_div += '</select>';
										
					$('#ana3').append(analysis_div);
					
                    $.jGrowl("Data updated.", {
                        sticky: false,
                        position: 'top-right',
                        theme: 'bg-green'
                    });                    
                }
            });
        }
	}

	// Summary Note Accordion
	function add_note_accordion() {
		
		var note_txt = $('#note_txt2').val();
		var note_type = $('#note_type').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;

		if(note_txt == "")
		{
			$("#note_txt2").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_newnote",
			type: "POST",
			data: {'case_id':  cid,	'note_txt': note_txt, 'note_type': note_type}, 
			success: function (data) {
				
				if(data != 0){
					
					$.jGrowl("Note Added.", {
					  sticky: false,
					  position: 'top-right',
					  theme: 'bg-green'
					});
						
					$("#note_txt").val('');
					$("#note_txt2").val('');
					
					var pnote = note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>';
					var pid = data;
					var html = '';
					var html_tab = '';
					var html_service = '';
					var html_service_tab = '';
					var html_assign = '';
					
					if(note_type == 'Case Review'){
						
						html += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-save deletenote" id="d' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-save tdeletenote" id="td' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("example").insertAdjacentHTML('afterbegin', html);
						document.getElementById("tabsumnote").insertAdjacentHTML('afterbegin', html_tab);
					}
					else if(note_type == 'Add Service'){
						
						html_service += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-save deletenote" id="d' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_service_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-save tdeletenote" id="td' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("servicenote").insertAdjacentHTML('afterbegin', html_service);
						document.getElementById("tabservicenote").insertAdjacentHTML('afterbegin', html_service_tab);						
					}
					else if(note_type == 'Assignment Note'){
						
						//html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-save deletenote" id="d' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-save tdeletenote" id="td' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("tabassignmentnote").insertAdjacentHTML('afterbegin', html_assign);
					}
				}
				else{
					
					$.jGrowl("Unable to add note.", {
					  sticky: true,
					  position: 'top-right',
					  theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//Summary Note Tab	
	function add_note() {
		
		var note_txt = $('#note_txt').val();
		var note_type = $('#tab_note_type').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;

		if(note_txt == "")
		{
			$("#note_txt").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_newnote",
			type: "POST",
			data: {'case_id':  cid,	'note_txt': note_txt, 'note_type': note_type}, 
			success: function (data) {
				
				if(data != 0){
					
					$.jGrowl("Note Added.", {
					  sticky: false,
					  position: 'top-right',
					  theme: 'bg-green'
					});
						
					$("#note_txt").val('');
					$("#note_txt2").val('');
					var pnote = note_txt + "&#10;" + dt + ', <?php echo $this->session->userdata('username'); ?>';
					var pid = data;
				
					var html = '';
					var html_tab = '';
					var html_service = '';
					var html_service_tab = '';
					var html_assign = '';
					
					if(note_type == 'Case Review'){
						
						html += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> </button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("example").insertAdjacentHTML('afterbegin', html);
						document.getElementById("tabsumnote").insertAdjacentHTML('afterbegin', html_tab);
					}
					else if(note_type == 'Add Service'){
						
						html_service += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> </button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_service_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("servicenote").insertAdjacentHTML('afterbegin', html_service);
						document.getElementById("tabservicenote").insertAdjacentHTML('afterbegin', html_service_tab);
					}
					else if(note_type == 'Assignment Note'){
						
						//html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-save deletenote" id="d' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-save tdeletenote" id="td' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + pid + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
						document.getElementById("tabassignmentnote").insertAdjacentHTML('afterbegin', html_assign);
					}
				}
				else{
					
					$.jGrowl("Unable to add note.", {
					  sticky: true,
					  position: 'top-right',
					  theme: 'bg-red'
					});
				}
			}
		});
	}
	// End of Summary functions
	
	
	//PPS FUNCTIONS
	/*function savePPSSugarDetails(){
		
		var error= 0;
        var case_id = $('#cid').val(); 
		
		var i = 0;
		var j = 0;		
		var td_value=[]
		var year_id={};
		var pps_data_input=[]
		var total_bal = 0;
		
		var ppsid = $('#pps_sugar_table').attr('name'); 		
		
		$("#pps_sugar_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			//console.log('year- '+year + 'class- ' + year_id[year])
			var tot_present = 0;			
			
			if(year){
				
				var j = 0;
				$("#" + year).find("input:text").each(function()				
				{					
					td_value[j] = $(this).val(); 
					console.log(td_value[j]);
					j++; 
					
				});
				
				$("#" + year).find("select").each(function()				
				{					
					td_value[j] = $(this).val();						
					j++;					
				});
				
				pps_data_input[i] = JSON.stringify(td_value);
				
				if(td_value[0] != 'Total'){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}								
			}			
			i++;        
		});
		
		var year_id_json = JSON.stringify(year_id);
		
		if(error < 1){
			
            jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_sugar_details",
                //dataType: 'json',
                data: {caseid: case_id, pps_data_input: pps_data_input,  year_id_json: year_id_json, pps_id: ppsid},
				
                success: function(res) 
                {
                    console.log(res);
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					
					$("#total_balance").val(total_bal);
					//$("#tab_pps_table #total_balance").val(total_bal);
					
					
                }
            });
        }		
	}*/
	
	function savePPSDetails(){
		
		var error= 0;
		var case_id = $('#cid').val(); 		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new=[];
		var year_id={};		
		var pps_existing_data ='';
		var pps_new_data ='';
			
		var ppsid = $('#pps_table').attr('name'); 
		//console.log(ppsid);
		
		$('#save_pps_data').attr("disabled", true);
		$('#save_tab_pps_data').attr("disabled", true);
				
		$("#pps_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			//console.log('year- '+year + 'class- ' + year_id[year])
			var tot_present = 0;			
			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				//console.log('year- '+year + 'class- ' + year_id[year])
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#pps_table tr#" + year).find("input:text").each(function()			
				{					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						//console.log('N - ' + n);
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}				
						l++; 
					}
					
				});
				
				$("#pps_table tr#" + year).find("select").each(function()			
				{					
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}			
					l++;					
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
							
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"';
				
				$("#pps_table tr." + year_id[year]).find("input:text").each(function()				
				{					
					//td_value[j] = $(this).val(); 
					//console.log(td_value[j]);
					//j++; 
					var td_id = $(this).attr("id"); 
					
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
						}
						else{
							
							td_value[j] = '';
							pps_existing_data += ',"csed":""';
						}
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}
				});
				
				$("#pps_table tr." +  year_id[year]).find("select").each(function()
				{					
					var td_id = $(this).attr("id"); 
					td_value[j] = $(this).val();

					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}						
					j++;					
				});
				
				pps_data_input[i] = JSON.stringify(td_value);
				//console.log('pps_data_input - ' + pps_data_input[i]);
				if(td_value[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}	
				pps_existing_data += '};';	
			}			
			i++;        
			k++;        
		});
		
		var year_id_json = JSON.stringify(year_id);
		//console.log('pps_new_data - ' + pps_new_data);
		//console.log('pps_existing_data - ' + pps_existing_data);
		
		//return false;	
		
        
        if(error < 1){
 
            jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_details",
                //dataType: 'json',
                data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},
                success: function(res) 
                {
                    //console.log(res);
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					$("#total_balance").val(total_bal);
					$("#tab_pps_table #total_balance").val(total_bal);
					
					refresh_data();
                }
            });
        }
	}
	
	function saveTabPPSDetails(){
		
		var error= 0;
        var case_id = $('#cid').val(); 
		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var pps_arr=[];
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new=[];
		var year_id={};
		var pps_existing_data ='';
		var pps_new_data ='';
		
		var ppsid = $('#tab_pps_table').attr('name'); 
		
		$('#save_tab_pps_data').attr("disabled", true);
		$('#save_pps_data').attr("disabled", true);
		
		$("#tab_pps_table tr ").each(function() {			
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			
			var tot_present = 0;			
			
			var td_id_new = $(this).attr("id"); 
			
			//new			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#tab_pps_table tr#" + year).find("input:text").each(function()			
				{					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}						
						l++; 
					}					
				});
				
				$("#tab_pps_table tr#" + year).find("select").each(function()				
				{					
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}					
					l++;					
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}	
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
				
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"'; 
				
				$("#tab_pps_table tr." + year_id[year]).find("input:text").each(function()
				{					
					var td_id = $(this).attr("id"); 
				
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
						}
						else{
							
							td_value[j] = '';
							pps_existing_data += ',"csed":""';
						}
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}					
				});
				
				$("#tab_pps_table tr." + year_id[year]).find("select").each(function()
				{	
					var td_id = $(this).attr("id"); 
					td_value[j] = $(this).val();

					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}
					j++;					
				});
				
				pps_data_input[i] = JSON.stringify(td_value);
				//alert(year_id[year] + ' --- ' + td_value[1]);
				if(td_value[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					
					if(td_value[1] != ''){
						//alert(td_value[0] + ' --- ' + td_value[1]);
						total_bal = total_bal + parseInt(td_value[1]);
					}
				}	
				pps_existing_data += '};';				
			}			
			i++;        
			k++;
		});
		
		//var year_id_json = JSON.stringify(year_id);		
		//console.log('pps_new_data - ' + pps_new_data);
		//console.log('------------------------------------');
		//console.log('pps_existing_data - ' + pps_existing_data);
		//alert(total_bal);
		//return false;  
        
        if(error < 1){
 
            jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_details",
               
                data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},
                success: function(res) 
                {
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					$("#total_balance").val(total_bal);
					$("#tab_pps_table #total_balance").val(total_bal);
					
					refresh_data(); 
                }
            });
        }
	}
	
	//Save PPS State Personal data
	function saveTabPPSStatePersDetails(){
		
		var error= 0;
        var case_id = $('#cid').val(); 
		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new=[];
		var year_id={};
		var pps_existing_data ='';
		var pps_new_data ='';
		
		var ppsid = $('#tab_pps_state_pers_table').attr('name'); 
		
		$('#save_tab_pps_state_pers_data').attr("disabled", true);
		$('#save_pps_state_pers_data').attr("disabled", true);
		
		$("#tab_pps_state_pers_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			
			var tot_present = 0;			
			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#tab_pps_state_pers_table tr#" + year).find("input:text").each(function(){					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}	
						l++; 
					}
					
				});
				
				$("#tab_pps_state_pers_table tr#" + year).find("select").each(function()
				{	
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}	
					l++;					
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}	
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
			
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"'; 
				
				$("#tab_pps_state_pers_table tr." + year_id[year]).find("input:text").each(function(){
					
					var td_id = $(this).attr("id"); 
					
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
						}
						else{
							
							td_value[j] = '';
							pps_existing_data += ',"csed":""';
						}
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}
				});
				
				$("#tab_pps_state_pers_table tr." + year_id[year]).find("select").each(function()
				{	
					var td_id = $(this).attr("id"); 
					td_value[j] = $(this).val();

					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}
					j++;					
				});
				
				pps_data_input[i] = JSON.stringify(td_value);
				//console.log('pps_data_input - ' + pps_data_input[i]);
				if(td_value[0] != 'Total'  && year_id[year] != '' && year_id[year] != null){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}	
				pps_existing_data += '};';	
			}			
			i++;        
			k++;        
		});
		
		var year_id_json = JSON.stringify(year_id);
		//console.log('year_id_json - ' + year_id_json);
		
		//return false;	
        
        if(error < 1){
 
            jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_details_state",
                //dataType: 'json',
                data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},
                success: function(res) 
                {
                    //console.log(res);
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					$("#total_balance").val(total_bal);
					$("#tab_pps_state_pers_table #total_balance").val(total_bal);
					
					refresh_data();
                }
            });
        }
	}
	
	//Save PPS State Personal data Accordion
	function savePPSStatePersDetails(){
		
		var error= 0;
        var case_id = $('#cid').val(); 
		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new = [];
		var year_id={};
		var pps_existing_data ='';
		var pps_new_data ='';
		
		var ppsid = $('#pps_state_pers_table').attr('name'); 
		
		$('#save_tab_pps_state_pers_data').attr("disabled", true);
		$('#save_pps_state_pers_data').attr("disabled", true);
		
		$("#pps_state_pers_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			
			var tot_present = 0;			
			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#pps_state_pers_table tr#" + year).find("input:text").each(function()
				{					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}		
						l++; 
					}
					
				});
				
				$("#pps_state_pers_table tr#" + year).find("select").each(function()
				{					
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}	
					l++;					
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}	
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
			
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"'; 
				
				$("#pps_state_pers_table tr." + year_id[year]).find("input:text").each(function()
				{
					var td_id = $(this).attr("id"); 
					
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
							pps_existing_data += ',"csed":""';
						}
						else{
							
							td_value[j] = '';
						}
						//console.log('N - ' + n);
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}
				});
				
				
				$("#pps_state_pers_table tr." + year_id[year]).find("select").each(function()
				{					
					td_value[j] = $(this).val();
					var td_id = $(this).attr("id"); 					

					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}
					j++;					
				});
				
				//pps_data_input[i] = JSON.stringify(td_value);
				//console.log('pps_data_input - ' + pps_data_input[i]);
				if(td_value[0] != 'Total' && year_id[year] != '' && year_id[year] != null){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}
				pps_existing_data += '};';					
			}			
			i++;        
			k++;        
		});
		
		//var year_id_json = JSON.stringify(year_id);
		//console.log('year_id_json - ' + year_id_json);
		
		//return false;	
        
        if(error < 1){
 
            jQuery.ajax({
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_details_state",
                data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},
                success: function(res) 
                {
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					$("#total_balance").val(total_bal);
					$("#pps_state_pers_table #total_balance").val(total_bal);
					
					refresh_data();
                }
            });
        }
	}
	
	//PPS Spouse
	function saveSpousePPSDetails(){
		
		var error= 0;
        var case_id = $('#cid').val(); 		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new=[];
		var year_id={};
		var pps_existing_data ='';
		var pps_new_data ='';
		
		var ppsid = $('#spouse_pps_table').attr('name'); 
				
		$('#save_tab_spouse_pps_data').attr("disabled", true);
		$('#save_spouse_pps_data').attr("disabled", true);
		
		$("#spouse_pps_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			//console.log('year- '+year + 'class- ' + year_id[year])
			var tot_present = 0;			
			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				//console.log('year- '+year + 'class- ' + year_id[year])
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#spouse_pps_table tr#" + year).find("input:text").each(function()
				{					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						//console.log('N - ' + n);
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}				
						l++; 
					}
					
				});
				
				$("#spouse_pps_table tr#" + year).find("select").each(function()	
				{					
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}			
					l++;					
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total'){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
			
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"';
				
				$("#spouse_pps_table tr." +  year_id[year]).find("input:text").each(function()
				{					
					//td_value[j] = $(this).val(); 
					//console.log(td_value[j]);
					//j++; 
					
					var td_id = $(this).attr("id"); 
					
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
						}
						else{
							
							td_value[j] = '';
							pps_existing_data += ',"csed":""';
						}
						//console.log('N - ' + n);
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}
				});
								
				$("#spouse_pps_table tr." + year_id[year]).find("select").each(function()
				{					
					var td_id = $(this).attr("id"); 
					td_value[j] = $(this).val();

					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}						
					j++;						
				});
				
				pps_data_input[i] = JSON.stringify(td_value);
				//console.log('pps_data_input - ' + pps_data_input[i]);
				if(td_value[0] != 'Total'){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}	
				pps_existing_data += '};';	
			}			
			i++;        
			k++;        
		});
		
        //var year_id_json = JSON.stringify(year_id);
		//console.log('year_id_json - ' + year_id_json);
		
		//return false;	
        if(error < 1){
 
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_spouse_details", 
                //dataType: 'json',                
				data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},	
                success: function(res) 
                {
                    //console.log(res);
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					
					$("#total_balance").val(total_bal);
					refresh_data();
					
                }
            });
        }
	}
	
	function saveTabSpousePPSDetails(){	
		
		var error= 0;
        var case_id = $('#cid').val(); 
		
		var i = 0;
		var j = 0;
		var k = 0;
		var total_bal = 0;
		var td_value=[]
		var pps_arr=[];
		var td_value_new=[]
		var pps_data_input=[];
		var pps_data_input_new=[];
		var year_id={};
		var pps_existing_data ='';
		var pps_new_data ='';
		
		var ppsid = $('#tab_spouse_pps_table').attr('name'); 
		//console.log('ppsid - ' + ppsid);		
		$('#save_tab_spouse_pps_data').attr("disabled", true);
		$('#save_spouse_pps_data').attr("disabled", true);
		
		$("#tab_spouse_pps_table tr ").each(function() {
			
			// Within tr we find the last td child element and get content
			
			var year = $(this).attr("id");
			
			year_id[year] = $(this).attr("class");
			//console.log('year- '+year + 'class- ' + year_id[year])
			var tot_present = 0;			
			var td_id_new = $(this).attr("id"); 
			
			if(year && year_id[year] == 'new'){
				
				var l = 0;
				var cdate = '';
				//console.log('year- '+year + 'class- ' + year_id[year])
				pps_new_data += '{"id": "' + year + '"'; 
				
				$("#tab_spouse_pps_table tr#" + year).find("input:text").each(function()
				{					
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'csedid')
					{
						var csed_date_new =  $(this).val();
						if(csed_date_new != ''){
							
							var date_arr = csed_date_new.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value_new[l] = cdate;
							pps_new_data += ',"csed":"' + td_value_new[l] + '"';
						}
						else{
							
							td_value_new[l] = '';
							pps_new_data += ',"csed":""';
						}
						//console.log('N - ' + n);
						l++; 
					}
					else
					{
						td_value_new[l] = $(this).val();
						
						if(td_id_new == 'pps_year2'){
							pps_new_data += ',"tax_period":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'defaultbal'){
							pps_new_data += ',"default_bal":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_wage'){
							pps_new_data += ',"rep_wage":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_se'){
							pps_new_data += ',"rep_se":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'rep_other'){
							pps_new_data += ',"rep_other":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'gross_month_inc'){
							pps_new_data += ',"gross_month_inc":"' + td_value_new[l] + '"';
						}
						if(td_id_new == 'tax_withheld'){
							pps_new_data += ',"tax_withheld":"' + td_value_new[l] + '"';
						}						
						l++;  
					}
					
				});
				
				$("#tab_spouse_pps_table tr#" + year).find("select").each(function()		
				{					
					td_value_new[l] = $(this).val();
					var td_id_new = $(this).attr("id"); 
					
					if(td_id_new == 'filing_stat'){
						pps_new_data += ',"filing_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'account_stat'){
						pps_new_data += ',"account_stat":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'ro'){
						pps_new_data += ',"ro":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'fta_eligible'){
						pps_new_data += ',"fta_eligible":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'liens'){
						pps_new_data += ',"liens":"' + td_value_new[l] + '"';
					}
					if(td_id_new == 'tin'){
						pps_new_data += ',"tin":"' + td_value_new[l] + '"';
					}					
					l++;							
				});
				
				//pps_data_input_new[k] = JSON.stringify(td_value_new);
				
				if(td_value_new[0] != 'Total'){
					if(td_value_new[1] != '')
						total_bal = total_bal + parseInt(td_value_new[1]);
				}	
				pps_new_data += '};';
			}
			
			if(year && year_id[year] != 'new'){
			
				var j = 0;
				var cdate = '';
				
				pps_existing_data += '{"id": "' + year_id[year] + '"'; 
				
				$("#tab_spouse_pps_table tr." + year_id[year]).find("input:text").each(function()				
				{					
					//td_value[j] = $(this).val(); 
					//console.log(td_value[j]);
					//j++; 
					
					var td_id = $(this).attr("id"); 
					
					if(td_id == 'csedid')
					{
						var csed_date =  $(this).val();
						if(csed_date != ''){
							
							var date_arr = csed_date.split("/");
							var month = date_arr[0];
							var date = date_arr[1];
							var year = date_arr[2];
							
							var cdate = year + '-' + month + '-' + date;
							td_value[j] = cdate;
							pps_existing_data += ',"csed":"' + td_value[j] + '"';
						}
						else{
							
							td_value[j] = '';
							pps_existing_data += ',"csed":""';
						}
						//console.log('N - ' + n);
						j++; 
					}
					else
					{
						td_value[j] = $(this).val();
						
						if(td_id == 'pps_year2'){
							pps_existing_data += ',"tax_period":"' + td_value[j] + '"';
						}
						if(td_id == 'defaultbal'){
							pps_existing_data += ',"default_bal":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_wage'){
							pps_existing_data += ',"rep_wage":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_se'){
							pps_existing_data += ',"rep_se":"' + td_value[j] + '"';
						}
						if(td_id == 'rep_other'){
							pps_existing_data += ',"rep_other":"' + td_value[j] + '"';
						}
						if(td_id == 'gross_month_inc'){
							pps_existing_data += ',"gross_month_inc":"' + td_value[j] + '"';
						}
						if(td_id == 'tax_withheld'){
							pps_existing_data += ',"tax_withheld":"' + td_value[j] + '"';
						}
						j++; 
					}
				});
				
				$("#tab_spouse_pps_table tr." + year_id[year]).find("select").each(function()
				{					
					var td_id = $(this).attr("id"); 
					td_value[j] = $(this).val();
					
					if(td_id == 'filing_stat'){
						pps_existing_data += ',"filing_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'account_stat'){
						pps_existing_data += ',"account_stat":"' + td_value[j] + '"';
					}
					if(td_id == 'ro'){
						pps_existing_data += ',"ro":"' + td_value[j] + '"';
					}
					if(td_id == 'fta_eligible'){
						pps_existing_data += ',"fta_eligible":"' + td_value[j] + '"';
					}
					if(td_id == 'liens'){
						pps_existing_data += ',"liens":"' + td_value[j] + '"';
					}
					if(td_id == 'tin'){
						pps_existing_data += ',"tin":"' + td_value[j] + '"';
					}
					j++;					
				});
				
				//pps_data_input[i] = JSON.stringify(td_value);
				//console.log('pps_data_input - ' + pps_data_input[i]);
				if(td_value[0] != 'Total'){
					if(td_value[1] != '')
						total_bal = total_bal + parseInt(td_value[1]);
				}	
				pps_existing_data += '};';		
			}			
			i++;        
			k++;        
		});
		
        //var year_id_json = JSON.stringify(year_id);
		//console.log('year_id_json - ' + year_id_json);
		//console.log('pps_data_input all - ' + pps_data_input);
		//return false;	
        if(error < 1){
 
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/casedetail/add_pps_spouse_details",
                //dataType: 'json',                
				data: {caseid: case_id, pps_id: ppsid, pps_new_data: pps_new_data, pps_existing_data: pps_existing_data},	
                success: function(res) 
                {
                   // console.log(res);
                    $.jGrowl("Data updated.", {
                      sticky: false,
                      position: 'top-right',
                      theme: 'bg-green'
                    });
					
					if(total_bal == '')
						total_bal = 0;
					
					$("#total_balance").val(total_bal);
					refresh_data();					
                }
            });
        }
	}
	
	//PPS Note Accordion
	function add_note_pps() {
		
		var note_txt = $('#note_txt_pps').val();
		var cid = document.getElementById("case_num").value;
		var ppsid = $('#pps_id').val();
		var today = new Date();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;

		if(note_txt == "")
		{
			$("#note_txt_pps").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note",
			type: "POST",
			data: {'case_id':  cid, 'note_txt': note_txt, pps_id: ppsid}, // our data object
			success: function (data) {
				
				if(data != 0){
					
					$("#note_txt_pps").val('');
					$("#tab_note_txt_pps").val('');
					
					var html = '';
					var html_tab = '';
					 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;		
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsnote" id="d' + pid + '" title="Delete"  onclick="delete_pps_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-danger tdeleteppsnote" id="td' + pid + '" title="Delete"  onclick="delete_pps_tab_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
				else{
					
					$.jGrowl('unable to update PPS Note', {
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//PPS Note Tab
	function tab_add_note_pps() {
		
		var note_txt = $('#tab_note_txt_pps').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		var ppsid = $('#pps_id_tab').val();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;
		
		if(note_txt == "")
		{
			$("#tab_note_txt_pps").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note",
			type: "POST",
			data: {	'case_id':  cid, 'note_txt': note_txt , pps_id: ppsid}, // our data object
			success: function (data) {
				
				if(data != 0){
					
					$("#note_txt_pps").val('');
					$("#tab_note_txt_pps").val('');
					
					var html = '';
					var html_tab = '';
					 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;		
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsnote" id="d' + pid + '" title="Delete"  onclick="delete_pps_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notes" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnote" id="tn' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnote" id="ts' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button>  </button>  <button class="btn btn-xs btn-danger tdeleteppsnote" id="td' + pid + '" title="Delete"  onclick="delete_pps_tab_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
				else{
					
					$.jGrowl('unable to update PPS Note', {
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//Spouse PPS Note Accordion
	function add_note_pps_spouse() {
		
		var note_txt = $('#note_txt_pps_spouse').val();
		var cid = document.getElementById("case_num").value;
		var ppsid = $('#pps_id_spouse').val();
		var today = new Date();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;

		if(note_txt == "")
		{
			$("#note_txt_pps_spouse").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note_spouse",
			type: "POST",
			data: {'case_id':  cid,	'note_txt': note_txt, pps_id: ppsid}, // our data object
			success: function (data) {
				
				if(data != 0){
					
					$("#note_txt_pps_spouse").val('');
					$("#tab_note_txt_pps_spouse").val('');
					
					var html = '';
					var html_tab = '';
					 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;		
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_spouse_notes" id="sp' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsspousenote" id="esp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsspousenote" id="ssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsspousenote" id="dsp' + pid + '" title="Delete"  onclick="delete_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove">		</i></button>';
							
					html_tab += '<textarea rows="7" cols="80" class="form-control past_pps_spouse_notes" id="tsp' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsspousenote" id="tesp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsspousenote" id="tssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-danger tdeleteppsspousenote" id="tdsp' + pid + '" title="Delete"  onclick="delete_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_spouse_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_spouse_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
				else{
					
					$.jGrowl('unable to update PPS Note', {
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//PPS Note State Personal Tab
	function tabAddNotePpsStatePers() {
		
		var note_txt = $('#tab_state_pers_note_txt_pps').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		var ppsid = $('#pps_state_pers_id_tab').val();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;
		
		if(note_txt == "")
		{
			$("#tab_state_pers_note_txt_pps").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note",
			type: "POST",
			data: {	'case_id':  cid, 'note_txt': note_txt , pps_id: ppsid}, // our data object
			success: function (data) {
				
				if(data != 0){
					
					$("#state_pers_note_txt_pps").val('');
					$("#tab_state_pers_note_txt_pps").val('');
				
					var html = '';
					var html_tab = '';
				 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_notessp" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnotesp" id="sp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnotesp" id="ssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_notesp(this.id);"> <i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsnote" id="dsp' + pid + '" title="Delete"  onclick="delete_pps_notesp(this.id);"> <i class="glyph-icon icon-remove"></i></button>';
				
					html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notessp" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnotesp" id="tsp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnotesp" id="tssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_notesp(this.id);"><i class="glyph-icon icon-check"></i></button>  </button>  <button class="btn btn-xs btn-danger tdeleteppsnote" id="tdsp' + pid + '" title="Delete"  onclick="delete_pps_tab_notesp(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_state_pers_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_state_pers_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
				else{
					
					$.jGrowl('Unable to update PPS Note', {
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//PPS Note State Personal Accordion
	function AddNotePpsStatePers() {
		
		var note_txt = $('#state_pers_note_txt_pps').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		var ppsid = $('#pps_state_pers_id').val();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;
		
		if(note_txt == "")
		{
			$("#state_pers_note_txt_pps").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		
		jQuery.ajax({
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note",
			type: "POST",
			data: {	'case_id':  cid, 'note_txt': note_txt , pps_id: ppsid}, // our data object
			success: function (data) {
				
				if(data != 0){
					
					$("#state_pers_note_txt_pps").val('');
					$("#tab_state_pers_note_txt_pps").val('');
				
					var html = '';
					var html_tab = '';
				 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_notessp" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnotesp" id="sp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnotesp" id="ssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_notesp(this.id);"> <i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsnote" id="dsp' + pid + '" title="Delete"  onclick="delete_pps_notesp(this.id);"> <i class="glyph-icon icon-remove"></i></button>';
				
					html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notessp" id="t' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnotesp" id="tsp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnotesp" id="tssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_notesp(this.id);"><i class="glyph-icon icon-check"></i></button>  </button>  <button class="btn btn-xs btn-danger tdeleteppsnote" id="tdsp' + pid + '" title="Delete"  onclick="delete_pps_tab_notesp(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_state_pers_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_state_pers_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
				else{
					
					$.jGrowl('Unable to update PPS Note', {
						sticky: true,
						position: 'top-right',
						theme: 'bg-red'
					});
				}
			}
		});
	}
	
	//Spouse PPS Note Tab
	function tab_add_note_pps_spouse() {
		
		var note_txt = $('#tab_note_txt_pps_spouse').val();
		var cid = document.getElementById("case_num").value;
		var today = new Date();
		var ppsid = $('#pps_id_tab_spouse').val();
		
		var y = today.getFullYear();
		var d = today.getDate();
		const monthNames = ["January", "February", "March", "April", "May", "June",
						  "July", "August", "September", "October", "November", "December"];
		var m = monthNames[today.getMonth()];
		
		var date = m + ' ' + d + ', ' + y + ' '; 
		var time = today.toLocaleTimeString(); 
		var dt = date+' '+time;

		if(note_txt == "")
		{
			$("#tab_note_txt_pps_spouse").focus();			
			return false;
		}
		if(cid == "")
		{
			alert('Unable to create note');		
			return false;
		}
		
		jQuery.ajax({
			
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/add_pps_note_spouse",
			type: "POST",
			data: {'case_id':  cid, 'note_txt': note_txt , pps_id: ppsid}, 
			
			success: function (data) {
				
				if(data != 0){
					
					$("#note_txt_pps_spouse").val('');
					$("#tab_note_txt_pps_spouse").val('');
					
					var html = '';
					var html_tab = '';
					 
					pnote =  note_txt + "&#10;" + dt  + ', <?php echo $this->session->userdata('username'); ?>'; 
					pid = data;		
					
					html += '<textarea rows="7" cols="80" class="form-control past_pps_spouse_notes" id="sp' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsspousenote" id="esp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsspousenote" id="ssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsspousenote" id="dsp' + pid + '" title="Delete"  onclick="delete_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove">		</i></button>';
							
					html_tab += '<textarea rows="7" cols="80" class="form-control past_pps_spouse_notes" id="tsp' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsspousenote" id="tesp' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsspousenote" id="tssp' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeleteppsspousenote" id="tdsp' + pid + '" title="Delete"  onclick="delete_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
				
					document.getElementById("pps_spouse_notes").insertAdjacentHTML('afterbegin', html);
					document.getElementById("tab_pps_spouse_notes").insertAdjacentHTML('afterbegin', html_tab);
					
					$.jGrowl('PPS Note updated', {
                        sticky: false,
                        position: 'top-right',
                        theme: 'bg-green'
                    });
				}
				else{
					
					$.jGrowl('Unable to add PPS note', {
                        sticky: true,
                        position: 'top-right',
                        theme: 'bg-red'
                    });
				}
			}
		});
	}
	
	function addNewPPSSugar(data){
		
		var previous_year = $(data).parent().parent().attr('id');
		var previous_class = $(data).parent().parent().attr('class');
		//var rid_arr = rid.split("_");
		//var previous_year = rid_arr[1];
		
		if(previous_year != '')
        {
            var next_year = previous_year - 1;
        }        
        //alert(previous_class + ', ' + previous_year + ', '+next_year);		
		//return false;
        tbody = '';
        tbody +=  "<tr class='new' id= '" + next_year + "'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" readonly></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option>	<option value="HOH">HOH</option> 	<option value="MFJ">MFJ</option>  		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> 		</select></td> ';
					
		tbody +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </select> </td>';
					
		tbody +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option> </select> 			</td>';
		
		tbody +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
        tbody += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new_ps" style="display: inline;" onClick="addNewPPSSugar(this);">  <i class="glyph-icon icon-plus"></i> </button>		<button type="button" class="btn btn-xs btn-danger" id="deleterow_ps" title="Delete Row" style="display: inline;" onclick="delete_pps_sugar_row(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
		
		tbody_tab = '';		
		tbody_tab +=  "<tr id= 'new_" + next_year + "_tab'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" readonly></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option> <option value="HOH">HOH</option> <option value="MFJ">MFJ</option>		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> 		</select></td> ';
		
		tbody_tab +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody_tab +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </td>';
		
		tbody_tab +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="1">Yes</option>	<option value="2">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="1">Yes</option> <option value="2">No</option> </select> 			</td>';
		
		tbody_tab +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
        tbody_tab += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new_ps" href="javascript:;" onClick="addNewPPSSugar(this)">  <i class="glyph-icon icon-plus"></i> </button> 	<button type="button" class="btn btn-xs btn-danger" id="deleterow_ps" title="Delete Row" onclick="delete_pps_sugar_row(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
        
        //$('.pps_tbody').append(tbody);
		$('.pps_sugar_tbody #total').before(tbody);
		//$('.tab_pps_tbody #total').before(tbody_tab);
		//$('.tab_pps_tbody').append(tbody_tab);
		
		$('#pps_sugar_table #'+previous_year+ ' #add_new_ps').hide();
		//$('#tab_pps_table #'+previous_year+ '_tab #add_new').hide();
		
		$('#pps_sugar_table #'+previous_year+ ' #deleterow_ps').hide();
		//$('#tab_pps_table #'+previous_year+ '_tab #deleterow').hide();
	}
	
	// Add new row in PPS Accordion and Tab
	function addNew(pps_data){ 
		
		var previous_year = $(pps_data).parent().parent().attr('id');
		if(previous_year.charAt(4) == '_')
        {
            previous_year = previous_year.substr(0,4);
           // alert(previous_year);
        }
        var next_year = previous_year - 1;
		
		//$('#add_new').hide();
        //alert(previous_year);
        tbody = '';
        tbody +=  "<tr class='new' id= '" + next_year + "'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option>	<option value="HOH">HOH</option> 	<option value="MFJ">MFJ</option>  		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> <option value="Qualifying Widow">Qualifying Widow</option>		</select></td> ';
					
		tbody +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </select> </td>';
					
		tbody +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option> </select> 			</td>';
		
		tbody +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNew(this);">  <i class="glyph-icon icon-plus"></i> </button>		<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
		
		tbody_tab = '';		
		tbody_tab +=  "<tr class='new' id= '" + next_year + "_tab'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option> <option value="HOH">HOH</option> <option value="MFJ">MFJ</option>		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> 	<option value="Qualifying Widow">Qualifying Widow</option>	</select></td> ';
		
		tbody_tab +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody_tab +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </td>';
		
		tbody_tab +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option> </select> 			</td>';
		
		tbody_tab +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody_tab += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button> 	<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
        
        //$('.pps_tbody').append(tbody);
		$('.pps_tbody #total').before(tbody);
		$('.tab_pps_tbody #total').before(tbody_tab);
		//$('.tab_pps_tbody').append(tbody_tab);
		
		$('#pps_table #'+previous_year+ ' #add_new').hide();
		$('#tab_pps_table #'+previous_year+ '_tab #add_new').hide();
		
		//$('#pps_table #'+previous_year+ ' #deleterow').hide();
		//$('#tab_pps_table #'+previous_year+ '_tab #deleterow').hide();
	}
	
	/*function delete_pps_sugar_row(rowid){
		
		var rid = $(rowid).parent().parent().attr('id');
		var case_id = document.getElementById("case_num").value;		
		
		var j=0;
		var td_value = [];
		var total_balance = $("#pps_sugar_table #total_balance").val();
		
		$("#pps_sugar_table #" + rid).find("input:text").each(function()				
		{
			
			td_value[j] = $(this).val(); 
			j++;
		})
		var new_balance = parseInt(total_balance) - parseInt(td_value[1]);	
		
		if(rid != ''){
			
			//alert(case_id + ',' +rid);
			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/remove_pps_row",
				type: "POST",
				data: {caseid: case_id, rowid: rid},// our data object
				success: function (data) {
					
					if(new_balance > 0){
						$("#pps_sugar_table #total_balance").val(new_balance);
						//$("#tab_pps_table #total_balance").val(new_balance);
					}
				}
			});			
		}
		
        var previous_rid = parseInt(rid) + 1;
       	
        $('#pps_sugar_table #'+previous_rid+ ' #add_new_ps').show();
		//$('#tab_pps_table #'+previous_rid+ '_tab #add_new').show();
		
		$('#pps_sugar_table #'+previous_rid+ ' #deleterow_ps').show();
		//$('#tab_pps_table #'+previous_rid+ '_tab #deleterow').show();
        
        $('#pps_sugar_table #' + rid).remove(); 
		//$("#tab_pps_table #" + rid + "_tab").remove();
	}*/
	
	// Delete PPS row from both Accordion and Tab
	function delete_pps_row(rowid){
		
		swal('Confirm delete pps detail record?', {
			buttons: {
				cancel: true,
				confirm: true,
			},
			icon: "warning"
		}).then(function(isConfirm) {
			if (isConfirm) {
				
				var rid = $(rowid).parent().parent().attr('id');
				var id = $(rowid).parent().parent().attr('class');
				
				var nextid = $(rowid).parent().parent().next('tr').attr('id');
				var previous = $(rowid).parent().parent().prev('tr').attr('id');
				
				var case_id = document.getElementById("case_num").value;
				var tab_v = 0;
				
				if(rid.charAt(4) == '_')
				{
					tab_v = 1;
					rid = rid.substr(0,4);
				}
				
				var j=0;
				var td_value = [];
				var total_balance = $("#tab_pps_table #total_balance").val();
				//alert(rid + ', ' + id);
				
				if(id != 'new'){
					
					if(tab_v == 1)
					{
						$("#tab_pps_table tr." + id).find("input:text").each(function()
						{
							//console.log('each');
							td_value[j] = $(this).val();
							//alert(td_value[j]);			
							j++;
						})
					}
					else
					{
						$("#pps_table tr." + id).find("input:text").each(function()
						{
							td_value[j] = $(this).val();		
							j++;
						})
					}
					var new_balance = parseInt(total_balance) - parseInt(td_value[1]);		
					//alert(new_balance + ', ' + total_balance + ', ' + td_value[1]);
				}
				
				if(rid != '' && id != '' && id != 'new'){
					
					jQuery.ajax({
						url: "<?php echo base_url(); ?>" + "index.php/Casedetail/remove_pps_row",
						type: "POST",
						data: {caseid: case_id, rowid: rid, id: id},
						success: function (data) {
							
							if(new_balance > 0){
								$("#pps_table #total_balance").val(new_balance);
								$("#tab_pps_table #total_balance").val(new_balance);
							}
						}
					});			
				}
				
				//var previous_rid = parseInt(rid) + 1;
				
				if(nextid == 'total' && previous != '')
				{
					//alert(nextid + ', ' + previous + ' -- ' +tab_v);
					if(tab_v == 1)
					{
						previous_rid = previous.substr(0,4);
						$('#pps_table #'+previous_rid+ ' #add_new').show();
						$('#tab_pps_table #'+previous+ ' #add_new').show();
					}
					else{
						
						$('#pps_table #'+previous+ ' #add_new').show();
						$('#tab_pps_table #'+previous+ '_tab #add_new').show();
					}				
				}
				
				if(id == 'new'){
					
					$(rowid).closest('tr').remove();
				}
				else{
					
					$('#pps_table .' + id).remove(); 
					$("#tab_pps_table ." + id).remove();
				}
			}
		})		
	}
	
	// Add new row in Spouse PPS Accordion and Tab
	function addNewRowSpousePPS(pps_data){ 
		
		var previous_year = $(pps_data).parent().parent().attr('id'); 		
		
		if(previous_year.charAt(4) == '_')
        {
            previous_year = previous_year.substr(0,4);
           // alert(previous_year);
        }
        var next_year = previous_year - 1;
		
		//$('#add_new').hide();
        //alert(previous_year);
        tbody = '';
        tbody +=  "<tr class ='new' id= '" + next_year + "'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option> <option value="HOH">HOH</option>	<option value="MFJ">MFJ</option>	<option value="MFS">MFS</option>  				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option><option value="Qualifying Widow">Qualifying Widow</option> 		</select></td> ';
					
		tbody +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </select> </td>';
					
		tbody +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option></select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option></select> 			</td>';
		
		tbody +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewRowSpousePPS(this);">  <i class="glyph-icon icon-plus"></i> </button>		<button class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
		
		tbody_tab = '';		
		tbody_tab +=  "<tr class ='new' id= '" + next_year + "_spouse_tab'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option> <option value="HOH">HOH</option> <option value="MFJ">MFJ</option>		<option value="MFS">MFS</option> <option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option><option value="Qualifying Widow">Qualifying Widow</option> 		</select></td> ';
		
		tbody_tab +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option>	</select> </td>';
		
        tbody_tab +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option></select> </td>';
		
		tbody_tab +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option></select> 			</td>';
		
		tbody_tab +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody_tab += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" onClick="addNewRowSpousePPS(this)">  <i class="glyph-icon icon-plus"></i> </button> 	<button class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
        
        //$('.spouse_pps_tbody').append(tbody);
		//$('.tab_spouse_pps_tbody').append(tbody_tab);
		
		$('.spouse_pps_tbody #total').before(tbody);
		$('.tab_spouse_pps_tbody #total').before(tbody_tab);
		
		$('#spouse_pps_table #'+previous_year+ ' #add_new').hide();
		$('#tab_spouse_pps_table #'+previous_year+ '_spouse_tab #add_new').hide();
		
		//$('#spouse_pps_table #'+previous_year+ ' #deleterow').hide();
		//$('#tab_spouse_pps_table #'+previous_year+ '_spouse_tab #deleterow').hide();
	}
	
	// Delete PPS row from both Accordion and Tab
	function delete_pps_row_spouse(rowid){
		
		swal('Confirm delete pps detail record?', {
			buttons: {
				cancel: true,
				confirm: true,
			},
			icon: "warning"
		}).then(function(isConfirm) {
			if (isConfirm) {
				
				var rid = $(rowid).parent().parent().attr('id');
				var id = $(rowid).parent().parent().attr('class');
				
				//alert(rid);
				
				var nextid = $(rowid).parent().parent().next('tr').attr('id');
				var previous = $(rowid).parent().parent().prev('tr').attr('id');
				
				var case_id = document.getElementById("case_num").value;
				var tab_v = 0;
			
		
				if(rid.charAt(4) == '_')
				{
					tab_v = 1;
					rid = rid.substr(0,4);
				}
		
				var j=0;
				var td_value = [];
				var total_balance = $("#tab_spouse_pps_table #total_balance").val();
				
				if(id != 'new'){
					
					if(tab_v == 1)
					{
						$("#tab_spouse_pps_table tr." + id).find("input:text").each(function()
						{
							//console.log('each');
							td_value[j] = $(this).val();
							//alert(td_value[j]);			
							j++;
						})
					}
					else
					{
						$("#spouse_pps_table tr." + id).find("input:text").each(function()
						{
							td_value[j] = $(this).val();		
							j++;
						})
					}
					var new_balance = parseInt(total_balance) - parseInt(td_value[1]);		
					//alert(new_balance + ', ' + total_balance + ', ' + td_value[1]);
				}
				
				if(rid != '' && id != ''  && id != 'new'){
					//alert('main');
					jQuery.ajax({
						url: "<?php echo base_url(); ?>" + "index.php/Casedetail/remove_pps_row",
						type: "POST",
						data: {caseid: case_id, rowid: rid, id: id},// our data object
						success: function (data) {
							
							if(new_balance > 0){
								$("#spouse_pps_table #total_balance").val(new_balance);
								$("#tab_spouse_pps_table #total_balance").val(new_balance);
							}
						}
					});
					if(new_balance > 0){
						
						$("#spouse_pps_table #total_balance").val(new_balance);
						$("#tab_spouse_pps_table #total_balance").val(new_balance);
					}
				}
		
				//var previous_rid = parseInt(rid) + 1;
				if(nextid == 'total' && previous != '')
				{
					//alert(nextid + ', ' + previous);
					if(tab_v == 1)
					{
						previous_rid = previous.substr(0,4);
						$('#spouse_pps_table #'+previous_rid+ ' #add_new').show();
						$('#tab_spouse_pps_table #'+previous+ ' #add_new').show();
					}
					else{
						
						$('#spouse_pps_table #'+previous+ ' #add_new').show();
						$('#tab_spouse_pps_table #'+previous+ '_tab #add_new').show();
					}				
				}
				if(id == 'new'){
					
					$(rowid).closest('tr').remove();
				}
				else{
					
					$('#spouse_pps_table .' + id).remove(); 
					$("#tab_spouse_pps_table ." + id).remove();
				}
				
			}
		})
	}
	
	// Add new row in PPS State Personal Accordion and Tab
	function addNewPPSStatePersonal(pps_data){ 
		
		var previous_year = $(pps_data).parent().parent().attr('id');
		if(previous_year.charAt(4) == '_')
        {
            previous_year = previous_year.substr(0,4);
           // alert(previous_year);
        }
        var next_year = previous_year - 1;
		
		//$('#add_new').hide();
        //alert(previous_year + ', ' + next_year);
        tbody = '';
        tbody +=  "<tr class='new' id= '" + next_year + "'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option>	<option value="HOH">HOH</option> 	<option value="MFJ">MFJ</option>  		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> <option value="Qualifying Widow">Qualifying Widow</option>		</select></td> ';
					
		tbody +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </select> </td>';
					
		tbody +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option> </select> 			</td>';
		
		tbody +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewPPSStatePersonal(this);">  <i class="glyph-icon icon-plus"></i> </button>		<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
		
		tbody_tab = '';		
		tbody_tab +=  "<tr class='new' id= '" + next_year + "_tab'>"  
                    + '<td><input class="form-control1" type ="text" name="year" value="' + next_year + '" id="pps_year2" required></td> '
                    + '<td><input class="form-control1" type ="text" name="balance" value="" id="defaultbal"> </td>'
                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"> 		<option value="0">Select</option> <option value="Single">Single</option> <option value="HOH">HOH</option> <option value="MFJ">MFJ</option>		<option value="MFS">MFS</option> 				<option value="SFR">SFR</option> <option value="Unfiled">Unfiled</option> 	<option value="Qualifying Widow">Qualifying Widow</option>	</select></td> ';
		
		tbody_tab +=  '<td>	<select class="form-control1" name="accountstat" id="account_stat">					<option value="0">Select</option> 	<option value="IA">IA</option> <option value="Defaulted IA">Defaulted IA</option> 		<option value="First Notice">First Notice</option> 	<option value="2nd NOITL">2nd NOITL</option> 					<option value="3rd FNOITL">3rd FNOITL</option> <option value="4th NOL">4th NOL</option> <option value="ACS">ACS </option> <option value="3rd Party">3rd Party</option> <option value="CNC">CNC</option> <option value="RO">RO</option>	<option value="Exam">Exam</option> 	</select> </td>';
		
        tbody_tab +=  '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
                    +  '<td><select class="form-control1" name="ro_recommend" id="ro"> <option value="0">Select</option><option value="Required">Required</option> <option value="Original Recommended">Original Recommended</option> <option value="No">No</option> </select> </td>';
		
		tbody_tab +=   '<td><select class="form-control1" name="fta_eligible" id="fta_eligible">			<option value="0"></option>	<option value="Yes">Yes</option>	<option value="No">No</option> </select> </td>   		<td>			<select class="form-control1" name="liens" id="liens"> 				<option value="0"></option> <option value="Yes">Yes</option> <option value="No">No</option> </select> 			</td>';
		
		tbody_tab +=   '<td><input class="form-control1" type ="text" name="rep_wage" value="" id="rep_wage"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="" id="rep_se"> </td>';
		tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="" id="rep_other"> </td>';
		
		tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="" id="tax_withheld"></td> 		<td><input class="form-control1" type="text" name="gross_month_inc" value="" id="gross_month_inc"></td>';
		
		tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin"><option value="SSN">SSN</option><option value="ITIN">ITIN</option></select>   </td>';
		
        tbody_tab += '<td id="actionbtn"> ' 
                    + '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" onClick="addNewPPSStatePersonal(this)">  <i class="glyph-icon icon-plus"></i> </button> 	<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> </td>'  
                        
                    + "</tr>";
        
        //console.log(tbody);
		$('.pps_state_pers_tbody #total').before(tbody);
		$('.tab_pps_state_pers_tbody #total').before(tbody_tab);		
		
		$('#pps_state_pers_table #'+previous_year+ ' #add_new').hide();
		$('#tab_pps_state_pers_table #'+previous_year+ '_tab #add_new').hide();
		
		//$('#pps_state_pers_table #'+previous_year+ ' #deleterow').hide();
		//$('#tab_pps_state_pers_table #'+previous_year+ '_tab #deleterow').hide();
	}
	
	// Delete PPS State Personal row from both Accordion and Tab
	function deletePPSRowStatePersonal(rowid){
		
		swal('Confirm delete pps detail record?', {
			buttons: {
				cancel: true,
				confirm: true,
			},
			icon: "warning"
		}).then(function(isConfirm) {
			if (isConfirm) {
				
				var rid = $(rowid).parent().parent().attr('id');
				var id = $(rowid).parent().parent().attr('class');
				
				var nextid = $(rowid).parent().parent().next('tr').attr('id');
				var previous = $(rowid).parent().parent().prev('tr').attr('id');
				
				var case_id = document.getElementById("case_num").value;
				var tab_v = 0;				
				
				if(rid.charAt(4) == '_')
				{
					tab_v = 1;	
					rid = rid.substr(0,4);
				}
				
				var j=0;
				var td_value = [];
				var total_balance = $("#tab_pps_state_pers_table #total_balance").val();
				
				if(id != 'new'){
					
					if(tab_v == 1)
					{
						$("#tab_pps_state_pers_table tr." + id).find("input:text").each(function()
						{
							//console.log('each');
							td_value[j] = $(this).val();
							//alert(td_value[j]);			
							j++;
						})
					}
					else
					{
						$("#pps_state_pers_table tr." + id).find("input:text").each(function()
						{
							td_value[j] = $(this).val();		
							j++;
						})
					}
					var new_balance = parseInt(total_balance) - parseInt(td_value[1]);		
					//alert(new_balance + ', ' + total_balance + ', ' + td_value[1]);
				}	
					
				if(rid != '' && id != ''  && id != 'new'){
					//alert('main');
					jQuery.ajax({
						url: "<?php echo base_url(); ?>" + "index.php/Casedetail/remove_pps_row_state_personal",
						type: "POST",
						data: {caseid: case_id, rowid: rid, id: id},// our data object
						success: function (data) {
							
							if(new_balance > 0){
								$("#pps_state_pers_table #total_balance").val(new_balance);
								$("#tab_pps_state_pers_table #total_balance").val(new_balance);
							}
						}
					});					
				}
		
				//var previous_rid = parseInt(rid) + 1;
				
				if(nextid == 'total' && previous != '')
				{
					if(tab_v == 1)
					{
						previous_rid = previous.substr(0,4);
						$('#pps_state_pers_table #'+previous_rid+ ' #add_new').show();
						$('#tab_pps_state_pers_table #'+previous+ ' #add_new').show();
					}
					else{
						
						$('#pps_state_pers_table #'+previous+ ' #add_new').show();
						$('#tab_pps_state_pers_table #'+previous+ '_tab #add_new').show();
					}
				}
				if(id == 'new'){
					
					$(rowid).closest('tr').remove();
				}
				else{
					
					$('#pps_state_pers_table .' + id).remove(); 
					$("#tab_pps_state_pers_table ." + id).remove();
				}
				
			}
		})
	}
		
	
	//update url in logics
	//$(document.body).on('click','#view_cr',function(){
	function updateCaseReviewURL(){
		
		//alert(cid);
		var cid = document.getElementById("case_num").value;	 	
		var cr_url = '<?php echo $this->config->item('sugar_url'); ?>index.php/casereport/records?id='+cid;
		
		if(cr_url != '' && cid != ''){
			
			jQuery.ajax({
				url: "https://ctr.irslogics.com/api/updatecase.aspx?WorkflowApiToken=889F6977-3FDE-4DE5-B229-B57E8F0EE862&CaseID="+cid +"&lead_provider_id=Inseev%20Transfer&overrideudf=1&udf434="+cr_url ,
				type: "POST",
				//data: {udf434: cr_url},
				success: function (data) {
					//"Status successfully updated in Logics"
					//alert('Case Review url successfully updated in Logics');
					$.jGrowl('Case Review url successfully updated in Logics', {
                        sticky: false,
                        position: 'top-right',
                        theme: 'bg-green'
                    });
				},
				error: function (err) {
					//var x = JSON.stringify(err);
					//alert('error ' +x);
					$.jGrowl("Unable to update url in Logics", {
                        sticky: false,
                        position: 'top-right',
                        theme: 'bg-red'
                    });
				}
			});
		}	
	}
	
	function updateCaseStatus(status_txt){
		
		var cid = document.getElementById("case_num").value;
		
		if(cid != '' && status_txt != ''){
			
			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_case_status",
				type: "POST",
				data: {caseid: cid, status: status_txt},// our data object
				success: function (data) {
					//console.log('data- '+data);
					var error = 0;
					
					if(data == 2){
						
						error = 1;
						
						$.jGrowl('Unable to update Case Review status as Case Not found/created in Sugar.', {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					} 
					
					if(data == 0){
						
						error = 1;
						
						$.jGrowl('Unable to update Case Review status.', {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
					
					if(data == 1){
						
						$.jGrowl('Case Review status successfully updated in Sugar', {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					if(error > 0){
						//console.log('error');
						document.getElementById("case_status").value=0; 
					}
				}
			});
		}
	}
	
	function updateAggregateValues(cid, cr_services){ //, cr_fee_total
		
		//console.log('updateAggregateValues - ' + cid+ ' , ' + cr_services + ', ' + cr_fee_total);
		if(cid != ''){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/update_cr_values",
				dataType: 'json',
				
				data: {caseid: cid, service_names: cr_services}, //, fee: cr_fee_total
				
				success: function(res) 
				{
					if(res == true){
						
						$.jGrowl('Case Review aggregate values successfully updated in Sugar', {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl('Unable to update Case Review aggregate values in Sugar', {
							sticky: false,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			})
		}
	}
		
	function getAggregateValues(){
		
		var cid = document.getElementById("case_num").value;
		var self = this;
		//console.log('getAggregateValues - ' + cid);
		
					
		if(cid != ''){
			
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_unsold_services_list",
				dataType: 'json',
				
				data: {caseid: cid},
				
				success: function(res) 
				{
					var service_len = res.length;
					var cr_services = '';
					var cr_fee_total = 0;					
					//console.log('service_len - '+service_len);
					if(service_len) {
												
						for (var i = 0; i < service_len; i++) {
							//console.log('Name 1  - ' + res[i]['name']);
							if(res[i]['sale_type'].length > 0){
								
								//var index = res[i]['sale_type'].indexOf('-');
								//var sale_type_val = res[i]['sale_type'].substr(0, index);
								//console.log('sale_type_val - ' + res[i]['sale_type']); 
								if(res[i]['sale_type'] == 'Case Review'){
									//console.log('Name - ' + res[i]['name']);
									if(res[i]['name'] != null){
										
										cr_services += ', ' + res[i]['name'];
									}
									/*if(res[i]['discount_amount'] == null){
								
										res[i]['discount_amount'] = '';
									}
																
									
									var calc = res[i]['list_price'] * res[i]['quantity'];
									
									if(res[i]['discount_amount'] != ''){
										
										calc = calc - res[i]['discount_amount'];
									}
										
									cr_fee_total +=  parseFloat(calc);*/
								}
							}
						}
							
						if(cr_services != ''){
							
							cr_services = cr_services.substring(1);
						}
						
						if(cr_services != ''){ //|| cr_fee_total > 0
						
							updateAggregateValues(cid, cr_services); //, cr_fee_total
						}
					}
				}
			})
			//console.log(self.cr_fee_total + '  cr_services - ' + self.cr_services);
		}
	}
	
	//update status in logics	
	//$(document.body).on('change','#case_status',function(){
	$(document.body).on('focusin','#case_status',function(){
        
        $(this).data('val', $(this).val());

    }).on('change','#case_status',function(){
		
		var previous_value = $(this).data('val');		
		var select_val = this.value;

		//alert(previous_value + ', ' + select_val);
		//return false;

		if(select_val != 0){
			
			if(select_val == 'Completed'){

				var recmd_type = $('#tab_final_rec_type').val();
				var cr_fee = $('.tab_input_cr_fee').val();

				if(recmd_type == '' || cr_fee == ''){

					if(recmd_type == ''){

						var msg = "Final Recommendation Type field is not selected. Please select it before moving Investigation stage to Completed";
					}
					if(cr_fee == ''){

						var msg = "CR Fee field is not filled. Please fill it before moving Investigation stage to Completed";
					}
					swal(msg, {
						buttons: {
							cancel: true,
						},
						icon: "warning"
					})
					$('#case_status').val(previous_value);
					return false;
				}
				else{
							
					swal('Selecting status as completed will mark case investigation status as Completed in sugar. Are you sure to set completed status?', {
						buttons: {
							cancel: true,
							confirm: true,
						},
						icon: "warning"
					}).then(function(isConfirm) {
						if (isConfirm) {
							updateCaseStatus(select_val);
							updateCaseReviewURL();
							getAggregateValues();
						}
						else{
							document.getElementById("case_status").value=0; 
						}
					})
				}			
			}
			else{	

				swal('Confirm update case investigation status in sugar?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					
					if (isConfirm) {
						
						updateCaseStatus(select_val);
					}
					else{
						$('#case_status').val(previous_value);
						//document.getElementById("case_status").value=0; 
					}
				})				
			}
		}			
	});
	
	function checkSummaryFields(){

		var self = this;
		self.result = '';
		var cid = document.getElementById("case_num").value;

		if(cid != ''){

			jQuery.ajax({

				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/checkSummary",
				type: "POST",
				data: {caseid : cid},
				success: function (response) {
					console.log('response sum - '+response);
					self.result = response;
				}
			})
		}
		
		return self.result;
	}

	//update di in summary accordion analysis1
	$(document.body).on('change','#analysis1',function(){
		
		var analysis1 = $('#analysis1').val(); 
		var cid = document.getElementById("case_num").value;
		
		if(analysis1 != '' && cid != ''){
			
			var di = 0;
			var si = $('.si').text();
			var se = $('.se').text();
			var verified_expense = $('.re').text();
			
			
			if(si != ''){
				var si_val = si.split('$')[1];				
			}
			if(se != ''){
				var se_val = se.split('$')[1];				
			}
			if(verified_expense != ''){
				var verified_expense_val = verified_expense.split('$')[1];				
			}
			
			if(analysis1 == 'Stated'){
				
				if(si_val != '')
					si_val = si_val.replace(/,/g, '');
				
				if(se_val != '')
					se_val = se_val.replace(/,/g, '');
				
				di = parseInt(si_val) - parseInt(se_val);
			}
			if(analysis1 == 'Verified'){
				
				if(si_val != '')
					si_val = si_val.replace(/,/g, '');
				
				if(verified_expense_val != '')
					verified_expense_val = verified_expense_val.replace(/,/g, '');
				
				di = parseInt(si_val) - parseInt(verified_expense_val);
			}
			
			//if(parseInt(di) > 0){
				$('.input_di').val('');
				$('.input_di').val(di);
				
				$('.tab_input_di').val('');
				$('.tab_input_di').val(di);
			//}			
		}		
	});
	
	//update di in summary accordion analysis2
	$(document.body).on('change','#analysis2',function(){

		var analysis2 = $('#analysis2').val();
		var cid = document.getElementById("case_num").value;
		
		if(analysis2 != '' && cid != ''){
			
			var di = 0;
			
			var se = $('.se').text();
			var rep_inc = $('.vi').text(); //#ri
			var verified_expense = $('.re').text();
			
			if(verified_expense != ''){
				var verified_expense_val = verified_expense.split('$')[1];				
			}
			if(se != ''){
				var se_val = se.split('$')[1];				
			}
			if(rep_inc != ''){
				var rep_inc_val = rep_inc.split('$')[1];				
			}
			
			if(analysis2 == 'Stated'){
				
				if(rep_inc_val != '')
					rep_inc_val = rep_inc_val.replace(/,/g, '');
				
				if(verified_expense_val != '')
					verified_expense_val = verified_expense_val.replace(/,/g, '');
				
				di = parseInt(rep_inc_val) - parseInt(verified_expense_val);
			}
			if(analysis2 == 'Verified'){
				
				if(rep_inc_val != '')
					rep_inc_val = rep_inc_val.replace(/,/g, '');
				
				if(se_val != '')
					se_val = se_val.replace(/,/g, '');
				
				di = parseInt(rep_inc_val) - parseInt(se_val);
			}
			
			//if(parseInt(di) > 0){
				$('.input_di').val('');
				$('.input_di').val(di);
				
				$('.tab_input_di').val('');
				$('.tab_input_di').val(di);	
			//}
		}	
	});
	
	//update di in summary tab
	$(document.body).on('change','#tab_analysis1',function(){

		var analysis1 = $('#tab_analysis1').val();
		var cid = document.getElementById("case_num").value;
		
		if(analysis1 != '' && cid != ''){
			
			var di = 0;
			var si = $('.si').text();
			var se = $('.se').text();
			var verified_expense = $('.re').text();
			
			
			if(si != ''){
				var si_val = si.split('$')[1];				
			}
			if(se != ''){
				var se_val = se.split('$')[1];				
			}
			if(verified_expense != ''){
				var verified_expense_val = verified_expense.split('$')[1];				
			}
			
			if(analysis1 == 'Stated'){
				
				if(si_val != '' && si_val != null)
					si_val = si_val.replace(/,/g, '');
				
				if(se_val != '' && se_val != null)
					se_val = se_val.replace(/,/g, '');
				
				if(verified_expense_val != '')
					verified_expense_val = verified_expense_val.replace(/,/g, '');
				
				di = parseInt(si_val) - parseInt(se_val);
			}
			if(analysis1 == 'Verified'){
				
				if(si_val != '' && si_val != null)
					si_val = si_val.replace(/,/g, '');
				
				if(verified_expense_val != '')
					verified_expense_val = verified_expense_val.replace(/,/g, '');
				
				di = parseInt(si_val) - parseInt(verified_expense_val);
			}
			
			//if(parseInt(di) > 0){
				$('.input_di').val('');
				$('.input_di').val(di); 
				
				$('.tab_input_di').val('');
				$('.tab_input_di').val(di);	
			//}
		}	
	});
	
	//update di in summary accordion tab_analysis2
	$(document.body).on('change','#tab_analysis2',function(){

		var analysis2 = $('#tab_analysis2').val();
		var cid = document.getElementById("case_num").value;
		
		if(analysis2 != '' && cid != ''){
			
			var di = 0;
			var verified_expense = $('.re').text();			
			var se = $('.se').text();
			var rep_inc = $('.vi').text();	//#ri		
			
			if(verified_expense != ''){
				var verified_expense_val = verified_expense.split('$')[1];				
			}
			if(se != ''){
				var se_val = se.split('$')[1];				
			}
			var rep_inc_val = '';
			if(rep_inc != ''){
				rep_inc_val = rep_inc.split('$')[1];				
			}
			
			if(analysis2 == 'Stated'){
				
				if(rep_inc_val != '')
					rep_inc_val = rep_inc_val.replace(/,/g, '');
				
				if(verified_expense_val != '')
					verified_expense_val = verified_expense_val.replace(/,/g, '');
				
				di = parseInt(rep_inc_val) - parseInt(verified_expense_val);				
			}
			if(analysis2 == 'Verified'){
				
				if(rep_inc_val != '')
					rep_inc_val = rep_inc_val.replace(/,/g, '');
				
				if(se_val != '')
					se_val = se_val.replace(/,/g, '');
				
				di = parseInt(rep_inc_val) - parseInt(se_val);				
			}
			
			//if(parseInt(di) > 0){
				$('.input_di').val('');
				$('.input_di').val(di);
				
				$('.tab_input_di').val('');
				$('.tab_input_di').val(di);	
			//}
		}	
	});
	
	//update assigned_to in pps 
	/*$(document.body).on('change','#pps_assigned_to',function(){

		var userid = this.value; 
		var cid = document.getElementById("case_num").value;
		var ppsid = $(this).closest('tr').attr('id');
		//alert(issue + ', ' + ppsid);
		
		if(ppsid == ''){
			
			$.jGrowl("Unable to update PPS Assigned user. PPS detail not found.", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
			return false;
		}
		if(userid == ''){
			
			$.jGrowl("Unable to update PPS Assigned user. User id not found.", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
			return false;
		}
		
		if(userid != '' && ppsid != ''){
			
			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_assigned_user",
				type: "POST",
				data: {'user_id':  userid, 'pps_id': ppsid}, 
				success: function (data) {
					
					$.jGrowl("PPS Assigned User Updated.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
			});
		}
		
	});*/
	
	//update poa issues in pps 
	/*$(document.body).on('change','#poa_issue',function(){

		var issue = this.value; 
		var cid = document.getElementById("case_num").value;
		var ppsid = $(this).closest('tr').attr('id');
		//alert(issue + ', ' + ppsid);
		
		if(ppsid == ''){
			
			$.jGrowl("Unable to update POA Issue. PPS detail not found.", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
			return false;
		}
		
		if(issue != '' && ppsid != ''){
			
			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_poa_issue",
				type: "POST",
				data: {'poa_issue':  issue, 'pps_id': ppsid}, 
				success: function (data) {
					
					$.jGrowl("POA Issue Updated.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
			});
		}
		
	});*/
	
	//update biz poa issues in pps 
	/*$(document.body).on('change','#biz_poa_issue',function(){

		var issue = this.value; 
		var cid = document.getElementById("case_num").value;
		var ppsid = $(this).closest('tr').attr('id');
		//alert(issue + ', ' + ppsid);
		
		if(ppsid == ''){
			
			$.jGrowl("Unable to update Business POA Issue. PPS detail not found.", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
			return false;
		}
		
		if(issue != '' && ppsid != ''){
			
			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_biz_poa_issue",
				type: "POST",
				data: {'biz_poa_issue':  issue, 'pps_id': ppsid}, 
				success: function (data) {
					
					$.jGrowl("Business POA Issue Updated.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
				}
			});
		}
		
	});*/
	
	function edit_summary_note(id){
		
		if(id != ''){
			var note_id = id.substr(1);			
			
			var note_txt = $('#'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#" + note_id).attr("readonly", false);
			$("#" + note_id).val();
			$("#" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#s'+note_id).show();	
			$('#d'+note_id).hide();	
		}
		
		
	}
	
	function edit_tab_summary_note(id){
		//alert(id);
		if(id != ''){ 
			var note_id = id.substr(2);			
			
			var note_txt = $('#t'+note_id).val();
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			$("#t" + note_id).attr("readonly", false);
			$("#t" + note_id).val();
			$("#t" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#ts'+note_id).show();
			$('#td'+note_id).hide();			
		}
	}
	
	function delete_summary_note(id){
		
		if(id != ''){ 
			//alert(note_id);
			var note_id = id.substr(1);	
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_summary_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$('#'+id).remove();
								$('#n'+note_id).remove();
								$('#'+note_id).remove();
								
								$('#t'+id).remove();
								$('#tn'+note_id).remove();
								$('#t'+note_id).remove();
								
								$.jGrowl("Review Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}			
		}
	}
	
	function delete_tab_summary_note(id){
		
		if(id != ''){ 
			//alert(note_id);
			var note_id = id.substr(2);				
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					if (isConfirm) {
					
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_summary_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$('#'+id).remove();
								$('#tn'+note_id).remove();
								$('#t'+note_id).remove();
								
								$('#'+note_id).remove();
								$('#n'+note_id).remove();
								$('#d'+note_id).remove();
								
								$.jGrowl("Review Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}						
		}
	}
	
	function save_summary_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(1);
			$('#'+id).hide();
			$('#n'+note_id).show();
			$('#d'+note_id).show();	
			
			var note_txt = $('#'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			
			if(note_txt != ''){
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_summary_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);
						
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);						
						
						$.jGrowl("Review Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});
			}
		}
	}
	
	function save_tab_summary_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(2);
			$('#'+id).hide();
			$('#tn'+note_id).show();
			$('#td'+note_id).show();
			
			var note_txt = $('#t'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			//alert('trimMessage - '+trimMessage); 
			//return false;
			if(note_txt != ''){
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_summary_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#t" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);
						
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);							
						
						$.jGrowl("Review Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});
			}
		}
	}
	
	//edit past pps notes
	function edit_pps_note(id){
		
		if(id != ''){
			var note_id = id.substr(1);			
			//alert(id+', '+note_id);
			var note_txt = $('#'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#" + note_id).attr("readonly", false);
			$("#" + note_id).val();
			$("#" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#s'+note_id).show();	
			$('#d'+note_id).hide();				
		}
	}
	
	function delete_pps_note(id){
		
		if(id != ''){
			
			var note_id = id.substr(1);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#" + note_id).remove();
								$("#n" + note_id).remove();
								$('#'+id).remove();
								
								$("#t" + note_id).remove();
								$("#td" + note_id).remove();
								$('#tn'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}	
		}
		
	}
	
	function delete_pps_tab_note(id){
		
		if(id != ''){
			
			var note_id = id.substr(2);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#t" + note_id).remove();
								$("#tn" + note_id).remove();
								$('#'+id).remove();
								
								$("#" + note_id).remove();
								$("#d" + note_id).remove();
								$('#n'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}	
		}
		
	}
	
	//delete pps tab note state
	function delete_pps_tab_notesp(id){
		
		if(id != ''){
			
			var note_id = id.substr(4);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#t" + note_id).remove();
								$("#tsp" + note_id).remove();
								$('#tdsp'+note_id).remove();
								
								$("#" + note_id).remove();
								$("#dsp" + note_id).remove();
								$('#sp'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}	
		}
		
	}
	
	//delete pps note state
	function delete_pps_notesp(id){
		
		if(id != ''){
			
			var note_id = id.substr(3);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#t" + note_id).remove();
								$("#tsp" + note_id).remove();
								$('#tdsp'+note_id).remove();
								
								$("#" + note_id).remove();
								$("#dsp" + note_id).remove();
								$('#sp'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}
		}		
	}
	
	function save_pps_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(1);
			$('#'+id).hide();
			$('#n'+note_id).show();
			$('#d'+note_id).show();		
			//alert(id + note_id);
			var note_txt = $('#'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			} 

			if(note_txt != ''){
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);
						
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);
						
						$.jGrowl("Review Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});				
			}
		}
	}
	
	//edit past pps notes
	function edit_tab_pps_note(id){
		
		//alert(id);
		if(id != ''){ 
			var note_id = id.substr(2);			
			
			var note_txt = $('#'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#t" + note_id).attr("readonly", false);
			$("#t" + note_id).val();
			$("#t" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#ts'+note_id).show();
			$('#td'+note_id).hide();				
		}
	}
	
	//edit tab past pps notes state
	function edit_tab_pps_notesp(id){
		
		//alert(id);
		if(id != ''){ 
			var note_id = id.substr(3);			
			//alert(note_id);
			var note_txt = $('#'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#t" + note_id).attr("readonly", false);
			$("#t" + note_id).val();
			$("#t" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#tssp'+note_id).show();
			$('#tdsp'+note_id).hide();				
		}
	}
	
	//edit acc past pps notes state
	function edit_pps_notesp(id){
		
		//alert(id);
		if(id != ''){ 
			var note_id = id.substr(2);			
			//alert(note_id);
			var note_txt = $('#'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#" + note_id).attr("readonly", false);
			$("#" + note_id).val();
			$("#" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#ssp'+note_id).show();
			$('#dsp'+note_id).hide();				
		}
	}
	
	function save_tab_pps_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(2);
			$('#'+id).hide();
			$('#tn'+note_id).show();
			$('#td'+note_id).show();
			
			var note_txt = $('#t'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			} 
			if(note_txt != ''){
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#t" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt  + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);
						
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);
						
						$.jGrowl("PPS Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});
			}
		}
	}
	
	function save_tab_pps_notesp(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(4);
			$('#'+id).hide();
			$('#tsp'+note_id).show();
			$('#tdsp'+note_id).show();
			
			var note_txt = $('#t'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			if(note_txt != ''){
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#t" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);
						
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);
						
						$.jGrowl("PPS Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});
			}
		}
	}
	
	function save_pps_notesp(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			//alert(dt);
			var note_id = id.substr(3);
			$('#'+id).hide();
			$('#sp'+note_id).show();
			$('#dsp'+note_id).show();
			
			var note_txt = $('#'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			if(note_txt != ''){
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#'+note_id).val();
						$('#'+note_id).val(new_text);
						
						$('#t'+note_id).val();
						$('#t'+note_id).val(new_text);
						
						$.jGrowl("PPS Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});
			}
		}
	}
	
	//spouse pps note
	function edit_pps_spouse_note(id){
		
		if(id != ''){
			var note_id = id.substr(3);	
			
			var note_txt = $('#sp'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#sp" + note_id).attr("readonly", false);
			$("#sp" + note_id).val();
			$("#sp" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#ssp'+note_id).show();
			$('#dsp'+note_id).hide();	
		}
	}
	
	function delete_pps_spouse_note(id){
		
		if(id != ''){
			
			var note_id = id.substr(3);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_spouse_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#tdsp" + note_id).remove();
								$("#tesp" + note_id).remove();
								$('#tsp'+note_id).remove();
								
								$("#" + id).remove();
								$("#esp" + note_id).remove();
								$('#sp'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			}	
		}		
	}
	
	function save_pps_spouse_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			
			var note_id = id.substr(3);
			$('#'+id).hide();
			$('#esp'+note_id).show();
			$('#dsp'+note_id).show();	
			
			var note_txt = $('#sp'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			if(note_txt != ''){
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_spouse_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#sp" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#sp'+note_id).val();
						$('#sp'+note_id).val(new_text);
						
						$('#tsp'+note_id).val();
						$('#tsp'+note_id).val(new_text);
						
						$.jGrowl("PPS Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});					
			}
		}
	}
	
	function edit_tab_pps_spouse_note(id){
		
		if(id != ''){
			var note_id = id.substr(4);			
			//alert(id+', '+note_id);
			var note_txt = $('#tsp'+note_id).val();
			
			var last_note_line = note_txt.split('\n');
			var l = last_note_line.length - 1;
			//console.log(last_note_line.length + 'last_note_line - ' + last_note_line[3]);
			//var firstLine = note_txt.split('\n')[0];
			var firstLine = note_txt.split(last_note_line[l])[0];
			
			//var firstLine = note_txt.split('\n')[0];
			$("#tsp" + note_id).attr("readonly", false);
			$("#tsp" + note_id).val();
			$("#tsp" + note_id).val(firstLine);
			$('#'+id).hide();
			$('#tssp'+note_id).show();	
			$('#tdsp'+note_id).hide();
		}
	}
	
	function delete_tab_pps_spouse_note(id){
		
		if(id != ''){
			
			var note_id = id.substr(4);			
			var cid = document.getElementById("case_num").value;
			
			if(note_id != ''){
				
				swal('Are you sure to delete this note?', {
					buttons: {
						cancel: true,
						confirm: true,
					},
					icon: "warning"
				}).then(function(isConfirm) {
					
					if (isConfirm) {
						
						jQuery.ajax({
							url: "<?php echo base_url(); ?>" + "index.php/Casedetail/delete_pps_spouse_notes",
							type: "POST",
							data: {'case_id': cid, 'noteid':  note_id}, 
							success: function (data) {
								
								$("#tdsp" + note_id).remove();
								$("#tesp" + note_id).remove();
								$('#tsp'+note_id).remove();
								
								$("#dsp" + note_id).remove();
								$("#esp" + note_id).remove();
								$('#sp'+note_id).remove();
								
								$.jGrowl("Note deleted.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
						});
					}
				})
			} 
		}		
	}
	
	function save_tab_pps_spouse_note(id){
		
		var cid = document.getElementById("case_num").value;
				
		if(id != '' && cid != ''){
			
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var date = new Date();
			var d1 = date.toLocaleDateString("en-US", options);
			var hours = date.getHours();
		    var minutes = date.getMinutes();
			var sec = date.getSeconds();
		    var ampm = hours >= 12 ? 'PM' : 'AM';
		    hours = hours % 12;
		    hours = hours ? hours : 12; // the hour '0' should be '12'
		    minutes = minutes < 10 ? '0'+minutes : minutes;
		    
			var strTime = hours + ':' + minutes + ':' + sec + ' ' + ampm;
		    var dt = d1 + ' ' + strTime;
  
			
			var note_id = id.substr(4);
			//alert(id + ', ' + note_id);
			$('#'+id).hide();
			$('#tesp'+note_id).show();
			$('#tdsp'+note_id).show();
			
			var note_txt = $('#tsp'+note_id).val();
			if(note_txt != ''){
				
				note_txt = note_txt.replace(/\n$/, '');
			}
			if(note_txt != ''){
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_spouse_note",
					type: "POST",
					data: {'case_id': cid, 'noteid':  note_id,	'note_text': note_txt}, 
					success: function (data) {
						
						$("#tsp" + note_id).attr("readonly", true);
						var new_text = note_txt + '\n' + dt + ', ' + '<?php echo $this->session->userdata('username'); ?>';
						$('#tsp'+note_id).val();
						$('#tsp'+note_id).val(new_text);
						
						$('#sp'+note_id).val();
						$('#sp'+note_id).val(new_text);
						
						$.jGrowl("PPS Note updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				});					
			}
		}
	}
	
	function create_pers_pps_detail_record(ppsid){
		
		var cid = document.getElementById("case_num").value;
		var records = '';
		
		if(ppsid != '' && cid != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/create_pps_detail_record_pers",
				type: "POST",
				data: {'case_id': cid, 'pps_id':  ppsid}, 
				async: false,
				
				success: function (data) {
					//console.log('success - ' + data); 
					records = data;
				}
			});
			//console.log( ' records '+records);
			return records;
		}
	}
	
	function create_sp_pps_detail_record(ppsid){
		
		var cid = document.getElementById("case_num").value;
		var records = '';
		
		if(ppsid != '' && cid != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/create_pps_detail_record_sp",
				type: "POST",
				data: {'case_id': cid, 'pps_id':  ppsid}, 
				async: false,
				
				success: function (data) {
					//console.log('success - ' + data); 
					records = data;
				}
			});
			//console.log( ' records '+records);
			return records;
		}
	}
	
	function create_state_pps_detail_record(ppsid){
		
		var cid = document.getElementById("case_num").value;
		var records = '';
		
		if(ppsid != '' && cid != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/create_pps_detail_record_state",
				type: "POST",
				data: {'case_id': cid, 'pps_id':  ppsid}, 
				async: false,
				
				success: function (data) {
					//console.log('success - ' + data); 
					records = data;
				}
			});
			//console.log( ' records '+records);
			return records;
		}
	}
	
	function updatePPSStatus(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		
		if(ppsid != '' && cid != ''){
			
			swal('Are you sure to set PPS status to Completed?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				
				if (isConfirm) {
					
					jQuery.ajax({
				
						url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_status",
						type: "POST",
						data: {'pps_id':  ppsid, 'case_id': cid}, 
						
						success: function (data1) {
							
							if(data1 == 2){
								
								$.jGrowl("PPS Status updated.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							
								refresh_data();
							}
							else if(data1 == 1){
								
								$(data).closest("tr").find("button").attr("disabled", true);
									
								$.jGrowl("PPS Status updated.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});
							}
							else{
								
								$.jGrowl("Unable to update PPS Status.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-red'
								});
							}
						}
					});
				}
			})
		}
	}
	
	function tabEditPPS(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		//alert('ppsid - ' + ppsid);
		if(ppsid != '' && cid != ''){
			
			$(data).closest('tr').find('#tab_poa_issue, .js-example-basic-single, #tab_pps_assigned_to').attr("disabled",false);
			
			$(data).closest('tr').find('#tab_edit_pps').hide();
			$(data).closest('tr').find('#tab_save_pps').show();
		}
	}
	
	function EditPPS(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		//alert('ppsid - ' + ppsid);
		if(ppsid != '' && cid != ''){
			
			$(data).closest('tr').find('#poa_issue, .js-example-basic-single, #pps_assigned_to').attr("disabled",false);
			
			$(data).closest('tr').find('#edit_pps').hide();
			$(data).closest('tr').find('#save_pps').show();
		}
	}
	
	function EditPPSSP(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		//alert('ppsid - ' + ppsid);
		if(ppsid != '' && cid != ''){
			
			$(data).closest('tr').find('#poa_issue_sp, .js-example-basic-single, #pps_assigned_to_sp').attr("disabled",false);
			
			$(data).closest('tr').find('#edit_pps_sp').hide();
			$(data).closest('tr').find('#save_pps_sp').show();
		}
	}

	function EditPPSState(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		//alert('ppsid - ' + ppsid);
		if(ppsid != '' && cid != ''){
			
			$(data).closest('tr').find('#poa_issue_state, .js-example-basic-single, #pps_assigned_to_state, #tab_pps_verbal_poa, #tab_pps_state, #tab_pps_instruct').attr("disabled",false);
			
			$(data).closest('tr').find('#edit_pps_state').hide();
			$(data).closest('tr').find('#save_pps_state').show();
		}
	}

	function tabSavePPS(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		
		var poa_issue = $(data).closest('tr').find('#tab_poa_issue').val();
		var biz_poa_issue = $(data).closest('tr').find('.js-example-basic-single').val();
		//alert(biz_poa_issue);
		//return false;
		
		var assigned_to = $(data).closest('tr').find('#tab_pps_assigned_to').val();		
		
		if(ppsid != '' && cid != '' && (poa_issue !='' || biz_poa_issue !='' || assigned_to !='')){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_values",
				type: "POST",
				data: {'pps_id':  ppsid, 'case_id': cid, 'poa_issue': poa_issue, 'biz_poa_issue': biz_poa_issue, 'assigned_to': assigned_to}, 
				
				success: function (data1) {
					
					if(data1 == 1){
							
						$.jGrowl("PPS Values updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update PPS Values.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				}
			});
					
			$(data).closest('tr').find('#tab_poa_issue, #tab_biz_poa_issue, #tab_pps_assigned_to').attr("disabled",true);
			
			$(data).closest('tr').find('#tab_edit_pps').show();
			$(data).closest('tr').find('#tab_save_pps').hide();
		}
	}
	
	function SavePPS(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		
		var poa_issue = $(data).closest('tr').find('#poa_issue').val();
		var biz_poa_issue = $(data).closest('tr').find('.js-example-basic-single').val();
		var assigned_to = $(data).closest('tr').find('#pps_assigned_to').val();		
		//alert(biz_poa_issue);
		//return false;
		
		if(ppsid != '' && cid != '' && (poa_issue !='' || biz_poa_issue !='' || assigned_to !='')){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_values",
				type: "POST",
				data: {'pps_id':  ppsid, 'case_id': cid, 'poa_issue': poa_issue, 'biz_poa_issue': biz_poa_issue, 'assigned_to': assigned_to}, 
				
				success: function (data1) {
					
					if(data1 == 1){
							
						$.jGrowl("PPS Values updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update PPS Values.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				}
			});
					
			$(data).closest('tr').find('#poa_issue, #biz_poa_issue, #pps_assigned_to').attr("disabled",true);
			
			$(data).closest('tr').find('#edit_pps').show();
			$(data).closest('tr').find('#save_pps').hide();
		}
	}
	
	function SavePPSSP(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		
		var poa_issue = $(data).closest('tr').find('#poa_issue_sp').val();
		var biz_poa_issue = $(data).closest('tr').find('.js-example-basic-single').val();
		var assigned_to = $(data).closest('tr').find('#pps_assigned_to_sp').val();		
		//alert(biz_poa_issue);
		//return false;
		
		if(ppsid != '' && cid != '' && (poa_issue !='' || biz_poa_issue !='' || assigned_to !='')){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_values",
				type: "POST",
				data: {'pps_id':  ppsid, 'case_id': cid, 'poa_issue': poa_issue, 'biz_poa_issue': biz_poa_issue, 'assigned_to': assigned_to}, 
				
				success: function (data1) {
					
					if(data1 == 1){
							
						$.jGrowl("PPS Values updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update PPS Values.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				}
			});
					
			$(data).closest('tr').find('#poa_issue_sp, #biz_poa_issue_sp, #pps_assigned_to_sp').attr("disabled",true);
			
			$(data).closest('tr').find('#edit_pps_sp').show();
			$(data).closest('tr').find('#save_pps_sp').hide();
		}
	}

	function SavePPSState(data){
		
		var cid = document.getElementById("case_num").value;
		var ppsid = $(data).closest('tr').attr('id');
		
		var poa_issue = $(data).closest('tr').find('#poa_issue_state').val();
		var biz_poa_issue = $(data).closest('tr').find('.js-example-basic-single').val();
		var assigned_to = $(data).closest('tr').find('#pps_assigned_to_state').val();
		var verbal_poa = $(data).closest('tr').find('#tab_pps_verbal_poa').val();		
		var state = $(data).closest('tr').find('#tab_pps_state').val();	
		var instruction = $(data).closest('tr').find('#tab_pps_instruct').val();	

		//alert(biz_poa_issue);
		//return false;
		
		if(ppsid != '' && cid != '' && (poa_issue !='' || biz_poa_issue !='' || assigned_to !='')){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/update_pps_values",
				type: "POST",
				data: {'pps_id':  ppsid, 'case_id': cid, 'poa_issue': poa_issue, 'biz_poa_issue': biz_poa_issue, 'assigned_to': assigned_to, 'verbal_poa': verbal_poa, 'state': state, 'instruction': instruction}, 
				
				success: function (data1) {
					
					if(data1 == 1){
							
						$.jGrowl("PPS Values updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
					else{
						
						$.jGrowl("Unable to update PPS Values.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}
				}
			});
					
			$(data).closest('tr').find('#poa_issue_state, #biz_poa_issue_state, #pps_assigned_to_state, #tab_pps_verbal_poa, #tab_pps_state, #tab_pps_instruct').attr("disabled",true);
			
			$(data).closest('tr').find('#edit_pps_state').show();
			$(data).closest('tr').find('#save_pps_state').hide();
		}
	}

	function tab_add_taxes(){
		
		//var html = '<h5><div class="col-md-8"><input class="form-control tab_expense_input extra_expense_fields" type="text" name="extraexpensetext_' + extra_expense + '" id="extraexpensetext_' + extra_expense + '" value=""></div><div class="col-md-4"> 				<input class="form-control tab_expense_input extra_expense_fields" type="text" name="extraexpenseval_'+ extra_expense + '" id="extraexpenseval_' + extra_expense + '" value="">			</div></h5>';
		
		var html = '<h5 id="newth5"><div class="col-md-8"><input class="form-control tab_expense_input extra_expense_fields" type="text" name="extraexpensetext_' + extra_expense + '" id="extraexpensetext_' + extra_expense + '" value=""></div><div class="col-md-4"> 				<input class="form-control tab_expense_input_v extra_expense_fields" type="text" name="extraexpenseval_'+ extra_expense + '" id="extraexpenseval_' + extra_expense + '" value="">		<button type="button" class="btn btn-xs btn-danger" id="tab_delete_extra_exp" title="Delete" onclick="tab_delete_extra_expense(this);" data-button=\'{"id": "new_exp"}\'><i class="glyph-icon icon-remove"></i></button>	</div></h5>';
		
		$('#tab_other_add_expense').append(html);
		
		extra_expense = extra_expense + 1;
	}
	
	function add_taxes(){
		
		//var html = '<h5><div class="col-md-8"><input class="form-control tab_expense_input extra_expense_fields" type="text" name="extraexpensetext_' + extra_expense + '" id="extraexpensetext_' + extra_expense + '" value=""></div><div class="col-md-4"> 				<input class="form-control tab_expense_input extra_expense_fields" type="text" name="extraexpenseval_'+ extra_expense + '" id="extraexpenseval_' + extra_expense + '" value="">			</div></h5>';
		
		var html = '<h5 id="newh5"><div class="col-md-8"><input class="form-control expense_input extra_expense_fields" type="text" name="extraexpensetext_' + extra_expense + '" id="extraexpensetext_' + extra_expense + '" value=""></div><div class="col-md-4"> 				<input class="form-control expense_input_v extra_expense_fields" type="text" name="extraexpenseval_'+ extra_expense + '" id="extraexpenseval_' + extra_expense + '" value="">		<button type="button" class="btn btn-xs btn-danger" id="delete_extra_exp" title="Delete" onclick="delete_extra_expense(this);" data-button=\'{"id": "new_exp"}\'><i class="glyph-icon icon-remove"></i></button>	</div></h5>';
		
		$('#other_add_expense').append(html);
		
		extra_expense = extra_expense + 1;
	}
	
	function tab_delete_extra_expense(detail){
		
		var d = $(detail).data('button');      
        //console.log('expense id - ' + d.id);
		
		if(d.id == ''){
			
			$.jGrowl("Unable to delete expense. Expense Id not found", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
		}
		else if(d.id == 'new_exp'){
			
			$('#newth5').remove();
		}
		else{
			
			swal('Confirm delete expense?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					jQuery.ajax({
				
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/casedetail/delete_extra_expense",				
						data: {'id': d.id},
						
						success: function(res)              
						{
							$('#th5' + d.id).remove();
							$('#h5' + d.id).remove();
							$.jGrowl("Expense deleted successfully", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
						}
					})
				}
			})			
		}
	}
	
	function delete_extra_expense(detail){
		
		var d = $(detail).data('button');      
        //console.log('expense id - ' + d.id);
		//alert('delete_extra_expense - ' + d.id);
		//return true;
		if(d.id == ''){
			
			$.jGrowl("Unable to delete expense. Expense Id not found", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
		}
		else if(d.id == 'new_exp'){
			
			$('#newh5').remove();
		}
		else{
			
			swal('Confirm delete expense?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {
					
					jQuery.ajax({
				
						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/casedetail/delete_extra_expense",				
						data: {'id': d.id},
						
						success: function(res)              
						{
							$('#th5' + d.id).remove();
							$('#h5' + d.id).remove();
							$.jGrowl("Expense deleted successfully", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
						}
					})
				}
			})			
		}
	}
	
	function getFaxList(){
		
		var cid = document.getElementById("case_num").value;
				
		jQuery.ajax({
			
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/get_case_fax_list",
			type: "POST",
			data: {'case_id': cid}, 
			
			success: function(response) 
			{	
				//console.log(response)
				if(response != ''){
					
					var records = JSON.parse(response);
					if(records.length > 0){
						
						var tbody = '';
						
						for (var i in records)
						{
							//console.log(records.result[i].title + ', ' + records.result[i].versionid)
							tbody += '<tr>';
							
							tbody += '<td>' + records[i].name + '</td>';
							tbody += '<td>' + records[i].fax_number_c + '</td>';
							tbody += '<td>' + records[i].date_entered + '</td>';
							tbody += '<td>' + records[i].date_modified + '</td>';
							tbody += '<td>' + records[i].uname + '</td>';
							tbody += '<td>' + records[i].status_c + '</td>'; 
							
							tbody += '</tr>';
						}
						
						if(tbody != ''){
							
							$('.tab_faxlist_tbody').html('');
							$('.tab_faxlist_tbody').append(tbody);
							//$('.docstar_tbody').html('');
							//$('.docstar_tbody').append(tbody);
						}
					}
				}
			}
		})
		
	}
	
	
	function open_file(data){
		
		var versionID = $(data).attr('id');
		var platform_name = $(data).attr('name');
		var caseid = document.getElementById("case_num").value;
		var data = new FormData(); 
		
		//alert(platform_name);
		
		if(versionID != ''){		
		
			data.append("CaseNo", caseid);
			data.append("VersionID", versionID);
			data.append("ServiceType", "Download");
			data.append("platform", platform_name);			
			
			$.ajax({
				
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $sugar_url; ?>" + "/DocstarSugar.php",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (data) {
					
					//window.open(data, '_blank');
					window.open('<?php echo $sugar_url; ?>'+data, '_blank');
				}
			});			
		}
	}
	
	function uploadFile(){
		
		var cid = document.getElementById("case_num").value;
		var categoryName = "Sugar Lead Uploads";
		var full_name = '<?php echo $this->session->userdata('username');?>';
		var filen = $("#file").val();
		var form = $('#docstarUploadForm')[0];
		var user_id = '<?php echo $this->session->userdata('User_id');?>';
		
		if(filen == ''){
			
			alert("Please choose a file to upload");
			return false;
		}
		else{
			
			var data = new FormData(form);
				
			data.append("CaseNo", cid);
			data.append("ServiceType", "Upload");
			data.append("fullName", full_name);
			data.append("categoryName", categoryName);
			data.append("user_id", user_id);
			
			jQuery.ajax({
				
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $sugar_url;?>" + "DocstarSugar.php",
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				
				success: function(response) 
				{
					//console.log('response - ' + response);
					
					if(response == 1){
						
						$.jGrowl("Document Uploaded Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});						
					}
					else{
						
						$.jGrowl("Unable to upload Document.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});	
					}
					$("#file").val("");
					$(".docstar-upload").hide(); 
				}
			})
		}
	}
	
	
	function renderSelect2(){
		//alert('renderSelect2')	
		$('.js-example-basic-single').prop('disabled', true);
		$('.js-example-basic-single').select2();
		$(".js-example-basic-single").select2({
		  theme: "classic"
		});
	}
	
	function escapeHtml(text) {
		
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};

		return text.replace(/[&<>"']/g, function(m) { return map[m]; });
	}
	
	//edit pps instruction
	function edit_pps_instruction(){
		
		var pps_id = $('#pps_id_tab').val();		
		
		$('#tab_editppsinstruct').hide();
		$('#tab_saveppsinstruct').show();
		$("#tab_pps_instruct_note").attr("readonly", false);		
	}
	
	//save pps instruction
	function save_pps_instruction(){
		
		var pps_id = $('#pps_id_tab').val();		
		var instruction = $('#tab_pps_instruct_note').val();		
		//alert(pps_id+','+ instruction);
		//return 1;
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#tab_editppsinstruct').show();
						$('#tab_saveppsinstruct').hide();
						$("#tab_pps_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}
		
			
	}
	
	//edit pps instruction - Accordian
	function acc_edit_pps_instruction(){
		
		var pps_id = $('#pps_id').val();		
		
		$('#editppsinstruct').hide();
		$('#saveppsinstruct').show();
		$("#pps_instruct_note").attr("readonly", false);		
	}
	
	//save pps instruction
	function acc_save_pps_instruction(){
		
		var pps_id = $('#pps_id').val();		
		var instruction = $('#pps_instruct_note').val();		
		
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#editppsinstruct').show();
						$('#saveppsinstruct').hide();
						$("#pps_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}
		
			
	}
	
	//edit pps spouse instruction - Tab
	function tab_edit_pps_spinstruction(){
		
		var pps_id = $('#pps_id_tab_spouse').val();		
		
		$('#tab_editpps_spinstruct').hide();
		$('#tab_savepps_spinstruct').show();
		$("#tab_pps_spouse_instruct_note").attr("readonly", false);		
	}
	
	//save pps spouse instruction - Tab
	function tab_save_pps_spinstruction(){
		
		var pps_id = $('#pps_id_tab_spouse').val();		
		var instruction = $('#tab_pps_spouse_instruct_note').val();		
		
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#tab_editpps_spinstruct').show();
						$('#tab_savepps_spinstruct').hide();
						$("#tab_pps_spouse_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}
	}
	
	//edit pps spouse instruction - Accordian
	function edit_pps_spinstruction(){
		
		var pps_id = $('#pps_id_spouse').val();		
		
		$('#editpps_spinstruct').hide();
		$('#savepps_spinstruct').show();
		$("#pps_spouse_instruct_note").attr("readonly", false);		
	}
	
	//save pps spouse instruction - Tab
	function save_pps_spinstruction(){
		
		var pps_id = $('#pps_id_spouse').val();		
		var instruction = $('#pps_spouse_instruct_note').val();		
		
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#editpps_spinstruct').show();
						$('#savepps_spinstruct').hide();
						$("#pps_spouse_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}	
	}
	
	//edit pps state instruction - Tab
	function tab_edit_ppsstate_instruction(){
		
		var pps_id = $('#pps_state_pers_id_tab').val();		
		
		$('#tab_editppsstateinstruct').hide();
		$('#tab_saveppsstateinstruct').show();
		$("#tab_pps_state_instruct_note").attr("readonly", false);		
	}
	
	//save pps state instruction - Tab
	function tab_save_ppsstate_instruction(){
		
		var pps_id = $('#pps_state_pers_id_tab').val();		
		var instruction = $('#tab_pps_state_instruct_note').val();		
		
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#tab_editppsstateinstruct').show();
						$('#tab_saveppsstateinstruct').hide();
						$("#tab_pps_state_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}	
	}
	
	//edit pps state instruction - Accordian
	function edit_ppsstate_instruction(){
		
		var pps_id = $('#pps_state_pers_id').val();		
		
		$('#editppsstateinstruct').hide();
		$('#saveppsstateinstruct').show();
		$("#pps_state_instruct_note").attr("readonly", false);		
	}
	
	//save pps state instruction - Tab
	function save_ppsstate_instruction(){
		
		var pps_id = $('#pps_state_pers_id').val();		
		var instruction = $('#pps_state_instruct_note').val();		
		
		if(pps_id != '' && instruction != ''){
			
			jQuery.ajax({
				
				url: "<?php echo base_url(); ?>" + "index.php/Casedetail/save_pps_instruction",
				type: "POST",
				data: {'pps_id':  pps_id, 'instruction': instruction}, 
				async: false,
				
				success: function (data) {
					
					if(data == 1){
						
						$.jGrowl("PPS Instruction updated.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
						
						$('#editppsstateinstruct').show();
						$('#saveppsstateinstruct').hide();
						$("#pps_state_instruct_note").attr("readonly", true);	
					}
					else{
						
						$.jGrowl("Unable to update PPS Instruction.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
				}
			});
		}	
	}
	
	function getSugarDropdowns()
	{
		var self = this;	
		jQuery.ajax({
				
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/getDropdowns",
			type: "POST",
			async: false,
			
			success: function (data) {

				self.dd_data = data
				//console.log('getSugarDropdowns - ' + data);
			}
		});

		return self.dd_data;
		//sugar_dropdowns = self.dd_data;
	}
		

	//MAIN FUNCTION TO DISPLAY ALL DATA
	
	function show_data()
	{ 
		$('#show_caseid_data').attr('disabled',true);
		$('#my_cases_div').hide();	
		
		var case_id = <?php echo $id; ?> 
		//console.log('case_id-- '+case_id);	
		
		if(case_id == "")
		{
			$("#s_msg").html("CaseID cannot be blank!!");
			$("#cid").focus();
			return false;
		}
		else{
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/get_data",				
				data: {caseid: case_id},
				
				success: function(res)              
				{					
					
					$("#case_num").val(case_id); 
					$('.accordion_set').hide();
					
					if(refresh == 0)
					{
						$('.tabs').show(); 
					}  							
					 
					$("#refresh_caseid_data").show();
					$('.form-group').hide();
					$('#hide_caseid_data').show(); 
					$('.accordion-view').show();
					$('.tabs-view').show();					
					
					if(refresh == 0)
					{
						getInitialServices();
						checkBusinessServiceLink();
						mainServiceNameValues();
					}
					
					
					getFaxList();
					
					var jsonData = $.parseJSON(res);
					
					if(jsonData.reported_inc){
 
                        var reported_income = jsonData.reported_inc;						
						var len_reported_income = reported_income.length;						
                    }
                    if(jsonData.account_stat_data){
 
                        var account_status_array = jsonData.account_stat_data;              
                        var len_as = account_status_array.length;
                    }
					else{

						var account_status_array = [];              
						var len_as = 0;
					}
                    if(jsonData.activity_data){
						
                        var activity_array = jsonData.activity_data;  
                        var len_activity = activity_array.length;
                    }					
					if(jsonData.penalty_data){
						
						var penalty_array = jsonData.penalty_data;					
						var len_p = penalty_array.length;
					}
					if(jsonData.civil_penalty_data){
						
						var civil_penalty_array = jsonData.civil_penalty_data;					
						var len_cp = civil_penalty_array.length;
					} 
					if(jsonData.csed_data){
						
						var csed_array = jsonData.csed_data;					
						var len_csed = csed_array.length;
					}
					if(jsonData.ths_data){
						
						var ths_array = jsonData.ths_data;					
						var len_ths_array = ths_array.length;
					}
					if(jsonData.casereview_data){
						
						var casereview_array = jsonData.casereview_data;					
						var len_casereview_array = casereview_array.length;
					}
					if(jsonData.notes){
						
						var review_notes_array = jsonData.notes;					
						var len_review_notes = review_notes_array.length;
					} 
					if(jsonData.notes_si){
						
						var notes_si_array = jsonData.notes_si;					
						var len_notes_si = notes_si_array.length;
					} 
					if(jsonData.pps_notes){
						
						var pps_notes_array = jsonData.pps_notes;					
						var len_pps_notes = pps_notes_array.length;
					}
					if(jsonData.pps_notes_spouse){
						
						var pps_notes_spouse_array = jsonData.pps_notes_spouse;					
						var len_pps_notes_spouse = pps_notes_spouse_array.length;
					}
					if(jsonData.pps_notes_state_personal){
						
						var pps_notes_state_personal_array = jsonData.pps_notes_state_personal;
						var len_pps_notes_state_personal = pps_notes_state_personal_array.length;
					}
					if(jsonData.summary){
						
						var summary_array = jsonData.summary;					
						var len_summary = summary_array.length;						
					} 
					if(jsonData.extra_cases){
						
						var extra_cases_array = jsonData.extra_cases;					
						var len_extra_cases = extra_cases_array.length;						
					}
					//console.log(jsonData.extra_cases);
					if(jsonData.aggregate){
						
						var aggregate_array = jsonData.aggregate;					
						var len_aggregate = aggregate_array.length;						
					}
					if(jsonData.pps_summary){
						
						var pps_summary_array = jsonData.pps_summary;					
						var len_pps_summary = pps_summary_array.length;						
					}
					if(jsonData.all_case_pps){
						
						var all_case_pps_array = jsonData.all_case_pps;					
						var len_all_case_pps = all_case_pps_array.length;
					}
					if(jsonData.pps){
						
						var pps_array = jsonData.pps;					
						var len_pps = pps_array.length;
					}   
					if(jsonData.pps_spouse){
						
						var spouse_pps_array = jsonData.pps_spouse;					
						var len_pps_spouse = spouse_pps_array.length;
					}
					if(jsonData.pps_state_personel){
						
						var pps_state_personel_array = jsonData.pps_state_personel;					
						var len_pps_state_personel = pps_state_personel_array.length;
					}					
					if(jsonData.assets){
						
						var assets_array = jsonData.assets;					
						var len_assets = assets_array.length;
					}
					if(jsonData.fin_overview){
						
						var fin_overview_array = jsonData.fin_overview;					
						var len_fin_overview = fin_overview_array.length
					} 
					if(jsonData.vehicles){
						
						var vehicles_array = jsonData.vehicles;					
						var len_vehicles = vehicles_array.length
					}
					if(jsonData.real_estate){
						
						var real_estate_array = jsonData.real_estate;					
						var len_real_estate = real_estate_array.length
					} 
					if(jsonData.sugar_irs_standards){
						
						var sugar_irs_standards_array = jsonData.sugar_irs_standards;					
						var len_sugar_irs_standards = sugar_irs_standards_array.length
						//console.log('sugar_irs_standards ' + sugar_irs_standards_array);
					} 
					if(jsonData.additional_expense){ 
						
						var additional_expense_array = jsonData.additional_expense;					
						var len_additional_expense = additional_expense_array.length
					}
					if(jsonData.tc_intake){ 
						
						var tc_intake_array = jsonData.tc_intake;					
						var len_tc_intake = tc_intake_array.length
					}	
					if(jsonData.finance){
						
						var finance_array = jsonData.finance;					
						var len_finance = finance_array.length;						
					}
					if(jsonData.pps_users){
						
						var pps_users_array = jsonData.pps_users;					
						var len_pps_users = pps_users_array.length;						
					} 
					if(jsonData.dependents){
						
						var dependents_array = jsonData.dependents;					
						var len_dependents = dependents_array.length;	
					}
					if(jsonData.additional_income_details){
                     
						var ai_array = jsonData.additional_income_details;					
						var len_ai_array = ai_array.length;						
					}
					if(jsonData.sugarTaxPrep_data){

						var sugarTaxPrep_data = jsonData.sugarTaxPrep_data;
						var len_taxprep = sugarTaxPrep_data.length;
						console.log('golbaljs sugarTaxPrep_data 1027',sugarTaxPrep_data);
					}
					else{

						var sugarTaxPrep_data = [];	
					}

					var dropdowns = getSugarDropdowns();
					//console.log('dropdowns - ' + dropdowns);
					//console.log(pps_notes_spouse_array);
					var opportunity_created = jsonData.opportunity;
					
					if(opportunity_created == 0){
						
						$('#add_services').attr("disabled", true);
						$('#add_business').attr("disabled", true);
						$('#service_error_msg').text('Add Service button is disabled as Opportunity is not created in Sugar');
					}
					//alert(main_business_exist + ' biz ');
					if(main_business_exist == 1)
					{
						$('#add_business').hide();
					}
					else{

						$('#add_business').show();
					}

					//If old pps are there show button to open old pps
					/*if(jsonData.check_old_pps_personal > 1 || jsonData.check_old_pps_spouse > 1 || jsonData.check_old_pps_state > 1){
						
						var html3 = '<a class="btn btn-default" href="<?php echo base_url();?>index.php/oldpps/records?id='+case_id+'" title="Extra PPS" id="view_extra_pps"  target="_blank">	<span> Extra PPS </span> </a>';
						
						$('#more_pps').append(html3);
						$('#more_pps').show();
					}*/
					
					// Check lead Marketing
					if(jsonData.lead_campaign){

						var lead_campaign_arr = jsonData.lead_campaign;					
						var len_lead_campaign = lead_campaign_arr.length;
						
						if(len_lead_campaign){

							var lead_campaign_val = lead_campaign_arr[0]['lead_campaign'];

							if(lead_campaign_val == "liberty-franchise")
							{
								$('#lcampaign').show();
							}
							else{

								$('#lcampaign').hide();
							}
							//alert(lead_campaign_val);
						}
						else{

							$('#lcampaign').hide();
						}
					}
					else{

						$('#lcampaign').hide();
					}

					if(jsonData.checkpps_extra){
						
						var extra_pps_arr = jsonData.checkpps_extra;					
						var len_extra_pps = extra_pps_arr.length;
						
						if(len_extra_pps){
							
							var pps_names = '';
							
							for (var i = 0; i < len_extra_pps; i++) {
								
								pps_names += ', ' + extra_pps_arr[i]['name'];
							}
							pps_names = pps_names.substr(1);
							
							if(refresh == 0)
							{
								alert('This case has extra pps apart from Federal Personal. They are - '+pps_names);
							}
						}
						
					}
					
					var default_pps = 0;
					//alert(jsonData.checkpps)
					if(jsonData.checkpps != 0){
						
						default_pps = 1;
						$('#PPStab_label').show();
						$('#PPStab').show(); 	
						$('#pps_accordion').show();		
						//$('#tab_save_summary').show();		
						//$('#save_summary').show();		
					}
					else{
						
						//$('#tab_save_summary').hide();		
						//$('#save_summary').hide();
					}
					if(jsonData.checkpps_sp != 0){
						
						default_pps = 1;
						$('#PPSspousetab').show();
						$('#PPSspousetab_label').show();
						$('#spouse_accordion').show();						
					}
					
					if(jsonData.checkpps_statepers != 0){
						
						default_pps = 1;
						$('#PPSstatepersonaltab').show();
						$('#PPSstatepersonaltab_label').show();
						$('#pps_state_accordion').show();
						 						
					}
					
					if(!len_pps && jsonData.checkpps != 0){
						
						
						var empty_pps_id = jsonData.checkpps;
						
						var pps_record = create_pers_pps_detail_record(empty_pps_id);
						
						if(pps_record != 0){
							
							var pps_array = $.parseJSON(pps_record);			
							var len_pps = pps_array.length;
						}
						
						$('#tab_pps_table').attr("name", empty_pps_id); 
						$('#pps_table').attr("name", empty_pps_id);
						$('#pps_id').val(empty_pps_id);
						$('#pps_id_tab').val(empty_pps_id);
					}
					
					if(!len_pps_spouse && jsonData.checkpps_sp != 0){
						
						var empty_pps_id_sp = jsonData.checkpps_sp;
						
						var pps_sp_record = create_sp_pps_detail_record(empty_pps_id_sp);
						
						if(pps_sp_record != 0){
							
							var spouse_pps_array = $.parseJSON(pps_sp_record);			
							var len_pps_spouse = spouse_pps_array.length;
						}
						
						$('#tab_spouse_pps_table').attr("name", empty_pps_id_sp);
						$('#spouse_pps_table').attr("name", empty_pps_id_sp);
						$('#pps_id_tab_spouse').val(empty_pps_id);
						$('#pps_id_spouse').val(empty_pps_id);
					}
					
					if(!len_pps_state_personel && jsonData.checkpps_statepers != 0){
						
						var empty_pps_id = jsonData.checkpps_statepers;
						
						var pps_state_record = create_state_pps_detail_record(empty_pps_id);
						
						if(pps_state_record != 0){
							
							var pps_state_personel_array = $.parseJSON(pps_state_record);			
							var len_pps_state_personel = pps_state_personel_array.length;
						}
						
						$('#tab_pps_state_pers_table').attr("name", empty_pps_id);
						$('#pps_state_pers_table').attr("name", empty_pps_id);
						$('#pps_state_pers_id_tab').val(empty_pps_id);						
						$('#pps_state_pers_id').val(empty_pps_id);
					}					
					
					//Display sugartax server data
					if(len_as){

						generateAccountStatusTable(len_as, account_status_array, sugarTaxPrep_data);
					}	
					else{

						generateAccountStatusTable(len_as, account_status_array, sugarTaxPrep_data);
					}
					
					if(len_p){
						
						generatePenaltyInterestTable(len_p, penalty_array);
					}
					
					if(len_activity){
						
						generateResoCollActivityTable(len_activity, activity_array);
					}
					
					if(len_cp){
						
						generateWageIncomeTable(len_cp, civil_penalty_array);
					}
					 
					if(len_csed){
						
						generateCSEDTable(len_csed, csed_array);						
					}
					//alert(jsonData.is_admin + ', ' + len_ths_array)
					if(jsonData.is_reviewer == 1 || jsonData.is_admin == 1 || jsonData.is_practitioner == 1){ //is_reviewer
					
						$('#ThsSummarytab').show();
						$('#ThsSummary_label').show();
						generateTHSSummaryTable(len_ths_array, ths_array);						
					}
					
					if(len_casereview_array>0){ //is_reviewer
					
						$('#CaseReviewtab').show();
						$('#CaseReview_label').show();
						generateCaseReviewTable(len_casereview_array, casereview_array);						
					}

					if(len_pps_summary)
					{
						$("#tab_inves_pps_id").val(pps_summary_array[0]['ppsid']);
						$("#inves_pps_id").val(pps_summary_array[0]['ppsid']);
						var analysis1 = pps_summary_array[0]['expense_analysis_c'];
						var analysis2 = pps_summary_array[0]['income_analysis_c'];
						var analysis3 = pps_summary_array[0]['asset_analysis_c'];
						var account_status = pps_summary_array[0]['account_status_c'];
						var compliance_summary = pps_summary_array[0]['compliance_summary_c'];
						var di = pps_summary_array[0]['disposable_income_c'];
						var pps_instruction = pps_summary_array[0]['pps_instructions_c'];
						
						$(".input_di").val(di);	
						$(".tab_input_di").val(di);
						$("#tab_pps_instruct_note").val(pps_instruction);
						$("#pps_instruct_note").val(pps_instruction);
					}					
					
					//DISPLAY DATA FOR SUMMARY TAB
					
					//Aggregate details in summary tab len_aggregate aggregate_array
					
					if(len_aggregate && refresh == 0){	
					
						//summary finance status fields
						$('#tab_aggregate_id').val(aggregate_array[0]['id_c']);
						$('#aggregate_id').val(aggregate_array[0]['id_c']);
						
						$('.tab_prelim_fee').val(aggregate_array[0]['prelim_fee_c']);
						$('.prelim_fee').val(aggregate_array[0]['prelim_fee_c']);
						
						var sp_service_common = '';
						var specified_services_select_tab = '<select class="form-control specified_service" name="tab_specified_service" id="tab_specified_service"><option value=""></option>';
						
						var specified_services_select = '<select class="form-control specified_service" name="specified_service" id="specified_service"><option value=""></option>';
						
						if(aggregate_array[0]['specified_services_c'] == 'OIC'){
							
							sp_service_common += '<option value="OIC" selected>OIC</option>	';	
						}
						else{
							sp_service_common += '<option value="OIC">OIC</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'CNC'){
							
							sp_service_common += '<option value="CNC" selected>CNC</option>	';	
						}
						else{
							sp_service_common += '<option value="CNC">CNC</option>	';
						}						
						if(aggregate_array[0]['specified_services_c'] == 'CIA'){
							
							sp_service_common += '<option value="CIA" selected>CIA</option>	';	
						}
						else{
							sp_service_common += '<option value="CIA">CIA</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'PPIA'){
							
							sp_service_common += '<option value="PPIA" selected>PPIA</option>	';	
						}
						else{
							sp_service_common += '<option value="PPIA">PPIA</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'PPIA'){
							
							sp_service_common += '<option value="PPIA" selected>PPIA</option>	';	
						}
						else{
							sp_service_common += '<option value="PPIA">PPIA</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == '25K SIA'){
							
							sp_service_common += '<option value="25K SIA" selected>25K SIA</option>	';	
						}
						else{
							sp_service_common += '<option value="25K SIA">25K SIA</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == '50K SIA'){
							
							sp_service_common += '<option value="50K SIA" selected>50K SIA</option>	';	
						}
						else{
							sp_service_common += '<option value="50K SIA">50K SIA</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'Audit'){
							
							sp_service_common += '<option value="Audit" selected>Audit</option>	';	
						}
						else{
							sp_service_common += '<option value="Audit">Audit</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'State Only'){
							
							sp_service_common += '<option value="State Only" selected>State Only</option>	';	
						}
						else{
							sp_service_common += '<option value="State Only">State Only</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'Tax Prep Only'){
							
							sp_service_common += '<option value="Tax Prep Only" selected>Tax Prep Only</option>	';	
						}
						else{
							sp_service_common += '<option value="Tax Prep Only">Tax Prep Only</option>	';
						}
						if(aggregate_array[0]['specified_services_c'] == 'No Rec'){
							
							sp_service_common += '<option value="No Rec" selected>No Rec</option>	';	
						}
						else{
							sp_service_common += '<option value="No Rec">No Rec</option>	';
						}
						
						sp_service_common += '</select>';
						
						specified_services_select_tab += sp_service_common;
						specified_services_select += sp_service_common;
						
						$('#tab_spec_service').append(specified_services_select_tab);
						$('#spec_service').append(specified_services_select);
						
						
						
						var prelim_type_common = '';
						
						var prelim_rec_type_tab = '<select class="form-control prelim_rec_type" name="tab_prelim_rec_type" id="tab_prelim_rec_type"><option value=""></option>';
						
						var prelim_rec_type = '<select class="form-control prelim_rec_type" name="prelim_rec_type" id="prelim_rec_type"><option value=""></option>';
						
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'Financial Hardship Plan'){
							prelim_type_common += '<option value="Financial Hardship Plan" selected>Financial Hardship Plan</option>	';	
						}
						else{
							prelim_type_common += '<option value="Financial Hardship Plan">Financial Hardship Plan</option>	';
						}
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'Financial Privacy Plan'){
							prelim_type_common += '<option value="Financial Privacy Plan" selected>Financial Privacy Plan</option>	';	
						}
						else{
							prelim_type_common += '<option value="Financial Privacy Plan">Financial Privacy Plan</option>	';
						}						
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'Income Based Repayment Plan'){
							prelim_type_common += '<option value="Income Based Repayment Plan" selected>Income Based Repayment Plan</option>	';	
						}
						else{
							prelim_type_common += '<option value="Income Based Repayment Plan">Income Based Repayment Plan</option>	';
						}
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'Offer In Compromise'){
							prelim_type_common += '<option value="Offer In Compromise" selected>Offer In Compromise</option>	';	
						}
						else{
							prelim_type_common += '<option value="Offer In Compromise">Offer In Compromise</option>	';
						}
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'TP Only'){
							prelim_type_common += '<option value="TP Only" selected>TP Only</option>	';	
						}
						else{
							prelim_type_common += '<option value="TP Only">TP Only</option>	';
						}
						if(aggregate_array[0]['prelim_recommendation_type_c'] == 'No Rec'){
							prelim_type_common += '<option value="No Rec" selected>No Rec</option>	';	
						}
						else{
							prelim_type_common += '<option value="No Rec">No Rec</option>	';
						}	
						
						prelim_type_common += '</select>';
						prelim_rec_type_tab += prelim_type_common;
						prelim_rec_type += prelim_type_common;
						
						$('#tab_prelim_rec').append(prelim_rec_type_tab);
						$('#prelim_rec').append(prelim_rec_type);
					}
					
					//Summary and general details
					var state = '';
					if(len_summary){												
						
						var add_inc = 0;
						var inc_type_client = '';
						var inc_type_sp = '';
						var cur_emp_client = '';
						var cur_emp_sp = '';
						var len_cur_emp_client = '';
						var len_cur_emp_sp = '';
						var filing_status = '';
						var client_bday = '';
						var sp_bday = '';
						
						if(jsonData.additional_income){
							
							add_inc = jsonData.additional_income;							
						}
						
						var inc_pers_net = 0;
						console.log('len_ai_array - ' + len_ai_array);
						if(len_ai_array > 0){
							
						 console.log(ai_array);
							for(var l=0; l<len_ai_array; l++)
							{
								if(ai_array[l]['income_source_c'] != '' || ai_array[l]['income_source_c'] != null){
									
									if(l == 0)
									{ 
										$('.ais1').text(ai_array[l]['income_source_c']);
										$('.ai_amt1').text(ai_array[l]['amount_c']);
										$('#ai_detail1').text(ai_array[l]['name']);
									}
									if(l == 1)
									{
										$('.ais2').text(ai_array[l]['income_source_c']);
										$('.ai_amt2').text(ai_array[l]['amount_c']);
										$('#ai_detail2').text(ai_array[l]['name']);
									}
									if(l == 2)
									{
										$('.ais3').text(ai_array[l]['income_source_c']);
										$('.ai_amt3').text(ai_array[l]['amount_c']);
										$('#ai_detail3').text(ai_array[l]['name']);
									}
									if(l == 3)
									{
										$('.ais4').text(ai_array[l]['income_source_c']);
										$('.ai_amt4').text(ai_array[l]['amount_c']);
										$('#ai_detail4').text(ai_array[l]['name']);
									}
								}
							}
						}

						if(len_fin_overview > 0){
							
							if(fin_overview_array[0]['primary_tax_payer_before_tax_c'] == '' || fin_overview_array[0]['primary_tax_payer_before_tax_c'] == null){
								
								fin_overview_array[0]['primary_tax_payer_before_tax_c'] = 0;
							}
							
							if(fin_overview_array[0]['spouseco_spouse_before_tax_c'] == '' || fin_overview_array[0]['spouseco_spouse_before_tax_c'] == null){
								
								fin_overview_array[0]['spouseco_spouse_before_tax_c'] = 0;
							}
							
							if(add_inc == '' || add_inc == null){
								
								add_inc = 0;
							}
							
							inc_pers_net = parseFloat(fin_overview_array[0]['primary_tax_payer_before_tax_c']) + parseFloat(fin_overview_array[0]['spouseco_spouse_before_tax_c']) + parseFloat(add_inc) ;
							
							if(fin_overview_array[0]['income_type_c'] != '' && fin_overview_array[0]['income_type_c'] != null){
								
								inc_type_client = fin_overview_array[0]['income_type_c'];
							}
							if(fin_overview_array[0]['spouse_income_type_c'] != '' && fin_overview_array[0]['spouse_income_type_c'] != null){
								
								inc_type_sp = fin_overview_array[0]['spouse_income_type_c'];
							}
							if(fin_overview_array[0]['name_of_current_employer_ind_c'] != '' && fin_overview_array[0]['name_of_current_employer_ind_c'] != null){
								
								cur_emp_client = fin_overview_array[0]['name_of_current_employer_ind_c'];
							}
							if(fin_overview_array[0]['name_of_current_employer_sp_c'] != '' && fin_overview_array[0]['name_of_current_employer_sp_c'] != null){
								
								cur_emp_sp = fin_overview_array[0]['name_of_current_employer_sp_c'];
							}
							if(fin_overview_array[0]['length_of_time_current_emp_c'] != '' && fin_overview_array[0]['length_of_time_current_emp_c'] != null){
								
								len_cur_emp_client = fin_overview_array[0]['length_of_time_current_emp_c'];
							}
							if(fin_overview_array[0]['len_of_time_current_emp_sp_c'] != '' && fin_overview_array[0]['len_of_time_current_emp_sp_c'] != null){
								
								len_cur_emp_sp = fin_overview_array[0]['len_of_time_current_emp_sp_c'];
							}
							/*if(fin_overview_array[0]['total_stated_income_c'] != '' && fin_overview_array[0]['total_stated_income_c'] != null){
                            
								tot_stat_inc_cl = fin_overview_array[0]['total_stated_income_c'];
							}
							if(fin_overview_array[0]['statedincomespouse_c'] != '' && fin_overview_array[0]['statedincomespouse_c'] != null){
								
								sp_tot_stat_inc = fin_overview_array[0]['statedincomespouse_c'];
							}*/
							//$('.tot_stat_inc_cl').text(tot_stat_inc_cl);
							//$('.sp_tot_stat_inc').text(sp_tot_stat_inc);                   
							$('.tot_stat_inc_cl_new').text('$ ' + fin_overview_array[0]['primary_tax_payer_before_tax_c']);
							$('.tot_stat_inc_sp').text('$ ' + fin_overview_array[0]['spouseco_spouse_before_tax_c']);

							$('.income_type_client').text(inc_type_client);
							$('.income_type_sp').text(inc_type_sp);
							$('.cur_emp_client').text(cur_emp_client);
							$('.cur_emp_sp').text(cur_emp_sp);
							$('.len_cur_emp_client').text(len_cur_emp_client);
							$('.len_cur_emp_sp').text(len_cur_emp_sp);
						}						

						if(summary_array[0]['cid_fed_debt_amount_c'] != '' && summary_array[0]['cid_fed_debt_amount_c'] != null){
								
							$('.stat_debt_amnt').text(summary_array[0]['cid_fed_debt_amount_c']);
						}
						
						
						var si_val =  inc_pers_net;
						var se_val = 0;
						var sa_val = 0;						
						main_lead_id = summary_array[0]['id'];
						var old_sugar_id = summary_array[0]['old_sugar_id_c'];

						//console.log('len_extra_cases ' + len_extra_cases);
						if(len_extra_cases){ 

							var accounting_case_number = extra_cases_array[0]['accounting_case_number_c'];
							var monitoring_case_number = extra_cases_array[0]['monitoring_case_number_c'];
							//var var len_extra_cases = extra_cases_array.length;	
							//console.log(accounting_case_number + ', ' + monitoring_case_number);
							if(accounting_case_number > 0){
								
								//console.log(accounting_case_number + ', ' + monitoring_case_number);
								$('#accounting_case_number').val(accounting_case_number);
							} 
							if(monitoring_case_number > 0){
								//console.log(accounting_case_number + ', ' + monitoring_case_number);
								$('#monitoring_case_number').val(monitoring_case_number);
							} 
						}
						
						getDocstarList();

						if(old_sugar_id > 0){
							
							$('#old_case_num').val(old_sugar_id);
						} 
						var marital_statue = '';
						var ass_to = '';
						var lang = '';
						var stat_debt_amnt = '';
						
						for (var i = 0; i < len_summary; i++) {
							
							var client_name = summary_array[i]['FirstName'] + ' ' + summary_array[i]['LastName'] ;
							
							state = summary_array[i]['State'] ;
							marital_statue = summary_array[i]['marital_status_c'] ;
							
							if(summary_array[i]['filing_status_state_c'] != '' && summary_array[i]['filing_status_state_c'] != null){
								
								filing_status = summary_array[i]['filing_status_state_c'] ;
							}
							if(summary_array[i]['birthdate'] != '' && summary_array[i]['birthdate'] != null){
								
								client_bday = summary_array[i]['birthdate'] ;
							}
							if(summary_array[i]['spouse_birthdate_c'] != '' && summary_array[i]['spouse_birthdate_c'] != null){
								
								sp_bday = summary_array[i]['spouse_birthdate_c'] ;
							}
							if(summary_array[i]['assigned_to_new_c'] != '' && summary_array[i]['assigned_to_new_c'] != null){
                            
								ass_to = summary_array[i]['assigned_to_new_c'] ;
							}
							if(summary_array[i]['language_prequal_c'] != '' && summary_array[i]['language_prequal_c'] != null){
								
								lang = summary_array[i]['language_prequal_c'] ;
							}
							if(summary_array[i]['cid_fed_debt_amount_c'] != '' && summary_array[i]['cid_fed_debt_amount_c'] != null){
								
								stat_debt_amnt = summary_array[i]['cid_fed_debt_amount_c'] ;
							}
							
							if(len_reported_income > 0)
							{								
								if(reported_income[0]['rep_inc_spouse']){
									
									var rep_inc = parseInt(reported_income[0]['rep_inc'])    
									              + parseInt(reported_income[0]['rep_inc_spouse']);
								}
								else
									var rep_inc = parseInt(reported_income[0]['rep_inc']) ;
							}
						}	
						//$('.filing_stat').text(filing_status);
						//$('.marital_stat').text(summary_array[i]['marital_status_c']); 
						$('.client_birthday').text(client_bday);
						$('.sp_birthday').text(sp_bday);
						$('.ass_to').text(ass_to);
						$('.lang').text(lang);
						//$('.stat_debt_amnt').text(stat_debt_amnt);

						if(refresh == 0){
							
							var final_rec_common = '';
							var final_rec_type_tab = '<select class="form-control final_rec_type" name="tab_final_rec_type" id="tab_final_rec_type">';
							var final_rec_type = '<select class="form-control final_rec_type" name="final_rec_type" id="final_rec_type">';
						
							var dropdown_array = JSON.parse(dropdowns);
							for (const [key, value] of Object.entries(dropdown_array['prelim_recommendation_type_list'])) {

								console.log(key, value);
								if(summary_array[0]['final_recommendation_type_c'] == value){
									final_rec_common += '<option value="' + key + '" selected>' + value + '</option>	';	
								}
								else{
									final_rec_common += '<option value="' + key + '">' + value + '</option>	';
								}

							}
							//console.log(dropdown_array['prelim_recommendation_type_list']);
							
							/*if(summary_array[0]['final_recommendation_type_c'] == 'Financial Hardship Plan'){
								final_rec_common += '<option value="Financial Hardship Plan" selected>Financial Hardship Plan</option>	';	
							}
							else{
								final_rec_common += '<option value="Financial Hardship Plan">Financial Hardship Plan</option>	';
							}
							if(summary_array[0]['final_recommendation_type_c'] == 'Financial Privacy Plan'){
								final_rec_common += '<option value="Financial Privacy Plan" selected>Financial Privacy Plan</option>	';	
							}
							else{
								final_rec_common += '<option value="Financial Privacy Plan">Financial Privacy Plan</option>	';
							}						
							if(summary_array[0]['final_recommendation_type_c'] == 'Income Based Repayment Plan'){
								final_rec_common += '<option value="Income Based Repayment Plan" selected>Income Based Repayment Plan</option>	';	
							}
							else{
								final_rec_common += '<option value="Income Based Repayment Plan">Income Based Repayment Plan</option>	';
							}
							if(summary_array[0]['final_recommendation_type_c'] == 'Offer In Compromise'){
								final_rec_common += '<option value="Offer In Compromise" selected>Offer In Compromise</option>	';	
							}
							else{
								final_rec_common += '<option value="Offer In Compromise">Offer In Compromise</option>	';
							}
							if(summary_array[0]['final_recommendation_type_c'] == 'TP Only'){
								final_rec_common += '<option value="TP Only" selected>TP Only</option>	';	
							}
							else{
								final_rec_common += '<option value="TP Only">TP Only</option>	';
							}
							if(summary_array[0]['final_recommendation_type_c'] == 'No Rec'){
								final_rec_common += '<option value="No Rec" selected>No Rec</option>	';	
							}
							else{
								final_rec_common += '<option value="No Rec">No Rec</option>	';
							}*/	
							
							final_rec_common += '</select>';
							final_rec_type_tab += final_rec_common;
							final_rec_type += final_rec_common;
						
							$('#tab_final_rec').append(final_rec_type_tab);	
							$('#final_rec').append(final_rec_type);	
						}
						
						//show general details  
						var caseid_text = 'Caseid#:   ' + case_id;
						var name_text = 'Name:   ' + client_name ;
						
						var status_text = '';
						
						if(jsonData.status){
							
							status_text = jsonData.status ;
						}
						
						var html = '<input class="form-control1 casenum" type="text" id="caseno" value="'+ caseid_text + '"  disabled>';
						
						html += '<input class="form-control1 clientname" type="text" id="cname" value="' +  name_text +'"  disabled>';		
						
						if(status_text == 'Strategy Assigned'){
							
							var status_dropdn = '<select class="form-control casestatus" name="case_status" id="case_status" style="background-color: #efefef;">		<option value="0">Status</option>';
						}
						else{
							
							var status_dropdn = '<select class="form-control casestatus" name="case_status" id="case_status">		<option value="0">Status</option>';
						}
						if(status_text == 'Waiting on THS'){
							status_dropdn += '<option value="Waiting on THS" selected>Waiting on THS</option>	';	
						}
						else{
							status_dropdn += '<option value="Waiting on THS">Waiting on THS </option>	';
						}
						if(status_text == 'PPS Assigned'){
							status_dropdn += '<option value="PPS Assigned" selected>PPS Assigned </option>	';	
						}
						else{
							status_dropdn += '<option value="PPS Assigned">PPS Assigned </option>	';
						}
						if(status_text == 'Initial Analysis'){
							status_dropdn += '<option value="Initial Analysis" selected>Initial Analysis </option>	';	
						}
						else{
							status_dropdn += '<option value="Initial Analysis">Initial Analysis </option>	';
						}						
						if(status_text == 'Strategy Assigned'){
							status_dropdn += '<option value="Strategy Assigned" selected>Strategy Assigned</option>	';	
						}
						else{
							status_dropdn += '<option value="Strategy Assigned">Strategy Assigned </option>	';
						}						
						/*if(status_text == 'In Back Office'){
							status_dropdn += '<option value="In Back Office" selected>In Back Office</option>	';	
						}
						else{
							status_dropdn += '<option value="In Back Office">In Back Office</option>	';
						}
						
						if(status_text == 'Returned From Back Office'){
							status_dropdn += '<option value="Returned From Back Office" selected>Returned From Back Office</option>	';	
						}
						else{
							status_dropdn += '<option value="Returned From Back Office">Returned From Back Office</option>	';
						}*/						
						if(status_text == 'Pricing'){
							status_dropdn += '<option value="Pricing" selected>Pricing</option>	';	
						}
						else{
							status_dropdn += '<option value="Pricing">Pricing</option>	';
						} 
						if(status_text == 'Completed'){
							status_dropdn += '<option value="Completed" selected>Completed</option>	';	
						}
						else{
							status_dropdn += '<option value="Completed">Completed</option>	';
						}
						if(status_text == 'Rework'){
							status_dropdn += '<option value="Rework" selected>Rework</option>	';	
						}
						else{
							status_dropdn += '<option value="Rework">Rework</option>	';
						}
						if(status_text == 'Paused'){
							status_dropdn += '<option value="Paused" selected>Paused</option>	';	
						}
						else{
							status_dropdn += '<option value="Paused">Paused</option>	';
						}
						if(status_text == 'Terminated'){
							status_dropdn += '<option value="Terminated" selected>Terminated</option>	';	
						}
						else{
							status_dropdn += '<option value="Terminated">Terminated</option>	';
						}
						if(status_text == 'Sales Verified'){
							status_dropdn += '<option value="Sales Verified" selected>Sales Verified</option>	';	
						}
						else{
							status_dropdn += '<option value="Sales Verified">Sales Verified</option>	';
						}
						if(status_text == 'Transcript Appt'){
							status_dropdn += '<option value="Transcript Appt" selected>Transcript Appt</option>	';	
						}
						else{
							status_dropdn += '<option value="Transcript Appt">Transcript Appt</option>	';
						}
						
						status_dropdn += '</select>';
						
						html += status_dropdn;
						 
						html += '<a class="btn btn-default" href="<?php echo base_url();?>index.php/casereport/records?id='+case_id+'" title="CRReport" id="view_cr">	<span> Open CR URL </span> </a>';
						
						$('#general_detail').append(html);	
											
						if(summary_array[0]['cr_fee_c'] > 0)
						{
							$('.input_cr_fee').val(summary_array[0]['cr_fee_c']);
							$('.tab_input_cr_fee').val(summary_array[0]['cr_fee_c']);
						}
						if(summary_array[0]['add_service_fee_c'] > 0)
						{
							$('.add_service_fee').val(summary_array[0]['add_service_fee_c']);
							$('.tab_add_service_fee').val(summary_array[0]['add_service_fee_c']);
						}
						if(summary_array[0]['cr_rec_c'] != '' && summary_array[0]['cr_rec_c'] !=  null)
						{
							$('.input_cr_recmd').val(summary_array[0]['cr_rec_c']);
							$('.tab_input_cr_recmd').val(summary_array[0]['cr_rec_c']);
						}
						
						if(si_val > 0)
							$(".si").text('$' + si_val.toLocaleString());
						if(se_val > 0){
							$(".se").text('$' + se_val.toLocaleString());							
						}						
						
						if(rep_inc > 0){
							$('.vi').text('$'+rep_inc.toLocaleString());
							$('#ri').text('$'+rep_inc.toLocaleString());
						}						
					}
					
					// If state of lead is not present hide save buttons
					if(state == '' || state == null){
						
						if(refresh == 0){
							
							alert('Please define State of Lead. Stated Interview data is not present in Sugar.');
						}
						$('#save_expenses').hide();
						$('.save_expenses_tabid').hide();
						//$('#tab_save_summary').hide();
						$('#save_summary').hide();
						
					}
					
					if(refresh == 0 && summary_array[0]['ssn_c'] != '' && summary_array[0]['ssn_c'] != null && summary_array[0]['itin_c'] != '' && summary_array[0]['itin_c'] != null){
						
						if(summary_array[0]['spouse_ssn_c'] != '' && summary_array[0]['spouse_ssn_c'] != null && summary_array[0]['spouseitin_c'] != '' && summary_array[0]['spouseitin_c'] != null){
							
							var alert_text = '*This client and spouse have both SSN and ITIN.';
						}				
						else{
							
							var alert_text = '*This client has both SSN and ITIN.';
						}	
						$('.ssn_itin_alert').text(alert_text);
					}
					
					/*if(summary_array[0]['spouse_ssn_c'] != '' && refresh == 0){
						
						swal('In this case, spouse has SSN value - ' + summary_array[0]['spouse_ssn_c'], {
							buttons: {
								cancel: true
							},
							icon: "warning"
						})												
					}
					
					if(summary_array[0]['itin_c'] != '' && refresh == 0){
						
						swal('In this case, client has ITIN value - ' + summary_array[0]['itin_c'], {
							buttons: {
								cancel: true
							},
							icon: "warning"
						})												
					}
					
					if(summary_array[0]['spouseitin_c'] != '' && refresh == 0){
						
						swal('In this case, spouse has ITIN value - ' + summary_array[0]['spouseitin_c'], {
							buttons: {
								cancel: true
							},
							icon: "warning"
						})												
					}*/
					
					
					
					//Account status select option on summary section
					var common = ''; 
					var ac_status = ''; 
					var tab_ac_status = '';
					
					ac_status += '<select class="form-control" name="ac_stat" id="ac_stat">		<option value="">Select</option>';
					
					tab_ac_status += '<select class="form-control" name="tab_ac_stat" id="tab_ac_stat">		<option value="">Select</option>';
					
					if(account_status == 'IA'){
						common += '<option value="IA" selected>IA</option>';	
					}
					else{
						common += '<option value="IA">IA</option>';
					}
					if(account_status == 'Defaulted IA'){
						common += '<option value="Defaulted IA" selected>Defaulted IA</option>';	
					}
					else{
						common += '<option value="Defaulted IA">Defaulted IA</option>';
					}
					if(account_status == 'First Notice'){
						common += '<option value="First Notice" selected>First Notice</option>';	
					}
					else{
						common += '<option value="First Notice">First Notice</option>';
					}
					if(account_status == '2nd NOITL'){
						common += '<option value="2nd NOITL" selected>2nd NOITL</option>';	
					}
					else{
						common += '<option value="2nd NOITL">2nd NOITL</option>';
					}
					if(account_status == '3rd FNOITL'){
						common += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';	
					}
					else{
						common += '<option value="3rd FNOITL">3rd FNOITL</option>';
					}
					if(account_status == '4th NOL'){
						common += '<option value="4th NOL" selected>4th NOL</option>';	
					}
					else{
						common += '<option value="4th NOL">4th NOL</option>';
					}
					if(account_status == 'ACS'){
						common += '<option value="ACS" selected>ACS</option>';	
					}
					else{
						common += '<option value="ACS">ACS</option>';
					}
					if(account_status == '3rd Party'){
						common += '<option value="3rd Party" selected>3rd Party</option>';	
					}
					else{
						common += '<option value="3rd Party">3rd Party</option>';
					}
					if(account_status == 'CNC'){
						common += '<option value="CNC" selected>CNC</option>';	
					}
					else{
						common += '<option value="CNC">CNC</option>';
					}
					if(account_status == 'RO'){
						common += '<option value="RO" selected>RO</option>';	
					}
					else{
						common += '<option value="RO">RO</option>';
					}
					if(account_status == 'Exam'){
						common += '<option value="Exam" selected>Exam</option>';	
					}
					else{
						common += '<option value="Exam">Exam</option>';
					}
					common += '</select>';
					
					ac_status += common;
					tab_ac_status += common;					
					
					$('.ac_stat_div').html(ac_status);
					$('.tab_ac_stat_div').html(tab_ac_status);
					
					
					//compliance summary select option on summary section
					var common = ''; 
					var compliance_div = ''; 
					var tab_compliance_div = '';
					
					compliance_div += '<select class="form-control" name="compliant" id="compliant">		<option value="">Select</option>';
					
					tab_compliance_div += '<select class="form-control" name="tab_compliant" id="tab_compliant">		<option value="">Select</option>';
					
					if(compliance_summary == 'Compliant'){
						common += '<option value="Compliant" selected>Compliant</option>';	
					}
					else{
						common += '<option value="Compliant">Compliant</option>';
					}
					if(compliance_summary == 'Non Compliant'){
						common += '<option value="Non Compliant" selected>Non Compliant</option>	';	
					}
					else{
						common += '<option value="Non Compliant">Non Compliant</option>	';
					}					
					common += '</select>';
					
					compliance_div += common;
					tab_compliance_div += common;					
					
					$('.comp_sum').html(compliance_div);
					$('.tab_comp_sum').html(tab_compliance_div);
					
					//analysis select option on summary section
					// Analysis 1
					var common = ''; 
					var analysis_div = ''; 
					var tab_analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="analysis1" id="analysis1">		<option value="">Select</option>';
					
					tab_analysis_div += '<select class="form-control" name="tab_analysis1" id="tab_analysis1">	<option value="">Select</option>';
					
					if(analysis1 == 'Stated'){
						common += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						common += '<option value="Stated">Stated</option>';
					}
					if(analysis1 == 'Verified'){
						common += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						common += '<option value="Verified">Verified</option>';
					}					
					common += '</select>';
					
					analysis_div += common
					tab_analysis_div += common;									
					
					$('#ana1').html(analysis_div);
					$('#tab_ana1').html(tab_analysis_div);
					
					// Analysis 2
					var common = ''; 
					var analysis_div = ''; 
					var tab_analysis_div = '';
					
					analysis_div += '<select class="form-control" name="analysis2" id="analysis2">		<option value="">Select</option>';
					
					tab_analysis_div += '<select class="form-control" name="tab_analysis2" id="tab_analysis2">		<option value="">Select</option>';
					
					if(analysis2 == 'Stated'){
						common += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						common += '<option value="Stated">Stated</option>';
					}
					if(analysis2 == 'Verified'){
						common += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						common += '<option value="Verified">Verified</option>';
					}					
					common += '</select>';
					
					analysis_div += common;
					tab_analysis_div += common;
										
					$('#ana2').html(analysis_div);
					$('#tab_ana2').html(tab_analysis_div);
					
					
					// Analysis 3
					var common = ''; 
					var analysis_div = ''; 
					var tab_analysis_div = ''; 
					
					analysis_div += '<select class="form-control" name="analysis3" id="analysis3">	<option value="">Select</option>';
					
					tab_analysis_div += '<select class="form-control" name="tab_analysis3" id="tab_analysis3"><option value="">Select</option>';
					
					if(analysis3 == 'Stated'){
						common += '<option value="Stated" selected>Stated</option>';	
					}
					else{
						common += '<option value="Stated">Stated</option>';
					}
					if(analysis3 == 'Verified'){
						common += '<option value="Verified" selected>Verified</option>';	
					}
					else{
						common += '<option value="Verified">Verified</option>';
					}					
					common += '</select>';
					
					analysis_div += common;
					tab_analysis_div += common;
										
					$('#ana3').html(analysis_div);
					$('#tab_ana3').html(tab_analysis_div);
										
					if(len_review_notes){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						var html_service = '';
						var html_service_tab = '';
						var html_assign = '';
						 
						for (var i = 0; i < len_review_notes; i++) {
							
							pnote =  review_notes_array[i]['note_text'] + "&#10;" + moment.utc(review_notes_array[i]['note_datecreated']).local().format('MMMM Do YYYY, h:mm:ss a') + ", " + review_notes_array[i]['note_user'];
							
							if(pnote != '')
							{
								pnote = pnote.replace("&amp;#039;", "'");
							}
							if(review_notes_array[i]['name'] == 'Case Review'){
								
								html += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deletenote" id="d' + review_notes_array[i]['note_id'] + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
								html_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeletenote" id="td' + review_notes_array[i]['note_id'] + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							}
							else if(review_notes_array[i]['name'] == 'Add Service'){
								//alert(review_notes_array[i]['note_id']);
								html_service += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deletenote" id="d' + review_notes_array[i]['note_id'] + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
								html_service_tab += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeletenote" id="td' + review_notes_array[i]['note_id'] + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>'; 
							}
							else if(review_notes_array[i]['name'] == 'Assignment Note'){
						
								//html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="' + pid + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editnote" id="n' + pid + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save savenote" id="s' + pid + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-save deletenote" id="d' + pid + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button><button class="btn btn-xs btn-danger deletenote" id="d' + pid + '" title="Delete" onclick="delete_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
									
								//html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-save tdeletenote" id="td' + review_notes_array[i]['note_id'] + '" title="Delete"  style="display: none; background-color: #48C6AC;" onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>  <button class="btn btn-xs btn-danger tdeletenote" id="td' + review_notes_array[i]['note_id'] + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
								
								html_assign += '<textarea rows="7" cols="10" class="form-control past_notes" id="t' + review_notes_array[i]['note_id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditnote" id="tn' + review_notes_array[i]['note_id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_summary_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsavenote" id="ts' + review_notes_array[i]['note_id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_summary_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeletenote" id="td' + review_notes_array[i]['note_id'] + '" title="Delete"  onclick="delete_tab_summary_note(this.id);"><i class="glyph-icon icon-remove"></i></button>'; 

								document.getElementById("tabassignmentnote").insertAdjacentHTML('afterbegin', html_assign);
							}
						}
						
						$('#example').html('');
						$('#tabsumnote').html('');
						$('#servicenote').html('');
						$('#tabservicenote').html('');
						$('#tabassignmentnote').html('');
						
						$('#example').append(html);
						$('#tabsumnote').append(html_tab);
						
						if(html_service_tab != '')
						{
							$('#tabservicenote').append(html_service_tab);
							$('#servicenote').append(html_service);
						}
						if(html_assign != '')
						{
							$('#tabassignmentnote').append(html_assign);
							//$('#servicenote').append(html_service);
						}
						//document.getElementById("note_msg").insertAdjacentHTML('afterbegin', pnote);
						//document.getElementById("note_msg2").insertAdjacentHTML('afterbegin', pnote);
						
					}
					
					//Expense notes len_notes_si = notes_si_array
					
					if(len_notes_si){
						
						var pnote_personal1 = '';
						var pnote_income1 = '';
						
						for (var i = 0; i < len_notes_si; i++) {
							
							if(notes_si_array[i]['note_type_c'] == 'Stated Interview - Personal'){
								
								pnote_personal1 +=  notes_si_array[i]['note_text'] + '\n';
							}
							
							if(notes_si_array[i]['note_type_c'] == 'Stated Interview - Income'){
								pnote_income1 +=  notes_si_array[i]['note_text'] + '\n';
							}
						}
						
						$('#tab_si_personal').val('');
						$('#tab_si_income').val('');
						$('#si_personal').val('');
						$('#si_income').val('');
						
						$('#tab_si_personal').val(pnote_personal1);
						$('#tab_si_income').val(pnote_income1);
						$('#si_personal').val(pnote_personal1);
						$('#si_income').val(pnote_income1);
					}
					
					// DISPLAY EXPENSE DATA	
					
					if(len_fin_overview > 0){
						
						var punder65 = fin_overview_array[0]['adults_under_65'];
						var pover65 = fin_overview_array[0]['adults_over_65'];
						
						var num_vehicle = len_vehicles; 
						var total_auto_expense = 0;
						var auto_ownership1 = 0;
						var auto_ownership2 = 0;
						var auto_ownership1_id = '';
						var auto_ownership2_id = '';
						
						if(len_vehicles){
							
							for(var i=0; i<len_vehicles; i++){
								total_auto_expense = parseFloat(vehicles_array[i]['monthly_payment']);
								
								if(i == 0){
									
									if(vehicles_array[i]['monthly_payment'] > 0){
										
										auto_ownership1 = parseFloat(vehicles_array[i]['monthly_payment']);
									}
									auto_ownership1_id = vehicles_array[i]['id'];
								}
								if(i == 1){
									
									if(vehicles_array[i]['monthly_payment'] > 0){
										
										auto_ownership2 = parseFloat(vehicles_array[i]['monthly_payment']);
									}
									auto_ownership2_id = vehicles_array[i]['id'];
								}
							}
						}
							
						
						total_auto_expense = parseFloat(total_auto_expense) + parseFloat(sugar_irs_standards_array['auto_operating']);
						
						var total_persons = parseInt(punder65) + parseInt(pover65);
						
						var total_food_clothing = parseFloat(sugar_irs_standards_array['fcm']);

						
						var tot_expense = 0;
						
						//var tot_expense = parseFloat(total_food_clothing);						
						//alert('total_food_clothing' + tot_expense);					
						var extra_rent=0;						
						 
						if(len_real_estate){
							
							for(var i=0; i<len_real_estate; i++){
								
								if(real_estate_array[i]['monthly_payment'] > 0){
									
									extra_rent += parseFloat(real_estate_array[i]['monthly_payment']);
								}
							}								
						}
						//alert(fin_overview_array[0]['rent_payment']);
						if(fin_overview_array[0]['rent_payment'] > 0){
							
							var stated_house_exp = parseFloat(fin_overview_array[0]['rent_payment'])
												+ parseFloat(fin_overview_array[0]['utilities_payment_c'])
												+ parseFloat(fin_overview_array[0]['telephone_payment_c']);
												
							//tot_expense += parseFloat(stated_house_exp);
						} 
						else {
							
							var stated_house_exp = extra_rent
												+ parseFloat(fin_overview_array[0]['utilities_payment_c'])
												+ parseFloat(fin_overview_array[0]['telephone_payment_c']);
												
							//tot_expense += parseFloat(stated_house_exp);
						}												 
						//alert(tot_expense);
						if(sugar_irs_standards_array['housing'] < stated_house_exp){
							
							var allowable_house_exp = sugar_irs_standards_array['housing']
						}
						else {
							var allowable_house_exp = stated_house_exp
						}
						tot_expense += parseFloat(allowable_house_exp);
						
						//health
						
						var prescp_copays = fin_overview_array[0]['prescriptions_copays'];
						var total_health_care = Math.floor(fin_overview_array[0]['health_insurance']);
						
						tot_expense += parseFloat(fin_overview_array[0]['healthcare_total']);						
						

						//Dependents var len_dependents = dependents_array.length;	
						var html_dependent = '';						

						if(len_dependents){

							html_dependent += '<h5 style="text-align: center;"><b>Dependents</b></h5>';
							html_dependent += '<h5> <b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; DOB     &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Relationship</b></h5>';
							
							for(var i=0; i<len_dependents; i++){

								if(dependents_array[i]['birth_date_c'] == '' || dependents_array[i]['birth_date_c'] == null){

									dependents_array[i]['birth_date_c'] = '';
								}
								if(dependents_array[i]['relationship'] == '' || dependents_array[i]['relationship'] == null){

									dependents_array[i]['relationship'] = '';
								}
								html_dependent += '<h5><div class="col-md-5">' + dependents_array[i]['name'] + '</div>';

								html_dependent += '<div class="col-md-3" style="text-align: center;"> ' + dependents_array[i]['birth_date_c'] + ' </div>';
								html_dependent += '<div class="col-md-4" style="text-align: center;">' + dependents_array[i]['relationship'] + ' </div>';
								html_dependent += '</h5>';
							}
						}

						$(".state").text(state);						
						
						{
							if(state != ''){
								
								var html_gen = '<h5><div class="col-md-8">Persons in Household (Under 65):</div><div class="col-md-4"> 	<input class="form-control tab_expense_input" type="text" name="tab_under65" id="tab_under65" value=""> </div>	</h5>';
								html_gen += '<h5><div class="col-md-8">Persons in Household (Over 65):</div> <div class="col-md-4"> <input class="form-control tab_expense_input" type="text" name="tab_over65" id="tab_over65" value=""></div> </h5>';
								html_gen += '<h5><div class="col-md-8">Filing Status:</div> <div class="col-md-4"> <p class="filing_stat" style="text-align: center;"></p>	</div> </h5>';
								html_gen += '<h5><div class="col-md-8">Marital Status:</div> <div class="col-md-4"> <p class="marital_stat" style="text-align: center;"></p>	</div> </h5>';

								var html_gen_acc = '<h5><div class="col-md-8">Persons in Household (Under 65):</div><div class="col-md-4"> 	<input class="form-control expense_input" type="text" name="under65" id="under65" value=""> </div>	</h5>';
								html_gen_acc += '<h5><div class="col-md-8">Persons in Household (Over 65):</div> <div class="col-md-4"> <input class="form-control expense_input" type="text" name="over65" id="over65" value=""></div> </h5>';
								html_gen_acc += '<h5><div class="col-md-8">Filing Status:</div> <div class="col-md-4"> <p class="filing_stat" style="text-align: center;"></p>	</div> </h5>';
								html_gen_acc += '<h5><div class="col-md-8">Marital Status:</div> <div class="col-md-4"> <p class="marital_stat" style="text-align: center;"></p>	</div> </h5>';
								
								if(html_dependent != ''){

									html_gen += html_dependent;
									html_gen_acc += html_dependent;
								}
								$("#general_exp_info").html(html_gen); 
								$("#general_exp_info_acc").html(html_gen_acc);

								$('.filing_stat').text(filing_status);
								$('.marital_stat').text(marital_statue); 

								$("#prescp_copay").val(Math.floor(fin_overview_array[0]['prescriptions_copays']));
								
								
								
								//$("#food_cloth_misc").val(sugar_irs_standards_array['fcm']);
								if(sugar_irs_standards_array['fcm'] > 0){
									$(".food_cloth_misc").text('$' + Math.floor(sugar_irs_standards_array['fcm']));
								}
								$("#rent_pay").val(Math.floor(fin_overview_array[0]['rent_payment']));
								$("#utilities_input").val(Math.floor(fin_overview_array[0]['utilities_payment_c']));
								$("#pic_pay").val(Math.floor(fin_overview_array[0]['telephone_payment_c']));
								
								$("#mortgage_payment").val(Math.floor(extra_rent));							
								$("#health_ins").val(Math.floor(fin_overview_array[0]['health_insurance'])); 						
								
								$("#total_health").val(Math.floor(fin_overview_array[0]['healthcare_total']));							
								
								$("#under65").val(punder65);
								$("#over65").val(pover65);
								
								if(allowable_house_exp > 0)
									$("#housing_exp").val(allowable_house_exp); 
								
								$("#stated_housing_exp").val(Math.floor(stated_house_exp));
								$("#irs_stand").val(sugar_irs_standards_array['housing']); 
								//$("#tot_auto_exp").val(Math.floor(exp_auto)); 
								$("#public_trans").val(0); 
								$("#num_vehicle").val(num_vehicle); 
								$("#pt_client").val(Math.floor(fin_overview_array[0]['public_transportation_client_c']));
								$("#pt_spouse").val(Math.floor(fin_overview_array[0]['public_transportation_spouse_c']));
								$(".public_trans_irs").text(sugar_irs_standards_array['public_transportation']);
								
								//$('#other_vehicle_exp1').addClass(auto_ownership1_id);
								$('#other_vehicle_exp1').attr('data-vehicle_id', auto_ownership1_id); 
								$('#other_vehicle_exp2').attr('data-vehicle_id', auto_ownership2_id);    
								//$('#other_vehicle_exp2').addClass(auto_ownership2_id);  
								
								//alert(sugar_irs_standards_array['public_transportation']);
								var exp_auto = 0;
								var auto_irs = 0;
								var vehicle_exp = 0;
								var vehicle_exp1 = 0;
								var vehicle_exp2 = 0;
								var vehicle2 = 0;
								
								if(len_vehicles){
									
									for(var i=0; i<len_vehicles; i++){
										
										if(i > 1)
											break;
										
										if(vehicles_array[i]['monthly_payment'] > sugar_irs_standards_array['ownership']){
											
											vehicle_exp += parseFloat(sugar_irs_standards_array['ownership']);
											//exp_auto += parseFloat(sugar_irs_standards_array['ownership']);
										}
										else{
											
											vehicle_exp += parseFloat(vehicles_array[i]['monthly_payment']);
											//exp_auto += parseFloat(vehicles_array[i]['monthly_payment']);
										}
										if(i == 0){
											
											vehicle_exp1 = parseFloat(vehicles_array[i]['monthly_payment']);
										}
										if(i == 1){
											
											vehicle2 = 1;
											vehicle_exp2 = parseFloat(vehicles_array[i]['monthly_payment']);
										}
																				
										auto_irs += parseFloat(sugar_irs_standards_array['ownership']);
										//alert(auto_irs);
									}
								}
								else{ 
									
									$('#tab_vehicle1div').hide();
									$('#vehicle1div').hide();
								}
								if(vehicle_exp1 > sugar_irs_standards_array['ownership']){
									
									vehicle_exp1 = parseFloat(sugar_irs_standards_array['ownership']);
								}
								//$("#other_vehicle_exp1").val(vehicle_exp1);
								
								if(vehicle2 == 1){
									
									if(vehicle_exp2 > sugar_irs_standards_array['ownership']){
									
										vehicle_exp2 = parseFloat(sugar_irs_standards_array['ownership']);
									}
									//$("#other_vehicle_exp2").val(vehicle_exp2);
								}
								else{
									$('#tab_vehicle2div').hide();
									$('#vehicle2div').hide();
								}
								
								$('#other_vehicle_exp1').val(auto_ownership1);
								$('#other_vehicle_exp2').val(auto_ownership2);
								
								//alert(sugar_irs_standards_array['auto_operating']);		
								//exp_auto += Math.floor(fin_overview_array[0]['public_transportation_client_c']) + Math.floor(fin_overview_array[0]['public_transportation_spouse_c']) + Math.floor(sugar_irs_standards_array['auto_operating']) + Math.floor(sugar_irs_standards_array['public_transportation']);
								
								var tot_public_trans = Math.floor(fin_overview_array[0]['public_transportation_client_c']) + Math.floor(fin_overview_array[0]['public_transportation_spouse_c']);
								
								if(tot_public_trans > sugar_irs_standards_array['public_transportation']){
									
									tot_public_trans = Math.floor(sugar_irs_standards_array['public_transportation'])
								}
								//alert('tot_public_trans - ' + tot_public_trans + ', vehicle_exp1 - '+vehicle_exp1 + ', vehicle_exp2 - ' +vehicle_exp2)
								if(vehicle_exp1 > 0 || vehicle_exp2 > 0){
									
									exp_auto += vehicle_exp1 + vehicle_exp2 + parseFloat(sugar_irs_standards_array['auto_operating']);
								}
								else{
									
									exp_auto += tot_public_trans + parseFloat(sugar_irs_standards_array['auto_operating']);
								}
								//exp_auto += parseFloat(sugar_irs_standards_array['auto_operating']);
								auto_irs += parseFloat(sugar_irs_standards_array['auto_operating']) + parseFloat(sugar_irs_standards_array['public_transportation']) ;
								
								tot_expense += Math.floor(parseFloat(exp_auto));
								
								
								if(parseFloat(sugar_irs_standards_array['public_transportation']) > 0)
								{
									$(".irs_public_trans").text(parseFloat(sugar_irs_standards_array['public_transportation']));
								}	
								if(parseFloat(sugar_irs_standards_array['ownership']) > 0)
								{
									$(".auto_own_irs").text(parseFloat(sugar_irs_standards_array['ownership']));
								}
								if(parseFloat(sugar_irs_standards_array['auto_operating']) > 0)
								{
									$(".auto_ei_irs").text(parseFloat(sugar_irs_standards_array['auto_operating']));
									$("#auto_exp_ins").val(parseFloat(sugar_irs_standards_array['auto_operating']));
									$("#tab_auto_exp_ins").val(parseFloat(sugar_irs_standards_array['auto_operating']));
								}
								var health_irs = parseFloat(sugar_irs_standards_array['under_65']) + parseFloat(sugar_irs_standards_array['over_65']);
								
								if(health_irs > 0)
									$(".prescp_copay_irs").text(health_irs);
								
								$("#tot_auto_exp").val(Math.floor(exp_auto));
								
								//for tab								
								
								$("#tab_prescp_copay").val(Math.floor(fin_overview_array[0]['prescriptions_copays']));							
								
								$("#tab_rent_pay").val(Math.floor(fin_overview_array[0]['rent_payment']));
								$("#tab_utilities_input").val(Math.floor(fin_overview_array[0]['utilities_payment_c']));
								$("#tab_pic_pay").val(Math.floor(fin_overview_array[0]['telephone_payment_c']));
								
								$("#tab_mortgage_payment").val(Math.floor(extra_rent));							
								$("#tab_health_ins").val(Math.floor(fin_overview_array[0]['health_insurance'])); 
								
								$("#tab_total_health").val(Math.floor(fin_overview_array[0]['healthcare_total']));						
								 
								$("#tab_under65").val(punder65);
								$("#tab_over65").val(pover65);
							
								if(allowable_house_exp > 0)
									$("#tab_housing_exp").val(Math.floor(allowable_house_exp)); 
								
								$("#tab_stated_housing_exp").val(Math.floor(stated_house_exp)); 
							
								$(".tab_irs_stand").text(sugar_irs_standards_array['housing']); 
								
								//if(total_auto_expense > 0)
								$("#tab_tot_auto_exp").val(Math.floor(exp_auto)); 
								
								$("#tab_num_vehicle").val(num_vehicle);
								$("#tab_pt_client").val(Math.floor(fin_overview_array[0]['public_transportation_client_c']));
								$("#tab_pt_spouse").val(Math.floor(fin_overview_array[0]['public_transportation_spouse_c']));
								
								//console.log('auto_ownership1_id - '+auto_ownership1_id);
								//$('#tab_other_vehicle_exp1').addClass(auto_ownership1_id);
								$('#tab_other_vehicle_exp1').attr('data-vehicle_id', auto_ownership1_id); 
								$('#tab_other_vehicle_exp2').attr('data-vehicle_id', auto_ownership2_id);   
								//$('#tab_other_vehicle_exp2').addClass(auto_ownership2_id); 
								
								$('#tab_other_vehicle_exp1').val(auto_ownership1);
								$('#tab_other_vehicle_exp2').val(auto_ownership2);	
								/*if(vehicle2 == 1){
									
									$("#tab_other_vehicle_exp2").val(vehicle_exp2); 
								}*/
								$("#tab_public_trans").val(0);
							}
						}
						
						tot_expense += parseFloat(total_food_clothing);
						
						var html1 = '<h5 style="text-align: center;"><b>Taxes & Other</b> <button type="button" id="add_other_taxes" class="btn btn-primary" onclick="add_taxes();"> Add </button></h5>';
							
						var html_tab = '<h5 style="text-align: center;"><b>Taxes & Other</b> <button type="button" id="tab_add_other_taxes" class="btn btn-primary" onclick="tab_add_taxes();"> Add </button></h5>';
						
						if(len_additional_expense && state != ''){
							//alert(additional_expense_array[i]['name']);
							var i=0;
							//var html1 = '<h5 style="text-align: center;"><b>Taxes & Other</b></h5>';
							//var html_tab = '<h5 style="text-align: center;"><b>Taxes & Other</b></h5>';
							
							for(i=0; i<additional_expense_array.length; i++){
								
								if(additional_expense_array[i]['amount_actual_c'] > 0){
									
									tot_expense += parseFloat(additional_expense_array[i]['amount_actual_c']);
								}
								if(additional_expense_array[i]['amount_actual_c'] == '' || additional_expense_array[i]['amount_actual_c'] == null){
									
									additional_expense_array[i]['amount_actual_c'] = 0;
								}
								
								//html1 += '<h5><div class="col-md-8">' + additional_expense_array[i]['name'] + '</div><div class="col-md-4">	<input class="form-control oth_expense_input" type="text" name="other_add_exp' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '" value="' + additional_expense_array[i]['amount_actual_c'] + '"> </div></h5> ';
								
								//html_tab += '<h5><div class="col-md-8"> <input class="form-control oth_expense_input_tab_text" type="text" name="other_add_exp_text' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_tab" value="' + additional_expense_array[i]['name'] + '"> ' +  '</div><div class="col-md-4">	<input class="form-control oth_expense_input_tab_val" type="text" name="other_add_exp' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_tabval" value="' + additional_expense_array[i]['amount_actual_c'] + '"> </div></h5> ';
								
								//html1 += '<h5 id="h5' + additional_expense_array[i]['add_expense_id'] + '"><div class="col-md-8">' + additional_expense_array[i]['name'] + '</div><div class="col-md-4">	<input class="form-control oth_expense_input" type="text" name="other_add_exp' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '" value="' + additional_expense_array[i]['amount_actual_c'] + '"> <button type="button" class="btn btn-xs btn-danger" id="delete_extra_exp" title="Delete" style="display: inline;" onclick="delete_extra_expense(this);" data-button=\'{"id": "' + additional_expense_array[i]['add_expense_id'] + '"}\'><i class="glyph-icon icon-remove"></i></button> </div></h5> ';
								
								html1 += '<h5 id="h5' + additional_expense_array[i]['add_expense_id'] + '"><div class="col-md-8"><input class="form-control oth_expense_input_text" type="text" name="other_add_exp_text' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_acc" value="' + additional_expense_array[i]['name'] + '"> ' + '</div><div class="col-md-4">	<input class="form-control oth_expense_input_val" type="text" name="other_add_exp' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_accval" value="' + additional_expense_array[i]['amount_actual_c'] + '"> <button type="button" class="btn btn-xs btn-danger" id="delete_extra_exp" title="Delete" style="display: inline;" onclick="delete_extra_expense(this);" data-button=\'{"id": "' + additional_expense_array[i]['add_expense_id'] + '"}\'><i class="glyph-icon icon-remove"></i></button> </div></h5> ';
								
								//var az = escapeHtml(additional_expense_array[i]['name']);
								//jQuery.parseHTML(additional_expense_array[i]['name']);
								//alert(additional_expense_array[i]['name']);
								//alert(additional_expense_array[i]['name']);
								html_tab += '<h5 id="th5' + additional_expense_array[i]['add_expense_id'] + '"><div class="col-md-8"> <input class="form-control oth_expense_input_tab_text" type="text" name="other_add_exp_text' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_tab" value="' + additional_expense_array[i]['name'] + '"> ' +  '</div><div class="col-md-4">	<input class="form-control oth_expense_input_tab_val" type="text" name="other_add_exp' + i + '" id="' + additional_expense_array[i]['add_expense_id'] + '_tabval" value="' + additional_expense_array[i]['amount_actual_c'] + '"> <button type="button" class="btn btn-xs btn-danger" id="tab_delete_extra_exp" title="Delete" onclick="tab_delete_extra_expense(this);" data-button=\'{"id": "' + additional_expense_array[i]['add_expense_id'] + '"}\'><i class="glyph-icon icon-remove"></i></button> </div></h5> ';
							}
							
							if(tot_expense > 0){
								
								$('.tot_expensenew').html('$' + tot_expense);
								
								//html1 += '<h5 style="text-align: center;"><b>Total Expenses</b></h5> <h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew"> ' + tot_expense + '</div></h5>';
							
								//html_tab += '<h5 style="text-align: center;"><b>Total Expenses</b></h5> <h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew" > ' +  tot_expense + '</div></h5>';
							}
							else{
								
								//html1 += '<h5 style="text-align: center;"><b>Total Expenses</b></h5> <h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew"> </div></h5>';
							
								//html_tab += '<h5 style="text-align: center;"><b>Total Expenses</b></h5> <h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew" > </div></h5>';
							}
							//alert(tot_expense);
							$('#other_add_expense').html(html1);
							$('#tab_other_add_expense').html(html_tab);
							
							if(tot_expense > 0){
								$('.se').html('$' + tot_expense.toLocaleString());
								$('.re').html('$' + tot_expense.toLocaleString());
							}
							else{
								$('.se').html(tot_expense.toLocaleString());
								$('.re').html(tot_expense.toLocaleString());
							}
						}
						else{
							
							//var html1 = '<h5 style="text-align: center;"><b>Total Expenses</b></h5>';
							//var html_tab = '<h5 style="text-align: center;"><b>Total Expenses</b></h5>';
							
							if(tot_expense > 0){
								
								$('.tot_expensenew').html('$' + tot_expense);
								//html1 += '<h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew" > ' + tot_expense + '</div></h5>';
								
								//html_tab += '<h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew"> ' +  tot_expense + '</div></h5>';
							}
							else{
								
								//html1 += '<h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew" > </div></h5>';
								
								//html_tab += '<h5><div class="col-md-8">Total Expenses:</div><div class="col-md-4 tot_expensenew"> </div></h5>';
							}
							
							$('#other_add_expense').html(html1);
							$('#tab_other_add_expense').html(html_tab);
							
							if(tot_expense > 0){
								$('.se').html('$' + tot_expense);
								$('.re').html('$' + tot_expense);
							}
							else{
								$('.se').html(tot_expense);
								$('.re').html(tot_expense);
							}
						}
					}
					
					//DISPLAY ASSETS DATA
						
						var tbody = '';
						var calculated_val = 0;
						var cash_on_hand = 0;
						var total_asset = 0;
						
						if(jsonData.asset_bank_acc){
							var bank_acc_val = jsonData.asset_bank_acc ;
							//alert(bank_acc_val);
							total_asset += parseFloat(bank_acc_val);
						}
						else{
							var bank_acc_val = 0;
						}
						if(len_fin_overview){							
							
							if(fin_overview_array[0]['cash_on_hand_c']){
								
								cash_on_hand = fin_overview_array[0]['cash_on_hand_c']; //jsonData.cash_on_hand;
							}
						}
						if(cash_on_hand > 0){
							cash_on_hand = 0;
						}
						tbody +=  "<tr> <td class='td col1'>Bank Accounts</td>"
									  + "<td class='td col2'>" + '$' + bank_acc_val + "</td>"
									  + "<td class='td col1'></td>"
									  + "<td class='td col1'></td>"
									  + "<td class='td col2'>" + '$' + bank_acc_val + "</td>"
									  + "</tr>";
						
						/*tbody +=  "<tr>" 
							          + "<td class='td col1'>CashOnHand</td>"
									  + "<td class='td col2'>" + '$' + cash_on_hand + "</td>"
									  + "<td class='td col1'></td>"
									  + "<td class='td col1'></td>"
									  + "<td class='td col2'>" + '$' + cash_on_hand + "</td>"
									   + "</tr>";*/
						
						var vehicle1_mv = 0;
						var vehicle1_lb = 0;
						var vehicle1_eq = 0;
						var vehicle2_mv = 0;
						var vehicle2_lb = 0;
						var vehicle2_eq = 0;
						
						var vehicle3_mv = 0;
						var vehicle3_lb = 0;
						var vehicle3_eq = 0;
						var vehicle4_mv = 0;
						var vehicle4_lb = 0;
						var vehicle4_eq = 0;
						
						if(len_vehicles){
							
							//for(var i=0; i<4; i++){
								
								//var j = i+1;
								//var varname_mv = 'vehicle' + j + '_mv';
								//var vehicle1_mv = vehicles_array[0]['market_value_c']
								//var vehicle1_lb = vehicles_array[0]['balance']
								
								if(len_vehicles){
									if(vehicles_array[0]['market_value_c'] != ''){
										
										var fmv80 = parseFloat(vehicles_array[0]['market_value_c']) * 0.8;
										
										if(vehicles_array[0]['balance'] == '' || vehicles_array[0]['balance'] == null){
									
											vehicles_array[0]['balance'] = 0;
										}
									
										//if(vehicles_array[0]['balance'] != '' && vehicles_array[0]['balance'] != null){
											
										if(fmv80 > 0){
											
											var vehicle1_eq = fmv80 - parseFloat(vehicles_array[0]['balance']);
											if(vehicle1_eq < 0)
												vehicle1_eq = 0;
											else{
												total_asset += parseFloat(vehicle1_eq);
											}
										}
									}
								}
								if(len_vehicles > 1){
									if(vehicles_array[1]['market_value_c'] != ''){
										
										var fmv80 = parseFloat(vehicles_array[1]['market_value_c']) * 0.8;
										
										if(vehicles_array[1]['balance'] == '' || vehicles_array[1]['balance'] == null){
									
											vehicles_array[1]['balance'] = 0;
										}
										
										//if(vehicles_array[1]['balance'] != '' && vehicles_array[1]['balance'] != null){
											
										if(fmv80 > 0){
											
											var vehicle2_eq = fmv80 - parseFloat(vehicles_array[1]['balance']);
											if(vehicle2_eq < 0)
												vehicle2_eq = 0;
											else{
												total_asset += parseFloat(vehicle2_eq);
											}
										}
									}
								}
								//alert(vehicles_array[2]['market_value_c']);
								if(len_vehicles > 2){
									if(vehicles_array[2]['market_value_c'] != ''){
										
										var fmv80 = parseFloat(vehicles_array[2]['market_value_c']) * 0.8;
										
										if(vehicles_array[2]['balance'] == '' || vehicles_array[2]['balance'] == null){
									
											vehicles_array[2]['balance'] = 0;
										}
										
										//if(vehicles_array[2]['balance'] != '' && vehicles_array[2]['balance'] != null){
											
										if(fmv80 > 0){
											
											var vehicle3_eq = fmv80 - parseFloat(vehicles_array[2]['balance']);
											if(vehicle3_eq < 0)
												vehicle3_eq = 0;
											else{
												total_asset += parseFloat(vehicle3_eq);
											}
										}
									}
								}
								if(len_vehicles > 3){
									if(vehicles_array[3]['market_value_c'] != ''){
										
										var fmv80 = parseFloat(vehicles_array[3]['market_value_c']) * 0.8;
										
										if(vehicles_array[3]['balance'] == '' || vehicles_array[3]['balance'] == null){
									
											vehicles_array[3]['balance'] = 0;
										}
										
										//if(vehicles_array[3]['balance'] != '' && vehicles_array[3]['balance'] != null){
											
										if(fmv80 > 0){
											
											var vehicle4_eq = fmv80 - parseFloat(vehicles_array[3]['balance']);
											if(vehicle4_eq < 0)
												vehicle4_eq = 0;
											else{
												total_asset += parseFloat(vehicle4_eq);
											}
										}
									}
								}
							//}
							//alert(vehicles_array[0]['market_value_c'] + ', ' + vehicles_array[0]['balance']);
							
							if(vehicles_array[0]['market_value_c'] != null){
								
								var vehicle1_mv = vehicles_array[0]['market_value_c'];
							}
							else{
								
								var vehicle1_mv = 0;
							}
							if(vehicles_array[0]['balance'] != null){
								
								var vehicle1_lb = vehicles_array[0]['balance'];
							}
							else{
								
								var vehicle1_lb = 0;
							}
														
							if(len_vehicles > 1){
								
								if(vehicles_array[1]['market_value_c'] != null){
								
									var vehicle2_mv = vehicles_array[1]['market_value_c'];
								}
								else{
									
									var vehicle2_mv = 0;
								}
								if(vehicles_array[1]['balance'] != null){
									
									var vehicle2_lb = vehicles_array[1]['balance'];
								}
								else{
									
									var vehicle2_lb = 0;
								}
							}
							
							if(len_vehicles > 2){
								
								if(vehicles_array[2]['market_value_c'] != null){
								
									var vehicle3_mv = vehicles_array[2]['market_value_c'];
								}
								else{
									
									var vehicle3_mv = 0;
								}
								if(vehicles_array[2]['balance'] != null){
									
									var vehicle3_lb = vehicles_array[2]['balance'];
								}
								else{
									
									var vehicle3_lb = 0;
								}
							}
							
							if(len_vehicles > 3){
								
								if(vehicles_array[3]['market_value_c'] != null){
								
									var vehicle4_mv = vehicles_array[3]['market_value_c'];
								}
								else{
									
									var vehicle4_mv = 0;
								}
								if(vehicles_array[3]['balance'] != null){
									
									var vehicle4_lb = vehicles_array[3]['balance'];
								}
								else{
									
									var vehicle4_lb = 0;
								}
							}
							//console.log(vehicle1_mv + ', ' + vehicle1_lb + ', ' + vehicle1_eq);
							//console.log(vehicle2_mv + ', ' + vehicle2_lb + ', ' + vehicle2_eq);
						}
						var real_estate_mv = 0;
						var real_estate_lb = 0;
						var real_estate_eq = 0;
						var tbody_extra_re = '';
						
						if(len_real_estate){
							
							for(var i=0; i<len_real_estate; i++){
								
								//eal_estate_mv += parseFloat(real_estate_array[i]['fmv_c']);
								//real_estate_lb += parseFloat(real_estate_array[i]['balance']);
								
								//real_estate_eq = parseFloat(real_estate_mv) - parseFloat(real_estate_lb);
								
								//alert(real_estate_array[i]['fmv_c'] + ', ' + real_estate_array[i]['balance']  ); 
								var j= i+1;
								if(real_estate_array[i]['fmv_c'] != ''){
									
									var fmv80 = parseFloat(real_estate_array[i]['fmv_c']) * 0.8;
									//alert(fmv80);
									
									if(real_estate_array[i]['balance'] == '' || real_estate_array[i]['balance'] == null){
									
										real_estate_array[i]['balance'] = 0;
									}
									//alert(real_estate_array[i]['balance'])
									//if(real_estate_array[i]['balance'] != '' && real_estate_array[i]['balance'] != null){
										
									if(fmv80 > 0){
										
										var real_estate_eq = Math.floor(fmv80 - Math.floor(real_estate_array[i]['balance']));
										if(real_estate_eq < 0)
											real_estate_eq = 0;
										else{
											total_asset += parseFloat(real_estate_eq);
										}
									}
								}
								
								
								tbody_extra_re +=  "<tr> <td class='td col1'>Real Estate " + j + "</td>"
									  + "<td class='td col2'>" + '$' + parseFloat(real_estate_array[i]['fmv_c']) + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + parseFloat(real_estate_array[i]['balance']) + "</td>"
									  + "<td class='td col2'>" + real_estate_eq + "</td>"
									  + "</tr>";
							}
						}
						//console.log( tbody_extra_re);
						
						var other_assts_fmv = 0;
						var other_assts_lb = 0;
						var other_assts_eq = 0;	
						var tbody_extra = '';
						var tbody_ve = '';
						
						tbody_ve +=  tbody;
						tbody_ve +=  tbody_extra_re;
						
						var tbody = '';
						tbody_ve +=  "<tr> <td class='td col1'>Vehicle 1</td>"
									  + "<td class='td col2'>" + '$' + vehicle1_mv + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + vehicle1_lb + "</td>"
									  + "<td class='td col2'>" + '$' + vehicle1_eq + "</td>"
									  + "</tr>";
									  
						tbody_ve +=  "<tr> <td class='td col1'>Vehicle 2</td>"
								  + "<td class='td col2'>" + '$' + vehicle2_mv + "</td>"
								  + "<td class='td col2'>80%</td>"
								  + "<td class='td col2'>"+ '$'  + vehicle2_lb + "</td>"
								  + "<td class='td col2'>" + '$' + vehicle2_eq + "</td>"
								  + "</tr>";
								  
						tbody_ve +=  "<tr> <td class='td col1'>Vehicle 3</td>"
								  + "<td class='td col2'>" + '$' + vehicle3_mv + "</td>"
								  + "<td class='td col2'>80%</td>"
								  + "<td class='td col2'>"+ '$'  + vehicle3_lb + "</td>"
								  + "<td class='td col2'>" + '$' + vehicle3_eq + "</td>"
								  + "</tr>";
								  
						tbody_ve +=  "<tr> <td class='td col1'>Vehicle 4</td>"
								  + "<td class='td col2'>" + '$' + vehicle4_mv + "</td>"
								  + "<td class='td col2'>80%</td>"
								  + "<td class='td col2'>"+ '$'  + vehicle4_lb + "</td>"
								  + "<td class='td col2'>" + '$' + vehicle4_eq + "</td>"
								  + "</tr>";
								  
										
						
						//alert('state '+ state);
						if(state != ''){
							$('.assets_tab').html(tbody_ve);
						}
					
					if(len_assets){
						
						for (var i = 0; i < len_assets; i++) {
							
							
							var flag=0;
							if(assets_array[i]['name'] == 'Investment'){
								
								if(assets_array[i]['fmv_c'] != ''){
									
									if(assets_array[i]['fmv_c'] > 0){
										
										var fmv80 = parseFloat(assets_array[i]['fmv_c']) * 0.8;
									}
									else{
										
										assets_array[i]['fmv_c'] = 0;
										var fmv80 = 0;
									}
									if(assets_array[i]['loan_balance_c'] != ''){
										
										var eq = fmv80 - Math.floor(assets_array[i]['loan_balance_c']);
										if(eq < 0)
											eq = 0;	
										else{
											total_asset += parseFloat(eq);
										}
									}
								}
									
								flag = 1;
								tbody +=  "<tr> <td class='td col1'>Investment</td>"
									  + "<td class='td col2'>" + '$' + assets_array[i]['fmv_c'] + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + assets_array[i]['loan_balance_c'] + "</td>"
									  + "<td class='td col2'>" + '$' + eq + "</td>"
									  + "</tr>";
									  
								
							}
							if(assets_array[i]['name'] == 'Life Insurance With Cash Value'){
								
								flag = 1;
								if(assets_array[i]['fmv_c'] != ''){
									
									var fmv80 = parseFloat(assets_array[i]['fmv_c']) * 0.8;
									if(assets_array[i]['loan_balance_c'] != ''){
										
										var eq = fmv80 - Math.floor(assets_array[i]['loan_balance_c']);
										if(eq < 0)
											eq = 0;	
										else{
											total_asset += parseFloat(eq);
										}
									}
								}
								tbody +=  "<tr> <td class='td col1'>Life Insurance With Cash Value</td>"
									  + "<td class='td col2'>" + '$' + assets_array[i]['fmv_c'] + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + assets_array[i]['loan_balance_c'] + "</td>"
									  + "<td class='td col2'>" + '$' + eq + "</td>"
									  + "</tr>";
							}
							//alert(assets_array[i]['name']);
							if(assets_array[i]['name'] == 'Retirement Account'){
								
								flag = 1;
								if(assets_array[i]['fmv_c'] != ''){
									
									var fmv80 = parseFloat(assets_array[i]['fmv_c']) * 0.8;
									if(assets_array[i]['loan_balance_c'] != ''){
										
										var eq = fmv80 - Math.floor(assets_array[i]['loan_balance_c']);
										if(eq < 0)
											eq = 0;	
										else{
											total_asset += parseFloat(eq);
										}
									}
								}
								tbody +=  "<tr> <td class='td col1'>Retirement Account</td>"
									  + "<td class='td col2'>" + '$' + assets_array[i]['fmv_c'] + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + assets_array[i]['loan_balance_c'] + "</td>"
									  + "<td class='td col2'>" + '$' + eq + "</td>"
									  + "</tr>";
							}	  
							/*if(flag < 1){
								
								other_assts_fmv += parseFloat(assets_array[i]['fmv_c']);
								other_assts_lb += parseFloat(assets_array[i]['loan_balance_c']);
								other_assts_eq += parseFloat(assets_array[i]['equity_c']);
							}*/
							
							if(flag < 1){
								
								//other_assts_fmv += parseFloat(assets_array[i]['fmv_c']);
								//other_assts_lb += parseFloat(assets_array[i]['loan_balance_c']);
								//other_assts_eq += parseFloat(assets_array[i]['equity_c']);
								
								if(assets_array[i]['fmv_c'] != ''){
									
									var fmv80 = parseFloat(assets_array[i]['fmv_c']) * 0.8;
									if(assets_array[i]['loan_balance_c'] != ''){
										
										var eq = fmv80 - Math.floor(assets_array[i]['loan_balance_c']);
										if(eq < 0)
											eq = 0;	
										else{
											total_asset += parseFloat(eq);
										}
									}
								}
								
								tbody_extra +=  "<tr> <td class='td col1'>" + assets_array[i]['name'] + "</td>"
									  + "<td class='td col2'>" + '$' + parseFloat(assets_array[i]['fmv_c']) + "</td>"
									  + "<td class='td col2'>80%</td>"
									  + "<td class='td col2'>"+ '$'  + parseFloat(assets_array[i]['loan_balance_c']) + "</td>"
									  + "<td class='td col2'>" + '$' + Math.floor(eq) + "</td>"
									  + "</tr>";
							} 
							
							//total_asset += parseFloat(assets_array[i]['equity_c']);
						}
														  
						tbody += tbody_extra; 
						
						
						//alert('state '+ state);
						if(state != ''){
							$('.assets_tab').append(tbody); //html(tbody);
						}
						//if(total_asset > 0)
							//$(".ra").text('$'+ Math.floor(total_asset));
					}					
					
					//DISPLAY PPS DATA					
					
					if(len_all_case_pps){
						
						var tbody = '';
						var tab_tbody = '';
						var tab_tbody_sp = '';
						var tab_tbody_state = '';

						for (var i = 0; i < len_all_case_pps; i++) {
							
							var common = ''; 

							tbody += "<tr id=" + all_case_pps_array[i]['id'] + ">";
							
							tab_tbody += "<tr id=" + all_case_pps_array[i]['id'] + ">";
							tab_tbody_sp += "<tr id=" + all_case_pps_array[i]['id'] + ">";
							tab_tbody_state += "<tr id=" + all_case_pps_array[i]['id'] + ">";
							
                            //tbody += '<td>' + all_case_pps_array[i]['name'] + '</td> '
                            tbody += '<td>' + '<a href="<?php echo base_url();?>index.php/oldpps/records?id='+all_case_pps_array[i]['id']+'" title="Extra PPS" id="view_extra_pps"  target="_blank" style="color: royalblue;">' + all_case_pps_array[i]['name'] + '</a></td> '
                            tab_tbody += '<td>' + '<a href="<?php echo base_url();?>index.php/oldpps/records?id='+all_case_pps_array[i]['id']+'" title="Extra PPS" id="view_extra_pps"  target="_blank" style="color: royalblue;">' + all_case_pps_array[i]['name'] + '</a></td> '

							tab_tbody_sp += '<td>' + '<a href="<?php echo base_url();?>index.php/oldpps/records?id='+all_case_pps_array[i]['id']+'" title="Extra PPS" id="view_extra_pps"  target="_blank" style="color: royalblue;">' + all_case_pps_array[i]['name'] + '</a></td> ';
							tab_tbody_state += '<td>' + '<a href="<?php echo base_url();?>index.php/oldpps/records?id='+all_case_pps_array[i]['id']+'" title="Extra PPS" id="view_extra_pps"  target="_blank" style="color: royalblue;">' + all_case_pps_array[i]['name'] + '</a></td> ';

							

							tbody += '<td>' + all_case_pps_array[i]['pps_status'] + '</td>'
									+ '<td>' + all_case_pps_array[i]['name'] + '</td>';
							
							tab_tbody += '<td>' + all_case_pps_array[i]['pps_status'] + '</td>' + '<td>' + all_case_pps_array[i]['name'] + '</td> ';
                                    

							tab_tbody_sp += '<td>' + all_case_pps_array[i]['pps_status'] + '</td>' + '<td>' + all_case_pps_array[i]['name'] + '</td> ';

							tab_tbody_state += '<td>' + all_case_pps_array[i]['pps_status'] + '</td>' + '<td>' + all_case_pps_array[i]['name'] + '</td> ';
									
							tbody += '<td><select class="form-control" name="tab_poa_issue" id="tab_poa_issue" disabled><option value="">Select</option>';
							
							tab_tbody += '<td><select class="form-control" name="poa_issue" id="poa_issue" disabled><option value="">Select</option>';
							tab_tbody_sp += '<td><select class="form-control" name="poa_issue" id="poa_issue_sp" disabled><option value="">Select</option>';;
							tab_tbody_state += '<td><select class="form-control" name="poa_issue" id="poa_issue_state" disabled><option value="">Select</option>';;

							if(all_case_pps_array[i]['poa_issues'] == 'Electronic Signature'){
								
								common += '<option value="Electronic Signature" selected>Electronic Signature</option>';
								//tbody += '<option value="Electronic Signature" selected>Electronic Signature</option>';
								//tab_tbody += '<option value="Electronic Signature" selected>Electronic Signature</option>';
								//tab_tbody_sp += '<option value="Electronic Signature" selected>Electronic Signature</option>';
								//tab_tbody_state += '<option value="Electronic Signature" selected>Electronic Signature</option>';
							}
							else{
								
								common += '<option value="Electronic Signature">Electronic Signature</option>';
								//tbody += '<option value="Electronic Signature">Electronic Signature</option>';
								//tab_tbody += '<option value="Electronic Signature">Electronic Signature</option>';
							}
							
							if(all_case_pps_array[i]['poa_issues'] == 'SSN Name Mismatch'){
								
								common += '<option value="SSN Name Mismatch" selected>SSN/Name Mismatch</option>';
								//tbody += '<option value="SSN Name Mismatch" selected>SSN/Name Mismatch</option>';
								//tab_tbody += '<option value="SSN Name Mismatch" selected>SSN/Name Mismatch</option>';
							}
							else{
								
								common += '<option value="SSN Name Mismatch">SSN/Name Mismatch</option>';
								//tbody += '<option value="SSN Name Mismatch">SSN/Name Mismatch</option>';
								//tab_tbody += '<option value="SSN Name Mismatch">SSN/Name Mismatch</option>';
							}
							
							if(all_case_pps_array[i]['poa_issues'] == 'Name Signature Mismatch'){
								
								common += '<option value="Name Signature Mismatch" selected>Name/Signature Mismatch</option>';
								//tbody += '<option value="Name Signature Mismatch" selected>Name/Signature Mismatch</option>';
								//tab_tbody += '<option value="Name Signature Mismatch" selected>Name/Signature Mismatch</option>';
							}
							else{
								
								common += '<option value="Name Signature Mismatch">Name/Signature Mismatch</option>';
								//tbody += '<option value="Name Signature Mismatch">Name/Signature Mismatch</option>';
								//tab_tbody += '<option value="Name Signature Mismatch">Name/Signature Mismatch</option>';
							}
							
							if(all_case_pps_array[i]['poa_issues'] == 'ITIN Expired'){
								
								common += '<option value="ITIN Expired" selected>ITIN Expired</option>';
								//tbody += '<option value="ITIN Expired" selected>ITIN Expired</option>';
								
								//tab_tbody += '<option value="ITIN Expired" selected>ITIN Expired</option>';
							}
							else{
								
								common += '<option value="ITIN Expired">ITIN Expired</option>';
								//tbody += '<option value="ITIN Expired">ITIN Expired</option>';
								//tab_tbody += '<option value="ITIN Expired">ITIN Expired</option>';
							}
							
							if(all_case_pps_array[i]['poa_issues'] == 'Number returns no results'){
								
								common += '<option value="Number returns no results" selected>Number returns no results</option>';
								//tbody += '<option value="Number returns no results" selected>Number returns no results</option>';
								
								//tab_tbody += '<option value="Number returns no results" selected>Number returns no results</option>';
							}
							else{
								
								common += '<option value="Number returns no results">Number returns no results</option>';
								//tbody += '<option value="Number returns no results">Number returns no results</option>';
								
								//tab_tbody += '<option value="Number returns no results">Number returns no results</option>';
							}
							
							if(all_case_pps_array[i]['poa_issues'] == '8821CT Not On File'){
								
								common += '<option value="8821CT Not On File" selected>8821CT Not On File</option>';
								//tbody += '<option value="8821CT Not On File" selected>8821CT Not On File</option>';
								
								//tab_tbody += '<option value="8821CT Not On File" selected>8821CT Not On File</option>';
							}
							else{
								
								common += '<option value="8821CT Not On File">8821CT Not On File</option>';
								//tbody += '<option value="8821CT Not On File">8821CT Not On File</option>';
								
								//tab_tbody += '<option value="8821CT Not On File">8821CT Not On File</option>';
							}
							
							common += '</select></td>';
							tbody += common;
							tab_tbody += common;
							tab_tbody_sp += common;
							tab_tbody_state += common;
							
							common = '';
							//tbody += '<td><select class="form-control" name="biz_poa_issue" id="biz_poa_issue"><option value="">Select</option>';
							
							tbody += '<td><select class="js-example-basic-single form-control" id="biz_poa' + i + '" name="biz_poa[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >				<option value="">Select</option>';
							
							tab_tbody += '<td><select class="js-example-basic-single form-control" id="tbiz_poa' + i + '" name="biz_poa[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >				<option value="">Select</option>';
							
							tab_tbody_sp += '<td><select class="js-example-basic-single form-control" id="tbiz_poa_sp' + i + '" name="biz_poa_sp[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >				<option value="">Select</option>';
							tab_tbody_state += '<td><select class="js-example-basic-single form-control" id="tbiz_poa_state' + i + '" name="biz_poa_state[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >				<option value="">Select</option>';

							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'EIN Name Mismatch'){
								
								tbody += '<option value="EIN Name Mismatch" selected>EIN/Name Mismatch</option>';
							}
							else*/
							{
								common += '<option value="EIN Name Mismatch">EIN/Name Mismatch</option>';
								//tbody += '<option value="EIN Name Mismatch">EIN/Name Mismatch</option>';
								
								//tab_tbody += '<option value="EIN Name Mismatch">EIN/Name Mismatch</option>';
							}
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Electronic Signature'){
								
								tbody += '<option value="Electronic Signature" selected>Electronic Signature</option>';
							}
							else*/
							{
								common += '<option value="Electronic Signature">Electronic Signature</option>';
								//tbody += '<option value="Electronic Signature">Electronic Signature</option>';
								
								//tab_tbody += '<option value="Electronic Signature">Electronic Signature</option>';
							}
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Entity Type Mismatch'){
								
								tbody += '<option value="Entity Type Mismatch" selected>Entity Type Mismatch</option>';
							}
							else*/
							{
								common += '<option value="Entity Type Mismatch">Entity Type Mismatch</option>';
								//tbody += '<option value="Entity Type Mismatch">Entity Type Mismatch</option>';
								
								//tab_tbody += '<option value="Entity Type Mismatch">Entity Type Mismatch</option>';
							}
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Incorrect Title'){
								
								tbody += '<option value="Incorrect Title" selected>Incorrect Title</option>';
							}
							else
							{
								
								tbody += '<option value="Incorrect Title">Incorrect Title</option>';
								
								tab_tbody += '<option value="Incorrect Title">Incorrect Title</option>';
							}*/
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Incomplete Name'){
								
								tbody += '<option value="Incomplete Name" selected>Incomplete Name</option>';
							}
							else
							{
								
								tbody += '<option value="Incomplete Name">Incomplete Name</option>';
								
								tab_tbody += '<option value="Incomplete Name">Incomplete Name</option>';
							}*/
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Signature Date Expired'){
								
								tbody += '<option value="Signature Date Expired" selected>Signature Date Expired</option>';
							}
							else*/
							{
								common += '<option value="Signature Date Expired">Signature Date Expired</option>';
								//tbody += '<option value="Signature Date Expired">Signature Date Expired</option>';
								
								//tab_tbody += '<option value="Signature Date Expired">Signature Date Expired</option>';
							}
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Business Address Mismatch'){
								
								tbody += '<option value="Business Address Mismatch" selected>Business Address Mismatch</option>';
							}
							else*/
							{
								common += '<option value="Business Address Mismatch">Business Address Mismatch</option>';
								//tbody += '<option value="Business Address Mismatch">Business Address Mismatch</option>';
								
								//tab_tbody += '<option value="Business Address Mismatch">Business Address Mismatch</option>';
							}
							
							/*if(all_case_pps_array[i]['biz_poa_issues'] == 'Reason Not Specified'){
								
								tbody += '<option value="Reason Not Specified" selected>Reason Not Specified</option>';
							}
							else*/
							{
								common += '<option value="Reason Not Specified">Reason Not Specified</option>';
								//tbody += '<option value="Reason Not Specified">Reason Not Specified</option>';
								
								//tab_tbody += '<option value="Reason Not Specified">Reason Not Specified</option>';
								
								common += '<option value="8821CT Not On File">8821CT Not On File</option>';
								//tbody += '<option value="8821CT Not On File">8821CT Not On File</option>';
								
								//tab_tbody += '<option value="8821CT Not On File">8821CT Not On File</option>';
							}
							
							common += '</select></td>';
							tbody += common;
							
							tab_tbody += common;
							tab_tbody_sp += common;
							tab_tbody_state += common;
							common = '';
							
							//options for state poa 
							if(all_case_pps_array[i]['pps_type'].indexOf('State') != -1){
								
							
								tab_tbody_state += '<td><select class="form-control" name="tab_pps_verbal_poa" id="tab_pps_verbal_poa" disabled><option value=""></option>';
								
								if(all_case_pps_array[i]['verbal_poa_c'] == 'Yes'){								
									
									tab_tbody_state += '<option value="Yes" selected>Yes</option>';
								}
								else{

									tab_tbody_state += '<option value="Yes">Yes</option>';
								}
								if(all_case_pps_array[i]['verbal_poa_c'] == 'No'){								
									//console.log('No');
									tab_tbody_state += '<option value="No" selected>No</option>';
								}
								else{

									tab_tbody_state += '<option value="No">No</option>';
								}
								tab_tbody_state += '</select></td>';
								console.log(all_case_pps_array[i]['state_c']);							

								tab_tbody_state += '<td><select class="form-control" name="tab_pps_state" id="tab_pps_state" disabled><option value=""></option>';
								
								if(statelist != ''){
									
									$.each(Object.keys(statelist),function(j,key){
										

										console.log(key + ', ' + statelist[key]);
										var short = statelist[key];
										if(short != ''){
										
											if(statelist[key] == all_case_pps_array[i]['state_c']){

												tab_tbody_state += '<option value="'+ statelist[key] + '" selected>' +statelist[key] + '</option>';
											}
											else{

												tab_tbody_state += '<option value="'+ statelist[key] + '">' + statelist[key] + '</option>';
											}
										}
									});

								}

								//tab_tbody_state += '<option value="AL">Alabama</option><option value="CA">California</option>';
								tab_tbody_state += '</select></td>';
								
								// end of state options

								//pps instruction 

								if(all_case_pps_array[i]['pps_instructions_c'] != '' && all_case_pps_array[i]['pps_instructions_c'] != null){

									var instruct = all_case_pps_array[i]['pps_instructions_c'];
								}
								else{

									var instruct = '';
								}
								tab_tbody_state += '<td><input type="text" id="tab_pps_instruct" value="'+ instruct + '" disabled></td>';
							}
							else{

								tab_tbody_state += '<td></td><td></td><td></td>';
							}

							if(all_case_pps_array[i]['modified_by'] != null){
								
								tbody += '<td>' + all_case_pps_array[i]['modified_by'] + '</td>';
								
								tab_tbody += '<td>' + all_case_pps_array[i]['modified_by'] + '</td>';
								tab_tbody_sp += '<td>' + all_case_pps_array[i]['modified_by'] + '</td>';
								tab_tbody_state += '<td>' + all_case_pps_array[i]['modified_by'] + '</td>';
							}
							else{
								tbody += '<td></td>';
								tab_tbody += '<td></td>';
								tab_tbody_sp += '<td></td>';
								tab_tbody_state += '<td></td>';
							}
									
							/*if(all_case_pps_array[i]['assigned_to'] != null){
								
								tbody += '<td>' + all_case_pps_array[i]['assigned_to'] + '</td>';
							}
							else{
								tbody += '<td></td>';
							}*/
							
							//tbody += '<td><select class="form-control" name="pps_assigned_to" id="pps_assigned_to"><option value="">Select</option>';
							
							tbody += '<td><select class="form-control" name="tab_pps_assigned_to" id="tab_pps_assigned_to" disabled><option value="">Select</option>';
							
							tab_tbody += '<td><select class="form-control" name="pps_assigned_to" id="pps_assigned_to" disabled><option value="">Select</option>';
							
							tab_tbody_sp += '<td><select class="form-control" name="pps_assigned_to" id="pps_assigned_to_sp" disabled><option value="">Select</option>';
							tab_tbody_state += '<td><select class="form-control" name="pps_assigned_to" id="pps_assigned_to_state" disabled><option value="">Select</option>';
							
							if(len_pps_users){
								
								for (var j=0; j<len_pps_users; j++){
									
									if(all_case_pps_array[i]['assigned_user_id'] == pps_users_array[j]['user_id']){
										
										//tbody += '<option value="'+ pps_users_array[j]['user_id'] + '" selected>' + pps_users_array[j]['uname'] + '</option>';
										
										tbody += '<option value="'+ pps_users_array[j]['user_id'] + '" selected>' + pps_users_array[j]['uname'] + '</option>';
										
										tab_tbody += '<option value="'+ pps_users_array[j]['user_id'] + '" selected>' + pps_users_array[j]['uname'] + '</option>';
										tab_tbody_sp += '<option value="'+ pps_users_array[j]['user_id'] + '" selected>' + pps_users_array[j]['uname'] + '</option>';
										tab_tbody_state += '<option value="'+ pps_users_array[j]['user_id'] + '" selected>' + pps_users_array[j]['uname'] + '</option>';
									}
									else{
										
										//tbody += '<option value="'+ pps_users_array[j]['user_id'] + '">' + pps_users_array[j]['uname'] + '</option>';
										
										tbody += '<option value="'+ pps_users_array[j]['user_id'] + '">' + pps_users_array[j]['uname'] + '</option>';
										
										tab_tbody += '<option value="'+ pps_users_array[j]['user_id'] + '">' + pps_users_array[j]['uname'] + '</option>';
										tab_tbody_sp += '<option value="'+ pps_users_array[j]['user_id'] + '">' + pps_users_array[j]['uname'] + '</option>';
										tab_tbody_state += '<option value="'+ pps_users_array[j]['user_id'] + '">' + pps_users_array[j]['uname'] + '</option>';
									}
								}
							}
							tbody += '</select></td>';
							tab_tbody += '</select></td>';
							tab_tbody_sp += '</select></td>';
							tab_tbody_state += '</select></td>';

							tbody += '<td>' + all_case_pps_array[i]['date_modified'] + '</td>';
							tab_tbody += '<td>' + all_case_pps_array[i]['date_modified'] + '</td>';
							tab_tbody_sp += '<td>' + all_case_pps_array[i]['date_modified'] + '</td>';
							tab_tbody_state += '<td>' + all_case_pps_array[i]['date_modified'] + '</td>';
							
							tbody += '<td> <button type="button" class="btn btn-pps-edit" title="Edit" id="tab_edit_pps" href="javascript:;" onClick="tabEditPPS(this)"><i class="glyph-icon icon-pencil"></i></button>  <button type="button" class="btn btn-pps-save" title="Save" id="tab_save_pps" href="javascript:;" onClick="tabSavePPS(this)" style="display: none;"><i class="glyph-icon icon-check"></i></button>';
							
							tab_tbody += '<td> <button type="button" class="btn btn-pps-edit" title="Edit" id="edit_pps" href="javascript:;" onClick="EditPPS(this)"><i class="glyph-icon icon-pencil"></i></button>  <button type="button" class="btn btn-pps-save" title="Save" id="save_pps" href="javascript:;" onClick="SavePPS(this)" style="display: none;"><i class="glyph-icon icon-check"></i></button>';
							tab_tbody_sp += '<td> <button type="button" class="btn btn-pps-edit" title="Edit" id="edit_pps_sp" href="javascript:;" onClick="EditPPSSP(this)"><i class="glyph-icon icon-pencil"></i></button>  <button type="button" class="btn btn-pps-save" title="Save" id="save_pps_sp" href="javascript:;" onClick="SavePPSSP(this)" style="display: none;"><i class="glyph-icon icon-check"></i></button>';
							tab_tbody_state += '<td> <button type="button" class="btn btn-pps-edit" title="Edit" id="edit_pps_state" href="javascript:;" onClick="EditPPSState(this)"><i class="glyph-icon icon-pencil"></i></button>  <button type="button" class="btn btn-pps-save" title="Save" id="save_pps_state" href="javascript:;" onClick="SavePPSState(this)" style="display: none;"><i class="glyph-icon icon-check"></i></button>';
							
							/*if(all_case_pps_array[i]['pps_status'] == 'Completed'){
								
								tbody += '<td></td>';
							}
							else{
								
								//<button type="button" class="btn btn-pps-edit" title="Edit" id="edit_pps" href="javascript:;" onClick="editPPS(this)">  <i class="glyph-icon icon-pencil"></i> </button>
								
								tbody += '<td>   <button type="button" class="btn btn-primary" title="Completed" id="pps_completed" onClick="updatePPSStatus(this);">Completed</button></td>';
							}*/
							
							if(all_case_pps_array[i]['pps_status'] != 'Completed'){
								
								tbody += ' <button type="button" class="btn btn-primary" title="Completed" id="pps_completed" onClick="updatePPSStatus(this);">Completed</button>';
								
								tab_tbody += ' <button type="button" class="btn btn-primary" title="Completed" id="acc_pps_completed" onClick="updatePPSStatus(this);">Completed</button>';
								tab_tbody_sp += ' <button type="button" class="btn btn-primary" title="Completed" id="acc_pps_completed_sp" onClick="updatePPSStatus(this);">Completed</button>';
								tab_tbody_state += ' <button type="button" class="btn btn-primary" title="Completed" id="acc_pps_completed_state" onClick="updatePPSStatus(this);">Completed</button>';
							}
							tbody += '</td>';
							tab_tbody += '</td>';
							tab_tbody_sp += '</td>';
							tab_tbody_state += '</td>';
							
							tbody += '<tr>';
							tab_tbody += '<tr>';
							tab_tbody_sp += '<tr>';
							tab_tbody_state += '<tr>';
							
							if(all_case_pps_array[i]['id'] == jsonData.checkpps_sp){
								
								$('#tab_pps_spouse_instruct_note').val(all_case_pps_array[i]['pps_instructions_c']);
								$('#pps_spouse_instruct_note').val(all_case_pps_array[i]['pps_instructions_c']);
							}
							
							if(all_case_pps_array[i]['id'] == jsonData.checkpps_statepers){
								
								$('#tab_pps_state_instruct_note').val(all_case_pps_array[i]['pps_instructions_c']);
								$('#pps_state_instruct_note').val(all_case_pps_array[i]['pps_instructions_c']);
							}
						}
						if(tbody != ''){
							
							$('.all_case_pps_tbody').html('');	
							$('.all_case_pps_tbody').append(tab_tbody); 
							
							$('.tab_all_case_pps_tbody').html('');	
							$('.tab_all_case_pps_tbody').append(tbody); 

							$('.tab_all_case_pps_tbody_sp').html('');	
							$('.tab_all_case_pps_tbody_sp').append(tab_tbody_sp);

							$('.tab_all_case_pps_tbody_state').html('');	
							$('.tab_all_case_pps_tbody_state').append(tab_tbody_state);  
							//$('.js-example-basic-multiple').select2();
							
							renderSelect2();
							
							for (var i = 0; i < len_all_case_pps; i++) {
								
								if(all_case_pps_array[i]['biz_poa_issues'] != '^^' && all_case_pps_array[i]['biz_poa_issues'] != null){
									
									//alert(all_case_pps_array[i]['biz_poa_issues']);
									//^Incomplete Name^,^Reason Not Specified^
									
									var issue = all_case_pps_array[i]['biz_poa_issues'];
									issue = issue.split('^').join('');
									
									var selectedIssues = [];
									if(issue){
										var arrIssues = issue.split(',');
										var iss='';
										for(var j in arrIssues) {
											iss += "'" + arrIssues[j] + "'" + ',';
											selectedIssues.push(arrIssues[j]);
											
										}
										iss = iss.substring(0,iss.length-1);
										//console.log(iss)
										//$('#biz_poa'+i).select2('val', [iss]); 
										//$('#tbiz_poa'+i).select2('val', [iss]);
										$('#biz_poa'+i).select2().val(selectedIssues).trigger('change');  
										$('#tbiz_poa'+i).select2().val(selectedIssues).trigger('change'); 

										$('#tbiz_poa_sp'+i).select2().val(selectedIssues).trigger('change'); 
										$('#tbiz_poa_state'+i).select2().val(selectedIssues).trigger('change');    
									}							
								}
							}
						}
						/*if(tbody != ''){
							
							$('.all_case_pps_tbody').html('');	
							$('.all_case_pps_tbody').append(tbody); 
							
							$('.tab_all_case_pps_tbody').html('');	
							$('.tab_all_case_pps_tbody').append(tbody); 
						}*/
					}
					
					if(len_pps){
						
						var tbody = '';				
						
						
						$( "#tab_pps_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						$( "#pps_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						
						var total_se = 0;
						var total_oth = 0;
						var total_wg= 0;
						var toal_pps_balance = 0;						
						
						var ppsid1 = pps_array[0]['ppsid'];
						$('#tab_pps_table').attr("name", ppsid1);
						$('#pps_table').attr("name", ppsid1);
						
						$('#pps_id').val(ppsid1);
						$('#pps_id_tab').val(ppsid1);
						
						for (var i = 0; i < len_pps; i++) {
							
							if(pps_array[i]['reported_se'] == null)
								pps_array[i]['reported_se'] = '';
							else 
								total_se += parseInt(Math.floor(pps_array[i]['reported_se']));
							
							if(pps_array[i]['reported_other'] == null)
								pps_array[i]['reported_other'] = '';
							else 
								total_oth += parseInt(Math.floor(pps_array[i]['reported_other']));
							
							if(pps_array[i]['reported_wage'] == null)
								pps_array[i]['reported_wage'] = '';
							else 
								total_wg += parseInt(Math.floor(pps_array[i]['reported_wage']));
							
							if(pps_array[i]['balance'] != '')
								toal_pps_balance += parseInt(Math.floor(pps_array[i]['balance']));
							
							tbody += "<tr class= '" + pps_array[i]['id']+ "' id=" + pps_array[i]['tax_period'] + ">"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + pps_array[i]['tax_period'] + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + pps_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
							if(pps_array[i]['filing_status'] == 'Single'){
                                tbody += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody += '<option value="Single">Single</option>';
                            }
							if(pps_array[i]['filing_status'] == 'HOH'){
                                tbody += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody += '<option value="HOH">HOH</option>';
                            }
							if(pps_array[i]['filing_status'] == 'MFJ'){
                                tbody += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody += '<option value="MFJ">MFJ</option>';
                            }
							if(pps_array[i]['filing_status'] == 'MFS'){
                                tbody += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody += '<option value="MFS">MFS</option>';
                            }							
                            if(pps_array[i]['filing_status'] == 'SFR'){
                                tbody += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody += '<option value="SFR">SFR</option>';
                            }
							if(pps_array[i]['filing_status'] == 'Unfiled'){
                                tbody += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody += '<option value="Unfiled">Unfiled</option>';
                            }
							if(pps_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody += '</select></td> ';                           
                            
							tbody += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(pps_array[i]['account_status'] == 'IA'){
                                tbody += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody += '<option value="IA">IA</option>';
                            }
							if(pps_array[i]['account_status'] == 'Defaulted IA'){
                                tbody += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(pps_array[i]['account_status'] == 'First Notice'){
                                tbody += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody += '<option value="First Notice">First Notice</option>';
                            }
							if(pps_array[i]['account_status'] == '2nd NOITL'){
                                tbody += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(pps_array[i]['account_status'] == '3rd FNOITL'){
                                tbody += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(pps_array[i]['account_status'] == '4th NOL'){
                                tbody += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody += '<option value="4th NOL">4th NOL</option>';
                            }
							if(pps_array[i]['account_status'] == 'ACS'){
                                tbody += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody += '<option value="ACS">ACS</option>';
                            }
							if(pps_array[i]['account_status'] == '3rd Party'){
                                tbody += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody += '<option value="3rd Party">3rd Party</option>';
                            }
							if(pps_array[i]['account_status'] == 'CNC'){
                                tbody += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody += '<option value="CNC">CNC</option>';
                            }
							if(pps_array[i]['account_status'] == 'RO'){
                                tbody += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody += '<option value="RO">RO</option>';
                            }
							if(pps_array[i]['account_status'] == 'Exam'){
                                tbody += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody += '<option value="Exam">Exam</option>';
                            }
							tbody += '</select>   </td>';
							
							if(pps_array[i]['csed_date_c'] != '00/00/0000' && pps_array[i]['csed_date_c'] != null){
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="' + pps_array[i]['csed_date_c'] + '" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
                            }
							else{
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>'
							}
							tbody +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(pps_array[i]['ro_recommended'] == 'Required'){
                                tbody += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody += '<option value="Required">Required</option>';
                            }
                            if(pps_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(pps_array[i]['ro_recommended'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
                            tbody += '</select>   </td>';				
							
							
							tbody += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(pps_array[i]['fta_eligible'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(pps_array[i]['fta_eligible'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
							tbody += '</select>   </td>';
							
							tbody += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(pps_array[i]['liens'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(pps_array[i]['liens'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
							tbody += '</select>   </td>';
							
                            tbody += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + pps_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="' + pps_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="' + pps_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + pps_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + pps_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(pps_array[i]['tin_c'] == 'SSN'){
                                tbody += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody += '<option value="SSN">SSN</option>';
                            }
                            if(pps_array[i]['tin_c'] == 'ITIN'){
                                tbody += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody += '</select>   </td>';
							
                            tbody +=  '<td id="actionbtn"> ' ;
							if(i == len_pps - 1){
								//console.log('if')
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNew(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else{
								//console.log('else')
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button><button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button>';
							}
							tbody +=   "</tr>";					
												
						}
						
						//total row
						tbody += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						//for tab view
						var tbody_tab = '';
						for (var i = 0; i < len_pps; i++) {
							if(pps_array[i]['reported_se'] == null)
								pps_array[i]['reported_se'] = '';
							if(pps_array[i]['reported_other'] == null)
								pps_array[i]['reported_other'] = '';							
							
							tbody_tab += "<tr class= '" + pps_array[i]['id']+ "' id='" + pps_array[i]['tax_period'] + "_tab'>"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + pps_array[i]['tax_period'] + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + pps_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
							if(pps_array[i]['filing_status'] == 'Single'){
                                tbody_tab += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody_tab += '<option value="Single">Single</option>';
                            }
							if(pps_array[i]['filing_status'] == 'HOH'){
                                tbody_tab += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody_tab += '<option value="HOH">HOH</option>';
                            }
							if(pps_array[i]['filing_status'] == 'MFJ'){
                                tbody_tab += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFJ">MFJ</option>';
                            }
							if(pps_array[i]['filing_status'] == 'MFS'){
                                tbody_tab += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFS">MFS</option>';
                            }
                            if(pps_array[i]['filing_status'] == 'SFR'){
                                tbody_tab += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody_tab += '<option value="SFR">SFR</option>';
                            }
							if(pps_array[i]['filing_status'] == 'Unfiled'){
                                tbody_tab += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody_tab += '<option value="Unfiled">Unfiled</option>';
                            }
							if(pps_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody_tab += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody_tab += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody_tab += '</select></td> ';                           
                            
							tbody_tab += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(pps_array[i]['account_status'] == 'IA'){
                                tbody_tab += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="IA">IA</option>';
                            }
							if(pps_array[i]['account_status'] == 'Defaulted IA'){
                                tbody_tab += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(pps_array[i]['account_status'] == 'First Notice'){
                                tbody_tab += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody_tab += '<option value="First Notice">First Notice</option>';
                            }
							if(pps_array[i]['account_status'] == '2nd NOITL'){
                                tbody_tab += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(pps_array[i]['account_status'] == '3rd FNOITL'){
                                tbody_tab += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(pps_array[i]['account_status'] == '4th NOL'){
                                tbody_tab += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody_tab += '<option value="4th NOL">4th NOL</option>';
                            }
							if(pps_array[i]['account_status'] == 'ACS'){
                                tbody_tab += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody_tab += '<option value="ACS">ACS</option>';
                            }
							if(pps_array[i]['account_status'] == '3rd Party'){
                                tbody_tab += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd Party">3rd Party</option>';
                            }							
							if(pps_array[i]['account_status'] == 'CNC'){
                                tbody_tab += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody_tab += '<option value="CNC">CNC</option>';
                            }							
							if(pps_array[i]['account_status'] == 'RO'){
                                tbody_tab += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody_tab += '<option value="RO">RO</option>';
                            }
							if(pps_array[i]['account_status'] == 'Exam'){
                                tbody_tab += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody_tab += '<option value="Exam">Exam</option>';
                            }
							tbody_tab += '</select>   </td>';
							
							if(pps_array[i]['csed_date_c'] != '00/00/0000' && pps_array[i]['csed_date_c'] != null){
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="' + pps_array[i]['csed_date_c'] + '" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
                            }
							else{
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
							}
							
							tbody_tab +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(pps_array[i]['ro_recommended'] == 'Required'){
                                tbody_tab += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody_tab += '<option value="Required">Required</option>';
                            }
                            if(pps_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody_tab += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody_tab += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(pps_array[i]['ro_recommended'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
                            tbody_tab += '</select>   </td>';						
							
							tbody_tab += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(pps_array[i]['fta_eligible'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(pps_array[i]['fta_eligible'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
							tbody_tab += '</select>   </td>';
							
							tbody_tab += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(pps_array[i]['liens'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(pps_array[i]['liens'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }
							tbody_tab += '</select>   </td>';
							
                            tbody_tab += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + pps_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="' + pps_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="' + pps_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + pps_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + pps_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(pps_array[i]['tin_c'] == 'SSN'){
                                tbody_tab += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody_tab += '<option value="SSN">SSN</option>';
                            }
                            if(pps_array[i]['tin_c'] == 'ITIN'){
                                tbody_tab += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody_tab += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody_tab += '</select>   </td>';
                            
							
                            tbody_tab +=  '<td id="actionbtn"> ' ;
							if(i == len_pps - 1){
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNew(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else{
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNew(this)">  <i class="glyph-icon icon-plus"></i> </button><button type="button" type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							tbody_tab +=  "</tr>";					
												
						}
						
						tbody_tab += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						$("#pps_table").append(tbody);
						$("#tab_pps_table").append(tbody_tab);
						
						$('#save_tab_pps_data').attr("disabled", false);
						$('#save_pps_data').attr("disabled", false);
						
						if(total_se > 0)
							$(".npc").text('$'+total_se);
						if(total_oth > 0)
							$(".oth").text('$'+total_oth);
						if(total_wg > 0)
							$(".wg").text('$'+total_wg);
						if(toal_pps_balance > 0)
							$(".cur_bal").text('$'+toal_pps_balance.toLocaleString());
						
					}
					
					//spouse pps
					if(len_pps_spouse){
						
						var tbody = '';
						var toal_spouse_pps_balance = 0;
												
						$( "#tab_spouse_pps_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						$( "#spouse_pps_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						
						var total_se = 0;
						var total_oth = 0;
						var total_wg= 0;
						
						var ppsid = spouse_pps_array[0]['ppsid'];
						$('#tab_spouse_pps_table').attr("name", ppsid);
						$('#spouse_pps_table').attr("name", ppsid);
						$('#pps_id_tab_spouse').val(ppsid);
						$('#pps_id_spouse').val(ppsid);
						
						for (var i = 0; i < len_pps_spouse; i++) {
							
							if(spouse_pps_array[i]['reported_se'] == null)
								spouse_pps_array[i]['reported_se'] = '';
							else 
								total_se += parseInt(Math.floor(spouse_pps_array[i]['reported_se']));
							
							if(spouse_pps_array[i]['reported_other'] == null)
								spouse_pps_array[i]['reported_other'] = '';
							else 
								total_oth += parseInt(Math.floor(spouse_pps_array[i]['reported_other']));
							
							if(spouse_pps_array[i]['reported_wage'] == null)
								spouse_pps_array[i]['reported_wage'] = '';
							else 
								total_wg += parseInt(Math.floor(spouse_pps_array[i]['reported_wage']));
							
							if(spouse_pps_array[i]['balance'] != '')
								toal_spouse_pps_balance +=  parseInt(Math.floor(spouse_pps_array[i]['balance']));
							//console.log(spouse_pps_array[i]['tax_period']);
							var syear = spouse_pps_array[i]['tax_period'];
							//console.log(syear);
							tbody += "<tr class= '" + spouse_pps_array[i]['id']+ "' id=" + spouse_pps_array[i]['tax_period'] + ">"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + syear + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + spouse_pps_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
									
							if(spouse_pps_array[i]['filing_status'] == 'Single'){
                                tbody += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody += '<option value="Single">Single</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'HOH'){
                                tbody += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody += '<option value="HOH">HOH</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'MFJ'){
                                tbody += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody += '<option value="MFJ">MFJ</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'MFS'){
                                tbody += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody += '<option value="MFS">MFS</option>';
                            }
                            if(spouse_pps_array[i]['filing_status'] == 'SFR'){
                                tbody += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody += '<option value="SFR">SFR</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'Unfiled'){
                                tbody += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody += '<option value="Unfiled">Unfiled</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody += '</select></td> ';                           
                            
							tbody += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(spouse_pps_array[i]['account_status'] == 'IA'){
                                tbody += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody += '<option value="IA">IA</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'Defaulted IA'){
                                tbody += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'First Notice'){
                                tbody += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody += '<option value="First Notice">First Notice</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '2nd NOITL'){
                                tbody += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '3rd FNOITL'){
                                tbody += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '4th NOL'){
                                tbody += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody += '<option value="4th NOL">4th NOL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'ACS'){
                                tbody += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody += '<option value="ACS">ACS</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '3rd Party'){
                                tbody += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody += '<option value="3rd Party">3rd Party</option>';
                            } 
							if(spouse_pps_array[i]['account_status'] == 'CNC'){
                                tbody += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody += '<option value="CNC">CNC</option>';
                            }							
							if(spouse_pps_array[i]['account_status'] == 'RO'){
                                tbody += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody += '<option value="RO">RO</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'Exam'){
                                tbody += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody += '<option value="Exam">Exam</option>';
                            }
							tbody += '</select>   </td>';
							
							if(spouse_pps_array[i]['csed_date_c'] != '00/00/0000' && spouse_pps_array[i]['csed_date_c'] != null){
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="' + spouse_pps_array[i]['csed_date_c'] + '" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
                            }
							else{
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
							}
							
							tbody +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(spouse_pps_array[i]['ro_recommended'] == 'Required'){
                                tbody += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody += '<option value="Required">Required</option>';
                            }
                            if(spouse_pps_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(spouse_pps_array[i]['ro_recommended'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
                            tbody += '</select>   </td>';				
							
							
							tbody += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(spouse_pps_array[i]['fta_eligible'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(spouse_pps_array[i]['fta_eligible'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
							tbody += '</select>   </td>';
							
							tbody += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(spouse_pps_array[i]['liens'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(spouse_pps_array[i]['liens'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }
							tbody += '</select>   </td>';
							
                            tbody += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + spouse_pps_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="' + spouse_pps_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="' + spouse_pps_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + spouse_pps_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + spouse_pps_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(spouse_pps_array[i]['tin_c'] == 'SSN'){
                                tbody += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody += '<option value="SSN">SSN</option>';
                            }
                            if(spouse_pps_array[i]['tin_c'] == 'ITIN'){
                                tbody += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody += '</select>   </td>';
							
                            tbody +=  '<td id="actionbtn"> ' ;
							if(i == len_pps_spouse - 1){
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewRowSpousePPS(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else{
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNewRowSpousePPS(this)">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							tbody +=   "</tr>";					
												
						}
						
						tbody += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_spouse_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						//for tab view
						var tbody_tab = '';
						for (var i = 0; i < len_pps_spouse; i++) {
							if(spouse_pps_array[i]['reported_se'] == null)
								spouse_pps_array[i]['reported_se'] = '';
							if(spouse_pps_array[i]['reported_other'] == null)
								spouse_pps_array[i]['reported_other'] = '';							
							
							tbody_tab += "<tr class= '" + spouse_pps_array[i]['id']+ "' id='" + spouse_pps_array[i]['tax_period'] + "_spouse_tab'>"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + spouse_pps_array[i]['tax_period'] + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + spouse_pps_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
							if(spouse_pps_array[i]['filing_status'] == 'Single'){
                                tbody_tab += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody_tab += '<option value="Single">Single</option>';
                            }							
							if(spouse_pps_array[i]['filing_status'] == 'HOH'){
                                tbody_tab += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody_tab += '<option value="HOH">HOH</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'MFJ'){
                                tbody_tab += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFJ">MFJ</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'MFS'){
                                tbody_tab += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFS">MFS</option>';
                            }
                            if(spouse_pps_array[i]['filing_status'] == 'SFR'){
                                tbody_tab += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody_tab += '<option value="SFR">SFR</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'Unfiled'){
                                tbody_tab += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody_tab += '<option value="Unfiled">Unfiled</option>';
                            }
							if(spouse_pps_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody_tab += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody_tab += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody_tab += '</select></td> ';                           
                            
							tbody_tab += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(spouse_pps_array[i]['account_status'] == 'IA'){
                                tbody_tab += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="IA">IA</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'Defaulted IA'){
                                tbody_tab += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'First Notice'){
                                tbody_tab += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody_tab += '<option value="First Notice">First Notice</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '2nd NOITL'){
                                tbody_tab += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '3rd FNOITL'){
                                tbody_tab += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '4th NOL'){
                                tbody_tab += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody_tab += '<option value="4th NOL">4th NOL</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'ACS'){
                                tbody_tab += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody_tab += '<option value="ACS">ACS</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == '3rd Party'){
                                tbody_tab += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd Party">3rd Party</option>';
                            }							
							if(spouse_pps_array[i]['account_status'] == 'CNC'){
                                tbody_tab += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody_tab += '<option value="CNC">CNC</option>';
                            }							
							if(spouse_pps_array[i]['account_status'] == 'RO'){
                                tbody_tab += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody_tab += '<option value="RO">RO</option>';
                            }
							if(spouse_pps_array[i]['account_status'] == 'Exam'){
                                tbody_tab += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody_tab += '<option value="Exam">Exam</option>';
                            }
							tbody_tab += '</select>   </td>';
							
							if(spouse_pps_array[i]['csed_date_c'] != '00/00/0000' && spouse_pps_array[i]['csed_date_c'] != null){
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="' + spouse_pps_array[i]['csed_date_c'] + '" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
                            }
							else{
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"> </td>';
							}
                            
							tbody_tab +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(spouse_pps_array[i]['ro_recommended'] == 'Required'){
                                tbody_tab += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody_tab += '<option value="Required">Required</option>';
                            }
                            if(spouse_pps_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody_tab += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody_tab += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(spouse_pps_array[i]['ro_recommended'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
                            tbody_tab += '</select>   </td>';						
							
							tbody_tab += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(spouse_pps_array[i]['fta_eligible'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(spouse_pps_array[i]['fta_eligible'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
							tbody_tab += '</select>   </td>';
							
							tbody_tab += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(spouse_pps_array[i]['liens'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(spouse_pps_array[i]['liens'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
							tbody_tab += '</select>   </td>';
							
                            tbody_tab += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + spouse_pps_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="' + spouse_pps_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="' + spouse_pps_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + spouse_pps_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + spouse_pps_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(spouse_pps_array[i]['tin_c'] == 'SSN'){
                                tbody_tab += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody_tab += '<option value="SSN">SSN</option>';
                            }
                            if(spouse_pps_array[i]['tin_c'] == 'ITIN'){
                                tbody_tab += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody_tab += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody_tab += '</select>   </td>';
                            
							
                            tbody_tab +=  '<td id="actionbtn"> ' ;
							if(i == len_pps_spouse - 1){
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewRowSpousePPS(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else{
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNewRowSpousePPS(this)">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_pps_row_spouse(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							tbody_tab +=  "</tr>";					
												
						}
						
						tbody_tab += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_spouse_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						$("#spouse_pps_table ").append(tbody);
						$("#tab_spouse_pps_table ").append(tbody_tab);
						
						$('#save_tab_spouse_pps_data').attr("disabled", false);
						$('#save_spouse_pps_data').attr("disabled", false);
					}					
					
					if(len_pps_state_personel){
						
						var tbody = '';				
						
						
						$( "#tab_pps_state_pers_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						$( "#pps_state_pers_table tbody tr" ).each( function(){
						  this.parentNode.removeChild( this ); 
						});
						
						var total_se = 0;
						var total_oth = 0;
						var total_wg= 0;
						var toal_pps_balance = 0;
						
						var ppsid1 = pps_state_personel_array[0]['ppsid'];
						$('#tab_pps_state_pers_table').attr("name", ppsid1);
						$('#pps_state_pers_table').attr("name", ppsid1);
						
						$('#pps_state_pers_id').val(ppsid1);
						$('#pps_state_pers_id_tab').val(ppsid1);
						
						for (var i = 0; i < len_pps_state_personel; i++) 
						{
							if(pps_state_personel_array[i]['reported_se'] == null)
								pps_state_personel_array[i]['reported_se'] = '';
							else 
								total_se += parseInt(Math.floor(pps_state_personel_array[i]['reported_se']));
							
							if(pps_state_personel_array[i]['reported_other'] == null)
								pps_state_personel_array[i]['reported_other'] = '';
							else 
								total_oth += parseInt(Math.floor(pps_state_personel_array[i]['reported_other']));
							
							if(pps_state_personel_array[i]['reported_wage'] == null)
								pps_state_personel_array[i]['reported_wage'] = '';
							else 
								total_wg += parseInt(Math.floor(pps_state_personel_array[i]['reported_wage']));
							
							if(pps_state_personel_array[i]['balance'] != '')
								toal_pps_balance += parseInt(Math.floor(pps_state_personel_array[i]['balance']));
							
							tbody += "<tr class= '" + pps_state_personel_array[i]['id']+ "' id=" + pps_state_personel_array[i]['tax_period'] + ">"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + pps_state_personel_array[i]['tax_period'] + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + pps_state_personel_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
									
							if(pps_state_personel_array[i]['filing_status'] == 'Single'){
                                tbody += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody += '<option value="Single">Single</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'HOH'){
                                tbody += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody += '<option value="HOH">HOH</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'MFJ'){
                                tbody += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody += '<option value="MFJ">MFJ</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'MFS'){
                                tbody += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody += '<option value="MFS">MFS</option>';
                            }							
                            if(pps_state_personel_array[i]['filing_status'] == 'SFR'){
                                tbody += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody += '<option value="SFR">SFR</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'Unfiled'){
                                tbody += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody += '<option value="Unfiled">Unfiled</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody += '</select></td> ';                           
                            
							tbody += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(pps_state_personel_array[i]['account_status'] == 'IA'){
                                tbody += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody += '<option value="IA">IA</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'Defaulted IA'){
                                tbody += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'First Notice'){
                                tbody += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody += '<option value="First Notice">First Notice</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '2nd NOITL'){
                                tbody += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '3rd FNOITL'){
                                tbody += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '4th NOL'){
                                tbody += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody += '<option value="4th NOL">4th NOL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'ACS'){
                                tbody += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody += '<option value="ACS">ACS</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '3rd Party'){
                                tbody += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody += '<option value="3rd Party">3rd Party</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'CNC'){
                                tbody += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody += '<option value="CNC">CNC</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'RO'){
                                tbody += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody += '<option value="RO">RO</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'Exam'){
                                tbody += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody += '<option value="Exam">Exam</option>';
                            }
							tbody += '</select>   </td>';
							
							if(pps_state_personel_array[i]['csed_date_c'] != '00/00/0000'){ 
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="' + pps_state_personel_array[i]['csed_date_c'] + '" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"></td>';
							}
							else{
								
								tbody += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"></td>';
							}
                            
							tbody +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(pps_state_personel_array[i]['ro_recommended'] == 'Required'){
                                tbody += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody += '<option value="Required">Required</option>';
                            }
                            if(pps_state_personel_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(pps_state_personel_array[i]['ro_recommended'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
                            tbody += '</select>   </td>';				
							
							
							tbody += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(pps_state_personel_array[i]['fta_eligible'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(pps_state_personel_array[i]['fta_eligible'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
							tbody += '</select>   </td>';
							
							tbody += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(pps_state_personel_array[i]['liens'] == 'Yes'){
                                tbody += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody += '<option value="Yes">Yes</option>';
                            }
							if(pps_state_personel_array[i]['liens'] == 'No'){
                                tbody += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody += '<option value="No">No</option>';
                            }							
							tbody += '</select>   </td>';
							
                            tbody += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + pps_state_personel_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_se" value="' + pps_state_personel_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody += '<td><input class="form-control1" type ="text" name="rep_other" value="' + pps_state_personel_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + pps_state_personel_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + pps_state_personel_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(pps_state_personel_array[i]['tin_c'] == 'SSN'){
                                tbody += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody += '<option value="SSN">SSN</option>';
                            }
                            if(pps_state_personel_array[i]['tin_c'] == 'ITIN'){
                                tbody += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody += '</select>   </td>';                            
							
                            tbody +=  '<td id="actionbtn"> ' ;
							
							if(i == len_pps_state_personel - 1)
							{
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewPPSStatePersonal(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else
							{
								tbody +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNewPPSStatePersonal(this)">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							tbody +=   "</tr>";					
												
						}
						
						//total row
						tbody += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						//for tab view
						var tbody_tab = '';
						for (var i = 0; i < len_pps_state_personel; i++) {
							if(pps_state_personel_array[i]['reported_se'] == null)
								pps_state_personel_array[i]['reported_se'] = '';
							if(pps_state_personel_array[i]['reported_other'] == null)
								pps_state_personel_array[i]['reported_other'] = '';							
							
							tbody_tab += "<tr class= '" + pps_state_personel_array[i]['id']+ "' id='" + pps_state_personel_array[i]['tax_period'] + "_tab'>"
                                    + '<td><input class="form-control1" type ="text" name="year" value="' + pps_state_personel_array[i]['tax_period'] + '" id="pps_year2" required></td> '
                                    + '<td><input class="form-control1" type ="text" name="balance" value="' + pps_state_personel_array[i]['balance'] + '" id="defaultbal"> </td>'
                                    + '<td><select class="form-control1" name="filingstat" id="filing_stat"><option value="0">Select</option>';
							if(pps_state_personel_array[i]['filing_status'] == 'Single'){
                                tbody_tab += '<option value="Single" selected>Single</option>';
                            }
                            else{
                                tbody_tab += '<option value="Single">Single</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'HOH'){
                                tbody_tab += '<option value="HOH" selected>HOH</option>';
                            }
                            else{
                                tbody_tab += '<option value="HOH">HOH</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'MFJ'){
                                tbody_tab += '<option value="MFJ" selected>MFJ</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFJ">MFJ</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'MFS'){
                                tbody_tab += '<option value="MFS" selected>MFS</option>';
                            }
                            else{
                                tbody_tab += '<option value="MFS">MFS</option>';
                            }
                            if(pps_state_personel_array[i]['filing_status'] == 'SFR'){
                                tbody_tab += '<option value="SFR" selected>SFR</option>';
                            }
                            else{
                                tbody_tab += '<option value="SFR">SFR</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'Unfiled'){
                                tbody_tab += '<option value="Unfiled" selected>Unfiled</option>';
                            }
                            else{
                                tbody_tab += '<option value="Unfiled">Unfiled</option>';
                            }
							if(pps_state_personel_array[i]['filing_status'] == 'Qualifying Widow'){
                                tbody_tab += '<option value="Qualifying Widow" selected>Qualifying Widow</option>';
                            }
                            else{
                                tbody_tab += '<option value="Qualifying Widow">Qualifying Widow</option>';
                            }
							tbody_tab += '</select></td> ';                           
                            
							tbody_tab += '<td><select class="form-control1" name="accountstat" id="account_stat">	<option value="0">Select</option>';
							if(pps_state_personel_array[i]['account_status'] == 'IA'){
                                tbody_tab += '<option value="IA" selected>IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="IA">IA</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'Defaulted IA'){
                                tbody_tab += '<option value="Defaulted IA" selected>Defaulted IA</option>';
                            }
                            else{
                                tbody_tab += '<option value="Defaulted IA">Defaulted IA</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'First Notice'){
                                tbody_tab += '<option value="First Notice" selected>First Notice</option>';
                            }
                            else{
                                tbody_tab += '<option value="First Notice">First Notice</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '2nd NOITL'){
                                tbody_tab += '<option value="2nd NOITL" selected>2nd NOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="2nd NOITL">2nd NOITL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '3rd FNOITL'){
                                tbody_tab += '<option value="3rd FNOITL" selected>3rd FNOITL</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd FNOITL">3rd FNOITL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '4th NOL'){
                                tbody_tab += '<option value="4th NOL" selected>4th NOL</option>';
                            }
                            else{
                                tbody_tab += '<option value="4th NOL">4th NOL</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'ACS'){
                                tbody_tab += '<option value="ACS" selected>ACS</option>';
                            }
                            else{
                                tbody_tab += '<option value="ACS">ACS</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == '3rd Party'){
                                tbody_tab += '<option value="3rd Party" selected>3rd Party</option>';
                            }
                            else{
                                tbody_tab += '<option value="3rd Party">3rd Party</option>';
                            }							
							if(pps_state_personel_array[i]['account_status'] == 'CNC'){
                                tbody_tab += '<option value="CNC" selected>CNC</option>';
                            }
                            else{
                                tbody_tab += '<option value="CNC">CNC</option>';
                            }							
							if(pps_state_personel_array[i]['account_status'] == 'RO'){
                                tbody_tab += '<option value="RO" selected>RO</option>';
                            }
                            else{
                                tbody_tab += '<option value="RO">RO</option>';
                            }
							if(pps_state_personel_array[i]['account_status'] == 'Exam'){
                                tbody_tab += '<option value="Exam" selected>Exam</option>';
                            }
                            else{
                                tbody_tab += '<option value="Exam">Exam</option>';
                            }
							tbody_tab += '</select>   </td>';
							
							if(pps_state_personel_array[i]['csed_date_c'] != '00/00/0000'){
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="' + pps_state_personel_array[i]['csed_date_c'] + '" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"></td>';
							}
							else{
								
								tbody_tab += '<td><input class="form-control1" type ="text" name="csed" value="" id="csedid" <input class="form-control1 datepick" type="text" name="csed" value="" id="csedid" onkeyup="checkMask(this);" maxlength="10"></td>';
							}
							
                            
							tbody_tab +=  '<td><select class="form-control1" name="ro_recommend" id="ro"><option value="0">Select</option> ';
                            if(pps_state_personel_array[i]['ro_recommended'] == 'Required'){
                                tbody_tab += '<option value="Required" selected>Required</option>';
                            }
                            else{
                                tbody_tab += '<option value="Required">Required</option>';
                            }
                            if(pps_state_personel_array[i]['ro_recommended'] == 'Original Recommended'){
                                tbody_tab += '<option value="Original Recommended" selected>Original Recommended</option>';
                            }
                            else{
                                tbody_tab += '<option value="Original Recommended">Original Recommended</option>';
                            }
							if(pps_state_personel_array[i]['ro_recommended'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
                            tbody_tab += '</select>   </td>';						
							
							tbody_tab += '<td><select class="form-control1" name="fta_eligible" id="fta_eligible"> <option value="0"></option>';
							if(pps_state_personel_array[i]['fta_eligible'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(pps_state_personel_array[i]['fta_eligible'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }							
							tbody_tab += '</select>   </td>';
							
							tbody_tab += '<td><select class="form-control1" name="liens" id="liens"> <option value="0"></option>';
							if(pps_state_personel_array[i]['liens'] == 'Yes'){
                                tbody_tab += '<option value="Yes" selected>Yes</option>';
                            }
                            else{
                                tbody_tab += '<option value="Yes">Yes</option>';
                            }
							if(pps_state_personel_array[i]['liens'] == 'No'){
                                tbody_tab += '<option value="No" selected>No</option>';
                            }
                            else{
                                tbody_tab += '<option value="No">No</option>';
                            }
							tbody_tab += '</select>   </td>';
							
                            tbody_tab += '<td><input class="form-control1" type ="text" name="rep_wage" value="' + pps_state_personel_array[i]['reported_wage'] + '" id="rep_wage"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_se" value="' + pps_state_personel_array[i]['reported_se'] + '" id="rep_se"> </td>';
							tbody_tab += '<td><input class="form-control1" type ="text" name="rep_other" value="' + pps_state_personel_array[i]['reported_other'] + '" id="rep_other"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="gross_month_inc" value="' + pps_state_personel_array[i]['gross_monthly_income'] + '" id="gross_month_inc"> </td>';
							
							tbody_tab += '<td><input class="form-control1" type="text" name="tax_withheld" value="' + pps_state_personel_array[i]['taxes_withheld'] + '" id="tax_withheld"> </td>';
							
							tbody_tab +=  '<td><select class="form-control1" name="tin" id="tin">';
                            if(pps_state_personel_array[i]['tin_c'] == 'SSN'){
                                tbody_tab += '<option value="SSN" selected>SSN</option>';
                            }
                            else{
                                tbody_tab += '<option value="SSN">SSN</option>';
                            }
                            if(pps_state_personel_array[i]['tin_c'] == 'ITIN'){
                                tbody_tab += '<option value="ITIN" selected>ITIN</option>';
                            }
                            else{
                                tbody_tab += '<option value="ITIN">ITIN</option>';
                            }                                           
                            tbody_tab += '</select>   </td>';
                            
							
                            tbody_tab +=  '<td id="actionbtn"> ' ;
							if(i == len_pps_state_personel - 1){
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;" onClick="addNewPPSStatePersonal(this);">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							else{
								tbody_tab +=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" href="javascript:;" style="display: none;" onClick="addNewPPSStatePersonal(this)">  <i class="glyph-icon icon-plus"></i> </button>			<button type="button" type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="deletePPSRowStatePersonal(this);"><i class="glyph-icon icon-remove"></i></button> ';
							}
							tbody_tab +=  "</tr>";					
												
						}
						
						tbody_tab += '<tr id="total"> <td><input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly></td> 						<td colspan="14"><input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_pps_balance) + '" id="total_balance"></td> 	</tr>';
						
						$("#pps_state_pers_table").append(tbody);
						$("#tab_pps_state_pers_table").append(tbody_tab);
						
						$('#save_tab_pps_state_pers_data').attr("disabled", false);
						$('#save_pps_state_pers_data').attr("disabled", false);		
					}					
					
					if(len_pps_notes){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_pps_notes; i++) {
							//alert(pps_notes_array[i]['note_text']);
							pnote =  pps_notes_array[i]['note_text']+ "&#10;" + moment.utc(pps_notes_array[i]['note_datecreated']).local().format('MMMM Do YYYY, h:mm:ss a') + ', ' + pps_notes_array[i]['note_user'];
							
							if(pnote != '')
							{
								pnote = pnote.replace("&amp;#039;", "'");
							}

							html += '<textarea rows="7" cols="80" class="form-control past_pps_notes" id="' + pps_notes_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnote" id="n' + pps_notes_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnote" id="s' + pps_notes_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button>  <button class="btn btn-xs btn-danger deleteppsnote" id="d' + pps_notes_array[i]['id'] + '" title="Delete"  onclick="delete_pps_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
							html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notes" id="t' + pps_notes_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnote" id="tn' + pps_notes_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnote" id="ts' + pps_notes_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeleteppsnote" id="td' + pps_notes_array[i]['id'] + '" title="Delete"  onclick="delete_pps_tab_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
						}
						
						$('#pps_notes').html('');
						$('#tab_pps_notes').html('');
						document.getElementById("pps_notes").insertAdjacentHTML('afterbegin', html);
						document.getElementById("tab_pps_notes").insertAdjacentHTML('afterbegin', html_tab);
						
					}	
					
					
					if(len_pps_notes_spouse){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_pps_notes_spouse; i++) {
							//alert(pps_notes_spouse_array[i]['note_text']);
							pnote =  pps_notes_spouse_array[i]['note_text'] + "&#10;" + moment.utc(pps_notes_spouse_array[i]['note_datecreated']).local().format('MMMM Do YYYY, h:mm:ss a') + ', ' + pps_notes_spouse_array[i]['note_user'];
							
							if(pnote != '')
							{
								pnote = pnote.replace("&amp;#039;", "'");
							}

							html += '<textarea rows="7" cols="80" class="form-control past_pps_spouse_notes" id="sp' + pps_notes_spouse_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsspousenote" id="esp' + pps_notes_spouse_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsspousenote" id="ssp' + pps_notes_spouse_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsspousenote" id="dsp' + pps_notes_spouse_array[i]['id'] + '" title="Delete"  onclick="delete_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
							
							html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_spouse_notes" id="tsp' + pps_notes_spouse_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsspousenote" id="tesp' + pps_notes_spouse_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsspousenote" id="tssp' + pps_notes_spouse_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger tdeleteppsspousenote" id="tdsp' + pps_notes_spouse_array[i]['id'] + '" title="Delete"  onclick="delete_tab_pps_spouse_note(this.id);"><i class="glyph-icon icon-remove"></i></button>';
						}
						
						$('#pps_spouse_notes').html('');
						$('#tab_pps_spouse_notes').html('');
						
						document.getElementById("pps_spouse_notes").insertAdjacentHTML('afterbegin', html);
						document.getElementById("tab_pps_spouse_notes").insertAdjacentHTML('afterbegin', html_tab);
						
					}
					
					if(len_pps_notes_state_personal){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_pps_notes_state_personal; i++) {
							
							pnote =  pps_notes_state_personal_array[i]['note_text']+ "&#10;" + moment.utc(pps_notes_state_personal_array[i]['note_datecreated']).local().format('MMMM Do YYYY, h:mm:ss a') + ', ' + pps_notes_state_personal_array[i]['note_user'];

							if(pnote != '')
							{
								pnote = pnote.replace("&amp;#039;", "'");
							}

							html += '<textarea rows="7" cols="80" class="form-control past_pps_notessp" id="' + pps_notes_state_personal_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit editppsnotesp" id="sp' + pps_notes_state_personal_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save saveppsnotesp" id="ssp' + pps_notes_state_personal_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_pps_notesp(this.id);"> <i class="glyph-icon icon-check"></i></button> <button class="btn btn-xs btn-danger deleteppsnote" id="dsp' + pps_notes_state_personal_array[i]['id'] + '" title="Delete"  onclick="delete_pps_notesp(this.id);"> <i class="glyph-icon icon-remove"></i></button>';
				 
							html_tab += '<textarea rows="7" cols="10" class="form-control past_pps_notessp" id="t' + pps_notes_state_personal_array[i]['id'] + '" readonly>' + pnote + '</textarea>  <button class="btn btn-xs btn-edit teditppsnotesp" id="tsp' + pps_notes_state_personal_array[i]['id'] + '" title="Edit"  style="background-color: #48C6AC;" onclick="edit_tab_pps_notesp(this.id);"><i class="glyph-icon icon-pencil"></i></button>  <button class="btn btn-xs btn-save tsaveppsnotesp" id="tssp' + pps_notes_state_personal_array[i]['id'] + '" title="Save"  style="display: none; background-color: #48C6AC;" onclick="save_tab_pps_notesp(this.id);"><i class="glyph-icon icon-check"></i></button>  </button>  <button class="btn btn-xs btn-danger tdeleteppsnote" id="tdsp' + pps_notes_state_personal_array[i]['id'] + '" title="Delete"  onclick="delete_pps_tab_notesp(this.id);"><i class="glyph-icon icon-remove"></i></button>';
						}
						
						$('#pps_state_pers_notes').html('');
						$('#tab_pps_state_pers_notes').html(''); 
						
						document.getElementById("pps_state_pers_notes").insertAdjacentHTML('afterbegin', html);
						document.getElementById("tab_pps_state_pers_notes").insertAdjacentHTML('afterbegin', html_tab);
						
					}
					
					if(total_asset > 0){
							$(".sa").text('$'+total_asset.toLocaleString()); 
							$(".ra").text('$'+total_asset.toLocaleString()); 
					}
					
					//alert(len_tc_intake + ', '+ tc_intake_array[0]['cid_fed_intake_notes_c']);
					//console.log(tc_intake_array);
					if(len_tc_intake)
					{
						var years = tc_intake_array[0]['scr_tax_years_needed_c'];
						
						if(years) 
						{
							years = years.split('^').join('');
						}
						
						$('.fed_debt').text(Math.floor(tc_intake_array[0]['cid_fed_debt_amount_c']));
						$('.state_debt').text(Math.floor(tc_intake_array[0]['cid_state_debt_amount_c']));
						
						if(years)
						{
							$('.missing_tax_years').text(years);
						}
						if(tc_intake_array[0]['cid_biz_issues_c'] != null)
						{
							$('.bix_tax_case').text(tc_intake_array[0]['cid_biz_issues_c']);
						}
						if(tc_intake_array[0]['cid_fed_garnishment_c'] != null)
						{
							$('.fed_garnishment').text(tc_intake_array[0]['cid_fed_garnishment_c']);
						}
						if(tc_intake_array[0]['cid_state_garnishment_c'] != null)
						{
							$('.state_garnishment').text(tc_intake_array[0]['cid_state_garnishment_c']);
						}
						if(tc_intake_array[0]['fed_bank_lvy_c'] != null)
						{
							$('.fed_levy').text(tc_intake_array[0]['fed_bank_lvy_c']);
						}
						if(tc_intake_array[0]['state_bank_levy_c'] != null)
						{
							$('.state_levy').text(tc_intake_array[0]['state_bank_levy_c']);
						}
						if(tc_intake_array[0]['cid_fed_audit_c'] != null)
						{
							$('.fed_audit_issue').text(tc_intake_array[0]['cid_fed_audit_c']);
						}
						if(tc_intake_array[0]['cid_state_audit_c'] != null)
						{
							$('.state_audit_issue').text(tc_intake_array[0]['cid_state_audit_c']);
						}
						if(tc_intake_array[0]['cid_passport_c'] != null)
						{
							$('.passport_issue').text(tc_intake_array[0]['cid_passport_c']);
						}
						if(tc_intake_array[0]['cid_license_issue_c'] != null)
						{
							$('.st_driver_lic_issue').text(tc_intake_array[0]['cid_license_issue_c']);
						}
						if(tc_intake_array[0]['cid_immigration_c'] != null)
						{
							$('.immigration').text(tc_intake_array[0]['cid_immigration_c']);
						}
						if(tc_intake_array[0]['cid_professional_license_c'] != null)
						{
							$('.st_prof_lic_issue').text(tc_intake_array[0]['cid_professional_license_c']);
						}
						
						if(tc_intake_array[0]['cid_fed_deadline_reason_c'] != null)
						{
							$('#federal_deadline_reason').val(tc_intake_array[0]['cid_fed_deadline_reason_c'])
							$('#acc_federal_deadline_reason').val(tc_intake_array[0]['cid_fed_deadline_reason_c'])
						}
						if(tc_intake_array[0]['cid_state_deadline_reason_c'] != null)
						{
							$('#state_deadline_reason').val(tc_intake_array[0]['cid_state_deadline_reason_c']);						
							$('#acc_state_deadline_reason').val(tc_intake_array[0]['cid_state_deadline_reason_c']);						
						}
						if(tc_intake_array[0]['cid_fed_deadline_date_c'] != null)
						{
							$('#federal_deadline_date').val(tc_intake_array[0]['cid_fed_deadline_date_c']);						
							$('#acc_federal_deadline_date').val(tc_intake_array[0]['cid_fed_deadline_date_c']);						
						}
						if(tc_intake_array[0]['personal_state_deadline_date_c'] != null)
						{
							$('#state_deadline_date').val(tc_intake_array[0]['personal_state_deadline_date_c']);						
							$('#acc_state_deadline_date').val(tc_intake_array[0]['personal_state_deadline_date_c']);						
						}
						/*if(tc_intake_array[0]['cid_fed_ro_c'] != null)
						{
							$('#federal_ro').val(tc_intake_array[0]['cid_fed_ro_c']);
						}
						if(tc_intake_array[0]['cid_state_ro_c'] != null)
						{
							$('#state_ro').val(tc_intake_array[0]['cid_state_ro_c']);
						}*/
						if(tc_intake_array[0]['cid_fed_ro_name_c'] != null)
						{
							$('#federal_ro_name').val(tc_intake_array[0]['cid_fed_ro_name_c']);
							$('#acc_federal_ro_name').val(tc_intake_array[0]['cid_fed_ro_name_c']);
						}
						if(tc_intake_array[0]['state_revenue_officer_name_c'] != null)
						{
							$('#state_ro_name').val(tc_intake_array[0]['state_revenue_officer_name_c']);
							$('#acc_state_ro_name').val(tc_intake_array[0]['state_revenue_officer_name_c']);
						}
						if(tc_intake_array[0]['cid_fed_ro_contact_c'] != null)
						{
							$('#federal_ro_phone').val(tc_intake_array[0]['cid_fed_ro_contact_c']);
							$('#acc_federal_ro_phone').val(tc_intake_array[0]['cid_fed_ro_contact_c']);
						}
						if(tc_intake_array[0]['cid_state_ro_phone_c'] != null)
						{
							$('#state_ro_phone').val(tc_intake_array[0]['cid_state_ro_phone_c']);
							$('#acc_state_ro_phone').val(tc_intake_array[0]['cid_state_ro_phone_c']);
						}
						if(tc_intake_array[0]['cid_fed_ro_fax_c'] != null)
						{
							$('#federal_ro_fax').val(tc_intake_array[0]['cid_fed_ro_fax_c']);
							$('#acc_federal_ro_fax').val(tc_intake_array[0]['cid_fed_ro_fax_c']);
						}
						if(tc_intake_array[0]['cid_state_ro_fax_c'] != null)
						{
							$('#state_ro_fax').val(tc_intake_array[0]['cid_state_ro_fax_c']);
							$('#acc_state_ro_fax').val(tc_intake_array[0]['cid_state_ro_fax_c']);
						}
						if(tc_intake_array[0]['cid_fed_intake_notes_c'] != null)
						{
							$('.fed_note').val(tc_intake_array[0]['cid_fed_intake_notes_c']);
						}
						if(tc_intake_array[0]['cid_state_intake_notes_c'] != null)
						{
							$('.state_note').val(tc_intake_array[0]['cid_state_intake_notes_c']);
						}
						if(tc_intake_array[0]['fr_survey_reason_c'] != null)
						{
							$('.fr_survey_reason').text(tc_intake_array[0]['fr_survey_reason_c']);
						}
						if(tc_intake_array[0]['fr_survey_incometype_c'] != null)
						{
							$('.fr_urvey_incometype').text(tc_intake_array[0]['fr_survey_incometype_c']);
						}
						if(tc_intake_array[0]['fr_survey_taxprohelp_c'] != null)
						{
							$('.fr_survey_taxprohelp').text(tc_intake_array[0]['fr_survey_taxprohelp_c']);
						}
						if(tc_intake_array[0]['fr_survey_complete_c'] != null)
						{
							$('.fr_survey_complete').text(tc_intake_array[0]['fr_survey_complete_c']);
						}
						if(tc_intake_array[0]['fr_survey_unfiled_c'] != null)
						{
							$('.fr_survey_unfiled').text(tc_intake_array[0]['fr_survey_unfiled_c']);
						}
						if(tc_intake_array[0]['fr_survey_incomerange_c'] != null)
						{
							$('.fr_survey_incomerange').text(tc_intake_array[0]['fr_survey_incomerange_c']);
						}
						if(tc_intake_array[0]['fr_survey_addnotes_c'] != null)
						{
							$('.fr_survey_addnotes').text(tc_intake_array[0]['fr_survey_addnotes_c']);
						}
					}
					
					//finance
					if(len_finance){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_finance; i++) {							
							
							pnote +=  "<pre>";
							
							if(finance_array[i]['finance_notes_c'] != null){
								
								pnote +=  finance_array[i]['finance_notes_c'];
							}
							else{
								
								pnote +=  '';
							}
							pnote +=  "<br><b>Program Type: </b>" + finance_array[i]['program_type_c'] + "<br>";
							pnote +=  "<b>Application Date: </b>" + finance_array[i]['application_date'] + "<br>"; 
							pnote +=  "<b>Application Status: </b>" + finance_array[i]['application_status'] + "<br>"; 
							pnote +=  "<b>Recommended: </b>" + finance_array[i]['recommended'] + "<br>"; 
						}
						pnote += "</pre>";
						
						if(pnote != null){
							
							$('#note_finance').html('');
							$('#note_finance_tab').html('');
							
							document.getElementById("note_finance").insertAdjacentHTML('afterbegin', pnote); 							
							document.getElementById("note_finance_tab").insertAdjacentHTML('afterbegin', pnote); 
						}  
					}
					else{
						
						$('#note_finance_div').hide();  
						$('#note_finance_div_tab').hide();  
					} 
				}
			});
		}
	}
	
	function generateAccountStatusTable(len_as, account_status_array, sugarTaxprepData)
	{
		var tbody = '';
		var currentDate = new Date();
		var currentYear = currentDate.getFullYear();
		var defaultYear = currentYear - 1; 
		var TotalAccuredBalance = 0;
		// console.log('sugarTaxPrep_data account-status js',sugarTaxprepData);
		if(len_as){
			// console.log('account_status_array typeof ',typeof account_status_array,account_status_array);
			//  TO REMOVE LAST ROW
			account_status_array.splice(-1);
			// console.log('account_status_array2 typeof ',typeof account_status_array,account_status_array);

			for (var i = 0; i < len_as-1; i++) {
			
				var complianceLoopCount = 0;
				var dataCount = false;
				var AccruedBalance = "";
				var testAccured = 0;
				
				if(account_status_array[0]['accrued_balance'] > 0 || account_status_array[1]['accrued_balance'] > 0 || account_status_array[2]['accrued_balance'] > 0)
				{
					complianceLoopCount = 9;
				}
	
				var return_outcome = 0;
				if(account_status_array[i]['accrued_balance'] > 0)
				{
					testAccured=account_status_array[i]['accrued_balance'];
					AccruedBalance=account_status_array[i]['accrued_balance'];
				}
				else{
					testAccured = 0;
					AccruedBalance="";	     
				}
				// Condition for Default Year
				if( (i == 0) && (account_status_array[i]['year']!=defaultYear) ) 
				{
					var checkfirstoccurence = 0;
					if(sugarTaxprepData.length){
						for(var k=0; k<sugarTaxprepData.length; k++){
							if(sugarTaxprepData[k]['tax_year_c']==defaultYear && !checkfirstoccurence){
								if(isNaN(sugarTaxprepData[k]['return_outcome_c'])){
									return_outcome = sugarTaxprepData[k]['return_outcome_c'];
								}
								else{
									return_outcome =parseFloat(sugarTaxprepData[k]['return_outcome_c']).toFixed(2);
									TotalAccuredBalance +=return_outcome;
								}
								tbody +=  "<tr> <td class='td col1'>" + defaultYear + "</td>"
										+ "<td class='td col2'></td>" 
										+ "<td class='td col2'></td>" 
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'>"+return_outcome+"</td></tr>";
								
								checkfirstoccurence ++;
							}
						}
					}
					if(!checkfirstoccurence){
						tbody +=  "<tr> <td class='td col1'>" + defaultYear + "</td>"
										+ "<td class='td col2'></td>" 
										+ "<td class='td col2'></td>" 
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td>"
										+ "<td class='td col2'></td></tr>";
					}
				}
	
				if(complianceLoopCount){
					// IF ACCURED BALANCE IN FIRST 3 YEARS, SHOW DATA FOR NEXT 6 YEARS
					if(i < complianceLoopCount)
					{
						if(AccruedBalance > 0){
	
							tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
									+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
									+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
							if(account_status_array[i]['assessed_balance'] >= 0)
							{
								tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
							}
							else
							{
								tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
							}
							if(account_status_array[i]['accrued_balance'] >= 0){
								TotalAccuredBalance +=parseFloat(account_status_array[i]['accrued_balance']);
								
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
									
									total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
								}
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
									
									total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
								}
								tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
							}
							else{
								tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
							}
							tbody +=  "</tr>";
						}
						else {
							if(sugarTaxprepData.length){
	
								var checkYear = account_status_array[i]['year'];
								var sameYearCount = 0; var checkFlag = false;
								for(var j=0; j<sugarTaxprepData.length; j++){
									if(sugarTaxprepData[j]['tax_year_c']==account_status_array[i]['year']){
										checkFlag =true;
										if(checkYear==sugarTaxprepData[j]['tax_year_c'] && !sameYearCount){
											if(isNaN(sugarTaxprepData[j]['return_outcome_c'])){
												return_outcome = sugarTaxprepData[j]['return_outcome_c'];
											}
											else{
												return_outcome =parseFloat(sugarTaxprepData[j]['return_outcome_c']).toFixed(2);
												TotalAccuredBalance += return_outcome;
											}
											tbody +=  "<tr> <td class='td col1'>" + sugarTaxprepData[j]['tax_year_c'] + "</td>"
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'>"+return_outcome+"</td></tr>";
											sameYearCount++;
										}
									}
								}	
								if(!checkFlag){
									tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
											+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
											+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
									if(account_status_array[i]['assessed_balance'] >= 0)
									{
										tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
									}
									else
									{
										tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
									}
									if(account_status_array[i]['accrued_balance'] >= 0){
										TotalAccuredBalance +=parseFloat(account_status_array[i]['accrued_balance']);
										if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
											
											total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
										}
										if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
											
											total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
										}
										tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
									}
									else{
										tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
									}
									tbody +=  "</tr>";
								}
							}
							else{
								tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
								if(account_status_array[i]['assessed_balance'] >= 0)
								{
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
								}
								else
								{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
								}
								if(account_status_array[i]['accrued_balance'] >= 0){
									TotalAccuredBalance += parseFloat(account_status_array[i]['accrued_balance']);
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
										
										total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
										
										total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
								}
								else{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
								}
								tbody +=  "</tr>";
							}
						}
					}
	
				}
				else{
					// IF ACCURED BALANCE NOT IN FIRST 3 YEARS, SHOW UNTIL ACCURED BALANCE > 0 AND TILL >0
					if(dataCount){
						if(testAccured){
							tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
									+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
									+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
									+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
							if(account_status_array[i]['assessed_balance'] >= 0)
							{
								tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
							}
							else
							{
								tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
							}
							if(account_status_array[i]['accrued_balance'] >= 0){
								TotalAccuredBalance += parseFloat(account_status_array[i]['accrued_balance']);
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
									
									total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
								}
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
									
									total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
								}
								tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
							}
							else{
								tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
							}
							tbody +=  "</tr>";
						}
						else{
							if(sugarTaxprepData.length){
								var checkYear = account_status_array[i]['year'];
								var sameYearCount = 0; var checkAccuredFlag = false;
								for(var j=0; j<sugarTaxprepData.length; j++){
									if(sugarTaxprepData[j]['tax_year_c']==account_status_array[i]['year']){
										if(sugarTaxprepData[j]['return_outcome_c']>0){checkAccuredFlag =true;}
										if(checkYear==sugarTaxprepData[j]['tax_year_c'] && !sameYearCount){
											if(isNaN(sugarTaxprepData[j]['return_outcome_c'])){
												return_outcome = sugarTaxprepData[j]['return_outcome_c'];
											}
											else{
												return_outcome =parseFloat(sugarTaxprepData[j]['return_outcome_c']).toFixed(2);
												TotalAccuredBalance += return_outcome;
											}
											tbody +=  "<tr> <td class='td col1'>" + sugarTaxprepData[j]['tax_year_c'] + "</td>"
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'>"+return_outcome+"</td></tr>";
											sameYearCount++;
										}
									}
								}
								if(!checkAccuredFlag){
									break
								}	
							}else{
								break;
							}
						}
					}
					else{
						
						if(testAccured){
							dataCount=true;
							tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
								if(account_status_array[i]['assessed_balance'] >= 0)
								{
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
								}
								else
								{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
								}
								if(account_status_array[i]['accrued_balance'] >= 0){
									TotalAccuredBalance +=  parseFloat(account_status_array[i]['accrued_balance']);
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
										
										total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
										
										total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
								}
								else{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
								}
								tbody +=  "</tr>";
						}else{
							if(sugarTaxprepData.length){
								var checkYear = account_status_array[i]['year'];
								var sameYearCount = 0; var checkFlag = false;
								for(var j=0; j<sugarTaxprepData.length; j++){
									if(sugarTaxprepData[j]['tax_year_c']==account_status_array[i]['year']){
										checkFlag =true;
										if(checkYear==sugarTaxprepData[j]['tax_year_c'] && !sameYearCount){
											if(isNaN(sugarTaxprepData[j]['return_outcome_c'])){
												return_outcome = sugarTaxprepData[j]['return_outcome_c'];
											}
											else{
												return_outcome =parseFloat(sugarTaxprepData[j]['return_outcome_c']).toFixed(2);
												TotalAccuredBalance += return_outcome;
											}
											tbody +=  "<tr> <td class='td col1'>" + sugarTaxprepData[j]['tax_year_c'] + "</td>"
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>" 
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'></td>"
													+ "<td class='td col2'>"+return_outcome+"</td></tr>";
											sameYearCount++;
											if(sugarTaxprepData[j]['return_outcome_c'] > 0){
												dataCount=true;
											}
										}
									}
								}	
								if(!checkFlag){
									tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
											+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
											+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
											+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
									if(account_status_array[i]['assessed_balance'] >= 0)
									{
										tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
									}
									else
									{
										tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
									}
									if(account_status_array[i]['accrued_balance'] >= 0){
										TotalAccuredBalance += parseFloat(account_status_array[i]['accrued_balance']);
										if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
											
											total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
										}
										if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
											
											total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
										}
										tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
									}
									else{
										tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
									}
									tbody +=  "</tr>";
								}
							}
							else{
								tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
										+ "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
										+ "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
								if(account_status_array[i]['assessed_balance'] >= 0)
								{
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
								}
								else
								{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
								}
								if(account_status_array[i]['accrued_balance'] >= 0){
									TotalAccuredBalance += parseFloat(account_status_array[i]['accrued_balance']);
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
										
										total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
										
										total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
									}
									tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
								}
								else{
									tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
								}
								tbody +=  "</tr>";
							}
						}
						
					}
				}
				
			}
		}
		else{
			if(sugarTaxprepData.length)
			{
				var return_outcome = 0;
				for(var k=0; k<sugarTaxprepData.length; k++){
					if(isNaN(sugarTaxprepData[k]['return_outcome_c'])){
						return_outcome = sugarTaxprepData[k]['return_outcome_c'];
					}
					else{
						return_outcome =parseFloat(sugarTaxprepData[k]['return_outcome_c']).toFixed(2);
						TotalAccuredBalance += return_outcome;
					}
					tbody +=  "<tr> <td class='td col1'>" + sugarTaxprepData[k]['tax_year_c'] + "</td>"
							+ "<td class='td col2'></td>" 
							+ "<td class='td col2'></td>" 
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'>"+return_outcome+"</td></tr>";
				}
			}else{
				tbody += '<tr><td colspan="8" style="text-align: center;">No Data</td></tr>';
			}
		}				
		console.log('-----------------------------TotalAccuredBalance-------------------' , TotalAccuredBalance);
		console
		if(TotalAccuredBalance > 0)
		{
			tbody +=  "<tr> <td class='td col1'>Total</td>"
							+ "<td class='td col2'></td>" 
							+ "<td class='td col2'></td>" 
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'></td>"
							+ "<td class='td col2'>" + TotalAccuredBalance +"</td></tr>";
		}
		console.log('TotalAccuredBalance',TotalAccuredBalance);
		if(total_ths_balance > 0){
			
			$(".ths_bal").text('$'+total_ths_balance.toLocaleString());
		}
		if(total_ths_spbalance > 0){
			
			$(".ths_spbal").text('$'+total_ths_spbalance.toLocaleString());
		}
		$('.rec_tbody').html(tbody);		
		
	}
	
	/*function generateAccountStatusTable(len_as, account_status_array)
	{
		var tbody = '';
						
		for (var i = 0; i < len_as; i++) {
			
			tbody +=  "<tr> <td class='td col1'>" + account_status_array[i]['year'] + "</td>"
					  + "<td class='td col2'>" + account_status_array[i]['taxpayer_spouse'] + "</td>" 
					  + "<td class='td col2'>" + account_status_array[i]['return_filed'] + "</td>" 
					  + "<td class='td col2'>" + account_status_array[i]['filing_status'] + "</td>"
					  + "<td class='td col2'>" + account_status_array[i]['lien_active'] + "</td>"
					  + "<td class='td col2'>" + account_status_array[i]['examination_active'] + "</td>";
					  
			if(account_status_array[i]['assessed_balance'] >= 0)
			{
				tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['assessed_balance'] + "</td>";
			}
			else
			{
				tbody +=  "<td class='td col2'>" + account_status_array[i]['assessed_balance'] + "</td>";
			}
			if(account_status_array[i]['accrued_balance'] >= 0){
				
				if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
					
					total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
				}
				if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
					
					total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
				}
				tbody +=  "<td class='td col2'>" + '$'+account_status_array[i]['accrued_balance'] + "</td>";
			}
			else{
				tbody +=  "<td class='td col2'>" + account_status_array[i]['accrued_balance'] + "</td>";
			}
			tbody +=  "</tr>";
		}
		
		if(total_ths_balance > 0){
			
			$(".ths_bal").text('$'+total_ths_balance.toLocaleString());
		}
		if(total_ths_spbalance > 0){
			
			$(".ths_spbal").text('$'+total_ths_spbalance.toLocaleString());
		}
		$('.rec_tbody').html(tbody);		
		
	} */
	
	function generateResoCollActivityTable(len_activity, activity_array)
	{
		var tbody = '';
	
		for (var i = 0; i < len_activity; i++) {
			
			tbody +=  "<tr> <td class='td col1'>" + activity_array[i]['year'] + "</td>"
					  + "<td class='td col2'>" + activity_array[i]['last_activity'] + "</td>"
					  + "<td class='td col2'>" + activity_array[i]['last_activity_date'] + "</td>"
					+ "</tr>";
		}
		$('.activity_tbody').html(tbody);
	}
	
	function generatePenaltyInterestTable(len_p, penalty_array)
	{
		var tbody = '';
	
		for (var i = 0; i < len_p; i++) {
			
			tbody +=  "<tr> <td class='td col1'>" + penalty_array[i]['year'] + "</td>"
					  + "<td class='td col2'>" + penalty_array[i]['fta'] + "</td>"
					  + "<td class='td col2'>" + penalty_array[i]['pa'] + "</td>"
					  + "<td class='td col2'>" + penalty_array[i]['penalties_failure_to_file'] + "</td>"
					  + "<td class='td col2'>" + penalty_array[i]['penalties_failure_to_pay'] + "</td>"								  
						
					+ "</tr>";
		}
		$('.penalty_tbody').html(tbody);
	}
	
	function generateWageIncomeTable(len_cp, civil_penalty_array)
	{
		var tbody = ''; 
		var thead = '<tr>'; 
		
		var heads = civil_penalty_array[0];
		var k = 0;
		$.each( civil_penalty_array[0], function( key, value ) {
			if(value == 'Totals'){								
				var totals_year = key;
				return false;								
			}
			k = k + 1;
		});
		var latest_year = k - 1;
		
		thead +=  "<th class='td col1'>" + heads['description']+ "</th>"
					+ "<th class='td col1'>" + heads['year_1']+ "</th>"
					+ "<th class='td col1'>" + heads['year_2']+ "</th>"
					+ "<th class='td col1'>" + heads['year_3']+ "</th>"
					+ "<th class='td col1'>" + heads['year_4']+ "</th>"
					+ "<th class='td col1'>" + heads['year_5']+ "</th>"
					+ "<th class='td col1'>" + heads['year_6']+ "</th>"
					+ "<th class='td col1'>" + heads['year_7']+ "</th>"
					+ "<th class='td col1'>" + heads['year_8']+ "</th>"
					+ "<th class='td col1'>" + heads['year_9']+ "</th>"
					+ "<th class='td col1'>" + heads['year_10']+ "</th>";
		if(heads['year_11'] != ''){
			thead += "<th class='td col1'>" + heads['year_11']+ "</th>";
		}
		if(heads['year_12'] != ''){
			thead += "<th class='td col1'>" + heads['year_12']+ "</th>";
		}
		if(heads['year_13'] != ''){
			thead += "<th class='td col1'>" + heads['year_12']+ "</th>";
		}
		if(heads['year_14'] != ''){
			thead += "<th class='td col1'>" + heads['year_12']+ "</th>";
		}
		if(heads['year_15'] != ''){
			thead += "<th class='td col1'>" + heads['year_12']+ "</th>";
		}
					
		
		thead += "</tr>";
		$('#cp_heads').html(thead);
		
		var ly = 'year_' + latest_year;
								
		for (var i = 1; i < len_cp; i++) {
			
			if(civil_penalty_array[i]['description'] == 'Wages:'){
				wage_amt = civil_penalty_array[i][ly];								
					
				if(wage_amt > 0){
					$(".wg").text(wage_amt);
					//$("#ri").append(wage_amt);									
				}
			}
			
			if(civil_penalty_array[i]['description'] == 'Non-employee Compensation:'){
				if(civil_penalty_array[i]['year_1'] != '')
					var comp_amt = parseInt(civil_penalty_array[i]['year_1']) ;
				if(civil_penalty_array[i]['year_2'] != '')
					comp_amt += parseInt(civil_penalty_array[i]['year_2']);
				if(civil_penalty_array[i]['year_3'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_3']);
				if(civil_penalty_array[i]['year_4'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_4']);
				if(civil_penalty_array[i]['year_5'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_5']);
				if(civil_penalty_array[i]['year_6'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_6']);
				if(civil_penalty_array[i]['year_7'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_7']);
				if(civil_penalty_array[i]['year_8'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_8']);
				if(civil_penalty_array[i]['year_9'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_9']);
				if(civil_penalty_array[i]['year_10'] != '')	
					comp_amt += parseInt(civil_penalty_array[i]['year_10']);												
				if(civil_penalty_array[i]['year_11'] != ''){
					comp_amt += parseInt(civil_penalty_array[i]['year_11']);
				}
				if(civil_penalty_array[i]['year_12'] != ''){
					comp_amt += parseInt(civil_penalty_array[i]['year_12']);
				}
				if(civil_penalty_array[i]['year_13'] != '')
					comp_amt += parseInt(civil_penalty_array[i]['year_13']);
				if(civil_penalty_array[i]['year_14'] != '')
					comp_amt += parseInt(civil_penalty_array[i]['year_14']);
				if(civil_penalty_array[i]['year_15'] != '')
					comp_amt += parseInt(civil_penalty_array[i]['year_15']);
				
				if(comp_amt > 0){
					comp_amt = comp_amt / 2;
					$(".npc").text(comp_amt);
					//alert(comp_amt);
				}
			}
			
			if(civil_penalty_array[i]['description'] == 'Other Income:'){
				if(civil_penalty_array[i]['year_1'] != '')
					var other_inc = parseInt(civil_penalty_array[i]['year_1']) ;
				if(civil_penalty_array[i]['year_2'] != '')
					other_inc += parseInt(civil_penalty_array[i]['year_2']);
				if(civil_penalty_array[i]['year_3'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_3']);
				if(civil_penalty_array[i]['year_4'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_4']);
				if(civil_penalty_array[i]['year_5'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_5']);
				if(civil_penalty_array[i]['year_6'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_6']);
				if(civil_penalty_array[i]['year_7'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_7']);
				if(civil_penalty_array[i]['year_8'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_8']);
				if(civil_penalty_array[i]['year_9'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_9']);
				if(civil_penalty_array[i]['year_10'] != '')	
					other_inc += parseInt(civil_penalty_array[i]['year_10']);												
				if(civil_penalty_array[i]['year_11'] != ''){
					other_inc += parseInt(civil_penalty_array[i]['year_11']);
				}
				if(civil_penalty_array[i]['year_12'] != ''){
					other_inc += parseInt(civil_penalty_array[i]['year_12']);
				}
				if(civil_penalty_array[i]['year_13'] != '')
					other_inc += parseInt(civil_penalty_array[i]['year_13']);
				if(civil_penalty_array[i]['year_14'] != '')
					other_inc += parseInt(civil_penalty_array[i]['year_14']);
				if(civil_penalty_array[i]['year_15'] != '')
					other_inc += parseInt(civil_penalty_array[i]['year_15']);
				
				if(other_inc > 0){
					other_inc = other_inc / 2;
					$("#oi").text(other_inc);
					//alert(other_inc);
				}
			}
			
			if(civil_penalty_array[i]['description'] == 'Fair Market Value:'){
				if(civil_penalty_array[i]['year_1'] != '')
					var fmv = parseInt(civil_penalty_array[i]['year_1']) ;
				if(civil_penalty_array[i]['year_2'] != '')
					fmv += parseInt(civil_penalty_array[i]['year_2']);
				if(civil_penalty_array[i]['year_3'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_3']);
				if(civil_penalty_array[i]['year_4'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_4']);
				if(civil_penalty_array[i]['year_5'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_5']);
				if(civil_penalty_array[i]['year_6'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_6']);
				if(civil_penalty_array[i]['year_7'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_7']);
				if(civil_penalty_array[i]['year_8'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_8']);
				if(civil_penalty_array[i]['year_9'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_9']);
				if(civil_penalty_array[i]['year_10'] != '')	
					fmv += parseInt(civil_penalty_array[i]['year_10']);												
				if(civil_penalty_array[i]['year_11'] != ''){
					fmv += parseInt(civil_penalty_array[i]['year_11']);
				}
				if(civil_penalty_array[i]['year_12'] != ''){
					fmv += parseInt(civil_penalty_array[i]['year_12']);
				}
				if(civil_penalty_array[i]['year_13'] != '')
					fmv += parseInt(civil_penalty_array[i]['year_13']);
				if(civil_penalty_array[i]['year_14'] != '')
					fmv += parseInt(civil_penalty_array[i]['year_14']);
				if(civil_penalty_array[i]['year_15'] != '')
					fmv += parseInt(civil_penalty_array[i]['year_15']);
				
				if(fmv > 0){
					fmv = fmv / 2;
					$("#ra").text(fmv);
					//alert(other_inc);
				}
			}
			
			tbody +=  "<tr> <td class='td col1'>" + civil_penalty_array[i]['description'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_1'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_2'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_3'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_4'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_5'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_6'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_7'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_8'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_9'] + "</td>"
					  + "<td class='td col2'>" + civil_penalty_array[i]['year_10'] + "</td>";
			if(civil_penalty_array[i]['year_11'] != ''){
				tbody += "<td class='td col2'>" + civil_penalty_array[i]['year_11'] + "</td>";
			}
			if(civil_penalty_array[i]['year_12'] != ''){
				tbody += "<td class='td col2'>" + civil_penalty_array[i]['year_12'] + "</td>";
			}
			if(civil_penalty_array[i]['year_13'] != ''){
				tbody += "<td class='td col2'>" + civil_penalty_array[i]['year_13'] + "</td>";
			}
			if(civil_penalty_array[i]['year_14'] != ''){
				tbody += "<td class='td col2'>" + civil_penalty_array[i]['year_14'] + "</td>";
			}
			if(civil_penalty_array[i]['year_15'] != ''){
				tbody += "<td class='td col2'>" + civil_penalty_array[i]['year_15'] + "</td>";
			}									 
					 
			tbody += "</tr>";
		}
		$('.civilp_tbody').html(tbody);
	}
	
	function generateCSEDTable(len_csed, csed_array)
	{
		var tbody = '';
	
		for (var i = 0; i < len_csed; i++) {
			 
			tbody +=  "<tr> <td class='td col1'>" + csed_array[i]['year'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['assessment_code'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['assessment'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['assessment_date'] + "</td>";
			if(parseInt(csed_array[i]['assessment_amount']) >= 0){
				tbody +=  "<td class='td col2' style='text-align: center;'>" + '$'+csed_array[i]['assessment_amount'] + "</td>";
			}
			else{
				tbody +=  "<td class='td col2' style='text-align: center;'>" + csed_array[i]['assessment_amount'] + "</td>";
			}
			tbody += "<td class='td col2'>" + csed_array[i]['total_tolling_days'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['estimated_csed_date'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['three_year_look_back_date'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['two_year_filing_rule_date'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['two_forty_day_assessment_date'] + "</td>"
					  + "<td class='td col2'>" + csed_array[i]['eligible_bankruptcy_discharge_date'] + "</td>"
					+ "</tr>";
		}
		$('.csed_tbody').html(tbody);
	}
	
	function generateTHSSummaryTable(len_ths_array, ths_array)
	{
		var tbody = '';
	
		for (var i = 0; i < len_ths_array; i++) {
			
			tbody +=  "<tr>";
			
			if(ths_array[i]['year'] != "Totals"){
						
				tbody += "<td class='td col2'>" + ths_array[i]['year'] + "</td>";
				tbody += "<td class='td col2'>" + ths_array[i]['taxpayer_spouse'] + "</td>";
				
				if(parseInt(ths_array[i]['assessed_balance']) >= 0){
					tbody +=  "<td class='td col2' style='text-align: center;'>" + '$'+ths_array[i]['assessed_balance'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2' style='text-align: center;'>" + ths_array[i]['assessed_balance'] + "</td>";
				}
				if(parseInt(ths_array[i]['accrued_balance']) >= 0){
					tbody +=  "<td class='td col2' style='text-align: center;'>" + '$'+ths_array[i]['accrued_balance'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2' style='text-align: center;'>" + ths_array[i]['accrued_balance'] + "</td>";
				}
				tbody += "<td class='td col2'>" + ths_array[i]['filing_status'] + "</td>";
				
				if(ths_array[i]['estimated_csed_date'] != '' && ths_array[i]['estimated_csed_date'] != null){
					tbody +=  "<td class='td col2'>" + ths_array[i]['estimated_csed_date'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				tbody +=  "<td class='td col2'>" + ths_array[i]['return_filed'] + "</td>";
				
				if(ths_array[i]['fta'] != '' && ths_array[i]['fta'] != null){
					tbody +=  "<td class='td col2'>" + ths_array[i]['fta'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
								
				tbody +=  "<td class='td col2'>" + ths_array[i]['lien_active'] + "</td>";
				//tbody +=  "<td class='td col2'>" + ths_array[i]['wage'] + "</td>";

				if(ths_array[i]['1099CIncome'] != '' && ths_array[i]['1099CIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099CIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				//console.log(ths_array[i]['1099KIncome'] );
				if(ths_array[i]['1099KIncome'] != '' && ths_array[i]['1099KIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099KIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}

				if(ths_array[i]['SSAIncome'] != '' && ths_array[i]['SSAIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['SSAIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				if(ths_array[i]['1099MISCIncome'] != '' && ths_array[i]['1099MISCIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099MISCIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				if(ths_array[i]['1099RIncome'] != '' && ths_array[i]['1099RIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099RIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				if(ths_array[i]['1099RTaxableIncome'] != '' && ths_array[i]['1099RTaxableIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099RTaxableIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				} 
				if(ths_array[i]['1099GIncome'] != '' && ths_array[i]['1099GIncome'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['1099GIncome'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				if(ths_array[i]['W2Income'] != '' && ths_array[i]['W2Income'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['W2Income'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				if(ths_array[i]['TaxWithheld'] != '' && ths_array[i]['TaxWithheld'] != null){
					tbody +=  "<td class='td col2'>$" + ths_array[i]['TaxWithheld'] + "</td>";
				}
				else{
					tbody +=  "<td class='td col2'></td>";
				}
				
				tbody +=  "</tr>";
			}
			else{
				//tbody +=  "<td class='td col1'>" + ths_array[i]['year'] + "</td>";
			}
		}
		$('.tab_ths_summary_tbody').html(tbody);
	}
	
	function generateCaseReviewTable(len_casereview_array, casereview_array)
	{
		var tbody = '';
	
		for (var i = 0; i < len_casereview_array; i++) {
			
			tbody +=  '<tr>'+
							'<td colspan="4">Was there any change in original CR Recommendation/Price?</td>'+			
					  '</tr>'+
					  '<tr>'+
					  	'<td>Case Review Changes: Rec/Price</td>'+
					  	'<td>'+casereview_array[i]['crchanges_rec_price_c']+'</td>'+
					  	'<td>CR/Price Change: Reason & Details</td>'+
					  	'<td>'+casereview_array[i]['cr_change_details_c']+'</td>'+
					  '</tr>'+

					  '<tr>'+
					  	'<td colspan="4">Does the Client have any CONCERNS/QUESTIONS/GOALS the Tax Preparers/Practitioners should address or be aware of? AND/OR Any changes in the clients financial position that was not identified or known about at time of Initial Enrollment/CR?</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Client Concerns</td>'+
					  	'<td>'+casereview_array[i]['client_concerns_c']+'</td>'+
					  	'<td>Client Circumstance Changes/Concerns/Questions/Goals</td>'+
					  	'<td>'+casereview_array[i]['client_concerns_details_c']+'</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td colspan="4">Are there any Specific Federal &/or State Case "Flags" that we should be aware of? For Example: Levies, ROs, Deadlines, Suspensions, Immigration Etc</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Federal Case Flags</td>'+
					  	'<td>'+casereview_array[i]['fed_case_flags_c']+'</td>'+
					  	'<td>State Case Flags</td>'+
					  	'<td>'+casereview_array[i]['state_case_flags_c']+'</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Federal Case Flag Options</td>'+
					  	'<td>'+casereview_array[i]['federal_case_flag_options_c']+'</td>'+
					  	'<td>State Case Flag Options</td>'+
					  	'<td>'+casereview_array[i]['state_case_flag_options_c']+'</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Federal Deadline Reason</td>'+
					  	'<td>'+casereview_array[i]['cid_fed_deadline_reason_c']+'</td>'+
					  	'<td>State Deadline Reason</td>'+
					  	'<td>'+casereview_array[i]['cid_state_deadline_reason_c']+'</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Federal Deadline Date</td>'+
					  	'<td>'+casereview_array[i]['cid_fed_deadline_date_c']+'</td>'+
					  	'<td>State Deadline Date</td>'+
					  	'<td>'+casereview_array[i]['personal_state_deadline_date_c']+'</td>'+
					  '</tr>'+
					  '<tr>'+
					  	'<td>Client Temperament</td>'+
					  	'<td>'+casereview_array[i]['client_temperament_c']+'</td>'+
					  	'<td>&nbsp;</td>'+
					  	'<td>&nbsp;</td>'+
					  '</tr>';			
		}
		console.log(tbody);
		$('.tab_case_review_tbody').html(tbody);
	}
	
	function init() {
		$('.accordion-view').click(function() {
			$('#accordion').show()
			$('#tab_view').hide()
		})

		$('.tabs-view').click(function() {
			$('#tab_view').show()
			$('#accordion').hide()
		})

		$('.expand-all').click(function() {
			$('.panel-collapse').collapse('show')
		})

		$('.collapse-all').click(function() {
			$('.panel-collapse').collapse('hide')
		})
		show_data();
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
			<div class="container" style="margin-top:20px;">
	<div class="panel">

	<div class="panel-body">
		

		<div class="col-md-11" style="float: left" id="general_detail">
			<button class="btn btn-default accordion-view">Accordion view</button>
			<button class="btn btn-default tabs-view" style="margin-left: 5px;">Tabs view</button>
		</div> 
		
		<div class="col-md-1 close_rec">
			<input class="form-control" type="hidden" id="case_num" value="">
			<input class="form-control" type="hidden" id="old_case_num" value=""> 
			
			<input class="form-control" type="hidden" id="accounting_case_number" value=""> 
			<input class="form-control" type="hidden" id="monitoring_case_number" value=""> 

			<button class="btn btn-default" id="refresh_caseid_data" data-toggle="modal" onclick="refresh_data()">Refresh</button>
			<a href="<?php echo base_url();?>index.php/caselist/records" class="btn btn-default" id="hide_caseid_data" role="button">Close</a>
			<!--<button class="btn btn-default" id="hide_caseid_data" data-toggle="modal" onclick="hide_data()">Close</button> --> 		
		</div>
		
		
		<?php include('case-detail/view-accordion.php'); ?>
		
		<?php include('case-detail/view-tabs.php'); ?>

		
	</div>
	
	
	
	
</div>	

</div>
</div>
</div>

