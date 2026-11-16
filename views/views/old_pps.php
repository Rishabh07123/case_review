<?php //header('Access-Control-Allow-Origin: *');?>
		
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datepicker-ui/datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>
			
<script type="text/javascript">
	
	$(document).ready(function() {
		
		init();
	});	
		
	
	function getPPSDetail(id, date, type){
		
		//alert(id + ', ' +  date);
		var ajax_url = '';
		if(type == 'personal'){
				
			ajax_url = "<?php echo base_url(); ?>" + "index.php/oldpps/get_pps_detail_personel";
		}
		if(type == 'spouse'){
				
			ajax_url = "<?php echo base_url(); ?>" + "index.php/oldpps/get_pps_detail_spouse";
		}
		if(type == 'state'){
				
			ajax_url = "<?php echo base_url(); ?>" + "index.php/oldpps/get_pps_detail_state";
		}
		if(id != '' && ajax_url != ''){
			
			jQuery.ajax({
				type: "POST",
				url: ajax_url,
				data: {ppsid: id},
				
				success: function(response)              
				{
					//console.log(response); pps_html
					if(response != ''){
						
						var pps_array = $.parseJSON(response);
						var len_pps = pps_array.length;
						
						console.log(len_pps);
						console.log(pps_array);
						
						if(len_pps){
							
							var toal_pps_balance = 0;
							var tbody = '';
							
							if(type == 'personal'){
								
								tbody += '<h3 style="color: black;">PPS Federal Personal- ' + id + '</h3><br>';
							}
							if(type == 'spouse'){
								
								tbody += '<h3 style="color: black;">PPS Federal Personal Spouse - ' + id + '</h3><br>';
							}
							if(type == 'state'){
								
								tbody += '<h3 style="color: black;">PPS State Personal- ' + id + '</h3><br>';
							}
							tbody += '<table class="table table-striped table-bordered caselist" cellspacing="0" width="100%">';
							
							tbody += '<thead><tr><th>Tax Period</th> <th>Balance</th><th>Filing Status</th>	<th>Account Status</th> <th>CSED</th><th>Required/Original Recommended</th> <th>FTA Eligible</th> <th>Liens</th><th>Reported Wages</th><th>Reported SE</th><th>Reported Other</th> <th>Gross Monthly Income</th><th>Taxes Withheld</th><th>TIN</th></tr></thead>';
			
							tbody += '<tbody class="tab_pps_tbody">';
			
							for (var i = 0; i < len_pps; i++) {
								
								if(pps_array[i]['balance'] != ''){
									
									toal_pps_balance += parseInt(Math.floor(pps_array[i]['balance']));
								}
								tbody += "<tr id=" + pps_array[i]['tax_period'] + ">"
										+ '<td style="text-align: center;">' + pps_array[i]['tax_period'] + '</td> '
										+ '<td style="text-align: center;"> ' + pps_array[i]['balance'] + '</td>';
								
								if(pps_array[i]['filing_status'] == null || pps_array[i]['filing_status'] == 0){
									
									pps_array[i]['filing_status'] = '';								
								}
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['filing_status'] + '</td>';
								
								if(pps_array[i]['account_status'] == null || pps_array[i]['account_status'] == 0){
									
									pps_array[i]['account_status'] = '';								
								}
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['account_status'] + '</td>   ';
								
								if(pps_array[i]['csed_date_c'] != '00/00/0000' && pps_array[i]['csed_date_c'] != null){
									
									tbody += '<td style="text-align: center;">' + pps_array[i]['csed_date_c'] + '</td>';
								}
								else{
									
									tbody += '<td style="text-align: center;"></td>';
								}
								if(pps_array[i]['ro_recommended'] == null || pps_array[i]['ro_recommended'] == 0){
									
									pps_array[i]['ro_recommended'] = '';								
								}
								
								tbody +=  '<td style="text-align: center;">' + pps_array[i]['ro_recommended'] + ' </td>';
								
								if(pps_array[i]['fta_eligible'] == null || pps_array[i]['fta_eligible'] == 0){
									
									pps_array[i]['fta_eligible'] = '';
								}
								
								if(pps_array[i]['liens'] == 'Yes'){
									
									pps_array[i]['fta_eligible'] = 'Yes';	
								}
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['fta_eligible'] + ' </td>';
								
								if(pps_array[i]['liens'] == null || pps_array[i]['liens'] == 0){
									
									pps_array[i]['liens'] = '';								
								}
								
								if(pps_array[i]['liens'] == 'Yes'){
									
									pps_array[i]['liens'] = 'Yes';	
								}
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['liens'] + ' </td>';
								
								tbody += '<td style="text-align: center;"> ' + pps_array[i]['reported_wage'] + ' </td>';
								
								tbody += '<td style="text-align: center;"> ' + pps_array[i]['reported_se'] + '</td>';
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['reported_other'] + '</td>';
								
								tbody += '<td style="text-align: center;">' + pps_array[i]['gross_monthly_income'] + ' </td>';
								
								tbody += '<td style="text-align: center;"> ' + pps_array[i]['taxes_withheld'] + '</td>';
								
								if(pps_array[i]['tin_c'] != '' && pps_array[i]['tin_c'] != null){
									tbody += '<td style="text-align: center;"> ' + pps_array[i]['tin_c'] + '</td>';
								}
								else{
									tbody += '<td></td>';
								}
								tbody +=   "</tr>";	
							}
							
							if(toal_pps_balance != ''){
								//alert('if'+toal_pps_balance)
							}
							else{
								//alert('else-'+toal_pps_balance)
								toal_pps_balance = 0;
							}
							
							tbody += '<tr id="total"> <td style="text-align: center;">Total</td> <td colspan="13">' + parseInt(toal_pps_balance) + '</td> 	</tr></tbody></table><br>';
											
							$("#pps_html").append(tbody);
						}
					}
				}
			})
		}
	}
	
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
				url: "<?php echo base_url(); ?>" + "index.php/oldpps/get_data",				
				data: {caseid: case_id},
				
				success: function(res)              
				{
					//console.log(res);
					var jsonData = $.parseJSON(res);
					
					if(jsonData.summary){
						
						var summary_array = jsonData.summary;					
						var len_summary = summary_array.length;						
					}
					if(jsonData.old_pps_personal){
						
						var old_pps_personal_array = jsonData.old_pps_personal;					
						var len_old_pps_personal = old_pps_personal_array.length;
					}
					if(jsonData.old_pps_spouse){
						
						var old_pps_spouse_array = jsonData.old_pps_spouse;					
						var len_old_pps_spouse = old_pps_spouse_array.length;
					}
					if(jsonData.old_pps_state){
						
						var old_pps_state_array = jsonData.old_pps_state;					
						var len_old_pps_state = old_pps_state_array.length;
					}
					console.log(jsonData.old_pps_personal);
					/*if(jsonData.pps_notes_spouse){
						
						var pps_notes_spouse_array = jsonData.pps_notes_spouse;
						var len_pps_notes_spouse = pps_notes_spouse_array.length;
					}					
					if(jsonData.pps){
						
						var pps_array = jsonData.pps;					
						var len_pps = pps_array.length;
					}   
					if(jsonData.pps_spouse){
						
						var spouse_pps_array = jsonData.pps_spouse;					
						var len_pps_spouse = spouse_pps_array.length;
					}					
					if(jsonData.pps_summary){
						
						var pps_summary_array = jsonData.pps_summary;					
						var len_pps_summary = pps_summary_array.length;						
					}*/
					
					
					//DISPLAY DATA FOR SUMMARY TAB
					if(len_summary){					
											
						for (var i = 0; i < len_summary; i++) {
							
							var client_name = summary_array[i]['FirstName'] + ' ' + summary_array[i]['LastName'] ;
						}	
						
						//show general details  
						var caseid_text = 'Caseid#:   ' + case_id;
						var name_text = 'Name:   ' + client_name ;
						
						//alert(status_text1);
						var html = '<input class="form-control1 casenum" type="text" id="caseno" value="'+ caseid_text + '"  disabled>';
						
						html += '<input class="form-control1 clientname" type="text" id="cname" value="' +  name_text +'"  disabled>';
						
						html += '<br>';
						
						$('#general_detail').append(html);
					}		
					
					//Get pps detail for each old pps and display
					//alert(' len_old_pps_personal ' + len_old_pps_personal)
					if(len_old_pps_personal){
						
						for (var i = 0; i < len_old_pps_personal; i++) {
							
							if(old_pps_personal_array[i]['id'] != ''){
								
								getPPSDetail(old_pps_personal_array[i]['id'], old_pps_personal_array[i]['date_entered'], 'personal');
							}
						}
					}  
					if(len_old_pps_spouse){
						
						for (var i = 0; i < len_old_pps_spouse; i++) {
							
							if(old_pps_spouse_array[i]['id'] != ''){
								
								getPPSDetail(old_pps_spouse_array[i]['id'], old_pps_spouse_array[i]['date_entered'], 'spouse');
							}
						}
					}
					if(len_old_pps_state){
						
						for (var i = 0; i < len_old_pps_state; i++) {
							
							if(old_pps_state_array[i]['id'] != ''){
								
								getPPSDetail(old_pps_state_array[i]['id'], old_pps_state_array[i]['date_entered'], 'state');
							}
						}
					}
					
					/*if(len_pps_summary)
					{	
						var pps_instruction = pps_summary_array[0]['pps_instructions_c'];
												
						if(pps_instruction != null) {
							
							$("#pps_instruct_note").val(pps_instruction);
						}	
					}*/					
							
					//DISPLAY PPS DATA					
					var len_pps = '';
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
							if(pps_array[i]['ro_recommended'] == null || pps_array[i]['ro_recommended'] == 0){
								
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
							
							tbody += '<td style="text-align: center;"> ' + pps_array[i]['tin_c'] + '</td>';
							
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
					var len_pps_spouse = '';
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
					
					/*if(len_pps_notes){		
						var pnote = '';
						
						for (var i = 0; i < len_pps_notes; i++) {
							pnote += "<p>"+ pps_notes_array[i]['note_text'] + "<br>" + pps_notes_array[i]['note_datecreated'];;															
						}
						
						pnote += "</p>";
						document.getElementById("note_pps_msg").insertAdjacentHTML('afterbegin', pnote);
					}
					
					if(len_pps_notes_spouse){
						
						var pnote = '';
						
						for (var i = 0; i < len_pps_notes_spouse; i++) {
							pnote += "<p>"+ pps_notes_spouse_array[i]['note_text'] + "<br>" + pps_notes_spouse_array[i]['note_datecreated'];;															
						}
						
						pnote += "</p>";
						document.getElementById("note_spouse_pps_msg").insertAdjacentHTML('afterbegin', pnote);
					}*/
					
				}
			});
		}
	}

	function init() {
		
		var case_id = <?php echo $id; ?> ;
		
		if(case_id != ''){
				
			show_data(case_id);
		}
		else{
			
			alert('Case id not found');
		}		
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
		<div class="container" style="margin-top:20px;">
			<div class="panel">

				<div class="panel-body">			
						
					<div class="col-md-11" style="float: left" id="general_detail">
						
					</div>
				</div>	
				<div class="panel-body">			
						
					<div class="col-md-12" style="float: left" id="pps_html">
						
					</div>
				</div>		
			</div>
		</div>
	</div>	
</div>	

		