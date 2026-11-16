<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PPSAssignmentModel extends CI_Model 
{
    public function fetchPPSData($language,$payment_status , $folder, $stage, $call_type, $pps_type, $mstatus, $fstatus, $start_date='', $end_date='') //,$team_id
	{
/*
SELECT `inves_pps_cstm`.`state_c`, `inves_pps_cstm`.`split_pps_call_c`, `cases`.`name` as `client_name`, `leads_cstm`.`marital_status_c`, `leads_cstm`.`filing_status_state_c`, `cases_cstm`.`transcripts_only_eligible_c`, `aggre_aggregatevalues_cstm`.`logics_folder_c`, `inves_pps`.`id` as `pps_id`, `cases`.`id`, `cases_cstm`.`case_id_c`, DATE_FORMAT(cases.date_entered, '%m/%d/%Y') as date_created, `cases_cstm`.`investigation_advocate_statu_c`, `cases`.`assigned_user_id`, `leads_cstm`.`language_prequal_c`, concat(users.first_name, ' ', users.last_name) as username, concat(cu.first_name, ' ', cu.last_name) as case_user, `cases_cstm`.`investigation_type_c`, `income_type_c`, `spouse_income_type_c`, `derived_table2`.*, `pps_type`, `pps_call_type_c` 
FROM `leads` 
LEFT JOIN `leads_cstm` ON `leads_cstm`.`id_c` = `leads`.`id` 
LEFT JOIN `leads_aggre_aggregatevalues_1_c` ON `leads_aggre_aggregatevalues_1_c`.`leads_aggre_aggregatevalues_1leads_ida`=`leads`.`id` AND `leads_aggre_aggregatevalues_1_c`.`deleted`=0 
LEFT JOIN `aggre_aggregatevalues` ON `aggre_aggregatevalues`.`id`=`leads_aggre_aggregatevalues_1_c`.`leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb` AND `aggre_aggregatevalues`.`deleted`=0 
LEFT JOIN `aggre_aggregatevalues_cstm` ON `aggre_aggregatevalues_cstm`.`id_c`=`aggre_aggregatevalues`.`id` 
LEFT JOIN `fin_overview_leads_1_c` ON `fin_overview_leads_1_c`.`fin_overview_leads_1leads_idb` = `leads`.`id` AND `fin_overview_leads_1_c`.`deleted`=0 
LEFT JOIN `fin_overview_cstm` ON `fin_overview_cstm`.`id_c` = `fin_overview_leads_1_c`.`fin_overview_leads_1fin_overview_ida` 
LEFT JOIN `cases_cstm` ON `cases_cstm`.`case_id_c`=`leads_cstm`.`case_id_c` 
LEFT JOIN `cases` ON `cases`.`id`=`cases_cstm`.`id_c` 
LEFT JOIN `cases_inves_pps_1_c` ON `cases_inves_pps_1_c`.`cases_inves_pps_1cases_ida`=`cases`.`id` 
LEFT JOIN `inves_pps` ON `inves_pps`.`id`=`cases_inves_pps_1_c`.`cases_inves_pps_1inves_pps_idb` 
LEFT JOIN `inves_pps_cstm` ON `inves_pps_cstm`.`id_c` = `inves_pps`.`id` 
LEFT JOIN `users` ON `users`.`id` = `inves_pps`.`assigned_user_id` 
LEFT JOIN `users` as `cu` ON `cu`.`id` = `cases`.`assigned_user_id` 
LEFT JOIN (SELECT l1.id as derived_id2, billing_status_c 
	FROM aggre_aggregatevalues_cstm as ag_cstm 
	INNER JOIN aggre_aggregatevalues ag ON ag.id=ag_cstm.id_c 
	INNER JOIN leads_aggre_aggregatevalues_1_c as lg on lg.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb=ag_cstm.id_c INNER JOIN leads l1 ON l1.id=lg.leads_aggre_aggregatevalues_1leads_ida 
	WHERE ag.deleted=0 
	AND lg.deleted=0 
	AND ag_cstm.logics_status_c <> "CCS" 
	AND ag_cstm.logics_status_c <> "CCS Review" 
	AND ag_cstm.logics_status_c <> "Refunded" 
	AND ag_cstm.logics_status_c <> "Refund Requested" ) as derived_table2 ON `derived_table2`.`derived_id2`=`leads`.`id` 
WHERE (cases_cstm.investigation_advocate_statu_c = 'PPS Assigned' OR (`cases_cstm`.`investigation_advocate_statu_c` = 'Rework' AND `cases_cstm`.`rework_status_c` = 'PPS Assigned')) 
AND `leads`.`deleted` = 0 
AND `cases`.`deleted` = 0 
AND `cases_inves_pps_1_c`.`deleted` = 0 
AND `inves_pps`.`deleted` = 0 AND `pps_status` = 'Needed' 
AND (`inves_pps`.`assigned_user_id` = '' OR `inves_pps`.`assigned_user_id` IS NULL OR `inves_pps`.`assigned_user_id` = '1') 
ORDER BY `cases_cstm`.`case_id_c` ASC
*/ 		
		$this->db_sugar->select("inves_pps_cstm.state_c, inves_pps_cstm.split_pps_call_c, cases.name as client_name, leads_cstm.marital_status_c, leads_cstm.filing_status_state_c, cases_cstm.transcripts_only_eligible_c, aggre_aggregatevalues_cstm.logics_folder_c, inves_pps.id as pps_id, cases.id, cases_cstm.case_id_c, DATE_FORMAT(cases.date_entered,'%m/%d/%Y') as date_created, cases_cstm.investigation_advocate_statu_c, cases.assigned_user_id, leads_cstm.language_prequal_c, concat(COALESCE(users.first_name,''),' ',users.last_name) as username, concat(cu.first_name,' ',cu.last_name) as case_user, cases_cstm.investigation_type_c,  income_type_c, spouse_income_type_c, derived_table2.*, pps_type, pps_call_type_c");
		
		$this->db_sugar->from('leads');
		
		$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
		
		$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida=leads.id AND leads_aggre_aggregatevalues_1_c.deleted=0', 'left');			
		$this->db_sugar->join('aggre_aggregatevalues', 'aggre_aggregatevalues.id=leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb AND aggre_aggregatevalues.deleted=0', 'left');			
		$this->db_sugar->join('aggre_aggregatevalues_cstm', 'aggre_aggregatevalues_cstm.id_c=aggre_aggregatevalues.id', 'left');

		$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1leads_idb = leads.id AND fin_overview_leads_1_c.deleted=0', 'left');
		
		$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida', 'left');
		
		$this->db_sugar->join('cases_cstm', 'cases_cstm.case_id_c=leads_cstm.case_id_c', 'left');
		
		$this->db_sugar->join('cases', 'cases.id=cases_cstm.id_c', 'left');		
		
		$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1cases_ida=cases.id', 'left');
		$this->db_sugar->join('inves_pps', 'inves_pps.id=cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
		$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');

		$this->db_sugar->join('users', 'users.id = inves_pps.assigned_user_id', 'left');
		$this->db_sugar->join('users as cu', 'cu.id = cases.assigned_user_id', 'left');
				
		$this->db_sugar->join('(SELECT l1.id as derived_id2,  billing_status_c
		FROM aggre_aggregatevalues_cstm  as ag_cstm 
		INNER JOIN aggre_aggregatevalues ag ON ag.id=ag_cstm.id_c
		INNER JOIN leads_aggre_aggregatevalues_1_c as lg on lg.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb=ag_cstm.id_c 
		INNER JOIN leads l1 ON l1.id=lg.leads_aggre_aggregatevalues_1leads_ida
		WHERE ag.deleted=0
		AND lg.deleted=0 AND ag_cstm.logics_status_c <> "CCS" AND ag_cstm.logics_status_c <> "CCS Review" AND ag_cstm.logics_status_c <> "Refunded" AND ag_cstm.logics_status_c <> "Refund Requested" ) as derived_table2', 'derived_table2.derived_id2=leads.id', 'left');
		
		$where = " (cases_cstm.investigation_advocate_statu_c = 'PPS Assigned' OR (cases_cstm.investigation_advocate_statu_c = 'Rework' AND cases_cstm.rework_status_c = 'PPS Assigned')) ";

		$this->db_sugar->where($where);			
		//$this->db_sugar->where('cases.assigned_user_id', 1);
		$this->db_sugar->where('leads.deleted', 0);		
		$this->db_sugar->where('cases.deleted', 0);	
		$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);	
		$this->db_sugar->where('inves_pps.deleted', 0);			
		$this->db_sugar->where('pps_status', 'Needed');
		
		
		
		if($stage == 'Anthem')
		{
			//$where = " (inves_pps.assigned_user_id = 'b1199824-8e33-11ec-9241-0a1db36945e2' ) ";
			//$this->db_sugar->where($where);
		}
		else
		{
			$where = " (inves_pps.assigned_user_id = '' OR inves_pps.assigned_user_id IS NULL  OR inves_pps.assigned_user_id = '1') ";
			$this->db_sugar->where($where);
		}
		
		if($mstatus != '' && $mstatus != null)
		{
			$this->db_sugar->where('leads_cstm.marital_status_c', $mstatus);
		}
		if($fstatus != '' && $fstatus != null)
		{
			$this->db_sugar->where('leads_cstm.filing_status_state_c', $fstatus);
		}
		if($language != '' && $language != null)
		{
			$this->db_sugar->where('leads_cstm.language_prequal_c', $language);
		}
		if($payment_status != '' && $payment_status != null)
		{
			$this->db_sugar->where('aggre_aggregatevalues_cstm.billing_status_c', $payment_status);
		}
		if($pps_type != '' && $pps_type != null)
		{
			$this->db_sugar->where('pps_type', $pps_type);
		}
		if($call_type != '' && $call_type != null)
		{
			$this->db_sugar->where('pps_call_type_c', $call_type);
		}
		if($stage != '' && $stage != null)
		{
			$this->db_sugar->where('cases_cstm.investigation_type_c', $stage);
		}
		if($folder != '' && $folder != null)
		{
			$this->db_sugar->where('logics_folder_c', $folder);
		}
		$startdate = '';
		$enddate = '';

		if($start_date != '' && $start_date != null)
		{
			//$startdate = date_format(date_create($start_date),'Y-d-m');
			$this->db_sugar->where('cases.date_entered >=', $start_date);
		}
		if($end_date != '' && $end_date != null)
		{
			//$enddate = date_format(date_create($end_date),'Y-d-m');
			//$enddate = date("Y-m-d", strtotime($end_date));
			$this->db_sugar->where('cases.date_entered <=', $end_date);
		}

		$this->db_sugar->order_by('cases_cstm.case_id_c', 'ASC');

		$data = $this->db_sugar->get();
		$record = $data->result();	
		//print $this->db_sugar->last_query();
		
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else
		{				
			return 0;
		}
	}

	
	public function updatePPSAssignedTo($assign_user_id,  $pps_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		$instance_url = $this->sugar_url . "rest/v11"; 

		//$instance_url =  "https://sugarupdate.nablasol.net/rest/v11"; 
		//print 	$instance_url . ', ' . $user_name. ', ' . $user_pwd. ', ' . $uid. ', ' . $assign_user_id;
		//print '--------------';
		//print_r($pps_array);
		//return true;
		
		if($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($pps_array) > 0)
		{	
			$record = array(
			
					"massupdate_params" => array(
						"uid" => $pps_array,						
						'assigned_user_id' => $assign_user_id,							
						'modified_user_id' => $uid
					)
			);
					
			$auth_url = $instance_url . "/oauth2/token";

			$oauth2_token_arguments = array(
				"grant_type" => "password",
				"client_id" => "sugar", 
				"client_secret" => "",
				"username" => $user_name,
				"password" => $user_pwd,
				"platform" => "mobile" 
			);
					
			$auth_request = curl_init($auth_url);
			curl_setopt($auth_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($auth_request, CURLOPT_HEADER, false);
			curl_setopt($auth_request, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($auth_request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($auth_request, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($auth_request, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json"
			));

			//convert arguments to json
			$json_arguments = json_encode($oauth2_token_arguments);
			curl_setopt($auth_request, CURLOPT_POSTFIELDS, $json_arguments);

			//execute request
			$oauth2_token_response = curl_exec($auth_request);

			//decode oauth2 response to get token
			$oauth2_token_response_obj = json_decode($oauth2_token_response);
			//print_r($oauth2_token_response_obj);
			if(isset($oauth2_token_response_obj->error))
			{
				return $oauth2_token_response_obj->error_message;
			}
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/inves_PPS/MassUpdate";
				//Set up the Record details
				
				$curl_request = curl_init($url);
				curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
				curl_setopt($curl_request, CURLOPT_HEADER, false);
				curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
					"Content-Type: application/json",
					"oauth-token: {$oauth_token}"
				));

				//convert arguments to json
				$json_arguments = json_encode($record);
				curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
				
				$curl_response = curl_exec($curl_request);
				
				$updatedRecord  = json_decode($curl_response);
				curl_close($curl_request);	
				
				echo $curl_response;
			}
		}
		else
		{
			return 0;
		}
	}
	
}