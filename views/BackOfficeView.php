<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"> </script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$('.BOlist').DataTable( {
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

	function showCaseBODetail()
	{
		var caseid = $('#cid').val();
		
		if(caseid == '')
		{
			alert('Please provide caseid');
		}
		else
		{
			//window.location = '<?php echo base_url();?>index.php/BackOffice/CaseBODetail?id='+caseid;
			jQuery.ajax({ 
				
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/BackOffice/CaseBODetail",
                //dataType: 'json',
                data: {caseid: caseid},
                success: function(res) 
                {
                    if(res == 1)
					{
						window.location = '<?php echo base_url();?>index.php/BackOfficeDetail/records?id='+caseid;
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
						<li><a href="#" onclick="showCaseBODetail();" class="btn btn-default" id="show_caseid_data" role="button"  style="margin-left: 112%; margin-top: -29%;">Submit</a></li>
  
						<!--  <?php echo base_url();?>index.php/casedetail/records					
						<li><button class="btn btn-default" id="show_caseid_data" onclick="show_data(this)">Submit</button></li>    background-color: Crimson;  border-radius: 5px; color: white; padding: .5em; text-decoration: none;-->
					 </ul>
				</div>
			</div>
		</div>
	</div>		
</div>
<?php if($mycases !=0){ ?>
<div class="container" style="margin-bottom:1%;">
 <span class="" style="color:#000;font-weight:bold;margin-left:1%;">Total Cases to be assigned : <?php echo count($mycases); ?> </span>
</div>
<?php }?>

<?php
	$english = 0;
	$spanish = 0;
	$active_service = 0;
	$active_enrol = 0;
	$failed_enrol = 0;
	$settled = 0;
	$cancel = 0;

	if(is_array($mycases))
	{
		foreach($mycases as $val)
		{ 
			if($val->language == "English")
			{
				$english++;
			}
			else
			{
				$spanish++;
			}
			if($val->ctax_status_c == "Active Service")
			{
				$active_service++;
			}
			else if($val->ctax_status_c == "Active Enrollment")
			{
				$active_enrol++;
			}
			else if($val->ctax_status_c == "Failed Enrollment")
			{
				$failed_enrol++;
			}
			else if($val->ctax_status_c == "Settled")
			{
				$settled++;
			}
			else if($val->ctax_status_c == "Cancelled")
			{
				$cancel++;
			}
		}
	}
?>

<div class="panel" id="my_cases_div" style="display: block;">
	<div class="panel-body">
		<label class="col-sm-4 control-label"><h3>Back Office Assignment</h3></label>
		<div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
			<label class="col-sm-2 control-label" style="width: 20%">Total English: &nbsp;&nbsp;&nbsp;<?php echo $english; ?></label>
			<label class="col-sm-2 control-label" style="width: 20%">Total Spanish: &nbsp;&nbsp;&nbsp;<?php echo $spanish; ?></label>
			<label class="col-sm-2 control-label" style="width: 20%">Total Active Service: &nbsp;&nbsp;&nbsp;<?php echo $active_service; ?></label>
			<label class="col-sm-2 control-label" style="width: 20%">New Clients: &nbsp;&nbsp;&nbsp;<?php echo $active_enrol + $failed_enrol; ?></label>
			<!-- <label class="col-sm-2 control-label" style="width: 20%">Total Failed Enrollment: &nbsp;&nbsp;&nbsp;<?php //echo $failed_enrol; ?></label> -->
			<label class="col-sm-2 control-label" style="width: 20%">Total Settled: &nbsp;&nbsp;&nbsp;<?php echo $settled; ?></label>
			<label class="col-sm-2 control-label" style="width: 20%">Total Cancelled: &nbsp;&nbsp;&nbsp;<?php echo $cancel; ?></label>
		</div>
		<div class="col-sm-12">
			
			<?php if($mycases == 0){ ?>
				<label class="col-sm-3 control-label">No Cases Found</label>
			<?php } else { ?>	
			
				<table id="datatable-tabletools" class="table table-striped table-bordered BOlist" cellspacing="0" width="100%">
				<thead>
					<tr style="text-align: center">
						<th>Case No.</th> 
						<th>Name</th>
						<th>Language</th>
						<th>CTax Status</th>		
						<th>TAP Status</th>		
						<th>Total New Fee</th>	
						<th>Billing Status</th>	
						<th>Date AR Flipped</th>
						<th>Lead Flag</th>
						<th>Us Only</th>	
					</tr>
				</thead>
				<tbody>
				
				<?php	
				foreach($mycases as $val){ ?>
				
					<tr>
						<td>
							<a href="<?php echo base_url();?>index.php/BackOfficeDetail/records?id=<?php echo $val->case_id_c; ?>" title="CaseDetail"><font color="8399E9"><?php echo $val->case_id_c; ?></font>
							</a> 						
						</td> 
						
						<td><?php echo $val->name;?></td>
						<td><?php echo $val->language; ?></td>
						<td><?php echo $val->ctax_status_c; ?></td>
						<td><?php echo $val->tap_status_c; ?></td>
						<td><?php echo $val->total_fee; ?></td>
						<td><?php echo $val->billing_status_c; ?></td> 						
						<td><?php echo $val->date_ar_flipped; ?></td>
						<?php if($val->lead_src == "anthem-tax-relief") {?>
						<td><?php echo "Anthem"; } ?></td>
						<?php if($val->lead_src == "liberty-tax") {?>
						<td><?php echo "Liberty"; } ?></td>
						<?php if($val->lead_campaign == "partner-referral") {?>
						<td><?php echo "Direct Partner"; } ?></td> <!-- Added on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners -->
						<?php if($val->lead_src == "tpg") {?>
						<td><?php echo "TPG"; } ?></td> <!-- Added on 27-10-2023 https://trello.com/c/QJMeAvCA/660-partner-flagstpg-issues -->
						<?php if($val->lead_src == "pathward") {?>
						<td><?php echo "Pathward"; } ?></td> <!-- Added on 27-10-2023-->
						<?php if($val->lead_src == "Tax Status") {?>
						<td><?php echo "Tax Status"; } ?></td> <!-- Added on 16-01-2024 https://trello.com/c/skxyw7nx/774-tax-status-referrals-lead-posting-api-uat-only-->
						<?php if($val->lead_src != "liberty-tax" && $val->lead_src != "anthem-tax-relief" && $val->lead_campaign != "partner-referral" && $val->lead_src != "tpg" && $val->lead_src != "pathward") {?> <!-- Updated on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners // Updated on 27-10-2023-->
						<td><?php echo ""; } ?></td>
						<td><?php echo $val->us_only; ?></td>
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