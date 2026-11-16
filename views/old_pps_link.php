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
		
	
	function getPPSDetail(id, type, name){
		
		//alert(id + ', ' +  name);
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
							
							tbody += '<h3 style="color: black;"> ' + name + '  -  ' + id + '</h3><br>';
							
							/*if(type == 'personal'){
								
								tbody += '<h3 style="color: black;">PPS Federal Personal- ' + id + '</h3><br>';
								
							}
							if(type == 'spouse'){
								
								tbody += '<h3 style="color: black;">PPS Federal Personal Spouse - ' + id + '</h3><br>';
							}
							if(type == 'state'){
								
								tbody += '<h3 style="color: black;">PPS State Personal- ' + id + '</h3><br>';
							}*/
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
	
	function show_data(ppsid)
	{			
		if(ppsid == "")
		{
			alert("PPS Id not found");
			return false;
		}
		else
		{	
			//Get PPS information and call method to display pps detail
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + 	"index.php/oldpps/general_pps_detail",			
				data: {ppsid: ppsid},
				
				success: function(res)              
				{					
					var summary_array = $.parseJSON(res);
					console.log(summary_array);
					var len_summary = summary_array.length;	
					
					if(len_summary){
						
						var name = summary_array[0]['name'];
						var pps_instruction = summary_array[0]['pps_instructions_c'];
						
						$("#pps_instruct_notes_new").val(pps_instruction);
						//var html = '<input class="form-control1 clientname" type="text" id="cname" value="' +  name +'"  disabled>';
						
						//html += '<br>';
						
						//$('#general_detail').append(html);
						
						if(name.indexOf('Personal') != -1){
						
							getPPSDetail(ppsid, type='personal', summary_array[0]['name']);
						}
						if(name.indexOf('State') != -1){
							
							getPPSDetail(ppsid, type='state', summary_array[0]['name']);
						}
						if(summary_array[0]['pps_spouse_c'] == 1 && name.indexOf('Personal') != -1){
							
							getPPSDetail(ppsid, type='spouse', summary_array[0]['name']);
						}
					}  
					
				}
			});
			
			//Get PPS notes 
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + 	"index.php/oldpps/pps_notes",			
				data: {ppsid: ppsid},
				
				success: function(res)              
				{					
					var pps_notes_array = $.parseJSON(res);
					console.log(pps_notes_array);
					var len_pps_notes = pps_notes_array.length;	
					
					if(len_pps_notes){
						
						var pnote = '';
						var html = '';
						
						for (var i = 0; i < len_pps_notes; i++) {
							
							pnote =  pps_notes_array[i]['note_text']+ "&#10;" + moment.utc(pps_notes_array[i]['note_datecreated']).local().format('MMMM Do YYYY, h:mm:ss a');
							
							html += '<textarea rows="7" cols="80" class="form-control past_pps_notes" id="' + pps_notes_array[i]['id'] + '" readonly>' + pnote + '</textarea> ';
						}
						
						document.getElementById("pps_notes_new").insertAdjacentHTML('afterbegin', html);
					}  
					
				}
			});
		}
	}

	function init() {
		
		var pps_id = '<?php echo $id; ?>' ;
		
		if(pps_id != ''){
				
			show_data(pps_id);
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
				
				<!-- <div class="panel-body">			
						
					<div class="col-md-11" style="float: left" id="general_detail">
						
					</div>
				</div>	-->
				
				<div class="panel-body">			
						
					<div class="col-md-12" style="float: left" id="pps_html">
						
					</div>
				</div>
				<div class="panel-body">			
						
					<h3 style="color: black;">PPS Instructions</h3>
					<div class="example-box-wrapper details_box">
						<div class="box-comments row" id="pps_note_box">
							<div class="box-comment" id="instruct_pps2"> 
								
								<div class="col-md-12">
									
									<div class="col-md-10"> 
										<textarea rows="7" cols="80" class="form-control pps_instruct_notes_new" id="pps_instruct_notes_new" style="margin-bottom: 10px;" readonly></textarea> 
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="panel-body">			
						
					<div class="col-md-12" style="float: left" id="pps_note_html">
						
						<h3 style="color: black;">Notes</h3>
						<div class="example-box-wrapper details_box">
							<div class="box-comments row" id="pps_note_box">
								<div class="box-comment" id="note_pps"> 
									<div class="col-md-12" style="margin-bottom: 10px;    margin-top: 10px;">
										
										<div id="pps_notes_new">
											
										</div>
										
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>		
			</div>
		</div>
	</div>	
</div>	

		