<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Casedetail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		/*try
		{
			$this->db2 = $this->load->database('sugartax', TRUE);
			//print $this->db2;
			
			if($this->db2 !== false)
			{
				$connected = $this->db2->initialize();
				if (!$connected) 
				{
					print 'NOT CONNECTED';
				}
			}
		}
		catch (Exception $e) {
			
			//log_message('error: ',$e->getMessage());
			//return;
		}*/
		
		/*$servername = "50.237.42.179";
		$username = "review_user";
		$password = "ct@xn@blasol123";
		$database_name = "communitytax";

		$db = mysqli_connect($servername,$username,$password,$database_name);

		// Check connection

		if (mysqli_connect_errno()) 
		{
			//printf("Connect failed: %s\n", mysqli_connect_error());
		   
		} 
		else
		{
			$this->db2 = $db;
		}*/
		
		$this->db2 = ''; // $this->load->database('sugartax', TRUE);
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');		

		// added on 07-11-2025
		$current_ip = $this->input->ip_address();
		
		$allow = $this->config->item('allowed_ips');   
		
		if(!in_array($current_ip, $allow)) 
		{
			print "403 forbidden";
			exit();
		}
	}
	
	public function records() 
	{	
		$user_type = $this->session->userdata('user_typeid');
		session_write_close();	
			
		$data['title'] = "CaseReview | CaseDetail"; 
		$data['sugar_url'] = $this->config->item('sugar_url');  
		
		$my_cases = $this->DBmodel->getMyCases();
			
		if(is_array($my_cases) && count($my_cases) > 0)
		{
			$data['mycases'] = $my_cases;				
		}
		else 
		{			
			$data['mycases'] = 0;
		}
		
		$data['no_header'] = 0;
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		$data['is_prac'] = $this->DBmodel->checkPracRole();
		
		$data['user_list'] = $this->DBmodel->getAllUsersList();
		$dropdown = $this->getSugarDropDownByName('timezone_dom', 'array', $col=null);
		$data['time_zone'] = $dropdown;
		$dropdownReminder = $this->getSugarDropDownByName('reminder_time_options', 'array', $col=null);
		$data['reminder_time'] = $dropdownReminder;

		$this->load->view('common/header' ,$data);
		$this->load->view('casedetail',$data);		
		$this->load->view('common/footer');
	}	
	
	public function get_data()
	{
		$account_stat_data = ''; 
		$penalty_data = ''; 
		$activity_data = '';
		$cp = ''; 
		$csed_data = '';
		$ths_data = '';
			
		$case_id= $this->input->post('caseid');	

		$webhookParam = $this->input->post('webhook_param')?$this->input->post('webhook_param'):'';	

		$data['title'] = "CaseReview | CaseDetail"; 
		
		//irs standards
		$sugar_irs_standards = $this->DBmodel->sugar_irs_standards($case_id);
		
		$data['is_admin'] = $this->DBmodel->checkIfAdmin();
		$data['is_reviewer'] = $this->DBmodel->checkIfReviewer();
		$data['is_practitioner'] = $this->DBmodel->checkIfPractitioner();
		//$doc_list = $this->DBmodel->getDocstarList($case_id);
		
		//pps checks from sugar
		$data['checkpps'] = $this->DBmodel->check_pps($case_id);
		$data['checkpps_sp'] = $this->DBmodel->check_pps_spouse($case_id);
		$data['checkpps_premium'] = $this->DBmodel->check_pps_premium($case_id);
		$data['checkpps_premium_spouse'] = $this->DBmodel->check_pps_premium_spouse($case_id);
		$data['checkpps_extra'] = $this->DBmodel->check_extra_pps($case_id);
		$data['checkpps_statepers'] = $this->DBmodel->check_pps_state_pers($case_id);	
		$data['opportunity_data'] = $this->DBmodel->get_opportunity_data($case_id); //Added on 08-12-2023 https://trello.com/c/6f8jcha1/742-display-new-opportunity-fields-discovery-part-1-2-tabs-in-cr-portal	
		//$data['check_old_pps_personal'] = $this->DBmodel->check_old_pps_personal($case_id);	
		//$data['check_old_pps_spouse'] = $this->DBmodel->check_old_pps_spouse($case_id);	
		//$data['check_old_pps_state'] = $this->DBmodel->check_old_pps_state($case_id);	
		//$data['checkpps_status'] = $this->DBmodel->check_all_pps_status($case_id);		
		
		//data from sugartax server
		if($this->db2)
		{			
			$account_stat_data = $this->DBmodel->fetch_caseid_data($case_id);
			$penalty_data = $this->DBmodel->fetch_penalty_data($case_id);
			$activity_data = $this->DBmodel->fetch_resolution_data($case_id); 
			$cp = $this->DBmodel->fetch_civil_penalty_data($case_id);
			$csed_data = $this->DBmodel->fetch_csed_data($case_id);
			$ths_data = $this->DBmodel->fetch_ths_data($case_id);
		}
		
		//case data from sugar
		$data['opportunity'] = $this->DBmodel->check_opportunity_in_sugar($case_id); 
		$sum = $this->DBmodel->general_caseid_details($case_id);
		$extra_cases = $this->DBmodel->get_other_caseids($case_id);
		
		$aggregate = $this->DBmodel->caseid_aggregate_details($case_id);
		$notes = $this->DBmodel->get_notes_all($case_id); //summary notes
		
		//pps data
		$pps_sum = $this->DBmodel->fetch_pps_summary($case_id);		
		$pps_sum_premium = $this->DBmodel->fetch_pps_summary_premium($case_id);		
		$pps_sum_premium_spouse = $this->DBmodel->fetch_pps_summary_premium_spouse($case_id);		
		$all_case_pps = $this->DBmodel->fetch_all_case_pps($case_id);
		$pps = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps']);		
		$pps_premium = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps_premium']);		
		$pps_premium_spouse = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps_premium_spouse']);		
		//added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
		$pps_civil_penalties = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps'],'Civil Penalties');		
		$pps_shared_responsibility = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps'],'Shared Responsibility');		
		$pps_resoultion_present = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps'],'Resolution Present');		
		$pps_resoultion_present_premium = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps_premium'],'Resolution Present Premium');		
		$pps_resoultion_present_premium_spouse = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps_premium_spouse'],'Resolution Present Premium');	
		
		//end added on 07-10-2022
		$pps_spouse = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp']);
		//Added on 03-11-2022
		$pps_sp_civil_penalties = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp'],'Civil Penalties');
		$pps_sp_shared_responsibility = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp'],'Shared Responsibility');		
		$pps_sp_resoultion_present = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp'],'Resolution Present');		
		$pps_sp_income_information = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp'],'Income Information');
		// end Added on 03-11-2022
		$pps_premium_civil_penalties = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps_premium'],'Civil Penalties');
		$pps_premium_shared_responsibility = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps_premium'],'Shared Responsibility');		
		$pps_premium_resoultion_present = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps_premium'],'Resolution Present');		
		
		$pps_premium_spouse_civil_penalties = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps_premium_spouse'],'Civil Penalties');
		$pps_premium_spouse_shared_responsibility = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps_premium_spouse'],'Shared Responsibility');		
		$pps_premium_spouse_resoultion_present = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps_premium_spouse'],'Resolution Present');		

		// file_put_contents('ppsResoPremSP.txt', '173 :: pps_premium_spouse_resoultion_present - '.print_r($pps_premium_spouse_resoultion_present, true)."\n", FILE_APPEND);

		$pps_state_personel = $this->DBmodel->fetch_pps_state_personel_sugar($data['checkpps_statepers']);
		$pps_notes = $this->DBmodel->get_pps_notes_sugar($data['checkpps']);
		$pps_notes_premium = $this->DBmodel->get_pps_notes_sugar_premium($data['checkpps_premium']);
		$pps_notes_premium_spouse = $this->DBmodel->get_pps_notes_sugar_premium($data['checkpps_premium_spouse']);
		$pps_notes_state_personal = $this->DBmodel->get_pps_notes_state_personal($data['checkpps_statepers']);
		$pps_notes_spouse = $this->DBmodel->get_pps_notes_spouse_sugar($data['checkpps_sp']);
		$pps_users = $this->DBmodel->getInvestigationAdvocateUsers($case_id);
		
		//stated interview data	
		$dependent = $this->DBmodel->getDependentInfo($case_id);
		$data['asset_bank_acc'] = $this->DBmodel->assets_bank_amount($case_id);
		$data['additional_income'] = $this->DBmodel->additional_income_details($case_id);
		$data['additional_income_details'] = $this->DBmodel->additional_income_details_new($case_id);
		$data['status'] = $this->DBmodel->get_case_status($case_id);
		$assets = $this->DBmodel->assets_details($case_id);			
		$vehicles = $this->DBmodel->vehicle_expense($case_id);
		$real_estate = $this->DBmodel->real_estate_details($case_id);		
		$fin_overview = $this->DBmodel->fin_overview_details($case_id);
		$additional_expense = $this->DBmodel->additional_monthly_expense($case_id);
		$reported_inc = $this->DBmodel->fetch_reported_income($case_id);
		$notes_si = $this->DBmodel->get_stated_interview_notes($case_id);
		$total_pps_balance = $this->DBmodel->get_pps_total_balance($case_id); // Added on 31-08-2023 https://trello.com/c/PodR8zl0/435-investigation-summary-letter-fixes
		
		//tax case intake
		$tc_intake = $this->DBmodel->fetch_tax_case_intake($case_id);
		
		//finance
		$finance = $this->DBmodel->fetch_finance_info($case_id); 

		//case -review
		$casereview_data = $this->DBmodel->fetch_casereview_data($case_id);

		$data['lead_campaign'] = $this->DBmodel->fetch_lead_campaign($case_id);

		$data['lead_src'] = $this->DBmodel->fetch_lead_source($case_id); // added on 25-07-2022

		$sugarTaxPrep_data = $this->DBmodel->getcomplianceTaxPrep($case_id);

		$gong_details = $this->DBmodel->fetch_gong_notes_details($case_id); // Added on 03-03-2025 for Gong Notes https://trello.com/c/dDJftjdb/2039-cr-portal-gong-notes-to-federal-intake

		//print_r($reported_inc);  
		if(is_array($sugarTaxPrep_data) && count($sugarTaxPrep_data) > 0)
		{
			$data['sugarTaxPrep_data'] = $sugarTaxPrep_data; 
		}
		if(is_array($casereview_data) && count($casereview_data) > 0)
		{
			$data['casereview_data'] = $casereview_data; 
		}
		if(is_array($dependent) && count($dependent) > 0)
		{
			$data['dependents'] = $dependent; 
		}
		if(is_array($account_stat_data) && count($account_stat_data) > 0)
		{
			$data['account_stat_data'] = $account_stat_data; 
		}
		else
		{
			$data['account_stat_data'] = '';
		}
		if(is_array($activity_data) && count($activity_data) > 0)
		{
			$data['activity_data'] = $activity_data; 
		}
		else
		{
			$data['activity_data'] = '';
		}
		if(is_array($penalty_data) && count($penalty_data) > 0)
		{
			$data['penalty_data'] = $penalty_data;
		}
		else
		{
			$data['penalty_data'] = '';
		}
		if(is_array($csed_data) && count($csed_data) > 0)
		{
			$data['csed_data'] = $csed_data; 
		}
		else
		{
			$data['csed_data'] = '';
		}
		if(is_array($ths_data) && count($ths_data) > 0)
		{
			$data['ths_data'] = $ths_data; 
		}
		else
		{
			$data['ths_data'] = '';
		}
		if($cp != 0 && is_array($cp) && count($cp) > 0)
		{
			$data['civil_penalty_data'] = $cp; 
		}
		else
		{
			$data['civil_penalty_data'] = '';
		}
		if(is_array($sum) && count($sum) > 0)
		{
			$data['summary'] = $sum;
		} 
		if(is_array($extra_cases) && count($extra_cases) > 0)
		{
			$data['extra_cases'] = $extra_cases;
		}
		if(is_array($aggregate) && count($aggregate) > 0)
		{
			$data['aggregate'] = $aggregate;
		}
		if(is_array($pps_sum) && count($pps_sum) > 0)
		{
			$data['pps_summary'] = $pps_sum;
		}
		if(is_array($pps_sum_premium) && count($pps_sum_premium) > 0)
		{
			$data['pps_summary_premium'] = $pps_sum_premium;
		}
		if(is_array($pps_sum_premium_spouse) && count($pps_sum_premium_spouse) > 0)
		{
			$data['pps_summary_premium_spouse'] = $pps_sum_premium_spouse;
		}
		if(is_array($all_case_pps) && count($all_case_pps) > 0)
		{
			$data['all_case_pps'] = $all_case_pps;
		}		
		if(is_array($notes) && count($notes) > 0)
		{
			$data['notes'] = $notes;
		} 
		if(is_array($notes_si) && count($notes_si) > 0)
		{
			$data['notes_si'] = $notes_si;
		} 
		if(is_array($pps_notes) && count($pps_notes) > 0)
		{
			$data['pps_notes'] = $pps_notes;
		}
		if(is_array($pps_notes_premium) && count($pps_notes_premium) > 0)
		{
			$data['pps_notes_premium'] = $pps_notes_premium;
		}
		if(is_array($pps_notes_premium_spouse) && count($pps_notes_premium_spouse) > 0)
		{
			$data['pps_notes_premium_spouse'] = $pps_notes_premium_spouse;
		}
		if(is_array($pps_notes_spouse) && count($pps_notes_spouse) > 0)
		{
			$data['pps_notes_spouse'] = $pps_notes_spouse;
		}
		if(is_array($pps_notes_state_personal) && count($pps_notes_state_personal) > 0)
		{
			$data['pps_notes_state_personal'] = $pps_notes_state_personal;
		}
		if(is_array($pps) && count($pps) > 0)
		{
			$data['pps'] = $pps;
		}
		if(is_array($pps_premium) && count($pps_premium) > 0)
		{
			$data['pps_premium'] = $pps_premium;
		}
		if(is_array($pps_premium_spouse) && count($pps_premium_spouse) > 0)
		{
			$data['pps_premium_spouse'] = $pps_premium_spouse;
		}
		//Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
		if(is_array($pps_civil_penalties))
		{
			if(count($pps_civil_penalties) > 0)
			{
				$data['pps_civil_penalties'] = $pps_civil_penalties;
			}
		}
		else{
			$data['pps_civil_penalties'] = 0;
		}
		if(is_array($pps_shared_responsibility))
		{
			if(count($pps_shared_responsibility) > 0)
			{
				$data['pps_shared_responsibility'] = $pps_shared_responsibility;
			}
		}
		else{
			$data['pps_shared_responsibility'] = 0;
		}
		if(is_array($pps_premium_civil_penalties))
		{
			if(count($pps_premium_civil_penalties) > 0)
			{
				$data['pps_premium_civil_penalties'] = $pps_premium_civil_penalties;
			}
		}
		else{
			$data['pps_premium_civil_penalties'] = 0;
		}
		if(is_array($pps_premium_spouse_civil_penalties))
		{
			if(count($pps_premium_spouse_civil_penalties) > 0)
			{
				$data['pps_premium_spouse_civil_penalties'] = $pps_premium_spouse_civil_penalties;
			}
		}
		else{
			$data['pps_premium_spouse_civil_penalties'] = 0;
		}
		if(is_array($pps_premium_shared_responsibility))
		{
			if(count($pps_premium_shared_responsibility) > 0)
			{
				$data['pps_premium_shared_responsibility'] = $pps_premium_shared_responsibility;
			}
		}
		else{
			$data['pps_premium_shared_responsibility'] = 0;
		}
		if(is_array($pps_premium_spouse_shared_responsibility))
		{
			if(count($pps_premium_spouse_shared_responsibility) > 0)
			{
				$data['pps_premium_spouse_shared_responsibility'] = $pps_premium_spouse_shared_responsibility;
			}
		}
		else{
			$data['pps_premium_spouse_shared_responsibility'] = 0;
		}
		if(is_array($pps_resoultion_present))
		{
			if(count($pps_resoultion_present) > 0)
			{
				$data['pps_resoultion_present'] = $pps_resoultion_present;
			}
		}
		else{
			$data['pps_resoultion_present'] = 0;
		}
		// if(is_array($pps_resoultion_present_premium))
		if(is_array($pps_premium_resoultion_present))
		{
			if(count($pps_premium_resoultion_present) > 0)
			{
				$data['pps_resoultion_present_premium'] = $pps_premium_resoultion_present;
			}
		}
		else{
			$data['pps_resoultion_present_premium'] = 0;
		}

		if(is_array($pps_premium_spouse_resoultion_present))
		{
			if(count($pps_premium_spouse_resoultion_present) > 0)
			{
				$data['pps_resoultion_present_premium_spouse'] = $pps_premium_spouse_resoultion_present;
			}
		}
		else{
			$data['pps_resoultion_present_premium_spouse'] = 0;
		}

		file_put_contents('ppsResoPremSP.txt', 'pps_resoultion_present_premium_spouse - '.print_r($pps_premium_spouse_resoultion_present, true)."\n", FILE_APPEND);
		// end Added on 07-10-2022
		if(is_array($pps_spouse) && count($pps_spouse) > 0)
		{
			$data['pps_spouse'] = $pps_spouse;
		}
		//Added on 03-11-2022
		if(is_array($pps_sp_civil_penalties))
		{
			if(count($pps_sp_civil_penalties) > 0)
			{
				$data['pps_sp_civil_penalties'] = $pps_sp_civil_penalties;
			}
		}
		else{
			$data['pps_sp_civil_penalties'] = 0;
		}
		if(is_array($pps_sp_shared_responsibility))
		{
			if(count($pps_sp_shared_responsibility) > 0)
			{
				$data['pps_sp_shared_responsibility'] = $pps_sp_shared_responsibility;
			}
		}
		else{
			$data['pps_sp_shared_responsibility'] = 0;
		}
		if(is_array($pps_sp_resoultion_present))
		{
			if(count($pps_sp_resoultion_present) > 0)
			{
				$data['pps_sp_resoultion_present'] = $pps_sp_resoultion_present;
			}
		}
		else{
			$data['pps_sp_resoultion_present'] = 0;
		}
		if(is_array($pps_sp_income_information))
		{
			if(count($pps_sp_income_information) > 0)
			{
				$data['pps_sp_income_information'] = $pps_sp_income_information;
			}
		}
		else{
			$data['pps_sp_income_information'] = 0;
		}
		// end added on 03-11-2022
		if(is_array($pps_state_personel) && count($pps_state_personel) > 0)
		{
			$data['pps_state_personel'] = $pps_state_personel;
		}
		if(is_array($pps_users) && count($pps_users) > 0)
		{
			$data['pps_users'] = $pps_users;
		}
		if(is_array($assets) && count($assets) > 0)
		{
			$data['assets'] = $assets;
		}			 
		if(is_array($fin_overview) && count($fin_overview) > 0)
		{
			$data['fin_overview'] = $fin_overview;
		} 
		if(is_array($additional_expense) && count($additional_expense) > 0)
		{
			$data['additional_expense'] = $additional_expense;
		} 		
		if(is_array($vehicles) && count($vehicles) > 0)
		{
			$data['vehicles'] = $vehicles;
		} 
		if(is_array($real_estate) && count($real_estate) > 0)
		{
			$data['real_estate'] = $real_estate;
		}
		if(is_array($sugar_irs_standards) && count($sugar_irs_standards) > 0)
		{			
			$data['sugar_irs_standards'] = $sugar_irs_standards;
		}  
		if($reported_inc != 0 && count($reported_inc) > 0)
		{
			$data['reported_inc'] = $reported_inc;
		}
		if($tc_intake != 0 && count($tc_intake) > 0)
		{
			$data['tc_intake'] = $tc_intake;
		}
		if(is_array($finance) && count($finance) > 0){
			$data['finance'] = $finance;
		}
		/*if(count($doc_list) > 0){
			$data['doc_list'] = $doc_list;
		}*/
		$data['total_pps_balance'] = $total_pps_balance; // Added on 31-08-2023 https://trello.com/c/PodR8zl0/435-investigation-summary-letter-fixes

		$data['lead_campaign_direct_partner'] = $this->DBmodel->fetch_lead_campaign_direct_partner($case_id); //Added on 28-09-2023 https://trello.com/c/apOv1pYa/532-direct-partner-tags-throughout-lead-investigations-and-client-record
		
		if($gong_details != 0 && count($gong_details) > 0)
		{
			$data['gong_details'] = $gong_details;
		} // Added on 03-03-2025 for Gong Notes https://trello.com/c/dDJftjdb/2039-cr-portal-gong-notes-to-federal-intake


		if(isset($webhookParam) && $webhookParam == 'send')
		{
			$current_ip = $this->input->ip_address();
		
			$allow = $this->config->item('allowed_ips');   
			
			if(!in_array($current_ip, $allow)) 
			{
				echo json_encode(['success'=>'false','message'=>'IP NotIn Allowed List']); 
				exit();
			}

			$url = $this->config->item('webhook_url');//'https://hook.us1.make.com/lrcq5gqoskr1wwms4wi6m6fqtomnq7q1';//base_url('index.php/casedetail/crSummary');
			// file_put_contents("crSummary.txt", 'inside url - '.print_r($url,true)."\n",FILE_APPEND | LOCK_EX );
			$apiKey = $this->config->item('webhook_key');

			$headers = [
				'Content-Type: application/json',
				'x-make-apikey: ' . $apiKey, // Use x-make-apikey header
				// 'Authorization: Bearer ' . $apiKey,
			];

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 

			$response = curl_exec($ch);
			file_put_contents("webhook.txt", 'webhook response - '.print_r($response,true)."\n",FILE_APPEND | LOCK_EX );
			curl_close($ch);

			if($response)
			{
				echo json_encode(['success'=>'true','message'=>'Successfully sent data!!','response'=>$response]); 
			}
			else{
				echo json_encode(['success'=>'false','message'=>'Something went wrong!!']); 
			}
			return true;
		}	

		echo json_encode($data);
	}
	
	public function check_business_exist()
	{
		$case_id = $this->input->post('caseid');
		$data = $this->DBmodel->check_business($case_id);
		
		echo $data;
	}
	
	public function add_new_service()
	{
		$case_id = $this->input->post('caseid');
	
		$sale_level = $this->input->post('sale_level');
		$service_type = $this->input->post('service_type');
		$service_name = $this->input->post('service_name');
		$service_name_id = $this->input->post('service_id');
		$category_id = $this->input->post('category_id');
		$desc = $this->input->post('desc');
		$price = $this->input->post('price');
		$qty = $this->input->post('qty');
		$disc_reason = $this->input->post('disc_reason');
		$disc_amount = $this->input->post('disc_amount');
		//$approved_by = $this->input->post('approved_by');
		//$sold_by = $this->input->post('sold_by');
		$calc_amt = $this->input->post('calc_amt');
		$years_list = $this->input->post('years_list');
		$state_list = $this->input->post('state_list');
		$commission = $this->input->post('commission');
		$billed_to = $this->input->post('billed_to');
		$entity = $this->input->post('entity');
		$servicer = $this->input->post('servicer');
		$recurring_type = $this->input->post('recurring_type');
		$service_start_date = $this->input->post('service_start_date');
		$rollin = $this->input->post('rollin');
		$quarter_list = $this->input->post('quarter_list');
		$flag_list = $this->input->post('flag_list');
		$parent_id = $this->input->post('parent_id');

		$result['id'] = $this->DBmodel->create_new_service($case_id, $sale_level, $service_type, $service_name, $service_name_id, $desc, $price, $qty, $disc_reason, $disc_amount, $calc_amt, $years_list, $category_id, $commission, $billed_to, $entity, $servicer, $recurring_type, $service_start_date, $rollin, $quarter_list, $flag_list, $state_list, $parent_id);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
	public function update_service()
	{
		$rid = $this->input->post('rid');
	
		$sale_level = $this->input->post('sale_level');
		$service_type = $this->input->post('service_type');
		$service_name = $this->input->post('service_name');
		$service_name_id = $this->input->post('service_id');
		$category_id = $this->input->post('category_id');
		$desc = $this->input->post('desc');
		$price = $this->input->post('price');
		$qty = $this->input->post('qty');
		$disc_reason = $this->input->post('disc_reason');
		$disc_amount = $this->input->post('disc_amount');
		//$approved_by = $this->input->post('approved_by');
		//$sold_by = $this->input->post('sold_by');
		$calc_amt = $this->input->post('calc_amt');
		$years_list = $this->input->post('years_list');
		$commission = $this->input->post('commission');
		$billed_to = $this->input->post('billed_to');
		$entity = $this->input->post('entity');
		$servicer = $this->input->post('servicer');
		$recurring_type = $this->input->post('recurring_type');
		$service_start_date = $this->input->post('service_start_date');
		$rollin = $this->input->post('rollin');
		$quarter_list = $this->input->post('quarter_list');
		$flag_list = $this->input->post('flag_list');
		$state_list = $this->input->post('state_list');
		
		$result = $this->DBmodel->update_services($rid, $sale_level, $service_type, $service_name, $service_name_id, $desc, $price, $qty, $disc_reason, $disc_amount, $calc_amt, $years_list, $category_id, $commission, $billed_to, $entity, $servicer, $recurring_type, $service_start_date, $rollin, $quarter_list, $flag_list, $state_list);
		
		if($result)
		{
			echo $result;
		}
	}
	
	public function update_child_service()
	{
		$rid = $this->input->post('rid');
	
		$service_type = $this->input->post('service_type');
		$service_name = $this->input->post('service_name');
		$service_name_id = $this->input->post('service_id');
		$category_id = $this->input->post('category_id');
		$desc = $this->input->post('desc');
		$price = $this->input->post('price');
		$qty = $this->input->post('qty');
		$years_list = $this->input->post('years_list');
		$rollin = $this->input->post('rollin');
		$quarter_list = $this->input->post('quarter_list');
		$flag_list = $this->input->post('flag_list');
		$state_list = $this->input->post('state_list');
		$parent_id = $this->input->post('parent_id');

		$result = $this->DBmodel->updateChildServices($rid, $service_type, $service_name, $service_name_id, $desc, $price, $qty,$years_list, $category_id, $rollin, $quarter_list, $flag_list, $state_list, $parent_id);
		
		if($result)
		{
			echo $result;
		}
	}

	public function get_all_services()
	{
		$type_id = $this->input->post('type_id');
		$related_to = $this->input->post('related_to');
		$status_check = $this->input->post('status_check');
		$entity = $this->input->post('entity');
		//print 		$status_check;		
		$result = $this->DBmodel->get_service_types($type_id, $related_to, $status_check, $entity);
		
		if($result)
		{ 
			echo json_encode($result);
		}
	}
	
	public function get_service_types()
	{
		$related_to = $this->input->post('related_to');
		$status_check = $this->input->post('status_check');
		$entity = $this->input->post('entity');
						
		$result = $this->DBmodel->get_filtered_service_types($related_to, $status_check, $entity);
		
		if($result)
		{ 
			echo json_encode($result);
		}
	}
	
	public function get_all_services_without_type()
	{				
		$result = $this->DBmodel->get_service_types_without_id();
		
		if($result)
		{ 
			echo json_encode($result);
		}
	}
	
	public function get_services_list()
	{
		$case_id = $this->input->post('caseid');
						
		$result = $this->DBmodel->get_services($case_id);
		
		if($result)
		{
			echo json_encode($result);
		}
	}
	
	public function get_unsold_services_list()
	{
		$case_id = $this->input->post('caseid');
						
		$result = $this->DBmodel->get_unsold_services($case_id);
		
		if($result)
		{
			echo json_encode($result);
		}
	}
	
	public function get_revenue_details_with_id()
	{
		$rli_id = $this->input->post('rowid');
						
		$result = $this->DBmodel->get_services_with_id($rli_id);
		
		if($result)
		{
			echo json_encode($result);
		}
	}
	
	public function update_cr_values()
	{
		$case_id = $this->input->post('caseid');
	
		$services = $this->input->post('service_names');
		//$fee = $this->input->post('fee');
						
		$logData = 'CaseID - ' . $case_id . ", services - " . $services . PHP_EOL; 
		$this->createCustomLog($logData,'SummaryTab','update_cr_values');

		$result = $this->DBmodel->save_cr_aggregate_values($case_id, $services); //, $fee
		
		if($result)
		{
			echo json_encode($result);
		}
	}
	
	public function add_expense_details()
	{
		$case_id = $this->input->post('caseid');
	
		$under65 = $this->input->post('under65');
		$over65 = $this->input->post('over65');
		$fcm = $this->input->post('fcm');
		$mortgage_pay = $this->input->post('mortgage_pay');
		$rent_pay = $this->input->post('rent_pay');
		$stated_housing = $this->input->post('stated_housing');
		$irs_stand = $this->input->post('irs_stand');
		$housing_exp = $this->input->post('housing_exp');
		$public_trans = $this->input->post('public_trans');
		$num_vehicle = $this->input->post('num_vehicle');
		$tot_auto_exp = $this->input->post('tot_auto_exp');
		$prescp_copay = $this->input->post('prescp_copay');
		$health_ins = $this->input->post('health_ins');
		$total_health = $this->input->post('total_health');
		$taxes = $this->input->post('taxes');
		$court_ordered = $this->input->post('court_ordered');
		$child_care = $this->input->post('child_care');
		$whole_life_ins = $this->input->post('whole_life_ins');	
		
		$public_tc = $this->input->post('public_trans_client');
		$public_ts = $this->input->post('public_trans_spouse');	
		
		$auto_ownership1 = $this->input->post('other_vehicle_exp1');
		$auto_ownership2 = $this->input->post('other_vehicle_exp2');
		$auto_ownership1_id = $this->input->post('exp_id_1');
		$auto_ownership2_id = $this->input->post('exp_id_2');		
		
		$exp_val1 = $this->input->post('expense1');
		$exp_val2 = $this->input->post('expense2');
		$exp_val3 = $this->input->post('expense3');
		$exp_val4 = $this->input->post('expense4');
		
		$tot_expense = $this->input->post('total_expense');
		$other_exp_json = $this->input->post('other_exp_json');
		
		$utilities = $this->input->post('utilities');
		$pic = $this->input->post('pic'); 
		$extra_exp_json = $this->input->post('extra_exp_json'); 
		
			//print 	$other_exp_json;
		$result = $this->DBmodel->save_expense_details($case_id, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id, $extra_exp_json);	
		
		//$this->DBmodel->save_expense_details($case_id, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id);		
		
		//$fcm, $mortgage_pay, $stated_housing, $irs_stand, $housing_exp, $public_trans, $num_vehicle, $tot_auto_exp,  $taxes, $court_ordered, $child_care, $whole_life_ins, $exp_val1, $exp_val2, $exp_val3, $exp_val4,$tot_expense,
		
		if($result)
		{
			echo $result;
		}
	}
	
	public function add_expense_details_acc()
	{
		$case_id = $this->input->post('caseid');
	
		$under65 = $this->input->post('under65');
		$over65 = $this->input->post('over65');
		$fcm = $this->input->post('fcm');
		$mortgage_pay = $this->input->post('mortgage_pay');
		$rent_pay = $this->input->post('rent_pay');
		$stated_housing = $this->input->post('stated_housing');
		$irs_stand = $this->input->post('irs_stand');
		$housing_exp = $this->input->post('housing_exp');
		$public_trans = $this->input->post('public_trans');
		$num_vehicle = $this->input->post('num_vehicle');
		$tot_auto_exp = $this->input->post('tot_auto_exp');
		$prescp_copay = $this->input->post('prescp_copay');
		$health_ins = $this->input->post('health_ins');
		$total_health = $this->input->post('total_health');
		$taxes = $this->input->post('taxes');
		$court_ordered = $this->input->post('court_ordered');
		$child_care = $this->input->post('child_care');
		$whole_life_ins = $this->input->post('whole_life_ins');	
		
		$public_tc = $this->input->post('public_trans_client');
		$public_ts = $this->input->post('public_trans_spouse');	
		
		$auto_ownership1 = $this->input->post('other_vehicle_exp1');
		$auto_ownership2 = $this->input->post('other_vehicle_exp2');
		$auto_ownership1_id = $this->input->post('exp_id_1');
		$auto_ownership2_id = $this->input->post('exp_id_2');		
		
		$exp_val1 = $this->input->post('expense1');
		$exp_val2 = $this->input->post('expense2');
		$exp_val3 = $this->input->post('expense3');
		$exp_val4 = $this->input->post('expense4');
		
		$tot_expense = $this->input->post('total_expense');
		$other_exp_json = $this->input->post('other_exp_json');
		
		$utilities = $this->input->post('utilities');
		$pic = $this->input->post('pic'); 
		//$extra_exp_json = $this->input->post('extra_exp_json'); 
		
			//print 	'save_expense_details_acc - '.$other_exp_json;
		$result = $this->DBmodel->save_expense_details_acc($case_id, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id);	
		
		//$this->DBmodel->save_expense_details($case_id, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id);		
		
		//$fcm, $mortgage_pay, $stated_housing, $irs_stand, $housing_exp, $public_trans, $num_vehicle, $tot_auto_exp,  $taxes, $court_ordered, $child_care, $whole_life_ins, $exp_val1, $exp_val2, $exp_val3, $exp_val4,$tot_expense,
		
		if($result)
		{
			echo $result;
		}
	}
	
	public function add_summary_details()
	{
		$case_id = $this->input->post('caseid');
		$pps_id = $this->input->post('inves_pps_id');
		$acc_status = $this->input->post('acc_stat');
		$compliance_val = $this->input->post('compliance');
		$analysis1 = $this->input->post('analysis1');
		$analysis2 = $this->input->post('analysis2');
		$analysis3 = $this->input->post('analysis3');
		$di_value = $this->input->post('di');	
		$cr_fee_value = $this->input->post('cr_fee_val');		
		$add_service_fee_val = $this->input->post('add_service_fee_val');		
		//$cr_rec_value = $this->input->post('cr_rec_val');		
		$spec_service = $this->input->post('spec_service');		
		$prelim_rec_type = $this->input->post('prelim_rec_type');		
		$final_rec_type = $this->input->post('final_rec_type');		
		$prelim_fee = $this->input->post('prelim_fee');	
		$aggregate_id = $this->input->post('aggregate_id');	
		$cr_template = $this->input->post('cr_template');	//Added on 26-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field
		$add_pps_info = $this->input->post('add_pps_info'); // Added on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added
		$service_source_val = $this->input->post('service_source_val'); // Added on 23-09-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record
		$reported_income = $this->input->post('reported_income'); // Added on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
		$reported_assets = $this->input->post('reported_assets'); // Added on 26-03-2024  https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp

		//Added on 09-11-2023 https://trello.com/c/OAHBnqTD/662-investigation-summary-letter-improvements
		if($final_rec_type == 'TP Only'){
			$acc_status = 'Low Risk';
		}
		//end on 09-11-2023
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL; //'CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','add_summary_details');

		$result = $this->DBmodel->save_summary_details($case_id, $pps_id, $acc_status, $compliance_val, $analysis1, $analysis2, $analysis3, $di_value, $cr_fee_value, $spec_service, $prelim_rec_type, $final_rec_type, $prelim_fee, $aggregate_id, $add_service_fee_val, $cr_template, $add_pps_info, $service_source_val,$reported_income,$reported_assets); //Added on 26-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field // Updated on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added // , $service_source_val Added on 23-09-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record // Updated on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
		
		if($result)
		{
			echo $result;
		}
	}	
	
	public function update_case_status()
	{
		$case_id = $this->input->post('caseid');
		$status_val = $this->input->post('status');
		$reason_val = $_POST['rejection_reason'] ;
		$recmd_type = $_POST['recmd_type'] ;
			
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL; 
		$this->createCustomLog($logData,'SummaryTab','update_case_status');

		$result = $this->DBmodel->save_case_status($case_id, $status_val, $reason_val, $recmd_type);
		if($result)
		{
			echo $result;
		}
	}		
	
	public function add_pps_details()
    {   
        //$pps_arr = array();
        $case_id= $this->input->post('caseid');
       // $input_data= $this->input->post('pps_data_input');  
        //$input_data_new= $this->input->post('pps_data_input_new');  
		//$id_data= $this->input->post('year_id_json');  
		$pps_id= $this->input->post('pps_id');  
		//$tab= $this->input->post('tab');  
		$pps_new_data= $this->input->post('pps_new_data');  
		$pps_existing_data= $this->input->post('pps_existing_data');  
        
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_details');

		$result = $this->DBmodel->save_pps_sugar_details($case_id, $pps_id, $pps_new_data, $pps_existing_data);
		
        if($result)
        {
            echo $result;
        }
    }
	
	public function add_pps_details_premium()
    {   
        //$pps_arr = array();
        $case_id= $this->input->post('caseid');
       // $input_data= $this->input->post('pps_data_input');  
        //$input_data_new= $this->input->post('pps_data_input_new');  
		//$id_data= $this->input->post('year_id_json');  
		$pps_id= $this->input->post('pps_id');  
		//$tab= $this->input->post('tab');  
		$pps_new_data= $this->input->post('pps_new_data');  
		$pps_existing_data= $this->input->post('pps_existing_data');  
        
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_details_premium');

		$result = $this->DBmodel->save_pps_sugar_details_premium($case_id, $pps_id, $pps_new_data, $pps_existing_data);
		
        if($result)
        {
            echo $result;
        }
    }
	public function add_pps_details_premium_spouse()
    {   
        //$pps_arr = array();
        $case_id= $this->input->post('caseid');
       // $input_data= $this->input->post('pps_data_input');  
        //$input_data_new= $this->input->post('pps_data_input_new');  
		//$id_data= $this->input->post('year_id_json');  
		$pps_id= $this->input->post('pps_id');  
		//$tab= $this->input->post('tab');  
		$pps_new_data= $this->input->post('pps_new_data');  
		$pps_existing_data= $this->input->post('pps_existing_data');  
        
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_details_premium_spouse');

		$result = $this->DBmodel->save_pps_sugar_details_premium_spouse($case_id, $pps_id, $pps_new_data, $pps_existing_data);
		
        if($result)
        {
            echo $result;
        }
    }
	
	public function add_pps_details_state()
    {   
        $pps_arr = array();
        $case_id= $this->input->post('caseid');
        //$input_data= $this->input->post('pps_data_input');  
        //$input_data_new= $this->input->post('pps_data_input_new');  
		//$id_data= $this->input->post('year_id_json');  
		$pps_id= $this->input->post('pps_id');  
		//$tab= $this->input->post('tab');  
		$pps_new_data= $this->input->post('pps_new_data');  
		$pps_existing_data= $this->input->post('pps_existing_data'); 
		
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_details_state');

		$result = $this->DBmodel->save_pps_state_sugar_details($case_id, $pps_id, $pps_new_data, $pps_existing_data);
		
        if($result)
        {
            echo $result;
        }
    }
	
	public function add_pps_spouse_details()
    {   
        $pps_arr = array();
        $case_id= $this->input->post('caseid');
		$pps_id= $this->input->post('pps_id');
        //$input_data= $this->input->post('pps_data_input');  
        //$input_data_new= $this->input->post('pps_data_input_new');  
       // $year_id_json= $this->input->post('year_id_json');
		//$tab = $this->input->post('tab');
        $pps_new_data= $this->input->post('pps_new_data');  
		$pps_existing_data= $this->input->post('pps_existing_data');
		
		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_spouse_details');

        $result = $this->DBmodel->save_pps_spouse_details($case_id, $pps_id, $pps_new_data, $pps_existing_data);
        if($result)
        {
            echo $result;
        }
    }
	
	public function remove_pps_row()
	{
		$case_id= $this->input->post('caseid');
        $year= $this->input->post('rowid'); 
		$id= $this->input->post('id');  
       
        $result = $this->DBmodel->delete_pps_rows($case_id, $year, $id);
        if($result)
        {
            echo $result;
        }		
	}
	
	public function remove_pps_row_state_personal()
	{
		$case_id= $this->input->post('caseid');
        $year= $this->input->post('rowid'); 
		$id= $this->input->post('id');  
        
        $result = $this->DBmodel->delete_pps_row_state_personal($case_id, $year, $id);
        if($result)
        {
            echo $result;
        }		
	}
	
	public function add_newnote()
	{	
		$this->load->library('uuid');
		$uuid = $this->uuid->v4();
		$note_txt= $this->input->post('note_txt');
		$note_type = $this->input->post('note_type');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','add_newnote');
		$result = $this->DBmodel->add_note($case_id, $note_txt, $note_type, $uuid);
		if($result)
		{
			echo $result;			
		}
			
	}

	// Added on 15-07-2025 https://trello.com/c/QES9Q6km/1053-investigations-cr-portal-notes-update
	public function publish_note()
	{
		$this->load->library('uuid');
		$uuid = $this->uuid->v4();
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');

		$logData = 'Line 925 CaseID - ' . $case_id . ", Note ID - " . $note_id . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','add_newnote');

		$result = $this->DBmodel->publish_note($case_id, $note_id, $uuid);
		$logData = 'Line 925 result - ' . $result . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','add_newnote');
		if($result)
		{
			echo $result;
		}
	}
	
	public function add_newDraftNote()
	{	
		$allow = $this->config->item('allowed_ips');   
		$current_ip = $this->input->ip_address();
		if(!in_array($current_ip, $allow)) 
		{
			echo json_encode(['success'=>'false','message'=>'IP Not In Allowed List']); 
			exit();
		}
		$allHeaders = $this->input->request_headers();
		//$webhookParam = $this->input->post(); //?$this->input->post('webhook_param'):'';	
		// if(isset($webhookParam))
		// {
		// 	$url = $this->config->item('webhook_url');//'https://hook.us1.make.com/lrcq5gqoskr1wwms4wi6m6fqtomnq7q1';//base_url('index.php/casedetail/crSummary');
		// 	// file_put_contents("crSummary.txt", 'inside url - '.print_r($url,true)."\n",FILE_APPEND | LOCK_EX );
		// 	$apiKey = $this->config->item('webhook_key');
			file_put_contents('webhookh.txt', print_r($allHeaders,true));
		// }

		if(is_array($allHeaders) && array_key_exists('Authorization',$allHeaders))
		{
			$apikey = $allHeaders['Authorization'];
			if($apikey != $this->config->item('webhook_key'))
			{
				echo json_encode(['success'=>'false','message'=>'Wrong Auth Key']); 
				exit();
			}
			$note_type = "AI Generated Note";
		}
		else
		{
			$note_type = $this->input->post('note_type');
		}		
		
		$this->load->library('uuid');
		$uuid = $this->uuid->v4();
		$note_txt= $this->input->post('note_txt');
		//$note_type = $this->input->post('note_type');
		$case_id= $this->input->post('case_id');
		
		$logData = 'Line 925 CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','add_newnote');
		$result = $this->DBmodel->add_draftNote($case_id, $note_txt, $note_type, $uuid);
		if($result)
		{
			echo $result;			
		}
		
	}
	// end Added on 15-07-2025
	
	public function add_pps_note() 
	{	
		$note_txt= $this->input->post('note_txt');
		$case_id= $this->input->post('case_id');
		$ppsid = $this->input->post('pps_id');
		//$uuid = $this->uuid->v4();
		
		$logData = 'CaseId - ' . $case_id . ', PPSID - ' . $ppsid . ", note_txt - " . $note_txt . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_note');

		$result = $this->DBmodel->add_note_pps($case_id, $note_txt, $ppsid);
		if($result)
		{
			echo $result;
			return $result;
		}
	}
	
	public function add_pps_note_spouse()
	{
		$note_txt= $this->input->post('note_txt');
		$case_id= $this->input->post('case_id');
		$ppsid = $this->input->post('pps_id');
		
		$logData = 'CaseId - ' . $case_id . ', PPSID - ' . $ppsid . ", note_txt - " . $note_txt . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_pps_note_spouse');

		$result = $this->DBmodel->save_pps_note_spouse($case_id, $note_txt, $ppsid);
		if($result)
		{
			echo $result;
		}
	}
	
	public function get_notes_list()
	{	
		$notes_all=array();
		$case_id= $this->input->post('case_id');
		$notes_all = $this->DBmodel->get_notes_all($case_id); 
		
		foreach($notes_all as $value)
		{						
			print "<p>".$value->note_text."<br>". "<span class='notes_time'>". $value->note_datecreated. "</span>". "</p>";
		}	
	}
	
	public function update_summary_note()
	{
		$note_txt= $this->input->post('note_text');
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Id - " . $note_id . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','update_summary_note');

		$result = $this->DBmodel->save_summary_note($case_id, $note_id, $note_txt);
		if($result)
		{
			echo $note_txt;
		}
	}
	
	public function delete_summary_notes()
	{
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseID - ' . $case_id . ", Note Id - " . $note_id . PHP_EOL;
		$this->createCustomLog($logData,'SummaryNotes','delete_summary_notes');

		$result = $this->DBmodel->remove_summary_note($case_id, $note_id);
		if($result)
		{
			echo $result;
		}		
	}
	
	public function update_pps_note()
	{
		$note_txt= $this->input->post('note_text');
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseId - ' . $case_id . ', NoteId - ' . $note_id . ", Note Txt - " . $note_txt . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_note');

		$result = $this->DBmodel->save_pps_note($case_id, $note_id, $note_txt);
		if($result)
		{
			echo $note_txt;
		}
	}
	
	public function delete_pps_notes()
	{
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseId - ' . $case_id . ', NoteId - ' . $note_id . PHP_EOL;
		$this->createCustomLog($logData,'PPS','delete_pps_notes');

		$result = $this->DBmodel->remove_pps_note($case_id, $note_id);
		if($result)
		{
			echo $result;
		}		
	}
	
	public function update_pps_spouse_note()
	{
		$note_txt= $this->input->post('note_text');
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseId - ' . $case_id . ', NoteId - ' . $note_id . ", Note Txt - " . $note_txt . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_spouse_note');

		$result = $this->DBmodel->save_pps_spouse_note($case_id, $note_id, $note_txt);
		if($result)
		{
			echo $note_txt;
		}
	}
	
	public function delete_pps_spouse_notes()
	{
		$note_id = $this->input->post('noteid');
		$case_id= $this->input->post('case_id');
		
		$logData = 'CaseId - ' . $case_id . ', NoteId - ' . $note_id . PHP_EOL;
		$this->createCustomLog($logData,'PPS','delete_pps_spouse_notes');

		$result = $this->DBmodel->remove_pps_spouse_note($case_id, $note_id);
		if($result)
		{
			echo $result;
		}		
	}
	
	public function delete_revenue_line_item()
	{
		$rid = $this->input->post('revenue_id');
		$child = $this->input->post('child');
		$parent_id = $this->input->post('parent_id');

		$result = $this->DBmodel->delete_revenue_line_items($rid, $child, $parent_id);  
		if($result)
		{
			echo $result;
		}
	}
	
	public function getParentServicePrice()
	{
		$parent_id = $this->input->post('parent_id');

		$result = $this->DBmodel->totalParentServicePrice($parent_id);  
		if($result)
		{
			echo json_encode($result);
		}
	}

	public function create_pps_detail_record_pers()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->create_pps_detail_record_fpers($case_id, $pps_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function create_pps_detail_record_premium()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->create_pps_detail_premium_record($case_id, $pps_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function create_pps_detail_record_premium_spouse()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->create_pps_detail_premium_spouse_record($case_id, $pps_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function create_pps_detail_record_sp()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->create_pps_detail_record_spouse($case_id, $pps_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function create_pps_detail_record_state()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->create_pps_detail_record_state_pers($case_id, $pps_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function update_pps_status()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        
		$logData = 'CaseId - ' . $case_id . ', PPSId - ' . $pps_id . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_status');

        $result = $this->DBmodel->update_main_pps_status($pps_id,$case_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function update_poa_issue()
    {
        $poa_issue = $this->input->post('poa_issue');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->update_main_poa_issue($pps_id, $poa_issue); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function update_biz_poa_issue()
    {
        $poa_issue = $this->input->post('biz_poa_issue');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->update_main_biz_poa_issue($pps_id, $poa_issue); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function update_pps_assigned_user()
    {
        $user_id = $this->input->post('user_id');
        $pps_id = $this->input->post('pps_id');
        
        $result = $this->DBmodel->update_pps_assigned_user($pps_id, $user_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function getCRServicesDD()
	{
        $result = $this->DBmodel->getInitialServiceDD(); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
		
	}
	
	public function getDropdowns()
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
		CURLOPT_POSTFIELDS => "{\r\n \"dropdowns\":{\"name\": \"prelim_recommendation_type_list\"}\r\n}",
		CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		echo "cURL Error #:" . $err;
		} else {
		echo $response;
		}
	}

	public function getStateList()
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
		CURLOPT_POSTFIELDS => "{\r\n \"dropdowns\":{\"name\": \"state_dom\"}\r\n}",
		CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		echo "cURL Error #:" . $err;
		} else {
		echo $response;
		}
	}

	public function update_pps_values()
    {
        $case_id = $this->input->post('case_id');
        $pps_id = $this->input->post('pps_id');
        $poa_issue = $this->input->post('poa_issue');
        $biz_poa_issue = $this->input->post('biz_poa_issue');
        $assigned_to = $this->input->post('assigned_to');
		$verbal_poa = $this->input->post('verbal_poa');
		$state = $this->input->post('state');
		$instruction = $this->input->post('instruction');

		$logData = print_r($this->input->post(),true) . PHP_EOL . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_values');

        $result = $this->DBmodel->update_main_pps_values($case_id, $pps_id, $poa_issue, $biz_poa_issue, $assigned_to, $verbal_poa, $state, $instruction); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_case_fax_list()
    {
        $case_id = $this->input->post('case_id');
        
        $result = $this->DBmodel->fetch_case_fax_list($case_id); 
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function delete_extra_expense()
    {
        $id = $this->input->post('id');
        
        $result = $this->DBmodel->delete_extra_expenses($id);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_detail_record()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->fetch_pps_personel_sugar($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_spouse_detail_record()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->fetch_pps_spouse_sugar($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_state_detail_record()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->fetch_pps_state_personel_sugar($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_notes()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->get_pps_notes_sugar($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_spouse_notes()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->get_pps_notes_spouse_sugar($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_pps_state_notes()
    {
        $ppsid = $this->input->post('pps_id');
        
        $result = $this->DBmodel->get_pps_notes_state_personal($ppsid);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function save_pps_instruction()
    {
        $ppsid = $this->input->post('pps_id');
        $instruction = $this->input->post('instruction');
        
		$logData = 'PPSID - ' . $ppsid . ", Instruction - " . $instruction . PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_instruction');
		
        $result = $this->DBmodel->save_pps_instructions($ppsid, $instruction);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function save_case_intake_federal()
    {
        $caseid = $this->input->post('caseid');
        $deadline_reason = $this->input->post('deadline_reason');
        $deadline_date = $this->input->post('deadline_date');
       // $ro = $this->input->post('ro');
        $ro_name = $this->input->post('ro_name');
        $ro_phone = $this->input->post('ro_phone');
        $ro_fax = $this->input->post('ro_fax');
        
        $result = $this->DBmodel->save_case_intake_detail_federal($caseid, $deadline_reason, $deadline_date, $ro_name, $ro_phone, $ro_fax);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function save_case_intake_state()
    {
        $caseid = $this->input->post('caseid');
        $deadline_reason = $this->input->post('deadline_reason');
        $deadline_date = $this->input->post('deadline_date');
        //$ro = $this->input->post('ro');
        $ro_name = $this->input->post('ro_name');
        $ro_phone = $this->input->post('ro_phone');
        $ro_fax = $this->input->post('ro_fax');
        
        $result = $this->DBmodel->save_case_intake_detail_state($caseid, $deadline_reason, $deadline_date, $ro_name, $ro_phone, $ro_fax);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function checkRecurringType()
    {
        $serviceType = $this->input->post('service_ype');
        
        $result = $this->DBmodel->productTemplateGet($serviceType);  
		echo $result;
    }
	
	public function checkServiceNameField()
    {
        $serviceType = $this->input->post('service_ype');
        
        $result = $this->DBmodel->productTemplateFieldCheck($serviceType);  
		echo json_encode($result);
    }
	
	public function checkPackageOption()
    {
        $serviceType = $this->input->post('service_type');
        
        $result = $this->DBmodel->PackageOptionCheck($serviceType);  
		echo json_encode($result);
    } 
	
	public function getChildServiceTypes()
    {
		$related = $this->input->post('related');
		$entity = $this->input->post('entity');
        $result = $this->DBmodel->getServiceTypesWithoutPackage($related, $entity);  
		echo json_encode($result);
	}

	public function checkChildRLI()
	{
		$parent_id = $this->input->post('parent_id');

		$result = $this->DBmodel->getChildServices($parent_id);  
		echo json_encode($result);
	}
	
	public function add_new_business()
    {
        $caseid = $this->input->post('caseid');
        
        $result = $this->DBmodel->addNewBusiness($caseid);  
		echo json_encode($result);
	}
	
	public function check_discount()
    {
        $discount_reason = $this->input->post('discount_reason');
        
        $result = $this->DBmodel->checkDiscount($discount_reason);  
		echo json_encode($result);
	}
	
	public function updateDocument()
	{
        $username='nablasol';
        $password='75NA1BL30566A5SOL5';
        $apiKey = '3F74D88B-9F35-11EA-BBDF-521B35C9A2C2';

        $doc_id = $this->input->post('document_id');
        $document_title = $this->input->post('document_title');
        $category = $this->input->post('category');
        $category = $this->input->post('category');
        $comment = $this->input->post('comment');
        $user_id = '1';

        $url='https://dms.nablasol.net/api/update_documents';
        $postfields = array(
	        'document_id'=> $doc_id,
	        'user_id'=> $user_id,
	        'document_title'=> $document_title,
	        'category'=> $category,
	        'comment'=> $comment,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY: '.$apiKey, 'Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec ($ch);
        curl_close ($ch);

        echo $result;
	}
	
	function fileUploadDMS()
	{
		$count = count($_FILES['file']['name']);
		$ret = is_array($_FILES['file']['name']);
		if(is_array($_FILES['file']['name']) && $count > 1)
		{
			$uploadSuccess = 0;
			$uploadError = 0;
			$uploadedStatus = [];
			$errorResponse = [];
			for ($i = 0; $i < $count; $i++) 
			{
				// echo 'Name: '.$_FILES['file']['name'][$i].'<br/>';
				$tmp_name = $_FILES["file"]["tmp_name"][$i];
				$ext = explode(".", $_FILES['file']['name'][$i]);						
				$title = $_FILES["file"]["name"][$i];
				$fullName = $this->input->post('fullName');
				$user_id = $this->input->post('user_id');
				$_CaseNo = $this->input->post('CaseNo');
				$_Category = $this->input->post('documentCategory');


				if(!empty($this->input->post('customName'))){
					$fileCustomName = $this->input->post('customName')." ".$i;
				}else{
					$fileCustomName = $title;
				}
				if(!empty($this->input->post('customComment'))){
					$comment = $this->input->post('customComment');
				}else{
					$comment = "";
				}
				
				
				if ($_FILES["file"]["error"][$i] > 0)
					echo "Return Code: " . $_FILES["file"][$i]["error"] . "<br>";
				else
				{	
					$cfile = curl_file_create($_FILES['file']['tmp_name'][$i], $_FILES['file']['type'][$i], $_FILES['file']['name'][$i]);
					$tax_year = date('Y');
					$postfields = array(
						'document_title'=> $fileCustomName,
						'case_number' => $_CaseNo,
						'user_id' => $user_id,
						'user_name' => $fullName,
						'tax_year' => $tax_year,
						'category' => $_Category,
						'document_name' => $cfile,
						'comment' => $comment
					);
					$fileUploaded = $this->fileUploadCurl($postfields);
					$decodedJson = json_decode($fileUploaded,true);
					//print_r($decodedJson);					
					if ($decodedJson['result']=='Success') {
						$uploadSuccess += 1;						
					}else{
						$uploadError += 1;
						
						$uploadedStatus['result'] = $decodedJson['result'];
						$uploadedStatus['file_type'] = $decodedJson['file_type'];
						$uploadedStatus['message'] = $decodedJson['message'];
						$uploadedStatus['fileName'] = $_FILES['file']['name'][$i];

						$errorResponse['message'][$i] = $uploadedStatus;
					}
				}
			}
			if ($uploadError > 0) {
				$errorResponse['result'] = 'Error';
				echo json_encode($errorResponse);
			}else{
				$response = '{"message":"Uploaded Successfully.","result":"Success"}';
				echo $response;
			}
		}
		else
		{
			// echo 'Name: '.$_FILES['file']['name'][0].'<br/>';die();			
			$tmp_name = $_FILES["file"]["tmp_name"][0];
			$ext = explode(".", $_FILES['file']['name'][0]);						
			$title = $_FILES["file"]["name"][0];
			$fullName = $this->input->post('fullName');
			$user_id = $this->input->post('user_id');
			$_CaseNo = $this->input->post('CaseNo');
			$_Category = $this->input->post('documentCategory');


			if(!empty($this->input->post('customName'))){
				$fileCustomName = $this->input->post('customName');
			}else{
				$fileCustomName = $title;
			}
			if(!empty($this->input->post('customComment'))){
				$comment = $this->input->post('customComment');
			}else{
				$comment = "";
			}
			
			
			if ($_FILES["file"]["error"][0] > 0)
				echo "Return Code: " . $_FILES["file"][0]["error"] . "<br>";
			else
			{	
				$cfile = curl_file_create($_FILES['file']['tmp_name'][0], $_FILES['file']['type'][0], $_FILES['file']['name'][0]);				
				$tax_year = date('Y');
				$postfields = array(
					'document_title'=> $fileCustomName,
					'case_number' => $_CaseNo,
					'user_id' => $user_id,
					'user_name' => $fullName,
					'tax_year' => $tax_year,
					'category' => $_Category,
					'document_name' => $cfile,
					'comment' => $comment
				);
				$fileUploaded = $this->fileUploadCurl($postfields);

				$decodedJson = json_decode($fileUploaded,true);					
				if ($decodedJson['result'] == 'Success') {					
					echo $fileUploaded;
				}else{
					$uploadedStatus = [];
					$errorResponse = [];
					$uploadedStatus['file_type'] = $decodedJson['file_type'];
					$uploadedStatus['message'] = $decodedJson['message'];
					$uploadedStatus['fileName'] = $_FILES['file']['name'][0];

					$errorResponse['result'] = $decodedJson['result'];
					$errorResponse['message'] = [$uploadedStatus];
					echo json_encode($errorResponse);
				}				
			}
		}		
	}

	function fileUploadCurl($postfields){
		$username='nablasol';
		$password='75NA1BL30566A5SOL5';
		$apiKey = '3F74D88B-9F35-11EA-BBDF-521B35C9A2C2';

		$url='https://dms.nablasol.net/api/upload_documents';

		//print_r($postfields); exit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY: '.$apiKey, 'Content-Type: multipart/form-data'));
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec ($ch);	
		//print $result;
		curl_close ($ch);
		return $result;
	}

	public function timezoneCheckRecord()
	{
		$timeZone = $this->input->get('timezone');
		$dateTimeOrg = $this->input->get('oldStartDate');
		$instance_url = $this->sugar_url . "rest/v11/UpdateMeetingTime";  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $instance_url);
        $requestData = json_encode(array("timezone"=> $timeZone ));
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestData );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);
		
        curl_close($ch);
		$result = json_decode($response);
		
		if(!empty($result->offset))
		{
			$explode = explode(':',$result->offset); 
		}
		else{
			$explode = array('0','0');
		}
		//echo $explode; die;
		if($explode[0]>=0)
		{
			$hours_min = $explode[0].' hours '.$explode[1].' minutes';
			$dataSend['hours_min'] = $explode[0].' hours '.$explode[1].' minutes';
		}
		else{
			$hours_min = $explode[0].' hours -'.$explode[1].' minutes';
			$dataSend['hours_min'] = '-'.$explode[0].' hours -'.$explode[1].' minutes';
		}
		
		$dataSend = date('Y-m-d h:i a',strtotime($hours_min,strtotime($dateTimeOrg)));
		print_r($dataSend); 
		
	}
	
	public function appointmentList()
	{
		$caseId = $this->input->post('caseId'); 
		
		if(!empty($caseId))
		{
			$resultobj = $this->DBmodel->allAppointmentList($caseId);
			print_r(json_encode($resultobj));
		}
		else{
			echo false;
		}
	}

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

	public function createAppointment()
	{
		$caseId = $this->input->post('caseId');
		$userId = $this->session->userdata('User_id');
		session_write_close();

		if($caseId != '')
		{
			$this->db_sugar->select("cases.id,cases_cstm.case_id_c,cases.name,contacts.phone_mobile"); 
			$this->db_sugar->from('cases');
			$this->db_sugar->join('cases_cstm','cases.id=cases_cstm.id_c');
			$this->db_sugar->join('contacts_cstm','contacts_cstm.case_id_c=cases_cstm.case_id_c','LEFT');	
			$this->db_sugar->join('contacts','contacts_cstm.id_c=contacts.id','LEFT');				
			$this->db_sugar->where('cases_cstm.case_id_c', $caseId);	
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			$data = $this->db_sugar->get(); 
			$record = $data->row();
			// print_r($record ); die;
			$name = 
			$dataSend = [];

			$nameSend = $record->case_id_c.'-'.$record->name.'-'.$record->phone_mobile;

			// $d1 = date('Y-m-d',strtotime($this->input->post('startDate')));
			$d1 = DateTime::createFromFormat("m-d-Y" , $this->input->post('startDate'))->format('Y-m-d');
			$t1 = date('H:i:s',strtotime($this->input->post('startTime')));
			$fdt1 = $d1.' '.$t1 ;
		
			// $d2 = date('Y-m-d',strtotime($this->input->post('endDate')));
			$d2 = DateTime::createFromFormat("m-d-Y" , $this->input->post('endDate'))->format('Y-m-d');
			$t2 = date('H:i:s',strtotime($this->input->post('endTime')));
			$fdt2 = $d2.' '.$t2 ;
				
			$timestamp1 = new DateTime($fdt1);
			$timestamp2 = new DateTime($fdt2);
			$interval = $timestamp2->diff($timestamp1);
		
			$duration_hours = ($interval->format("%a") * 24) + $interval->format("%h") ;
			$duration_minutes =  $interval->format("%i");

			// $dataSend['result_timezone'] = $this->timezoneCheck($this->input->post('timezone'));
			$result_timezone = $this->timezoneCheck($this->input->post('timezone'));

			$startDate = DateTime::createFromFormat("m-d-Y" , $this->input->post('startDate'))->format('Y-m-d');
			$endDate = DateTime::createFromFormat("m-d-Y" , $this->input->post('endDate'))->format('Y-m-d');

		
			$makeStartDate = $startDate.' '.$t1;
			$makeEndDate = $endDate.' '.$t2;

			$dataSend['modified_user_id'] = $userId;
			$dataSend['created_by'] = $userId;
			
			$oldStartDate = date('c',strtotime($makeStartDate));
			// $dataSend['oldStartDate'] = date('c',strtotime($makeStartDate));

			$oldEndDate = date('c',strtotime($makeEndDate));
			// $dataSend['oldEndDate'] = date('c',strtotime($makeEndDate));

			$explodeStartDate = explode('+',$oldStartDate);
			$explodeEndDate = explode('+',$oldEndDate);

			$finalStartDate = $explodeStartDate[0].$result_timezone->offset;
			$finalEndDate = $explodeEndDate[0].$result_timezone->offset;

			$dataSend['date_start'] = $finalStartDate;
			// $dataSend['date_start'] = date('c',strtotime($hours_min,strtotime($finalStartDate)));
			// $dataSend['date_start'] = date('c',strtotime($finalStartDate));

			$dataSend['duration_hours'] = $duration_hours;
			$dataSend['duration_minutes'] = $duration_minutes;
		
			$dataSend['repeat_interval'] = $this->input->post('repeatInterval');
			$repeat_dow = $this->input->post('repeatDOW');
			$weekTot = '';

		
			$dataSend['name'] = $nameSend;
			$dataSend['repeat_dow'] = $repeat_dow;
			$dataSend['repeat_count'] = $this->input->post('repeatOccurance');
			// $dataSend['recurrence_id'] = date('c',strtotime($hours_min,strtotime($finalStartDate)));
			$dataSend['recurrence_id'] = $finalStartDate;

			// $dataSend['date_end'] = date('c',strtotime($hours_min,strtotime($finalEndDate)));
			$dataSend['date_end'] = $finalEndDate;
			
			$dataSend['repeat_type'] = $this->input->post('sel_repeat_type');
			$dataSend['repeatUntil'] = $this->input->post('repeatUntil');
			$dataSend['reminder_time'] = $this->input->post('popup_repeat_type');
			$dataSend['email_reminder_time'] = $this->input->post('email_reminder_time');
			$dataSend['description'] = $this->input->post('description');
			$dataSend['parent_type'] = 'Cases';
		
		
			$dataSend['assigned_user_id'] = $this->input->post('assign_to');
			$dataSend['timezone_c'] = $this->input->post('timezone');
			$dataSend['parent_id'] = $record->id;

			//updated on 07-07-2022 for editing appointments https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
			$dataSend['id'] = $this->input->post('editId');
		
			// print_r($dataSend) ; 
			if(!empty($dataSend['id']))
			{
				$dataSend['status'] = $this->input->post('statusSchedule'); // added on 21-07-2022
				echo $result = $this->DBmodel->editAppointment($dataSend);  
			}
			else
			{			
				echo $result = $this->DBmodel->saveAppointment($dataSend);  
			}
			//end updated on 07-07-2022
		}		
	}
	
	public function timezoneCheck($timeZone)
	{
		$instance_url = $this->sugar_url . "rest/v11/UpdateMeetingTime";  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $instance_url);
        $requestData = json_encode(array("timezone"=> $timeZone ));
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestData );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);
        // print_r($response); die;
        curl_close($ch);

        $result = json_decode($response);
                        
        return $result;
	}

	//added on 07-07-2022 for editing appointments https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
	public function appointmentDetailById()
	{
		$appointmentId = $this->input->get('appointmentId');
		$result = $this->DBmodel->getAppointmentDetail($appointmentId); 

		if($result)
        {
            echo json_encode($result);
        }
	}
	//end added on 07-07-2022

	//Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	public function update_pps_info()
    {
        $ppsid = $this->input->post('pps_id');
        $instruction = $this->input->post('data');
        
		$logData = 'PPS Id - ' . $ppsid . ', Data - ' . $instruction . PHP_EOL . PHP_EOL; 
		$this->createCustomLog($logData,'PPS','update_pps_info');

        $result = $this->DBmodel->update_pps_info($ppsid, $instruction);  
        //print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    } // end Added on 07-10-2022

	//added on 19-01-2023 https://trello.com/c/jlGzat5u/1646-cr-portal-duplicating-the-years
	public function get_pps_tab_data(){
		$case_id= $this->input->post('caseid');
		$data['checkpps'] = $this->DBmodel->check_pps($case_id);
		$pps = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps']);
		if(is_array($pps))
				{
					if(count($pps) > 0)
					{
						$data['pps'] = $pps;
					}
				}
				else{
					$data['pps'] = 0;
				}
		echo json_encode($data);
	} // end added on 19-01-2023

	//added on 10-03-2026 
	public function get_pps_premium_tab_data(){
		$case_id= $this->input->post('caseid');
		$data['checkpps'] = $this->DBmodel->check_pps_premium($case_id);
		$pps = $this->DBmodel->fetch_pps_premium_personel_sugar($data['checkpps']);
		if(is_array($pps))
				{
					if(count($pps) > 0)
					{
						$data['pps'] = $pps;
					}
				}
				else{
					$data['pps'] = 0;
				}
		echo json_encode($data);
	} // end added on 10-03-2026

	//added on 10-03-2026 
	public function get_pps_premium_spouse_tab_data(){
		$case_id= $this->input->post('caseid');
		$data['checkpps'] = $this->DBmodel->check_pps_premium_spouse($case_id);
		$pps = $this->DBmodel->fetch_pps_premium_spouse_personel_sugar($data['checkpps']);
		if(is_array($pps))
				{
					if(count($pps) > 0)
					{
						$data['pps'] = $pps;
					}
				}
				else{
					$data['pps'] = 0;
				}
		echo json_encode($data);
	} // end added on 10-03-2026

	public function logout()
	{
		//session_destroy();
		$this->session->sess_destroy();
		redirect('login');
	}

	public function createCustomLog($data,$function,$logFor)
	{
		$dirName = dirname(__DIR__, 3);
		//file_put_contents('path.txt', __DIR__ . ", " . $dirName);
		//file_put_contents('data.txt', $apiName . ", " . $logFor);
		$year = explode("/",date('Y/m/d'));
		$finalYear = $year[0];
		$finalMonth = $year[1];
		$finalDate = $year[2];
		$fileName = $finalYear.$finalMonth.$finalDate."_".$logFor.".log";
		$finalContent = "\n".date('Y-m-d H:i:s').":\n".print_r($data,true)." \r\n";
		$directoryName = $dirName . "/customlogs/CaseDetailController/". "/".$finalYear."/".$finalMonth . "/" . $finalDate. "/" . $function;
		//file_put_contents('createCustomLog.txt', $fileName . ', ' . $directoryName);
		if (!is_dir($directoryName))
		{
			mkdir($directoryName, 0755, true);
		}
		file_put_contents($directoryName."/".$fileName, $finalContent, FILE_APPEND | LOCK_EX);
	}

	// Added on 22-12-2023 https://trello.com/c/MC2tSrcs/800-taxstatus-deep-link
	public function viewTaxStatus(){
		$case_id= $this->input->post('caseid');	
		$tax_status_for = $this->input->post('tax_status_for');
		$spouse = 0;
		if($tax_status_for=="Spouse")
		{
			$spouse = 1;
		}	
		if(!empty($case_id)){	
			$taxStatusData = $this->DBmodel->get_tax_status_data($case_id, $spouse);
			echo json_encode($taxStatusData);
		}
	}
	// end added on 22-12-2023

	public function mycaseSync()
    {
        $caseId = $this->input->post('caseid');

		file_put_contents("mycaseSync.txt", 'caseId controller - '.print_r($caseId,true)."\n",FILE_APPEND | LOCK_EX );

        
        $result = $this->DBmodel->post_record_mycase($caseId);  
		//print_r($result);
        if($result)
        {
            echo json_encode($result);
        }
    }

	// public function crSummary()
    // {
    //     $data = $this->input->post();

	// 	file_put_contents("crSummary.txt", 'All post data - '.print_r($data,true)."\n",FILE_APPEND | LOCK_EX );

    //     $result = $this->get_data($caseId);  
	// 	//print_r($result);
    //     if($result)
    //     {
    //         echo json_encode($result);
    //     }
    // }
}