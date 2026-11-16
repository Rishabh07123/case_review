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

	#assign_to_filter {
		width: 255px !important;
		overflow: hidden !important;
		margin-right: 20px !important;
	}

	#page-content-wrapper ul {
		display: inline-block;
	}
</style>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/widgets/datatable/datatable-bootstrap.js"> </script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/widgets/datatable/datatable-reorder.js"></script>

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
					body: function(data, row, column, node) {
						// Strip $ from salary column to make it numeric
						return column === 5 ?
							data.replace(/[$,]/g, '') :
							data;
					}
				},
				columns: [1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17] // Updated on 12-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements
			}
		}; //,17,18,19,20

		$('table.case_assign').DataTable({

			'columnDefs' : [
				{ 'visible': false, 'targets': [6] }
			],  // Added on 12-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements
			dom: 'Blfrtip',
			lengthMenu: [
				[50, 25, 10, -1],
				['50 rows', '25 rows', '10 rows', 'Show all']
			],
			buttons: [
				$.extend(true, {}, buttonCommon, {
					extend: 'excelHtml5',
					className: 'btn btn-primary'
				})
			]
		});
	});

	/* Datatables reorder */
	$(document).ready(function() {
		$('#datatable-reorder').DataTable({
			dom: 'Rlfrtip'
		});

		$('#datatable-reorder_length').hide();
		$('#datatable-reorder_filter').hide();

	});

	$(document).ready(function() {
		$('.dataTables_filter input').attr("placeholder", "Search...");
	});

	$(document).ready(function() {

		$('#selectAllSO').change(function() {

			if ($(this).prop('checked')) {

				$('#so_assign_tbody').find('td input:checkbox').prop('checked', 'checked');
			} else {

				$('#so_assign_tbody').find('td input:checkbox').prop('checked', false);
			}
		});

		renderSelect2();
	});

	//Code from SOAssignment
	function final_assignment() {

		var error = 0;
		var i = 0;
		var lead_array = [];
		var main_lead_array = [];

		var check = true;
		var check_case = false;
		var col_user = '';

		data_json = '';

		$(':checkbox:checked').each(function(i) {
			//alert($(this).val() );
			if ($(this).val() != "on") {

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
				//alert( $(this).val() + ', ' + col_user);
				//console.log(col_user + ', ' + check_case);
				if (col_user != '' && col_user != undefined && col_user != "null") {

					//console.log('check' + check);
				} else {
					//alert('else ' + col_user);
					check = false;
				}
				//console.log(lead_array);
				i = i + 1;
			}
		});
		//alert(col_user + ', ' + check);
		//return false;
		//console.log(data_json);

		if (check == false) {

			error = 1;
			alert('No temporary user found for selected case - ' + check_case);
			return false;
		}
		//var assign_user_id = col_user; //$('#final_assign_user_id').val();
		var batch_id = $('#batch_id').val();

		//Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
		var p3_batch_id = $('#p3_batch_id').val();
		var sub_status = $('#sub_status').val();

		if (p3_batch_id != '' || (sub_status != '' && sub_status == 'P3 Assignment Ready')) {
			var batch_flag = 'p3_batch_id';
		} else {
			var batch_flag = 'cr_batch_id';
		} // end Added on 26-10-2023

		if (lead_array.length < 1) {

			error = 1;
			alert('Please select a lead.');
			return false;
		}
		/*if(assign_user_id == ''){

			error = 1;
			alert('No temporary user found for selected case.');
			return false;
		}*/
		if (batch_id == '') {

			error = 1;
			alert('Please select a batch.');
			return false;
		}
		var table = $('.case_assign').DataTable(); //Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services 
		//return false;
		if (error == 0) {

			swal('Assign selected leads to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {

					$('#final_assign_to').attr("disabled", true);
					$("#reg_js_img").css('display', 'block');
					//lead_json = JSON.stringify(lead_array);
					jQuery.ajax({

						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/update_lead_assignment",

						data: {
							lead_array: data_json,
							batch_id: batch_id,
							batch_flag: batch_flag
						}, //assign_user_id: assign_user_id, //Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
						success: function(response) {
							$('#final_assign_user_id').val('');

							//response = JSON.parse(response);

							if (response == 1) { // .status == 'done'

								//update_lead_status(main_lead_array); // Commented on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14

								$("#reg_js_img").css('display', 'none');

								$.each(main_lead_array, function(key, value) {

									table.row("#" + value).remove().draw();
								}); // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14

								//location.reload();
								$.jGrowl("Lead assignment. updated successfully.", {
									sticky: false,
									position: 'top-right',
									theme: 'bg-green'
								});	 // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14						
							} 
							else {

								$.jGrowl("Unable to update lead assignment. Error - " + response, {
									sticky: true,
									position: 'top-right',
									theme: 'bg-red'
								});
								$("#reg_js_img").css('display', 'none');
							}
							$('#final_assign_to').attr("disabled", false);
						}
					})
				}
			})
		}
		$('#selectAllSO').prop('checked', false);
	}

	//Code from SOAssignment
	function assign_cases() {

		var error = 0;
		var lead_array = [];

		var select_lang = $('#language_filter :selected').text();
		var select_pay_status = $('#pay_status_filter :selected').text();
		var user_ids = $('.js-example-basic-single').val();
		var team_id = $('#team_filter :selected').val();

		$(':checkbox:checked').each(function(i) {

			lead_array[i] = $(this).val();
		});

		var assign_user_id = $('#selected_assign_user_id').val();

		if (lead_array.length < 1) {

			error = 1;
			alert('Please select a lead.');
			return false;
		}
		if (assign_user_id == '') {

			error = 1;
			alert('Please select a user.');
			return false;
		}

		if (error == 0) {

			swal('Assign selected leads to user?', {
				buttons: {
					cancel: true,
					confirm: true,
				},
				icon: "warning"
			}).then(function(isConfirm) {
				if (isConfirm) {

					$('#mass_assign_to').attr("disabled", true);
					$("#reg_js_img").css('display', 'block');

					jQuery.ajax({

						type: "POST",
						url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/tempAssignment", //update_lead_assignment

						data: {
							assign_user_id: assign_user_id,
							lead_array: lead_array
						},
						//async: false,
						success: function(response) {
							console.log('response1 - ' + response);
							$('#selected_assign_user_id').val('');
							$('#selected_assign_user_id').trigger('change');

							if (response != '' && response != 0) { //If Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
								//response = JSON.parse(response);  // Commented on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14

								//if(response.status == 'done')
								if(response == 1)  // Updated on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
								{ 

									$.jGrowl("Temporary assignment updated successfully.", {
										sticky: false,
										position: 'top-right',
										theme: 'bg-green'
									});
									$("#reg_js_img").css('display', 'none');
									//console.log(select_lang +select_pay_status );
									//location.reload();
									/*var select_lang = $('#language_filter :selected').text();
									var select_pay_status = $('#pay_status_filter :selected').text();
									var user_ids = $('.js-example-basic-single').val();
									var team_id = $('#team_filter :selected').val();*/

									setFilter(select_lang, select_pay_status);

								} 
								else {

									$.jGrowl("Unable to update lead assignment. Error - " + response, {
										sticky: true,
										position: 'top-right',
										theme: 'bg-red'
									});
									$("#reg_js_img").css('display', 'none');
								}
							} 
							else {
								$.jGrowl("Unable to update lead assignment. Missing Aggregate data.", {
									sticky: true,
									position: 'top-right',
									theme: 'bg-red'
								});
								$("#reg_js_img").css('display', 'none');

							} //Else added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
							$('#mass_assign_to').attr("disabled", false);
						}
					})
				}
			})
		}
		//console.log(select_lang +select_pay_status );

	}

	function setFilter(select_lang, select_pay_status) {
		//alert(select_lang + ', '+ select_pay_status);
		//var select_lang = $('#language_filter :selected').text();
		//var select_pay_status = $('#pay_status_filter :selected').text();
		var user_ids = ''; //$('.js-example-basic-single').val();
		var team_id = ''; //$('#team_filter :selected').val();
		var final_recomm = '';
		var cr_batch_id = '';
		//alert(team_id);
		//return 1;
		//$.cookie('language', select_lang);
		//$.cookie('pay_status', select_pay_status);
		$("#load_img").css('display', 'block');
		console.log('user_ids - ' + user_ids);
		//return true;
		jQuery.ajax({

			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/get_filtered_data",

			data: {
				language: select_lang,
				pay_status: select_pay_status,
				user_ids: user_ids,
				team_id: team_id,
				final_recomm: final_recomm,
				cr_batch_id: cr_batch_id
			}, //, no_payments_made: no_payments_made
			success: function(response) {
				var table = $('.case_assign').DataTable();

				if (response != '') {

					var tbody = '';
					var response_array = $.parseJSON(response);
					var len = response_array.length;
					//console.log(response_array);
					var table = $('.case_assign').dataTable();
					table.fnClearTable();

					for (var i = 0; i < len; i++) {

						var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['username'], response_array[i]['temp_user'], response_array[i]['lead_level_c'] , response_array[i]['custom_status_c'], response_array[i]['name'], response_array[i]['case_id_c'],  "<a href='<?= $this->config->item('sugar_url'); ?>#Leads/"+response_array[i]['id']+"' target='_blank'>" +response_array[i]['case_id_c']+"</a>", response_array[i]['cr_fee_c'] , response_array[i]['add_service_fee_c'], response_array[i]['language_prequal_c'], response_array[i]['billing_status_c'], response_array[i]['percentage_paid_c'],  response_array[i]['payment_date'], response_array[i]['investigation_type_c'], response_array[i]['initial_fee'], response_array[i]['lender'],  response_array[i]['final_recommendation_type_c'], response_array[i]['cr_batch_id_c'], response_array[i]['add_service_source_c'], response_array[i]['previousUser']]); //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  // // Updated on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record

						//table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['username'], response_array[i]['temp_user'], response_array[i]['lead_level_c'] , response_array[i]['custom_status_c'], response_array[i]['logics_status_c'], response_array[i]['name'],  response_array[i]['case_id_c'], response_array[i]['cr_fee_c'] , response_array[i]['language_prequal_c'], response_array[i]['billing_status_c'], response_array[i]['percentage_paid_c'],  response_array[i]['payment_date'], response_array[i]['lending_partner_eligibility_c'],  response_array[i]['net_approved'], response_array[i]['investigation_type_c'], response_array[i]['initial_fee'], response_array[i]['lender'], response_array[i]['spl_campaign'],  response_array[i]['final_recommendation_type_c'], response_array[i]['cr_batch_id_c'], response_array[i]['sale_date']]); // sale_date added on 30-03-2023 https://trello.com/c/RZIrxYQt/275-new-column-needed-in-the-cr-assignment-tool //Commented on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services

						//response_array[i]['lender'],

						var theNode = table.fnSettings().aoData[rowNode].nTr;
						theNode.setAttribute('id', response_array[i]['id']);

						$('td', theNode)[2].setAttribute('id', response_array[i]['user_id7_c']);
					}
					$("#load_img").css('display', 'none');
				} else {
					alert("No record with given filters found.");
					$("#load_img").css('display', 'none');
				}
			}
		})

	}
	/*function update_lead_status(lead_array){
		alert(1);
		console.log('1');
		$('#mass_assign_to').attr("disabled", true);
		//$('#complete_assignment').attr("disabled", true);
		$("#reg_js_img").css('display','block');

		var table=$('.case_assign').DataTable();

		jQuery.ajax({

			type: "POST",
			url: "<?php //echo base_url(); 
					?>" + "index.php/CaseAssignment/update_lead_assignment_status",

			data: {lead_array: lead_array},
			success: function(response)
			{
				$('#selected_assign_user_id').val('');
				$('#selected_assign_user_id').trigger('change');

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

					$.jGrowl("Unable to update lead assignment", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-red'
					});
					$("#reg_js_img").css('display','none');
				}
				$('#mass_assign_to').attr("disabled", false);
				//$('#complete_assignment').attr("disabled", false);
			}
		})
	}*/

	//Filter Code
	function update_lead_status(lead_array) {
		//alert(2);
		//console.log('2');
		$('#final_assign_to').attr("disabled", true);
		//$('#complete_assignment').attr("disabled", true);
		$("#reg_js_img").css('display', 'block');

		var table = $('.case_assign').DataTable();

		jQuery.ajax({

			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/update_lead_assignment_status",

			data: {
				lead_array: lead_array
			},
			success: function(response) {
				//$('#selected_assign_user_id').val('');
				//$('#selected_assign_user_id').trigger('change');
				//console.log(response);
				response = JSON.parse(response);
				//console.log(response.status);
				if (response.status == 'done') {

					$.jGrowl("Lead assignment updated successfully.", {
						sticky: false,
						position: 'top-right',
						theme: 'bg-green'
					});
					$("#reg_js_img").css('display', 'none');

					//location.reload();

					$.each(lead_array, function(key, value) {

						table.row("#" + value).remove().draw();
					});
				} else {

					$.jGrowl("Unable to update lead assignment. Error - " + response, {
						sticky: false,
						position: 'top-right',
						theme: 'bg-red'
					});
					$("#reg_js_img").css('display', 'none');
				}
				$('#final_assign_to').attr("disabled", false);
				$('#batch_id').val('');
				//$('#complete_assignment').attr("disabled", false);
			}
		})
	}

	//Filter Code
	function get_filtered_data(data) {

		var select_lang = $('#language_filter :selected').text();
		var select_pay_status = $('#pay_status_filter :selected').text();
		var user_ids = $('.js-example-basic-single').val();
		var team_id = $('#team_filter :selected').val();
		var final_recomm = $('#final_recomm_type :selected').val();
		var cr_batch_id = $('#cr_batch_id :selected').val();
		var p3_batch_id = $('#p3_batch_id :selected').val(); //Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
		var sub_status = $('#sub_status :selected').val();
		var no_payments_made = $('#no_payments_made :selected').val();

		//alert(team_id);
		//return 1;
		//$.cookie('language', select_lang);
		//$.cookie('pay_status', select_pay_status);
		$("#load_img").css('display', 'block');
		console.log('user_ids - ' + user_ids);
		//return true;
		jQuery.ajax({

			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/CaseAssignment/get_filtered_data",

			data: {
				language: select_lang,
				pay_status: select_pay_status,
				user_ids: user_ids,
				team_id: team_id,
				final_recomm: final_recomm,
				cr_batch_id: cr_batch_id,
				p3_batch_id: p3_batch_id,
				sub_status: sub_status,
				no_payments_made: no_payments_made
			}, // p3_batch_idAdded on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
			success: function(response) {
				var table = $('.case_assign').DataTable();

				if (response != '') {

					var tbody = '';
					var response_array = $.parseJSON(response);
					var len = response_array.length;
					//console.log(response_array);
					var table = $('.case_assign').dataTable();
					table.fnClearTable();
					var addServiceFee = ""; // Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements
					var crFee = ""; // Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements

					// Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
					if (sub_status == 'P3 Assignment Ready') {
						//$("#label_cr_fee").html('Add Service Fee'); // Commented on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements
						$("#label_final_add_fee").html('Final Add Fee Recommendation Type');
						$("#label_batch_id").html('P3 Batch ID');
					} else {
						//$("#label_cr_fee").html('CR Fee'); // Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements
						$("#label_final_add_fee").html('Final Recommendation Type');
						$("#label_batch_id").html('CR Batch ID');
					}
					// end Added on 26-10-2023

					for (var i = 0; i < len; i++) {

						// Added on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
						if (sub_status == 'P3 Assignment Ready' || p3_batch_id != '') {
							$("#label_batch_id").html('P3 Batch ID');
							var cr_p3_batch_id = response_array[i]['p3_batch_id_c']
						} else {
							$("#label_batch_id").html('CR Batch ID');
							var cr_p3_batch_id = response_array[i]['cr_batch_id_c']
						}

						var rowNode = table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['username'], response_array[i]['temp_user'], response_array[i]['lead_level_c'], response_array[i]['custom_status_c'], response_array[i]['name'], response_array[i]['case_id_c'], "<a href='<?= $this->config->item('sugar_url'); ?>#Leads/" + response_array[i]['id'] + "' target='_blank'>" + response_array[i]['case_id_c'] + "</a>", response_array[i]['cr_fee_c'], response_array[i]['add_service_fee_c'], response_array[i]['language_prequal_c'], response_array[i]['billing_status_c'], response_array[i]['percentage_paid_c'], response_array[i]['payment_date'], response_array[i]['investigation_type_c'], response_array[i]['initial_fee'], response_array[i]['lender'], response_array[i]['final_recommendation_type_c'], cr_p3_batch_id, response_array[i]['add_service_source_c'], response_array[i]['previousUser']]);
						// end Added on 26-10-2023  // Updated on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record // Updated on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements

						//table.fnAddData(["<input type='checkbox' name='selector[]' id='" + response_array[i]['id'] + "' class='assignment_cases' value='" + response_array[i]['id'] + "' class='assignment_cases'>", response_array[i]['username'], response_array[i]['temp_user'], response_array[i]['lead_level_c'] , response_array[i]['custom_status_c'], response_array[i]['logics_status_c'], response_array[i]['name'],  response_array[i]['case_id_c'], response_array[i]['cr_fee_c'] , response_array[i]['language_prequal_c'], response_array[i]['billing_status_c'], response_array[i]['percentage_paid_c'], response_array[i]['payment_date'], response_array[i]['lending_partner_eligibility_c'],  response_array[i]['net_approved'], response_array[i]['investigation_type_c'], response_array[i]['initial_fee'], response_array[i]['lender'], response_array[i]['spl_campaign'], response_array[i]['final_recommendation_type_c'], response_array[i]['cr_batch_id_c'], response_array[i]['sale_date']]);  // sale_date added on 30-03-2023 https://trello.com/c/RZIrxYQt/275-new-column-needed-in-the-cr-assignment-tool

						//response_array[i]['lender'],

						var theNode = table.fnSettings().aoData[rowNode].nTr;
						theNode.setAttribute('id', response_array[i]['id']);

						$('td', theNode)[2].setAttribute('id', response_array[i]['user_id7_c']);
					}
					$("#load_img").css('display', 'none');
				} else {
					alert("No record with given filters found.");
					$("#load_img").css('display', 'none');
				}
			}
		})
	}

	function renderSelect2() {
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
						<div id="load_img" style="display: none;"><img src="<?php echo base_url(); ?>assets/images/icons/spinner.gif" /></div>
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
							<option value="No Payments Made">No Payments Made</option>
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
						<select class="js-example-basic-single form-control" id="assign_to_filter" name="assigned_filter[]" multiple="multiple" style="border: #dfe8f1 solid 1px; margin-right: 20px;">
							<!--<select class="js-example-basic-single form-control" id="form_name" name="form_names[]" multiple="multiple" style="width: 100%; border: #dfe8f1 solid 1px;" >-->
							<option value=""></option>
							<?php foreach ($user_list as $val) { ?>

								<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
							<?php } ?>
						</select>

						<!-- <button type="button" id="filter_button" class="btn btn-primary" onclick="get_filtered_data(this);" style="margin-left: 20px;">Filter</button> -->
					</div>
					<div class="col-sm-12" id="filters" style="display: flex; margin-bottom: 30px;margin-left: 84px;">
						<font color="black" style="padding-right: 20px; padding-top: 3px; ">Final Recommendation Type</font>
						<select class="form-control" id="final_recomm_type" style="width: 10%;  margin-right: 40px;">
							<option value=""></option>
							<option value="Financial Hardship Plan">Financial Hardship Plan</option>
							<option value="Financial Privacy Plan">Financial Privacy Plan</option>
							<option value="Income Based Repayment Plan">Income Based Repayment Plan</option>
							<option value="Offer In Compromise">Offer In Compromise</option>
							<option value="TP Only">TP Only</option>
							<option value="No Rec">No Rec</option>
							<option value="Monitoring">Monitoring</option>

						</select>

						<font color="black" style="padding-right: 20px; padding-top: 3px; ">CR Batch ID</font>
						<select class="form-control" id="cr_batch_id" style="width: 10%;  margin-right: 40px;">
							<option value=""></option>
							<?php foreach ($batch_list as $key => $val) { ?>

								<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
							<?php } ?>
						</select>
						<!-- // Added on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
						<font color="black" style="padding-right: 20px; padding-top: 3px; ">P3 Batch ID</font>
						<select class="form-control" id="p3_batch_id" style="width: 10%;  margin-right: 40px;">
							<option value=""></option>
							<?php foreach ($batch_list as $key => $val) { ?>

								<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
							<?php } ?>
						</select>
						<!-- // end Added on 26-10-2023 -->
						<font color="black" style="padding-right: 20px; padding-top: 3px; ">Sub Status</font>
						<select class="form-control" id="sub_status" name="sub_status" style="width: 10%;  margin-right: 40px;"> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
							<option value=""></option>
							<option value="Assignment Ready">Assignment Ready</option>
							<option value="P3 Assignment Ready">P3 Assignment Ready</option>
						</select>

						<!--<font color="black" style="padding-right: 20px; padding-top: 3px; ">No Payments Made</font>
            <select class="form-control" id="no_payments_made" style="width: 10%;  margin-right: 40px;" >
                <option value=""></option>
                <option value="Loan">Loan</option>
                <option value="Soft Approval">Soft Approval</option>
                <option value="Pre Approved">Pre Approved</option>
                <option value="all_above">All Of The Above</option>

            </select> -->

						<button type="button" id="filter_button" class="btn btn-primary" onclick="get_filtered_data(this);" style="margin-left: 20px;">Filter</button>
					</div>

					<div class="col-sm-12">
						<div id="reg_js_img" style="display: none;"><img src="<?php echo base_url(); ?>assets/images/icons/p1_new.gif" /></div>

						<?php if ($so_assignments == 0) { ?>
							<label class="col-sm-3 control-label">No Cases Found</label>
						<?php } else { ?>

							<table id="datatable-tabletools" class="table table-striped table-bordered case_assign" cellspacing="0" width="100%">
								<!-- style="float: left;  max-height: 400px; overflow-y: scroll;" -->
								<thead>
									<tr style="text-align: center">
										<th>Action <input type="checkbox" id="selectAllSO" class="assignment_cases" /></th>
										<th>Assigned To</th>
										<th>Temp User</th>
										<th>Lead Level</th>
										<th>Sub Status</th>
										<!-- <th>Logics Status</th> --> <!-- // Commented on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
										<th>Client Name</th>
										<th>Client Id.</th> <!-- Added on 12-01-2024   -->
										<th>Client Id.</th>
										<th id="label_cr_fee">CR Fee</th> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
										<th>Add Service Fee</th> <!--  Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements -->
										<th>Client Language</th>
										<th>Billing Status</th>
										<th>Percentage Paid</th>
										<th>Next Payment Date</th>
										<!-- <th>Lending Partner Eligibility</th>
						<th>Approved Lender Amount</th> --> <!-- // Commented on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
										<th>Investigation Type</th>
										<th>Initial Fee</th>
										<th>Lender</th>
										<!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
										<!-- <th>Spl. Campaign</th> -->
										<th id="label_final_add_fee">Final Recommendation Type</th>
										<th id="label_batch_id">CR Batch ID</th>
										<th>Add Service Source</th> <!--  Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record -->
										<th>Previous Assigned User</th> <!--  Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements -->
										<!-- <th>Sale Date</th> --> <!-- sale_date added on 30-03-2023 https://trello.com/c/RZIrxYQt/275-new-column-needed-in-the-cr-assignment-tool -->
										<!-- // Updated on 26-10-2023 -->
									</tr>
								</thead>
								<tbody id="so_assign_tbody">

									<?php
									foreach ($so_assignments as $val) { ?>

										<tr id="<?php echo $val->id; ?>">
											<td><input type="checkbox" name="selector[]" id="<?php echo $val->id; ?>" class="assignment_cases" value="<?php echo $val->id; ?>" class="assignment_cases"></td>

											<td><?php echo $val->username; ?></td>
											<td id='<?php echo $val->user_id7_c; ?>'><?php echo $val->temp_user; ?> </td>
											<td><?php echo $val->lead_level_c; ?></td>
											<td><?php echo $val->custom_status_c; ?></td>
											<!-- <td><?php //echo $val->logics_status_c; 
														?></td> --> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
											<td><?php echo $val->name; ?></td>
											<td><?php echo $val->case_id_c;
														?></td> <!--  Uncommented on 12-01-2024 -->
											<td><a href="<?= $this->config->item('sugar_url'); ?>#Leads/<?= $val->id ?>" target="_blank"><?php echo $val->case_id_c; ?></a></td><!--  Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements -->
											<td><?php echo $val->cr_fee_c; ?></td>
											<td><?php echo $val->add_service_fee_c; ?></td> <!--  Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements -->
											<td><?php echo $val->language_prequal_c; ?></td>
											<td><?php echo $val->billing_status_c; ?></td>
											<td><?php echo $val->percentage_paid_c; ?></td>
											<td><?php echo $val->payment_date; ?></td>
											<!-- <td><?php //echo $val->lending_partner_eligibility_c; 
														?></td> --> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
											<!-- <td><?php //echo $val->net_approved; 
														?></td> --> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
											<td><?php echo $val->investigation_type_c; ?></td>
											<td><?php echo $val->initial_fee; ?></td>
											<td><?php echo $val->lender; ?></td>
											<!-- <td><?php //echo $val->spl_campaign; 
														?></td> --> <!-- // Updated on 26-10-2023  https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  -->
											<td><?php echo $val->final_recommendation_type_c; ?></td>
											<td><?php echo $val->cr_batch_id_c; ?></td>
											<td><?php echo $val->add_service_source_c; ?></td> <!--  Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record -->
											<!-- <td><?php echo $val->sale_date; ?></td> --> <!--  added on 30-03-2023 https://trello.com/c/RZIrxYQt/275-new-column-needed-in-the-cr-assignment-tool -->

											<!--<td><?php //if($val->lead_src == "Emersive Marketing") { //echo "Bobby"; } 
													?></td>-->
											<td><?php echo $val->previousUser; ?></td> <!--  Added on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements -->


										</tr>
									<?php } ?>



								</tbody>
							</table>


							<div id="assign">

								<select class="form-control select2" id="selected_assign_user_id">
									<option></option>
									<?php foreach ($user_list as $val) { ?>

										<option value="<?php echo $val->id; ?>"><?php echo $val->uname; ?></option>
									<?php } ?>
								</select>

								<!--<button type="button" id="mass_so_assign_to" class="btn btn-primary" onclick="move_to_assignment();">Move To SO Assignment</button> -->

								<button type="button" id="mass_assign_to" class="btn btn-primary" onclick="assign_cases();">Temp Assignment</button>

							</div>

							<div id="final_assign">

								<select class="form-control select_batch" id="batch_id">

									<?php foreach ($batch_list as $key => $val) { ?>

										<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
									<?php } ?>
									<!--<option value="Spanish">Spanish</option>
					<option value="2020Week30">20-Week 30</option>
					<option value="2020Week31">20-Week 31</option>
					<option value="2020Week32">20-Week 32</option>
					<option value="2020Week33">20-Week 33</option>
					<option value="2020Week34">20-Week 34</option>
					<option value="2020Week35">20-Week 35</option>
					<option value="2020Week36">20-Week 36</option>
					<option value="2020Week37">20-Week 37</option>
					<option value="2020Week38">20-Week 38</option>
					<option value="2020Week39">20-Week 39</option>
					<option value="2020Week40">20-Week 40</option>
					<option value="2020Week41">20-Week 41</option>
					<option value="2020Week42">20-Week 42</option>
					<option value="2020Week43">20-Week 43</option>
					<option value="2020Week44">20-Week 44</option>
					<option value="2020Week45">20-Week 45</option>
					<option value="2020Week46">20-Week 46</option>
					<option value="2020Week47">20-Week 47</option>
					<option value="2020Week48">20-Week 48</option>
					<option value="2020Week49">20-Week 49</option>
					<option value="2020Week50">20-Week 50</option>
					<option value="2020Week51">20-Week 51</option>
					<option value="2020Week52">20-Week 52</option>-->


								</select>

								<!--<select class="form-control select2" id="final_assign_user_id">
					<option></option>
					<?php	//foreach($user_list as $val){ 
					?>

						<option value="<?php //echo $val->id; 
										?>"><?php //echo $val->uname; 
																	?></option>
					<?php //}
					?>
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