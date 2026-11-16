<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackOfficeDetail extends CI_Controller {

	/*
		Create BackOfficeDetail Constructor 
		Parameters. 
		Author- =
	*/
	public function __construct()
	{
		parent::__construct();
		//$this->db2 = $this->load->database('sugartax', TRUE);
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('BackOfficeModel');	
		$this->load->model('DBmodel');	
	}

	/*########################################
				Common Code Start
	########################################*/
	
	/*
		No Knowledge 
		Parameters. 
		Author- 
	*/
	public function caseReview()
	{	
		$current_ip = $this->input->ip_address();
		$allow = $this->config->item('allowed_ips');   
		if(!in_array($current_ip, $allow)) 
		{
			print "403 forbidden";
			exit();
		}
		else
		{	
			$data['title'] = "CaseReview | CaseReport"; 
			$case_id= $this->input->get('id');
			$data['id'] = $case_id;
			$data['status'] = $this->DBmodel->statusCheck($case_id);
			
			$this->load->view('common/header_report' ,$data);
			$this->load->view('caseReview',$data);
		}
	}

	/*
		Return backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();

		// added on 20-06-2022 https://trello.com/c/X37JleuM/1533-we-need-to-carve-out-a-new-sugar-role-for-robert-rodriguez
		$data['poa_coordinator'] = $this->BackOfficeModel->checkPOACoordinator();
		// end added

		$data['title'] = "CaseReview | BackOffice"; 
		$data['no_header'] = 0;

		// load backoffice detail view page
		$this->load->view('common/header' ,$data);
		$this->load->view('BackOfficeDetailView',$data);		
		$this->load->view('common/footer');
	}

	/*
		Get all data for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function get_data()
	{	
		$case_id= $this->input->post('caseid');	
		$data['title'] = "CaseReview | CaseDetail"; 
		
		//irs standards
		$sugar_irs_standards = $this->DBmodel->sugar_irs_standards($case_id);
		$data['is_admin'] = $this->DBmodel->checkIfAdmin();
				
		//case data from sugar
		//$data['opportunity'] = $this->DBmodel->check_opportunity_in_sugar($case_id); 
		$sum = $this->DBmodel->general_caseid_details($case_id);
		$campaign = $this->DBmodel->fetch_lead_source($case_id);
		$sum_contact = $this->BackOfficeModel->general_contact_details($case_id);
		$total_fee = $this->BackOfficeModel->total_fee_details($case_id);
			
		if(count($sum) > 0)
		{
			$data['summary'] = $sum;
		}
		if(is_array($campaign) && count($campaign) > 0)
		{
			$sum[0]->campaign = $campaign[0]->lead_src;
			$sum[0]->lead_campaign = $campaign[0]->lead_campaign;
		}
		if(is_array($sum_contact) && count($sum_contact) > 0)
		{
			$sum[0]->ctax_status = $sum_contact[0]->ctax_status_c;
			$sum[0]->tap_status = $sum_contact[0]->tap_status_c;
			//print_r($sum);
		}
		if(is_array($total_fee) && count($total_fee) > 0)
		{
			$sum[0]->total_fee = $total_fee[0]->likely_case;
			//print_r($sum);
		}
		$data['lead_campaign_direct_partner'] = $this->DBmodel->fetch_lead_campaign_direct_partner($case_id); // Added on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners	
		echo json_encode($data);
	}
		
	/*
		Get all service list for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function get_services_list()
	{
		$case_id = $this->input->post('caseid');
		$result = $this->BackOfficeModel->get_services($case_id);
		if($result)
		{
			echo json_encode($result);
		}
	}

	/*
		DESC 
		Parameters. 
		Author- 
	*/
	/*public function BO_edit_update_del($class)
	{
		return '<button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

		<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

		<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
				
	}*/
	
	public function BO_edit_update_del($class)
	{
		if($class == "Resolution")
		{
			//return '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="addResolutionRow(this);">  <i class="glyph-icon icon-plus"></i> </button><button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

			return '<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
			
		}
		if($class == "TaxPrepration")
		{
			//return '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_tax_prep_Row(this);">  <i class="glyph-icon icon-plus"></i> </button><button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

			return '<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
			
		}
		if($class ==  "ancl_srvc")
		{
			//return '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_ancl_srvc_Row(this);">  <i class="glyph-icon icon-plus"></i> </button><button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

			return '<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
			
		}
		if($class == "mntrg")
		{
			//return '<button type="button" class="btn btn-xs btn-success add_mntr_new" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="addMonitoringRow(this);">  <i class="glyph-icon icon-plus"></i> </button><button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

			return '<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
			
		}
		else
		{
			return '<button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;    display:none" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

			<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

			<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
			
		}				
	}
	
	/*
		DESC 
		Parameters. 
		Author- 
	*/
	public function BO_edit_update_del2($class)
	{
		return '<button class="btn btn-xs btn-edit" title="Edit" style="background-color: #48C6AC;" onclick="edit_BO_'.$class.'_row(this);"><i class="glyph-icon icon-pencil"></i></button>

		<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;display:none;" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>

		<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
				
	}

	/*
		DESC
		Parameters. 
		Author- 
	*/
	public function BO_update_del($class)
	{
		return '<button class="btn btn-xs btn-update" title="Update" style="background-color: #48C6AC;display:inline" onclick="save_row_BO_'.$class.'(this);"><i class="glyphicon glyphicon-floppy-disk"></i></button>
		<button type="button" class="btn btn-xs btn-danger" id="deleterow" title="Delete Row" style="display: inline;" onclick="delete_BO_'.$class.'(this);"><i class="glyph-icon icon-trash"></i></button> ';
	}

	/*
		Function for get all users from sugar DB 
		Parameters. 
		Author- 
	*/
	public function ddl_BO_related_users($col=null, $role=null)
	{	
		// Below code for get users list from sugar db. Tara(20-Nov-2020)
		$arr = $this->DBmodel->getAllUsers($role);
		$arr = (array)$arr;
		
		$dropdown="";

		if(count($arr) > 0)
		{
			foreach ($arr as $userObject) 
			{
				$userArr = (array) $userObject;
				$key = $userArr['id'];
				$value = $userArr['uname'];
				if($key && $value){

					if($col && $col==$key)
					{
						$dropdown.= '<option value="'.$key.'" selected>'.$value.'</option>';
					}
					else
					{
						$dropdown.= '<option value="'.$key.'" >'.$value.'</option>';
					}
				}
				
			}
		}
		return $dropdown;
	}

	public function BO_RS_business_contact($contactId,$col=null)
	{	
		$result = $this->BackOfficeModel->get_business_contact($contactId);
		$arr = (array)$result;
		
		$dropdown="";
		foreach ($arr as $contactObject) {
			
			$userArr = (array) $contactObject;
			$key = $userArr['id'];
			$value = $userArr['name'];
			if($key && $value){

				if($col && $col==$key)
				{
					$dropdown.= '<option value="'.$key.'" selected>'.$value.'</option>';
				}
				else
				{
					$dropdown.= '<option value="'.$key.'" >'.$value.'</option>';
				}
			}
			
		}
		
		return $dropdown;
	}

	/*public function ddl_BO_return_type($col=null)
	{	
		
		$dropdown = $this->getSugarDropDownByName('taxprep_return_type_list' ,'option', $col=null);
		//print $dropdown;
		foreach ($dropdown as $key=>$value) 
		{
			if($col && $col==$key)
			{
				$dropdown.= '<option value="'.$key.'" selected>'.$value.'</option>';
			}
			else
			{
				$dropdown.= '<option value="'.$key.'" >'.$value.'</option>';
			}
		}
		//return $dropdown;
	}*/
	
	/*
		Function for get dropdown option list from sugar by name paramenter 
		Parameters. Name, col optional if in edit mode
		Author- Tara (20-Nov-2020)
	*/
	public function getSugarDropDownByName($dropDownName ,$format, $col=null)
	{	
		$curl = curl_init();
		$sugar_url = $this->config->item('sugar_url'); 
		curl_setopt_array($curl, array(
		CURLOPT_URL => $sugar_url . "rest/v11_1/getDropdowns",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\r\n  \"dropdowns\":{\"name\": \"$dropDownName\"}\r\n}",
		CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$response_array = '';
		if($response != '')
		{ 
			$response_array_sugar = json_decode($response);
			$response_array = $response_array_sugar->$dropDownName;
		}
		
		// Get the array from json object 
		$arr = array_filter((array) $response_array);

		// If format is option than return select dropdown option html else return array
		if($format == 'option'){
			$dropdown="";
			foreach ($arr as $key => $value) {
				if($col && $col==$key)
				{
					$dropdown.= '<option value="'.$key.'" selected>'.$value.'</option>';
				}
				else
				{
					$dropdown.= '<option value="'.$key.'" >'.$value.'</option>';
				}
			}
			return $dropdown;
		}
		return $arr;
	}

	/*
		Function for provide get users list for create page 
		Parameters. 
		Author- Tara (21-Nov-2020)
	*/
	public function getAtorneyUsers()
	{	
		$BO_poa_gen_tble="";
		
		$allUsers = $this->ddl_BO_related_users();
		echo '<option value="0">Select</option>'.$allUsers;
		exit();
	}

	/*
		Function for get users list for given group 
		Parameters. 
		Author- Tara (01-Dec-2020)
	*/
	public function getUserByGroup()
	{
		$group = $this->input->post('group');
		$users = $this->DBmodel->getUserByGroup($group);
		echo json_encode($users);
	}

	/*
		Validate given inputs and ignore inputs which are coming as second arguments for backoffice detail view page 
		Parameters. Inputs Name and value array, Ignore fields name array 
		Author- 
	*/
	public function validateForm($inputs,$ignoreInputs)
	{
		// Run loop for each field
		foreach($inputs as $inputName=>$inputValue)
		{
			if(!in_array($inputName, $ignoreInputs) && ($inputValue=='0' || $inputValue=="")) 
			{
				return array('status' => 'invalid','desc' => ucfirst(str_replace("_"," ",$inputName)));
			}
		}
		return 'validated';
	}

	/*########################################
				Common Code Start
	########################################*/
	

	/*########################################
			Resolution Module Code Start
	########################################*/
	/*
		Get resolution list for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function getResolution()
	{
		$case_id = $this->input->post('case_id');	
		$table = null;
		$BO_resolution = null;

		//if($this->db2)
		{	
			$BO_resolution = $this->BackOfficeModel->fetch_BO_resolution($case_id);
			$contactId = $this->BackOfficeModel->getContactIdByCaseId($case_id);
		}
		$arr_BO_resolution=(array)($BO_resolution);
		
		//if(count($BO_resolution) > 0)
		if(is_array($BO_resolution) && count($BO_resolution) > 0 && $BO_resolution != 0)
		{
			$BO_RS_tble="";
		
			for($i=0; $i<count($BO_resolution); $i++)
			{
				$reso_cstm_id="";

				if(!$BO_resolution[$i])
				{
					$this->newResolutionRow();
					exit();
				}

				if($BO_resolution[$i] && $BO_resolution[$i]->reso_cstm_id)
				{
					$reso_cstm_id=$BO_resolution[$i]->reso_cstm_id;
				}

				$BO_RS_tble.="<tr reso_id=".$reso_cstm_id." >";
				
				$BO_RS_tble.= '<td style="width: 8%"><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="'.$reso_cstm_id.'" id="reso" data-rliId = "'.$BO_resolution[$i]->rli_id.'"></td> ' ;
				
				$BO_RS_tble.= '<td style="width: 10%" id="actionbtn"> ' ;
				
				// if($i==count($BO_resolution)-1)
				// {
				// 	$BO_RS_tble.= '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="addResolutionRow(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }
				
				$BO_RS_tble.=$this->BO_edit_update_del2('Resolution');
				$BO_RS_tble.='</td> ';

				$BO_RS_tble.= '<td><select class="form-control1 BO_RS_resolution_type" name="resolution_type_c" id="resolution_type_c" disabled> <option value="0">Select</option>'.$this->getSugarDropDownByName('resolution_type_list', 'option' ,$BO_resolution[$i]->resolution_type_c).'</select></td> '; 
				
				$BO_RS_tble.= '<td><select class="form-control1 BO_RS_collection_division" name="collection_division_c" id="collection_division_c" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('collection_division_list', 'option' ,$BO_resolution[$i]->collection_division_c).'</select></td> '; 

				if($BO_resolution != null && is_array($BO_resolution))
				{
					$BO_RS_tble.= '<td><select class="form-control1 BO_RS_advocate_c BO_related_user" name="BO_RS_advocate_c" id="BO_RS_advocate_c" disabled> <option value="0">Select</option>'.$this->ddl_BO_related_users($BO_resolution[$i]->advocate_c,'Advocate').'</select></td> '; 
				}
				else
				{
					$BO_RS_tble.= '<td><select class="form-control1 BO_RS_advocate_c BO_related_user" name="BO_RS_advocate_c" id="BO_RS_advocate_c" disabled> <option value="0">Select</option>'.$this->ddl_BO_related_users('','Advocate').'</select></td> '; 
				}

				if($BO_resolution != null && is_array($BO_resolution))
				{
					$BO_RS_tble.= '<td><select class="form-control1 BO_RS_practitionar_c BO_related_user" name="BO_RS_practitionar_c" id="BO_RS_practitionar_c" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_resolution[$i]->practitionar_c,'Practitionar').'</select></td> '; 
				}
				else
				{
					$BO_RS_tble.= '<td><select class="form-control1 BO_RS_practitionar_c BO_related_user" name="BO_RS_practitionar_c" id="BO_RS_practitionar_c" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select></td> '; 
				}
				$BO_RS_tble.= '<td><select class="form-control1 BO_RS_service_status service_status" name="BO_RS_service_status" id="BO_RS_service_status" disabled>'.$this->getSugarDropDownByName('reso_service_status_c_list', 'option' ,$BO_resolution[$i]->service_status_c).'</select></td> '; 

				$BO_RS_tble.= '<td><select class="form-control1 BO_RS_state" name="BO_RS_state" id="BO_RS_state" disabled> <option value="select">Select</option> '.$this->getSugarDropDownByName('state_dom', 'option' ,$BO_resolution[$i]->state_c).'</select></td> '; 
				
				$BO_RS_tble.= '<td><select class="form-control1 BO_RS_business" name="BO_RS_business" id="BO_RS_business" disabled> <option value="select">Select</option> '.$this->BO_RS_business_contact($contactId,$BO_resolution[$i]->related_business_c).'</select></td> '; 

				$BO_RS_tble.= '<td class="td_escalation_status"><select class="form-control1 BO_RS_escalation_status" name="BO_RS_escalation_status" id="BO_RS_escalation_status" disabled><option value="">Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_resolution[$i]->resolution_escalation_status_c).'</select></td> ';

				//$BO_RS_tble.= '<td class="td_escalation_status"><select class="form-control1 BO_RS_escalation_status" name="BO_RS_escalation_status" id="BO_RS_escalation_status"><option value="">Select</option> '.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' , '').'</select></td> ';

				$BO_RS_tble.=  "</tr>";

			}
			echo $BO_RS_tble;
		}
		/*if( (count($BO_resolution) < 2) && empty($BO_resolution[0]->resolution_type_c) ){
            
        }
        else{ */
            
        //}
		//echo $BO_RS_tble;
		exit();
	}


	/*
		Get new resolution row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function newResolutionRow()
	{	
		
		$case_id = $this->input->post('case_id');	
		$table = null;
		$BO_resolution = null;

		//if($this->db2)
		{	
			$BO_resolution = $this->BackOfficeModel->fetch_BO_resolution($case_id);
			$contactId = $this->BackOfficeModel->getContactIdByCaseId($case_id);
		}
		
		$BO_RS_tble="<tr data-state='save'>";
		
		$BO_RS_tble.= '<td style="width: 8%"><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="new" id="reso" data-rliId = "'.$_POST['rliId'].'" ><input type="hidden" id="rliId" name="rliId" value="'.$_POST['rliId'].'" ></td> ' ;
		
		$BO_RS_tble.=  '<td style="width: 10%" id="actionbtn">';

		$BO_RS_tble.=$this->BO_edit_update_del('Resolution');

		$BO_RS_tble.='</td> ';

		$BO_RS_tble.= '<td><select class="form-control1 BO_RS_resolution_type" name="BO_RS_resolution_type" id="BO_RS_resolution_type"  > <option value="0">Select</option> '.$this->getSugarDropDownByName('resolution_type_list', 'option').' </select></td>';

		$BO_RS_tble.= '<td><select class="form-control1 BO_RS_collection_division" name="collection_division_c" id="collection_division_c"  > <option value="0">Select</option> '.$this->getSugarDropDownByName('collection_division_list', 'option', 'ACS').' </select></td>';

		if($BO_resolution != null && is_array($BO_resolution))
		{
			$BO_RS_tble.='<td><select class="form-control1 BO_RS_advocate_c BO_related_user" name="BO_RS_advocate_c" id="BO_RS_advocate_c"  > <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_resolution[0]->advocate_c,'Advocate').'</select></td> ';
		}
		else
		{
			$BO_RS_tble.='<td><select class="form-control1 BO_RS_advocate_c BO_related_user" name="BO_RS_advocate_c" id="BO_RS_advocate_c"  > <option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select></td> ';	
		}

		if($BO_resolution != null && is_array($BO_resolution))
		{
			$BO_RS_tble.='<td><select class="form-control1 BO_RS_practitionar_c BO_related_user" name="BO_RS_practitionar_c" id="BO_RS_practitionar_c"  > <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_resolution[0]->practitionar_c,'Practitionar').'</select></td> ';
		}
		else
		{
			$BO_RS_tble.='<td><select class="form-control1 BO_RS_practitionar_c BO_related_user" name="BO_RS_practitionar_c" id="BO_RS_practitionar_c"  > <option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select></td> ';
		}
		$BO_RS_tble.= '<td><select class="form-control1 BO_RS_service_status service_status" name="BO_RS_service_status" id="BO_RS_service_status" >'.$this->getSugarDropDownByName('reso_service_status_c_list', 'option').'</select></td> '; 

		$BO_RS_tble.= '<td><select class="form-control1 BO_RS_state" name="BO_RS_state" id="BO_RS_state" > <option value="select">Select</option> '.$this->getSugarDropDownByName('state_dom', 'option', $_POST['clientState']).'</select></td> '; 

		$BO_RS_tble.= '<td><select class="form-control1 BO_RS_business" name="BO_RS_business" id="BO_RS_business"> <option value="select">Select</option> '.$this->BO_RS_business_contact($contactId).'</select></td> ';

		//$BO_RS_tble.= '<td class="td_escalation_status"><select class="form-control1 BO_RS_escalation_status" name="BO_RS_escalation_status" id="BO_RS_escalation_status" disabled><option value="0">Select</option> '.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_resolution[$i]->resolution_escalation_status_c).'</select></td> '; 

		$BO_RS_tble.= '<td class="td_escalation_status"><select class="form-control1 BO_RS_escalation_status" name="BO_RS_escalation_status" id="BO_RS_escalation_status"><option value="" selected>Select</option> '.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' , '').'</select></td> ';

		$BO_RS_tble.=  "</tr>";
	
		echo $BO_RS_tble;
		exit();
	}

	/*
		Update resolution row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function createResolution()
    {
		$result ="";
		$caseId = $this->input->post('case_id');
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','reso_id'];

		$result= $this->validateForm($inputs, $ignoreInputs);
		
		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}
        $service_status = $this->input->post('service_status');
        $collection_division = $this->input->post('collection_division');
        $resolution_type = $this->input->post('resolution_type');
		$practitionar = $this->input->post('practitionar');
		$advocate = $this->input->post('advocate');
		$state = $this->input->post('state');
        $business_contact = $this->input->post('business_contact');
		$escalationStatus = $this->input->post('escalationStatus');
		//return 1;
		$result = $this->BackOfficeModel->createResolution($caseId, $service_status, $collection_division, $resolution_type, $practitionar, $advocate, $state, $business_contact, $_POST['rliId'], $escalationStatus);
		
		echo $result;
		/*if($result['status'] == false)
		{
			echo json_encode(array('status' => $result['status'],'desc' => $result['msg']));
		}
		else
		{
			echo json_encode(array('status' => $result['status'],'desc' => ''));
		}*/
		//return;
	}
	
	/*
		Update resolution row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function updateResolution()
    {
		// Declare all variables and get inputs from form
		$result ="";
		
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','reso_id','rliId'];

		$result= $this->validateForm($inputs, $ignoreInputs);
		
		if($result != 'validated')
		{
			echo json_encode($result);
			return;
		}

        $service_status = $this->input->post('service_status');
        $collection_division = $this->input->post('collection_division');
        $resolution_type = $this->input->post('resolution_type');
		$resolutionId = $this->input->post('reso_id');
		$practitionar = $this->input->post('practitionar');
		$advocate = $this->input->post('advocate');
		$state = $this->input->post('state');
        $business_contact = $this->input->post('business_contact');
		$escalationStatus = $this->input->post('escalationStatus');
        
		$result = $this->BackOfficeModel->updateResolution($service_status, $collection_division, $resolution_type, $practitionar, $advocate, $resolutionId, $state, $business_contact, $escalationStatus);  
		
		echo $result;
		return;
    }

	/*
		Delete resolution row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function deleteResolution()
	{
		$case_id= $this->input->post('caseid');
		$id= $this->input->post('id');  
       
		$result = $this->BackOfficeModel->deleteResolution($case_id, $id);
        if($result)
        {
            echo $result;
        }		
	}
	/*########################################
			Resolution Module Code End
	########################################*/

	/*########################################
			Tax Preparation Module Code End
	########################################*/
	/*
		Get tax preparation records for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function getTaxPrepration()
	{	
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_tax_prepration=null;

		//if($this->db2)
		{	
			$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);
		}
		
		$arr_BO_tax_prepration=(array)($BO_tax_prepration);
		
		if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && $BO_tax_prepration != 0)
		//if(count($BO_tax_prepration) > 0)
		{
			$BO_TP_tble="";

			for($i=0; $i<count($BO_tax_prepration); $i++)
			{
				$tp_cstm_id="";

				if(!$BO_tax_prepration[$i])
				{
					$this->newTaxPreparationRow();
					exit();
				}

				if($BO_tax_prepration[$i]->tp_cstm_id)
				{
					$tp_cstm_id=$BO_tax_prepration[$i]->tp_cstm_id;
				}

				$BO_TP_tble.="<tr tp_cstm_id=".$BO_tax_prepration[$i]->tp_cstm_id." 
				tax_prep_id=".$BO_tax_prepration[$i]->tax_prep_id.">";
				
				$BO_TP_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="'.$BO_tax_prepration[$i]->tax_prep_id.'" id="tax_prep" data-rliId = "'.$BO_tax_prepration[$i]->rli_id.'"></td> ' ;
				
				$BO_TP_tble.= '<td id="actionbtn"> ' ;
				
				// if($i==count($BO_tax_prepration)-1)
				// {
				// 	$BO_TP_tble.=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_tax_prep_Row(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }
				
				$BO_TP_tble.=$this->BO_edit_update_del2('TaxPrepration');

				$BO_TP_tble.='</td> ';
				
				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_return_type BO_return_type" name="BO_TP_return_type" id="BO_TP_return_type " disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('taxprep_return_type_list', 'option' ,$BO_tax_prepration[$i]->return_type_c).'</select></td> '; 

				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_year" name="BO_TP_tax_year" id="BO_TP_tax_year" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('years_list', 'option' ,$BO_tax_prepration[$i]->tax_year_c).'</select></td> '; 
				
				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[$i]->advocate_id,'Advocate').'</select></td> '; 
	
				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer " disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[$i]->tax_preparer_c,'TaxPrep').'</select></td> '; 

				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_service_status service_status tax_prep_service_status" name="BO_TP_service_status" id="BO_TP_service_status" disabled>'.$this->getSugarDropDownByName('taxprep_service_status_list', 'option' ,$BO_tax_prepration[$i]->service_status_c).'</select></td> '; 

				$BO_TP_tble.= '<td class="td_tax_prep_escalation_status"><select class="form-control1 BO_TP_td_tax_prep_escalation_status service_status" name="td_tax_prep_escalation_status" id="td_tax_prep_escalation_status" disabled><option value="" selected>Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_tax_prepration[$i]->tax_prep_escalation_status_c).'</select></td> '; 

				$BO_TP_tble.=  "</tr>";
			}
			echo $BO_TP_tble;
		}

		
		exit();

	}

	//added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
	public function getCtaxPlus()
	{	
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_ctax_plus=null;

		//if($this->db2)
		{	
			$BO_ctax_plus = $this->BackOfficeModel->fetch_BO_ctax_plus($case_id);
		}
		
		$arr_BO_ctax_plus=(array)($BO_ctax_plus);
		
		if(is_array($BO_ctax_plus) && count($BO_ctax_plus) > 0 && $BO_ctax_plus != 0)
		//if(count($BO_ctax_plus) > 0)
		{
			$BO_CP_table="";

			for($i=0; $i<count($BO_ctax_plus); $i++)
			{
				$cp_cstm_id="";

				// if(!$BO_ctax_plus[$i])
				// {
				// 	$this->newTaxPreparationRow();
				// 	exit();
				// }

				if($BO_ctax_plus[$i]->cp_cstm_id)
				{
					$cp_cstm_id=$BO_ctax_plus[$i]->cp_cstm_id;
				}

				$BO_CP_table.="<tr cp_cstm_id=".$BO_ctax_plus[$i]->cp_cstm_id." 
				ctax_plus_id=".$BO_ctax_plus[$i]->ctax_plus_id.">";
				
				$BO_CP_table.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="'.$BO_ctax_plus[$i]->ctax_plus_id.'" id="ctax_plus" data-rliId = "'.$BO_ctax_plus[$i]->rli_id.'"></td> ' ;
				
				$BO_CP_table.= '<td id="actionbtn"> ' ;
				
				// if($i==count($BO_ctax_plus)-1)
				// {
				// 	$BO_CP_table.=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_tax_prep_Row(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }
				
				$BO_CP_table.=$this->BO_edit_update_del2('CtaxPlus');

				$BO_CP_table.='</td> ';
				
				$BO_CP_table.= '<td><input type="text" class="form-control1 BO_CP_name CP_name" name="BO_CP_name" id="BO_CP_name " disabled value="'.$BO_ctax_plus[$i]->name.'"></td> '; 
				$BO_CP_table.= '<td><select class="form-control1 BO_CP_started_year" name="BO_CP_started_year" id="BO_CP_started_year" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('initial_tp_year_included_list', 'option' ,$BO_ctax_plus[$i]->initial_tp_year_included_c).'</select></td> '; 
				
				
				$BO_CP_table.= '<td><input type="date" class="form-control1 BO_CP_ctax_plus_start_date CP_ctax_plus_start_date" name="BO_CP_ctax_plus_start_date" id="BO_CP_ctax_plus_start_date " disabled value="'.$BO_ctax_plus[$i]->ctaxplusstartdate_c.'"></td> '; 
				
				$BO_CP_table.= '<td><select class="form-control1 BO_CP_intro_call" name="BO_CP_intro_call" id="BO_CP_intro_call" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('tap_intro_call_list', 'option' ,$BO_ctax_plus[$i]->ctaxplus_intro_call_c).'</select></td> '; 
				$BO_CP_table.= '<td><select class="form-control1 BO_CP_service_status" name="BO_CP_service_status" id="BO_CP_service_status" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('tap_service_status_list', 'option' ,$BO_ctax_plus[$i]->ctaxplus_service_status_c).'</select></td> '; 

				
				$BO_CP_table.=  "</tr>";
			}
			echo $BO_CP_table;
		}
		exit();
	} // end added on 04-04-2024
	
	/*
		Add a input field in tax preparation for adding multiple rows
		Parameters. 
		Author- 
	*/
	
	public function getFieldTaxPrepration()
	{	
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_tax_prepration=null;

		//if($this->db2)
		{	
			$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);
		}
		
		$arr_BO_tax_prepration=(array)($BO_tax_prepration);
		
		// if(count($BO_tax_prepration) > 0)
		{
			$TP_Field_html="";
			
			$TP_Field_html.='<label>Row Count</label><input class="form-control1" type="number" name="num_rows_TP" id="row_count_TP">   ';
			
			$TP_Field_html.='  <label>Return Type</label><select class="form-control1 BO_TP_return_type BO_return_type" name="BO_TP_return_type" id="num_TP_return_type"> <option value="0">Select</option> '.$this->getSugarDropDownByName('taxprep_return_type_list', 'option', 'W2 1040').'</select>';
		}

		echo $TP_Field_html;
		exit();

	}
	

	/*
		Add tax preparation row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function newTaxPreparationRow()
	{
		
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_tax_prepration=null;

		//if($this->db2)
		{	
			$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);
		}
		
		$BO_TP_tble="<tr data-state='save'>";
		
		$BO_TP_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="new" id="tax_prep"  data-rliId = "'.$_POST['rliId'].'"><input type="hidden" id="rliId" name="rliId" value="'.$_POST['rliId'].'" ></td> ' ;
		
		$BO_TP_tble.= '<td id="actionbtn">' ;
		
		$BO_TP_tble.= $this->BO_edit_update_del('TaxPrepration');

		$BO_TP_tble.= '</td>';

		$BO_TP_tble.= '<td><select class="form-control1 BO_TP_return_type BO_return_type" name="BO_TP_return_type" id="BO_TP_return_type "> <option value="0">Select</option> '.$this->getSugarDropDownByName('taxprep_return_type_list', 'option','W2 1040').'</select></td> '; 

		$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_year" name="taBO_TP_tax_yearx_year" id="BO_TP_tax_year"  > <option value="0">Select</option> '.$this->getSugarDropDownByName('years_list', 'option').'</select></td> '; 

		if($BO_tax_prepration != null && is_array($BO_tax_prepration))
		{
			$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->advocate_id,'Advocate').'</select></td> '; 
		}
		else
		{
			$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select></td> '; 
		}

		if($BO_tax_prepration != null && is_array($BO_tax_prepration))
		{
			$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->tax_preparer_c,'TaxPrep').'</select></td> '; 
		}
		else
		{
			$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxPrep').'</select></td> '; 
		}
		$BO_TP_tble.= '<td><select class="form-control1 BO_TP_service_status service_status tax_prep_service_status" name="BO_TP_service_status" id="BO_TP_service_status">'.$this->getSugarDropDownByName('taxprep_service_status_list', 'option').'</select></td> '; 

		$BO_TP_tble.= '<td class="td_tax_prep_escalation_status"><select class="form-control1 BO_TP_td_tax_prep_escalation_status service_status" name="td_tax_prep_escalation_status" id="td_tax_prep_escalation_status"><option value="" selected>Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_tax_prepration[$i]->tax_prep_escalation_status_c).'</select></td> '; 
		
		$BO_TP_tble.=  "</tr>";
	
		echo $BO_TP_tble;
		exit();

	}
	
	/*
		Add multiple tax preparation row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function multipleNewTaxPreparationRow()
	{
		$case_id= $this->input->post('case_id');
		$rowCountnum= $this->input->post('rowCount');
		$returnTypeSet= $this->input->post('returnType');
		
		$BO_tax_prepration=null;

		//if($this->db2)
		{	
			$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);
		}
		
		$BO_TP_tble="";
		if($rowCountnum > 0)
		{
			for($i=0; $i<$rowCountnum; $i++)
			{
		       
				$BO_TP_tble.="<tr data-state='save'>";
				
				$BO_TP_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="new" id="tax_prep" data-rliId = "'.$_POST['rliId'].'"></td> ' ;
				
				$BO_TP_tble.= '<td id="actionbtn">' ;
				
				// if($i == $rowCountnum-1)
				// {
				// 	$BO_TP_tble.=  '<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_tax_prep_Row(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }
				
				$BO_TP_tble.= $this->BO_update_del('TaxPrepration'); 	
				
				$BO_TP_tble.= '</td>';

				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_return_type BO_return_type" name="BO_TP_return_type" id="BO_TP_return_type ">'.$this->getSugarDropDownByName('taxprep_return_type_list', 'option',$returnTypeSet).'</select></td> ';   

				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_year" name="taBO_TP_tax_yearx_year" id="BO_TP_tax_year"  > <option value="0">Select</option> '.$this->getSugarDropDownByName('years_list', 'option').'</select></td> '; 

				if($BO_tax_prepration != null && is_array($BO_tax_prepration))
				{
					//$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->tax_preparer_c,'TaxPrep').'</select></td> '; 
					$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->advocate_id,'Advocate').'</select></td> '; 
				}
				else
				{
					$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select></td> '; 
					//$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxPrep').'</select></td> '; 
				}

				//$BO_TP_tble.= '<td><select class="form-control1 BO_TP_advocate" name="BO_TP_advocate" id="BO_TP_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->advocate_id,'Advocate').'</select></td> '; 

				if($BO_tax_prepration != null && is_array($BO_tax_prepration))
				{
					$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->tax_preparer_c,'TaxPrep').'</select></td> '; 
				}
				else
				{
					$BO_TP_tble.= '<td><select class="form-control1 BO_TP_tax_preparer BO_related_user" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxPrep').'</select></td> '; 
				}
				$BO_TP_tble.= '<td><select class="form-control1 BO_TP_service_status service_status tax_prep_service_status" name="BO_TP_service_status" id="BO_TP_service_status">'.$this->getSugarDropDownByName('taxprep_service_status_list', 'option').'</select></td> '; 

				$BO_TP_tble.= '<td class="td_tax_prep_escalation_status"><select class="form-control1 BO_TP_td_tax_prep_escalation_status service_status" name="td_tax_prep_escalation_status" id="td_tax_prep_escalation_status"><option value="" selected>Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_tax_prepration[$i]->tax_prep_escalation_status_c).'</select></td> '; 
				
				$BO_TP_tble.=  "</tr>";   
				
		  }
		}
		echo $BO_TP_tble;
		exit();
	}

	/*
		Create(Store) tax preparation record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function createTaxPrepration()
	{
		$result ="";
		
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','tax_prep_id'];

		$result= $this->validateForm($inputs, $ignoreInputs);

		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$case_id = $this->input->post('case_id');
		$tax_year = $this->input->post('tax_year');
		$advocate = $this->input->post('advocate');
		$service_status = $this->input->post('service_status');
		$tax_preparer = $this->input->post('tax_preparer');
		$return_type = $this->input->post('return_type');
		$escalationStatus = $this->input->post('escalationStatus');

		$result = $this->BackOfficeModel->createTaxPrepration($case_id ,$tax_year ,$advocate ,$service_status ,$tax_preparer, $return_type, $_POST['rliId'], $escalationStatus);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	}

	// Added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
	public function createCtaxPlus()
	{
		$result ="";
		
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','ctax_plus_id'];

		$result= $this->validateForm($inputs, $ignoreInputs);

		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$case_id = $this->input->post('case_id');
		$name = $this->input->post('name');
		$year_started = $this->input->post('year_started');
		$start_date = $this->input->post('start_date');
		$intro_call = $this->input->post('intro_call');
		$service_status = $this->input->post('service_status');

		$result = $this->BackOfficeModel->createCtaxPlus($case_id ,$name ,$year_started ,$start_date ,$intro_call, $service_status, $_POST['rliId']);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	} // end added on 04-04-2024

	/*
		Update tax preparation record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function updateTaxPrepration()
	{
		$result ="";
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','tax_prep_id','rliId'];

		$result= $this->validateForm($inputs, $ignoreInputs);

		if($result != 'validated')
		{
			echo json_encode($result);
			return;
		}

		$tax_prep_id = $this->input->post('tax_prep_id');
		$tax_year = $this->input->post('tax_year');
		$advocate = $this->input->post('advocate');
		$service_status = $this->input->post('service_status');
		$tax_preparer = $this->input->post('tax_preparer');
		$return_type = $this->input->post('return_type');
		$escalationStatus = $this->input->post('escalationStatus');
		
		$result = $this->BackOfficeModel->updateTaxPrepration($tax_prep_id,$tax_year ,$advocate ,$service_status ,$tax_preparer, $return_type, $escalationStatus);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	}

	// Added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
	public function updateCtaxPlus()
	{ 
		$result ="";
		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','ctax_plus_id','rliId'];

		// $result= $this->validateForm($inputs, $ignoreInputs);

		// if($result != 'validated')
		// {
		// 	echo json_encode($result);
		// 	file_put_contents('CPUpdate101.txt', $result );
		// 	return;
		// }

		$ctax_plus_id = $this->input->post('ctax_plus_id');
		$name = $this->input->post('name');
		$year_started = $this->input->post('year_started');
		$start_date = $this->input->post('start_date');
		$intro_call = $this->input->post('intro_call');
		$service_status = $this->input->post('service_status');
		
		$result = $this->BackOfficeModel->updateCtaxPlus($ctax_plus_id,$name ,$year_started ,$start_date ,$intro_call, $service_status);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	} // end added on 04-04-2024

	/*
		Delete tax preparation record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function deleteTaxPrepration()
	{
		$case_id= $this->input->post('caseid');
		// $year= $this->input->post('rowid'); 
		$id= $this->input->post('id');  
	
		// $result = $this->DBmodel->delete_pps_rows($case_id, $year, $id);
		$result = $this->BackOfficeModel->deleteTaxPrepration($case_id,$id);
		if($result)
		{
			echo $result;
		}		
	}

	// Added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
	public function deleteCtaxPlus()
	{
		$case_id= $this->input->post('caseid');
		// $year= $this->input->post('rowid'); 
		$id= $this->input->post('id');  
	
		// $result = $this->DBmodel->delete_pps_rows($case_id, $year, $id);
		$result = $this->BackOfficeModel->deleteCtaxPlus($case_id,$id);
		if($result)
		{
			echo $result;
		}		
	} // end added on 04-04-2024
	
	/*
		Add a select field in BO for assign user to multiple records
		Parameters. 
		Author- 
	*/
	
	public function getBOFieldAssignUser()
	{	
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_tax_prepration=null;
			
		$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);		
		
		// if(count($BO_tax_prepration) > 0)
		{
			$BO_Field_html="";
			
			$BO_Field_html.='<label>Tax Preparer</label><select class="form-control1 BO_TP_tax_preparer BO_user_assign" name="BO_TP_tax_preparer" id="BO_TP_tax_preparer"><option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxPrep').'</select>';
			
			$BO_Field_html.='<label class="BO_label_field">Practitioner</label><select class="form-control1 BO_RS_practitionar_c BO_user_assign" name="BO_RS_practitionar_c" id="BO_RS_practitionar_c"  ><option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select>';
			
			$BO_Field_html.='<label class="BO_label_field">Advocate</label><select class="form-control1 BO_TP_advocate BO_user_assign" name="BO_TP_advocate" id="BO_TP_advocate"><option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select>';
		}

		echo $BO_Field_html;
		exit();

	}

	 // Added on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned
	public function getBOFieldCurrentAssignUser()
	{	
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_tax_prepration=null;
		$BO_Field_html="";	
		$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($case_id);		
		//  print_r($BO_tax_prepration);
		//  die();

		if(isset($BO_tax_prepration[0]->preparer_id))
		{
			$tp_id = $BO_tax_prepration[0]->preparer_id;
		}
		else
		{
			$tp_id = '';
		}
		//if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0)
		{
			if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && isset($BO_tax_prepration[0]->preparer_id))
			{
				$BO_Field_html.='<label>Current Tax Preparer</label><select class="form-control1 BO_TP_tax_preparer BO_user_assign" name="BO_TP_tax_preparer" id="Current_BO_TP_tax_preparer"><option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->preparer_id,'TaxPrep').'</select>'; 
			}
			else
			{
				$BO_Field_html.='<label>Current Tax Preparer</label><select class="form-control1 BO_TP_tax_preparer BO_user_assign" name="BO_TP_tax_preparer" id="Current_BO_TP_tax_preparer"><option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxPrep').'</select>'; // $BO_tax_prepration[0]->preparer_id
			}
			
			if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && isset($BO_tax_prepration[0]->practitionar_id))
			{
				$BO_Field_html.='<label class="BO_label_field">Current Practitioner</label><select class="form-control1 BO_RS_practitionar_c BO_user_assign" name="BO_RS_practitionar_c" id="Current_BO_RS_practitionar_c"  ><option value="0">Select</option> '.$this->ddl_BO_related_users( $BO_tax_prepration[0]->practitionar_id,'Practitionar').'</select>'; 
			}
			else
			{
				$BO_Field_html.='<label class="BO_label_field">Current Practitioner</label><select class="form-control1 BO_RS_practitionar_c BO_user_assign" name="BO_RS_practitionar_c" id="Current_BO_RS_practitionar_c"  ><option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select>'; // $BO_tax_prepration[0]->practitionar_id,
			}

			if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && isset($BO_tax_prepration[0]->cadvocate_id))
			{			
				$BO_Field_html.='<label class="BO_label_field">Current Advocate</label><select class="form-control1 BO_TP_advocate BO_user_assign" name="BO_TP_advocate" id="Current_BO_TP_advocate"><option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->cadvocate_id,'Advocate').'</select>'; 
			}
			else
			{
				$BO_Field_html.='<label class="BO_label_field">Current Advocate</label><select class="form-control1 BO_TP_advocate BO_user_assign" name="BO_TP_advocate" id="Current_BO_TP_advocate"><option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select>'; // $BO_tax_prepration[0]->cadvocate_id
			}

			if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && isset($BO_tax_prepration[0]->reviewer_id))
			{
				$BO_Field_html.='<label class="BO_label_field">Current Tax Reviewer</label><select class="form-control1 BO_TP_TaxReviewer BO_user_assign" name="BO_TP_advocate" id="Current_BO_TP_TaxReviewer"><option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->reviewer_id,'TaxReviewer').'</select>'; // 
			}
			else
			{
				$BO_Field_html.='<label class="BO_label_field">Current Tax Reviewer</label><select class="form-control1 BO_TP_TaxReviewer BO_user_assign" name="BO_TP_advocate" id="Current_BO_TP_TaxReviewer"><option value="0">Select</option> '.$this->ddl_BO_related_users('','TaxReviewer').'</select>'; // $BO_tax_prepration[0]->reviewer_id
			}
			
			if(is_array($BO_tax_prepration) && count($BO_tax_prepration) > 0 && isset($BO_tax_prepration[0]->escalation_id))
			{
				$BO_Field_html.='<label class="BO_label_field">Escalation PA</label><select class="form-control1 BO_Escalation_PA BO_user_assign" name="Escalation_PA" id="Current_BO_Escalation_PA"><option value="0">Select</option> '.$this->ddl_BO_related_users($BO_tax_prepration[0]->escalation_id,'EscalationUser').'</select>'; // 
			}
			else
			{
				$BO_Field_html.='<label class="BO_label_field">Escalation PA</label><select class="form-control1 BO_Escalation_PA BO_user_assign" name="Escalation_PA" id="Current_BO_Escalation_PA"><option value="0">Select</option> '.$this->ddl_BO_related_users('','EscalationUser').'</select>'; // $BO_tax_prepration[0]->reviewer_id
			}
			
		}

		echo $BO_Field_html;
		exit();

	} // end added on 12-02-2024
	
	public function assignUserMultipleRecords()
	{
		$caseId = $this->input->post('caseId');
		$BO_tax_preparer = $this->input->post('tax_preparer');
		$RS_practitionar = $this->input->post('practitionar');
		$BO_TP_advocate = $this->input->post('TP_advocate');
        $BO_TP_array = $this->input->post('TP_array');
        $BO_R_array = $this->input->post('R_array');
        $BO_MRS_array = $this->input->post('MRS_array');
        $BO_MO_array = $this->input->post('MO_array');
        $error_msg = '';

		if($BO_TP_array != '')
		{
			$result = $this->BackOfficeModel->assignTaxPrepration($caseId,$BO_TP_array,$BO_tax_preparer ,$BO_TP_advocate); 
			if($result == true)
			{
				//echo "true";
			}
			else
			{
				if($error_msg == '')
				{
					$error_msg = $result;
				}
			} 
		}
		if($BO_R_array != '')
		{
			$result = $this->BackOfficeModel->assignResolution($caseId,$BO_R_array,$RS_practitionar ,$BO_TP_advocate); 
			//print $result;
			if($result == true)
			{
				//echo "true";
			}
			else
			{
				if($error_msg == '')
				{
					$error_msg = $result;
					//print $result;
				}
			}  
		}
		if($BO_MRS_array != '')
		{
			$result = $this->BackOfficeModel->assignResolutionService($caseId,$BO_MRS_array,$RS_practitionar ,$BO_TP_advocate); 
			if($result == true)
			{
				//echo "true";
			}
			else
			{
				if($error_msg == '')
				{
					$error_msg = $result;
				}
			}  
		} 
		if($BO_MO_array != '')
		{
			$result = $this->BackOfficeModel->assignMonitorService($caseId,$BO_MO_array,$RS_practitionar); 
			if($result == true)
			{
				//echo "true";
			}
			else
			{
				if($error_msg == '')
				{
					$error_msg = $result;
				}				
			}  
		} 
		//echo $error_msg;
		//die();
		
        if($error_msg != '')
		{
			echo $error_msg ;
			
        }else{
			echo "true";
		}
		
	}
	
	/*########################################
			Tax Preparation Module Code End
	########################################*/

	/*########################################
		Ancillary Services Module Code Start
	########################################*/
	/*
		Get tax preparation record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function getAnclSrvc()
	{
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_anclSrv=null;

		//if($this->db2)
		{	
			$BO_anclSrv = $this->BackOfficeModel->fetch_BO_anclSrv($case_id);
		}

		$arr_BO_anclSrv=(array)($BO_anclSrv);

		$BO_AS_tble="";
		if(is_array($BO_anclSrv) && count($BO_anclSrv) > 0 && $BO_anclSrv != 0)
		{
			for($i=0; $i<count($BO_anclSrv); $i++)
			{
				if(!$BO_anclSrv[$i])
				{
					$this->newAncillaryServicesRow();
					exit();
				}
			
				$as_cstm_id="";
				$ancl_srvc_id="";

				if($BO_anclSrv[$i]->ancl_srvc_id)
				{
					$as_cstm_id=$BO_anclSrv[$i]->ancl_srvc_id;
				}

				if($BO_anclSrv[$i]->ancl_srvc_id)
				{
					$ancl_srvc_id=$BO_anclSrv[$i]->ancl_srvc_id;
				}

				$BO_AS_tble.="<tr AS_cstm_id=".$as_cstm_id." ancl_srvc_id=".$ancl_srvc_id.">";

				$BO_AS_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="'.$ancl_srvc_id.'" id="ancl_srvc" data-rliId = "'.$BO_anclSrv[$i]->rli_id.'"></td> 
				
				<td id="actionbtn"> ' ;
				
				// if($i==count($BO_anclSrv)-1)
				// {
				// 	$BO_AS_tble.='<button type="button" class="btn btn-xs btn-success" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="add_BO_ancl_srvc_Row(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }

				$BO_AS_tble.= $this->BO_edit_update_del2('ancl_srvc');

				$BO_AS_tble.= '</td>';

				$BO_AS_tble.= '<td><select class="form-control1 BO_AS_service" name="BO_AS_service" id="BO_AS_service" disabled>'.$this->getSugarDropDownByName('ancillary_service_list', 'option' ,$BO_anclSrv[$i]->service_c).'</select></td> '; 

				$BO_AS_tble.='<td><select class="form-control1 BO_AS_advocate BO_related_user" name="BO_AS_advocate" id="BO_AS_advocate" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_anclSrv[$i]->advocate_c,'Advocate').'</select></td> ';

				$BO_AS_tble.='<td><select class="form-control1 BO_AS_practitioner BO_related_user" name="BO_AS_practitioner" id="BO_AS_practitioner" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_anclSrv[$i]->practitioner_c,'Practitionar').'</select></td> '; 

				$BO_AS_tble.= '<td><select class="form-control1 BO_AS_service_status service_status misc_reso_service_status" name="BO_AS_service_status" id="BO_AS_service_status" disabled>'.$this->getSugarDropDownByName('ancillary_service_status_list', 'option' , $BO_anclSrv[$i]->service_status_c).'</select></td> ';

				$BO_AS_tble.= '<td class="td_misc_reso_escalation_status"><select class="form-control1 BO_TP_td_misc_reso_escalation_status service_status" name="td_misc_reso_escalation_status" id="td_misc_reso_escalation_status" disabled><option value="" selected>Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option' ,$BO_anclSrv[$i]->misc_escalation_status_c).'</select></td> '; 
				
				// $BO_AS_tble.='<td><select class="form-control1 BO_AS_tax_preparer BO_related_user" name="BO_AS_tax_preparer" id="BO_AS_tax_preparer" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_anclSrv[$i]->tax_preparer_c).'</select></td> ';

				$BO_AS_tble.=  "</tr>";

			}
			echo $BO_AS_tble;
		}

		
		exit();

	}

	/*
		Create New ancillary services row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function newAncillaryServicesRow()
	{				
			
			$case_id= $this->input->post('case_id');	
			$table=null;
			$BO_anclSrv=null;

			//if($this->db2)
			{	
				$BO_anclSrv = $this->BackOfficeModel->fetch_BO_anclSrv($case_id);
			}
			
			$BO_AS_tble="<tr class='BO_default_AS_row' data-state='save'>";

			$BO_AS_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="new" id="ancl_srvc" data-rliId = "'.$_POST['rliId'].'"><input type="hidden" id="rliId" name="rliId" value="'.$_POST['rliId'].'" ></td> ' ;
			
			$BO_AS_tble.= '<td id="actionbtn">';
			
			$BO_AS_tble.= $this->BO_edit_update_del('ancl_srvc');
			
			$BO_AS_tble.= '</td>';

			$BO_AS_tble.= '<td><select class="form-control1 BO_AS_service" name="BO_AS_service" id="BO_AS_service"> '.$this->getSugarDropDownByName('ancillary_service_list', 'option').'</select></td> '; 

			if($BO_anclSrv != null && is_array($BO_anclSrv))
			{
				$BO_AS_tble.='<td><select class="form-control1 BO_AS_advocate BO_related_user" name="BO_AS_advocate" id="BO_AS_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_anclSrv[0]->advocate_c,'Advocate').'</select></td> ';
			}
			else
			{
				$BO_AS_tble.='<td><select class="form-control1 BO_AS_advocate BO_related_user" name="BO_AS_advocate" id="BO_AS_advocate"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','Advocate').'</select></td> ';
			}
			if($BO_anclSrv != null && is_array($BO_anclSrv))
			{
				$BO_AS_tble.='<td><select class="form-control1 BO_AS_practitioner BO_related_user" name="BO_AS_practitioner" id="BO_AS_practitioner"> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_anclSrv[0]->practitioner_c,'Practitionar').'</select></td> '; 
			}
			else
			{
				$BO_AS_tble.='<td><select class="form-control1 BO_AS_practitioner BO_related_user" name="BO_AS_practitioner" id="BO_AS_practitioner"> <option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select></td> '; 
			}
			$BO_AS_tble.= '<td><select class="form-control1 BO_AS_service_status service_status misc_reso_service_status" name="BO_AS_service_status" id="BO_AS_service_status">'.$this->getSugarDropDownByName('ancillary_service_status_list', 'option').'</select></td> ';

			$BO_AS_tble.= '<td class="td_misc_reso_escalation_status"><select class="form-control1 BO_TP_td_misc_reso_escalation_status service_status" name="td_misc_reso_escalation_status" id="td_misc_reso_escalation_status"><option value="" selected>Select</option>'.$this->getSugarDropDownByName('resolution_escalation_status_list', 'option').'</select></td> '; 

			// $BO_AS_tble.='<td><select class="form-control1 BO_AS_tax_preparer BO_related_user" name="BO_AS_tax_preparer" id="BO_AS_tax_preparer"> <option value="0">Select</option> '.$this->ddl_BO_related_users().'</select></td> ';

			$BO_AS_tble.="</tr>";
		
			echo $BO_AS_tble;
			exit();

	}

	/*
		Save(Store) new ancillary services row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function createAncillaryServices()
	{
		$result ="";

		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','ancl_srvc_id'];

		$result= $this->validateForm($inputs, $ignoreInputs);

		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$case_id = $this->input->post('case_id');
		$service_status = $this->input->post('service_status');
		$service = $this->input->post('service');
		$practitioner = $this->input->post('practitioner');
		$advocate = $this->input->post('advocate');
		$escalationStatus = $this->input->post('escalationStatus');
		
		$result ="";

		$result = $this->BackOfficeModel->createAncillaryServices($case_id, $service_status, $service,$practitioner, $advocate, $_POST['rliId'], $escalationStatus);
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	}

	/*
		Update ancillary services row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function updateAncillaryServices()
	{
		$result ="";

		// Get all inputs
		$inputs = $this->input->post();
		$ignoreInputs = ['case_id','ancl_srvc_id','rliId'];

		$result= $this->validateForm($inputs, $ignoreInputs);

		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$ancl_srvc_id = $this->input->post('ancl_srvc_id');
		$service_status = $this->input->post('service_status');
		$service = $this->input->post('service');
		$practitioner = $this->input->post('practitioner');
		$advocate = $this->input->post('advocate');
		$escalationStatus = $this->input->post('escalationStatus');
		
		$result ="";

		$result = $this->BackOfficeModel->updateAncillaryServices($ancl_srvc_id, $service_status, $service,$practitioner, $advocate, $escalationStatus);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
	}

	/*
		Delete Ancillary Service record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function deleteAncillaryServices()
	{
		$case_id= $this->input->post('caseid');
		$id= $this->input->post('id');  
	
		$result = $this->BackOfficeModel->deleteAncillaryServices($case_id,$id);
		if($result)
		{
			echo $result;
		}		
	}
	/*########################################
		Ancillary Services Module Code End
	########################################*/

	/*########################################
		Monitoring Module Code Start
	########################################*/
	/*
		Get monitoring record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function getMonitoring()
	{
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_monitoring=null;

		//if($this->db2)
		{	
			$BO_monitoring = $this->BackOfficeModel->fetch_BO_monitoring($case_id);
		}
		$arr_BO_monitoring=(array)($BO_monitoring);

		$BO_Mn_tble="";
		//print_r($BO_monitoring);
		if(is_array($BO_monitoring) && count($BO_monitoring) > 0 && $BO_monitoring != 0)
		{
			for($i=0; $i<count($BO_monitoring); $i++)
			{
				$mntr_id="";

				if(!$BO_monitoring[$i])
				{	
					$this->addMonitoringRow();
					exit();
				}
		
				if($BO_monitoring[$i]->mntr_id)
				{
					$mntr_id=$BO_monitoring[$i]->mntr_id;
				}
				
				$BO_Mn_tble.="<tr mntr_id=".$BO_monitoring[$i]->mntr_id." >";
				
				$BO_Mn_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="'.$BO_monitoring[$i]->mntr_id.'" id="mntr" data-rliId = "'.$BO_monitoring[$i]->rli_id.'"></td> ' ;
				
				$BO_Mn_tble.= '<td id="actionbtn"> ' ;
				
				// if($i==count($BO_monitoring)-1)
				// {
				// 	$BO_Mn_tble.='<button type="button" class="btn btn-xs btn-success add_mntr_new" title="AddNew" id="add_new" style="display: inline;margin-right:1%" onClick="addMonitoringRow(this);">  <i class="glyph-icon icon-plus"></i> </button>';
				// }

				$BO_Mn_tble.= $this->BO_edit_update_del2('mntrg');

				$BO_Mn_tble.= '</td>';

				//$BO_Mn_tble.= '<td><select class="form-control1 tap_service_c service_status" name="tap_service_c" id="tap_service_c" disabled> <option value="0">Select</option> '.$this->getSugarDropDownByName('monitoring_tap_service_list', 'option' ,$BO_monitoring[$i]->tap_service_c).'</select></td> '; 

				$BO_Mn_tble.='<td><input type="text" id="tap_service_started_'.$i.'" class="tap_service_started" value="'.$BO_monitoring[$i]->tap_service_started_c.'" readonly></td> '; 

				$BO_Mn_tble.= '<td><select class="form-control1 tap_service_status_c service_status" name="tap_service_status_c" id="tap_service_status_c" disabled>'.$this->getSugarDropDownByName('tap_service_status_list', 'option' ,$BO_monitoring[$i]->tap_service_status_c).'</select></td> '; 

				$BO_Mn_tble.= '<td><select class="form-control1 BO_MN_practitionar_c BO_MN_related_user" name="BO_MN_practitionar_c" id="BO_MN_practitionar_c" disabled> <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_monitoring[$i]->practitioner_c,'Practitionar').'</select></td> '; 

				$BO_Mn_tble.=  "</tr>";
			}
			echo $BO_Mn_tble;
		}
		/*print count($BO_monitoring) . ' - ' . 
		if( count($BO_monitoring) > 0 && $BO_monitoring != 0 )
		{
			echo $BO_Mn_tble;
        }*/
        
		//echo $BO_Mn_tble;
		exit();

	}

	/*
		Create new monitoring row for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function addMonitoringRow()
	{
		
		$case_id= $this->input->post('case_id');	
		$table=null;
		$BO_monitoring=null;

		//if($this->db2)
		{	
			$BO_monitoring = $this->BackOfficeModel->fetch_BO_monitoring($case_id);
		}
		
		$BO_Mn_tble="<tr data-state='save'>";
		
		$BO_Mn_tble.= '<td><input name="selector[]" class="form-check-input assignment_cases" type="checkbox" value="new" id="mntr" data-rliId = "'.$_POST['rliId'].'"><input type="hidden" id="rliId" name="rliId" value="'.$_POST['rliId'].'" ></td> ' ;
		
		$BO_Mn_tble.= '<td id="actionbtn">' ;
		
		$BO_Mn_tble.= $this->BO_edit_update_del('mntrg');

		$BO_Mn_tble.= '</td>';
		$statuses = $this->getSugarDropDownByName('tap_service_status_list','Array');
		$dropdown = '';

		// Run loop for make selected intro call option default
		foreach ($statuses as $key => $value) {
			if($key == 'Open File'){
				$dropdown.= '<option value="'.$key.'" selected>'.$value.'</option>';
			}else{
				$dropdown.= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		

		//$BO_Mn_tble.= '<td><select class="form-control1 BO_MN_service service_status tap_service_c" name="BO_MN_service" id="BO_MN_service"> <option value="0">Select</option> '.$this->getSugarDropDownByName('monitoring_tap_service_list', 'option').'</select></td> '; 

		$BO_Mn_tble.='<td><input type="text" id="tap_service_started" class="tap_service_started" ></td> '; 

		$BO_Mn_tble.= '<td><select class="form-control1 BO_MN_service service_status tap_service_status_c" name="BO_MN_service" id="BO_MN_service">'.$dropdown.'</select></td> '; 

		if($BO_monitoring != null && is_array($BO_monitoring))
		{
			$BO_Mn_tble.= '<td><select class="form-control1 BO_MN_practitionar_c BO_MN_related_user" name="BO_MN_practitionar_c" id="BO_MN_practitionar_c" > <option value="0">Select</option> '.$this->ddl_BO_related_users($BO_monitoring[0]->practitioner_c,'Practitionar').'</select></td> '; 
		}
		else
		{
			$BO_Mn_tble.= '<td><select class="form-control1 BO_MN_practitionar_c BO_MN_related_user" name="BO_MN_practitionar_c" id="BO_MN_practitionar_c" > <option value="0">Select</option> '.$this->ddl_BO_related_users('','Practitionar').'</select></td> '; 
		}
		$BO_Mn_tble.=  "</tr>";
	
		echo $BO_Mn_tble;
		exit();
	}
	
	/*
		Save (Store) new monitoring record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function createMonitoring()
	{
		$result ="";

		// Get all inputs
		$inputs = $this->input->post();
		// $ignoreInputs = ['case_id','mntr_id'];

		// Validate all monitoring form values
		$result= $this->validateMonitoring($inputs);
		
		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$case_id = $this->input->post('case_id');
		$tap_service_status_c = $this->input->post('tap_service_status_c');
		//$tap_service_c = $this->input->post('tap_service_c');
		$tap_service_started1_c = $this->input->post('tap_service_started1_c');
		$tapPractitoner = $this->input->post('tapPractitoner');
		
		$result ="";
		
		$result = $this->BackOfficeModel->createMonitoring($case_id, $tap_service_status_c, $tap_service_started1_c, $tapPractitoner, $_POST['rliId']);  
		
		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
		
	}

	/*
		Update monitoring record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function updateMonitoring()
	{
		$result ="";

		// Get all inputs
		$inputs = $this->input->post();
		// $ignoreInputs = ['case_id','mntr_id'];
		$ignoreInputs = ['rliId'];

		// Validate all monitoring form values
		$result= $this->validateMonitoring($inputs, $ignoreInputs);
		
		if($result!='validated')
		{
			echo json_encode($result);
			return;
		}

		$mntr_id = $this->input->post('mntr_id');
		$tap_service_status_c = $this->input->post('tap_service_status_c');
		//$tap_service_c = $this->input->post('tap_service_c');
		$tap_service_started1_c = $this->input->post('tap_service_started1_c');
		$tapPractitoner = $this->input->post('tapPractitoner');

		$result ="";
		$result = $this->BackOfficeModel->updateMonitoring($mntr_id, $tap_service_status_c,  $tap_service_started1_c, $tapPractitoner);

		echo $result; //json_encode(array('status' => $result,'desc' => ''));
		return;
		
	}

	/*
		Delete monitoring record for backoffice detail view page 
		Parameters. 
		Author- 
	*/
	public function deleteMonitoring()
	{
		$case_id = $this->input->post('caseid');
		$monitoringId = $this->input->post('id');  
	
		$result = $this->BackOfficeModel->deleteMonitoring($case_id, $monitoringId);

		if($result)
		{
			echo $result;
		}		
	}

	/*
		Validate monitoring module form inputs 
		Parameters. 
		Author- 
	*/
	public function validateMonitoring($input, $ignoreInputs = [])
	{
		foreach($input as $k=>$v)
		{
			if(!in_array($k, $ignoreInputs) && $k!='mntr_id' && $k!='case_id' && ($v=='0' || $v=="")) 
			{
				$k=rtrim(str_replace("tap"," ",$k), ' c');

				$k=rtrim($k, ' 1');

				return array('status' => 'invalid','desc' => ucfirst(str_replace("_"," ",$k)));
			}
		}
		return 'validated';
	}

	/*########################################
		Monitoring Module Code End
	########################################*/

	
	/*########################################
			POA Generation Code Start
	########################################*/
	/*
		Function for get all sugar poa generation records 
		Parameters. 
		Author- Tara (24-Nov-2020)
	*/
	public function getAllPoaGeneration()
	{	
		$caseId= $this->input->post('case_id');	
		$table=null;
		$poaGeneration=null;
		$sourceLists = $this->getSugarDropDownByName('poa_source_list','array');
		//if($this->db2)
		{	
			$poaGeneration = $this->BackOfficeModel->fetchBoPoaGeneration($caseId);
		}
		
		$arrPoaGeneration = array_filter((array)($poaGeneration));
		$BO_TP_tble="";
		
		if(count($arrPoaGeneration) > 0)
		{
			for($i=0; $i<count($arrPoaGeneration); $i++)
			{
				$BO_TP_tble.="<tr>";
				
				$BO_TP_tble.= '<td>'.$arrPoaGeneration[$i]->form_type.'</td> '; 
				
				$BO_TP_tble.= '<td>'.$arrPoaGeneration[$i]->status.'</td> '; 
				
				$source = '';
				// If sugar value exits in source list array then get tha actual value of dropdown
				if($arrPoaGeneration[$i]->poa_source_c && array_key_exists($arrPoaGeneration[$i]->poa_source_c, $sourceLists)){
					$source = $sourceLists[$arrPoaGeneration[$i]->poa_source_c];
				}
				$BO_TP_tble.= '<td>'.$source.'</td> ';

				$BO_TP_tble.= '<td>'.$arrPoaGeneration[$i]->tax_payer.'</td>'; 

				$BO_TP_tble.=  "</tr>";
			}
		}
		echo $BO_TP_tble;
		exit();
	}

	/*########################################
				POA Generation Code End
	########################################*/

	public function completeBackOffice()
	{
		$caseId= $this->input->post('caseId');

		if(isset($_REQUEST['attIDs']))
		{
			$attIDs = $_REQUEST['attIDs'];	
		}
		else
		{
			$attIDs = '';
		}						
		$result = $this->BackOfficeModel->completeBackOfficeTask($caseId, $attIDs);
		
		if($result)
		{
			echo $result;
		}
	}
	
		/*
		Function for get all summary of BO records in popup
		Parameters. 
		Author- 
	*/
	
	public function getSummeryBackOffice()
	{
		$caseId= $this->input->post('caseId');							
		$BO_resolution = null;
		$BO_tax_prepration=null;
		$BO_anclSrv=null;
		$userGroup=null;
			
		$BO_resolution = $this->BackOfficeModel->fetch_BO_resolution($caseId);
		$BO_tax_prepration = $this->BackOfficeModel->fetch_BO_tax_prepration($caseId);
		$BO_anclSrv = $this->BackOfficeModel->fetch_BO_anclSrv($caseId);
		$BO_monitoring = $this->BackOfficeModel->fetch_BO_monitoring($caseId);
		
		
		$BO_complete="";
		
		//Resolution
		$BO_complete .= '<label class="summery_label">Resolution</label><br>';
		
		if($BO_resolution != null && is_array($BO_resolution))
		{
			for($i=0; $i<count($BO_resolution); $i++)
			{
				$advocate[] = $this->BackOfficeModel->getUserById($BO_resolution[$i]->advocate_c);
				$practitionar[] = $this->BackOfficeModel->getUserById($BO_resolution[$i]->practitionar_c);
			}
		
			$RS_ad_name = implode(', ',array_unique($advocate));
			$RS_p_name = implode(', ',array_unique($practitionar));
			$BO_complete .= '<span class="summary_head">Advocate - </span> <span class="assign_summ">'.$RS_ad_name.'</span><br><span class="summary_head">Practitioner - </span> <span class="assign_summ">'.$RS_p_name.'</span><br><br>';
		}

		//Tax Preparation
		$BO_complete .= '<label class="summery_label">Tax Preparation</label><br>';

		if($BO_tax_prepration != null && is_array($BO_tax_prepration))
		{
			for($i=0; $i<count($BO_tax_prepration); $i++)
			{
				$advocate[] = $this->BackOfficeModel->getUserById($BO_tax_prepration[$i]->advocate_id);
				$tax_prep[] = $this->BackOfficeModel->getUserById($BO_tax_prepration[$i]->tax_preparer_c);
			}
			$TP_ad_name = implode(', ',array_unique($advocate));
			$TP_name = implode(', ',array_unique($tax_prep));
			$BO_complete .= '<span class="summary_head">Advocate - </span> <span class="assign_summ">'.$TP_ad_name.'</span><br><span class="summary_head">Tax Prep - </span> <span class="assign_summ">'.$TP_name.'</span><br><br>';
		}

		//Resolution Services
		$BO_complete .= '<label class="summery_label">Miscellaneous Resolution Services</label><br>';

		if($BO_anclSrv != null && is_array($BO_anclSrv))
		{
			for($i=0; $i<count($BO_anclSrv); $i++)
			{
				$advocate[] = $this->BackOfficeModel->getUserById($BO_anclSrv[$i]->advocate_c);
				$practitionar[] = $this->BackOfficeModel->getUserById($BO_anclSrv[$i]->practitioner_c);
			}
			$RS_ad_name = implode(', ',array_unique($advocate));
			$RS_p_name = implode(', ',array_unique($practitionar));
			$BO_complete .= '<span class="summary_head">Advocate - </span> <span class="assign_summ">'.$RS_ad_name.'</span><br><span class="summary_head">Practitioner - </span> <span class="assign_summ">'.$RS_p_name.'</span><br><br>';
		}	

		//Tax Assurance Program
		if($BO_monitoring != null && is_array($BO_monitoring))
		{
			$BO_complete .= '<label class="summery_label">Tax Assurance Program</label><br>'; 
			for($i=0; $i<count($BO_monitoring); $i++)
			{
				$practitionar[] = $this->BackOfficeModel->getUserById($BO_monitoring[$i]->practitioner_c);
			}
			$TS_name = implode(', ',array_unique($practitionar));
			$BO_complete .= '<span class="summary_head">Practitioner - </span> <span class="assign_summ">'.$TS_name.'</span><br>';
		}	
		
		if($BO_resolution != null && is_array($BO_resolution))
        { 
			if($BO_resolution[0]->practitionar_c != '')
			{
				$userGroup = $this->BackOfficeModel->getGroupUsers($BO_resolution[0]->practitionar_c);
				
				if($userGroup != 0)
				{
					$UserMemNames =  implode(',',$userGroup['attNames']);
					$UserMemIDS =  implode(',',$userGroup['attIds']);
					$BO_complete .= '<label class="summery_label">Atorneys For POA</label><br>'; 
					$BO_complete .= '<span class="summary_head">Atorneys Names - </span> <input type="hidden" id="getIDs" name="getIDs" value="'.$UserMemIDS.'"><span class="assign_summ">'.$UserMemNames.'</span><br><br>';
				} 
				else
				{
					$BO_complete .= '<input type="hidden" id="poa_error" name="poa_error" value="1">';
				} 
			} 
			else
			{
				$BO_complete .= '<input type="hidden" id="poa_error" name="poa_error" value="1">';
			}
        }
		echo $BO_complete; 
		//exit();
		
	}

	public function getLinkData()
	{
		$rliId= $this->input->post('rliId');
		$caseId = $this->input->post('caseId');
		$result = $this->BackOfficeModel->getLinkData($rliId, $caseId);  
		// echo $result;
		print_r($result);
		return $result;

	}

	public function getNotLinkedTaxInvestigation()
	{
		$caseId = $this->input->post('caseId'); 
		
		if(!empty($caseId))
		{
			$res = array();
			$resultobj = $this->BackOfficeModel->getNotLinkedTaxInvestigation($caseId);
			for($i = 0; $i < count($resultobj); $i++)
			{
				array_push($res, $resultobj[$i]->id);
			
			}
			print_r(json_encode($res));
		}
		else{
			echo false;
		}		
	}

	//added on 23-06-2022
	public function getBusiness()
	{
		if($_POST['caseId'])
		{
			$resultobj = $this->BackOfficeModel->getBusiness($_POST['caseId']);
		}
		print_r(json_encode($resultobj));
	}
	
	// public function getBusiness()
	// {
	// 	if($_POST['caseId'])
	// 	{
	// 		$resultobj = $this->BackOfficeModel->getBusiness($_POST['caseId']);
	// 	}
	// 	print_r(json_encode($resultobj));
	// }

	 // Added on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned
	public function Update_Current_BO_User()
	{
		$caseId = $this->input->post('caseId');
		$BO_tax_preparer = $this->input->post('tax_preparer');
		$BO_practitionar = $this->input->post('practitionar');
		$BO_TP_advocate = $this->input->post('TP_advocate');
        $TP_TaxReviewer = $this->input->post('TP_TaxReviewer');
        $PA_Escalation = $this->input->post('PA_Escalation');
		//echo "TP_TaxReviewer 2072=".$TP_TaxReviewer;
		$result = $this->BackOfficeModel->Update_Current_BO_User($caseId, $BO_tax_preparer, $BO_practitionar, $BO_TP_advocate, $TP_TaxReviewer, $PA_Escalation); 
		$error_msg = '';

		if($result == true)
		{
			echo "true";
		}
		else
		{
			if($error_msg == '')
			{
				$error_msg = $result;
			}
		} 
		
	} // end added on 12-02-2024
	
}