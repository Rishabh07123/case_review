<?php $userid = $this->session->userdata('User_id'); session_write_close(); $id = $_GET['id'];  
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/css/back-office-detail.css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datepicker-ui/datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-tabletools.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/datatable/datatable-reorder.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<!-- Convert all php url and ids in js variable and use in different js files -->
<script type="text/javascript">
	var baseURL = "<?php echo base_url();?>";
	var caseId = "<?php echo $id; ?>";
</script>
 
<!-- All tabs html statrt -->
<div id="page-content-wrapper">
   <div id="page-content">
      	<div class="container" style="margin-top:20px;">
         	<div class="panel">
            	<div class="panel-body">
               		<div class="form-group">

					   <!--<div class="general_contact_info" style = "border-radius: 25px; background: #f3f4f5de; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 5px;"> -->
							<div class="col-md-11" style="float: left" id="BO_general_detail">
								<!-- General summary comes by js -->
							</div>
							<div class="col-md-11" style="float: left; margin-top: 20px;" id="BO_general_detail2">
								<!-- General summary comes by js -->
							</div>
						<!--</div> -->
						<div class="row" id="acc_show_services_div" style="padding: 10px 10px;">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<!--<div class="text-right accordion-buttons">									
									<button class="btn btn-default BO_expand_all" onclick="expand_all_srvce_tabs()">Expand All</button>
									<button class="btn btn-default BO_collapse_all"  onclick="close_all_srvce_tabs()"  >Collapse All</button>
									<button class="btn btn-default BO_Complete" onclick="completeBO();" style="color: #fff; background: #2cb31c;">Complete</button>
									<a type="button" href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $id;?>" target="_blank" class="btn btn-info">CR Portal</a> 
								</div> -->

								<div class="text-right accordion-buttons">									
									<button class="btn btn-default BO_expand_all" onclick="expand_all_srvce_tabs()">Expand All</button>
									<button class="btn btn-default BO_collapse_all"  onclick="close_all_srvce_tabs()"  >Collapse All</button>
									<button class="btn btn-default BO_Complete" onclick="completeBO();" style="color: #fff; background: #2cb31c;">Complete</button>
									<button class="btn btn-default BO_Complete" onclick="completeBOandSendPoa();" style="color: #fff; background: #2cb31c; display :none;">Complete and Generate POA</button>
									<a type="button" href="<?php echo base_url();?>index.php/casedetail/records?id=<?php echo $id;?>" target="_blank" class="btn btn-info">CR Portal</a> 
								</div>

								<!-- Current User   Added on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned -->
								<div class="example-box-wrapper details_box"  id="reports_box">
								    <div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
							       <?php include('back-office/current-assign-user.php'); ?>  
								</div> 
								<!-- Current User End -->
								
								<!-- Assign User -->
								 <div class="example-box-wrapper details_box"  id="reports_box">
								    <div id="reg_js_img" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/p1_new.gif" /></div>
							       <?php include('back-office/assign-user.php'); ?>  
								</div> 
								<!-- Assign User End -->
								
								<!-- Service Summary Start -->
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
										<a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
											<p>Service Summary</p>
										</a>
										</h4>
									</div>
									<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										<div class="panel-body" id="accordion_BO_serv_summary" style="position:relative">
											<?php include('back-office/service-summary.php'); ?>
										</div>
									</div>
								</div>
								<!-- Service Summary End -->

								<!-- Resolution  start -->
								<div class="panel panel-default servc_module_tabs">
									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_BO_res" role="button" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
											<p>Resolutions</p>
										</a>
										</h4>
									</div>
									<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/resolution.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!-- Resolution ends -->

								<!--  Tax Prep start   -->
								<div class="panel panel-default servc_module_tabs">
									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_BO_tp" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapseOne">
											<p>Tax Preparation</p>
										</a>
										</h4>
									</div>
									<div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/tax-prep.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!--  Tax Prep   Ends-->

								<!-- Ancillary Services -->
								<div class="panel panel-default servc_module_tabs">
									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_BO_AS" role="button" data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapseOne">
											<p>Miscellaneous Resolution Services</p>
										</a>
										</h4>
									</div>
									<div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/ancillary-services.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!-- Ancillary Services  Ends-->

								<!-- CTAX PLUS  Added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed -->
								<div class="panel panel-default servc_module_tabs">
									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_BO_CTAXP" role="button" data-toggle="collapse" href="#collapse6" aria-expanded="false" aria-controls="collapse6">
											<p>CTAX+</p>
										</a>
										</h4>
									</div>
									<div id="collapse6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/ctax-plus.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!-- CTAX PLUS Ends  Added on 04-04-2024-->

								<!--  Monitoring -->
								<div class="panel panel-default servc_module_tabs">

									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_BO_mn" role="button" data-toggle="collapse" href="#collapse4" aria-expanded="false" aria-controls="collapseOne">
											<p>Tax Assurance Program</p>
										</a>
										</h4>
									</div>
									<div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/monitoring.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!--  Monitoring Ends-->

								<!-- Poa Generation Start  -->
								<div class="panel panel-default servc_module_tabs">
									<div class="panel-heading" role="tab" id="headingRes">
										<h4 class="panel-title">
										<a  id="accord_poa_generation_res" role="button" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
											<p>POA Generation</p>
										</a>
										</h4>
									</div>
									<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRes">
										<div class="panel-body" style="color:black">
											<div class="example-box-wrapper">
												<?php include('back-office/poa-generation.php'); ?>
											</div>
										</div>
									</div>
								</div>
								<!-- Poa Generation End -->

							</div>

							<!-- Hidden input field for store case id and use in multiple places -->
							<input class="form-control" type="hidden" id="BO_caseId" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Summary modal-->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Back Office Assignment Summary</h4>
        </div>
        <div class="modal-body" id="getSummery">
          
        </div>
        <div class="modal-footer">
		<p class="confirm_summ">Confirm complete Back Office?</p>
          <button type="button" onclick="confirmBOSummery();" class="btn btn-primary" >Confirm</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" >No</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModalPoa" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Back Office Assignment Summary</h4>
        </div>
        <div class="modal-body" id="getSummeryPoa">
          
        </div>
        <div class="modal-footer">
		<p class="confirm_summ">Confirm complete Back Office and Send POA?</p>
          <button type="button" onclick="confirmBOSummeryandSendPoa();" class="btn btn-primary" >Confirm and Send POA</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" >No</button>
        </div>
      </div>
    </div>
  </div>
  
<!-- All tabs html end -->

<!-- Load global js for back office detail page. For different tabs js are loading in corosponding view.php file -->
<script type = 'text/javascript' src = "<?php echo base_url();?>assets/js/back-office-detail/global.js"></script>
<!-- Load resolution js for resolution module page. -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/resolution.js"></script>
<!-- Load tax preparation js for tax preparation module page. -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/tax-preparation.js"></script>
<!-- Load tax preparation js for Ctax Plus module page.Added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed  -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/ctax-plus.js"></script>
<!-- Load ancillary services js for ancillary services module page. -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/ancillary-services.js"></script>
<!-- Load monitoring js for monitoring module page. -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/monitoring.js"></script>
<!-- Load poa generation js for poa generation module page. -->
<script type = 'text/javascript' src ="<?php echo base_url();?>assets/js/back-office-detail/poa-generation.js"></script>

<script type = 'text/javascript'>

	function checkUnsavedRows()
	{
		var error = 0;
		$("#BO_RS_table tbody tr").each(function() {
		
			if($(this).attr("reso_id") === undefined){
				error = 0;
				if(!saveAllBOResolution()){error = 1;}
				// error = 1;
				// $.jGrowl("Please save Resolution records first.", {
				// 	sticky: true,
				// 	position: 'top-right',
				// 	theme: 'bg-red'
				// });
			}
		});

		$("#BO_TP_table tbody tr").each(function() {
		
			if($(this).attr("tax_prep_id") === undefined){
				error = 0;
				if(!save_All_BO_TaxPrepration()){error=1;}
				// error = 1;
				// $.jGrowl("Please save Tax Preparation records first.", {
				// 	sticky: true,
				// 	position: 'top-right',
				// 	theme: 'bg-red'
				// });
			}
		});

		$("#BO_AS_table tbody tr").each(function() {
			
			if($(this).attr("ancl_srvc_id") === undefined && (($('#BO_AS_advocate').val()) != 0)  && (($('#BO_AS_practitioner').val()) != 0) ){
				error=0;
				if(!saveAllBOAncillaryServices()){error=1;}
				// error = 1;
				// $.jGrowl("Please save Misc. Resolution Services records first.", {
				// 	sticky: true,
				// 	position: 'top-right',
				// 	theme: 'bg-red'
				// });
			}
		});

		$("#BO_Mn_table tbody tr").each(function() {
			
			if($(this).attr("mntr_id") === undefined && (($('.tap_service_started').val()) != '') && (($('.tap_service_started').val()) !== undefined) && (($('#BO_MN_practitionar_c').val()) != 0) ){
				error = 0;
				if(!save_All_BO_mntrg()){error=1;}
				// error = 1;
				// $.jGrowl("Please save Tax Assurance records first.", {
				// 	sticky: true,
				// 	position: 'top-right',
				// 	theme: 'bg-red'
				// });
			}
		});

		if(error == 1){
			return false;
		}
		else{
			return true;
		}
	}

	/* Funtion to confirm BO summary */
	function confirmBOSummery()
	{
		var retval = checkUnsavedRows();

		if(retval == false){
			return false;
		}
		var caseId = "<?php echo $id; ?>";
		   jQuery.ajax({
		
					type: "POST",
					url: baseURL + "index.php/BackOfficeDetail/completeBackOffice",
					
					data: {caseId: caseId},
					success: function(response) 
					{
						if(response == 1)
						{
							$.jGrowl("Completed successfully.", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
							$('.close').click();
						}
						else
						{
							$.jGrowl("Not able to complete. " + response, {
								sticky: true,
								position: 'top-right',
								theme: 'bg-red'
							});
						}
					}
				})
			
		
	}
	
	/* Funtion to show BO summary in popup */
	function completeBO()
		{
			var caseId = "<?php echo $id; ?>";
				jQuery.ajax({
			
						type: "POST",
						url: baseURL + "index.php/BackOfficeDetail/getSummeryBackOffice",
						
						data: {caseId: caseId},
						success: function(response) 
						{
							$('#getSummery').html(response);
							$('#myModal').modal('show');					
							
						}
					})
			
		}

	function confirmBOSummeryandSendPoa()
	{
		var retval = checkUnsavedRows();
		var attIDs = document.getElementById("getIDs");
		var poa_error = document.getElementById("poa_error");
		var attIDsVal = attIDs.value;
		

		if(retval == false){
			return false;
		}
		if(poa_error == 1){

			$.jGrowl("Unable to send POA. No attorney found.", {
				sticky: true,
				position: 'top-right',
				theme: 'bg-red'
			});
			$('.close').click();
			return false;
		}
		var caseId = "<?php echo $id; ?>";
		   jQuery.ajax({
		
					type: "POST",
					url: baseURL + "index.php/BackOfficeDetail/completeBackOffice",					
					data: {caseId: caseId , attIDs : attIDsVal},
					success: function(response) 
					{
						if(response == 1)
						{
							$.jGrowl("Completed and sent successfully.", {
								sticky: false,
								position: 'top-right',
								theme: 'bg-green'
							});
							$('.close').click();
						}
						else
						{
							$.jGrowl("Not able to complete.", {
								sticky: true,
								position: 'top-right',
								theme: 'bg-red'
							});
						}
							
						// alert('success - ' + response);
						
						
					}
				})
			
		
	}

	function completeBOandSendPoa()
	{
		var caseId = "<?php echo $id; ?>";
		jQuery.ajax({
	
			type: "POST",
			url: baseURL + "index.php/BackOfficeDetail/getSummeryBackOffice",
			
			data: {caseId: caseId},
			success: function(response) 
			{
				$('#getSummeryPoa').html(response);
				$('#myModalPoa').modal('show');
				
				
			}
		})
		
	}
	
	</script>