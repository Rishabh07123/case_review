<style>
    
	.blurry {color: transparent; text-shadow: 0 0 3px rgba(0,0,0,1.9);}
    li.blurry {pointer-events:none;}
    .docstar-upload {border:none;}
     .docstar-upload .modal-header h3 {font-size:15px;font-weight: normal;}
    .docstar-upload .modal-header{background: #3498db;color: #efefef;}
    .docstar-upload .modal-body{
    	background: #f2f2f2;
    }
    /*.docstar-upload .modal-body{padding: 5px 0 28px 12px;}*/
    .docstar-upload .modal-body .row-fluid {margin-top: 10px;}
    .docstar-upload label.span3{font-size: 13px;color: #797979;}
    .docstar-upload .fa-book{color: #FFF;} 
    ul.no-bullet li {list-style: none;}
	.modal {
		position: fixed; 
		z-index: 120;
		top: 90px;
		right: 240px;
		bottom: 0;
		left: 240px;
		display: none;
		outline: 0;
		height: auto;
		overflow-y: hidden !important;
		/*box-shadow: rgba(0, 0, 0, 0.35) 0px 0px 6px 0px;*/
		max-height: 526px;
	}
	.modal-footer {
		/* padding: 155px; */
		text-align: right;
		border-top: 1px solid #206ebb;
		background: #f2f2f2;
	}
	
	button#uploadBtn {
		margin-right: 20px;
	}
	
	button#sendFaxBtn {
		margin-right: 20px;
	}
	.span10 {
		padding-top: 10px;
	}
	
	.send-fax {border:none;}
    .send-fax .modal-header h3 {font-size:15px;font-weight: normal;}
    .send-fax .modal-header{background: #3498db;color: #efefef;}
    .send-fax .modal-body{background: #f2f2f2;}
	/*.send-fax .modal-body{padding: 5px 0 28px 12px;}*/
    .send-fax .modal-body .row-fluid {margin-top: 10px;}
    .send-fax label.span3{font-size: 13px;color: #797979;}
    .send-fax .fa-book{color: #FFF;}
	
	.fax-input{
		margin-left: 10px;
	}

	.buttonload {
		background-color: #4CAF50; 
		border: none; 
		color: white; 
		padding: 7px 10px; 
		font-size: 14px; 
		margin-right: 10px;
    	border-radius: 3px;
	}
</style>

<script type="text/javascript">
	
	function open_doc_upload_modal(){
		$("#docstarUploadForm")[0].reset();
		$('.docstar-upload').show();
	}
	
	function closeModal(){
		$("#docstarUploadForm")[0].reset();
		$('#docFile p').text('Drag your files here or click in this area.');
		$(".docstar-upload").hide(); 
	}
	
	function open_send_fax_modal(){
		
		$('.send-fax').show();
	}
	
	function closeFaxModal(){
		$("#docstarUploadForm")[0].reset();
		$(".send-fax").hide(); 
	}

	function editDocument(event){
		let title = '';
		$(event).html('<i class="fa fa-times" aria-hidden="true"></i>');
		$(event).attr('onclick','cancelEditDocument(this)');
		$(event).attr('title','Cancel');
		$(event).css('background-color','#fd6d68');
		$(event).siblings('button').css('display','inline-block');		
		let catOptions = '';
		let selectedCat = '';
		let categoryOptions = [
			"",
			"Sales Uploads",
			"Contracts",
			"Tax Preparation",
			"Signed Tax Returns",
			"Resolution",
			"Notices",
			"Transcripts",
			"POAs"
		];		

		title = $(event).parent('td').siblings().eq(0).children('a').children('font').text();
		id = $(event).parent('td').siblings().eq(0).children('a').attr('id');
		name = $(event).parent('td').siblings().eq(0).children('a').attr('name');
		category = $(event).parent('td').siblings().eq(2).text();
		comment = $(event).parent('td').siblings().eq(3).text();

		categoryOptions.forEach(function(value, index){
		    if (value == category) {
		       selectedCat = value;
		       catOptions += "<option value='"+value+"' selected='selected'>"+value+"</option>";
		    }else{
		        catOptions += "<option value='"+value+"'>"+value+"</option>";        
		    }
		});

		editTitle = '<input dataId="'+id+'" name="'+name+'" type="text" class="form-control" value="'+title+'"/>';		
		editComment = '<textarea  dataId="'+id+'" name="'+name+'" type="text" class="form-control" value="'+comment+'">'+comment+'</textarea >';
		editCategory = '<select id="documentCategory" dataId="'+id+'" name="'+name+'" class="form-control" selectedCat="'+selectedCat+'">'+catOptions+'</select>';

		$(event).parent('td').siblings().eq(0).html(editTitle);
		$(event).parent('td').siblings().eq(2).html(editCategory);
		$(event).parent('td').siblings().eq(3).html(editComment);
	}

	function updateDocument(event){
		newTitle = $(event).parent('td').siblings().eq(0).children('input').val();
		id = $(event).parent('td').siblings().eq(0).children('input').attr('dataId');
		name = $(event).parent('td').siblings().eq(0).children('input').attr('name');
		category = $(event).parent('td').siblings().eq(2).children('select').val();
		comment = $(event).parent('td').siblings().eq(3).children('textarea').val();

		if (category) {
			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/updateDocument",
				dataType: 'json',			
				data: {document_id: id, document_title: newTitle, category: category, comment: comment},			
				success: function(res) 
				{
					if (res.result=='Success') {
						editTitle = '<a id="' + id + '" href="#" onclick="open_file(this);" name="' +name + '"><font color="8399E9">' + newTitle + '</font></a>';		
						
						$(event).parent('td').siblings().eq(0).html(editTitle);
						$(event).parent('td').siblings().eq(2).html(category);
						$(event).parent('td').siblings().eq(3).html(comment);
						
						$(event).css('display','none');
						$(event).siblings('button').html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
						$(event).siblings('button').attr('onclick','editDocument(this)');
						$(event).siblings('button').css('background-color','#24a0f2');
					}else{
						alert('Document upadte failed.');
					}
				}
			})
		}else{
			alert('Category is required.');
		}	
	}
	function cancelEditDocument(event){
		$(event).html('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>');
		$(event).attr('onclick','editDocument(this)');
		$(event).attr('title','Edit');
		$(event).css('background-color','#24a0f2');

		title = $(event).parent('td').siblings().eq(0).children('input').attr('value');
		id = $(event).parent('td').siblings().eq(0).children('input').attr('dataId');
		name = $(event).parent('td').siblings().eq(0).children('input').attr('name');
		
		category = $(event).parent('td').siblings().eq(2).children('select').attr('selectedCat');
		comment = $(event).parent('td').siblings().eq(3).children('textarea').attr('value');
		
		editTitle = '<a id="' +id+ '" href="#" onclick="open_file(this);" name="' +name + '"><font color="8399E9">' + title + '</font></a>';			
		$(event).parent('td').siblings().eq(0).html(editTitle);
		$(event).parent('td').siblings().eq(2).html(category);
		$(event).parent('td').siblings().eq(3).html(comment);

		$(event).siblings('button').css('display','none');
		// console.log($(event).parent('td').siblings().eq(0).html());
	}
	
	function getDocstarList(){
	
		var cid = document.getElementById("case_num").value;
		var old_case_id = document.getElementById("old_case_num").value;

		//if(cid != ''){ 
			/*
			if(jsonData.extra_cases){
						
						var extra_cases_array = jsonData.extra_cases;					
						var len_extra_cases = extra_cases_array.length;						
					}
			console.log('len_extra_cases ' + len_extra_cases);
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
						*/

		//}
		//alert('old_case_id- ' + old_case_id);
		//console.log(accounting_case_number + ', ' + monitoring_case_number);
		var accounting_cid = document.getElementById("accounting_case_number").value;
		var monitoring_cid = document.getElementById("monitoring_case_number").value;
		//alert('accounting_cid- ' + accounting_cid + ', monitoring_cid- ' + monitoring_cid);
		var data = new FormData(); 
		data.append("CaseNo", cid);		
		data.append("ServiceType", "Listing");
		
		if(old_case_id > 0){
			//alert('old_case_id- ' + old_case_id);
			data.append("SugarCaseNo", old_case_id);
		}
		if(accounting_cid > 0){
			//alert('old_case_id- ' + old_case_id);
			data.append("AccountingCaseNo", accounting_cid); 
		}
		if(monitoring_cid > 0){
			//alert('old_case_id- ' + old_case_id);
			data.append("MonitoringCaseNo", monitoring_cid); 
		}
		/*for (var value of data.values()) {
		console.log(value); 
		}*/
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
				//console.log(response)
				if(response != ''){
					
					var records = JSON.parse(response);
					if(records.response.length > 0){//result.length
						
						var tbody = '';
						var tbody_acc = '';
						/*for (var i in records.result)
						{
							//console.log(records.result[i].title + ', ' + records.result[i].versionid)
							tbody += '<tr>';
							
							//tbody += '<td>' + records.result[i].title + '</td>';
							tbody += '<td><a id="' +records.result[i].versionid + '" href="#" onclick="open_file(this);"><font color="8399E9">' + records.result[i].title + '</font></a></td>';
							tbody += '<td>' + records.result[i].versionid + '</td>';
							tbody += '<td>' + records.result[i].createdOn + '</td>';
							//tbody += '<td>' + records.result[i].uploadedBy + '</td>';
							tbody += '<td>' + records.result[i].Category + '</td>';
							
							tbody += '</tr>';
						}*/
						
						for (var i in records.response)
						{							
							//if((records.response[i].title.includes("THS") === false || records.response[i].title.includes("ths") === false) && (records.response[i].title.includes(".xlsx") === false || records.response[i].title.includes(".xls") === false))
							{
								let category = records.response[i].Category == null ? "" : records.response[i].Category;
								let comments = records.response[i].Comments == null ? "" : records.response[i].Comments;
								let uploadedBy = records.response[i].uploadedBy == null ? "" : records.response[i].uploadedBy;
								//console.log(records.response[i].versionid)
								tbody += '<tr>';
								tbody_acc += '<tr>';

								if(records.response[i].Platform == 'AWS'){
									//console.log('AWS - ' + records.response[i].DocumentID);
									tbody += '<td><a id="' +records.response[i].DocumentID + '" href="#" onclick="open_file(this);" name="' +records.response[i].Platform + '"><font color="8399E9">' + records.response[i].title + '</font></a></td>';
									
									tbody_acc += '<td><a id="' +records.response[i].DocumentID + '" href="#" onclick="open_file(this);" name="' +records.response[i].Platform + '"><font color="8399E9">' + records.response[i].title + '</font></a></td>';

									//tbody += '<td>' + records.response[i].DocumentID + '</td>';
								}
								else{
									//console.log('Docstar - ' + records.response[i].versionid);
									tbody += '<td><a id="' +records.response[i].versionid + '" href="#" onclick="open_file(this);" name="' +records.response[i].Platform + '"><font color="8399E9">' + records.response[i].title + '</font></a></td>'; 
									tbody_acc += '<td><a id="' +records.response[i].versionid + '" href="#" onclick="open_file(this);" name="' +records.response[i].Platform + '"><font color="8399E9">' + records.response[i].title + '</font></a></td>'; 
									//tbody += '<td>' + records.response[i].versionid + '</td>';
								}
								
								tbody += '<td>' + records.response[i].createdOn + '</td>';
								tbody_acc += '<td>' + records.response[i].createdOn + '</td>';
								//tbody += '<td>' + records.result[i].uploadedBy + '</td>';
								tbody += '<td>' + category + '</td>';
								tbody_acc += '<td>' + category + '</td>';
								tbody += '<td>' + comments + '</td>';
								tbody_acc += '<td>' + comments + '</td>';
								tbody += '<td>' + uploadedBy + '</td>';
								tbody_acc += '<td>' + uploadedBy + '</td>';

								

								tbody += '<td style="text-align: center;"><button onclick="updateDocument(this);" class="btn btn-save saveDoc" id="saveDoc" title="Save"  style="display: none; background-color: #00bca4; color: white" ><i class="fa fa-floppy-o" aria-hidden="true"></i></button> <button onclick="editDocument(this);" class="btn btn-edit editDoc" id="editDoc" title="Edit"  style="background-color: #24a0f2; color: white"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> </td>';
								
								tbody += '</tr>';
								tbody_acc += '</tr>';
							}
						}	
						if(tbody != ''){
							
							$('.tab_docstar_tbody').html('');
							$('.tab_docstar_tbody').append(tbody);
							$('.docstar_tbody').html('');
							$('.docstar_tbody').append(tbody_acc);
						}
					}
				}
			}
		})
		
	}
	function fileUploadDMS(event){

		var cid = document.getElementById("case_num").value;
		var full_name = '<?php echo $this->session->userdata('username');?>';
		var user_id = '<?php echo $this->session->userdata('User_id');?>';

		var files = $('#fileList').prop("files");
		var customName = $("#customFileName").val();
		var categoryName  = $('#documentCategory').val();			
		var comment = $("#customComment").val();
		var requiredFieldsStatus = true;
		var errorMessage = "";

		if(files.length == 0){
			requiredFieldsStatus = false;
			alert("Please select document for upload.");
			return false;
		}
		if(categoryName == ""){
			requiredFieldsStatus = false;
			alert("Please select uploaded document category");
			return false;
		}
		var form = $('#docstarUploadForm')[0];

		if(requiredFieldsStatus){			
			
			var data = new FormData(form);
			// data.append("comment", comment);
			data.append("customName", customName);
			data.append("CaseNo", cid);
			data.append("fullName", full_name);
			data.append("user_id", user_id);
			data.append("categoryName", categoryName);
			var ins = files.length;
			for (var x = 0; x < ins; x++) {
				data.append("file[]", files[x]);
			}
			$('#loaderDiv').show();
			$("#uploadBtn").hide();
			$("#uploadingBtn").show();

			jQuery.ajax({
					
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/casedetail/fileUploadDMS",
				enctype: 'multipart/form-data',
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,			
				success: function(res) 
				{
					$('#loaderDiv').hide();
					var response = JSON.parse(res);
					console.log("line 401",response);
					if (response.result == 'Success') {
						$.jGrowl("Document Uploaded Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
					}else{
						for (var i = 0; i < response.message.length; i++) {
							resMes = "Unable to upload "+response.message[i].fileName;
							$.jGrowl(resMes, {
								sticky: true,
								position: 'top-right',
								theme: 'bg-red'
							});
						}						
					}
					$("#docstarUploadForm")[0].reset();
					$('#docFile p').text('Drag your files here or click in this area.');
					$("#uploadBtn").show();
					$("#uploadingBtn").hide();
					$(".docstar-upload").hide();
					getDocstarList(); 
				}
			})
		}
	}
	
</script>

<div class="example-box-wrapper details_box"  id="reports_box">
	
	<button type="button" id="upload_btn" class="btn btn-primary" onclick="open_doc_upload_modal();" style="float: right; margin-top: -25px;"> Upload</button>
	
	<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th style="text-align: center;">Title</th> 
				<!--<th style="text-align: center;">Version Id</th>-->
				<th style="text-align: center;">Created Date</th>
				<!--<th style="text-align: center;">Uploaded By</th>-->
				<th style="text-align: center;">Category</th>											
				<th style="text-align: center;">Comments</th>											
				<th style="text-align: center;">Uploaded By</th>											
				<th style="text-align: center;">Action</th>											
			</tr>
		</thead>
		
		<tbody class="tab_docstar_tbody">
		</tbody>
		
	</table>
	
</div>
<div class="modal docstar-upload">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" onclick="closeModal();"><i class="fa fa-times"></i></a>
        <h3><i class="fa fa-book"></i>&nbsp;&nbsp;<span id="titleId">Upload Documents</span></h3>
    </div>

    <div class="modal-body">
		<div id="loaderDiv" style="position: absolute;left:41%; top:35%; display:none; ">
			<img src="<?php echo base_url();?>assets/images/icons/spinner.gif" />
		</div>
		<form name="docstarUploadForm" id="docstarUploadForm" action="" method="post" enctype="multipart/form-data" style="width: 650px;margin: 13px auto;border: 1px solid #dcdcdc;padding: 25px 40px; background-color: white; display: block;">
			<fieldset>
				
				<div class="row-fluid control-group" id="docFile">
					  <input id="fileList" type="file" name="file" multiple style="position: absolute;margin: 0;padding: 0;opacity: 0;width: 500px;height: 74px;margin-top: 0px;cursor: pointer;" />
					  <p style="line-height: 70px;color: #000;text-align: center;border: 2px dashed #ccc;">Drag your files here or click in this area.</p>
				</div>
				
				<div class="row-fluid control-group" id="previewDocumentDiv" style="display: none;">
					<label class="span2">Document</label>
					<div class="span10">
						<a class="fancybox" id="documentPreview" href="#">Preview Document</a>
					</div>
				</div>
				
				<div class="row-fluid control-group">
					<label class="span2">Name</label>
					<div class="span10">
						<input type="hidden" id="documentId">
						<span class="docstar-upload"><input class="form-control" type="input" name="customFileName" id="customFileName" style="border: 1px solid #dcdcdc;"/></span>
					</div>
				</div>
				<div class="row-fluid control-group">
					<label class="span2">Comment</label>
					<div class="span10">
						<span class="docstar-upload"><input class="form-control" type="input" name="customComment" id="customComment" style="border: 1px solid #dcdcdc;"/></span>
					</div>
				</div>
				<div class="row-fluid control-group">
					<label class="span2">Category</label>
					<div class="span10">
						<span class="docstar-upload">
							 <select class="form-control" name="documentCategory" id="documentCategory">
							  <option value="">--Select--</option>
							  <option value="Sales Uploads">Sales Uploads</option>
							  <option value="Contracts">Contracts</option>
							  <option value="Tax Preparation">Tax Preparation</option>
							  <option value="Signed Tax Returns">Signed Tax Returns</option>
							  <option value="Resolution">Resolution</option>
							  <option value="Notices">Notices</option>
							  <option value="Transcripts">Transcripts</option>
							  <option value="POAs">POAs</option>
							</select>
						</span>
					</div>
				</div>
			</fieldset>
        </form>
    </div>
    <div class="modal-footer">
    	<button type="cancel" class="btn btn-primary pull-right" data-dismiss="modal" onclick="closeModal();"> Cancel</button> 			
    	<!-- <button type="button" class="btn btn-primary pull-right" id="uploadBtn" onclick="uploadFile(this);">Upload</button> -->
		<button type="button" class="btn btn-primary pull-right" id="uploadBtn" onclick="fileUploadDMS(this);">Upload</button>
		<button class="buttonload" class="btn btn-primary pull-right" id="uploadingBtn" style="display:none;">
			<i class="fa fa-spinner fa-spin"></i>Uploading
		</button>
    </div>
</div>
<!-- FAXES -->
<br>
 <h3>Fax Docs List</h3> 
<div class="example-box-wrapper details_box"  id="reports_box">
	
	<button type="button" id="upload_btn" class="btn btn-primary" onclick="open_send_fax_modal();" style="float: right; margin-top: -25px;"> Send Fax</button> 
	
	<table id="datatable-tabletools" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th style="text-align: center;">Name</th> 
				<th style="text-align: center;">Fax Number</th>
				<th style="text-align: center;">Created Date</th>				
				<th style="text-align: center;">Modified Date</th>
				<th style="text-align: center;">Created By</th>
				<th style="text-align: center;">Status</th> 
			</tr>
		</thead>
		
		<tbody class="tab_faxlist_tbody">
		</tbody>
		
	</table>
	
</div>

<div class="modal send-fax">

	<div class="modal-header">
		<a class="close" data-dismiss="modal" onclick="closeFaxModal();"><i class="fa fa-times"></i></a>
		<h3><i class="fa fa-book"></i>&nbsp;&nbsp;Send Fax</h3>
	</div>

	<div class="modal-body">
	
		<form name="faxForm" id="faxForm" action="" method="post" enctype="multipart/form-data">
			<fieldset>
			
				<span style="display:inline-block;"><label>To : </label><input class="fax-input" type="text" name="fromNumber" id="toNum" /></span>
				<span style="display:inline-block; margin-left:18px;"><label>Document : </label><input class="fax-input" id="document" type="file" name="attachment"/></span>
			</fieldset>
		</form>
	</div>

	<div class="modal-footer">			
		<button type="cancel" class="btn btn-primary pull-right" data-dismiss="modal" onclick="closeFaxModal();"> Cancel</button> 			
		<button type="button" class="btn btn-primary pull-right" id="sendFaxBtn" onclick="sendFax(this);" >Send</button>
		<button class="buttonload" class="btn btn-primary pull-right" id="loadingBtn" style="display:none;">
			<i class="fa fa-spinner fa-spin"></i>Sending Fax
		</button>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$('#docstarUploadForm #fileList').change(function () {
			$('form p').text(this.files.length + " file(s) selected");
		});
	});

	function sendFax(){

		$("#sendFaxBtn").hide();
		$("#loadingBtn").show();

		var error = 0;
		var cid = document.getElementById("case_num").value;
		var tonum = $("#toNum").val(); //18003232806
		var file = $("#document")[0].files[0];  
		var moduleID = main_lead_id;
		var form = $('#faxForm')[0];
		var uid = '<?php echo $userid ; ?>';
		//alert(tonum);

		//return true;
		if(tonum == ''){
			
			error = 1;
			alert("Please provide Fax Number");
			return false;
		}
		if(file == ''){
			
			error = 1;
			alert("Please choose a file to fax");
			return false;
		}

		if(error == 0){
			
			
			var form_data = new FormData();                  
			form_data.append('file', file);
			form_data.append('phone', tonum);
			form_data.append('ModuleID', moduleID);
			form_data.append('ModuleName', 'Leads');
			form_data.append('LoginUserID', uid);

			jQuery.ajax({
				
				type: "POST",
				enctype: 'multipart/form-data',
				url: "<?php echo $sugar_url;?>" + "rest/v11_1/signalwireSendFaxToClient",
				data: form_data,
				dataType: 'text',
				processData: false,
				contentType: false,
				//async: false,

				success: function(response) 
				{
					var json = JSON.parse(response+'"');
                    if (json == '"pass"') {  
                        uploadFaxFile(file);
                        $.jGrowl("Document Faxed.", {
                            sticky: false,
                            position: 'top-right',
                            theme: 'bg-green'
                        });
                        $('.send-fax').hide();
                    }
                    else{
                        $.jGrowl("Unable to fax document.", {
                            sticky: true,
                            position: 'top-right',
                            theme: 'bg-red'
                        });
                    }

					$("#sendFaxBtn").show();
					$("#loadingBtn").hide();
				},
				error: function () {
                    console.log("ERROR : send fax ");
				},
			})

			
		}
	}

	function uploadFaxFile(file){

		var cid = document.getElementById("case_num").value;
		var categoryName = "Sugar Lead Uploads";
		var full_name = '<?php echo $this->session->userdata('username');?>';
		var user_id = '<?php echo $this->session->userdata('User_id');?>';

		if(file != ''){
			
			var data = new FormData();
			data.append('file', file);	
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
					//console.log('response uploadFaxFile- ' + response);
					
					if(response == 'Return Code: 4<br>'){
						
						$.jGrowl("Unable to upload Document.", {
							sticky: true,
							position: 'top-right',
							theme: 'bg-red'
						});
					}
					else{
						
						$.jGrowl("Document Uploaded Successfully.", {
							sticky: false,
							position: 'top-right',
							theme: 'bg-green'
						});
									
						$("#file").val("");
						$(".docstar-upload").hide(); 
					}
				}
			})
			$("#document").val("");
			$("#faxForm").val(null); 
			$(".docstar-upload").hide();	
		}
	}

</script>