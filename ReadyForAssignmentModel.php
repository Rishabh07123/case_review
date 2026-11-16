<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ReadyForAssignmentModel extends CI_Model 
{
    
	//Fetch cases for case assignment tab
	public function fetch_ready_for_assignments($language,$payment_status,$user_ids,$team_id)
	{ 		
		$this->db_sugar->distinct();
		$this->db_sugar->select("  
									leads.id, 
									concat(leads.first_name,' ', leads.last_name) as name, 
									leads.assigned_user_id, 
									leads_cstm.lead_level_c, 
									leads_cstm.case_id_c, 
									leads_cstm.language_prequal_c, 
									leads_cstm.cr_fee_c, 
									leads_cstm.lending_partner_eligibility_c, 
									leads_cstm.financial_servicing_eligibil_c, 									
									
									concat(users.first_name,' ',users.last_name) as username, 
									
									aggre_aggregatevalues_cstm.billing_status_c, 
									aggre_aggregatevalues_cstm.logics_status_c, 
									round(aggre_aggregatevalues_cstm.percentage_paid_c,2) as percentage_paid_c,
									aggre_aggregatevalues_cstm.user_id7_c,

									'test' as spl_campaign
									");  // ,  derived_table_m.*
		
		$this->db_sugar->from('leads'); 		
		$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
		$this->db_sugar->join('users', 'users.id = leads.assigned_user_id', 'left');					
  		$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads.id = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida', 'left');	
  		$this->db_sugar->join('aggre_aggregatevalues_cstm', 'aggre_aggregatevalues_cstm.id_c = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb', 'left');	
  		//$this->db_sugar->where('ag_cstm.activate_for_assignment_c', 'Ready for Assignment');	
		$this->db_sugar->where('leads_aggre_aggregatevalues_1_c.deleted', 0);	  
		$this->db_sugar->where('aggre_aggregatevalues_cstm.activate_for_assignment_c', 'Ready for Assignment');		
		$this->db_sugar->where('leads.deleted', 0);		
		

		//$language,$payment_status
		if($language != '' && $language != null)
		{
			$this->db_sugar->where('leads_cstm.language_prequal_c', $language);
		}
		if($payment_status != '' && $payment_status != null)
		{
			$this->db_sugar->where('billing_status_c', $payment_status);
		}
		if($team_id != '' && $team_id != null)
		{
			$this->db_sugar->where('leads.team_id', $team_id);
		}
		if(is_array($user_ids) && count($user_ids) > 0)
		{
			$uid_list = '';
			foreach($user_ids as $uid)
			{
				$uid_list .= "'" . $uid . "',";
			}
			$uid_list = rtrim($uid_list,',');
			$where = 'leads.assigned_user_id IN (' . $uid_list . ')';
			$this->db_sugar->where($where);
		}
		
		$data = $this->db_sugar->get();
		$record = $data->result();			
	//print $this->db_sugar->last_query();
	//print_r($record);
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else
		{				
			return 0;
		}
    }
	
	public function assignTempUser($assign_user_id,  $lead_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
				
		//$instance_url = $this->sugar_url . "rest/v11";  case_temp_user
		//print 	$instance_url;	
		if($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($lead_array) > 0)
		{
			$lead_list =  '';
			foreach($lead_array as $key=>$val)
			{
				$lead_list .=  "'" . $val . "',";
			}
			$lead_list = rtrim($lead_list, ',');
			//print $lead_list;

			// Get all aggregate record ids
			$sql = "SELECT leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb as agg_id
					FROM leads_aggre_aggregatevalues_1_c
					WHERE leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida IN ($lead_list)"; 
			//print $sql;
			$query_sugar = $this->db_sugar->query($sql);	
			
			$record = $query_sugar->result_array();	
			//print_r($record);		
			
			$aggregate_array = array();

			if(is_array($record) && count($record) > 0)
			{
				for($i=0; $i<count($record); $i++)
				{
					//$agg_id_list .=  "'" . $record[$i]['agg_id'] . "',";
					$aggregate_array[] = $record[$i]['agg_id'];
				}
				//$agg_id_list = rtrim($agg_id_list, ',');

				//Mass Update Bean
				$uid = $this->session->userdata('User_id');
				$user_name = $this->session->userdata('uname');
				$user_pwd = $this->session->userdata('pwd');
				session_write_close();
						
				$instance_url =  $this->sugar_url . "rest/v11"; //
				//print 	$instance_url . ', ' . $user_name . ', ' . $user_pwd;	
				//return 0;
				if($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($aggregate_array) > 0)
				{	
					$record = array(
					
							"massupdate_params" => array(
								"uid" => $aggregate_array,						
								'user_id7_c' => $assign_user_id,		 //case_temp_user_c					
								'modified_user_id' => $uid
							)
					);
						//print_r($record); case_temp_user_c
						//die();	
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
//print $oauth2_token_response;
					//decode oauth2 response to get token
					$oauth2_token_response_obj = json_decode($oauth2_token_response);

					//if($oauth2_token_response_obj->error != '')
					if(property_exists($oauth2_token_response_obj, 'error'))
					{
						//print $oauth2_token_response_obj->error;
						return  $oauth2_token_response_obj->error_message;
					}
					
					$oauth_token = $oauth2_token_response_obj->access_token;
					
					//Create Records - PUT /<module>/id
					if($oauth_token != '')
					{
						$url = $instance_url . "/Aggre_AggregateValues/MassUpdate";
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
			else
			{				
				return 0;
			}	
		}
		else
		{
			return 0;
		}
	}

    public function update_lead_assigned_to( $lead_json, $batch_id) //$assign_user_id, 
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
				
		$instance_url =  $this->sugar_url . "rest/v11"; //
		//print 	$instance_url . ', ' . $user_name . ', ' . $user_pwd;	 //&& $assign_user_id != '' 
		$lead_array = array();
		$lead_array = explode(';',$lead_json);
		//$lead_array = json_decode($lead_json);
		//print_r($lead_array);
		//return 0;
		if($user_name != '' && $user_pwd != ''  && $uid != '' && count($lead_array) > 0)
		{
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
			
			if(property_exists($oauth2_token_response_obj, 'error'))
			{
				//print $oauth2_token_response_obj->error;
				return  $oauth2_token_response_obj->error_message;
			}

			$oauth_token = $oauth2_token_response_obj->access_token;
			
			foreach($lead_array as $row)
			{
				$lead_row_array = json_decode($row);
				
				if(count($lead_row_array) > 0) 
				{
					$record_id = $lead_row_array->id;

					$record = array(											
						'assigned_user_id' => $lead_row_array->user,							
						'modified_user_id' => $uid,
						'cr_batch_id_c' => $batch_id
					);
//print_r($record);
//die();
					if($oauth_token != '')
					{
						$url = $instance_url . "/Leads/" . $record_id;
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
						
						//echo $curl_response;
					}
				}
			}
			return 1;			
		}
		else
		{
			return 0;
		}
	}
    
    public function update_lead_assigned_status($lead_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');		
		session_write_close();
		
		$instance_url = $this->sugar_url . "rest/v11"; // https://sugarupdate.nablasol.net/
				
		if($user_name != '' && $user_pwd != ''  && $uid != '' && count($lead_array) > 0)
		{	
			$record = array(
			
					"massupdate_params" => array(
						"uid" => $lead_array,						
						'status' => 'Assigned',
						'custom_status_c' => 'New No Contact',
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
			
			
			if(property_exists($oauth2_token_response_obj, 'error'))
			{
				//print $oauth2_token_response_obj->error;
				return  $oauth2_token_response_obj->error_message;
			}

			$oauth_token = $oauth2_token_response_obj->access_token;
	
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/Leads/MassUpdate";
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