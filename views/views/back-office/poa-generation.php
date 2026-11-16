	
	<!-- <div id="tab_poa_generation_create_page">
	</div>
	<div class="row">
		<input class="form-control" type="submit" id="save_tab_poa_generation" name="save_tab_poa_generation_data" value="Generate" onclick="saveAllPoaGeneration()">
	</div> -->

	<div id = "poa_file" style = "display: none;">

	</div>

	<div class="row" style="padding: 0px 10px;" id="add-poa-button-div">
		<button type="button" class="btn btn-primary" id="add-poa-button"> Add POA</button>
	</div>

	<!-- Section start for add poa form -->
	<div style="border: 1px solid rgb(223, 232, 241); padding: 15px; display:none;" id="add-poa-div">
		<div id="loader" style="display: none;"><img src="<?php echo base_url();?>assets/images/icons/saving.gif" alt="Saving ... "/></div>
		<div>
			<div class="tab_services">
				<div class="record-label">Poa Type</div>
				<div>
					<select class="form-control services bo_poa_generation_poa_type poa_type" name="bo_poa_generation_poa_type" id="bo_poa_generation_poa_type" >
						<option value="0">Select</option>
						<option value="doc8821">8821</option>
						<option value="doc2848">2848</option>
					</select>
				</div>
			</div>	
			<!-- added on 01-09-2022 https://trello.com/c/DxtVhQzM/1565-8821s-through-bodg -->
			<div class="tab_services" style = "display:none" id="8821_type">
				<div class="record-label">8821 Type</div>
				<div>
					<select class="form-control services bo_poa_generation_poa_type poa_type" name="8821_type" id="8821_type_select"  >
						<option value="0">Select</option>
						<option value="TAP English">8821 - TAP English</option>
						<option value="TAP Spanish">8821 - TAP Spanish</option>
						<option value="Active Service English">8821 - Active Service English</option>
						<option value="Active Service Spanish">8821 - Active Service Spanish</option>
					</select>
				</div>
			</div> <!-- end added on 01-09-2022 -->
			<div class="tab_services">
				<div class="record-label">Client</div>
				<div>
					<select class="form-control services bo_poa_generation_client" name="client" id="bo_poa_generation_client">
						<option value="0">Select</option>
						<option value="Client">Client</option>
						<option value="Spouse">Spouse</option>
						<option value="Both">Both Client & Spouse</option>
						<option value="Business">Business</option>
						<option value="All">All</option>
					</select>
				</div>
			</div>	
			<div class="tab_services bo_poa_generation_business_div" style = 'display:none'>
				<div class="record-label">Business</div>
				<div>
					<select class="form-control bo_poa_generation_business" name="client" id="bo_poa_generation_business_select" >
					
				</select>
				</div>
			</div>
			<div class="tab_services">
				<div class="record-label">Language</div>
				<div>
					<select class="form-control services bo_poa_generation_language" name="language" id="bo_poa_generation_language"> 
						<option value="0">Select</option>
						<option value="English">English</option>
						<option value="Spanish">Spanish</option>
					</select>
				</div>
			</div>	
			<div class="tab_services">
				<div class="record-label">Type</div>
				<div>
					<select class="form-control services bo_poa_generation_type" name="bo_poa_generation_type" id="bo_poa_generation_type"> 
						<option value="0">Select</option>
						<option value="SSN">SSN</option>
						<option value="ITIN">ITIN</option>
					</select>
				</div>
			</div>	
			<div class="tab_services poa-group-th">
				<div class="record-label">Group</div>
				<div>
					<select class="form-control services bo_poa_generation_group" name="group" id="bo_poa_generation_group"> 
						<option value="0">Select</option>
						<option value="Group1">Group 1</option>
						<option value="Group2">Group 2</option>
						<option value="Group3">Group 3</option>
						<option value="Group4">Group 4</option>
						<option value="Group5">Group 5</option>
						<option value="Group6">Group 6</option>
						<option value="Group7">Group 7</option>
						<option value="Group8">Group 8</option>
						<option value="Group9">Group 9</option>
						<option value="Group10">Group 10</option>
					</select>
				</div>
			</div>	
		</div>
		<div>
			<div class="tab_services poa-atorney-th">
				<div class="record-label">Atorney 1</div>
				<div>
				<select class="form-control services bo_poa_generation_atorney" name="atorney_1" id="bo_poa_generation_atorney_1"></select>
				</div>
			</div>
			<div class="tab_services poa-atorney-th">
				<div class="record-label">Atorney 2</div>
				<div>
				<select class="form-control services bo_poa_generation_atorney" name="atorney_2" id="bo_poa_generation_atorney_2"></select>
				</div>
			</div>
			<div class="tab_services poa-atorney-th">
				<div class="record-label">Atorney 3</div>
				<div>
				<select class="form-control services bo_poa_generation_atorney" name="atorney_3" id="bo_poa_generation_atorney_3"></select>
				</div>
			</div>
			<div class="tab_services poa-atorney-th">
				<div class="record-label">Atorney 4</div>
				<div>
				<select class="form-control services bo_poa_generation_atorney" name="atorney_4" id="bo_poa_generation_atorney_4"></select>
				</div>
			</div>
		</div>
		<div  style="margin-top: 10px;">
			<button type="submit" class="btn btn-primary" name="save_tab_poa_generation_data" onclick="saveAllPoaGeneration()"> Generate</button>
			<button type="button" class="btn btn-primary" id="cancel-poa-button"> Cancel</button>
		</div>
	</div>
	<!-- Section end for add poa form -->
	<br>
	<!-- Section start for poa generation table existing records -->
	<div class="example-box-wrapper details_box"  id="reports_box">
		<table id="poa_generation_table" class="table table-striped table-bordered caselist" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Form Type</th> 
					<th>Status</th>
					<th>Source</th>
					<th>Tax Payer</th> 
				</tr>
			</thead>
			<tbody id="tab_poa_generation_tbody">
			</tbody>
		</table>
	</div>
	<!-- Section end of poa generation table existing records -->
