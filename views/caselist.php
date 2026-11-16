<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>
			
<script type="text/javascript">
	
	/* Datatables export */

	$(document).ready(function() {
		
		 $('.caselist').DataTable( {
			"order": [[ 0, "desc" ]]
		} );
	
		//var table = $('#datatable-tabletools').DataTable();
		//var tt = new $.fn.dataTable.TableTools( table );

		//$( tt.fnContainer() ).insertBefore('#datatable-tabletools_wrapper div.dataTables_filter');

		$('.DTTT_container').addClass('btn-group');
		$('.DTTT_container a').addClass('btn btn-default btn-md');

		$('.dataTables_filter input').attr("placeholder", "Search...");

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
				
	function showCaseDetail()
	{
		var caseid = $('#cid').val();
		
		if(caseid == '')
		{
			alert('Please provide caseid');
		}
		else
		{
			//window.location = '<?php echo base_url();?>index.php/casedetail/records?id='+caseid;
			jQuery.ajax({ 
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/caselist/check_caseid_in_sugar",
                //dataType: 'json',
                data: {caseid: caseid},
                success: function(res) 
                {
                    if(res == 1)
					{
						window.location = '<?php echo base_url();?>index.php/casedetail/records?id='+caseid;
					}
					else
					{
						$.jGrowl("Case not found in sugar.", {
						  sticky: true,
						  position: 'top-right',
						  theme: 'bg-red'
						});
					}
				}
			})
			
		}
	}

	//added on 23-06-2022 https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
	function getStartTime(timezone,oldStartDate,$kee)
	{
		// var newStart = '';
		var timezone = timezone;
		var oldStartDate = oldStartDate;
		//console.log('akash 1');
		jQuery.ajax({
			type: "GET",
			url: "<?php echo base_url(); ?>" + "index.php/Casedetail/timezoneCheckRecord",					
			data: {timezone : timezone,oldStartDate : oldStartDate},
			success: function(response45) 
			{
				$("#appointmtent_"+$kee).html(response45);
			}
		});
			
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
	<div class="container" style="margin-top:20px;">
	<div class="panel">

		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label">Enter CaseID</label>
				<div class="col-sm-2">
					
					<ul>
						<li><input type="text" name="cid" id="cid" placeholder="Required Field" class="form-control" ></li>
						<li><a href="#" onclick="showCaseDetail();" class="btn btn-default" id="show_caseid_data" role="button" style="margin-left: 112%; margin-top: -29%;">Submit</a></li>
  
						<!--  <?php echo base_url();?>index.php/casedetail/records					
						<li><button class="btn btn-default" id="show_caseid_data" onclick="show_data(this)">Submit</button></li>    background-color: Crimson;  border-radius: 5px; color: white; padding: .5em; text-decoration: none;-->
					 </ul>
				</div>
			</div>
		</div>
	</div>	

<div class="panel" id="showDivAppointmentAll" style="display: block;">
	<div class="panel-body" style="color:black">
		<!--<label class="col-sm-3 control-label">My Appointments</label>
		<div class="example-box-wrapper details_box"  id="appointment_listing">-->
		<label class="col-sm-3 control-label">My Appointments</label>
		<div class="col-sm-12">
			
			<?php if($appointmentList == 0){ ?>
				<label class="col-sm-3 control-label">No Appointments Found</label>
			<?php } else { ?>
			<!--<table id="" class="table table-striped table-bordered " cellspacing="0" width="100%">-->
			<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Case No.</th> 
						<th>Name</th> 
						<th>Status</th>
						<th>Appointment Start Date</th>
						<!--<th>Assign User</th> -->
					</tr>
				</thead>
				<tbody id="tab_assignment_list_tbody">
					<?php //print_r($appointmentList);
						foreach($appointmentList as $kee=>$rows)
						{
					?>
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $rows->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $rows->case_id_c; ?></font>
							</a> 						<!--<?php //echo $rows->case_id_c; ?></td> -->
						</td>
						<td><?php echo $rows->name; ?></td>
						<td><?php echo $rows->status; ?></td>
						<td id='appointmtent_<?php echo $kee; ?>'><script>getStartTime(<?php echo json_encode((string)$rows->timezone_c); ?>, <?php echo json_encode((string)$rows->date_start); ?>, <?php echo json_encode((string)$kee); ?>);</script></td>
						<!--<td><?php //echo $rows->assign_name; ?></td> -->
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } ?>	
		</div>
	</div>
</div>

<div class="panel" id="my_cases_div" style="display: block;">
	<div class="panel-body">
		<label class="col-sm-3 control-label">My Cases</label>
		<div class="col-sm-12">
			
			<?php if($mycases == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<thead>
					<tr style="text-align: center">
						<th>Case No.</th> 
						<th>Name</th>
						<th>Service Type</th>						
						<th>Investigation Type</th>						
						<th>Payment Date</th>			
						<th>Total Amount</th>
						<th>Logics Billing Status</th>
						<th>PPS Call Date</th>	
						<th>Transcripts Available</th>
						<th>THS Report Date</th>
					</tr>
				</thead>
				<tbody>
				
				<?php	
				foreach($mycases as $val){ ?>
				
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td>
						
						<td><?php echo $val->name;?></td>
						<td><?php echo $val->pcname; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->payment_date; ?></td>
						<td><?php echo $val->total_amount; ?></td>
						<td><?php echo $val->lbstatus; ?></td>
						<td><?php echo $val->pps_call_date_c; ?></td>
						<td><?php if($val->ths_report_date_c != null && $val->ths_report_date_c != '') echo "Yes"; else echo "No"; ?></td>
						<td><?php echo $val->ths_report_date_c; ?></td>
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
				
			<?php } ?>	
			
		</div>
	</div>
</div>

<div class="panel" id="my_cases_div" style="display: block;">
	<div class="panel-body">
		<label class="col-sm-3 control-label">My PPS Calls</label>
		<div class="col-sm-12">
			
			<?php if($mypps == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%" style="display: block; overflow-x: auto;">
				<thead>
					<tr style="text-align: center">
						<th>Case No.</th> 
						<th>Name</th>
						<th>Date Created</th>
						<th>PPS Status</th>
						<th>Investigation Type</th>
						<th>Client Language</th>
						<th>Payment Date</th>			
						<th>Total Amount</th>
						<th>Logics Billing Status</th>
						<th>POA Issues</th>
						<th> Business POA Issues</th>						
						<th>PPS Type</th>
						<th>Transcripts Only Eligible</th>
						<th class="pps_instruct_list">PPS Instructions</th>
					</tr>
				</thead>
				<tbody>
				
				<?php	 
				foreach($mypps as $val){ ?>
				
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td>
						
						<td><?php echo $val->name; ?></td>
						<td><?php echo $val->case_created_date; ?></td>
						<td><?php echo $val->pps_status; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->client_language; ?></td>
						<td><?php echo $val->payment_date; ?></td>
						<td><?php echo $val->total_amount; ?></td>
						<td><?php echo $val->lbstatus; ?></td>
						<td><?php echo $val->poa_issues; ?></td>
						<td>
						<?php 
						$biz_issue = '';
						if($val->biz_poa_issues_new_c != '')
							{
								$biz_issue = str_replace('^', '', $val->biz_poa_issues_new_c);
								//$biz_issue = ltrim($biz_issue,',');
								//$biz_issue = rtrim($biz_issue,',');
							}
							echo $biz_issue;
							?>
						</td>
						<td><?php echo $val->pps_type; ?></td>
						<td><?php if($val->transcripts_only_eligible_c == 1) { echo "Yes"; } //$val->transcripts_only_eligible_c; ?></td>
						<td class="pps_instruct_list"><textarea rows="2" cols="30" class="form-control" style="height: 75px;" disabled > <?php echo $val->pps_instructions_c; ?> </textarea></td>
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
				
			<?php } ?>	
			
		</div>
	</div>
</div>


<?php if($pricing_role == 'Investigation Reviewer' || $pricing_role == 'Investigation Admin'){ ?>
	<div class="panel" id="my_pricing_div" style="display: block;">
<?php } else { ?>
	<div class="panel" id="my_pricing_div" style="display: none;">
<?php }?>	
	<div class="panel-body">
		<label class="col-sm-3 control-label">Pricing Cases</label>
		<div class="col-sm-12">
			
			<?php if($mypricing == 0){ ?>			
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<thead>
					<tr style="text-align: center">
						<th>Case No.</th> 
						<th>Name</th>
						<th>Lead Level</th>	
						<th>Language</th>						
						<th>Investigation Type</th>
						<th>Case Record Assignment</th>
						<th>Lead Record Assignment</th>
						<!-- <th>Logics Billing Status</th> commented on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related -->
						<th>Transcripts Available</th>
						<th>Channel Partner</th><!-- added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related -->
						<!-- <th>THS Report Date</th> commented on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related  -->
					</tr>
				</thead>
				<tbody>
				
				<?php	
				foreach($mypricing as $val){ 

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
					//end added on 26-02-2024 
					?> 

				
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td>
						
						<td><?php echo $val->name; ?></td>
						<td><?php echo $val->lead_level_c; ?></td>
						<td><?php echo $val->language_prequal_c; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->case_assigned; ?></td>
						<td><?php echo $val->lead_assigned; ?></td>
						<?php /* Logics Billing Status cell removed 26-02-2024 */ ?>
						<td><?php if($val->ths_report_date_c != null && $val->ths_report_date_c != '') echo "Yes"; else echo "No"; ?></td>
						<td><?php echo $leadSRC ; ?></td><!-- added on 26-02-2024  -->
						<?php /* THS Report Date cell removed 26-02-2024 */ ?>
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
				
			<?php } ?>	
			
		</div>
	</div>
</div>

<div class="panel" id="my_transcript_div" style="display: block;">
	<div class="panel-body">
		<label class="col-sm-3 control-label">My POA Issues</label> <!-- Transcript Appt Cases -->
		<div class="col-sm-12">
			
			<?php if($my_transcript_aptt == 0){ ?>			
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
				<thead>
					<tr style="text-align: center">
						<th>Case No.</th> 
						<th>Name</th>
						<th>PPS Status</th>
						<th>Investigation Type</th>	
						<th>Total Amount</th>
						<th>Logics Billing Status</th>
						<th>POA Issues</th>
						<th> Business POA Issues</th>
						<th>PPS Type</th>
						<th class="pps_instruct_list">PPS Instructions</th>
					</tr>
				</thead>
				<tbody>
				
				<?php	
				foreach($my_transcript_aptt as $val){ ?> 
				
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td>
						
						<td><?php echo $val->name; ?></td>
						<td><?php echo $val->pps_status; ?></td>
						<td><?php echo $val->investigation_type_c; ?></td>
						<td><?php echo $val->total_amount; ?></td>
						<td><?php echo $val->lbstatus; ?></td>
						<td><?php echo $val->poa_issues; ?></td>
						<td>
						<?php 
						$biz_issue = '';
						if($val->biz_poa_issues_new_c != '')
							{
								$biz_issue = str_replace('^', '', $val->biz_poa_issues_new_c);
								//$biz_issue = ltrim($biz_issue,',');
								//$biz_issue = rtrim($biz_issue,',');
							}
							echo $biz_issue;
							?>
						</td>
						<td><?php echo $val->pps_type; ?></td>
						<td class="pps_instruct_list"><textarea rows="2" cols="30" class="form-control" style="height: 75px;" disabled > <?php echo $val->pps_instructions_c; ?> </textarea></td>
					</tr>
				<?php }?>
				
				

				</tbody>
			</table>
				
			<?php } ?>	
			
		</div>
	</div>
</div>

</div>
</div>
</div>