<style>
    
	.blurry {color: red; text-shadow: 0 0 3px rgba(0,0,0,1.9);}
    li.blurry {pointer-events:none;}
    .import-csv {border:none;}
    .docstar-upload .modal-header h3 {font-size:15px;font-weight: normal;}
    .import-csv .modal-header{background: #3498db;color: #efefef;}
    .import-csv .modal-body{background: #f2f2f2;}
	.import-csv .modal-body{padding: 5px 0 28px 12px;}
    .import-csv .modal-body .row-fluid {margin-top: 10px;}
    .import-csv label.span3{font-size: 13px;color: #797979;}
    .import-csv .fa-book{color: #FFF;}
    ul.no-bullet li {list-style: none;}
	
	

	.import-csv {
		border: none;
		width: 50%;
		height: 246px;
		margin-top: 135px;
		margin-left: 378px;
	}
	.modal {
    overflow-y: hidden;
    }

	.modal-footer {
		/* padding: 155px; */
		text-align: right;
		border-top: 1px solid #206ebb;
		background: #f2f2f2;
	}
	
	button#uploadCSVBtn {
		margin-right: 20px;
	}
	
	.span10 {
		padding-top: 10px;
	}
	
	.buttonload {
		  background-color: #4CAF50; /* Green background */
		  border: none; /* Remove borders */
		  color: white; /* White text */
		  padding: 7px 10px; /* Some padding */
		  font-size: 14px; /* Set a font size */
		  margin-right: 12px;
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

	function openImportModal(){
		
		//alert('hello');
		$('.import-csv').show();
	}
	
	function closeImportModal(){
		
		$("#uploadCSVBtn").show();
		$("#loadingBtn").hide();
		$("#filecsv").val("");
		$(".import-csv").hide(); 
	}
	
	function uploadCSVFileAnthem(evt){
		
		var full_name = '<?php echo $this->session->userdata('username');?>';
		var filen = $("#filecsv_anthem").val();
		
		var form = $('#anthemImportForm')[0];
		var user_id = '<?php echo $this->session->userdata('User_id');?>';
		
		if(filen == ''){
			
			alert("Please choose a file to upload");
			return false;
		}
		else{
			
			$("#uploadCSVBtn").hide();
			$("#loadingBtn").show();
			
			var data = new FormData(form);
			
			data.append("user_id", 1);
			console.log(data);
            jQuery.ajax({
				
				url: '<?php echo base_url(); ?>' + "index.php/Anthemuploader/importData",
				type: "POST",
				enctype: 'multipart/form-data',				
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				//timeout: 600000,
				
				success: function(response) 
				{
					console.log('response - 124' + response);
					if(response != ''){
						
						var res =$.parseJSON(response);
						
						if(res['message'] == "success"){
							
							var num = res['totalimported'];
							$.jGrowl("Data Imported Successfully. " + num + " records imported", {
								sticky: true,
								position: 'top-right',
								theme: 'bg-green'
							});	
						}else if(res['message'] == "Auth_token"){
							var num = res['totalimported'];
							$.jGrowl("Auth Token is not generated. " + num + " records imported", {
								sticky: true,
								position: 'top-right',
								theme: 'bg-blue'
							});	
						}
						else{						
							$.jGrowl(res['showthis'], {
								sticky: true,
								position: 'top-right',
								theme: 'bg-red'
							});	
						}
						//$("#load_imgcsv").css('display','none');
					}				
					//$("#uploadCSVBtn").attr("disabled", false);
					$("#uploadCSVBtn").show();
					$("#loadingBtn").hide();
					$("#filecsv_anthem").val("");
					$(".import-csv").hide(); 
				}
			})
		}
	}
</script>

<div id="page-content-wrapper">
	<div id="page-content">
	<div class="container" style="margin-top:20px;">
	
<div class="panel" id="my_cases_div" style="display: block;">
	<div class="panel-body">
	
		<div class="col-sm-12">
			<label class="control-label" style="font-size: medium;">Anthem Uploader</label>
			
			<button type="button" id="import_btn" class="btn btn-primary" onclick="openImportModal();" style="float: right;"> Import CSV</button>
			
			<div id="load_imgcsv" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/spinner.gif" /></div> 
		</div>

	</div>		
</div>


		
</div>
</div>
</div>

<!-- Modal -->
<div class="modal import-csv">

	<div class="modal-header">
		<a class="close" data-dismiss="modal" onclick="closeImportModal();"><i class="fa fa-times"></i></a>
		<h3><i class="fa fa-book"></i>&nbsp;&nbsp;Import CSV</h3>
	</div>

	<div class="modal-body">
	
		<form name="anthemImportForm" id="anthemImportForm" action="" method="post" enctype="multipart/form-data">
			<fieldset>
				<div class="row-fluid control-group">
					<label class="span2">Upload File</label>
				
					<div class="span10">
						
						<span class="import-csv">							
							
							<input type="file" name="file" id="filecsv_anthem" aria-label="Upload File" accept=".csv" style="color: #333; " required>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>

	<div class="modal-footer">			
		<button type="cancel" class="btn btn-primary pull-right" data-dismiss="modal" onclick="closeImportModal();"> Cancel</button> 			
		<button type="button" class="btn btn-primary pull-right" id="uploadCSVBtn" onclick="uploadCSVFileAnthem(this);">Upload</button>
		
		<button class="buttonload" class="btn btn-primary pull-right" id="loadingBtn" style="display:none;">
			<i class="fa fa-spinner fa-spin"></i>Loading
		</button>
	</div>
</div>