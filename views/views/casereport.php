<?php //header('Access-Control-Allow-Origin: *');
$current_ip = $this->input->ip_address();
$allow = $this->config->item('allowed_ips');
if(!in_array($current_ip, $allow)) 
{
	print "403 forbidden";
	exit();
}
?>
		
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datepicker-ui/datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>
			
<script type="text/javascript">
	$(function() { "use strict";
		$('#trcdval').datepicker({});
		$('#lcdval').datepicker({});
		$('#indval').datepicker({});
	});	
	
	
	$(document).ready(function() {
		
		init()
	});	
		
		
	//MAIN FUNCTION TO DISPLAY ALL DATA
	
	function show_data(caseid)
	{
		var case_id = caseid;
				
		if(case_id == "")
		{
			alert("CaseID not found");
			return false;
		}
		else
		{
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casereport/get_data",				
				data: {caseid: case_id},
				
				success: function(res)              
				{
					console.log(res);
					var jsonData = $.parseJSON(res);
					
					var irs_standards = jsonData.irs_standards;
					
					if(jsonData.reported_inc){
						//alert('reported_income1 '+jsonData.reported_inc);
                        var reported_income = jsonData.reported_inc;						
						var len_reported_income = reported_income.length;						
                    }
					/*if(jsonData.notes){
						
						var notes_array = jsonData.notes;					
						var len_notes = notes_array.length;
					} 
					 */  	
					if(jsonData.notes){
						
						var review_notes_array = jsonData.notes;					
						var len_review_notes = review_notes_array.length;
					}
					if(jsonData.pps_notes){
						
						var pps_notes_array = jsonData.pps_notes;					
						var len_pps_notes = pps_notes_array.length;
					}
					if(jsonData.pps_notes_spouse){
						
						var pps_notes_spouse_array = jsonData.pps_notes_spouse;
						var len_pps_notes_spouse = pps_notes_spouse_array.length;
					}
					
					if(jsonData.summary){
						
						var summary_array = jsonData.summary;					
						var len_summary = summary_array.length;						
					}
					//console.log(jsonData.aggregate)
					if(jsonData.aggregate){
						
						var aggregate_array = jsonData.aggregate;					
						var len_aggregate = aggregate_array.length;						
					}
					if(jsonData.pps){
						
						var pps_array = jsonData.pps;					
						var len_pps = pps_array.length;
					}   
					if(jsonData.pps_spouse){
						
						var spouse_pps_array = jsonData.pps_spouse;					
						var len_pps_spouse = spouse_pps_array.length;
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
						console.log('sugar_irs_standards ' + sugar_irs_standards_array);
					} 
					if(jsonData.additional_expense){ 
						
						var additional_expense_array = jsonData.additional_expense;					
						var len_additional_expense = additional_expense_array.length
					}
					if(jsonData.services){ 
						
						var servicese_array = jsonData.services;					
						var service_len = servicese_array.length
					}
					if(jsonData.pps_summary){
						
						var pps_summary_array = jsonData.pps_summary;					
						var len_pps_summary = pps_summary_array.length;						
					}
					if(jsonData.finance){
						
						var finance_array = jsonData.finance;					
						var len_finance = finance_array.length;						
					}
					if(jsonData.status){
							
						var status_text = jsonData.status ;
						//alert(status_text);
					}
					if(jsonData.account_stat_data){
 
                        var account_status_array = jsonData.account_stat_data;              
                        var len_as = account_status_array.length;
                    }
					//DISPLAY DATA FOR SUMMARY TAB
					
					//Summary and general details
					var add_inc = 0;
					if(jsonData.additional_income){
						
						add_inc = jsonData.additional_income;
					}
					if(add_inc == '' || add_inc == null){
								
						add_inc = 0;
					}
					
					if(len_fin_overview)
					{
						if(fin_overview_array[0]['primary_tax_payer_before_tax_c'] == '' || fin_overview_array[0]['primary_tax_payer_before_tax_c'] == null){
									
							fin_overview_array[0]['primary_tax_payer_before_tax_c'] = 0;
						}
						if(fin_overview_array[0]['spouseco_spouse_before_tax_c'] == '' || fin_overview_array[0]['spouseco_spouse_before_tax_c'] == null){
									
							fin_overview_array[0]['spouseco_spouse_before_tax_c'] = 0;
						}
						var inc_pers_net = parseFloat(fin_overview_array[0]['primary_tax_payer_before_tax_c']) + parseFloat(fin_overview_array[0]['spouseco_spouse_before_tax_c']) + parseFloat(add_inc) ;
										
						var si_val =  inc_pers_net;
						if(si_val > 0)
							$(".si").text('$' + si_val.toLocaleString());
					}
					if(len_aggregate){	
						
						if(aggregate_array[0]['prelim_fee_c'] > 0)
						{
							$('.prelim_fee').text('$' + aggregate_array[0]['prelim_fee_c']);
						}
						$('.spec_service').text('$' + aggregate_array[0]['specified_services_c']);
						$('.prelim_rec').text(aggregate_array[0]['prelim_recommendation_type_c']);						
					}
					
					//summary finance status fields
					if(len_summary){
					
											
						for (var i = 0; i < len_summary; i++) {
							
							var client_name = summary_array[i]['FirstName'] + ' ' + summary_array[i]['LastName'] ;
							var status = summary_array[i]['StatusID'] ;
							var state = summary_array[i]['State'] ;
							var final_recmd_type = summary_array[i]['final_recommendation_type_c'] ;
							
							if(len_reported_income > 0)
							{								
								if(reported_income[0]['rep_inc_spouse']){
									
									var rep_inc = parseInt(reported_income[0]['rep_inc'])    
									              + parseInt(reported_income[0]['rep_inc_spouse']);
								}
								else
									var rep_inc = parseInt(reported_income[0]['rep_inc']) ;
																
								if(rep_inc > 0){
									
									$('#ri').text('$'+rep_inc.toLocaleString());
								}
							}
						}	
						
						//show general details  
						var caseid_text = 'Caseid#:   ' + case_id;
						var name_text = 'Name:   ' + client_name ;
						var today = new Date();
						var dd = today.getDate();
						var mm = today.getMonth()+1; //January is 0!
						var yyyy = today.getFullYear();
						
						$('.final_rec').text(summary_array[0]['final_recommendation_type_c']);
						
						if(dd<10) {
							dd = '0'+dd
						} 

						if(mm<10) {
							mm = '0'+mm
						} 

						today = mm + '/' + dd + '/' + yyyy;
						
						status_text1 = 'Status: ' + status_text;
						//alert(status_text1);
						var html = '<input class="form-control1 casenum" type="text" id="caseno" value="'+ caseid_text + '"  disabled>';
						
						html += '<input class="form-control1 clientname" type="text" id="cname" value="' +  name_text +'"  disabled>';

						html += '<input class="form-control1 clientdate" type="text" id="cdate" value="' +  today +'" style="color: #000; font-weight: 800;" disabled>';
						
						html += '<input class="form-control1 clientstatus" type="text" id="cstatus" value="' +  status_text1 +'" style="color: #000; font-weight: 800;" disabled>';
						
						html += '<br>';
						
						$('#general_detail').append(html);
						
						if(summary_array[0]['cr_fee_c'] > 0)
						{
							$('.input_cr_fee').text('$' + summary_array[0]['cr_fee_c']);
						}						
						
					}		
					
					if(len_pps_summary)
					{						
						var exp_analysis = pps_summary_array[0]['expense_analysis_c'];
						var inc_analysis = pps_summary_array[0]['income_analysis_c'];
						var asset_analysis = pps_summary_array[0]['asset_analysis_c'];
						var account_status = pps_summary_array[0]['account_status_c'];
						var compliance_summary = pps_summary_array[0]['compliance_summary_c'];
						var di = pps_summary_array[0]['disposable_income_c'];
						var pps_instruction = pps_summary_array[0]['pps_instructions_c'];
						
						
						if(pps_instruction != null) {
							
							$("#pps_instruct_note").val(pps_instruction);
						}
						
						if(di != null) {
							
							$(".di").text('$' + di);
						}
						
						if(compliance_summary != null){
							
							$(".compliance_sum").text(compliance_summary);
						}
						if(compliance_summary != null){
							
							$(".ac_stat").text(account_status);	
						}
						if(exp_analysis != null){
							
							$(".analysis1").text(exp_analysis);	
						}
						if(inc_analysis != null){
							
							$(".analysis2").text(inc_analysis);	
						}
						if(asset_analysis != null){
							
							$(".analysis3").text(asset_analysis);	
						}
					}					
					
					if(len_notes){
						
						if(notes_array[0]['Notes'] != null){
							
							var notes_arr = notes_array[0]['Notes'].split("\n");
							var len_notes = notes_arr.length;
							var pnote = '';
							for (var i = 0; i < len_notes; i++) {
								if(notes_arr[i] == "" || notes_arr[i] == " "){
									//console.log(notes_arr[i]);
								}
								else{
									pnote += "<p>"+ notes_arr[i] + "<br>";	
								}							
							}
							pnote += "</p>";
							document.getElementById("pn").insertAdjacentHTML('afterbegin', pnote);
							
						}
					} 
					
					if(len_review_notes){			
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_review_notes; i++) {
							
							//updated on 29-09-2022 https://trello.com/c/PCHnpoSI/1589-cr-portal-to-sugar-cr-note-formatting 
							//pnote +=  "<p>"+ review_notes_array[i]['note_text'] + "<br>" + review_notes_array[i]['note_datecreated']; 
							pnote +=  "<p><textarea disabled style='width:100%; font-size:15px; color: black;'>"+ review_notes_array[i]['note_text'] + "\n" + review_notes_array[i]['note_datecreated']; 
							pnote +=  "</textarea></p>"; // Updated on 17-11-2022
							//end updated on 29-09-2022
						}
						//pnote += "</p>"; //commented on 29-09-2022 https://trello.com/c/PCHnpoSI/1589-cr-portal-to-sugar-cr-note-formatting 
						
						if(pnote != null){
							
							//document.getElementById("note_msg2").insertAdjacentHTML('afterbegin', pnote); //commented on 29-09-2022 https://trello.com/c/PCHnpoSI/1589-cr-portal-to-sugar-cr-note-formatting 
							document.getElementById("note_msg2").innerHTML = pnote; //added on 29-09-2022 https://trello.com/c/PCHnpoSI/1589-cr-portal-to-sugar-cr-note-formatting 
							//Added on 17-11-2022 
							$(function() {
								$('textarea').each(function() {
									$(this).height($(this).prop('scrollHeight'));
								});
							}); // end Added on 17-11-2022 
						}  
					}
					
					if(len_finance){
						
						var pnote = '';
						var html = '';
						var html_tab = '';
						
						for (var i = 0; i < len_finance; i++) {							
							
							pnote +=  "<pre>";
							
							if(finance_array[i]['finance_notes_c'] != '' && finance_array[i]['finance_notes_c'] != null){
								
								pnote +=  finance_array[i]['finance_notes_c'];
							}
							else{
								pnote += '';
							}
							pnote +=  "<br><b>Program Type: </b>" + finance_array[i]['program_type_c'] + "<br>";
							pnote +=  "<b>Application Date: </b>" + finance_array[i]['application_date'] + "<br>"; 
							pnote +=  "<b>Application Status: </b>" + finance_array[i]['application_status'] + "<br>"; 
							pnote +=  "<b>Recommended: </b>" + finance_array[i]['recommended'] + "<br>"; 
						}
						pnote += "</pre>";
						
						if(pnote != null){
							
							document.getElementById("note_finance").insertAdjacentHTML('afterbegin', pnote);
						}   
					}
					else{
						
						$('#note_finance_div').hide();
					}  

										
					//DISPLAY PPS DATA					
					
					if(len_pps){
						//alert(len_pps);
						var tbody = '';
						var total_se = 0;
						var total_oth = 0;
						var total_wg= 0;
						var toal_pps_balance = 0;
						
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
							//<input class="form-control1" type ="text" name="year" value="' + pps_array[i]['year'] + '" id="pps_year2" readonly>
							//<input class="form-control1" type ="text" name="balance" value="' + pps_array[i]['balance'] + '" id="defaultbal" readonly>
							
							tbody += "<tr id=" + pps_array[i]['tax_period'] + ">"
                                    + '<td style="text-align: center;">' + pps_array[i]['tax_period'] + '</td> '
                                    + '<td style="text-align: center;"> ' + pps_array[i]['balance'] + '</td>';
							
							if(pps_array[i]['filing_status'] == null || pps_array[i]['filing_status'] == 0){
								
								pps_array[i]['filing_status'] = '';								
                            }
							//<input class="form-control1" type ="text" name="filingstat" value="' + pps_array[i]['filing_status'] + '" id="filing_stat" readonly>
							tbody += '<td style="text-align: center;">' + pps_array[i]['filing_status'] + '</td>';
							
							if(pps_array[i]['account_status'] == null || pps_array[i]['account_status'] == 0){
								
								pps_array[i]['account_status'] = '';								
                            }
							//<input class="form-control1" type ="text" name="accountstat" 	value="' + pps_array[i]['account_status'] + '" id="account_stat" readonly>
							tbody += '<td style="text-align: center;">' + pps_array[i]['account_status'] + '</td>   ';
							
							//<input class="form-control1" type ="text" name="csed" value="' + pps_array[i]['csed'] + '" id="csedid" readonly> 
							
							if(pps_array[i]['csed_date_c'] != '00/00/0000' && pps_array[i]['csed_date_c'] != null){
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['csed_date_c'] + '</td>';
                            }
							else{
								
								tbody += '<td style="text-align: center;"></td>';
							}
							//Commented on 07-10-2022
							/*if(pps_array[i]['ro_recommended'] == null || pps_array[i]['ro_recommended'] == 0){
								
								pps_array[i]['ro_recommended'] = '';								
                            }
							//<input class="form-control1" type ="text" name="ro_recommend" value="' + pps_array[i]['ro_recommended'] + '" id="ro" readonly>
							tbody +=  '<td style="text-align: center;">' + pps_array[i]['ro_recommended'] + ' </td>';
							
							if(pps_array[i]['fta_eligible'] == null || pps_array[i]['fta_eligible'] == 0){
								
								pps_array[i]['fta_eligible'] = '';								
                            }
							
							if(pps_array[i]['liens'] == 'Yes'){
								
								pps_array[i]['fta_eligible'] = 'Yes';	
							}
							//<input class="form-control1" type ="text" name="fta_eligible" value="' + pps_array[i]['fta_eligible'] + '" id="fta_eligible" readonly>
							tbody += '<td style="text-align: center;">' + pps_array[i]['fta_eligible'] + ' </td>';
							*/
							if(pps_array[i]['liens'] == null || pps_array[i]['liens'] == 0){
								
								pps_array[i]['liens'] = '';								
                            }
							
							if(pps_array[i]['liens'] == 'Yes'){
								
								pps_array[i]['liens'] = 'Yes';	
							}
							//<input class="form-control1" type ="text" name="liens" value="' + pps_array[i]['liens'] + '" id="liens" readonly>
							tbody += '<td style="text-align: center;">' + pps_array[i]['liens'] + ' </td>';
							
							//<input class="form-control1" type ="text" name="rep_wage" value="' + pps_array[i]['reported_wage'] + '" id="rep_wage" readonly>
							
                            tbody += '<td style="text-align: center;"> ' + pps_array[i]['reported_wage'] + ' </td>';
							
							//<input class="form-control1" type ="text" name="rep_se" value="' + pps_array[i]['reported_se'] + '" id="rep_se" readonly>
							tbody += '<td style="text-align: center;"> ' + pps_array[i]['reported_se'] + '</td>';
							
							//<input class="form-control1" type ="text" name="rep_other" value="' + pps_array[i]['reported_other'] + '" id="rep_other" readonly> 
							tbody += '<td style="text-align: center;">' + pps_array[i]['reported_other'] + '</td>';
							
							//<input class="form-control1" type="text" name="gross_month_inc" value="' + pps_array[i]['gross_monthly_inc'] + '" id="gross_month_inc" readonly>
							tbody += '<td style="text-align: center;">' + pps_array[i]['gross_monthly_income'] + ' </td>';
							
							//<input class="form-control1" type="text" name="tax_withheld" value="' + pps_array[i]['tax_withheld'] + '" id="tax_withheld" readonly>
							tbody += '<td style="text-align: center;"> ' + pps_array[i]['taxes_withheld'] + '</td>';
							
							//tbody += '<td style="text-align: center;"> ' + pps_array[i]['tin_c'] + '</td>'; // Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
							
                            tbody +=   "</tr>";					
												
						}
						
						if(toal_pps_balance != ''){
							//alert('if'+toal_pps_balance)
						}
						else{
							//alert('else-'+toal_pps_balance)
							toal_pps_balance = 0;
						}
						//total row
						//<input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly> <input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_pps_balance) + '" id="total_balance" readonly>
						
						tbody += '<tr id="total"> <td style="text-align: center;">Total</td> <td colspan="13">' + parseInt(toal_pps_balance) + '</td> 	</tr>';
										
						$("#pps_table").append(tbody);
						
						if(total_se > 0)
							$(".npc").text('$'+total_se);
						if(total_oth > 0)
							$(".oth").text('$'+total_oth);
						if(total_wg > 0)
							$(".wg").text('$'+total_wg);
						if(toal_pps_balance > 0)
							$(".cur_bal").text('$'+toal_pps_balance.toLocaleString()); 
					}
					//alert('len_pps_spouse '+ len_pps_spouse);
					//spouse pps
					if(len_pps_spouse){
						//alert(len_pps_spouse);
						var tbody = '';
						var total_se = 0;
						var total_oth = 0;
						var total_wg= 0;
						var toal_spouse_pps_balance = 0;
						
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
							
							//<input class="form-control1" type ="text" name="year" value="' + spouse_pps_array[i]['year'] + '" id="pps_year2" readonly>
							//<input class="form-control1" type ="text" name="balance" value="' + spouse_pps_array[i]['balance'] + '" id="defaultbal" readonly>
							//<input class="form-control1" type ="text" name="filingstat" value="' + spouse_pps_array[i]['filing_status'] + '" id="filing_stat" readonly>
							
							tbody += "<tr id=" + spouse_pps_array[i]['tax_period'] + ">"
                                    + '<td style="text-align: center;">' + spouse_pps_array[i]['tax_period'] + '</td> '
                                    + '<td style="text-align: center;">' + spouse_pps_array[i]['balance'] + ' </td>'
                                    + '<td style="text-align: center;">' + spouse_pps_array[i]['filing_status'] + ' </td> ';
                            
							//<input class="form-control1" type ="text" name="accountstat" value="' + spouse_pps_array[i]['account_status'] + '" id="account_stat" readonly> 
							
							tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['account_status'] + '</td>';							
							//<input class="form-control1" type ="text" name="csed" value="' + spouse_pps_array[i]['csed'] + '" id="csedid" readonly>							
							//tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['csed'] + ' </td>'
                            
							if(spouse_pps_array[i]['csed_date_c'] != '00/00/0000' && spouse_pps_array[i]['csed_date_c'] != null){
								
								tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['csed_date_c'] + '</td>';
                            }
							else{
								
								tbody += '<td style="text-align: center;"></td>';
							}
							
							//<input class="form-control1" type ="text" name="ro_recommend" value="' + spouse_pps_array[i]['ro_recommended'] + '" id="ro" readonly>
							tbody +=  '<td style="text-align: center;"> ' + spouse_pps_array[i]['ro_recommended'] + '</td>';
                            							
							if(spouse_pps_array[i]['fta_eligible'] == 1){
                                spouse_pps_array[i]['fta_eligible'] = 'Yes';
                            }
                            
							//<input class="form-control1" type ="text" name="fta_eligible" value="' + spouse_pps_array[i]['fta_eligible'] + '" id="fta_eligible" readonly>
							
							tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['fta_eligible'] + ' </td>';  
							
							if(spouse_pps_array[i]['liens'] == 1){
                                spouse_pps_array[i]['liens'] = 'Yes';
                            }
                            //<input class="form-control1" type ="text" name="liens" value="' + spouse_pps_array[i]['liens'] + '" id="liens" readonly>							
							tbody += '<td style="text-align: center;"> ' + spouse_pps_array[i]['liens'] + '  </td>';
							
							//<input class="form-control1" type ="text" name="rep_wage" value="' + spouse_pps_array[i]['reported_wage'] + '" id="rep_wage" readonly>
							
                            tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['reported_wage'] + ' </td>';
							
							//<input class="form-control1" type ="text" name="rep_se" value="' + spouse_pps_array[i]['reported_se'] + '" id="rep_se" readonly> 
							//<input class="form-control1" type ="text" name="rep_other" value="' + spouse_pps_array[i]['reported_other'] + '" id="rep_other" readonly>
							
							tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['reported_se'] + '</td>';
							tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['reported_other'] + ' </td>';
							
							//<input class="form-control1" type="text" name="gross_month_inc" value="' + spouse_pps_array[i]['gross_monthly_inc'] + '" id="gross_month_inc" readonly>
							tbody += '<td style="text-align: center;">' + spouse_pps_array[i]['gross_monthly_income'] + '  </td>';
							
							//<input class="form-control1" type="text" name="tax_withheld" value="' + spouse_pps_array[i]['tax_withheld'] + '" id="tax_withheld" readonly>
							tbody += '<td style="text-align: center;"> ' + spouse_pps_array[i]['taxes_withheld'] + ' </td>';
							
							if(spouse_pps_array[i]['tin_c'] != null){
								
								tbody += '<td style="text-align: center;"> ' + spouse_pps_array[i]['tin_c'] + ' </td>';
							}
							else{
								
								tbody += '<td></td>';
							}
							
                            tbody +=   "</tr>";					
												
						}
						
						var pps_instruction_sp = spouse_pps_array[0]['pps_instructions_c'];
						
						
						if(pps_instruction_sp != null) {
							
							$("#pps_spouse_instruct_note").val(pps_instruction_sp);
						}
						
						//<input class="form-control1" type="text" name="total_text" value="Total" id="total_text" readonly>
						//<input class="form-control1" type="text" name="total_balance" value="' + parseInt(toal_spouse_pps_balance) + '" id="total_balance" readonly>
						
						tbody += '<tr id="total"> <td style="text-align: center;">Total</td> 						<td colspan="13"> ' + parseInt(toal_spouse_pps_balance) + ' </td> 	</tr>';
												
						$("#spouse_pps_table ").append(tbody);
						
						
					}
					
					
					//PPS notes
					
					if(len_pps_notes){		
						var pnote = '';
						
						for (var i = 0; i < len_pps_notes; i++) {
							pnote += "<p>"+ pps_notes_array[i]['note_text'] + "<br>" + pps_notes_array[i]['note_datecreated'];;															
						}
						
						pnote += "</p>";
						document.getElementById("note_pps_msg").insertAdjacentHTML('afterbegin', pnote);
					}
					
					//PPS notes spouse
					//len_pps_notes_spouse = pps_notes_spouse_array
					if(len_pps_notes_spouse){		
						var pnote = '';
						
						for (var i = 0; i < len_pps_notes_spouse; i++) {
							pnote += "<p>"+ pps_notes_spouse_array[i]['note_text'] + "<br>" + pps_notes_spouse_array[i]['note_datecreated'];;															
						}
						
						pnote += "</p>";
						document.getElementById("note_spouse_pps_msg").insertAdjacentHTML('afterbegin', pnote);
					}
										
					//assets
					//alert(len_assets)
					//if(len_assets)
					{
						
						var calculated_val = 0;
						var total_asset = 0;
						var cash_on_hand = 0;
						
						if(jsonData.asset_bank_acc){
							var bank_acc_val = jsonData.asset_bank_acc ;
							total_asset += parseFloat(bank_acc_val);
						}
						//alert(total_asset)
						if(len_vehicles){
							
							if(vehicles_array[0]['market_value_c'] != ''){
								
								var fmv80 = parseFloat(vehicles_array[0]['market_value_c']) * 0.8;
								if(vehicles_array[0]['balance'] != ''){
									
									var vehicle1_eq = fmv80 - parseFloat(vehicles_array[0]['balance']);
									if(vehicle1_eq < 0)
										vehicle1_eq = 0;
									else{
										total_asset += parseFloat(vehicle1_eq);
									}
								}
							}
						}
						//alert(total_asset)
						if(len_vehicles > 1){
							
							if(vehicles_array[1]['market_value_c'] != ''){
								
								var fmv80 = parseFloat(vehicles_array[1]['market_value_c']) * 0.8;
								if(vehicles_array[1]['balance'] != ''){
									
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
								if(vehicles_array[2]['balance'] != ''){
									
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
								if(vehicles_array[3]['balance'] != ''){
									
									var vehicle4_eq = fmv80 - parseFloat(vehicles_array[3]['balance']);
									if(vehicle4_eq < 0)
										vehicle4_eq = 0;
									else{
										total_asset += parseFloat(vehicle4_eq);
									}
								}
							}
						}
						
						if(len_real_estate){
							
							for(var i=0; i<len_real_estate; i++){
								
								var j= i+1;
								if(real_estate_array[i]['fmv_c'] != ''){
									
									var fmv80 = parseFloat(real_estate_array[i]['fmv_c']) * 0.8;
									if(real_estate_array[i]['balance'] != ''){
										
										var real_estate_eq = Math.floor(fmv80 - Math.floor(real_estate_array[i]['balance']));
										if(real_estate_eq < 0)
											real_estate_eq = 0;
										else{
											total_asset += parseFloat(real_estate_eq);
										}
									}
								}
							}
						}
						
						if(len_assets){
						
							for (var i = 0; i < len_assets; i++) {
								
								
								var flag=0;
								if(assets_array[i]['name'] == 'Investment'){
									
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
										
									flag = 1;
									
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
								}
								
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
								}	  
								
								if(flag < 1){
									
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
								} 
							}
							
							
						}
					
						if(total_asset > 0){
							
							$(".sa").text('$'+total_asset.toLocaleString()); 
							$(".ra").text('$'+total_asset.toLocaleString()); 
						}						
					}
					
					//summary expense
							
					if(len_fin_overview > 0){
						
						var punder65 = fin_overview_array[0]['adults_under_65'];
						var pover65 = fin_overview_array[0]['adults_over_65'];
						
						var num_vehicle = len_vehicles; 
						var total_auto_expense = 0;
						
						if(len_vehicles){
							
							for(var i=0; i<len_vehicles; i++){
								
								total_auto_expense = parseFloat(vehicles_array[i]['monthly_payment']);
							}
						}
						total_auto_expense = parseFloat(total_auto_expense) + parseFloat(sugar_irs_standards_array['auto_operating']);
						
						var total_persons = parseInt(punder65) + parseInt(pover65);
						
						var total_food_clothing = parseFloat(sugar_irs_standards_array['fcm']);
						
						var tot_expense = 0; //parseFloat(total_food_clothing);						
										
						var extra_rent=0;						
						 
						if(len_real_estate){
							
							for(var i=0; i<len_real_estate; i++){
								
								extra_rent += parseFloat(real_estate_array[i]['monthly_payment']);
							}								
						}
							
						if(fin_overview_array[0]['rent_payment'] > 0){
							
							if(fin_overview_array[0]['utilities_payment_c'] == null){
								
								fin_overview_array[0]['utilities_payment_c'] = 0;
							}
							if(fin_overview_array[0]['rent_payment'] == null){
								
								fin_overview_array[0]['rent_payment'] = 0;
							}
							if(fin_overview_array[0]['telephone_payment_c'] == null){
								
								fin_overview_array[0]['telephone_payment_c'] = 0;
							}
							var stated_house_exp = parseFloat(fin_overview_array[0]['rent_payment'])
												+ parseFloat(fin_overview_array[0]['utilities_payment_c'])
												+ parseFloat(fin_overview_array[0]['telephone_payment_c']);
							
						}
						else { 
							
							if(fin_overview_array[0]['utilities_payment_c'] == null){
								
								fin_overview_array[0]['utilities_payment_c'] = 0;
							}
							if(fin_overview_array[0]['telephone_payment_c'] == null){
								
								fin_overview_array[0]['telephone_payment_c'] = 0;
							}
							
							var stated_house_exp = extra_rent
												+ parseFloat(fin_overview_array[0]['utilities_payment_c'])
												+ parseFloat(fin_overview_array[0]['telephone_payment_c']);
												
							//tot_expense += parseFloat(stated_house_exp);
						}
						if(sugar_irs_standards_array['housing'] < stated_house_exp){
							
							var allowable_house_exp = parseFloat(sugar_irs_standards_array['housing']);
						}
						else { 
							var allowable_house_exp = parseFloat(stated_house_exp);
						}
						//alert(tot_expense); 
						tot_expense += parseFloat(allowable_house_exp);						
						//alert(allowable_house_exp);	
						tot_expense += parseFloat(fin_overview_array[0]['healthcare_total']);
						tot_expense += parseFloat(total_food_clothing);
						
						var exp_auto = 0;								
						
						var vehicle_exp = 0;
						var vehicle_exp1 = 0;
						var vehicle_exp2 = 0;
						var vehicle2 = 0;
								
						if(len_vehicles){
							
							/*for(var i=0; i<len_vehicles; i++){
								
								if(vehicles_array[i]['monthly_payment'] > sugar_irs_standards_array['ownership']){
									
									exp_auto += parseFloat(sugar_irs_standards_array['ownership'])
								}
								else{
									
									exp_auto += parseFloat(vehicles_array[i]['monthly_payment'])											
								}								
							}*/
							for(var i=0; i<len_vehicles; i++){
										
								if(i > 1)
									break;
								
								/*if(vehicles_array[i]['monthly_payment'] > sugar_irs_standards_array['ownership']){
									
									vehicle_exp += parseFloat(sugar_irs_standards_array['ownership']);
									//exp_auto += parseFloat(sugar_irs_standards_array['ownership']);
								}
								else{
									
									vehicle_exp += parseFloat(vehicles_array[i]['monthly_payment']);
									//exp_auto += parseFloat(vehicles_array[i]['monthly_payment']);
								}*/
								if(i == 0){
									
									vehicle_exp1 = parseFloat(vehicles_array[i]['monthly_payment']);
								}
								if(i == 1){
									
									vehicle2 = 1;
									vehicle_exp2 = parseFloat(vehicles_array[i]['monthly_payment']);
								}
																		
								//auto_irs += parseFloat(sugar_irs_standards_array['ownership']);
								//alert(auto_irs);
							}
						}
								
						//exp_auto += parseFloat(sugar_irs_standards_array['auto_operating']);
						if(vehicle_exp1 > sugar_irs_standards_array['ownership']){
									
							vehicle_exp1 = parseFloat(sugar_irs_standards_array['ownership']);
						}						
						
						if(vehicle2 == 1){
							
							if(vehicle_exp2 > sugar_irs_standards_array['ownership']){
							
								vehicle_exp2 = parseFloat(sugar_irs_standards_array['ownership']);
							}
							
						}
						var tot_public_trans = Math.floor(fin_overview_array[0]['public_transportation_client_c']) + Math.floor(fin_overview_array[0]['public_transportation_spouse_c']);
								
						if(tot_public_trans > sugar_irs_standards_array['public_transportation']){
							
							tot_public_trans = Math.floor(sugar_irs_standards_array['public_transportation'])
						}
						if(vehicle_exp1 > 0 || vehicle_exp2 > 0){
									
									exp_auto += vehicle_exp1 + vehicle_exp2 + parseFloat(sugar_irs_standards_array['auto_operating']);
						}
						else{
							
							exp_auto += tot_public_trans + parseFloat(sugar_irs_standards_array['auto_operating']);
						}
								
						tot_expense += Math.floor(parseFloat(exp_auto));
							
						//tot_expense += parseFloat(sugar_irs_standards_array['public_transportation']);
																				
						//tot_expense += parseFloat(total_auto_expense);
						
						

						//additional_expense_array						
						if(len_additional_expense){
							
							var i=0;
							var html1 = '<h5 style="text-align: center;"><b>Taxes & Other</b></h5>';
							var html_tab = '<h5 style="text-align: center;"><b>Taxes & Other</b></h5>';
							
							for(i=0; i<additional_expense_array.length; i++){
								//alert('add-'+additional_expense_array[i]['amount_actual_c'])
								tot_expense += parseFloat(additional_expense_array[i]['amount_actual_c']);	
							}
						}
						
						if(tot_expense > 0){
							
							$('.se').html('$' + tot_expense.toLocaleString());
							$('.re').html('$' + tot_expense.toLocaleString());
						}
						else{
							$('.se').html(tot_expense);
							$('.re').html(tot_expense);
						}
						
						//new summary 
						var inc_type_client = '';
						var inc_type_sp = '';
						var cur_emp_client = '';
						var cur_emp_sp = '';
						var len_cur_emp_client = '';
						var len_cur_emp_sp = '';
						
						//if(len_fin_overview > 0){
							
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
						//}
						$('.income_type_client').text(inc_type_client);
						$('.income_type_sp').text(inc_type_sp);
						$('.cur_emp_client').text(cur_emp_client);
						$('.cur_emp_sp').text(cur_emp_sp);
						$('.len_cur_emp_client').text(len_cur_emp_client);
						$('.len_cur_emp_sp').text(len_cur_emp_sp);
					}
					
					//Services 
					if(service_len) {
						
						var acc_tbody = '';
												
						for (var i = 0; i < service_len; i++) {
							
							acc_tbody += "<tr id=" + servicese_array[i]['id'] + ">";
							
							var calc = servicese_array[i]['list_price'] * servicese_array[i]['quantity'];
							
							if(servicese_array[i]['discount_amount'] > 0){
								
								calc = calc - servicese_array[i]['discount_amount'];
							}
							if(servicese_array[i]['sold_by'] == null){
								
								servicese_array[i]['sold_by'] = '';
							}
							if(servicese_array[i]['approved_by'] == null){
								
								servicese_array[i]['approved_by'] = '';
							}
							if(servicese_array[i]['description'] == null){
								
								servicese_array[i]['description'] = '';
							}
							if(servicese_array[i]['discount_reason_c'] == null){
								
								servicese_array[i]['discount_reason_c'] = '';
							}
							
							//Check If Package 
							if(servicese_array[i]['is_package_c'] == 1){
								//console.log(res[i]['childServices']); 
								var child_array = [];
								var child_list = '';
								if(servicese_array[i]['childServices'] != '' && servicese_array[i]['childServices'] != null){

									child_array = servicese_array[i]['childServices'] .split(','); //.join('<br>'); 
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

							if(servicese_array[i]['sale_type'] != null){
								
								acc_tbody += '<td>' + servicese_array[i]['sale_type'] + info_icon + '</td> '; 
							}
							else{

								acc_tbody += '<td></td>';
							}
							
							acc_tbody += '<td>' + servicese_array[i]['name'] + '</td> '
									+ '<td>' + servicese_array[i]['quantity'] + '</td> '
									+ '<td>' + servicese_array[i]['list_price'] + '</td> '
									+ '<td>' + servicese_array[i]['discount_reason_c'] + '</td> ';
							
							if(servicese_array[i]['discount_amount'] > 0){
								
								acc_tbody += '<td>' + servicese_array[i]['discount_amount'] + '</td> ';
							}
							else{
								
								acc_tbody += '<td></td>';
							}
							acc_tbody += '<td>' + calc + '</td> '
									+ '<td>' + servicese_array[i]['created_by'] + '</td> '
									+ '<td>' + servicese_array[i]['date_entered'] + '</td> '
									+ '<td></td>'
									+ '<td>' + servicese_array[i]['sold_by'] + '</td>'
									+ '<td>' + servicese_array[i]['approved_by'] + '</td>';
									
							if(servicese_array[i]['dateapproved_c']){
								
								acc_tbody += '<td>' + servicese_array[i]['dateapproved_c'] + '</td> ';
							}
							else{
								
								acc_tbody += '<td></td>';
							}
							
							acc_tbody += '<td>' + servicese_array[i]['description'] + '</td> '
									+ '</tr>';
						}
						if(acc_tbody != ''){
							
							$("#acc_services_table").append(acc_tbody);
						}
						
					}
					
					if(len_as){
						
						var total_ths_balance = 0;
						var total_ths_spbalance = 0;
						
						for (var i = 0; i < len_as; i++) {
			
							if(account_status_array[i]['accrued_balance'] >= 0){
								
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'T'){
							
									total_ths_balance += parseFloat(account_status_array[i]['accrued_balance']);
								}
								
								if(account_status_array[i]['year'] != 'Totals' && account_status_array[i]['taxpayer_spouse'] == 'S'){
									
									total_ths_spbalance += parseFloat(account_status_array[i]['accrued_balance']);
								}
							}
			
						}
		
						if(total_ths_balance > 0){
							
							$(".ths_bal").text('$'+total_ths_balance.toLocaleString());
						}
						if(total_ths_spbalance > 0){
			
							$(".ths_spbal").text('$'+total_ths_spbalance.toLocaleString());
						}
					}
				}
			});
		}
	}

	function init() {
		
		var case_id = <?php echo $id; ?> ;
		var status = '<?php echo $status; ?>' ;
		//alert(status);
		if(case_id != ''){
				
			show_data(case_id);
		}
		else{
			
			alert('Case id not found');
		}
		/*if(status == 'Completed')
		{
			
			if(case_id != ''){
				
				show_data(case_id);
			}
			else{
				
				alert('Case id not found');
			}
		}
		else
		{		
			alert('Cannot display Case Review data as the case is not in Completed status');
		}*/	 		
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
		<div class="container" style="margin-top:20px;">
			<div class="panel">

				<div class="panel-body">			
						
					<div class="col-md-11" style="float: left" id="general_detail">
						
					</div>
					<?php //if($status == 'Completed')
					{ ?>
						<h4 style="color: black;" id="pps_heading">PPS Information</h4>
					<?php } ?>
					<br>
					<?php //if($status == 'Completed')
					{
					include('case-report/pps-information.php'); }?>
					<br>
					<h4 style="color: black;" id="pps_spouse_heading">PPS Spouse Information</h4>
					<br>
					<?php include('case-report/pps-spouse-information.php'); ?>
					<br>
					<?php //if($status == 'Completed')
					{ ?>
						<h4 style="color: black;" id="summary_heading">Summary</h4>
					<?php } ?>
					<br>
					<?php //if($status == 'Completed')
					{ include('case-report/summary-notes.php'); }?>
				</div>
			</div>		
		</div>
	</div>
</div>	

		