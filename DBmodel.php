<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DBmodel extends CI_Model 
{	
	//Validate login from sugar
	public function login($userid,$pwd)
	{		
		if($userid != '' && $pwd != '')
		{
			$this->db_sugar->select('id, first_name, last_name, user_hash');
			$this->db_sugar->from('users');					
			$this->db_sugar->where(array('user_name' =>$userid, 'deleted' =>'0' ));			
				
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($data_sugar);
			if(count($record_sugar) > 0)
			{				
				$uid = $record_sugar[0]->id;
				$hash = $record_sugar[0]->user_hash;
				$username = $record_sugar[0]->first_name . ' ' . $record_sugar[0]->last_name;				
			}
			else
			{			
				return 0;
			}
			
			if($uid != '' && $hash != '')
			{
				$pwd1 = md5($pwd);
				$valid = password_verify($pwd1, $hash);
				
				if($valid == true)
				{					
					$session_data=array(
									'User_id'=>$uid,
									'uname'=>$userid,
									'username'=>$username,
									'pwd'=>$pwd,
									'user_typeid'=>1,									
									'loged_in'=>true
									);
					$this->session->set_userdata($session_data);	
					session_write_close();
										
					$this->createCustomLog($uid,'Login','login');
					// $this->load->helper('custom');
					// $oauth_token = getAuthToken();

					// if($oauth_token)
					// {					
					// 	$this->session->set_userdata('oauth_token', $oauth_token);
					// }
					// file_put_contents('sess.txt',print_r($this->session->userdata,true));
					return 1;
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
	}	
	
	public function addNewBusiness($caseid)
	{
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		$lead_id = '';

		if($caseid != '')
		{
			$this->db_sugar->select('id');
			$this->db_sugar->from('leads');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);	
			$this->db_sugar->where('leads.deleted', 0);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($data_sugar);
			if(count($record_sugar) > 0)
			{				
				$lead_id = $record_sugar[0]->id;				
			}
		}
		else
		{
			return 0;
		}

		if($lead_id != '' && $user_name != '' && $user_pwd != '')
		{				
			$instance_url = $this->sugar_url . "rest/v11";  
			//file_put_contents('url.txt',$instance_url);
			//Login - POST /oauth2/token
			$auth_url = $instance_url . "/oauth2/token";

			$oauth2_token_arguments = array(
			
				"grant_type" => "password",
				//client id - default is sugar. 
				//It is recommended to create your own in Admin > OAuth Keys
				"client_id" => "sugar", 
				"client_secret" => "", 
				"username" => $user_name,
				"password" => $user_pwd,
				//platform type - default is base.
				//It is recommend to change the platform to a custom name such as "custom_api" to avoid authentication conflicts.
				"platform" => "mobile" 
			);
			
			//file_put_contents('oauth2_token_arguments.txt',print_r($oauth2_token_arguments));
			
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
			
			//file_put_contents('oauth2_token_response.txt',$oauth2_token_response); 
			
			//decode oauth2 response to get token
			$oauth2_token_response_obj = json_decode($oauth2_token_response);
			$oauth_token = $oauth2_token_response_obj->access_token;

			$record = array(
				'name' => "TBD",
				'trade_name_c' => "TBD", 
				'entity_type_c' => "na",
				'modified_user_id' => $uid,
				'created_by' => $uid,
				'description' => "",
				'leads_epi_business_1leads_ida'=> $lead_id
			);
			
			$url = $instance_url . "/epi_business";
			//Set up the Record details
			
			$curl_request_rli = curl_init($url);
			curl_setopt($curl_request_rli, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($curl_request_rli, CURLOPT_HEADER, false);
			curl_setopt($curl_request_rli, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl_request_rli, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_request_rli, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($curl_request_rli, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"oauth-token: {$oauth_token}"
			));

			$json_arguments = json_encode($record);
			curl_setopt($curl_request_rli, CURLOPT_POSTFIELDS, $json_arguments);
			
			$curl_response = curl_exec($curl_request_rli);
			
			$createdRecord = json_decode($curl_response);
			curl_close($curl_request_rli);
			//file_put_contents('new_business_rec.txt',print_r($record,true));
			//file_put_contents('new_business.txt',$curl_response);
			if($createdRecord->id != '')
			{
				
				return $createdRecord->id;
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
	
	//get investigation advocate users
	public function getInvestigationAdvocateUsers()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{						
			$this->db_sugar->select("acl_roles_users.user_id, concat(users.first_name, ' ', users.last_name) as uname "); 
			
			$this->db_sugar->from('acl_roles_users');
			
			$this->db_sugar->join('acl_roles', 'acl_roles.id = acl_roles_users.role_id', 'left');	
			
			$this->db_sugar->join('users', 'users.id = acl_roles_users.user_id', 'left');		
	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles.deleted', 0);
			$this->db_sugar->where('users.deleted', 0);
			$role = array('Investigation Advocate','Investigation Teamlead', 'Investigation Admin');
			$this->db_sugar->where_in('acl_roles.name', $role);			
			$this->db_sugar->order_by('uname');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	//get users of Sr. Case Analyst role_id
	public function getAllSrCaseAnalystUsers()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$where = '(acl_roles.name="Sr. Case Analyst" OR acl_roles.name="Case Advocate" OR acl_roles.name = "Sales Management" OR acl_roles.name = "Sales Admin" OR acl_roles.name = "Sales Administrator" OR acl_roles.name = "P3")';
			
			
			$this->db_sugar->select("users.id, concat(users.first_name, ' ', users.last_name) as uname "); 			
			$this->db_sugar->from('users');	
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.user_id = users.id', 'left');
			$this->db_sugar->join('acl_roles', 'acl_roles.id = acl_roles_users.role_id', 'left');

			$this->db_sugar->where($where);
			
			//$this->db_sugar->where('acl_roles.name', 'Sr. Case Analyst');	
			$this->db_sugar->where('users.deleted', 0);	
			$this->db_sugar->where('users.status', 'Active');		
			$this->db_sugar->where('acl_roles.deleted', 0);		
			$this->db_sugar->where('acl_roles_users.deleted', 0);		
			$this->db_sugar->order_by('uname');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	public function getAllUsers($role='')
	{
		$where_role = '';

		/*$this->db_sugar->select(" users.id, IFNULL(concat(users.first_name, ' ', users.last_name), last_name) as uname "); 			
		$this->db_sugar->from('users');
		$this->db_sugar->join('acl_roles_users', 'acl_roles_users.user_id=users.id', 'left');
		$this->db_sugar->join('acl_roles', 'acl_roles.id=acl_roles_users.role_id', 'left');
		//$this->db_sugar->from('');
		
		//$where  = "( status = 'Active' OR is_group = 1)";

		//$this->db_sugar->where($where);
		if($where_role != '')
		{
			$this->db_sugar->where($where_role);
		}
		
		$this->db_sugar->where('users.deleted', 0);	
		$this->db_sugar->where('acl_roles_users.deleted', 0);	
		$this->db_sugar->where('acl_roles.deleted', 0);		
		$this->db_sugar->order_by('uname'); */
		
		// if($role == 'Advocate')
		// {
		// 	$where_role  = "( acl_roles.name = 'P3 Advocate' || acl_roles.name = 'Case Advocate' || acl_roles.name = 'Back Office Admin') AND ( status = 'Active' OR is_group = 1)";
		// }
		// if($role == 'Practitionar')
		// {
		// 	$where_role  = "( acl_roles.name = 'P3 Practitioner' || acl_roles.name = 'Practitioner'  || acl_roles.name = 'Back Office Admin')";
		// }
		// if($role == 'TaxPrep')
		// {
		// 	$where_role  = "( acl_roles.name = 'Tax Preparer' || acl_roles.name = 'Tax Preparation Reviewer'  || acl_roles.name = 'Back Office Admin')";
		// }  // Commented on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned

		if($where_role != '')
		{
			$sql = "SELECT users.id, IFNULL(concat(users.first_name, ' ', users.last_name), last_name) as uname
			FROM users
			LEFT JOIN acl_roles_users ON acl_roles_users.user_id=users.id
			LEFT JOIN acl_roles ON acl_roles.id=acl_roles_users.role_id 
			WHERE  (status = 'Active' OR is_group = 1)
			AND " . $where_role . 
			" AND users.deleted = 0
			AND acl_roles_users.deleted=0
			AND acl_roles.deleted = 0
			
			UNION 
			SELECT users.id, IFNULL(concat(users.first_name, ' ', users.last_name), last_name) as uname
			FROM users
			WHERE  is_group = 1
			AND users.deleted = 0
			ORDER BY uname";
		}
		else
		{
			$sql = "SELECT `users`.`id`, IFNULL(concat(users.first_name, ' ', users.last_name), last_name) as uname
			FROM `users`
			WHERE  (`status` = 'Active' OR `is_group` = 1)			
			AND `users`.`deleted` = 0 ORDER BY uname";
		}

		$record1 = $this->db_sugar->query($sql);
		$record = $record1->result();		
		
		//print_r($record);
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else 
			return 0; 						
	}
	
	/*
		Function for get users list for given group 
		Parameters. 
		Author- Tara (01-Dec-2020)
	*/
	public function getUserByGroup($group)
	{
		$this->db_sugar->select("users.id, concat(users.first_name, ' ', users.last_name) as uname "); 			
		$this->db_sugar->from('users');
		$this->db_sugar->join('users_cstm', 'users_cstm.id_c = users.id', 'left');
		$this->db_sugar->where('users.status','Active');		
		$this->db_sugar->like('users_cstm.poa_group_c',$group . '^');		
		$this->db_sugar->where('users.deleted', 0);		
		$this->db_sugar->order_by('uname');
		
		$data = $this->db_sugar->get();
		//print  $this->db_sugar->last_query();
		$record = $data->result();
		
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else 
			return 0; 						
	}

	public function checkSalesAdministratorRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');
			
			//$this->db_sugar->join('users', 'users.id=acl_roles_users.user_id', 'left');			
	
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			
			$where = '(acl_roles.name = "Sales Admin" OR acl_roles.name = "Sales Administrator" OR acl_roles.name = "P3")';			
			$this->db_sugar->where($where);	 
			//'1fb2e5b0-794c-11e9-9525-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record[0]->name;
			}
		}
	}

	public function checkInvestigationManagerRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');
			
			//$this->db_sugar->join('users', 'users.id=acl_roles_users.user_id', 'left');			
	
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			
			$where = '(acl_roles.name = "Investigation Manager" OR acl_roles.name = "Investigation Reviewer" OR acl_roles.name = "Investigation Admin")';			
			$this->db_sugar->where($where);	 
			//'1fb2e5b0-794c-11e9-9525-0a1db36945e2' 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return 1;
			}
		}
	}
	
	public function checkBackOfficeAdminRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');
			
			//$this->db_sugar->join('users', 'users.id=acl_roles_users.user_id', 'left');			
	
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			
			$where = '(acl_roles.name = "Back Office Admin")';			
			$this->db_sugar->where($where);	 
			//'1fb2e5b0-794c-11e9-9525-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return 1;
			}
		}
	}

	public function checkPracRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');
			
			//$this->db_sugar->join('users', 'users.id=acl_roles_users.user_id', 'left');			
	
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			
			$where = '(acl_roles.name = "Practitioner")';			
			$this->db_sugar->where($where);	 
			//'1fb2e5b0-794c-11e9-9525-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return 1;
			}
		}
	}

	public function checkInvestigationReviewerRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		//print $uid;
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');
			
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			
			$where = '(acl_roles.name="Investigation Reviewer"  OR acl_roles.name = "P3" OR acl_roles.name = "Investigation Admin")';
			
			$this->db_sugar->where($where);	 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record); 'Investigation Admin'
			//die(); 
			if(is_array($record) && count($record) > 0)
			{
				return $record[0]->name;
			}
		}
	}
	
	public function checkUserRoleAssignment()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		$role_name = $this->checkSalesAdministratorRole();
		
		if($role_name == 'Sales Admin' || $role_name == 'Sales Administrator' || $role_name == 'P3')
		{
			return 1;
		}
		else if($uid != '')
		{
			$this->db_sugar->select("is_admin ");
			
			$this->db_sugar->from('users');
			
			$this->db_sugar->where('users.deleted', 0);		
			$this->db_sugar->where('users.id', $uid); 
			//'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->is_admin == 1)
				{
					//return $record[0]->is_admin;
					return 1;
				}
				else
				{
					return 0;
				}
			}
		}
	}
	
	public function checkIfAdmin()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("is_admin ");
			
			$this->db_sugar->from('users');
			
			$this->db_sugar->where('users.deleted', 0);		
			$this->db_sugar->where('users.id', $uid); 
			//'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->is_admin == 1)
				{
					//return $record[0]->is_admin;
					return 1;
				}
				else
				{
					return 0;
				}
			}
		}
	}
	
	public function checkIfReviewer()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$where = '(acl_roles.name="Investigation Reviewer"  OR acl_roles.name = "Investigation Admin")';
			
			$this->db_sugar->select("name ");
			
			$this->db_sugar->from('acl_roles'); 

			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id = acl_roles.id', 'left');

			$this->db_sugar->where($where);		
			$this->db_sugar->where('acl_roles_users.user_id', $uid);
			
			$data = $this->db_sugar->get();
			//print  $this->db_sugar->last_query();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
					return 1;
			}
			else
			{
				return 0;
			}
		}		
	}

	public function checkIfPractitioner()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("name ");
			
			$this->db_sugar->from('acl_roles'); 

			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id = acl_roles.id', 'left');

			$this->db_sugar->where('acl_roles.name', 'Investigation Practitioner');		
			$this->db_sugar->where('acl_roles_users.user_id', $uid);
			
			$data = $this->db_sugar->get();
			//print  $this->db_sugar->last_query();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
					return 1;
			}
			else
			{
				return 0;
			}
		}		
	}

	public function checkUserRoleWritingAssignment()
	{ //print 'checkUserRoleWritingAssignment';
		$uid = $this->session->userdata('User_id');
		session_write_close();
		$role_name = $this->checkInvestigationReviewerRole();
		//print 'role_name - ' . $role_name;
		if($role_name == 'Investigation Reviewer' || $role_name == 'P3' || $role_name == 'Investigation Admin')
		{
			return 1;
		}
		else if($uid != '')
		{
			$this->db_sugar->select("is_admin ");
			
			$this->db_sugar->from('users');
			
			$this->db_sugar->where('users.deleted', 0);		
			$this->db_sugar->where('users.id', $uid); 
			//'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->is_admin == 1)
				{
					//return $record[0]->is_admin;
					return 1;
				}
				else
				{
					return 0;
				}
			}
		}
	}
	
	//CaseList functions for logged in user's cases
	public function getMyCases()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			/*select concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c,  cstm.pps_call_date_c as pps_call_date_c, ep.payment_date, group_concat(rlic.product_catalog_type_c) as pname , sum(rli.total_amount) AS total_amount, group_concat(pc.name) as pcname, cstm.logics_billingstatus_c as lbstatus 
				from leads l
				left join leads_cstm lc on l.id=lc.id_c
				left join opportunities op on l.opportunity_id = op.id
				left join epi_payment_plans_opportunities_1_c epo on op.id=epo.epi_payment_plans_opportunities_1opportunities_idb
				left join epi_payment_plans epp on epo.epi_payment_plans_opportunities_1epi_payment_plans_ida=epp.id
				left join epi_payment_plans_epi_payments_1_c eppe on epp.id=eppe.epi_payment_plans_epi_payments_1epi_payment_plans_ida
				left join epi_payments ep on eppe.epi_payment_plans_epi_payments_1epi_payments_idb=ep.id
				left join cases_cstm cstm on lc.case_id_c=cstm.case_id_c
				left join cases cs on cstm.id_c=cs.id
				left join revenue_line_items rli on op.id=rli.opportunity_id
				left join revenue_line_items_cstm rlic on rli.id=rlic.id_c
				left join product_templates pt on rli.product_template_id=pt.id
				left join product_categories pc on rli.category_id=pc.id
				where l.deleted= 0	
				and cs.assigned_user_id=''
				and (cstm.investigation_advocate_statu_c="Strategy Assigned" OR cstm.investigation_advocate_statu_c = "Returned from Back Office")
				and rli.sales_stage='Closed Won'	
				and rli.deleted=0			
				group by lc.case_id_c, lc.lead_level_c, l.id, cs.id, cstm.id_c, ep.payment_date
				*/
			
			$this->db_sugar->select("concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c,  cstm.pps_call_date_c as pps_call_date_c, group_concat(rlic.product_catalog_type_c) as pname , '' as payment_date, sum(rli.total_amount) AS total_amount, group_concat(pc.name) as pcname, cstm.logics_billingstatus_c as lbstatus, investigation_type_c, derived_table3.* "); //lc.state_poa_c as state_poa_c, lc.js_8821_fax_date_c as js_8821_fax_date_c,
			
			$this->db_sugar->from('leads l');
			
			$this->db_sugar->join('leads_cstm lc', 'l.id=lc.id_c', 'left');	

			$this->db_sugar->join('opportunities op', 'l.opportunity_id = op.id', 'left');
			
			//$this->db_sugar->join('epi_payment_plans_opportunities_1_c epo', 'op.id=epo.epi_payment_plans_opportunities_1opportunities_idb', 'left');
			
			//$this->db_sugar->join('epi_payment_plans epp', 'epo.epi_payment_plans_opportunities_1epi_payment_plans_ida=epp.id', 'left');
								
			//$this->db_sugar->join('epi_payment_plans_epi_payments_1_c eppe', 'epp.id=eppe.epi_payment_plans_epi_payments_1epi_payment_plans_ida', 'left');
			
			//$this->db_sugar->join('epi_payments ep', 'eppe.epi_payment_plans_epi_payments_1epi_payments_idb=ep.id', 'left');
			
			$this->db_sugar->join('cases_cstm cstm', 'lc.case_id_c=cstm.case_id_c', 'left');
			$this->db_sugar->join('cases cs', 'cstm.id_c=cs.id', 'left');
			
			$this->db_sugar->join('revenue_line_items rli', 'op.id=rli.opportunity_id', 'left');
			
			$this->db_sugar->join('revenue_line_items_cstm rlic', 'rli.id=rlic.id_c', 'left');
			
			$this->db_sugar->join('product_templates pt', 'rli.product_template_id=pt.id', 'left');
			
			$this->db_sugar->join('product_categories pc', 'rli.category_id=pc.id', 'left');
			
			$this->db_sugar->join('(SELECT cases_ths_ths_1_c.cases_ths_ths_1cases_ida,  ths_ths.date_modified as ths_date, ths_record_type, ths_client_type, DATE_FORMAT(ths_report_date_c,"%m/%d/%Y") as ths_report_date_c, location_c 
	FROM ths_ths 
	INNER JOIN ths_ths_cstm ON ths_ths_cstm.id_c = ths_ths.id
	INNER JOIN cases_ths_ths_1_c on cases_ths_ths_1_c.cases_ths_ths_1ths_ths_idb=ths_ths.id
	INNER JOIN cases as c ON c.id=cases_ths_ths_1_c.cases_ths_ths_1cases_ida
	WHERE ths_ths.deleted=0 
	AND cases_ths_ths_1_c.deleted=0 
	AND ths_ths_cstm.location_c= "DMS_SSN" AND ths_record_type = "Individual") as derived_table3',  'derived_table3.cases_ths_ths_1cases_ida=cs.id', 'left');
	
			$this->db_sugar->where('l.deleted', 0);	
			//$this->db_sugar->where('cstm.investigation_advocate_statu_c', 'Strategy Assigned');
			
			$where = '(cstm.investigation_advocate_statu_c="Strategy Assigned" OR cstm.investigation_advocate_statu_c = "Returned from Back Office")';
			$this->db_sugar->where($where);
	   
			//$this->db_sugar->where('cstm.investigation_advocate_statu_c', 'Returned from Back Office');
			//$this->db_sugar->where('cstm.user_id12_c', $uid);	
			$this->db_sugar->where('cs.assigned_user_id', $uid); //'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'	
			$this->db_sugar->where('rli.sales_stage', 'Closed Won');	
			$this->db_sugar->where('rli.deleted', 0);				
			$this->db_sugar->where('cs.deleted', 0);				
			//$this->db_sugar->group_by('lc.case_id_c, lc.lead_level_c, l.id, cs.id, cstm.id_c, ep.payment_date'); 
			$this->db_sugar->group_by('lc.case_id_c, lc.lead_level_c, l.id, cs.id, payment_date, cstm.id_c'); 
			$this->db_sugar->order_by('cstm.logics_billingstatus_c','"Paid in Full", "Current", "Past Due"'); 
			 
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	//CaseList functions for logged in user's pps calls
	public function getMyPPSCalls()
	{
		$uid = $this->session->userdata('User_id'); 
		session_write_close();
		
		if($uid != '')
		{
			/*
			select concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c,cstm.investigation_advocate_last_c as investigationLaststatusUpdate, group_concat(rlic.product_catalog_type_c) as pname ,ep.payment_date, sum(rli.total_amount) AS total_amount,group_concat(pc.name) as pcname, cstm.logics_billingstatus_c as lbstatus, pps.poa_issues, pps.pps_type, pps_cstm.biz_poa_issues_new_c 
				
				from leads l
				left join leads_cstm lc on l.id=lc.id_c
				left join opportunities op on l.opportunity_id = op.id
				left join epi_payment_plans_opportunities_1_c epo on op.id=epo.epi_payment_plans_opportunities_1opportunities_idb
				left join epi_payment_plans epp on epo.epi_payment_plans_opportunities_1epi_payment_plans_ida=epp.id
				left join epi_payment_plans_epi_payments_1_c eppe on epp.id=eppe.epi_payment_plans_epi_payments_1epi_payment_plans_ida
				left join epi_payments ep on eppe.epi_payment_plans_epi_payments_1epi_payments_idb=ep.id
				left join cases_cstm cstm on lc.case_id_c=cstm.case_id_c
				left join cases cs on cstm.id_c=cs.id
				left join revenue_line_items rli on op.id=rli.opportunity_id
				left join revenue_line_items_cstm rlic on rli.id=rlic.id_c
				left join product_templates pt on rli.product_template_id=pt.id
				left join product_categories pc on rli.category_id=pc.id
				left join cases_inves_pps_1_c cspps on cs.id = cspps.cases_inves_pps_1cases_ida
				left join inves_pps pps on cspps.cases_inves_pps_1inves_pps_idb = pps.id
				left join inves_pps_cstm pps_cstm on pps_cstm.id_c = pps.id
				where l.deleted= 0	
				and pps.pps_status= 'Needed'
				and cs.status <> 'Terminated'
				and cs.status <> 'Suspended'
				and cstm.investigation_advocate_statu_c= 'PPS Assigned'
				and pps.assigned_user_id=''	
				group by lc.case_id_c, lc.lead_level_c, l.id, cs.id, cstm.id_c, ep.payment_date, pps.id
								*/ 
			$this->db_sugar->select("lc.language_prequal_c as client_language, cstm.transcripts_only_eligible_c, DATE_FORMAT(date(cs.date_entered),'%m/%d/%Y') as case_created_date , concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c,cstm.investigation_advocate_last_c as investigationLaststatusUpdate, group_concat(rlic.product_catalog_type_c) as pname ,'' as payment_date , round(sum(rli.total_amount),2) AS total_amount,group_concat(pc.name) as pcname, cstm.logics_billingstatus_c as lbstatus, pps.poa_issues, pps.pps_type, pps.pps_status, pps_cstm.biz_poa_issues_new_c, investigation_type_c, pps_cstm.pps_instructions_c ");
				
			$this->db_sugar->from('leads l');
			
			$this->db_sugar->join('leads_cstm lc', 'l.id=lc.id_c', 'left');	

			$this->db_sugar->join('opportunities op', 'l.opportunity_id = op.id', 'left');
			
			//$this->db_sugar->join('epi_payment_plans_opportunities_1_c epo', 'op.id=epo.epi_payment_plans_opportunities_1opportunities_idb', 'left');
			
			//$this->db_sugar->join('epi_payment_plans epp', 'epo.epi_payment_plans_opportunities_1epi_payment_plans_ida=epp.id', 'left');
								
			//$this->db_sugar->join('epi_payment_plans_epi_payments_1_c eppe', 'epp.id=eppe.epi_payment_plans_epi_payments_1epi_payment_plans_ida', 'left');
			
			//$this->db_sugar->join('epi_payments ep', 'eppe.epi_payment_plans_epi_payments_1epi_payments_idb=ep.id', 'left');
			
			//$this->db_sugar->join('cases cs', 'lc.case_id_c=cs.system_id', 'left');
			
			//$this->db_sugar->join('cases_cstm cstm', 'cs.id=cstm.id_c', 'left');
			$this->db_sugar->join('cases_cstm cstm', 'lc.case_id_c=cstm.case_id_c', 'left');
			$this->db_sugar->join('cases cs', 'cstm.id_c=cs.id', 'left');
			
			$this->db_sugar->join('revenue_line_items rli', 'op.id=rli.opportunity_id', 'left');
			
			$this->db_sugar->join('revenue_line_items_cstm rlic', 'rli.id=rlic.id_c', 'left');
			
			$this->db_sugar->join('product_templates pt', 'rli.product_template_id=pt.id', 'left');
			
			$this->db_sugar->join('product_categories pc', 'rli.category_id=pc.id', 'left');
	
			$this->db_sugar->join('cases_inves_pps_1_c cspps', 'cs.id = cspps.cases_inves_pps_1cases_ida', 'left');

			$this->db_sugar->join('inves_pps pps', 'cspps.cases_inves_pps_1inves_pps_idb = pps.id', 'left');
			
			$this->db_sugar->join('inves_pps_cstm pps_cstm', 'pps_cstm.id_c = pps.id', 'left');

			$this->db_sugar->where('l.deleted', 0);	
			$this->db_sugar->where('cs.deleted', 0);	
			$this->db_sugar->where('pps.deleted', 0);	
			$this->db_sugar->where('cspps.deleted', 0);	
			
			$where = '(pps.pps_status = "Needed" OR pps.pps_status="Transcripts Only")';
			$this->db_sugar->where($where);
			
			$this->db_sugar->where('cs.status <>', 'Terminated');
			$this->db_sugar->where('cs.status <>', 'Suspended');
			$this->db_sugar->where('cstm.investigation_advocate_statu_c', 'PPS Assigned');
			$this->db_sugar->where('pps.assigned_user_id',  $uid); // $uid '5aebaa00-c284-11e9-9656-0a1db36945e2'
						
			$this->db_sugar->group_by('lc.case_id_c, lc.lead_level_c, l.id, cs.id, cstm.id_c, payment_date, pps.id');
			
			$this->db_sugar->order_by('cs.date_entered, cstm.logics_billingstatus_c','desc');
			
			//$this->db_sugar->where('pps_cstm.pps_requested_by_c', 'Servicing');
			//$this->db_sugar->where('pps.pps_type', 'Federal Personal');	
			//$this->db_sugar->where('cstm.user_id11_c', $uid);	//'5fbb58e0-794c-11e9-9708-0a1db36945e2' Keena
			//$this->db_sugar->where('rli.sales_stage', 'Closed Won');	
			//$this->db_sugar->where('rli.deleted', 0);	
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	//CaseList functions for logged in user's transcript appts
	public function getMyPPSTranscriptAppts()
	{
		$uid = $this->session->userdata('User_id'); 
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c,cstm.investigation_advocate_last_c as investigationLaststatusUpdate, group_concat(rlic.product_catalog_type_c) as pname , round(sum(rli.total_amount),2) AS total_amount,group_concat(pc.name) as pcname, cstm.logics_billingstatus_c as lbstatus, pps.poa_issues, pps.pps_type, pps.pps_status, pps_cstm.biz_poa_issues_new_c, investigation_type_c, pps_cstm.pps_instructions_c  "); //ep.payment_date,
				
			$this->db_sugar->from('leads l');
			
			$this->db_sugar->join('leads_cstm lc', 'l.id=lc.id_c', 'left');	

			$this->db_sugar->join('opportunities op', 'l.opportunity_id = op.id', 'left');
			
			/*$this->db_sugar->join('epi_payment_plans_opportunities_1_c epo', 'op.id=epo.epi_payment_plans_opportunities_1opportunities_idb', 'left');
			
			$this->db_sugar->join('epi_payment_plans epp', 'epo.epi_payment_plans_opportunities_1epi_payment_plans_ida=epp.id', 'left');
								
			$this->db_sugar->join('epi_payment_plans_epi_payments_1_c eppe', 'epp.id=eppe.epi_payment_plans_epi_payments_1epi_payment_plans_ida', 'left');
			
			$this->db_sugar->join('epi_payments ep', 'eppe.epi_payment_plans_epi_payments_1epi_payments_idb=ep.id', 'left');*/
			
			$this->db_sugar->join('cases_cstm cstm', 'lc.case_id_c=cstm.case_id_c', 'left');
			$this->db_sugar->join('cases cs', 'cstm.id_c=cs.id', 'left');
			
			$this->db_sugar->join('revenue_line_items rli', 'op.id=rli.opportunity_id', 'left');
			
			$this->db_sugar->join('revenue_line_items_cstm rlic', 'rli.id=rlic.id_c', 'left');
			
			$this->db_sugar->join('product_templates pt', 'rli.product_template_id=pt.id', 'left');
			
			$this->db_sugar->join('product_categories pc', 'rli.category_id=pc.id', 'left');
	
			$this->db_sugar->join('cases_inves_pps_1_c cspps', 'cs.id = cspps.cases_inves_pps_1cases_ida', 'left');

			$this->db_sugar->join('inves_pps pps', 'cspps.cases_inves_pps_1inves_pps_idb = pps.id', 'left');
			
			$this->db_sugar->join('inves_pps_cstm pps_cstm', 'pps_cstm.id_c = pps.id', 'left');

			$this->db_sugar->where('l.deleted', 0);	
			$this->db_sugar->where('cs.deleted', 0);	
			$this->db_sugar->where('pps.deleted', 0);	
			$this->db_sugar->where('cspps.deleted', 0);	
			
			//$where = '(pps.pps_status = "Needed" OR pps.pps_status="Transcripts Only")';
			//$this->db_sugar->where($where);
			
			$this->db_sugar->where('cs.status <>', 'Terminated');
			$this->db_sugar->where('cs.status <>', 'Suspended');
			$this->db_sugar->where('cstm.investigation_advocate_statu_c', 'Paused'); //'Transcript Appt'
			$this->db_sugar->where('pps.assigned_user_id', $uid ); //  '5fbb58e0-794c-11e9-9708-0a1db36945e2'
			$this->db_sugar->where('pps.pps_status', 'Needed');
			$this->db_sugar->group_by('lc.case_id_c, lc.lead_level_c, l.id, cs.id, cstm.id_c, pps.id'); //ep.payment_date,
			
			$this->db_sugar->order_by('cstm.logics_billingstatus_c','desc');
			
			//$this->db_sugar->where('pps_cstm.pps_requested_by_c', 'Servicing');
			//$this->db_sugar->where('pps.pps_type', 'Federal Personal');	
			//$this->db_sugar->where('cstm.user_id11_c', $uid);	//'5fbb58e0-794c-11e9-9708-0a1db36945e2' Keena
			//$this->db_sugar->where('rli.sales_stage', 'Closed Won');	
			//$this->db_sugar->where('rli.deleted', 0);	
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	public function checkUserRole()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			$this->db_sugar->select("acl_roles.name ");
			
			$this->db_sugar->from('acl_roles');
			
			$this->db_sugar->join('acl_roles_users', 'acl_roles_users.role_id=acl_roles.id', 'left');			
	
			$this->db_sugar->where('acl_roles.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.deleted', 0);	
			$this->db_sugar->where('acl_roles_users.user_id', $uid);	 
			$role = array('Investigation Reviewer', 'Investigation Admin');	 
			//$this->db_sugar->where('acl_roles.name', 'Investigation Reviewer');	 
			$this->db_sugar->where_in('acl_roles.name', $role);	
			//'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record[0]->name;
			}
		}
	}
	
	//CaseList functions for logged in user's under pricing status
	public function getMyPricingCases()
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($uid != '')
		{
			//$role = $this->checkUserRole();
			/*select concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c, DATE_FORMAT(date(cstm.investigation_advocate_last_c),'%m/%d/%Y') as investigationLaststatusUpdate, cstm.logics_billingstatus_c as lbstatus ");
				from leads l
				left join leads_cstm lc on l.id=lc.id_c
				left join cases_cstm cstm on lc.case_id_c=cstm.case_id_c
				left join cases cs on cstm.id_c=cs.id
				where l.deleted
				and cstm.investigation_advocate_statu_c= 'Pricing'
				*/
			$this->db_sugar->select("concat(
				(CASE WHEN l.first_name != '' THEN CONCAT(l.first_name,' ') ELSE '' END),(CASE WHEN l.last_name !='' THEN CONCAT(l.last_name,'') ELSE '' END)) as 'name', cs.id as cs_id, lc.case_id_c, lc.lead_level_c, lc.language_prequal_c, DATE_FORMAT(date(cstm.investigation_advocate_last_c),'%m/%d/%Y') as investigationLaststatusUpdate, cstm.logics_billingstatus_c as lbstatus, concat(users2.first_name, ' ', users2.last_name) as case_assigned, concat(users1.first_name, ' ', users1.last_name) as lead_assigned, investigation_type_c,lm_sub.lead_src, derived_table3.* ");
			
			$this->db_sugar->from('leads l');
			
			$this->db_sugar->join('leads_cstm lc', 'l.id=lc.id_c', 'left');
			
			$this->db_sugar->join('cases_cstm cstm', 'lc.case_id_c=cstm.case_id_c', 'left');
			
			$this->db_sugar->join('cases cs', 'cstm.id_c=cs.id', 'left');
			
			$this->db_sugar->join('users users1', 'users1.id=l.assigned_user_id', 'left');
			
			$this->db_sugar->join('users users2', 'users2.id=cs.assigned_user_id', 'left');

			// $this->db_sugar->join('leads_leadm_leadmarketing_1_c llm', 'llm.leads_leadm_leadmarketing_1leads_ida=l.id AND llm.deleted=0' , 'left'); //added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
			// $this->db_sugar->join('leadm_leadmarketing lm', 'lm.id=llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lm.deleted=0', 'left');//added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
			// $this->db_sugar->join('leadm_leadmarketing_cstm lmc', 'lmc.id_c=llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lmc.attributable_c=1', 'left');//added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
			
			$this->db_sugar->join('(SELECT cases_ths_ths_1_c.cases_ths_ths_1cases_ida,  ths_ths.date_modified as ths_date, ths_record_type, ths_client_type, DATE_FORMAT(ths_report_date_c,"%m/%d/%Y") as ths_report_date_c, location_c 
	FROM ths_ths 
	INNER JOIN ths_ths_cstm ON ths_ths_cstm.id_c = ths_ths.id
	INNER JOIN cases_ths_ths_1_c on cases_ths_ths_1_c.cases_ths_ths_1ths_ths_idb=ths_ths.id
	INNER JOIN cases as c ON c.id=cases_ths_ths_1_c.cases_ths_ths_1cases_ida
	WHERE ths_ths.deleted=0 
	AND cases_ths_ths_1_c.deleted=0 
	AND ths_ths_cstm.location_c= "DMS_SSN" AND ths_record_type = "Individual") as derived_table3',  'derived_table3.cases_ths_ths_1cases_ida=cs.id', 'left');

	$this->db_sugar->join('(SELECT llm.leads_leadm_leadmarketing_1leads_ida AS lead_id, lm.lead_src
    FROM leads_leadm_leadmarketing_1_c llm
    INNER JOIN leadm_leadmarketing lm ON lm.id = llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lm.deleted = 0
    INNER JOIN leadm_leadmarketing_cstm lmc ON lmc.id_c = lm.id AND lmc.attributable_c = 1
    WHERE llm.deleted = 0
    ORDER BY lm.date_modified DESC) as lm_sub',  'lm_sub.lead_id=l.id', 'left');
			
	$this->db_sugar->join('(SELECT llm.leads_leadm_leadmarketing_1leads_ida AS lead_id, lm.lead_src
    FROM leads_leadm_leadmarketing_1_c llm
    INNER JOIN leadm_leadmarketing lm ON lm.id = llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lm.deleted = 0
    INNER JOIN leadm_leadmarketing_cstm lmc ON lmc.id_c = lm.id AND lmc.attributable_c = 1
    WHERE llm.deleted = 0
    ORDER BY lm.date_modified DESC LIMIT 1) as lm_sub',  'lm_sub.lead_id=l.id', 'left');
			
			$this->db_sugar->where('l.deleted', 0);	
			$this->db_sugar->where('cs.deleted', 0);	
			$this->db_sugar->where('cstm.investigation_advocate_statu_c', 'Pricing');
			//$this->db_sugar->where('l.assigned_user_id', $uid);
			//$this->db_sugar->where('cstm.user_id11_c', $uid);	 
			//'22dd1e7c-794c-11e9-a9bf-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
			return 0;				
	}
	
	//check if caseid is present in sugar
	public function check_case_in_sugar($case_id)
	{
		if($case_id != '')
		{
			$this->db_sugar->select("id_c");
			$this->db_sugar->from('leads_cstm');
			$this->db_sugar->where('case_id_c', $case_id);						
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//echo $record ;
			if(is_array($record) && count($record) > 0)
			{
				return 1;
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
	
	//check if opportunity is created for lead
	public function check_opportunity_in_sugar($case_id)
	{ 
		if($case_id != '')
		{
			$this->db_sugar->select("opportunity_id");
			$this->db_sugar->from('leads');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id');
			$this->db_sugar->where('leads_cstm.case_id_c', $case_id);						
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record) ; 
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->opportunity_id != '')
				{
					return 1;
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
	
	//Fetch data from sugartax server
	public function fetch_caseid_data($caseid) 
	{
		$this->db2->select('r.year, r.return_filed, r.filing_status, r.lien_active, r.examination_active, r.assessed_balance , r.accrued_balance, r.taxpayer_spouse, e.last_download_date');
		$this->db2->from('communitytax.entity_resolution_detail as r ');
		$this->db2->join('entity as e', 'e.entity_id = r.entity_id');
		$this->db2->where('e.case_id',$caseid);
		//$this->db2->where('r.taxpayer_spouse','T');
		
		$data = $this->db2->get();
		$record = $data->result();			
		//print_r($record);
		return $record;
	}

	public function fetch_penalty_data($caseid) 
	{
		$this->db2->select('r.fta, r.pa, r.penalties_failure_to_file, r.penalties_failure_to_pay');
		$this->db2->from('communitytax.entity_business_penalty_dashboard as r ');
		$this->db2->join('entity as e', 'e.entity_id = r.entity_id');
		$this->db2->where('e.case_id',$caseid);
		
		$data = $this->db2->get();
		$record = $data->result();			
		//print_r($record);
		return $record;
	}
	
	public function fetch_resolution_data($caseid) 
	{
		$this->db2->select('r.year, r.last_activity, r.last_activity_date');
		$this->db2->from('communitytax.entity_resolution_detail as r ');
		$this->db2->join('entity as e', 'e.entity_id = r.entity_id');
		$this->db2->where('e.case_id',$caseid);
		$this->db2->where('r.taxpayer_spouse','T');
		
		$data = $this->db2->get();
		$record = $data->result();			
		
		return $record;
	}
	
	public function fetch_civil_penalty_data($caseid) 
	{
		//$caseid = 632871;
		$this->db2->select('year, account_balance');
		$this->db2->from('communitytax.entity_civil_penalty_dashboard as r ');
		$this->db2->join('entity as e', 'e.entity_id = r.entity_id');
		$this->db2->where('e.case_id',$caseid);
		$this->db2->where('r.year','Totals');
		$this->db2->where('r.taxpayer_spouse','T');
		
		$data = $this->db2->get();
		$record = $data->result();	
		if(is_array($record) && count($record) > 0)
		{
			$balance = $record[0]->account_balance;
		
			if($balance > 0)
			{
				$this->db2->select('description, year_1, year_2, year_3, year_4, year_5, year_6, year_7, year_8, year_9, year_10, year_11,  year_12, year_13, year_14, year_15');
				$this->db2->from('communitytax.entity_wage_and_income_summary as r ');
				$this->db2->join('entity as e', 'e.entity_id = r.entity_id');
				$this->db2->where('e.case_id',$caseid);
						
				$data2 = $this->db2->get();
				$record2 = $data2->result();
				//print_r($record2);
				return $record2;
			}
			else 
				return 0;
		}
	}

	public function fetch_csed_data($caseid) //$user_type 1 
	{
		if($caseid != '')
		{
			$this->db2->select('c.year, c.assessment_code, c.assessment, c.assessment_date, c.assessment_amount, c.total_tolling_days, c.estimated_csed_date, c.three_year_look_back_date,
			c.two_year_filing_rule_date, c.two_forty_day_assessment_date, c.eligible_bankruptcy_discharge_date ');
			$this->db2->from('communitytax.entity_csed_calculation as c ');
			$this->db2->join('entity as e', 'e.entity_id = c.entity_id');
			$this->db2->where('e.case_id',$caseid);
			$this->db2->where('c.taxpayer_spouse','T');
			
			$data = $this->db2->get();
			$record = $data->result();			
			
			return $record;
		}
		else 
			return 0;
	}
	
	public function fetch_ths_data($caseid)
	{
		if($caseid != '')
		{//account status, r.examination_active c.estimated_csed_date,
	
			/*
			select r.year, r.return_filed, r.filing_status, r.lien_active, r.assessed_balance , 
			r.accrued_balance, r.taxpayer_spouse, e.case_id,
			(
			select estimated_csed_date from entity_csed_calculation
			left join entity on entity_csed_calculation.entity_id = entity.entity_id
			where case_id=e.case_id and year=r.year
			group by year
			)	as csed		
			from entity as e
			left join communitytax.entity_resolution_detail as r on r.entity_id = e.entity_id
			 where e.case_id=1000051
			 order by r.year
			
			
			$this->db2->select('r.year, r.return_filed, r.filing_status, r.lien_active, r.assessed_balance , r.accrued_balance, r.taxpayer_spouse, e.case_id, (
			select estimated_csed_date from entity_csed_calculation
			left join entity on entity_csed_calculation.entity_id = entity.entity_id
			where case_id=e.case_id and year=r.year
			group by year
			)	as csed	, 		
			(
			select entity_business_penalty_dashboard.fta from entity_business_penalty_dashboard
			left join entity on entity_business_penalty_dashboard.entity_id = entity.entity_id
			where case_id=e.case_id and year=r.year
			group by year
			)	as fta,
			(
			SELECT SUM(year_11)  
			FROM entity_wage_and_income_summary as ewis
			WHERE ewis.entity_id = e.entity_id 
			) as wage');
			
			$this->db2->from('entity as e');
			$this->db2->join('communitytax.entity_resolution_detail as r ', 'r.entity_id = e.entity_id');
			
			$this->db2->where('e.case_id',$caseid);
			$this->db2->order_by('r.year DESC');
			
			$data = $this->db2->get();
			$record = $data->result();			
			
			return $record; */

			$sql = "SELECT `r`.`year`, `r`.`return_filed`, `r`.`filing_status`, `r`.`lien_active`, `r`.`assessed_balance`, `r`.`accrued_balance`, `r`.`taxpayer_spouse`, `e`.`case_id`, (
				select estimated_csed_date from entity_csed_calculation
				left join entity on entity_csed_calculation.entity_id = entity.entity_id
				where case_id=e.case_id and year=r.year
				and entity_csed_calculation.taxpayer_spouse = r.taxpayer_spouse
				group by year
				)	as csed, (
				select entity_business_penalty_dashboard.fta from entity_business_penalty_dashboard
				left join entity on entity_business_penalty_dashboard.entity_id = entity.entity_id
				where case_id=e.case_id and year=r.year
				group by year
				)	as fta, (
				SELECT SUM(year_11)  
				FROM entity_wage_and_income_summary as ewis
				WHERE ewis.entity_id = e.entity_id 
				) as wage, (
					SELECT round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)),2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form = 'Form 1099-C Cancellation of Debt'
					AND definition = 'Amount of Debt Canceled:' 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year    
				) as '1099CIncome', (
					SELECT  round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)),2) 
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN entity ON entity.entity_id = entity_wage_and_income_form.entity_id
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form = 'Form 1099-K:  Merchant Card and Third Party Network Transactions'
					AND definition LIKE '%Gross Amount%'
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year    
				) as '1099KIncome', (
					SELECT round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)) ,2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form = 'Form SSA-1099 Benefits Statement'
					AND definition LIKE 'Pensions and Annuities (Total Benefits Paid)%' 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as 'SSAIncome', (
					SELECT  round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)) ,2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form = 'Form 1099-MISC'
					AND (definition = 'Non-Employee Compensation:' OR definition = 'Other Income:')
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as '1099MISCIncome', (
					SELECT round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)),2) 
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form LIKE 'Form 1099-R Distributions from Pensions, Annuities, Retire or  Profit-Sharing Plans, IRAs, Insurance Contracts, etc%'
					AND definition = 'Gross Distribution:' 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as '1099RIncome', (
					SELECT round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)) ,2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form LIKE 'Form 1099-R Distributions from Pensions, Annuities, Retire or  Profit-Sharing Plans, IRAs, Insurance Contracts, etc%'
					AND definition = 'Taxable Amount:' 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as '1099RTaxableIncome', 
				(
					SELECT sum(cast(REPLACE(TRIM('$' FROM value),',','') as DECIMAL)) 
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where form LIKE 'Form 1099-G'
					AND definition = 'Unemployment Compensation:' 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as '1099GIncome',
				(
					SELECT  round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)) ,2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where (form = 'Form W-2 Wage and Tax Statement' OR form = 'Form W-2G')
					AND (definition = 'Wages, Tips and Other Compensation:' OR definition = 'Gross Winnings:') 
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as 'W2Income', (
					SELECT round(sum(cast(REPLACE(TRIM('$' FROM value), ',', '') as DECIMAL)) ,2)
					FROM communitytax.entity_wage_and_income_form 
					LEFT JOIN communitytax.entity_wage_and_income_form_detail ON entity_wage_and_income_form_detail.entity_wage_and_income_form_id=entity_wage_and_income_form.entity_wage_and_income_form_id
					where (form = 'Form W-2 Wage and Tax Statement' 
					OR form = 'Form W-2G' 
					OR form like 'Form 1099-R Distributions from Pensions, Annuities, Retire or  Profit-Sharing Plans, IRAs, Insurance Contracts, etc%' 
					OR form = 'Form 1099-MISC' 
					OR form = 'Form 1099-G'
					OR form = 'Form SSA-1099 Benefits Statement' 
					OR form = 'Form 1099-K:  Merchant Card and Third Party Network Transactions')
					AND (definition = 'Federal Income Tax Withheld:' OR definition = 'Tax Withheld:') 
					and entity_wage_and_income_form.taxpayer_spouse=r.taxpayer_spouse
					AND entity_wage_and_income_form.entity_id=e.entity_id 
					and entity_wage_and_income_form.year  = r.year
					group by entity_wage_and_income_form.year
				) as 'TaxWithheld'
				FROM `entity` as `e`
				JOIN `communitytax`.`entity_resolution_detail` as `r` ON `r`.`entity_id` = `e`.`entity_id`
				WHERE `e`.`case_id` = $caseid
				ORDER BY `r`.`year` DESC";

			//$record = mysqli_query($this->db2, $sql); '646470'
			$record1 = $this->db2->query($sql);
			$record = $record1->result();
			return $record;
		}
		else 
			return 0;
	}
	
	//End of sugartax server data functions
	
	public function fetch_casereview_data($caseid)
	{
		if($caseid != '')
		{			
			$sql = "SELECT * FROM `leads_cstm` WHERE `case_id_c` = $caseid";			
			//print_r($sql);exit;
			$record1 = $this->db_sugar->query($sql);
			$record = $record1->result();
			//print_r($record);exit;
			return $record;
		}
		else 
			return 0;
	}

	public function fetch_lead_campaign($caseid)
	{
		if($caseid != '')
		{	
			$lead_campaign = '';		
			$sql = "SELECT lead_campaign 
					FROM `leadm_leadmarketing` 
					INNER JOIN leads_leadm_leadmarketing_1_c ON leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb = leadm_leadmarketing.id
					INNER JOIN leads_cstm ON leads_cstm.id_c = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida
					INNER JOIN leads ON leads.id = leads_cstm.id_c
					WHERE  leadm_leadmarketing.deleted = 0
					AND leads_leadm_leadmarketing_1_c.deleted = 0
					AND leads.deleted = 0 
					AND (lead_campaign LIKE '%liberty%' OR lead_campaign LIKE '%tpg%' OR lead_campaign LIKE '%eps%' OR lead_campaign LIKE '%ra%')					
					AND leads_cstm.case_id_c = $caseid";	// AND lead_src NOT LIKE '%#%' removed on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners		
			//print_r($sql);exit;
			$record1 = $this->db_sugar->query($sql);
			$record = $record1->result();		
		
			for($i=0; $i<count($record); $i++)
			{
				$lead_campaign = $record[$i]->lead_campaign;	
				if($lead_campaign != '' && $lead_campaign != null)
				{
					break;
				}
			}
			
			return $lead_campaign;
		}
		else 
			return 0;
	}

	public function fetch_lead_source($caseid)
	{
		if($caseid != '')
		{			
			$sql = "SELECT lead_src, lead_campaign 
					FROM `leadm_leadmarketing`
					INNER JOIN leadm_leadmarketing_cstm as lmc ON lmc.id_c=leadm_leadmarketing.id
					INNER JOIN leads_leadm_leadmarketing_1_c ON leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb = leadm_leadmarketing.id
					INNER JOIN leads_cstm ON leads_cstm.id_c = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida
					INNER JOIN leads ON leads.id = leads_cstm.id_c
					WHERE  leadm_leadmarketing.deleted = 0
					AND leads_leadm_leadmarketing_1_c.deleted = 0
					AND leads.deleted = 0 
					AND leads_cstm.case_id_c = $caseid
					AND lmc.attributable_c = '1' ORDER BY leadm_leadmarketing.date_modified DESC LIMIT 1";			
			//print_r($sql);exit;
			$record1 = $this->db_sugar->query($sql);
			$record = $record1->result();
			//print_r($record);exit;
			return $record;
		}
		else 
			return 0;
	}

	//Data from SugarCRM
	public function general_caseid_details($caseid)
	{
		if($caseid != '')		
		{
			//$this->db_sugar->select("marital_status_c, leads.status, leads.id as id, first_name as FirstName, last_name as LastName,  primary_address_state as State, ssn_c, spouse_ssn_c, itin_c, spouseitin_c, cr_fee_c, cr_rec_c, filing_status_state_c, DATE_FORMAT(birthdate,'%m/%d/%Y') as birthdate, DATE_FORMAT(spouse_birthdate_c,'%m/%d/%Y') as spouse_birthdate_c,  final_recommendation_type_c, old_sugar_id_c, add_service_fee_c, language_prequal_c as client_language"); 
			$this->db_sugar->select("marital_status_c, leads.status, leads.id as id, leads.first_name as FirstName, leads.last_name as LastName,  primary_address_state as State, ssn_c, spouse_ssn_c, itin_c, spouseitin_c, cr_fee_c, cr_rec_c,service_partner_fee_c,service_partner_fee_c, filing_status_state_c, DATE_FORMAT(birthdate,'%m/%d/%Y') as birthdate, DATE_FORMAT(spouse_birthdate_c,'%m/%d/%Y') as spouse_birthdate_c, leads_cstm.language_prequal_c as language_prequal_c, concat(users.first_name, ' ', users.last_name) as assigned_to_new_c, final_recommendation_type_c, old_sugar_id_c, add_service_fee_c, concat('$','',round(leads_cstm.cid_fed_debt_amount_c,2)) as cid_fed_debt_amount_c, language_prequal_c as client_language, cases_cstm.cr_template_c,cases_cstm.additional_pps_info_needed_c, leads_cstm.add_service_source_c, round(cases_cstm.reported_income_c,2) as reported_income_c, round(cases_cstm.reported_assets_c,2) as reported_assets_c, leads.contact_id");//cr_template added on 27-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field // Updated on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added // Updated on 23-11-2023 https://trello.com/c/RVmA8RNV/691-tra-anthem-fee-field-name-updates
			//prelim_recommendation_type_c, prelim_fee_c, specified_services_c
			// anthem_fee_c replaced on 11-12-2023 https://trello.com/c/Ga3XDBZC/746-anthem-portal-update-lead-record-anthem-field-deletions // Updated on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp

			$this->db_sugar->from('leads');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			$this->db_sugar->join('users', 'users.id = leads.assigned_user_id', 'left');

			//added on 27-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field
			$this->db_sugar->join('cases_cstm', 'cases_cstm.case_id_c = leads_cstm.case_id_c', 'left'); 
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c AND cases.deleted = 0', 'left');  // Updated on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners
			$this->db_sugar->where('leads.deleted', 0);			
			//$this->db_sugar->where('cases.deleted', 0);	// Commented on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners
			//end added on 26-04-2023 
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);						
			
			//$sql_sugar = "SELECT marital_status_c, leads.id as id, first_name as FirstName, last_name as LastName,  primary_address_state as State, ssn_c, spouse_ssn_c, itin_c, spouseitin_c, cr_fee_c, cr_rec_c, filing_status_state_c, DATE_FORMAT(birthdate,'%m/%d/%Y') as birthdate, DATE_FORMAT(spouse_birthdate_c,'%m/%d/%Y') as spouse_birthdate_c,  final_recommendation_type_c, old_sugar_id_c, add_service_fee_c, language_prequal_c as client_language from leads left join leads_cstm on id_c = id WHERE case_id_c = " . $caseid;

			$data = $this->db_sugar->get(); //$this->db_sugar->query($sql_sugar); //$this->db_sugar->get();
			$record = $data->result();
			
			//print $this->db_sugar->last_query();
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}	
	
	public function getcomplianceTaxPrep($case_id)
	{        
        if(!empty($case_id))
		{           
			$sql_comp="SELECT rtpc.tax_year_c, rtpc.return_outcome_c from reso_tax_preparation_cstm rtpc
			LEFT JOIN reso_tax_preparation rtp ON rtp.id = rtpc.id_c
			LEFT JOIN contacts_reso_tax_preparation_1_c crtp ON crtp.contacts_reso_tax_preparation_1reso_tax_preparation_idb = rtpc.id_c
			LEFT JOIN contacts_cstm ON contacts_cstm.id_c = crtp.contacts_reso_tax_preparation_1contacts_ida
			LEFT JOIN contacts ON contacts.id = contacts_cstm.id_c
			WHERE contacts_cstm.case_id_c='".$case_id."'
			AND contacts.deleted=0
			AND crtp.deleted=0
			AND rtp.deleted=0";

        	// return $sql;
			$data = $this->db_sugar->query($sql_comp);
			
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
        }
		else{
			return 0;
		}
    }

	public function get_other_caseids($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("accounting_case_number_c, monitoring_case_number_c"); 
			
			//prelim_recommendation_type_c, prelim_fee_c, specified_services_c
			
			$this->db_sugar->from('contacts_cstm');
			$this->db_sugar->where('case_id_c', $caseid);						
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}

	public function caseid_aggregate_details($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("aggre_aggregatevalues_cstm.id_c, aggre_aggregatevalues_cstm.prelim_recommendation_type_c, aggre_aggregatevalues_cstm.prelim_fee_c, aggre_aggregatevalues_cstm.specified_services_c"); 
			$this->db_sugar->from('aggre_aggregatevalues_cstm');
			$this->db_sugar->join('aggre_aggregatevalues', 'aggre_aggregatevalues.id = aggre_aggregatevalues_cstm.id_c', 'inner'); 
			$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb = aggre_aggregatevalues_cstm.id_c', 'left'); 
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida', 'left'); 
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);						
			$this->db_sugar->where('aggre_aggregatevalues.deleted',0);						
			$this->db_sugar->where('leads_aggre_aggregatevalues_1_c.deleted',0);
			$this->db_sugar->order_by('date_entered','DESC');						
			$this->db_sugar->limit(1, 0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}
	
	public function check_old_pps_personal($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("count(inves_pps.id) as pps_num");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->pps_num ;
			}
			else 
				return 0;
		}
	}
	
	public function check_old_pps_spouse($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("count(inves_pps.id) as pps_num");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 1);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->pps_num ;
			}
			else 
				return 0;
		}
	}
	
	public function check_old_pps_state($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("count(inves_pps.id) as pps_num");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'State Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->pps_num ;
			}
			else 
				return 0;
		}
	}
	
	public function fetch_general_pps_detail($ppsid)
	{
		if($ppsid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.name, inves_pps_cstm.pps_spouse_c, pps_instructions_c");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('inves_pps.id', $ppsid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record ;
			}
			else 
				return 0;
		}
	}
	
	public function get_old_pps_personal($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.date_entered");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(100,1); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record ;
			}
			else 
				return 0;
		}
	}
	
	public function get_old_pps_spouse($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.date_entered");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 1);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,100); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->pps_num ;
			}
			else 
				return 0;
		}
	}
	
	public function get_old_pps_state($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.date_entered");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->like('inves_pps.name', 'State Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,100); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->pps_num ;
			}
			else 
				return 0;
		}
	}
	
	public function check_pps($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cases_inves_pps_1inves_pps_idb");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	// Added on 10-02-2023
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			file_put_contents('check_pps.txt', $caseid . ' - ' . $this->db_sugar->last_query(). PHP_EOL, FILE_APPEND);
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->cases_inves_pps_1inves_pps_idb ;
			}
			else 
				return 0;
		}
	}
	
	public function check_pps_premium($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cases_inves_pps_1inves_pps_idb");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->like('inves_pps.name', 'Premium');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	// Added on 10-02-2023
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			file_put_contents('check_pps_premium.txt', $caseid . ' - ' . $this->db_sugar->last_query(). PHP_EOL, FILE_APPEND);
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->cases_inves_pps_1inves_pps_idb ;
			}
			else 
				return 0;
		}
	}
	
	public function check_pps_premium_spouse($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cases_inves_pps_1inves_pps_idb");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->like('inves_pps.name', 'Premium');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 1);
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	// Added on 10-02-2023
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			file_put_contents('check_pps_premium_spouse.txt', $caseid . ' - ' . $this->db_sugar->last_query(). PHP_EOL, FILE_APPEND);
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->cases_inves_pps_1inves_pps_idb ;
			}
			else 
				return 0;
		}
	}
	
	/*public function check_all_pps_status($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.pps_status");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('inves_pps.pps_status <> ', 'Completed');
			$this->db_sugar->where('inves_pps.name LIKE ', '%Federal%');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0)
			{
				return 0;
			}
			else 
				return 1;
		}
	}*/
	
	public function check_all_pps_status($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.pps_status");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			//$this->db_sugar->where('inves_pps.name LIKE ', '%Federal%');
			$this->db_sugar->where('inves_pps.pps_status <> ', 'Completed');
			
			$where = "(inves_pps_cstm.pps_requested_by_c <>  'Servicing' OR inves_pps_cstm.pps_requested_by_c IS NULL )";
			
			$this->db_sugar->where($where);
			
			//$this->db_sugar->where('inves_pps_cstm.pps_requested_by_c', 'Servicing');
			
			//$where = "((inves_pps_cstm.pps_requested_by_c = 'Servicing' AND inves_pps.pps_status <> 'Completed') OR (inves_pps_cstm.pps_requested_by_c = 'Servicing' AND inves_pps.pps_status = 'Completed'))";
			
			//$this->db_sugar->where($where);
			
			
			//$this->db_sugar->where('inves_pps_cstm.pps_requested_by_c  ', 'Servicing');
			
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0)
			{
				return 0;
			}
			else 
				return 1;
		}
	}
	
	public function check_pps_service($ppsid)
	{
		if($ppsid != '')		
		{
			$this->db_sugar->select("pps_requested_by_c");
			
			$this->db_sugar->from('inves_pps_cstm');
			
			$this->db_sugar->where('inves_pps_cstm.id_c', $ppsid);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0)
			{
				$logData = '1. PPS Service Check success Id - ' . $ppsid . ", record - " . print_r($record,true).  PHP_EOL;
				$this->createCustomLog($logData,'PPS','check_pps_service');
				return $record[0]->pps_requested_by_c ;
			}
			else 
			{
				$logData = '1. PPS Service Check err Id - ' . $ppsid .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','check_pps_service');
				return 1;
			}
		}
	}
	
	public function check_investigation_type_stage($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("investigation_type_c, investigation_advocate_statu_c");
			
			$this->db_sugar->from('cases_cstm');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases.deleted', 0);
			
			$this->db_sugar->where('investigation_type_c', 'Finance Pal');
			$this->db_sugar->where('investigation_advocate_statu_c', 'PPS Assigned');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0)
			{
				return 1;
			}
			else 
				return 0;
		}
	}
	
	public function check_pps_spouse($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cases_inves_pps_1inves_pps_idb");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);	
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	 // Added on 10-02-2023
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 1);			
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');	
			$this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->cases_inves_pps_1inves_pps_idb ;
			}
			else 
				return 0;
		}
	}
	
	public function check_extra_pps($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("inves_pps.id, inves_pps.name");
			$this->db_sugar->from('inves_pps_cstm');
			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_cstm.id_c', 'left');
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps_cstm.id_c', 'left');
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);	
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('inves_pps.name !=', 'Federal Personal');
			$this->db_sugar->not_like('inves_pps.name', 'Federal Personal');
			//$this->db_sugar->where('inves_pps.name !=', 'State Personal');	
			$this->db_sugar->not_like('inves_pps.name', 'State Personal');	
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
	}
	
	public function check_pps_state_pers($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cases_inves_pps_1inves_pps_idb");
			
			$this->db_sugar->from('cases_inves_pps_1_c');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('inves_pps', 'inves_pps.id = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);	
			$this->db_sugar->where('cases.deleted', 0);	
			$this->db_sugar->where('inves_pps.deleted', 0);	 // Added on 10-02-2023
			//$this->db_sugar->where('inves_pps.name', 'State Personal');	 
			$this->db_sugar->like('inves_pps.name', 'State Personal');	
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');	
			$this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
		
			if(is_array($record) && count($record) > 0){
				return $record[0]->cases_inves_pps_1inves_pps_idb ;
			}
			else 
				return 0;
		}
	}
	
	//Revenue Line Items or Products and Services api call
	public function create_new_service($caseid, $sale_level, $service_type, $service_name, $service_name_id, $desc, $price, $qty, $disc_reason, $disc_amount, $calc_amt, $years_list, $category_id, $commission, $billed_to, $entity, $servicer, $recurring_type, $service_start_date, $rollin, $quarter_list, $flag_list, $state_list, $parent_id)
	{
		//$this->sugar_url = 'https://ctaxstagingupgrade.nablasol.net/';
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		if($caseid != '' && $uid != '')
		{
			$this->db_sugar->select('opportunity_id');
			$this->db_sugar->from('leads');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($data_sugar);
			if(count($record_sugar) > 0)
			{				
				$oppid = $record_sugar[0]->opportunity_id;				
			}
			
			if($oppid != ''){
				
				//$service_name_id_json = str_replace("'",'"',$service_name_id);
				//$service_name_id_arr = json_decode($service_name_id_json); 
				//echo $parent_id;
				if($parent_id == '')
				{
					if($price > 0 && $qty > 0)
					{
						$total = $price * $qty;
					}
					else
					{
						$total = 0;
					}
					$sub_service = 0;
					$package_price = 0;
					$likely_case = $total;
				}
				else
				{
					$total = 0;
					$sub_service = 1; //is_sub_service_c 
					if($price > 0 && $qty > 0)
					{
						$package_price = $price * $qty;
					}
					else
					{
						$package_price = 0;
					}
					$likely_case = 0;
				}
				
				if($desc == 'null')
				{				
					$desc = '';
				}
				if($disc_amount != 'null' && $price > 0)
				{
					$disc_rp = ($disc_amount / $price ) * 100;
					$discount_price = $price;
					$discount_amount = $disc_amount;
					$discount_rate_percent = $disc_rp;
				}
				else
				{
					$discount_price = '';
					$discount_amount = $disc_amount;
					$discount_rate_percent = 0;
				}
				
				if($parent_id != '')
				{
					$discount_rate_percent = 100;
					$discount_amount = $price * $qty;
				}
				if($sale_level == 'null')
				{
					$sale_level = '';
				}
				if($disc_reason == 'null')
				{
					$disc_reason = '';
				}	
				if($commission == 'null' || $commission == '')
				{
					$commission = 0.00;
				}
				if($discount_amount == 'null' || $discount_amount == '')
				{
					$discount_amount = 0;
				}
				if($years_list != '')
				{
					$service_name = $service_name . ' - ' . $years_list;
				}
				
				if($entity == 'Individual')
				{
					$entity = '';
				}
				//, $commission, $billed_to, $entity, $servicer
				/*$record = array(
					'name' => $service_name,
					'modified_user_id' => $uid,
					'created_by' => $uid,
					'description' => $desc,
					'product_template_id' => $service_name_id,
					'total_amount' => $total,
					'category_id' => $category_id, 
					'discount_amount' => $discount_amount,
					'list_price' => $price,
					'quantity' => $qty,
					'opportunity_id' => $oppid,
					'product_catalog_type_c' => $service_type,
					'sale_type_c' => $sale_level,
					'discount_reason_c' => $disc_reason,
					'state_list_c' => '',					
					'years_list_c' => $years_list,
					'override_commission_c' => $commission,
					'lead_from_c' => $servicer,
					'for_entity_c' => $billed_to,
					'recurring_type_c' => $recurring_type,
					'recurring_date_c' => $service_start_date,
					'res_reset_c' => $rollin,
					'period_quarter_c' => $quarter_list,
					'resservice_flag_c' => $flag_list,
					'epi_business_revenuelineitems_1epi_business_ida'=> $entity
				);*/
				
				$record = array(
					'name' => $service_name,
					'modified_user_id' => $uid,
					'created_by' => $uid,
					'description' => $desc,
					'product_template_id' => $service_name_id,
					'total_amount' => $total,
					'category_id' => $category_id, 
					'discount_amount' => $discount_amount,
					'list_price' => $price,
					'quantity' => $qty,
					'opportunity_id' => $oppid,
					'product_catalog_type_c' => $service_type,
					'sale_type_c' => $sale_level,
					'discount_reason_c' => $disc_reason,	
					'years_list_c' => $years_list, 
					'state_list_c' => $state_list,
					'override_commission_c' => $commission,
					'lead_from_c' => $servicer,
					'for_entity_c' => $billed_to,
					'recurring_type_c' => $recurring_type,
					'recurring_date_c' => $service_start_date,
					'res_reset_c' => $rollin,
					'period_quarter_c' => $quarter_list,
					'resservice_flag_c' => $flag_list,
					'service_parent_id_c' => $parent_id,
					'epi_business_revenuelineitems_1epi_business_ida' => $entity,
					'status' => "Pending",
					'case_id_c' => $caseid,
					'is_sub_service_c' => $sub_service,
					'promo_amount_c' => 0.00,
					'package_service_price_c' => $package_price,
					'likely_case' => $likely_case,
					'discount_rate_percent' => $discount_rate_percent
				);
//print_r($record);
				if(!is_array($record) || count($record) <= 0)
				{
					$error = 1;
				}				
			}
			else
			{
				$error = 1;
				return 0;
			}
		}
		else
		{
			$error = 1;
			return 0;
		}
		
		if($user_name != '' && $user_pwd != '' && $error < 1)
		{		
			
			$instance_url = $this->sugar_url . "rest/v11";  
			//file_put_contents('url.txt',$instance_url);
			//Login - POST /oauth2/token
			$auth_url = $instance_url . "/oauth2/token";

			$oauth2_token_arguments = array(
			
				"grant_type" => "password",
				//client id - default is sugar. 
				//It is recommended to create your own in Admin > OAuth Keys
				"client_id" => "sugar", 
				"client_secret" => "", 
				"username" => $user_name,
				"password" => $user_pwd,
				//platform type - default is base.
				//It is recommend to change the platform to a custom name such as "custom_api" to avoid authentication conflicts.
				"platform" => "mobile" 
			);
			
			//file_put_contents('oauth2_token_arguments.txt',print_r($oauth2_token_arguments));
			
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
			
			//file_put_contents('oauth2_token_response.txt',$oauth2_token_response); 
			
			//decode oauth2 response to get token
			$oauth2_token_response_obj = json_decode($oauth2_token_response);
			$oauth_token = $oauth2_token_response_obj->access_token;
						
			//Create Records - POST /<module>
			$url = $instance_url . "/RevenueLineItems";
			//Set up the Record details
			
			$curl_request = curl_init($url);
			curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($curl_request, CURLOPT_HEADER, false);
			curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"oauth-token: {$oauth_token}"
			));

			$json_arguments = json_encode($record);
			curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
			
			$curl_response = curl_exec($curl_request);
			file_put_contents('service_res_json1june.txt',$curl_response); 
			//print $curl_response;
			$createdRecord = json_decode($curl_response);
			curl_close($curl_request);	
			
			
					
			//print_r($createdRecord); 
			//is_array($createdRecord) && count($createdRecord) > 0 && 
			if($createdRecord->id != '')
			{
				//file_put_contents('service_res_array11.txt',$createdRecord->id);

				if($parent_id != '')
				{
					$this->updateParentServicePrice($parent_id);
				}
				return $createdRecord->id;
			}
			/*else if($curl_response != '')
			{
				//return 0; 
				$curl_response_arr = explode(',',$curl_response);	
				//file_put_contents('curl_response_arr.txt',print_r($curl_response_arr,true));
				$rec_id_json = $curl_response_arr[0];
				$rec_id_arr = explode(':',$rec_id_json);
				//file_put_contents('rec_id_arr.txt',print_r($rec_id_arr,true));
				$id1 = $rec_id_arr[1];
				
				if($id1 != '')
				{
					$id1 = ltrim($id1,'"');
					$id1 = rtrim($id1,'"');
					return $id1;
				}
				else
				{
					return 0; 
				}
			}*/
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
	
	public function totalParentServicePrice($parent_id)
	{		
		$error = 0;
		$initial_price  = 0;
		$updated_price = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname'); 
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		$amt_array = array();

		if($parent_id != '')
		{
			$this->db_sugar->select('list_price, quantity');
			$this->db_sugar->from('revenue_line_items');	
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');						
			$this->db_sugar->where('service_parent_id_c', $parent_id);	
			$this->db_sugar->where('deleted', 0);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();

			//print_r($record_sugar);
			if(count($record_sugar) > 0)
			{
				for($i=0; $i<count($record_sugar); $i++)
				{
					$updated_price = $updated_price + ($record_sugar[$i]->list_price * $record_sugar[$i]->quantity);
				}
				
				$amt_array['price'] = $updated_price;
				return $amt_array;
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

	public function updateParentServicePrice($parent_id)
	{  
		$error = 0;
		$initial_price  = 0;
		$updated_price = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname'); 
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();

		if($parent_id != '')
		{
			$this->db_sugar->select('list_price, quantity');
			$this->db_sugar->from('revenue_line_items');	
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');						
			$this->db_sugar->where('service_parent_id_c', $parent_id);	
			$this->db_sugar->where('deleted', 0);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($record_sugar);
			if(count($record_sugar) > 0)
			{
				for($i=0; $i<count($record_sugar); $i++)
				{
					$updated_price = $updated_price + ($record_sugar[$i]->list_price * $record_sugar[$i]->quantity);
				}								
			}

			//$updated_price = $initial_price + $price;

			$record = array(
				'modified_user_id' => $uid,				
				'list_price' => $updated_price
			);

			if($user_name != '' && $user_pwd != '')
			{
				$instance_url = $this->sugar_url . "rest/v11"; 
				
				//Login - POST /oauth2/token
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
				$oauth_token = $oauth2_token_response_obj->access_token;
				
				//Create Records - PUT /<module>/id
				if($oauth_token != '')
				{
					$url = $instance_url . "/RevenueLineItems/" . $parent_id;
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
								
					return true;
				}
			}
		}
	}

	public function update_services($rid, $sale_level, $service_type, $service_name, $service_name_id, $desc, $price, $qty, $disc_reason, $disc_amount, $calc_amt, $years_list, $category_id, $commission, $billed_to, $entity, $servicer,  $recurring_type, $service_start_date, $rollin, $quarter_list, $flag_list, $state_list)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		if($rid != '' && $uid != '')
		{
			//$service_name_id_json = str_replace("'",'"',$service_name_id);
			//$service_name_id_arr = json_decode($service_name_id_json); 
			
			//$total = $price * $qty;
			if($price > 0 && $qty > 0)
			{
				$total = $price * $qty;
			}
			else
			{
				$total = 0;
			}
			
			if($desc == 'null')
			{				
				$desc = '';
			}
			if($disc_amount != 'null' && $price > 0)
			{				
				$disc_rp = ($disc_amount / $price ) * 100;
				$discount_price = $price;
				$discount_amount = $disc_amount;
				$discount_rate_percent = $disc_rp;
			}
			else
			{
				$discount_price = '';
				$discount_amount = $disc_amount;
				$discount_rate_percent = '';
			}
			
			if($sale_level == 'null')
			{
				$sale_level = '';
			}
			if($disc_reason == 'null')
			{
				$disc_reason = '';
			}
				
			/*if($entity == 'Individual')
			{
				$entity = '';
			}
			else
			{
				
			}*/
			
			$check_biz = $this->checkRLIBusiness($rid,$entity);
			//print $check_biz .', '. $entity;
			
			if($check_biz == 1 && $entity != "Individual")
			{ //print ' if  ';
				$record = array(
					'name' => $service_name,
					'modified_user_id' => $uid,
					'description' => $desc,
					'product_template_id' => $service_name_id, //$service_name_id_arr->id,
					'total_amount' => $total,
					'category_id' => $category_id, //$service_name_id_arr->cat,
					//'discount_price' => $discount_price,
					'discount_amount' => $discount_amount,
					//'discount_rate_percent' => $discount_rate_percent,
					'list_price' => $price,
					'quantity' => $qty,
					//'opportunity_id' => $oppid,
					'product_catalog_type_c' => $service_type,
					'sale_type_c' => $sale_level,
					'discount_reason_c' => $disc_reason,
					//'user_id1_c' => $approved_by,
					//'user_id2_c' => $sold_by,
					'state_list_c' => $state_list,					
					'years_list_c' => $years_list,
					'override_commission_c' => $commission,
					'lead_from_c' => $servicer,
					'for_entity_c' => $billed_to,
					'recurring_type_c' => $recurring_type,
					'recurring_date_c' => $service_start_date,
					'res_reset_c' => $rollin,
					'period_quarter_c' => $quarter_list,
					'resservice_flag_c' => $flag_list,
					//'package_service_price_c' => $package_price,
					//'likely_case' => $likely_case,
					//'discount_rate_percent' => $discount_rate_percent,
					'epi_business_revenuelineitems_1epi_business_ida'=> $entity
				);
			}
			else
			{
				//print ' else  ';
				$record = array(
					'name' => $service_name,
					'modified_user_id' => $uid,
					'description' => $desc,
					'product_template_id' => $service_name_id, //$service_name_id_arr->id,
					'total_amount' => $total,
					'category_id' => $category_id, //$service_name_id_arr->cat,
					//'discount_price' => $discount_price,
					'discount_amount' => $discount_amount,
					//'discount_rate_percent' => $discount_rate_percent,
					'list_price' => $price,
					'quantity' => $qty,
					//'opportunity_id' => $oppid,
					'product_catalog_type_c' => $service_type,
					'sale_type_c' => $sale_level,
					'discount_reason_c' => $disc_reason,
					//'user_id1_c' => $approved_by,
					//'user_id2_c' => $sold_by,
					'state_list_c' => $state_list,					
					'years_list_c' => $years_list,
					'override_commission_c' => $commission,
					'lead_from_c' => $servicer,
					'for_entity_c' => $billed_to,
					'recurring_type_c' => $recurring_type,
					'recurring_date_c' => $service_start_date,
					'res_reset_c' => $rollin,
					'period_quarter_c' => $quarter_list,
					'resservice_flag_c' => $flag_list,
				);
			}
			//die();
			
			if(!is_array($record) || count($record) <= 0)
			{
				$error = 1;
			}			
		}
		else
		{
			$error = 1;
			return 0;
		}
		
		if($user_name != '' && $user_pwd != '' && $error < 1)
		{
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/RevenueLineItems/" . $rid;
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
							
				return true;
				/*if($updatedRecord->id != ''){
					return true;
				}
				else
					return false;*/
			}
			else
				return false;
			
		}
		
	}
	
	public function updateChildServices($rid, $service_type, $service_name, $service_name_id, $desc, $price, $qty,$years_list, $category_id, $rollin, $quarter_list, $flag_list, $state_list, $parent_id)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		if($rid != '' && $uid != '')
		{			
			if($price > 0 && $qty > 0)
			{
				$total = $price * $qty;
			}
			else
			{
				$total = 0;
			}
			
			if($desc == 'null')
			{				
				$desc = '';
			}
			
			$record = array(
				'name' => $service_name,
				'modified_user_id' => $uid,
				'description' => $desc,
				'product_template_id' => $service_name_id, 
				'total_amount' => $total,
				'category_id' => $category_id, 
				'discount_amount' => $total,
				'list_price' => $price,
				'quantity' => $qty,
				'product_catalog_type_c' => $service_type,
				'state_list_c' => $state_list,				
				'years_list_c' => $years_list,
				'res_reset_c' => $rollin,
				'period_quarter_c' => $quarter_list,
				'resservice_flag_c' => $flag_list,
			);
			
			//die();
			
			if(!is_array($record) || count($record) <= 0)
			{
				$error = 1;
			}			
		}
		else
		{
			$error = 1;
			return 0;
		}
		
		if($user_name != '' && $user_pwd != '' && $error < 1)
		{
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/RevenueLineItems/" . $rid;
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
				
				if($parent_id != '')
				{
					$this->updateParentServicePrice($parent_id);
				}

				return true;
				/*if($updatedRecord->id != ''){
					return true;
				}
				else
					return false;*/
			}
			else
				return false;
			
		}
	}

	public function checkRLIBusiness($rid,$entity)
	{
		if($rid != '' && $entity != '')
		{
			$this->db_sugar->select(" epi_business_revenuelineitems_1epi_business_ida as revenue_biz_id");
			
			$this->db_sugar->from('epi_business_revenuelineitems_1_c');
			 
			$this->db_sugar->where('epi_business_revenuelineitems_1revenuelineitems_idb',$rid );
			
			$this->db_sugar->where('epi_business_revenuelineitems_1_c.deleted', 0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				$biz_id = $record[0]->revenue_biz_id;
				
				if($biz_id == $entity)
				{
					return 0;
				}
				else
				{ //print 'CHECK ELSE ';
					//either new is new biz or new is individual
					//delete old related biz record
					
					$sql_sugar = "UPDATE epi_business_revenuelineitems_1_c set deleted=1";
					$sql_sugar .= " WHERE epi_business_revenuelineitems_1revenuelineitems_idb='$rid' ";	
					
					//print $sql_sugar;
				//die();
					$query_sugar = $this->db_sugar->query($sql_sugar);	
					
					return 1;
				}
			}
			else if($entity == 'Individual')
			{
				return 0;
			}
			else
				return 1;
		}
		else
			return 0;
	}
	
	public function save_cr_aggregate_values($case_id, $services) //, $fee
	{
		$logData = '1. Agg Value Update On Status Update Start CaseID - ' . $case_id . ", services - " . $services . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','save_cr_aggregate_values');
		
		if($case_id != '')
		{					
			if($services != '')
			{
				$sql_sugar = "UPDATE leads_cstm set ";	
				$sql_sugar .= " cr_services_c='$services' ";
				$sql_sugar .= " WHERE case_id_c=$case_id ";	
				
				//print $sql_sugar;
			
				$query_sugar = $this->db_sugar->query($sql_sugar);
				
				if($query_sugar)
				{
					$logData = '2. Agg Value Update On Status Update Success CaseID - ' . $case_id . ", services - " . $services . " , sql_sugar - " . $sql_sugar . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_cr_aggregate_values');
					return true ; 
				}
				else
				{				
					$err =  $this->db_sugar->error();
					$logData = '2. Agg Value Update On Status Update Error CaseID - ' . $case_id . ", services - " . $services . " , sql_sugar - " . $sql_sugar . ", err - " . $err. PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_cr_aggregate_values');
					//print_r($err);
					return false;
				}			
			}
			/*if($fee > 0)
			{
				$sql_sugar .= "  cr_fee_c=$fee ";
			}
			else
			{
				$sql_sugar .= "  cr_fee_c=0 ";
			}*/
		}
		else
		{
			$logData = '1. Agg Value Update On Status Update Missing Data CaseID - ' . $case_id . ", services - " . $services . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','save_cr_aggregate_values');
			return false;
		}
	}
	
	public function save_case_intake_detail_federal($case_id, $deadline_reason, $deadline_date, $ro_name, $ro_phone, $ro_fax)
	{
		if($case_id != '' && ($deadline_reason !='' || $deadline_date !='' || $ro_name !='' ||  $ro_phone !='' ||  $ro_fax !=''))
		{
			$sql_sugar = "UPDATE leads_cstm set ";	
			
			if($deadline_reason != '')
			{
				$sql_sugar .= " cid_fed_deadline_reason_c='$deadline_reason',";
			}
			if($deadline_date != '')
			{
				$old_date = date($deadline_date); // returns Saturday, January 30 10 02:06:34
				$old_date_timestamp = strtotime($old_date);
				$new_date = date('Y-m-d', $old_date_timestamp); 
			
				$sql_sugar .= " cid_fed_deadline_date_c='$new_date',";
			}
			/*if($ro != '')
			{
				$sql_sugar .= " cid_fed_ro_c='$ro',";
			}*/
			if($ro_name != '')
			{
				$sql_sugar .= " cid_fed_ro_name_c='$ro_name',";
			}
			if($ro_phone != '')
			{
				$sql_sugar .= " cid_fed_ro_contact_c='$ro_phone',";
			}
			if($ro_fax != '')
			{
				$sql_sugar .= " cid_fed_ro_fax_c='$ro_fax',";
			}
			
			$sql_sugar = rtrim($sql_sugar,',');
			
			$sql_sugar .= " WHERE case_id_c=$case_id ";	
			
			
			
			//print $sql_sugar;
		
			$query_sugar = $this->db_sugar->query($sql_sugar);
			
			if($query_sugar)
			{
				return true ; 
			}
			else
			{				
				$err =  $this->db_sugar->error();
				print_r($err);
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function save_case_intake_detail_state($case_id, $deadline_reason, $deadline_date, $ro_name, $ro_phone, $ro_fax)
	{
		if($case_id != '' && ($deadline_reason !='' || $deadline_date !='' || $ro_name !='' ||  $ro_phone !='' ||  $ro_fax !=''))
		{
			$sql_sugar = "UPDATE leads_cstm set ";	
			
			if($deadline_reason != '')
			{
				$sql_sugar .= " cid_state_deadline_reason_c='$deadline_reason',";
			}
			if($deadline_date != '')
			{
				$old_date = date($deadline_date); // returns Saturday, January 30 10 02:06:34
				$old_date_timestamp = strtotime($old_date);
				$new_date = date('Y-m-d', $old_date_timestamp); 
			
				$sql_sugar .= " personal_state_deadline_date_c='$new_date',";
			}
			/*if($ro != '')
			{
				$sql_sugar .= " cid_state_ro_c='$ro',";
			}*/
			if($ro_name != '')
			{
				$sql_sugar .= " state_revenue_officer_name_c='$ro_name',";
			}
			if($ro_phone != '')
			{
				$sql_sugar .= " cid_state_ro_phone_c='$ro_phone',";
			}
			if($ro_fax != '')
			{
				$sql_sugar .= " cid_state_ro_fax_c='$ro_fax',";
			}
			
			$sql_sugar = rtrim($sql_sugar,',');
			
			$sql_sugar .= " WHERE case_id_c=$case_id ";	
			
			
			
			//print $sql_sugar;
		
			$query_sugar = $this->db_sugar->query($sql_sugar);
			
			if($query_sugar)
			{
				return true ; 
			}
			else
			{				
				$err =  $this->db_sugar->error();
				print_r($err);
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function check_isvalidated()
	{
		if(! $this->session->userdata('loged_in')){
			redirect('login');
			//echo "not";
		}
		else
		{
			//echo "Loged in";
		}
	
	}	
	
	public function fetch_reported_income($caseid)
	{
		if($caseid != '')
		{			
			$record = array();
			
			$this->db_sugar->select(" round(gross_monthly_income,2) as rep_inc");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');			
					
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			//$where = "(ppsd_ppsdetail.tax_period = 2018 OR ppsd_ppsdetail.tax_period = 2019)"; // Commented on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$where = "(ppsd_ppsdetail.tax_period = 2021)"; // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			//$this->db_sugar->where('ppsd_ppsdetail.tax_period', 2019);
			$this->db_sugar->where($where);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			$this->db_sugar->order_by('ppsd_ppsdetail.tax_period','DESC');
			$this->db_sugar->limit(1,0);
			
			
			$data = $this->db_sugar->get();
			$record1 = $data->result();
			
			//print_r($record);
			if(count($record1) > 0){
				$record[0]['rep_inc'] = $record1[0]->rep_inc;
			}
			
			//Spouse
			$this->db_sugar->select(" round(gross_monthly_income,2) as rep_inc_spouse");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.tax_period', 2021);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',1);
			$this->db_sugar->order_by('ppsd_ppsdetail.tax_period','DESC');
			$this->db_sugar->limit(1,0);
			
			
			$data2 = $this->db_sugar->get();
			$record2 = $data2->result();
			
			//print_r($record);
			if(count($record2) > 0){
				$record[0]['rep_inc_spouse'] = $record2[0]->rep_inc_spouse;
			}
			
			if(count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
			if(count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;
	}	
	
	public function addExtraExpenseBean($exp_json, $linkid, $caseid)
	{
		$extra_exp_array = json_decode($exp_json);	
		$user_id = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		//print 'linkid - ' .$linkid;
		//print_r($extra_exp_array);
		
		if(count($extra_exp_array) > 0)
		{					
			foreach($extra_exp_array as $key=>$value)
			{							
				if($value->text != '')
				{					
					$record = array(
						'name' => $value->text,
						//'name' => htmlspecialchars(utf8_encode($value->text), ENT_QUOTES),
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'amount_c' => $value->val,
						'logics_case_id_c' => $caseid,						
						'fin_overview_ame_additional_monthly_expen_1fin_overview_ida'=> $linkid
					);
				
					if($user_name != '' && $user_pwd != '')
					{						
						$instance_url = $this->sugar_url . "rest/v11";  
						
						//Login - POST /oauth2/token
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
						$oauth_token = $oauth2_token_response_obj->access_token;
									
						//Create Records - POST /<module>
						$url = $instance_url . "/ame_Additional_Monthly_Expen";
						//Set up the Record details
						
						$curl_request = curl_init($url);
						curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
						curl_setopt($curl_request, CURLOPT_HEADER, false);
						curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
						curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json",
							"oauth-token: {$oauth_token}"
						));

						$json_arguments = json_encode($record);
						curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
						
						$curl_response = curl_exec($curl_request);
						
						$txt = $caseid . PHP_EOL . $curl_response . PHP_EOL . PHP_EOL;
						file_put_contents('AddExpense.txt', $txt , FILE_APPEND);
						
						$createdRecord = json_decode($curl_response);
						curl_close($curl_request);			
						
					}
			
				}
			}
		}
	}
	
	public function save_expense_details($caseid, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id, $extra_exp_json)	
	{		
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$sugar_id = '';		
		
		if($caseid != '')
		{			
			$this->db_sugar->select('fin_overview_leads_1fin_overview_ida');
			$this->db_sugar->from('fin_overview_leads_1_c');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($data_sugar);
			if(count($record_sugar) > 0)
			{				
				$sugar_id = $record_sugar[0]->fin_overview_leads_1fin_overview_ida;				
			}
			if($sugar_id != '')
			{
				//print $sugar_id;	
				$sql_sugar = "UPDATE fin_overview set date_modified=now(), modified_user_id = '" . $user_id  . "'";
					
					
				if($under65 != '')
				{
					$sql_sugar .= " , adults_under_65=$under65 ";
				}
				if($over65 != '')
				{
					$sql_sugar .= " , adults_over_65=$over65 ";
				}
				if($rent_pay != '')
				{
					$rent_pay = str_replace( ',', '', $rent_pay );
					$sql_sugar .= " , rent_payment=" . floatval($rent_pay) ;
				}
				if($prescp_copay != '')
				{
					$prescp_copay = str_replace( ',', '', $prescp_copay );
					$sql_sugar .= " , prescriptions_copays=" . floatval($prescp_copay);
				}
				if($health_ins != '')
				{
					$health_ins = str_replace( ',', '', $health_ins );
					$sql_sugar .= " , health_insurance=" . floatval($health_ins);
				}	
				if($total_health > 0)
				{
					$total_health = str_replace( ',', '', $total_health );
					$sql_sugar .= " , healthcare_total=" . floatval($total_health);
				}				
				
				$sql_sugar .= " WHERE id='$sugar_id' ";	

				//print $sql_sugar;
				
				$query_sugar = $this->db_sugar->query($sql_sugar);
				
				if($query_sugar)
				{
					//return $query ; 
				}
				else{
					
					$err =  $this->db_sugar->error();
					print_r($err);
				}
				
				//update public transportation if not empty
				if($public_tc != '' || $public_ts != '')
				{
					$sql_sugar_c = "UPDATE fin_overview_cstm set ";					
					
					if($public_tc != '')
					{
						$public_tc = str_replace( ',', '', $public_tc );
						$sql_sugar_c .= " public_transportation_client_c=" . floatval($public_tc) . ",";
					}
					if($public_ts != '')
					{
						$public_ts = str_replace( ',', '', $public_ts );
						$sql_sugar_c .= " public_transportation_spouse_c=" . floatval($public_ts) . ",";
					}					 
					if($utilities != '')
					{
						$utilities = str_replace( ',', '', $utilities );
						$sql_sugar_c .= "  utilities_payment_c=" . floatval($utilities) . ",";
					}
					if($pic != '')
					{
						$pic = str_replace( ',', '', $pic );
						$sql_sugar_c .= "  telephone_payment_c=" . floatval($pic) . ",";
					} 
					
					$sql_sugar_c = rtrim($sql_sugar_c,',');
					//print $sql_sugar_c; 
					$sql_sugar_c .= " WHERE id_c='$sugar_id' ";	

					
					
					$query_sugar_c = $this->db_sugar->query($sql_sugar_c);
					
					if($query_sugar_c)
					{
						//return $query ; 
					}
					else{
						
						$err =  $this->db_sugar->error();
						print_r($err);
					}
				}
				
				//update auto ownership in vehicles auto_ownership1				
				if($auto_ownership1_id != '' && $auto_ownership1 > 0)
				{
					$auto_ownership1 = str_replace( ',', '', $auto_ownership1 );
					$auto_ownership1 = floatval($auto_ownership1);
					
					$sql_sugar_v1 = "UPDATE ctr_vehicles set monthly_payment = $auto_ownership1 WHERE id='$auto_ownership1_id'";
					//print $sql_sugar_v1;
					$query_sugar_v1 = $this->db_sugar->query($sql_sugar_v1);
					
					if(!$query_sugar_v1)
					{
						$err =  $this->db_sugar->error();
						print_r($err);
					}
				}
				if($auto_ownership2_id != '' && $auto_ownership2 > 0)
				{
					$auto_ownership2 = str_replace( ',', '', $auto_ownership2 );
					$auto_ownership2 = floatval($auto_ownership2);
					
					$sql_sugar_v2 = "UPDATE ctr_vehicles set monthly_payment = $auto_ownership2 WHERE id='$auto_ownership2_id'";
					//print $sql_sugar_v2;
					$query_sugar_v2 = $this->db_sugar->query($sql_sugar_v2);
					
					if(!$query_sugar_v2)
					{
						$err =  $this->db_sugar->error();
						print_r($err);
					} 
				}
				
				//Add new additional expense 
				if($extra_exp_json != 'null' && $extra_exp_json != '')
				{
					$this->addExtraExpenseBean($extra_exp_json, $sugar_id, $caseid);
				}
				
				//print $other_exp_json;
				if($other_exp_json != 'null'){
			
					$other_exp_arr  = json_decode($other_exp_json);			
					//print_r($other_exp_arr);
					if(count($other_exp_arr) > 0){		
				
						foreach($other_exp_arr as $key=>$record){
							
							if($record->id != '' && $record->text != ''){
								
								$name_text = htmlspecialchars(utf8_encode($record->text), ENT_QUOTES);
								$key = $record->id;
								$amt = $record->value;
								
								$sql_sugar2_main = "UPDATE ame_additional_monthly_expen set name='$name_text', date_modified=now(), modified_user_id='$user_id' WHERE id='$key'" ;
																
								//print $sql_sugar2_main;
								$query_sugar2_main = $this->db_sugar->query($sql_sugar2_main);
								
								if($amt == '')
								{
									$amt = 0;
								}
								if($amt >= 0)
								{
									$amt = str_replace( ',', '', $amt );
									$amt = floatval($amt);
								}
								$sql_sugar2 = "UPDATE ame_additional_monthly_expen_cstm set ";
								$sql_sugar2 .= " amount_c=" .$amt ." WHERE id_c='".$key."'";
								
								//print $sql_sugar2;
								$query_sugar2 = $this->db_sugar->query($sql_sugar2);
								
								if($query_sugar2)
								{
									//return true ; 
								}
								else{
									
									$err =  $this->db_sugar->error();
									print_r($err);
								}			
							}
							//print $key.' '.$val;
						}
						return true ; 
					}			
				}
				else
					return true;
			}
			else{
				//print 'no sugar id';
				return -1;
			}			
		}
		else 
			return 0;
	}
	
	public function save_expense_details_acc($caseid, $under65, $over65, $rent_pay, $prescp_copay, $health_ins, $total_health, $other_exp_json, $public_tc, $public_ts, $utilities, $pic, $auto_ownership1, $auto_ownership2, $auto_ownership1_id, $auto_ownership2_id)	
	{		
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$sugar_id = '';		
		
		if($caseid != '')
		{			
			$this->db_sugar->select('fin_overview_leads_1fin_overview_ida');
			$this->db_sugar->from('fin_overview_leads_1_c');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($data_sugar);
			if(count($record_sugar) > 0)
			{				
				$sugar_id = $record_sugar[0]->fin_overview_leads_1fin_overview_ida;				
			}
			if($sugar_id != '')
			{
				//print $sugar_id;	
				//$sql_sugar = "UPDATE fin_overview set ";
				$sql_sugar = "UPDATE fin_overview set date_modified=now(), modified_user_id = '" . $user_id  . "'";	
					
				if($under65 != ''){
					$sql_sugar .= " , adults_under_65=$under65 ";
				}
				if($over65 != ''){
					$sql_sugar .= " , adults_over_65=$over65 ";
				}
				if($rent_pay != '')
				{
					$rent_pay = str_replace( ',', '', $rent_pay );
					$sql_sugar .= " , rent_payment=" . floatval($rent_pay);
				}
				if($prescp_copay != '')
				{
					$prescp_copay = str_replace( ',', '', $prescp_copay );
					$sql_sugar .= " , prescriptions_copays=" . floatval($prescp_copay);
				}
				if($health_ins != '')
				{
					$health_ins = str_replace( ',', '', $health_ins );
					$sql_sugar .= " , health_insurance=" . floatval($health_ins) ;
				}	
				if($total_health > 0)
				{
					$total_health = str_replace( ',', '', $total_health );
					$sql_sugar .= " , healthcare_total=" . floatval($total_health);
				}				
				
				$sql_sugar .= " WHERE id='$sugar_id' ";	

				//print $sql_sugar;
				
				$query_sugar = $this->db_sugar->query($sql_sugar);
				
				if($query_sugar)
				{
					//return $query ; 
				}
				else{
					
					$err =  $this->db_sugar->error();
					print_r($err);
				}
				
				//update public transportation if not empty
				if($public_tc != '' || $public_ts != '')
				{
					$sql_sugar_c = "UPDATE fin_overview_cstm set ";					
					
					if($public_tc != '')
					{
						$public_tc = str_replace( ',', '', $public_tc );
						$sql_sugar_c .= " public_transportation_client_c=" . floatval($public_tc) . ",";
					}
					if($public_ts != '')
					{
						$public_ts = str_replace( ',', '', $public_ts );
						$sql_sugar_c .= " public_transportation_spouse_c=" . floatval($public_ts) . ",";
					}					 
					if($utilities != '')
					{
						$utilities = str_replace( ',', '', $utilities );
						$sql_sugar_c .= "  utilities_payment_c=" . floatval($utilities) . ",";
					}
					if($pic != '')
					{
						$pic = str_replace( ',', '', $pic );
						$sql_sugar_c .= "  telephone_payment_c=" . floatval($pic) . ",";
					} 
					
					$sql_sugar_c = rtrim($sql_sugar_c,',');
					//print $sql_sugar_c; 
					$sql_sugar_c .= " WHERE id_c='$sugar_id' ";	

					
					
					$query_sugar_c = $this->db_sugar->query($sql_sugar_c);
					
					if($query_sugar_c)
					{
						//return $query ; 
					}
					else{
						
						$err =  $this->db_sugar->error();
						print_r($err);
					}
				}
				
				//update auto ownership in vehicles auto_ownership1				
				if($auto_ownership1_id != '' && $auto_ownership1 > 0)
				{
					$auto_ownership1 = str_replace( ',', '', $auto_ownership1 );
					$auto_ownership1 = floatval($auto_ownership1);
					
					$sql_sugar_v1 = "UPDATE ctr_vehicles set monthly_payment = $auto_ownership1 WHERE id='$auto_ownership1_id'";
					//print $sql_sugar_v1;
					$query_sugar_c = $this->db_sugar->query($sql_sugar_v1);
					
					if(!$query_sugar_c)
					{
						$err =  $this->db_sugar->error();
						print_r($err);
					}
				}
				if($auto_ownership2_id != '' && $auto_ownership2 > 0)
				{
					$auto_ownership2 = str_replace( ',', '', $auto_ownership2 );
					$auto_ownership2 = floatval($auto_ownership2);
					
					$sql_sugar_v2 = "UPDATE ctr_vehicles set monthly_payment = $auto_ownership2 WHERE id='$auto_ownership2_id'";
					//print $sql_sugar_v2;
					$query_sugar_c = $this->db_sugar->query($sql_sugar_v2);
					
					if(!$query_sugar_c)
					{
						$err =  $this->db_sugar->error();
						print_r($err);
					} 
				}
				
				
				//print $other_exp_json;
				if($other_exp_json != 'null'){
			
					$other_exp_arr  = json_decode($other_exp_json);			
					//print_r($other_exp_arr);
					if(count($other_exp_arr) > 0){		
				
						foreach($other_exp_arr as $key=>$val){
							
							if($key != '' && $val != ''){
								
								$val = str_replace( ',', '', $val );
								$val = floatval($val);
								
								$sql_sugar2 = "UPDATE ame_additional_monthly_expen_cstm set ";
								$sql_sugar2 .= " amount_c=$val WHERE id_c='".$key."'";
								$query_sugar2 = $this->db_sugar->query($sql_sugar2);
								//print $query_sugar2;
								if($query_sugar2)
								{
									//return true ; 
								}
								else{
									
									$err =  $this->db_sugar->error();
									print_r($err);
								}				
							}
							//print $key.' '.$val;
						}
						return true ; 
					}			
				}
				else
					return true;
			}
			else{
				//print 'no sugar id';
				return -1;
			}			
		}
		else 
			return 0;
	}
	
	public function statusCheck($caseid)
	{
		if($caseid != '')
		{
			$this->db_sugar->select('investigation_advocate_statu_c as status');
			$this->db_sugar->from('cases_cstm');
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			$this->db_sugar->where('case_id_c', $caseid);	
			$this->db_sugar->where('cases.deleted', 0);	
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($record_sugar);
			if(count($record_sugar) > 0){
				
				$status = $record_sugar[0]->status;	
				if($status != '')
				{
					return $status;
				}
				else
				{
					return -1;
				}
			}
			else
			{
				return -1;
			}
		}
		else
		{
			return -1;
		}
	}
	
	public function save_case_status($caseid, $status, $reason_val='', $recmd_type = '')	
	{		
		$logData = '1. PPS  Case Status Update Save Start CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", recmd_type - " . $recmd_type .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_case_status');

		if($caseid != '' && $status != ''){
			
			$sugar_id = '';
			$sugar_lead_id = '';
			$investigation_status = '';

			//case table id
			$this->db_sugar->select('id_c, investigation_advocate_statu_c');
			$this->db_sugar->from('cases_cstm');
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			$this->db_sugar->where('case_id_c', $caseid);			
			$this->db_sugar->where('cases.deleted', 0);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($record_sugar);
			if(count($record_sugar) > 0){
				
				$sugar_id = $record_sugar[0]->id_c;	
				$investigation_status = $record_sugar[0]->investigation_advocate_statu_c;					
			}
			//print $investigation_status;
			//lead table id

			$this->db_sugar->select('leads_cstm.id_c, leads.contact_id');
			$this->db_sugar->from('leads_cstm');
			$this->db_sugar->join('leads', 'leads.id = leads_cstm.id_c', 'inner');
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);

			$data_sugar1 = $this->db_sugar->get();
			$record_sugar1 = $data_sugar1->result();
			//print_r($record_sugar1);
			if(count($record_sugar1) > 0){
				
				$sugar_lead_id = $record_sugar1[0]->id_c;				
				$contact_id = $record_sugar1[0]->contact_id;				
			}
			//print 'sugar_id-' . $sugar_id;
			if($sugar_id != ''){
				
				if($status == 'Completed'){
					
					$this->updateStatusBean($sugar_id, $caseid, $status, $investigation_status);
					
					//lead status changes
					$txt = $caseid . ' - sugar_lead_id - ' . $sugar_lead_id . PHP_EOL;
					//file_put_contents('LeadBeanDetails.txt', $txt, FILE_APPEND);
					if($sugar_lead_id != '' && $investigation_status != 'Rework')
					{
						//$sale_type = 'Case Review';
						//$this->updateLeadBean($sugar_lead_id, $sale_type);
						
						$sale_type = $this->get_last_service_sale_type($caseid);
						
						$txt = $caseid . ' - sugar_lead_id - ' . $sugar_lead_id . ', sale type - '. $sale_type . PHP_EOL;
						//file_put_contents('CaseSaleType.txt', $txt, FILE_APPEND);
						
						$logData = '3. PPS  Case Status Update Save CaseID - ' . $caseid . ", status - " . $status . ", sugar_lead_id - " . $sugar_lead_id . ", sale_type - " . $sale_type .  PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_case_status');

						if($sale_type == 'Case Review' || $sale_type == 'Add Service')
						{							
							//set lead level
							$this->updateLeadBean($sugar_lead_id, $sale_type);
						}
						if($sale_type == 'Initial Service')
						{
							$sale_type = 'Case Review';
							//set lead level
							$this->updateLeadBean($sugar_lead_id, $sale_type);
						}
					}
					else
					{
						$logData = '3. PPS  Case Status Update  else Save CaseID - ' . $caseid . ", status - " . $status . ", sugar_lead_id - " . $sugar_lead_id . ", sale_type - " . $sale_type .  ", investigation_status - " . $investigation_status . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_case_status');
					}

					//Added on 25-08-2022
					$user_id_for_log = $this->session->userdata('User_id');
					session_write_close();
					if($recmd_type != '')
					{
						$updateFinalRecType = "UPDATE
													leads_cstm
												INNER JOIN leads ON leads.id = leads_cstm.id_c
												SET
													final_recommendation_type_c = '".$recmd_type."'
												WHERE
													case_id_c = ".$caseid." AND leads.deleted = 0 ";
						$exeFinalRecType = $this->db_sugar->query($updateFinalRecType);
						//file_put_contents('RecmdTypeOnCompleted.txt', $user_id_for_log . ", " . $updateFinalRecType . PHP_EOL, FILE_APPEND);
						$logData = '5. PPS  Case Status Update  FinalRecType Save CaseID - ' . $caseid . ", status - " . $status . ", sugar_lead_id - " . $sugar_lead_id . ", updateFinalRecType - " . $updateFinalRecType .  ", user_id_for_log - " . $user_id_for_log . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_case_status');
					}
					else
					{
						$logData = '5. PPS  Case Status Update  FinalRecType Empty CaseID - ' . $caseid . ", status - " . $status . ", sugar_lead_id - " . $sugar_lead_id . ", recmd_type - " . $recmd_type .  ", user_id_for_log - " . $user_id_for_log . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_case_status');
						//file_put_contents('RecmdTypeOnCompletedEmpty.txt',$user_id_for_log . ", " . $caseid . PHP_EOL, FILE_APPEND);
					}
					// end Added on 25-08-2022

					if(isset($sugar_lead_id))
					{
						//For Leads draft notes
						$this->db_sugar->select('description, name, saved_note_id, id');
						$this->db_sugar->from('draft_notes');
						$this->db_sugar->where(array('parent_type' => 'Leads','parent_id'=>$sugar_lead_id , 'deleted' => '0'));
						$data_sugar = $this->db_sugar->get();
						$record_sugar = $data_sugar->result();

						if (count($record_sugar) > 0) {
							foreach ($record_sugar as $row) {
								$this->load->library('uuid');
								$uuid = $this->uuid->v4();
								$noteId1 = $row->id;
								$result = $this->publish_note($caseid, $noteId1, $uuid);
							}
						}
					}

					if(isset($contact_id))
					{
						//For Contacts draft notes
						$this->db_sugar->select('description, name, saved_note_id, id');
						$this->db_sugar->from('draft_notes');
						$this->db_sugar->where(array('parent_type' => 'Contacts','parent_id'=>$contact_id , 'deleted' => '0'));
						$data_sugar2 = $this->db_sugar->get();
						$record_sugar2 = $data_sugar2->result();

						if (count($record_sugar2) > 0) {
							foreach ($record_sugar2 as $row2) {
								
								$this->load->library('uuid');
								$uuid2 = $this->uuid->v4();
								$noteId2 = $row2->id;
								$result2 = $this->publish_note($caseid, $noteId2 , $uuid2);

							}
						}
					}

					return 1;
				}				
				else
				{ 
					$this->updateStatusBean($sugar_id, $caseid, $status, $investigation_status, $reason_val);
					$logData = '3. PPS  Case Status Update Save CaseID - ' . $caseid . ", status - " . $status . ", sugar_id - " . $sugar_id . ", investigation_status - " . $investigation_status . ", reason_val - " . $reason_val .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','save_case_status');	
					return 1;
				}				
			}
			else
			{
				$logData = '2. PPS  Case Status Update Save SugarId not found CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", recmd_type - " . $recmd_type .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_case_status');
				return 2;
			}
		}
		else
		{
			$logData = '1. PPS  Case Status Update Save Data Missing CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", recmd_type - " . $recmd_type .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_case_status');
			return 0;
		}
	}
	
	//Commented on 20-07-2022
	/*public function updateLogicsStatus($StatusID, $caseid)
	{ 
		if($StatusID !="" && $caseid !="")
		{
			$crm_update = 'https://ctr.irslogics.com/api/updatecase.aspx';
			$crm_update .= '?WorkflowApiToken=889F6977-3FDE-4DE5-B229-B57E8F0EE862&overridestatus=1';
			$crm_update .= '&CaseID='.$caseid;
			$crm_update .= '&StatusID='.$StatusID;

			$update = curl_init($crm_update);
			curl_setopt($update,CURLOPT_RETURNTRANSFER,true);
			$ret = curl_exec($update);
			//print $ret;
			curl_close($update);
		}
	}*/
	
	public function getDocstarList($caseid)
	{ 
		if($caseid != "")
		{
			$record = array(				
				'CaseNo' => $caseid,
				'ServiceType' => 'Listing'
			);	
			
			$url = $this->sugar_url . "/DocstarSugar.php";
			
			$curl_request = curl_init($url);
			curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			//curl_setopt($curl_request, CURLOPT_HEADER, false);
			curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
			/*curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"oauth-token: {$oauth_token}"
			));*/

			//convert arguments to json
			$json_arguments = json_encode($record);
			curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
			
			$curl_response = curl_exec($curl_request);
			print $curl_response;
			
			
			curl_close($curl_request);
		}
	}
	
	public function updateStatusBean($beanid, $caseid, $status, $investigation_status, $reason_val='')
	{ 
		//file_put_contents('updateStatusBean.txt',$caseid . ", " . $status . ", " . $investigation_status, FILE_APPEND);
		$user_id = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();

		$logData = '1. PPS  Case Status Update Status Save Start CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", investigation_status - " . $investigation_status . ", beanid - " . $beanid . ", user_id - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','updateStatusBean');
		
		//print $beanid . ', ' . $caseid . ', ' .  $status. $investigation_status;
		//return false;
		if($beanid != '' && $caseid != '' && $status  != '' && $user_id != '' && $user_name != '' && $user_pwd != '')
		{	
			$curdate = date("Y-m-d");

			if($status == "Rework")
			{
				//if($investigation_status == 'Rework')
				{
					$record = array(				
						'modified_user_id' => $user_id,
						'investigation_advocate_statu_c' => $status,
						'rework_status_c' => 'CR Draft Price'
					);
				}
			}			
			else
			{
				$record = array(				
					'modified_user_id' => $user_id,
					'investigation_advocate_statu_c' => $status,
					'investigation_advocate_last_c' => $curdate,
					'rejection_reason_c' => $reason_val
				);
			}

			/*$record = array(				
				'modified_user_id' => $user_id,
				'investigation_advocate_statu_c' => $status,
				'investigation_advocate_last_c' => $curdate
			);*/				
											
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/Cases/" . $beanid;
				//file_put_contents('status_url.txt',$url);
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
				//file_put_contents('status_req.txt',$json_arguments);
				curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
				
				$curl_response = curl_exec($curl_request);

				$logData = '2. PPS  Case Status Update Status Save Done CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", investigation_status - " . $investigation_status . ", beanid - " . $beanid . ", user_id - " . $user_id . ", record - " . $json_arguments . ", curl_resp - " . $curl_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','updateStatusBean');

				//file_put_contents('CasesStatusResp.txt', $beanid . ' - ' . $curl_response . PHP_EOL, FILE_APPEND);
				//print $curl_response;
				$updatedRecord  = json_decode($curl_response);
				curl_close($curl_request);
			}
			else
			{
				$logData = '2. PPS  Case Status Update Status Save Auth token not found CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", investigation_status - " . $investigation_status . ", beanid - " . $beanid . ", user_id - " . $user_id . ", record - " . $json_arguments . ", oauth_tokenResp - " . $oauth2_token_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','updateStatusBean');
			}
		}
		else
		{
			$logData = '1. PPS  Case Status Update Status Save Data not found CaseID - ' . $caseid . ", status - " . $status . ", reason_val - " . $reason_val . ", investigation_status - " . $investigation_status . ", beanid - " . $beanid . ", user_id - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','updateStatusBean');	
		}
	}
	
	public function updateLeadBean($beanid, $lead_level)
	{
		$user_id = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		$logData = '1. PPS  Case Status Update Lead Start beanid - ' . $beanid . ", lead_level - " . $lead_level . ", user_id - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','updateLeadBean');

		//print $beanid .  ', ' .  $lead_level;
		if($beanid != '' && $lead_level  != '' && $user_id != '' && $user_name != '' && $user_pwd != '')
		{	
			
			//$txt = 'beanid - ' . $beanid . ', lead_level - '. $lead_level . PHP_EOL;
			//file_put_contents('updateLeadBean.txt', $txt, FILE_APPEND);
						
			$record = array(				
				'modified_user_id' => $user_id,
				'lead_level_c' => $lead_level,
				'custom_status_c' => 'Finance Desk',
				'status' => 'Pending'
			);				
											
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/Leads/" . $beanid;
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
				
				$logData = '2. PPS  Case Status Update Lead Done beanid - ' . $beanid . ", lead_level - " . $lead_level . ", user_id - " . $user_id . ", record - " . print_r($record,true) .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','updateLeadBean');

				//$txt = $beanid .  PHP_EOL . $curl_response . PHP_EOL;
				//file_put_contents('LeadBeanCURL.txt', $txt, FILE_APPEND);
					
				//print $curl_response; 
				$updatedRecord  = json_decode($curl_response);
				curl_close($curl_request);
			}
			else
			{
				$logData = '2. PPS  Case Status Update Lead Auth Token not found beanid - ' . $beanid . ", lead_level - " . $lead_level . ", user_id - " . $user_id . ", record - " . print_r($record,true) . ", auth token resp - " . $oauth2_token_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','updateLeadBean');
			}
		}
		else
		{
			$logData = '2. PPS  Case Status Update Lead Data not found beanid - ' . $beanid . ", lead_level - " . $lead_level . ", user_id - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','updateLeadBean');
		}
	}
	
	public function save_summary_details($caseid, $pps_id, $acc_status, $compliance_val, $analysis1, $analysis2, $analysis3, $di_value, $cr_fee_value, $spec_service, $prelim_rec_type, $final_rec_type, $prelim_fee, $aggregate_id, $add_service_fee_val, $cr_template,$add_pps_info,$service_source_val,$reported_income,$reported_assets) //cr_template added on 27-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field // Updated on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added // ,$service_source_val Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record // Updated on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
	{
		$logData = '1. Parms caseid - ' . $caseid . ", pps_id - " . $pps_id . ", acc_status - " . $acc_status . ", compliance_val - " . $compliance_val . ", analysis1 - " . $analysis1 . ", analysis2 - " . $analysis2 . ", analysis3 - " . $analysis3 . ", di_value - " . $di_value . ", cr_fee_value - " . $cr_fee_value . ", spec_service - " . $spec_service . ", prelim_rec_type - " . $prelim_rec_type . ", final_rec_type - " . $final_rec_type . ", prelim_fee - " . $prelim_fee . ", aggregate_id - " . $aggregate_id . ", add_service_fee_val - " . $add_service_fee_val . ", reported_income - " . $reported_income . ", reported_assets - " . $reported_assets . ", cr_template - " . $cr_template . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','save_summary_details');

		//file_put_contents('save_summary_details.txt', $caseid . ', ' . $pps_id . ', ' . $acc_status . ', ' . $compliance_val . PHP_EOL, FILE_APPEND);
		if($caseid != '')
		{	
			//cr_template added on 27-04-2023 https://trello.com/c/tWBZ4lEb/1717-new-cr-portal-field
			//if($cr_template != '')
			{
				//$sql_case = "UPDATE cases_cstm set cr_template_c='".$cr_template."' WHERE case_id_c=$caseid;";
				//$query_sugar_case = $this->db_sugar->query($sql_case); // Commented on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added

				//Added on 31-08-2023 https://trello.com/c/0AxTyj3G/484-cr-portal-summary-notes-field-added
				// $sql_case = "UPDATE cases_cstm set cr_template_c='".$cr_template."' ";
				// $sql_case .= " WHERE case_id_c=$caseid;";

				// $query_sugar_case = $this->db_sugar->query($sql_case);
				if($add_pps_info != '')
				{
					//$sql_case .= ", additional_pps_info_needed_c='" . $add_pps_info . "' " ;

					$sugar_case_id = '';
					//case table id
					$this->db_sugar->select('id_c, additional_pps_info_needed_c');
					$this->db_sugar->from('cases_cstm');
					$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');							
					$this->db_sugar->where('cases.deleted', 0);	
					$this->db_sugar->where('case_id_c', $caseid);	
					
					$data_sugar = $this->db_sugar->get();
					$record_sugar = $data_sugar->result();
					//print_r($record_sugar);
					if(is_array($record_sugar) && count($record_sugar) > 0){
						
						$sugar_case_id = $record_sugar[0]->id_c;	
						$additional_pps_info_needed = $record_sugar[0]->additional_pps_info_needed_c;	
					}
					if($sugar_case_id != '' && $additional_pps_info_needed != $add_pps_info)
					{
						$user_id = $this->session->userdata('User_id');
						$user_name = $this->session->userdata('uname');
						$user_pwd = $this->session->userdata('pwd');
						session_write_close();

						if($cr_template != '')
						{
							$record_cases = array(				
								'modified_user_id' => $user_id,
								'cr_template_c' => $cr_template,
								'additional_pps_info_needed_c' => $add_pps_info
							);	
						}
						else
						{
							$record_cases = array(				
								'modified_user_id' => $user_id,
								'additional_pps_info_needed_c' => $add_pps_info
							);	
						}

						// Added on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
						if($reported_income != '')
						{
							$record_cases['reported_income_c'] = $reported_income;
						}
						if($reported_assets != '')
						{
							$record_cases['reported_assets_c'] = $reported_assets;
						} // end added on 26-03-2024
						file_put_contents('UCB.txt', print_r($record_cases,true) . PHP_EOL, FILE_APPEND);
						
									file_put_contents('status_data.txt',print_r($record_cases,true));
						$instance_url = $this->sugar_url . "rest/v11"; 

						//Login - POST /oauth2/token
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
						$oauth_token = $oauth2_token_response_obj->access_token;
						
						//Create Records - PUT /<module>/id
						if($oauth_token != '')
						{
							$url = $instance_url . "/Cases/" . $sugar_case_id;
							file_put_contents('status_url.txt',$url);
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
							$json_arguments = json_encode($record_cases);
							//file_put_contents('status_req.txt',$json_arguments);
							curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
							
							$curl_response = curl_exec($curl_request);
					
							$updatedRecord  = json_decode($curl_response);
							curl_close($curl_request);
						}
						else
						{
							$logData = "2. Cases Update No Auth Token " . ' caseid - ' . $caseid . ", cr_template - " . $cr_template . ", pps_info - " . $add_pps_info  . PHP_EOL;
							$this->createCustomLog($logData,'SummaryTab','save_summary_details');
						}
					}
					else
					{
						// Added on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
						$conditions = '';
						$sql_case = "UPDATE cases_cstm set ";
						if($cr_template != '')
						{
							$conditions .= " cr_template_c='".$cr_template."' ";							
						}
						
						if($reported_income != '')
						{
							$conditions .= ", reported_income_c='" . $reported_income . "' " ;
						}
						if($reported_assets != '')
						{
							$conditions .= ", reported_assets_c='" . $reported_assets . "' " ;
						}

						if($conditions != '')
						{
							$conditions = ltrim($conditions,",");
							$sql_case .= $conditions . " WHERE case_id_c=$caseid;";
							$query_sugar_case = $this->db_sugar->query($sql_case);
							file_put_contents('UC1.txt', $sql_case . PHP_EOL, FILE_APPEND);
							$logData = "2. Cases Update No Sugar Case Id " . ' caseid - ' . $caseid . ", cr_template - " . $cr_template . ", pps_info - " . $add_pps_info  . PHP_EOL;
							$this->createCustomLog($logData,'SummaryTab','save_summary_details');
						}  // end added on 26-03-2024
					}
				}
				else
				{
					// Added on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
					$conditions = '';
					$sql_case = "UPDATE cases_cstm set ";
					if($cr_template != '')
					{
						$conditions .= " cr_template_c='".$cr_template."' ";							
					}
					
					if($reported_income != '')
					{
						$conditions .= ", reported_income_c='" . $reported_income . "' " ;
					}
					if($reported_assets != '')
					{
						$conditions .= ", reported_assets_c='" . $reported_assets . "' " ;
					} 

					if($conditions != '')
					{
						$conditions = ltrim($conditions,",");
						$sql_case .= $conditions . " WHERE case_id_c=$caseid;";
						$query_sugar_case = $this->db_sugar->query($sql_case);
						file_put_contents('UC2.txt', $sql_case . PHP_EOL, FILE_APPEND);
						$query_sugar_case = $this->db_sugar->query($sql_case);
					}
					// end added on 26-03-2024

					// if($cr_template != '')
					// {
					// 	$sql_case = "UPDATE cases_cstm set cr_template_c='".$cr_template."' WHERE case_id_c=$caseid;";
					// 	$query_sugar_case = $this->db_sugar->query($sql_case);
					// } // Commented on 26-03-2024 https://trello.com/c/5oQi9Pzf/771-investigations-reported-income-field?filter=label:Services,label:MobileApp
				}			
				// end added on 31-08-2023 
				
				$logData = "2. Cases Update Success " . ' caseid - ' . $caseid . ", cr_template - " . $cr_template . ", pps_info - " . $add_pps_info  . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','save_summary_details');
			}	//end added on 27-04-2023
			$pps_val_update = 0;
			if($pps_id != '')
			{
				$sql = "UPDATE inves_pps_cstm set "; 
							
				if($acc_status != '')
				{
					$pps_val_update = 1;
					$sql .= " account_status_c='$acc_status',";
				}
				if($compliance_val != '')
				{
					$pps_val_update = 1;
					$sql .= "compliance_summary_c='$compliance_val',";
				}
				if($analysis1 != '')
				{
					$pps_val_update = 1;
					$sql .= "expense_analysis_c='$analysis1',";
				}
				if($analysis2 != '')
				{
					$pps_val_update = 1;
					$sql .= "income_analysis_c='$analysis2',";
				}
				if($analysis3 != '')
				{
					$pps_val_update = 1;
					$sql .= "asset_analysis_c='$analysis3',";
				}
				if($di_value != '')
				{
					$pps_val_update = 1;
					$di_value = str_replace( ',', '', $di_value );
					//$sql .= "disposable_income_c=$di_value,";
					$sql .= "disposable_income_c=" . floatval($di_value) . ",";
				}
				if($pps_val_update > 0)
				{
					$sql = rtrim($sql,',');
					$sql .= " WHERE id_c='$pps_id' ";	

					//print $sql;
				
					$query_sugar = $this->db_sugar->query($sql);
					//print_r($query_sugar1);
					if($query_sugar)
					{
						$logData = "3. PPS Update Success " . ' caseid - ' . $caseid . ", pps_id - " . $pps_id . ", sql - " . $sql . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','save_summary_details');
						//file_put_contents('save_summary_detailsQS.txt', $caseid . ', ' . $sql  . PHP_EOL, FILE_APPEND);
						//return 1 ; 
					}
					else{
						
						$err =  $this->db_sugar->error();
						$logData = "3. PPS Update Error " . ' caseid - ' . $caseid . ", pps_id - " . $pps_id . ", err- " . $err . ", sql - " . $sql . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','save_summary_details');
						//file_put_contents('save_summary_detailsQE.txt', $caseid . ', ' . $sql  . PHP_EOL, FILE_APPEND);
						//print_r($err);
					}					
				}
				else
				{
					$logData = "3. PPS Update Nothing to update " . ' caseid - ' . $caseid . ", pps_id - " . $pps_id . ", sql - " . $sql . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_summary_details');
				}
			}
			else
			{
				$logData = "2. PPSId not found " . '1. Parms caseid - ' . $caseid . ", pps_id - " . $pps_id . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','save_summary_details');
			}
			//Update leads cstm for cr fees $spec_service, $prelim_rec_type, $final_rec_type, $prelim_fee
			if($cr_fee_value > 0 || $final_rec_type != '' || $service_source_val != '') // ,$service_source_val Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record
			{							
				$sql_leads = "UPDATE leads_cstm set ";
				
				if($cr_fee_value >= 0)
				{
					$cr_fee_value = str_replace( ',', '', $cr_fee_value );
					$cr_fee_value = floatval($cr_fee_value);
					$sql_leads .= " cr_fee_c=$cr_fee_value,";					 
				}
				if($add_service_fee_val >= 0)
				{
					$add_service_fee_val = str_replace( ',', '', $add_service_fee_val );
					$add_service_fee_val = floatval($add_service_fee_val);
					$sql_leads .= " add_service_fee_c=$add_service_fee_val,";
				}				
				if($final_rec_type != '')
				{
					$sql_leads .= " final_recommendation_type_c='$final_rec_type',";					 
				}
				// Added on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record
				if($service_source_val != '')
				{
					$sql_leads .= " add_service_source_c='$service_source_val',";					 
				} // end added on 23-11-2023
				$sql_leads = rtrim($sql_leads, ",");
				$sql_leads .= " WHERE case_id_c=$caseid;";
				
				//print $sql_leads;
			
				$query_sugar_leads = $this->db_sugar->query($sql_leads);
				if($query_sugar_leads)
				{
					$logData = "4. Leads Update Success " . ' caseid - ' . $caseid . ", query_sugar_leads - " . $query_sugar_leads . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_summary_details');
					//file_put_contents('FinalRecTypeIssueSuccess.txt', $caseid . ', ' . $final_rec_type . ", " . $sql_leads . PHP_EOL, FILE_APPEND);	
				}
				else
				{
					$logData = "4. Leads Update Error " . ' caseid - ' . $caseid . ", query_sugar_leads - " . $query_sugar_leads . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_summary_details');
					//file_put_contents('FinalRecTypeIssueError.txt', $caseid . ', ' . $final_rec_type . ", " . $sql_leads . PHP_EOL, FILE_APPEND);	
				}
			}
			if($aggregate_id != '' && $aggregate_id != null && ($prelim_fee > 0 || $spec_service != '' || $prelim_rec_type != ''))
			{	
				//INNER JOIN aggre_aggregatevalues ON id =id_c
				$sql_leads_agg = "UPDATE aggre_aggregatevalues_cstm  SET ";
				
				if($prelim_fee >= 0)
				{
					$prelim_fee = str_replace( ',', '', $prelim_fee );
					$prelim_fee = floatval($prelim_fee);
					$sql_leads_agg .= " prelim_fee_c=$prelim_fee,";					 
				}
				if($spec_service != '')
				{
					$sql_leads_agg .= " specified_services_c='$spec_service',";					 
				}
				if($prelim_rec_type != '')
				{
					$sql_leads_agg .= " prelim_recommendation_type_c='$prelim_rec_type',";					 
				}
				$sql_leads_agg = rtrim($sql_leads_agg, ",");
				//$sql_leads_agg .= " WHERE name='$caseid' AND deleted=0;";
				$sql_leads_agg .= " WHERE id_c='" . $aggregate_id . "';";
				
				//print $sql_leads_agg;
			
				$query_sugar_leads1 = $this->db_sugar->query($sql_leads_agg);
				
				if($query_sugar_leads1)
				{
					$logData = "5. Aggregate Update Success " . ' caseid - ' . $caseid . ", query_sugar_agg - " . $query_sugar_leads1 . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_summary_details');
					return 1;
				}
				else
				{					
					$err =  $this->db_sugar->error();
					$logData = "5. Aggregate Update Error " . ' caseid - ' . $caseid . ", err - ". $err . ", query_sugar_agg - " . $query_sugar_leads1 . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','save_summary_details');
					//print_r($err);
				}
			}
			else
			{
				$logData = "5. Aggregate Nothing to update " . ' caseid - ' . $caseid . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','save_summary_details');
				return 1;
			}
		}
		else 
		{
			$logData = "2. CaseId not found " . '1. Parms caseid - ' . $caseid . ", pps_id - " . $pps_id . ", acc_status - " . $acc_status . ", compliance_val - " . $compliance_val . ", analysis1 - " . $analysis1 . ", analysis2 - " . $analysis2 . ", analysis3 - " . $analysis3 . ", di_value - " . $di_value . ", cr_fee_value - " . $cr_fee_value . ", spec_service - " . $spec_service . ", prelim_rec_type - " . $prelim_rec_type . ", final_rec_type - " . $final_rec_type . ", prelim_fee - " . $prelim_fee . ", aggregate_id - " . $aggregate_id . ", add_service_fee_val - " . $add_service_fee_val . ", cr_template - " . $cr_template . PHP_EOL;;
			$this->createCustomLog($logData,'SummaryTab','save_summary_details');
			return 0;
		}
	}	
	
	public function save_pps_instructions($ppsid, $instruction)
	{
		$logData = '1. PPS Instr Start PPSId - ' . $ppsid . ", instruction - " . $instruction . PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_instructions');

		$instruction = htmlspecialchars(utf8_encode($instruction), ENT_QUOTES);
		
		if($ppsid != '' && $instruction != '')
		{	
			$sql = "UPDATE inves_pps_cstm set pps_instructions_c = '" . $instruction . "'"; 
			$sql .= " WHERE id_c='$ppsid' ";				
			
			$query_sugar = $this->db_sugar->query($sql);
			//print_r($query_sugar);
			if($query_sugar)
			{
				$logData = '2. PPS Instr Success PPSId - ' . $ppsid . ", instruction - " . $instruction . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_instructions');
				return 1 ; 
			}
			else
			{
				$logData = '2. PPS Instr Error PPSId - ' . $ppsid . ", sql - " . $sql . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_instructions');

				$err =  $this->db_sugar->error();
				print_r($err);
			}
		}
		else 
		{
			$logData = '2. PPS Instr Missing Data PPSId - ' . $ppsid . ", instruction - " . $instruction . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_instructions');
			return 0;
		}
	}
	
	public function create_pps_detail_record_fpers($caseid, $ppsid)
	{ 
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$id_year = array();
		$years = array();
		//print 'create_pps_detail_record_fpers - '. $caseid . ', '. $ppsid;
		if($ppsid != '' && $caseid != '')
		{
			//print $ppsid . ' , ' . $caseid;
			for($i=1; $i<=6; $i++)
			{	
				$year = date("Y",strtotime("-$i year"));
				
				$newid = $this->gen_uuid();
				
				$id_year[$year] = $newid;
				//print $year . ' - '. $newid . ',  ';
				
							
				if($newid != '' && $year != '')
				{
					$name = $year .' - '. 'Federal Personal';
					
					//Added on 10-02-2023 add check to check existing records of the ppsid id with the given year and name
					$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
									INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
									INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
									WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
									AND tax_period = '".$year."'
									AND name = '".$name."'
									AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
									AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 0;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

						
					$dataRecords = $this->db_sugar->query($getRecords);
					$resRecords = $dataRecords->result();
								
				
					if($resRecords[0]->recCount == 0)
					{ // end  Added on 10-02-2023
					 
						$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, 1, 1)"; //`csed`, 
						
						//print $sql1 .'<br>';
								
						$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $user_id)); 
						//print $query1 ;
						
						if($query1)
						{
							$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`) VALUES (?)"; 
						
							//print $sql_csed .'<br>';
								
							$query_csed = $this->db_sugar->query($sql_csed, array($newid));  
						
							//print $rec[12] . '  -- ' . $query_csed .'<br>';
							
							$newid2 = $this->gen_uuid();
							
							$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
						
							//print $sql_rel ;
							
							$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
							
							if(!$query_rel)
							{
								$error = 1;
							}
						}
						else{
							$error = 2;
							$err =  $this->db_sugar->error();
							print_r($err);
						}
					} // if closing  Added on 10-02-2023
				} 
			}
			//print_r($id_year);
			
			//fetch new pps detail record
			
			$pps_rec = $this->fetch_pps_personel_sugar($ppsid);
			//print_r($pps_rec);
			return $pps_rec;
			//die();
		}
	}
	
	public function create_pps_detail_premium_record($caseid, $ppsid)
	{ 
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$id_year = array();
		$years = array();
		//print 'create_pps_detail_record_fpers - '. $caseid . ', '. $ppsid;
		if($ppsid != '' && $caseid != '')
		{
			//print $ppsid . ' , ' . $caseid;
			for($i=1; $i<=6; $i++)
			{	
				$year = date("Y",strtotime("-$i year"));
				
				$newid = $this->gen_uuid();
				
				$id_year[$year] = $newid;
				//print $year . ' - '. $newid . ',  ';
				
							
				if($newid != '' && $year != '')
				{
					$name = $year .' - '. 'Premium';
					
					//Added on 10-02-2023 add check to check existing records of the ppsid id with the given year and name
					$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
									INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
									INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
									WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
									AND tax_period = '".$year."'
									AND name = '".$name."'
									AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
									AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 0;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

						
					$dataRecords = $this->db_sugar->query($getRecords);
					$resRecords = $dataRecords->result();
								
				
					if($resRecords[0]->recCount == 0)
					{ // end  Added on 10-02-2023
					 
						$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, 1, 1)"; //`csed`, 
						
						//print $sql1 .'<br>';
								
						$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $user_id)); 
						//print $query1 ;
						
						if($query1)
						{
							$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`) VALUES (?)"; 
						
							//print $sql_csed .'<br>';
								
							$query_csed = $this->db_sugar->query($sql_csed, array($newid));  
						
							//print $rec[12] . '  -- ' . $query_csed .'<br>';
							
							$newid2 = $this->gen_uuid();
							
							$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
						
							//print $sql_rel ;
							
							$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
							
							if(!$query_rel)
							{
								$error = 1;
							}
						}
						else{
							$error = 2;
							$err =  $this->db_sugar->error();
							print_r($err);
						}
					} // if closing  Added on 10-02-2023
				} 
			}
			//print_r($id_year);
			
			//fetch new pps detail record
			
			$pps_rec = $this->fetch_pps_premium_personel_sugar($ppsid);
			//print_r($pps_rec);
			return $pps_rec;
			//die();
		}
	}
	
	public function create_pps_detail_premium_spouse_record($caseid, $ppsid)
	{ 
		$user_id = $this->session->userdata('User_id');
		session_write_close();

		$id_year = array();
		$years = array();
		//print 'create_pps_detail_record_fpers - '. $caseid . ', '. $ppsid;
		if($ppsid != '' && $caseid != '')
		{
			//print $ppsid . ' , ' . $caseid;
			for($i=1; $i<=6; $i++)
			{	
				$year = date("Y",strtotime("-$i year"));
				
				$newid = $this->gen_uuid();
				
				$id_year[$year] = $newid;
				//print $year . ' - '. $newid . ',  ';
				
							
				if($newid != '' && $year != '')
				{
					$name = $year .' - '. 'Premium';
					
					//Added on 10-02-2023 add check to check existing records of the ppsid id with the given year and name
					$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
									INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
									INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
									WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
									AND tax_period = '".$year."'
									AND name = '".$name."'
									AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
									AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 1;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

						
					$dataRecords = $this->db_sugar->query($getRecords);
					$resRecords = $dataRecords->result();
								
				
					if($resRecords[0]->recCount == 0)
					{ // end  Added on 10-02-2023
					 
						$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, 1, 1)"; //`csed`, 
						
						//print $sql1 .'<br>';
								
						$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $user_id)); 
						//print $query1 ;
						
						if($query1)
						{
							$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`) VALUES (?)"; 
						
							//print $sql_csed .'<br>';
								
							$query_csed = $this->db_sugar->query($sql_csed, array($newid));  
						
							//print $rec[12] . '  -- ' . $query_csed .'<br>';
							
							$newid2 = $this->gen_uuid();
							
							$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
						
							//print $sql_rel ;
							
							$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
							
							if(!$query_rel)
							{
								$error = 1;
							}
						}
						else{
							$error = 2;
							$err =  $this->db_sugar->error();
							print_r($err);
						}
					} // if closing  Added on 10-02-2023
				} 
			}
			//print_r($id_year);
			
			//fetch new pps detail record
			
			$pps_rec = $this->fetch_pps_premium_spouse_personel_sugar($ppsid);
			//print_r($pps_rec);
			return $pps_rec;
			//die();
		}
	}
	
	public function create_pps_detail_record_spouse($caseid, $ppsid)
	{   
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$id_year = array();
		$years = array();
		//print 'create_pps_detail_record_spouse - '. $caseid . ', '. $ppsid;
		if($ppsid != '' && $caseid != '')
		{
			//print $ppsid . ' , ' . $caseid;
			for($i=1; $i<=6; $i++)
			{	
				$year = date("Y",strtotime("-$i year"));
				
				$newid = $this->gen_uuid();
				
				$id_year[$year] = $newid;
				//print $year . ' - '. $newid . ',  ';
				
							
				if($newid != '' && $year != '')
				{
					$name = $year .' - '. 'Federal Personal';

					//  Added on 10-02-2023  to check existing records of the ppsid id with the given year and name
					$getRecords = "SELECT
										COUNT(*)
									FROM
										`ppsd_ppsdetail`
									INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
									INNER JOIN inves_pps ON inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
									INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps.id
									WHERE
										inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."' AND tax_period = '".$year."' AND ppsd_ppsdetail.name = '".$name."' AND inves_pps_ppsd_ppsdetail_1_c.deleted = 0 AND ppsd_ppsdetail.deleted = 0 AND inves_pps.deleted = 0 AND inves_pps_cstm.pps_spouse_c = 1;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

									

						
					$dataRecords = $this->db_sugar->query($getRecords);
					$resRecords = $dataRecords->result();
					if($resRecords[0]->recCount == 0)
					{ // end  Added on 10-02-2023
					
						$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, 1, 1)";
						
						//print $sql1 .'<br>';
								
						$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $user_id)); 
						//print $query1 ;
						
						if($query1)
						{
							$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`) VALUES (?)"; 
						
							//print $sql_csed .'<br>';
								
							$query_csed = $this->db_sugar->query($sql_csed, array($newid));  
						
							//print $rec[12] . '  -- ' . $query_csed .'<br>';
							
							$newid2 = $this->gen_uuid();
							
							$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
						
							//print $sql_rel ;
								
							$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
							
							if(!$query_rel)
							{
								$error = 1;
							}
						}
						else{
							$error = 2;
							$err =  $this->db_sugar->error(); 
							print_r($err);
						}
					} // if closing  Added on 10-02-2023
				} 
			}
			//print_r($id_year);
			
			//fetch new pps spouse detail record
			
			$pps_rec = $this->fetch_pps_spouse_sugar($ppsid);
			//print_r($pps_rec);
			return $pps_rec;
			//die();
		}
	}
	
	public function create_pps_detail_record_state_pers($caseid, $ppsid)
	{  
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$id_year = array();
		$years = array();
		//print 'create_pps_detail_record_state_pers - '. $caseid . ', '. $ppsid;
		if($ppsid != '' && $caseid != '')
		{
			//print $ppsid . ' , ' . $caseid;
			for($i=1; $i<=6; $i++)
			{	 
				$year = date("Y",strtotime("-$i year"));
				
				$newid = $this->gen_uuid();
				
				$id_year[$year] = $newid;
				//print $year . ' - '. $newid . ',  ';
				
							
				if($newid != '' && $year != '')
				{
					$name = $year .' - '. 'State Personal';

					// Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
					$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
									INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
									WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
									AND tax_period = '".$year."'
									AND name = '".$name."'
									AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
									AND ppsd_ppsdetail.deleted=0"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

						
					$dataRecords = $this->db_sugar->query($getRecords);
					$resRecords = $dataRecords->result();
								

					if($resRecords[0]->recCount == 0)
					{ // end Added on 10-02-2023
					
						$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, 1, 1)";
						
						//print $sql1 .'<br>';
								
						$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $user_id)); 
						//print $query1 ;
						
						if($query1)
						{
							$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`) VALUES (?)"; 
						
							//print $sql_csed .'<br>';
								
							$query_csed = $this->db_sugar->query($sql_csed, array($newid));  
						
							//print $rec[12] . '  -- ' . $query_csed .'<br>';
							
							$newid2 = $this->gen_uuid();
							
							$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
						
							//print $sql_rel ;
								
							$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
							
							if(!$query_rel)
							{
								$error = 1;
							}
						}
						else{
							$error = 2;
							$err =  $this->db_sugar->error(); 
							print_r($err);
						}
					}  // if closing  Added on 10-02-2023
				} 
			}
			//print_r($id_year);
			
			//fetch new pps spouse detail record
			
			$pps_rec = $this->fetch_pps_state_personel_sugar($ppsid);
			//print_r($pps_rec);
			return $pps_rec; 
			//die();
		}
	}
	
	public function update_main_pps_status($ppsid, $caseid)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		
		$logData = '1. PPS Status Update Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_main_pps_status');

		if($ppsid != '' && $caseid != '')
		{
			$pps_status = 'Completed';
			$retval = $this->update_pps_status($caseid, $ppsid, $pps_status);
			$ret_req_service = $this->check_pps_service($ppsid);
			
			if($retval == 1 && $ret_req_service != 'Servicing')
			{
				$logData = '2. PPS Status Update Save If CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_status');

				$ret_val = $this->check_all_pps_status($caseid);
				
				$ret_val_inves = $this->check_investigation_type_stage($caseid);

				$logData = '3. PPS Status Update Save If CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service . ", ret_val - " . $ret_val . ", ret_val_inves - " . $ret_val_inves .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_status');

				//print $ret_val_inves. ', all pps - '.$ret_val.' - ';
				if($ret_val_inves == 1 && $ret_val == 1  && $ret_req_service != 'Servicing')
				{
					$status = 'Completed';
					$this->save_case_status($caseid, $status);
					
					$StatusID=254;
					//$this->updateLogicsStatus($StatusID, $caseid); // commented on 20-07-2022
					
					$logData = '4. PPS Status Update Save If2 CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service . ", ret_val - " . $ret_val . ", ret_val_inves - " . $ret_val_inves . ", status" . $status .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','update_main_pps_status');

					return 2;
				}
				else if($ret_val == 1  && $ret_req_service != 'Servicing')
				{
					$status = 'Initial Analysis'; //'Strategy Assigned';
					$this->save_case_status($caseid, $status);

					$logData = '4. PPS Status Update Save ElseIf2 CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service . ", ret_val - " . $ret_val . ", ret_val_inves - " . $ret_val_inves . ", status" . $status .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','update_main_pps_status');
					return 2;
				}
				else
				{
					$logData = '4. PPS Status Update Save else2 CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service . ", ret_val - " . $ret_val . ", ret_val_inves - " . $ret_val_inves .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','update_main_pps_status');
					return 1;
				}
			}
			else if($retval == 1)
			{	
				$logData = '2. PPS Status Update Save else If CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", ret_req_service - " . $ret_req_service .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_status');			
				return 1;
			}
			else
			{				
				$err =  $this->db_sugar->error(); 
				$logData = '2. PPS Status Update Save else CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id . ", err - " . $err .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_status');
				//print_r($err);
				return 0;
			}
		}
		else
		{
			$logData = '2. PPS Status Update Data Not found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','update_main_pps_status');
		}
	}
	
	public function update_main_poa_issue($ppsid, $issue)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		
		if($ppsid != '' && $issue != '')
		{
			$sql = "UPDATE inves_pps set poa_issues='$issue', date_modified=now(), modified_user_id='$user_id' WHERE id='$ppsid';";     

			//print $sql.'<br>' ; 
							
			$query = $this->db_sugar->query($sql);
			
			if($query)
			{
				return 1;
			}
			else
			{				
				$err =  $this->db_sugar->error(); 
				print_r($err);
				return 0;
			}
		}
	}
	
	public function update_main_biz_poa_issue($ppsid, $issue)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		
		if($ppsid != '' && $issue != '')
		{
			$sql = "UPDATE inves_pps_cstm set biz_poa_issues_c='$issue' WHERE id_c='$ppsid';"; 
			
			//print $sql.'<br>' ; 
							
			$query = $this->db_sugar->query($sql);
			
			if($query)
			{
				$sql1 = "UPDATE inves_pps set date_modified=now(), modified_user_id='$user_id' WHERE id='$ppsid';";

				//print $sql1.'<br>' ; 
							
				$query1 = $this->db_sugar->query($sql1);
			
				return 1;
			}
			else
			{				
				$err =  $this->db_sugar->error(); 
				print_r($err);
				return 0;
			}
		}
	}
	
	public function update_pps_assigned_user($ppsid, $assigned_user)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		
		if($ppsid != '' && $assigned_user != '')
		{
			$sql = "UPDATE inves_pps set assigned_user_id='$assigned_user', date_modified=now(), modified_user_id='$user_id' WHERE id='$ppsid';";     

			//print $sql.'<br>' ; 
							
			$query = $this->db_sugar->query($sql);
			
			if($query)
			{
				return 1;
			}
			else
			{				
				$err =  $this->db_sugar->error(); 
				print_r($err);
				return 0;
			}
		}
	}
	
	public function update_main_pps_values($caseid, $ppsid, $poa_issue, $biz_poa_issue, $assigned_to, $verbal_poa, $state, $instruction)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		$logData = '1. PPS Value Update Main Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", poa_issue - " . $poa_issue . ", biz_poa_issue - " . $biz_poa_issue . ", assigned_to - " . $assigned_to  . ", verbal_poa - " . $verbal_poa . ", state - " . $state  . ", instruction - " . $instruction . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_main_pps_values');

		if($ppsid != '' && $uid != '' && $user_name != '' && $user_pwd != '' && ($poa_issue != '' || $biz_poa_issue != '' || $assigned_to != '' ||  $verbal_poa != '' || $state != '' || $instruction != ''))
		{	
			$record = array(
				'poa_issues' => $poa_issue,
				'modified_user_id' => $uid,
				'assigned_user_id' => $assigned_to,								
				'biz_poa_issues_new_c' => $biz_poa_issue,
				'verbal_poa_c' => $verbal_poa,
				'state_c' => $state,
				'pps_instructions_c' => $instruction
			);
			
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/inves_PPS/" . $ppsid;
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
				
				$logData = '2. PPS Value Update Main Done CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", record - " . print_r($record,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_values');

				$updatedRecord  = json_decode($curl_response);
				curl_close($curl_request);
				//print 'curl_response - ' . $curl_response;			
				return 1;
				/*if($updatedRecord->id != ''){
					return true;
				}
				else
					return false;*/
			}
			else
			{
				$logData = '2. PPS Value Update Main Auth Token Not Found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", record - " . print_r($record,true) . ", oauth2_token_response - " . $oauth2_token_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_main_pps_values');
				return 0;
			}
		}
		else
		{
			$logData = '2. PPS Value Update Main Data Not Found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", poa_issue - " . $poa_issue . ", biz_poa_issue - " . $biz_poa_issue . ", assigned_to - " . $assigned_to  . ", verbal_poa - " . $verbal_poa . ", state - " . $state  . ", instruction - " . $instruction . PHP_EOL;
			$this->createCustomLog($logData,'PPS','update_main_pps_values');
			return 0;
		}
	}
	
	public function calculatePPSFieldsNew($ppsid)
	{
		$result_array = array();
		if($ppsid != '')
		{
			$this->db_sugar->select("round(SUM(balance),2) as total_balance ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Total_Balance'] =  $record[0]->total_balance;
			}
			else
			{
				$result_array['Total_Balance'] =  '';
			}

//---------------------------------------------------------------------------------

			$this->db_sugar->select("csed_date_c ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('balance > ', 0);
			$this->db_sugar->order_by('tax_period  ASC');
			$this->db_sugar->limit(1,0);

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['CSED'] =  $record[0]->csed_date_c;
			}
			else
			{
				$result_array['CSED'] = '';
			}

			//Unfiled Yyears---------------------------------------------------------------------------------
			$unfiled_year = '';
			$record = array();
			$this->db_sugar->select("tax_period ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('filing_status', 'Unfiled');

			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();	
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				for($i=0; $i<count($record); $i++)
				{
					$unfiled_year .= '^' . $record[$i]->tax_period . '^,';
					//print $record[0]->tax_period;
				}				
			}
			if($unfiled_year != '')
			{
				$unfiled_year = rtrim($unfiled_year,',');
				$result_array['Unfiled_Years'] =  $unfiled_year;
			}
			else
			{
				$result_array['Unfiled_Years'] =  '';
			}
			//Last_Year_Filed---------------------------------------------------------------------------------
			
			$this->db_sugar->select("max(tax_period) as tax_period");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);

			$where = "(filing_status = 'Single' OR filing_status = 'HOH' OR filing_status = 'MFJ' OR filing_status = 'MFS')";
			$this->db_sugar->where($where);

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Last_Year_Filed'] =  $record[0]->tax_period;
			}
			else
			{
				$result_array['Last_Year_Filed'] =  '';
			}

			//Last_Year_Filed_Status---------------------------------------------------------------------------------
			
			$this->db_sugar->select("filing_status ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$where = "(filing_status = 'Single' OR filing_status = 'HOH' OR filing_status = 'MFJ' OR filing_status = 'MFS')";
			$this->db_sugar->where($where);
			$this->db_sugar->order_by('tax_period  DESC');
			$this->db_sugar->limit(1,0);			

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Last_Year_Filed_Status'] =  $record[0]->filing_status;
			}
			else
			{
				$result_array['Last_Year_Filed_Status'] =  '';
			}

			//SFR Years---------------------------------------------------------------------------------
			$sfr_years = '';
			$this->db_sugar->select("tax_period ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('filing_status','SFR');

			$data = $this->db_sugar->get();
			$record = $data->result();

			if(is_array($record) && count($record) > 0)
			{
				for($i=0; $i<count($record); $i++)
				{
					$sfr_years .= '^' . $record[$i]->tax_period . '^,';
				}				
			}
			if($sfr_years != '')
			{
				$sfr_years = rtrim($sfr_years,',');
				$result_array['SFR_Years'] =  $sfr_years;
			}
			else
			{
				$result_array['SFR_Years'] = '';
			}

			//col_active ---------------------------------------------------------------------------------
			
			$this->db_sugar->select("ppsd_ppsdetail.id ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$where = "(account_status = 'ACS' OR account_status = '4th NOL')";
			$this->db_sugar->where($where);

			$data = $this->db_sugar->get();
			$record = $data->result();
				
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Col_Active'] =  "Yes";
			}
			else
			{
				$result_array['Col_Active'] =  "No";
			}

			//notice_status ---------------------------------------------------------------------------------
			
			$this->db_sugar->select("ppsd_ppsdetail.id ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$where = "(account_status = 'First Notice' OR account_status = '2nd NOITL' OR account_status = '3rd FNOITL' OR account_status = '4th NOL')";
			$this->db_sugar->where($where);

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Notice_Status'] =  "Yes";
			}
			else
			{
				$result_array['Notice_Status'] =  "";
			}

			//Exam_Active ---------------------------------------------------------------------------------
			
			$this->db_sugar->select("ppsd_ppsdetail.id ");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			
			$this->db_sugar->where('account_status','Exam');
			$this->db_sugar->where('tax_period <=','YEAR(now())');
			$this->db_sugar->where('tax_period >=','(YEAR(now())-6)');

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Exam_Active'] =  "Yes";
			}
			else
			{
				$result_array['Exam_Active'] =  "No";
			}

			//RO ---------------------------------------------------------------------------------
			
			$this->db_sugar->select("tax_period");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			
			$this->db_sugar->where('account_status','RO');

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['RO'] =  "Yes";
			}
			else
			{
				$result_array['RO'] =  "No";
			}

			//CNC ---------------------------------------------------------------------------------
			$cnc_years = '';
			$this->db_sugar->select("tax_period");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			
			$this->db_sugar->where('account_status','CNC');

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				for($i=0; $i<count($record); $i++)
				{
					$cnc_years .=  '^' . $record[$i]->tax_period . '^,';
				}				
			}
			if($cnc_years != '')
			{
				$cnc_years =  rtrim($cnc_years,',');
				$result_array['CNC_Years'] = $cnc_years;
			}
			else
			{
				$result_array['CNC_Years'] = '';
			}
			
			//Lien ---------------------------------------------------------------------------------
			
			$this->db_sugar->select("liens");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', $ppsid);			
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			
			$this->db_sugar->where('liens','Yes');

			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0)
			{
				$result_array['Lien'] = "Yes";
			}
			else				
			{
				$result_array['Lien'] = "No";
			}

			return $result_array;
		}
		else
			return 0;
	}
	
	public function update_pps_status($caseid, $ppsid, $status)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		$logData = '1. PPS Status Update Main Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", status - " . $status .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_status');

		//calculate values
		$calculated_values = array();
		$calculated_values = $this->calculatePPSFieldsNew($ppsid);

		//----------------------------------------------------------------------
		//print $caseid . ', ' . $ppsid. ', ' . $status;
		//print_r($calculated_values);
		//die();

		$logData = '2. PPS Status Update Main Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", status - " . $status . ", Calculated Values - " . print_r($calculated_values, true) .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_status');

		if($ppsid != '' && $status != '')
		{	
			$record = array(
				'total_balance_c' => $calculated_values['Total_Balance'],
				'oldest_year_csed_c' => $calculated_values['CSED'],
				'unfiled_years_c' => $calculated_values['Unfiled_Years'],
				'sfr_years_c' => $calculated_values['SFR_Years'],
				'last_year_filed_c' => $calculated_values['Last_Year_Filed'],
				'last_year_filed_status_c' => $calculated_values['Last_Year_Filed_Status'],
				'collections_active_c' => $calculated_values['Col_Active'],
				'notice_status_c' => $calculated_values['Notice_Status'],
				'exam_active_c' => $calculated_values['Exam_Active'],
				'ro_present_c' => $calculated_values['RO'],
				'lien_active_c' => $calculated_values['Lien'],
				'cnc_years_c' => $calculated_values['CNC_Years'],
				'pps_status' => $status,
				'modified_user_id' => $uid
			);
			//print_r($record);
			//die();
			
			$instance_url = $this->sugar_url . "rest/v11"; 
			
			//Login - POST /oauth2/token
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/inves_PPS/" . $ppsid;
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
				
				$logData = '4. PPS Status Update Main Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", status - " . $status . ", auth Token - " . $oauth_token . ", curlResp - " . $curl_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_pps_status');

				$updatedRecord  = json_decode($curl_response);
				curl_close($curl_request);
				//print 'curl_response - ' . $curl_response;			
				return 1;
				/*if($updatedRecord->id != ''){
					return true;
				}
				else
					return false;*/
			}
			else
			{
				$logData = '4. PPS Status Update Main Save auth token not foud CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", status - " . $status . ", auth TokenResp - " . $oauth2_token_response .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_pps_status');

				return 0;
			}
		}
		else
		{
			$logData = '3. PPS Status Update Main Save Data Not Found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", UserId - " . $uid .", status - " . $status .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','update_pps_status');
			return 0;
		}
	}
	
	// Commented on 07-10-2022
	/*public function save_pps_sugar_details($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		
		//print $ppsid
		//$idarr = json_decode($id_data);		
		//print $pps_new_data;
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			//print_r($pps_new_data_array);
			//return 1;
			if(count($pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count($pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						if($newid != '' &&  $year > 0)
						{							
							$name = $year .' - '. 'Federal Personal';
							
							if($pps_row_array->default_bal != '')
							{
								$bal1 = str_replace(',', '', $pps_row_array->default_bal);
							}
							else
							{
								$bal1 = 0;
							}
									
							$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
							
							//print $sql1 .'<br>';
											
							$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, $pps_row_array->rep_wage, $pps_row_array->rep_se, $pps_row_array->rep_other, $pps_row_array->gross_month_inc, $pps_row_array->tax_withheld, $pps_row_array->filing_stat, $pps_row_array->account_stat, $pps_row_array->ro, $pps_row_array->fta_eligible, $pps_row_array->liens, $user_id)); //$rec[12], 
								
									
							if($query1)
							{ 
								$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`) VALUES (?, ?, ?)"; //`csed`, 
							
								//print $sql_csed .'<br>';
									
								$query_csed = $this->db_sugar->query($sql_csed, array($newid,  $pps_row_array->csed, $pps_row_array->tin));  
							
								//print $rec[12] . '  -- ' . $query_csed .'<br>';
								
								$newid2 = $this->gen_uuid();
								
								$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
							
								//print $sql_rel ;
									
								$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
								
								if($query_rel)
								{
									
								}
								else
								{
									$error = 1;										
								}
							}
							else
							{
								$error = 2;
								$err =  $this->db_sugar->error();
								print_r($err);
							}							
						}
					}
				}
			}
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			//print_r($pps_new_data_array);
			if(count($pps_existing_data_array) > 0)
			{
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count($existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						//print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							$year = $existing_pps_row_array->tax_period;
							
							if($year > 0)
							{
								$sql2 = "UPDATE ppsd_ppsdetail set ";
								
								if($existing_pps_row_array->default_bal != '')
								{
									$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
									
									$sql2 .= " balance=" . floatval($bal) . ", ";
								}
															
								if($existing_pps_row_array->rep_wage != '')
								{
									$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
								}
								if($existing_pps_row_array->rep_se != '')
								{
									$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
								}
								if($existing_pps_row_array->rep_other != '')
								{
									$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
								}
								if($existing_pps_row_array->gross_month_inc != '')
								{
									$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
								}
								if($existing_pps_row_array->tax_withheld != '')
								{
									$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
								}							
								if($existing_pps_row_array->filing_stat != '')
								{
									$filing_stat = $existing_pps_row_array->filing_stat;
									$sql2 .= " filing_status='$filing_stat', ";
								}
								if($existing_pps_row_array->account_stat != '')
								{
									$account_stat = $existing_pps_row_array->account_stat;
									$sql2 .= " account_status='$account_stat', ";
								}
								if($existing_pps_row_array->ro != '')
								{
									$ro = $existing_pps_row_array->ro;
									$sql2 .= " ro_recommended='$ro', ";
								}
								if($existing_pps_row_array->fta_eligible != '')
								{
									$fta_eligible = $existing_pps_row_array->fta_eligible;
									$sql2 .= " fta_eligible='$fta_eligible', ";
								}
								if($existing_pps_row_array->liens != '')
								{
									$liens = $existing_pps_row_array->liens;
									$sql2 .= " liens='$liens', ";
								}
								$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

								//print '<br>UPDATE<br>' . $sql2 ;
							
								$query2 = $this->db_sugar->query($sql2);
							
								if($query2)
								{
									$csed = $existing_pps_row_array->csed;
									$tin = $existing_pps_row_array->tin;
									$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
									
									$sql_up .= " csed_date_c='$csed' ";						
									$sql_up .= " , tin_c='$tin' ";
									
									$sql_up .= " WHERE id_c='$id' ";      

									//print ' ------------------- ' . $sql_up ;
									
									$query3 = $this->db_sugar->query($sql_up);
								}
								else
								{
									$error = 3;
									$err =  $this->db_sugar->error();
									print_r($err);
								}
							}
						}						
					}
				}
			}
		}
		else
		{			
			return 0;
		}
	}*/

	//Added on 13-09-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	public function save_pps_sugar_details($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		//file_put_contents('save_pps_sugar_details1.txt', $caseid .', ' . $ppsid .', ' .  $pps_new_data .', ' .  $pps_existing_data . PHP_EOL, FILE_APPEND);
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		session_write_close();

		$logData = '1. PPS Details Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_sugar_details');

		//print $ppsid
		//$idarr = json_decode($id_data);		
		//print $pps_new_data;
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			//print_r($pps_new_data_array);
			//return 1;
			if(count((array)$pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print '----------------------';
					if(count((array)$pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						
						if($newid != '' &&  $year > 0)
						{
							try	
							{						
								$name = $year .' - '. 'Federal Personal';
								
								if($pps_row_array->default_bal != '')
								{
									$bal1 = str_replace(',', '', $pps_row_array->default_bal);
								}
								else
								{
									$bal1 = 0;
								}
								
								//Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
								$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
								INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
								INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
								WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
								AND tax_period = '".$year."'
								AND name = '".$name."'
								AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
								AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 0;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

					
								$dataRecords = $this->db_sugar->query($getRecords);
								$resRecords = $dataRecords->result();
											

								if($resRecords[0]->recCount == 0)
								{ // end  Added on 10-02-2023
									$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
									
									//print $sql1 .'<br>';
													
									$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, (isset($pps_row_array->rep_wage))?$pps_row_array->rep_wage:NULL, (isset($pps_row_array->rep_se))?$pps_row_array->rep_se:NULL, (isset($pps_row_array->rep_other))?$pps_row_array->rep_other:NULL, (isset($pps_row_array->gross_month_inc))?$pps_row_array->gross_month_inc:NULL, (isset($pps_row_array->tax_withheld))?$pps_row_array->tax_withheld:NULL, (isset($pps_row_array->filing_stat))?$pps_row_array->filing_stat:NULL, (isset($pps_row_array->account_stat))?$pps_row_array->account_stat:NUll, (isset($pps_row_array->ro))?$pps_row_array->ro:NULL, (isset($pps_row_array->fta_eligible))?$pps_row_array->fta_eligible:NULL, (isset($pps_row_array->liens))?$pps_row_array->liens:NULL, $user_id)); //$rec[12], 
										
									//file_put_contents('save_pps_sugar_details2.txt', $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);		
									if($query1)
									{ 
										$logData = '3. PPS Details Save pps detail Main Create CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details');

										$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`,`balance_quarter_c`,`type_pps_c`,`accrued_assessed_c`,`levy_garnishment_c`,`resolution_c`,`resolution_years_c`,`monthly_payment_amount_c`,`monthly_due_date_c`,`last_payment_date_c`,`default_date_c`,`resolution_date_entered_c`,`seperate_assesment_c`,`multiple_csed_c`,`credit_etps_c`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)"; //`csed`, added multiple_csed_c on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
									
										//print $sql_csed .'<br>';
											
										$query_csed = $this->db_sugar->query($sql_csed, array($newid,  (isset($pps_row_array->csed))?$pps_row_array->csed:NULL, (isset($pps_row_array->tin))?$pps_row_array->tin:NULL, (isset($pps_row_array->defaultba2))?$pps_row_array->defaultba2:NULL, (isset($pps_row_array->type_pps))?$pps_row_array->type_pps:'', (isset($pps_row_array->accrued_assessed))?$pps_row_array->accrued_assessed:NULL, (isset($pps_row_array->levy))?$pps_row_array->levy:NULL,(isset($pps_row_array->resolution))?$pps_row_array->resolution:NULL,(isset($pps_row_array->resolution_years))?$pps_row_array->resolution_years:NULL,(isset($pps_row_array->monthly_payment_amount))?$pps_row_array->monthly_payment_amount:NULL,(isset($pps_row_array->monthly_due_date))?$pps_row_array->monthly_due_date:NULL,(isset($pps_row_array->last_payment_date))?$pps_row_array->last_payment_date:NULL,(isset($pps_row_array->default_date))?$pps_row_array->default_date:NULL,(isset($pps_row_array->resolution_date_entered))?$pps_row_array->resolution_date_entered:NULL,(isset($pps_row_array->seperate_assesment))?$pps_row_array->seperate_assesment:NULL,(isset($pps_row_array->multiple_csed))?$pps_row_array->multiple_csed:NULL,(isset($pps_row_array->credit_etps))?$pps_row_array->credit_etps:NULL));  // Updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										//print $rec[12] . '  -- ' . $query_csed .'<br>';

										if($query_csed)
										{
											$logData = '4. PPS Details Save pps detail Cstm Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid .  ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '4. PPS Details Save pps detail Cstm Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');									
										}
										//file_put_contents('save_pps_sugar_details3.txt', $caseid . ' - ' .  $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										$newid2 = $this->gen_uuid();
										
										$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
									
										//print $sql_rel ;
											
										$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
										//file_put_contents('save_pps_sugar_details4.txt',  $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										if($query_rel)
										{
											//$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										}
										else
										{
											$error = 1;	
											$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');									
										}
									}
									else
									{
										$error = 2;
										$err =  $this->db_sugar->error();
										$logData = '3. PPS Details Save pps detail Main Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										//print_r($err);
									}	
								} // if closing Added on 10-02-2023	
								else
								{
									$logData = '3. PPS Details Save No pps detail record found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", getRecordsSQL - " . $getRecords . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
								}
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_catch.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
							}				
						}
						else
						{
							$logData = '3. PPS Details Save New id, year not found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
						}
					}
					else
					{
						$logData = '3. PPS Details Save No row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_row_array - " . print_r($pps_row_array,true) . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
					}
				}
			}
			else
			{
				$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data_array - " . print_r($pps_new_data_array,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
			}
		}
		else
		{
			$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			//print_r($pps_new_data_array);
			if(count($pps_existing_data_array) > 0)
			{
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count((array)$existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						//print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							try
							{
								$year = $existing_pps_row_array->tax_period;
								
								if($year > 0)
								{
									$sql2 = "UPDATE ppsd_ppsdetail set ";
									
									if($existing_pps_row_array->default_bal != '')
									{
										$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
										
										$sql2 .= " balance=" . floatval($bal) . ", ";
									}
																
									if(isset($existing_pps_row_array->rep_wage) && $existing_pps_row_array->rep_wage != '')
									{
										$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
									}
									if(isset($existing_pps_row_array->rep_se) && $existing_pps_row_array->rep_se != '')
									{
										$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
									}
									if(isset($existing_pps_row_array->rep_other) && $existing_pps_row_array->rep_other != '')
									{
										$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
									}
									if(isset($existing_pps_row_array->gross_month_inc) && $existing_pps_row_array->gross_month_inc != '')
									{
										$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
									}
									if(isset($existing_pps_row_array->tax_withheld) && $existing_pps_row_array->tax_withheld != '')
									{
										$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
									}							
									if(isset($existing_pps_row_array->filing_stat) && $existing_pps_row_array->filing_stat != '')
									{
										$filing_stat = $existing_pps_row_array->filing_stat;
										$sql2 .= " filing_status='$filing_stat', ";
									}
									if(isset($existing_pps_row_array->account_stat) && $existing_pps_row_array->account_stat != '')
									{
										$account_stat = $existing_pps_row_array->account_stat;
										$sql2 .= " account_status='$account_stat', ";
									}
									if(isset($existing_pps_row_array->ro) && $existing_pps_row_array->ro != '')
									{
										$ro = $existing_pps_row_array->ro;
										$sql2 .= " ro_recommended='$ro', ";
									}
									if(isset($existing_pps_row_array->fta_eligible) && $existing_pps_row_array->fta_eligible != '')
									{
										$fta_eligible = $existing_pps_row_array->fta_eligible;
										$sql2 .= " fta_eligible='$fta_eligible', ";
									}
									if(isset($existing_pps_row_array->liens) && $existing_pps_row_array->liens != '')
									{
										$liens = $existing_pps_row_array->liens;
										$sql2 .= " liens='$liens', ";
									}
									$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

									//print '<br>UPDATE<br>' . $sql2 ;
								
									$query2 = $this->db_sugar->query($sql2);

									
									//file_put_contents('save_pps_sugar_detailsE1.txt',  $caseid . ' - ' . $sql2 . PHP_EOL , FILE_APPEND);
									if($query2)
									{
										$logData = '6. PPS Details Save Existing row Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details');

										$csed = (isset($existing_pps_row_array->csed))?$existing_pps_row_array->csed:NULL;
										$tin = (isset($existing_pps_row_array->tin))?$existing_pps_row_array->tin: NULL;
										$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
										
										$sql_up .= " csed_date_c='$csed' ";						
										$sql_up .= " , tin_c='$tin' ";
										if(isset($existing_pps_row_array->balance_quarter)){			
											$sql_up .= " , balance_quarter_c='$existing_pps_row_array->balance_quarter' ";
										}

										if(isset($existing_pps_row_array->accrued_assessed)){			
											$sql_up .= " , accrued_assessed_c='$existing_pps_row_array->accrued_assessed' ";
										}

										if(isset($existing_pps_row_array->levy)){			
											$sql_up .= " , levy_garnishment_c='$existing_pps_row_array->levy' ";
										}

										if(isset($existing_pps_row_array->resolution)){			
											$sql_up .= " , resolution_c='$existing_pps_row_array->resolution' ";
										}

										if(isset($existing_pps_row_array->resolution_years)){			
											$sql_up .= " , resolution_years_c='$existing_pps_row_array->resolution_years' ";
										}

										if(isset($existing_pps_row_array->monthly_due_date)){			
											$sql_up .= " , monthly_due_date_c='$existing_pps_row_array->monthly_due_date' ";
										}

										if(isset($existing_pps_row_array->last_payment_date)){			
											$sql_up .= " , last_payment_date_c='$existing_pps_row_array->last_payment_date' ";
										}

										if(isset($existing_pps_row_array->default_date)){			
											$sql_up .= " , default_date_c='$existing_pps_row_array->default_date' ";
										}

										if(isset($existing_pps_row_array->monthly_payment_amount)){			
											$sql_up .= " , monthly_payment_amount_c='$existing_pps_row_array->monthly_payment_amount' ";
										}

										if(isset($existing_pps_row_array->resolution_date_entered)){			
											$sql_up .= " , resolution_date_entered_c='$existing_pps_row_array->resolution_date_entered' ";
										}

										if(isset($existing_pps_row_array->seperate_assesment)){			
											$sql_up .= " , seperate_assesment_c='$existing_pps_row_array->seperate_assesment' ";
										}

										// added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										if(isset($existing_pps_row_array->multiple_csed) ){			
											$sql_up .= " , multiple_csed_c='$existing_pps_row_array->multiple_csed' ";
										}//end added on 26-03-2024	

										if(isset($existing_pps_row_array->credit_etps)){			
											$sql_up .= " , credit_etps_c='$existing_pps_row_array->credit_etps' ";
										}
										
										$sql_up .= " WHERE id_c='$id' ";      

										//print ' ------------------- ' . $sql_up ;
										
										$query3 = $this->db_sugar->query($sql_up);

										if($query3)
										{
											$logData = '6. PPS Details Save Existing row Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '6. PPS Details Save Existing row Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										}
										//file_put_contents('save_pps_sugar_detailsE2.txt',  $caseid . ' - ' . $sql_up . PHP_EOL, FILE_APPEND);
									}
									else
									{
										$error = 3;
										$err =  $this->db_sugar->error();
										$logData = '6. PPS Details Save Existing row Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
										//print_r($err);
									}
								}
								else
								{
									$logData = '6. PPS Details Save Year negative CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year  . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
								}								
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_catch2.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL , FILE_APPEND );
							}
						}
						else
						{
							$logData = '6. PPS Details Save No Id found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id  . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
						}						
					}
					else
					{
						$logData = '6. PPS Details Save No Existing row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", existing_pps_row_array - " . print_r($existing_pps_row_array,true)  . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
					}
				}
			}
			else
			{
				$logData = '6. PPS Details Save No Existing record CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_existing_data_array - " . print_r($pps_existing_data_array,true)  . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details');
			}
		}
		else
		{	
			$logData = '2. PPS Details Save No Old Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details');		
			return 0;
		}
	} //end added on 07-10-2022
	
	//Added on 13-09-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	public function save_pps_sugar_details_premium($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		//file_put_contents('save_pps_sugar_details1.txt', $caseid .', ' . $ppsid .', ' .  $pps_new_data .', ' .  $pps_existing_data . PHP_EOL, FILE_APPEND);
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		session_write_close();

		$logData = '1. PPS Details Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');

		//print $ppsid
		//$idarr = json_decode($id_data);		
		//print $pps_new_data;
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			//print_r($pps_new_data_array);
			//return 1;
			if(count((array)$pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print '----------------------';
					if(count((array)$pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						
						if($newid != '' &&  $year > 0)
						{
							try	
							{						
								$name = $year .' - '. 'Premium';
								
								if($pps_row_array->default_bal != '')
								{
									$bal1 = str_replace(',', '', $pps_row_array->default_bal);
								}
								else
								{
									$bal1 = 0;
								}
								
								//Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
								$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
								INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
								INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
								WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
								AND tax_period = '".$year."'
								AND name = '".$name."'
								AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
								AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 0;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

					
								$dataRecords = $this->db_sugar->query($getRecords);
								$resRecords = $dataRecords->result();
											

								if($resRecords[0]->recCount == 0)
								{ // end  Added on 10-02-2023
									$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
									
									//print $sql1 .'<br>';
													
									$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, (isset($pps_row_array->rep_wage))?$pps_row_array->rep_wage:NULL, (isset($pps_row_array->rep_se))?$pps_row_array->rep_se:NULL, (isset($pps_row_array->rep_other))?$pps_row_array->rep_other:NULL, (isset($pps_row_array->gross_month_inc))?$pps_row_array->gross_month_inc:NULL, (isset($pps_row_array->tax_withheld))?$pps_row_array->tax_withheld:NULL, (isset($pps_row_array->filing_stat))?$pps_row_array->filing_stat:NULL, (isset($pps_row_array->account_stat))?$pps_row_array->account_stat:NUll, (isset($pps_row_array->ro))?$pps_row_array->ro:NULL, (isset($pps_row_array->fta_eligible))?$pps_row_array->fta_eligible:NULL, (isset($pps_row_array->liens))?$pps_row_array->liens:NULL, $user_id)); //$rec[12], 
										
									//file_put_contents('save_pps_sugar_details2.txt', $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);		
									if($query1)
									{ 
										$logData = '3. PPS Details Save pps detail Main Create CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');

										$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`,`balance_quarter_c`,`type_pps_c`,`accrued_assessed_c`,`levy_garnishment_c`,`resolution_c`,`resolution_years_c`,`monthly_payment_amount_c`,`monthly_due_date_c`,`last_payment_date_c`,`default_date_c`,`resolution_date_entered_c`,`seperate_assesment_c`,`multiple_csed_c`,`credit_etps_c`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)"; //`csed`, added multiple_csed_c on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
									
										//print $sql_csed .'<br>';
											
										$query_csed = $this->db_sugar->query($sql_csed, array($newid,  (isset($pps_row_array->csed))?$pps_row_array->csed:NULL, (isset($pps_row_array->tin))?$pps_row_array->tin:NULL, (isset($pps_row_array->defaultba2))?$pps_row_array->defaultba2:NULL, (isset($pps_row_array->type_pps))?$pps_row_array->type_pps:'', (isset($pps_row_array->accrued_assessed))?$pps_row_array->accrued_assessed:NULL, (isset($pps_row_array->levy))?$pps_row_array->levy:NULL,(isset($pps_row_array->resolution))?$pps_row_array->resolution:NULL,(isset($pps_row_array->resolution_years))?$pps_row_array->resolution_years:NULL,(isset($pps_row_array->monthly_payment_amount))?$pps_row_array->monthly_payment_amount:NULL,(isset($pps_row_array->monthly_due_date))?$pps_row_array->monthly_due_date:NULL,(isset($pps_row_array->last_payment_date))?$pps_row_array->last_payment_date:NULL,(isset($pps_row_array->default_date))?$pps_row_array->default_date:NULL,(isset($pps_row_array->resolution_date_entered))?$pps_row_array->resolution_date_entered:NULL,(isset($pps_row_array->seperate_assesment))?$pps_row_array->seperate_assesment:NULL,(isset($pps_row_array->multiple_csed))?$pps_row_array->multiple_csed:NULL,(isset($pps_row_array->credit_etps))?$pps_row_array->credit_etps:NULL));  // Updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										//print $rec[12] . '  -- ' . $query_csed .'<br>';

										if($query_csed)
										{
											$logData = '4. PPS Details Save pps detail Cstm Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid .  ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '4. PPS Details Save pps detail Cstm Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');									
										}
										//file_put_contents('save_pps_sugar_details3.txt', $caseid . ' - ' .  $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										$newid2 = $this->gen_uuid();
										
										$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
									
										//print $sql_rel ;
											
										$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
										//file_put_contents('save_pps_sugar_details4.txt',  $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										if($query_rel)
										{
											//$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										}
										else
										{
											$error = 1;	
											$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');									
										}
									}
									else
									{
										$error = 2;
										$err =  $this->db_sugar->error();
										$logData = '3. PPS Details Save pps detail Main Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										//print_r($err);
									}	
								} // if closing Added on 10-02-2023	
								else
								{
									$logData = '3. PPS Details Save No pps detail record found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", getRecordsSQL - " . $getRecords . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
								}
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_premium_catch.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
							}				
						}
						else
						{
							$logData = '3. PPS Details Save New id, year not found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
						}
					}
					else
					{
						$logData = '3. PPS Details Save No row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_row_array - " . print_r($pps_row_array,true) . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
					}
				}
			}
			else
			{
				$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data_array - " . print_r($pps_new_data_array,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
			}
		}
		else
		{
			$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			//print_r($pps_new_data_array);
			if(count($pps_existing_data_array) > 0)
			{
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count((array)$existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						//print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							try
							{
								$year = $existing_pps_row_array->tax_period;
								
								if($year > 0)
								{
									$sql2 = "UPDATE ppsd_ppsdetail set ";
									
									if($existing_pps_row_array->default_bal != '')
									{
										$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
										
										$sql2 .= " balance=" . floatval($bal) . ", ";
									}
																
									if(isset($existing_pps_row_array->rep_wage) && $existing_pps_row_array->rep_wage != '')
									{
										$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
									}
									if(isset($existing_pps_row_array->rep_se) && $existing_pps_row_array->rep_se != '')
									{
										$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
									}
									if(isset($existing_pps_row_array->rep_other) && $existing_pps_row_array->rep_other != '')
									{
										$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
									}
									if(isset($existing_pps_row_array->gross_month_inc) && $existing_pps_row_array->gross_month_inc != '')
									{
										$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
									}
									if(isset($existing_pps_row_array->tax_withheld) && $existing_pps_row_array->tax_withheld != '')
									{
										$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
									}							
									if(isset($existing_pps_row_array->filing_stat) && $existing_pps_row_array->filing_stat != '')
									{
										$filing_stat = $existing_pps_row_array->filing_stat;
										$sql2 .= " filing_status='$filing_stat', ";
									}
									if(isset($existing_pps_row_array->account_stat) && $existing_pps_row_array->account_stat != '')
									{
										$account_stat = $existing_pps_row_array->account_stat;
										$sql2 .= " account_status='$account_stat', ";
									}
									if(isset($existing_pps_row_array->ro) && $existing_pps_row_array->ro != '')
									{
										$ro = $existing_pps_row_array->ro;
										$sql2 .= " ro_recommended='$ro', ";
									}
									if(isset($existing_pps_row_array->fta_eligible) && $existing_pps_row_array->fta_eligible != '')
									{
										$fta_eligible = $existing_pps_row_array->fta_eligible;
										$sql2 .= " fta_eligible='$fta_eligible', ";
									}
									if(isset($existing_pps_row_array->liens) && $existing_pps_row_array->liens != '')
									{
										$liens = $existing_pps_row_array->liens;
										$sql2 .= " liens='$liens', ";
									}
									$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

									//print '<br>UPDATE<br>' . $sql2 ;
								
									$query2 = $this->db_sugar->query($sql2);

									
									//file_put_contents('save_pps_sugar_detailsE1.txt',  $caseid . ' - ' . $sql2 . PHP_EOL , FILE_APPEND);
									if($query2)
									{
										$logData = '6. PPS Details Save Existing row Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');

										$csed = (isset($existing_pps_row_array->csed))?$existing_pps_row_array->csed:NULL;
										$tin = (isset($existing_pps_row_array->tin))?$existing_pps_row_array->tin: NULL;
										$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
										
										$sql_up .= " csed_date_c='$csed' ";						
										$sql_up .= " , tin_c='$tin' ";
										if(isset($existing_pps_row_array->balance_quarter)){			
											$sql_up .= " , balance_quarter_c='$existing_pps_row_array->balance_quarter' ";
										}

										if(isset($existing_pps_row_array->accrued_assessed)){			
											$sql_up .= " , accrued_assessed_c='$existing_pps_row_array->accrued_assessed' ";
										}

										if(isset($existing_pps_row_array->levy)){			
											$sql_up .= " , levy_garnishment_c='$existing_pps_row_array->levy' ";
										}

										if(isset($existing_pps_row_array->resolution)){			
											$sql_up .= " , resolution_c='$existing_pps_row_array->resolution' ";
										}

										if(isset($existing_pps_row_array->resolution_years)){			
											$sql_up .= " , resolution_years_c='$existing_pps_row_array->resolution_years' ";
										}

										if(isset($existing_pps_row_array->monthly_due_date)){			
											$sql_up .= " , monthly_due_date_c='$existing_pps_row_array->monthly_due_date' ";
										}

										if(isset($existing_pps_row_array->last_payment_date)){			
											$sql_up .= " , last_payment_date_c='$existing_pps_row_array->last_payment_date' ";
										}

										if(isset($existing_pps_row_array->default_date)){			
											$sql_up .= " , default_date_c='$existing_pps_row_array->default_date' ";
										}

										if(isset($existing_pps_row_array->monthly_payment_amount)){			
											$sql_up .= " , monthly_payment_amount_c='$existing_pps_row_array->monthly_payment_amount' ";
										}

										if(isset($existing_pps_row_array->resolution_date_entered)){			
											$sql_up .= " , resolution_date_entered_c='$existing_pps_row_array->resolution_date_entered' ";
										}

										if(isset($existing_pps_row_array->seperate_assesment)){			
											$sql_up .= " , seperate_assesment_c='$existing_pps_row_array->seperate_assesment' ";
										}

										// added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										if(isset($existing_pps_row_array->multiple_csed) ){			
											$sql_up .= " , multiple_csed_c='$existing_pps_row_array->multiple_csed' ";
										}//end added on 26-03-2024	

										if(isset($existing_pps_row_array->credit_etps)){			
											$sql_up .= " , credit_etps_c='$existing_pps_row_array->credit_etps' ";
										}
										
										$sql_up .= " WHERE id_c='$id' ";      

										//print ' ------------------- ' . $sql_up ;
										
										$query3 = $this->db_sugar->query($sql_up);

										if($query3)
										{
											$logData = '6. PPS Details Save Existing row Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '6. PPS Details Save Existing row Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										}
										//file_put_contents('save_pps_sugar_detailsE2.txt',  $caseid . ' - ' . $sql_up . PHP_EOL, FILE_APPEND);
									}
									else
									{
										$error = 3;
										$err =  $this->db_sugar->error();
										$logData = '6. PPS Details Save Existing row Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
										//print_r($err);
									}
								}
								else
								{
									$logData = '6. PPS Details Save Year negative CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year  . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
								}								
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_premium_catch2.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL , FILE_APPEND );
							}
						}
						else
						{
							$logData = '6. PPS Details Save No Id found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id  . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
						}						
					}
					else
					{
						$logData = '6. PPS Details Save No Existing row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", existing_pps_row_array - " . print_r($existing_pps_row_array,true)  . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
					}
				}
			}
			else
			{
				$logData = '6. PPS Details Save No Existing record CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_existing_data_array - " . print_r($pps_existing_data_array,true)  . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');
			}
		}
		else
		{	
			$logData = '2. PPS Details Save No Old Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium');		
			return 0;
		}
	} //end added on 07-10-2022

	public function save_pps_sugar_details_premium_spouse($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		//file_put_contents('save_pps_sugar_details1.txt', $caseid .', ' . $ppsid .', ' .  $pps_new_data .', ' .  $pps_existing_data . PHP_EOL, FILE_APPEND);
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		session_write_close();

		$logData = '1. PPS Details Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');

		//print $ppsid
		//$idarr = json_decode($id_data);		
		//print $pps_new_data;
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			//print_r($pps_new_data_array);
			//return 1;
			if(count((array)$pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print '----------------------';
					if(count((array)$pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						
						if($newid != '' &&  $year > 0)
						{
							try	
							{						
								$name = $year .' - '. 'Premium';

								
								if($pps_row_array->default_bal != '')
								{
									$bal1 = str_replace(',', '', $pps_row_array->default_bal);
								}
								else
								{
									$bal1 = 0;
								}
								
								//Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
								$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
								INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
								INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
								WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
								AND tax_period = '".$year."'
								AND name = '".$name."'
								AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
								AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 1;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

					
								$dataRecords = $this->db_sugar->query($getRecords);
								$resRecords = $dataRecords->result();
											

								if($resRecords[0]->recCount == 0)
								{ // end  Added on 10-02-2023
									$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
									
									//print $sql1 .'<br>';
													
									$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, (isset($pps_row_array->rep_wage))?$pps_row_array->rep_wage:NULL, (isset($pps_row_array->rep_se))?$pps_row_array->rep_se:NULL, (isset($pps_row_array->rep_other))?$pps_row_array->rep_other:NULL, (isset($pps_row_array->gross_month_inc))?$pps_row_array->gross_month_inc:NULL, (isset($pps_row_array->tax_withheld))?$pps_row_array->tax_withheld:NULL, (isset($pps_row_array->filing_stat))?$pps_row_array->filing_stat:NULL, (isset($pps_row_array->account_stat))?$pps_row_array->account_stat:NUll, (isset($pps_row_array->ro))?$pps_row_array->ro:NULL, (isset($pps_row_array->fta_eligible))?$pps_row_array->fta_eligible:NULL, (isset($pps_row_array->liens))?$pps_row_array->liens:NULL, $user_id)); //$rec[12], 
										
									//file_put_contents('save_pps_sugar_details2.txt', $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);		
									if($query1)
									{ 
										$logData = '3. PPS Details Save pps detail Main Create CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');

										$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`,`balance_quarter_c`,`type_pps_c`,`accrued_assessed_c`,`levy_garnishment_c`,`resolution_c`,`resolution_years_c`,`monthly_payment_amount_c`,`monthly_due_date_c`,`last_payment_date_c`,`default_date_c`,`resolution_date_entered_c`,`seperate_assesment_c`,`multiple_csed_c`,`credit_etps_c`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)"; //`csed`, added multiple_csed_c on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
									
										//print $sql_csed .'<br>';
											
										$query_csed = $this->db_sugar->query($sql_csed, array($newid,  (isset($pps_row_array->csed))?$pps_row_array->csed:NULL, (isset($pps_row_array->tin))?$pps_row_array->tin:NULL, (isset($pps_row_array->defaultba2))?$pps_row_array->defaultba2:NULL, (isset($pps_row_array->type_pps))?$pps_row_array->type_pps:'', (isset($pps_row_array->accrued_assessed))?$pps_row_array->accrued_assessed:NULL, (isset($pps_row_array->levy))?$pps_row_array->levy:NULL,(isset($pps_row_array->resolution))?$pps_row_array->resolution:NULL,(isset($pps_row_array->resolution_years))?$pps_row_array->resolution_years:NULL,(isset($pps_row_array->monthly_payment_amount))?$pps_row_array->monthly_payment_amount:NULL,(isset($pps_row_array->monthly_due_date))?$pps_row_array->monthly_due_date:NULL,(isset($pps_row_array->last_payment_date))?$pps_row_array->last_payment_date:NULL,(isset($pps_row_array->default_date))?$pps_row_array->default_date:NULL,(isset($pps_row_array->resolution_date_entered))?$pps_row_array->resolution_date_entered:NULL,(isset($pps_row_array->seperate_assesment))?$pps_row_array->seperate_assesment:NULL,(isset($pps_row_array->multiple_csed))?$pps_row_array->multiple_csed:NULL,(isset($pps_row_array->credit_etps))?$pps_row_array->credit_etps:NULL));  // Updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										//print $rec[12] . '  -- ' . $query_csed .'<br>';

										if($query_csed)
										{
											$logData = '4. PPS Details Save pps detail Cstm Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid .  ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '4. PPS Details Save pps detail Cstm Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');									
										}
										//file_put_contents('save_pps_sugar_details3.txt', $caseid . ' - ' .  $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										$newid2 = $this->gen_uuid();
										
										$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
									
										//print $sql_rel ;
											
										$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
										//file_put_contents('save_pps_sugar_details4.txt',  $caseid . ' - ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
										if($query_rel)
										{
											//$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										}
										else
										{
											$error = 1;	
											$err =  $this->db_sugar->error();
											$logData = '5. PPS Details Save pps detail Rel Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');									
										}
									}
									else
									{
										$error = 2;
										$err =  $this->db_sugar->error();
										$logData = '3. PPS Details Save pps detail Main Create Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err" . $err . ", ppsDetailSql - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										//print_r($err);
									}	
								} // if closing Added on 10-02-2023	
								else
								{
									$logData = '3. PPS Details Save No pps detail record found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", getRecordsSQL - " . $getRecords . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
								}
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_premium_spouse_catch.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
							}				
						}
						else
						{
							$logData = '3. PPS Details Save New id, year not found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
						}
					}
					else
					{
						$logData = '3. PPS Details Save No row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_row_array - " . print_r($pps_row_array,true) . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
					}
				}
			}
			else
			{
				$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data_array - " . print_r($pps_new_data_array,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
			}
		}
		else
		{
			$logData = '2. PPS Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			//print_r($pps_new_data_array);
			if(count($pps_existing_data_array) > 0)
			{
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count((array)$existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						//print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							try
							{
								$year = $existing_pps_row_array->tax_period;
								
								if($year > 0)
								{
									$sql2 = "UPDATE ppsd_ppsdetail set ";
									
									if($existing_pps_row_array->default_bal != '')
									{
										$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
										
										$sql2 .= " balance=" . floatval($bal) . ", ";
									}
																
									if(isset($existing_pps_row_array->rep_wage) && $existing_pps_row_array->rep_wage != '')
									{
										$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
									}
									if(isset($existing_pps_row_array->rep_se) && $existing_pps_row_array->rep_se != '')
									{
										$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
									}
									if(isset($existing_pps_row_array->rep_other) && $existing_pps_row_array->rep_other != '')
									{
										$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
									}
									if(isset($existing_pps_row_array->gross_month_inc) && $existing_pps_row_array->gross_month_inc != '')
									{
										$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
									}
									if(isset($existing_pps_row_array->tax_withheld) && $existing_pps_row_array->tax_withheld != '')
									{
										$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
									}							
									if(isset($existing_pps_row_array->filing_stat) && $existing_pps_row_array->filing_stat != '')
									{
										$filing_stat = $existing_pps_row_array->filing_stat;
										$sql2 .= " filing_status='$filing_stat', ";
									}
									if(isset($existing_pps_row_array->account_stat) && $existing_pps_row_array->account_stat != '')
									{
										$account_stat = $existing_pps_row_array->account_stat;
										$sql2 .= " account_status='$account_stat', ";
									}
									if(isset($existing_pps_row_array->ro) && $existing_pps_row_array->ro != '')
									{
										$ro = $existing_pps_row_array->ro;
										$sql2 .= " ro_recommended='$ro', ";
									}
									if(isset($existing_pps_row_array->fta_eligible) && $existing_pps_row_array->fta_eligible != '')
									{
										$fta_eligible = $existing_pps_row_array->fta_eligible;
										$sql2 .= " fta_eligible='$fta_eligible', ";
									}
									if(isset($existing_pps_row_array->liens) && $existing_pps_row_array->liens != '')
									{
										$liens = $existing_pps_row_array->liens;
										$sql2 .= " liens='$liens', ";
									}
									$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

									//print '<br>UPDATE<br>' . $sql2 ;
								
									$query2 = $this->db_sugar->query($sql2);

									
									//file_put_contents('save_pps_sugar_detailsE1.txt',  $caseid . ' - ' . $sql2 . PHP_EOL , FILE_APPEND);
									if($query2)
									{
										$logData = '6. PPS Details Save Existing row Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');

										$csed = (isset($existing_pps_row_array->csed))?$existing_pps_row_array->csed:NULL;
										$tin = (isset($existing_pps_row_array->tin))?$existing_pps_row_array->tin: NULL;
										$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
										
										$sql_up .= " csed_date_c='$csed' ";						
										$sql_up .= " , tin_c='$tin' ";
										if(isset($existing_pps_row_array->balance_quarter)){			
											$sql_up .= " , balance_quarter_c='$existing_pps_row_array->balance_quarter' ";
										}

										if(isset($existing_pps_row_array->accrued_assessed)){			
											$sql_up .= " , accrued_assessed_c='$existing_pps_row_array->accrued_assessed' ";
										}

										if(isset($existing_pps_row_array->levy)){			
											$sql_up .= " , levy_garnishment_c='$existing_pps_row_array->levy' ";
										}

										if(isset($existing_pps_row_array->resolution)){			
											$sql_up .= " , resolution_c='$existing_pps_row_array->resolution' ";
										}

										if(isset($existing_pps_row_array->resolution_years)){			
											$sql_up .= " , resolution_years_c='$existing_pps_row_array->resolution_years' ";
										}

										if(isset($existing_pps_row_array->monthly_due_date)){			
											$sql_up .= " , monthly_due_date_c='$existing_pps_row_array->monthly_due_date' ";
										}

										if(isset($existing_pps_row_array->last_payment_date)){			
											$sql_up .= " , last_payment_date_c='$existing_pps_row_array->last_payment_date' ";
										}

										if(isset($existing_pps_row_array->default_date)){			
											$sql_up .= " , default_date_c='$existing_pps_row_array->default_date' ";
										}

										if(isset($existing_pps_row_array->monthly_payment_amount)){			
											$sql_up .= " , monthly_payment_amount_c='$existing_pps_row_array->monthly_payment_amount' ";
										}

										if(isset($existing_pps_row_array->resolution_date_entered)){			
											$sql_up .= " , resolution_date_entered_c='$existing_pps_row_array->resolution_date_entered' ";
										}

										if(isset($existing_pps_row_array->seperate_assesment)){			
											$sql_up .= " , seperate_assesment_c='$existing_pps_row_array->seperate_assesment' ";
										}

										// added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
										if(isset($existing_pps_row_array->multiple_csed) ){			
											$sql_up .= " , multiple_csed_c='$existing_pps_row_array->multiple_csed' ";
										}//end added on 26-03-2024	

										if(isset($existing_pps_row_array->credit_etps)){			
											$sql_up .= " , credit_etps_c='$existing_pps_row_array->credit_etps' ";
										}
										
										$sql_up .= " WHERE id_c='$id' ";      

										//print ' ------------------- ' . $sql_up ;
										
										$query3 = $this->db_sugar->query($sql_up);

										if($query3)
										{
											$logData = '6. PPS Details Save Existing row Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										}
										else
										{
											$err =  $this->db_sugar->error();
											$logData = '6. PPS Details Save Existing row Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_up - " . $sql_up . PHP_EOL;
											$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										}
										//file_put_contents('save_pps_sugar_detailsE2.txt',  $caseid . ' - ' . $sql_up . PHP_EOL, FILE_APPEND);
									}
									else
									{
										$error = 3;
										$err =  $this->db_sugar->error();
										$logData = '6. PPS Details Save Existing row Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql2 - " . $sql2 . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
										//print_r($err);
									}
								}
								else
								{
									$logData = '6. PPS Details Save Year negative CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year  . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
								}								
							}	
							catch(Exception $e)
							{
								file_put_contents('save_pps_sugar_details_premium_spouse_catch2.txt',  $caseid . ' - ' . $e->getMessage() . PHP_EOL , FILE_APPEND );
							}
						}
						else
						{
							$logData = '6. PPS Details Save No Id found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id  . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
						}						
					}
					else
					{
						$logData = '6. PPS Details Save No Existing row CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", existing_pps_row_array - " . print_r($existing_pps_row_array,true)  . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
					}
				}
			}
			else
			{
				$logData = '6. PPS Details Save No Existing record CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_existing_data_array - " . print_r($pps_existing_data_array,true)  . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');
			}
		}
		else
		{	
			$logData = '2. PPS Details Save No Old Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_sugar_details_premium_spouse');		
			return 0;
		}
	} //end added on 07-10-2022
	
	public function save_pps_spouse_details($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		session_write_close();
	
		
		$logData = '1. PPS Spouse Details Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			// print_r($pps_new_data_array);
			//return 1;
			if(count($pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print '----------------------';
					if(count($pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						
						if($newid != '' &&  $year > 0)
						{							
							$name = $year .' - '. 'Federal Personal';
							
							if($pps_row_array->default_bal != '')
							{
								$bal1 = str_replace(',', '', $pps_row_array->default_bal);
							}
							else
							{
								$bal1 = 0;
							}
								
							//Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
							$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
							INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
							INNER JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida
							WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
							AND tax_period = '".$year."'
							AND name = '".$name."'
							AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
							AND ppsd_ppsdetail.deleted=0 AND inves_pps_cstm.pps_spouse_c = 1;"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

				
							$dataRecords = $this->db_sugar->query($getRecords);
							$resRecords = $dataRecords->result();
										

							if($resRecords[0]->recCount == 0)
							{ // end Added on 10-02-203	
								$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
								
								
												
								$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, (isset($pps_row_array->rep_wage))?$pps_row_array->rep_wage:NULL, (isset($pps_row_array->rep_se))?$pps_row_array->rep_se:NULL, (isset($pps_row_array->rep_other))?$pps_row_array->rep_other:NULL, (isset($pps_row_array->gross_month_inc))?$pps_row_array->gross_month_inc:NULL, (isset($pps_row_array->tax_withheld))?$pps_row_array->tax_withheld:NULL,(isset($pps_row_array->filing_stat))?$pps_row_array->filing_stat:NULL, (isset($pps_row_array->account_stat))?$pps_row_array->account_stat:NULL,(isset($pps_row_array->ro))?$pps_row_array->ro:NULL, (isset($pps_row_array->fta_eligible))?$pps_row_array->fta_eligible:NULL, (isset($pps_row_array->liens))?$pps_row_array->liens:NULL, $user_id)); //$rec[12], 
									
										// print $sql1 .'<br>';
										// print $this->db_sugar->last_query();
										print $query1 ;
								if($query1)
								{ 
									$logData = '3. PPS Spose Details Save pps detail Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_spouse_details');

									$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`,`balance_quarter_c`,`type_pps_c`,`accrued_assessed_c`,`levy_garnishment_c`,`resolution_c`,`resolution_years_c`,`monthly_payment_amount_c`,`monthly_due_date_c`,`last_payment_date_c`,`default_date_c`,`resolution_date_entered_c`,`seperate_assesment_c`,`multiple_csed_c`,`credit_etps_c`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)"; //`csed`, // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator 
								
									// print $sql_csed .'<br>';
										
									$query_csed = $this->db_sugar->query($sql_csed, array($newid,  (isset($pps_row_array->csed))?$pps_row_array->csed:NULL, (isset($pps_row_array->tin))?$pps_row_array->tin:NULL, (isset($pps_row_array->defaultba2))?$pps_row_array->defaultba2:NULL, (isset($pps_row_array->type_pps))?$pps_row_array->type_pps:'', (isset($pps_row_array->accrued_assessed))?$pps_row_array->accrued_assessed:NULL, (isset($pps_row_array->levy))?$pps_row_array->levy:NULL,(isset($pps_row_array->resolution))?$pps_row_array->resolution:NULL,(isset($pps_row_array->resolution_years))?$pps_row_array->resolution_years:NULL,(isset($pps_row_array->monthly_payment_amount))?$pps_row_array->monthly_payment_amount:NULL,(isset($pps_row_array->monthly_due_date))?$pps_row_array->monthly_due_date:NULL,(isset($pps_row_array->last_payment_date))?$pps_row_array->last_payment_date:NULL,(isset($pps_row_array->default_date))?$pps_row_array->default_date:NULL,(isset($pps_row_array->resolution_date_entered))?$pps_row_array->resolution_date_entered:NULL,(isset($pps_row_array->seperate_assesment))?$pps_row_array->seperate_assesment:NULL,(isset($pps_row_array->multiple_csed))?$pps_row_array->multiple_csed:NULL,(isset($pps_row_array->credit_etps))?$pps_row_array->credit_etps:NULL));  // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
									//print $query_csed ;
									//print $this->db_sugar->last_query();
									
									if($query_csed)
									{
										$logData = '3. PPS Spouse Details Save pps detail Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}
									else
									{
										$err =  $this->db_sugar->error();
										$logData = '3. PPS Spouse Details Save pps detail Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}
									$newid2 = $this->gen_uuid();
									
									$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
								
									
										
									$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
									//print $query_rel ;
									
									if($query_rel)
									{
										$logData = '3. PPS Spouse Details Save pps detail Rel Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}
									else
									{
										$error = 1;	
										$err =  $this->db_sugar->error();
										$logData = '3. PPS Spouse Details Save pps detail Rel Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}
								}
								else
								{
									$error = 2;
									$err =  $this->db_sugar->error();
									$logData = '3. PPS Spose Details Save pps detail Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql1 - " . $this->db_sugar->last_query() . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									//print_r($err);
								}	
							} // closing if Added on 10-02-203						
							else
							{
								$logData = '2. PPS Spouse Details Save No record CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", getRecords - " . $getRecords . PHP_EOL;
								$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
							}
						}
						else
						{
							$logData = '2. PPS Spouse Details Save No id or year CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id . ", year - " . $year . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
						}
					}
					else
					{
						$logData = '2. PPS Spouse Details Save No row array CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_row_array - " . print_r($pps_row_array,true) . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
					}
				}
			}
			else
			{
				$logData = '2. PPS Spouse Details Save No Data array CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data_array - " . print_r($pps_new_data_array,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
			}
		}
		else
		{
			$logData = '2. PPS Spouse Details Save No New Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			
			if(count($pps_existing_data_array) > 0)
			{
				
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					// print_r($existing_pps_row_array);
					// print '----------------------';
					if(!empty($existing_pps_row_array))//count($existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						// print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							$year = $existing_pps_row_array->tax_period;
							
							if($year > 0)
							{
								$sql2 = "UPDATE ppsd_ppsdetail set ";
								
								if($existing_pps_row_array->default_bal != '')
								{
									$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
									
									$sql2 .= " balance=" . floatval($bal) . ", ";
								}
															
								if(isset($existing_pps_row_array->rep_wage) && $existing_pps_row_array->rep_wage != '')
								{
									$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
								}
								if(isset($existing_pps_row_array->rep_se) && $existing_pps_row_array->rep_se != '')
								{
									$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
								}
								if(isset($existing_pps_row_array->rep_other) && $existing_pps_row_array->rep_other != '')
								{
									$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
								}
								if(isset($existing_pps_row_array->gross_month_inc) && $existing_pps_row_array->gross_month_inc != '')
								{
									$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
								}
								if(isset($existing_pps_row_array->tax_withheld) && $existing_pps_row_array->tax_withheld != '')
								{
									$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
								}							
								if(isset($existing_pps_row_array->filing_stat) && $existing_pps_row_array->filing_stat != '')
								{
									$filing_stat = $existing_pps_row_array->filing_stat;
									$sql2 .= " filing_status='$filing_stat', ";
								}
								if(isset($existing_pps_row_array->account_stat) && $existing_pps_row_array->account_stat != '')
								{
									$account_stat = $existing_pps_row_array->account_stat;
									$sql2 .= " account_status='$account_stat', ";
								}
								if(isset($existing_pps_row_array->ro) && $existing_pps_row_array->ro != '')
								{
									$ro = $existing_pps_row_array->ro;
									$sql2 .= " ro_recommended='$ro', ";
								}
								if(isset($existing_pps_row_array->fta_eligible) && $existing_pps_row_array->fta_eligible != '')
								{
									$fta_eligible = $existing_pps_row_array->fta_eligible;
									$sql2 .= " fta_eligible='$fta_eligible', ";
								}
								if(isset($existing_pps_row_array->liens) && $existing_pps_row_array->liens != '')
								{
									$liens = $existing_pps_row_array->liens;
									$sql2 .= " liens='$liens', ";
								}
								$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

								// print '<br>UPDATE<br>' . $sql2 ;
							
								$query2 = $this->db_sugar->query($sql2);
								// print $sql2;
							
								if($query2)
								{
									$logData = '6. PPS Spouse Details Save pps detail Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql2 - " . $sql2 . PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_spouse_details');

									if(isset($existing_pps_row_array->csed) && $existing_pps_row_array->csed != '')
									{
										$csed = $existing_pps_row_array->csed;
									}
									else
									{
										$csed = NULL;
									}
									// print "--<csed-->". $csed;
									$tin = (isset($existing_pps_row_array->tin))?$existing_pps_row_array->tin: NULL;
									$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
									
									$sql_up .= " csed_date_c='$csed' ";						
									$sql_up .= " , tin_c='$tin' ";

									// print "5883---".$existing_pps_row_array->defaultba2;
									if(isset($existing_pps_row_array->defaultba2)){			
										$sql_up .= " , balance_quarter_c='$existing_pps_row_array->defaultba2' ";
									}

									if($existing_pps_row_array->accrued_assessed){			
										$sql_up .= " , accrued_assessed_c='$existing_pps_row_array->accrued_assessed' ";
									}

									if(isset($existing_pps_row_array->levy) &&$existing_pps_row_array->levy){			
										$sql_up .= " , levy_garnishment_c='$existing_pps_row_array->levy' ";
									}

									if(isset($existing_pps_row_array->resolution)){			
										$sql_up .= " , resolution_c='$existing_pps_row_array->resolution' ";
									}

									if(isset($existing_pps_row_array->resolution_years)){			
										$sql_up .= " , resolution_years_c='$existing_pps_row_array->resolution_years' ";
									}

									if(isset($existing_pps_row_array->monthly_due_date)){			
										$sql_up .= " , monthly_due_date_c='$existing_pps_row_array->monthly_due_date' ";
									}

									if(isset($existing_pps_row_array->last_payment_date)){			
										$sql_up .= " , last_payment_date_c='$existing_pps_row_array->last_payment_date' ";
									}

									if(isset($existing_pps_row_array->default_date)){			
										$sql_up .= " , default_date_c='$existing_pps_row_array->default_date' ";
									}

									if(isset($existing_pps_row_array->monthly_payment_amount)){			
										$sql_up .= " , monthly_payment_amount_c='$existing_pps_row_array->monthly_payment_amount' ";
									}

									if(isset($existing_pps_row_array->resolution_date_entered)){			
										$sql_up .= " , resolution_date_entered_c='$existing_pps_row_array->resolution_date_entered' ";
									}

									if(isset($existing_pps_row_array->seperate_assesment) && $existing_pps_row_array->seperate_assesment){			
										$sql_up .= " , seperate_assesment_c='$existing_pps_row_array->seperate_assesment' ";
									}
									//added on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
									if(isset($existing_pps_row_array->multiple_csed) ){			
										$sql_up .= " , multiple_csed_c='$existing_pps_row_array->multiple_csed' ";
									}//end added on 26-03-2024

									if(isset($existing_pps_row_array->credit_etps) && $existing_pps_row_array->credit_etps){			
										$sql_up .= " , credit_etps_c='$existing_pps_row_array->credit_etps' ";
									}
									
									$sql_up .= " WHERE id_c='$id' ";      

									//print ' ------------------- ' . $sql_up ;
									
									$query3 = $this->db_sugar->query($sql_up);
									//echo "<pre>";
									//print_r($query3);
									if($query3)
									{
										$logData = '6. PPS Spouse Details Save pps detail Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_up - " . $sql_up . PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}
									else
									{
										$error = 1;	
										$err =  $this->db_sugar->error();
										$logData = '6. PPS Spouse Details Save pps detail Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_up - " . $sql_up. PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									}

								}
								else
								{
									$error = 3;
									$err =  $this->db_sugar->error();
									$logData = '6. PPS Spouse Details Save pps detail Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql2 - " . $sql2. PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_spouse_details');
									//print_r($err);
								}
							}
							else
							{
								$logData = '6. PPS Spouse Details Save year negative  CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year . PHP_EOL;
								$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
							}
						}
						else
						{
							$logData = '6. PPS Spouse Details Save No id  CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id . PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
						}						
					}
					else
					{
						$logData = '6. PPS Spouse Details Save No row array CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", existing_pps_row_array - " . print_r($existing_pps_row_array,true) . PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
					}
				}
			}
			else
			{
				$logData = '6. PPS Spouse Details Save No Data array CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_existing_data_array - " . print_r($pps_existing_data_array,true) . PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
			}
		}
		else
		{
			$logData = '6. PPS Spouse Details Save No Existing Data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data . PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_spouse_details');			
			return 0;
		}
	}
	// end added on 03-11-2022
	
	public function save_pps_state_sugar_details($caseid, $ppsid, $pps_new_data, $pps_existing_data)
	{ 
		$pps_arr = array();
		$error = 0;
		$i = 0;
		$user_id = $this->session->userdata('User_id');	 
		session_write_close();
		
		$logData = '1. PPS State Details Save Start CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data .", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');

		//print $ppsid
		//$idarr = json_decode($id_data);		
		//print $pps_new_data;
		
		//For new records
		if($ppsid !='' && $pps_new_data != '')
		{ 	
			$pps_new_data_array = array();
			$pps_new_data_array = explode(';',$pps_new_data);
			//print_r($pps_new_data_array);
			//return 1;
			if(count($pps_new_data_array) > 0)
			{
				foreach($pps_new_data_array as $new_record)
				{
					$pps_row_array = json_decode($new_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count($pps_row_array) > 0) 
					{
						//print $pps_row_array->tax_period;
						$newid = $this->gen_uuid();
						$year = $pps_row_array->tax_period;
						//$year = $pps_row_array->default_bal;
						
						if($newid != '' &&  $year > 0)
						{							
							$name = $year .' - '. 'State Personal';	
							
							if($pps_row_array->default_bal != '')
							{
								$bal1 = str_replace(',', '', $pps_row_array->default_bal);
							}
							else
							{
								$bal1 = 0;
							}

							//Added on 10-02-2023 to check existing records of the ppsid id with the given year and name
							$getRecords = "SELECT count(*) as recCount FROM `ppsd_ppsdetail` 
							INNER JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id
							WHERE inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida = '".$ppsid."'
							AND tax_period = '".$year."'
							AND name = '".$name."'
							AND inves_pps_ppsd_ppsdetail_1_c.deleted=0
							AND ppsd_ppsdetail.deleted=0"; //pps_is = 'd1f1df20-9fd5-11ed-bce4-9eff2f4a401f'

				
							$dataRecords = $this->db_sugar->query($getRecords);
							$resRecords = $dataRecords->result();
										

							if($resRecords[0]->recCount == 0)
							{ // end Added on 10-02-2023
									
								$sql1 = "INSERT INTO ppsd_ppsdetail(`id`, `date_entered`, `date_modified`, `created_by`, `name`, `tax_period`, `balance`, `reported_wage`, `reported_se`, `reported_other`, `gross_monthly_income`, `taxes_withheld`, `filing_status`, `account_status`, `ro_recommended`, `fta_eligible`, `liens`, modified_user_id, team_id, team_set_id) VALUES (?, now(), now(), ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?,?,?,1,1)"; //`csed`, 
								
								//print $sql1 .'<br>';
												
								$query1 = $this->db_sugar->query($sql1, array($newid, $user_id, $name, $year, $bal1, $pps_row_array->rep_wage, $pps_row_array->rep_se, $pps_row_array->rep_other, $pps_row_array->gross_month_inc, $pps_row_array->tax_withheld, $pps_row_array->filing_stat, $pps_row_array->account_stat, $pps_row_array->ro, $pps_row_array->fta_eligible, $pps_row_array->liens, $user_id)); //$rec[12], 
									
										
								if($query1)
								{ 
									$logData = '3. PPS State Details Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql1 - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	

									$sql_csed = "INSERT INTO ppsd_ppsdetail_cstm(`id_c`, `csed_date_c`,`tin_c`) VALUES (?, ?, ?)"; //`csed`, 
								
									//print $sql_csed .'<br>';
										
									$query_csed = $this->db_sugar->query($sql_csed, array($newid,  $pps_row_array->csed, $pps_row_array->tin));  
								
									if($query_csed)
									{
										$logData = '4. PPS State Details Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_csed - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									}
									else
									{
										$err =  $this->db_sugar->error();
										$logData = '4. PPS State Details Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_csed - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									}
									//print $rec[12] . '  -- ' . $query_csed .'<br>';
									
									$newid2 = $this->gen_uuid();
									
									$sql_rel = "INSERT INTO inves_pps_ppsd_ppsdetail_1_c(`id`, `date_modified`, `inves_pps_ppsd_ppsdetail_1inves_pps_ida`, `inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb`) VALUES (?, now(), ?, ?)";
								
									//print $sql_rel ;
										
									$query_rel = $this->db_sugar->query($sql_rel, array($newid2, $ppsid, $newid));
									
									if($query_rel)
									{
										$logData = '5. PPS State Details Rel Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_rel - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
									}
									else
									{
										$error = 1;		
										$err =  $this->db_sugar->error();
										$logData = '4. PPS State Details Rel Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_rel - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');									
									}
								}
								else
								{
									$error = 2;
									$err =  $this->db_sugar->error();
									$logData = '3. PPS State Details Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql1 - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									//print_r($err);
								}		
							} // if closing added on 10-02-2023
							else
							{
								$logData = '2. PPS State Details No record found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", getRecords - " . $getRecords .", UserId - " . $user_id .  PHP_EOL;
								$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
							}
						}
						else
						{
							$logData = '2. PPS State Details No id/year found CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year .", UserId - " . $user_id .  PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
						}
					}
					else
					{
						$logData = '2. PPS State Details No row data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_row_array - " . print_r($pps_row_array,true) .", UserId - " . $user_id .  PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
					}
				}
			}
			else
			{
				$logData = '2. PPS State Details No data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data_array - " . print_r($pps_new_data_array,true) .", UserId - " . $user_id .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
			}
		}
		else
		{
			$logData = '2. PPS State Details No new data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data .", UserId - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
		}
		//die();
		//For existing records
		if($ppsid !='' && $pps_existing_data != '')
		{ 			
			$pps_existing_data_array = array();
			$existing_pps_row_array = array();
			$pps_existing_data_array = explode(';',$pps_existing_data);
			//print_r($pps_new_data_array);
			if(count($pps_existing_data_array) > 0)
			{
				foreach($pps_existing_data_array as $existing_record)
				{
					$existing_pps_row_array = json_decode($existing_record);
					//print_r($pps_row_array);
					//print '----------------------';
					if(count($existing_pps_row_array) > 0) 
					{						
						$id = $existing_pps_row_array->id;						
						//print $id;
						if($id != '' && $id != null && $id != 'undefined')
						{	
							$year = $existing_pps_row_array->tax_period;
							
							if($year > 0)
							{
								$sql2 = "UPDATE ppsd_ppsdetail set ";
								
								if($existing_pps_row_array->default_bal != '')
								{
									$bal = str_replace(',', '', $existing_pps_row_array->default_bal);
									
									$sql2 .= " balance=" . floatval($bal) . ", ";
								}
															
								if($existing_pps_row_array->rep_wage != '')
								{
									$sql2 .= " reported_wage=" . floatval($existing_pps_row_array->rep_wage) . ", ";
								}
								if($existing_pps_row_array->rep_se != '')
								{
									$sql2 .= " reported_se=" . floatval($existing_pps_row_array->rep_se) . ", ";
								}
								if($existing_pps_row_array->rep_other != '')
								{
									$sql2 .= " reported_other=" . floatval($existing_pps_row_array->rep_other) . ", ";
								}
								if($existing_pps_row_array->gross_month_inc != '')
								{
									$sql2 .= "gross_monthly_income=" . floatval($existing_pps_row_array->gross_month_inc) . ", ";
								}
								if($existing_pps_row_array->tax_withheld != '')
								{
									$sql2 .= " taxes_withheld=" . floatval($existing_pps_row_array->tax_withheld) . ", ";
								}							
								if($existing_pps_row_array->filing_stat != '')
								{
									$filing_stat = $existing_pps_row_array->filing_stat;
									$sql2 .= " filing_status='$filing_stat', ";
								}
								if($existing_pps_row_array->account_stat != '')
								{
									$account_stat = $existing_pps_row_array->account_stat;
									$sql2 .= " account_status='$account_stat', ";
								}
								if($existing_pps_row_array->ro != '')
								{
									$ro = $existing_pps_row_array->ro;
									$sql2 .= " ro_recommended='$ro', ";
								}
								if($existing_pps_row_array->fta_eligible != '')
								{
									$fta_eligible = $existing_pps_row_array->fta_eligible;
									$sql2 .= " fta_eligible='$fta_eligible', ";
								}
								if($existing_pps_row_array->liens != '')
								{
									$liens = $existing_pps_row_array->liens;
									$sql2 .= " liens='$liens', ";
								}
								$sql2 .= " date_modified=now(), modified_user_id='$user_id' WHERE id='$id' AND tax_period=$year AND deleted=0";       

								//print '<br>UPDATE<br>' . $sql2 ;
							
								$query2 = $this->db_sugar->query($sql2);
							
								if($query2)
								{
									$logData = '7. PPS State Details Main Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql2 - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	

									$csed = $existing_pps_row_array->csed;
									$tin = $existing_pps_row_array->tin;
									$sql_up = "UPDATE ppsd_ppsdetail_cstm set ";							
									
									$sql_up .= " csed_date_c='$csed' ";						
									$sql_up .= " , tin_c='$tin' ";
									
									$sql_up .= " WHERE id_c='$id' ";      

									//print ' ------------------- ' . $sql_up ;
									
									$query3 = $this->db_sugar->query($sql_up);

									if($query3)
									{
										$logData = '7. PPS State Details Cstm Success CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", sql_up - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									}
									else
									{
										$err =  $this->db_sugar->error();
										$logData = '7. PPS State Details Cstm Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_up - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
										$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									}
								}
								else
								{
									$error = 3;
									$err =  $this->db_sugar->error();
									$logData = '6. PPS State Details Main Error CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql2 - " . $this->db_sugar->last_query() .", UserId - " . $user_id .  PHP_EOL;
									$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');	
									//print_r($err);
								}
							}
							else
							{
								$logData = '2. PPS State Details No year CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", year - " . $year .", UserId - " . $user_id .  PHP_EOL;
								$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
							}
						}
						else
						{
							$logData = '2. PPS State Details No id CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", id - " . $id .", UserId - " . $user_id .  PHP_EOL;
							$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
						}						
					}
					else
					{
						$logData = '2. PPS State Details No existing row data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", existing_pps_row_array - " . print_r($existing_pps_row_array,true) .", UserId - " . $user_id .  PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
					}
				}
			}
			else
			{
				$logData = '2. PPS State Details No existing data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_existing_data_array - " . print_r($pps_existing_data_array,true) .", UserId - " . $user_id .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');
			}
		}
		else
		{
			$logData = '2. PPS State Details No existing data CaseID - ' . $caseid . ", PPS Id - " . $ppsid . ", pps_new_data - " . $pps_new_data . ", pps_existing_data - " . $pps_existing_data .", UserId - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_state_sugar_details');			
			return 0;
		}
	}	
		
	public function delete_pps_rows($caseid, $year, $id)
	{		
		if($caseid != '' && $year != '' && $id != '')
		{
			$user_id = $this->session->userdata('User_id');
			session_write_close();
			
			$sql = "UPDATE ppsd_ppsdetail set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";	
			//print $sql;
			$query = $this->db_sugar->query($sql);
			
			if($query)
			{
				$sql2 = "UPDATE inves_pps_ppsd_ppsdetail_1_c set deleted=1, date_modified=now() WHERE inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb='$id' ";	
				
				$query2 = $this->db_sugar->query($sql2);
			
				return 1 ; 
			}
			else{
				$err =  $this->db_sugar->error();
				//print_r($err);
			}						
		}
		else
			return 0;
	}
	
	public function delete_pps_row_state_personal($caseid, $year, $id)
	{		
		if($caseid != '' && $year != '' && $id != '')
		{
			$user_id = $this->session->userdata('User_id');
			session_write_close();
			
			$sql = "UPDATE ppsd_ppsdetail set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";	
			//print $sql;
			$query = $this->db_sugar->query($sql);
			
			if($query)
			{
				$sql2 = "UPDATE inves_pps_ppsd_ppsdetail_1_c set deleted=1, date_modified=now() WHERE inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb='$id' ";	
				//print $sql2;
				$query2 = $this->db_sugar->query($sql2);
			
				return 1 ; 
			}
			else{
				$err =  $this->db_sugar->error();
				//print_r($err);
			}						
		}
		else
			return 0;
	}
	
	
	/*public function add_note($case_id, $note_txt, $note_type, $uuid)
	{
		$user_id = $this->session->userdata('User_id');	
		
		if($note_type == '')
		{
			$note_type = 'Case Review';
		}
		
		if($case_id != ''){
			
			//get lead id for lead notes
			$this->db_sugar->select('id_c');
			$this->db_sugar->from('leads_cstm');
			$this->db_sugar->join('leads', 'leads.id = leads_cstm.id_c', 'left');
			$this->db_sugar->where('case_id_c', $case_id);	
			$this->db_sugar->where('deleted', 0);			
			
			$data_sugar = $this->db_sugar->get();
			$record_sugar = $data_sugar->result();
			//print_r($record_sugar);
			if(count($record_sugar) > 0){
				
				$sugar_id = $record_sugar[0]->id_c;				
			}
			
			$uuid = $this->gen_uuid();
			
			//print 'sugar_id-' . $sugar_id;
			if($sugar_id != '' && $note_txt != '' && $uuid != ''){
				
				$note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
				$note_txt = htmlentities($note_txt);

				//Insert notes in Leads module
				$sql_sugar = "INSERT INTO notes(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, parent_type, parent_id, assigned_user_id, team_id, team_set_id) VALUES('$uuid', '$note_type', now(), now(), '$user_id', '$user_id', '$note_txt', 0, 'Leads', '$sugar_id', 1, 1, 1)";
				
				$query_sugar = $this->db_sugar->query($sql_sugar);
				//print_r($query_sugar);
				if($query_sugar)
				{
					$sql_sugar1 = "INSERT INTO notes_cstm (id_c, migrated_from_logics_c) VALUES('$uuid',0)";
					
					$query_sugar1 = $this->db_sugar->query($sql_sugar1);
					//print_r($query_sugar);
					if($query_sugar1)
					{
						//return 1 ; 
					}
					else{
					
						$err =  $this->db_sugar->error();
						return 0;
						//print_r($err);
					}
				}
				else{
					
					$err =  $this->db_sugar->error();
					return 0;
					//print_r($err);
				}
				
				//Insert notes in Cases module
				$uuid2 = $this->gen_uuid();
				
				//get cases id for cases notes
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('cases_cstm');
				$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);			
				$this->db_sugar->where('cases.deleted', 0);			
				
				$data_sugar2 = $this->db_sugar->get();
				$record_sugar2 = $data_sugar2->result();
				//print_r($record_sugar2);
				if(count($record_sugar2) > 0){
					
					$sugar_cid = $record_sugar2[0]->id_c;				
				}
			
				if($uuid2 != '' && $sugar_cid != '')
				{
					$sql_sugar2 = "INSERT INTO notes(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, parent_type, parent_id, assigned_user_id, team_id, team_set_id) VALUES('$uuid2', '$note_type', now(), now(), '$user_id', '$user_id', '$note_txt', 0, 'Cases', '$sugar_cid', 1, 1, 1)";
				
					$query_sugar2 = $this->db_sugar->query($sql_sugar2);
					//print_r($query_sugar2);
					if($query_sugar2)
					{
						$sql_sugar3 = "INSERT INTO notes_cstm (id_c, migrated_from_logics_c) VALUES('$uuid2',0)";
						
						$query_sugar3 = $this->db_sugar->query($sql_sugar3);
						//print_r($query_sugar3);
						if($query_sugar3)
						{
							return $uuid ; 
						}
						else{
						
							$err =  $this->db_sugar->error();
							return 0;
							//print_r($err);
						}
					}
					else{
						
						$err =  $this->db_sugar->error();
						return 0;
						//print_r($err);
					}
				}
				else
				{
					return $uuid ; 
				}
			}
			else
			{
				return 0 ; 
			}
		}
		else
		{
			return 0 ; 
		}
	} */
	
	public function add_note($case_id, $note_txt, $note_type, $uuid)
	{
		$logData = '1. Note Create Start CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','add_note');

		$error = 0;
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		
		if($note_type == '')
		{
			$note_type = 'Case Review';
		}
		
		if($case_id != '')
		{
			//Assignment Note
			if($note_type == 'Assignment Note')
			{
				$logData = '2. Assignment Note CaseID  ' . $case_id  . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','add_note');
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('contacts_cstm');
				$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);	
				$this->db_sugar->where('deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();
				//print_r($record_sugar);
				if(count($record_sugar) > 0){
					
					$contact_id = $record_sugar[0]->id_c;				
				}
				if($contact_id != '')
				{
					//$instance_url = $this->sugar_url . "rest/v11";  
					//$url = $instance_url . "/Notes";
					//Set up the Record details	
						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Contacts",
						'parent_id' => $contact_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);

					$url = "/Notes";
				
					// Call global function for store and update data by paramenters url and record
					$createdRecord = $this->storeDataInSugar('add_note', $url, $record);
					//file_put_contents('createNoteLog1.txt',print_r($createdRecord,true) . PHP_EOL . PHP_EOL, FILE_APPEND);
					if($createdRecord->id != '')
					{
						$logData = '3. Assignment Note Create Success CaseId - ' . $case_id . ", Response CreatedRecId - " . $createdRecordLeads->id . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						return $createdRecord->id;
					}
					else
					{
						$error = 1;
						$logData = '3. Assignment Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($createdRecordLeads,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				else
				{
					$logData = '4. Contact Id Not Found CaseID - ' . $case_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','add_note');
				}
			}
			else
			{
				$logData = '2. Else CR Note CaseID - ' . $case_id . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','add_note');
				//get lead id for lead notes
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('leads_cstm');
				$this->db_sugar->join('leads', 'leads.id = leads_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);	
				$this->db_sugar->where('deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();
				//print_r($record_sugar);
				if(count($record_sugar) > 0)
				{					
					$lead_id = $record_sugar[0]->id_c;				
				}
				if($lead_id != '')
				{						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Leads",
						'parent_id' => $lead_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);

					$url = "/Notes";
				
					// Call global function for store and update data by paramenters url and record
					$createdRecordLeads = $this->storeDataInSugar('add_note', $url, $record);
					//file_put_contents('createNoteLog2.txt',print_r($createdRecordLeads,true) . PHP_EOL . PHP_EOL, FILE_APPEND);
					
					if($createdRecordLeads->id != '')
					{
						$logData = '3. Lead Note Create Success CaseId - ' . $case_id . ", CreatedRecordId - " . $createdRecordLeads->id . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						//$error = 1;
					}
					else
					{
						$error = 1;
						$logData = '3.  Lead Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($createdRecordLeads,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				else
				{
					$logData = '4. Lead Id Not Found CaseID - ' . $case_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','add_note');
				}

				//get  id for case notes
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('cases_cstm');
				$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);			
				$this->db_sugar->where('cases.deleted', 0);			
				
				$data_sugar2 = $this->db_sugar->get();
				$record_sugar2 = $data_sugar2->result();
				//print_r($record_sugar2);
				if(count($record_sugar2) > 0){
					
					$case_id = $record_sugar2[0]->id_c;				
				}
				if($case_id != '')
				{						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Cases",
						'parent_id' => $case_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);

					$url = "/Notes";
				
					// Call global function for store and update data by paramenters url and record
					$createdRecordCase = $this->storeDataInSugar('add_note', $url, $record);
					file_put_contents('createNoteLog3.txt',print_r($createdRecordCase,true) . PHP_EOL . PHP_EOL, FILE_APPEND);
					//file_put_contents('createReso.txt',print_r($createdRecord,true));
					if($createdRecordCase->id != '')
					{
						$logData = '5.  Cases Note Create Success CaseId - ' . $case_id . ", CreatedRecordId - " . $createdRecordLeads->id . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						//$error = 1;
					}
					else
					{
						$error = 1;
						$logData = '5.  Cases Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($createdRecordLeads,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				return $createdRecordLeads->id;
			}
		}
		else
		{
			$logData = 'End. No CaseID  ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','add_note');
		}	
	} 

	// Added on 15-07-2025 https://trello.com/c/QES9Q6km/1053-investigations-cr-portal-notes-update
	public function add_draftNote($case_id, $note_txt, $note_type, $uuid)
	{
		$logData = '1. Note Create Start CaseID - ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','add_note');

		$retutnCreatedId = '';
		$error = 0;
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		
		if($note_type == '')
		{
			$note_type = 'Case Review';
		}
		// $note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
		// $note_txt = htmlentities($note_txt);
		$note_txt = htmlspecialchars($note_txt, ENT_QUOTES, 'UTF-8');
		
		if($case_id != '')
		{
			//Assignment Note
			if($note_type == 'Assignment Note')
			{
				$logData = '2. Assignment Note CaseID  ' . $case_id  . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','add_note');
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('contacts_cstm');
				$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);	
				$this->db_sugar->where('deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();
				//print_r($record_sugar);
				if(count($record_sugar) > 0){
					
					$contact_id = $record_sugar[0]->id_c;				
				}
				if($contact_id != '')
				{
					//$instance_url = $this->sugar_url . "rest/v11";  
					//$url = $instance_url . "/Notes";
					//Set up the Record details	
						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Contacts",
						'parent_id' => $contact_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);
				
					$parent_type = 'Contacts';
					$parent_id = $contact_id;
					$user_id = $user_id;

					$draftNotes = "
						INSERT INTO draft_notes (
							id, name, date_entered, date_modified,
							modified_user_id, created_by, description,
							deleted, parent_type, parent_id, assigned_user_id,
							team_id, team_set_id
						)
						VALUES (
							'$uuid', '$note_type', NOW(), NOW(),
							'$user_id', '$user_id', '$note_txt',
							0, '$parent_type', '$parent_id', '1',
							'1', '1'
						)
					";

					$result_draftNotes = $this->db_sugar->query($draftNotes);

					if($result_draftNotes)
					{
						$logData = '3. Assignment Note Create Success CaseId - ' . $case_id . ", Response CreatedRecId - " . $uuid . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						return $uuid;
					}
					else
					{
						$error = 1;
						$logData = '3. Assignment Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($result_draftNotes,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				else
				{
					$logData = '4. Contact Id Not Found CaseID - ' . $case_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','add_note');
				}
			}
			else
			{
				$logData = '2. Else CR Note CaseID - ' . $case_id . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','add_note');
				//get lead id for lead notes
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('leads_cstm');
				$this->db_sugar->join('leads', 'leads.id = leads_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);	
				$this->db_sugar->where('deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();
				//print_r($record_sugar);
				if(count($record_sugar) > 0)
				{					
					$lead_id = $record_sugar[0]->id_c;				
				}
				if($lead_id != '')
				{						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Leads",
						'parent_id' => $lead_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);

					$parent_type = 'Leads';
					$parent_id = $lead_id;
					$user_id = $user_id;

					$draftNotes = "
						INSERT INTO draft_notes (
							id, name, date_entered, date_modified,
							modified_user_id, created_by, description,
							deleted, parent_type, parent_id, assigned_user_id,
							team_id, team_set_id
						)
						VALUES (
							'$uuid', '$note_type', NOW(), NOW(),
							'$user_id', '$user_id', '$note_txt',
							0, '$parent_type', '$parent_id', '1',
							'1', '1'
						)
					";

					$result_draftNotes = $this->db_sugar->query($draftNotes);

					
					if($result_draftNotes)
					{
						$retutnCreatedId = $uuid;
						$logData = '3. Lead Note Create Success CaseId - ' . $case_id . ", CreatedRecordId - " . $uuid . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						//$error = 1;
					}
					else
					{
						$error = 1;
						$logData = '3.  Lead Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($result_draftNotes,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				else
				{
					$logData = '4. Lead Id Not Found CaseID - ' . $case_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','add_note');
				}

				//get  id for case notes
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('cases_cstm');
				$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				$this->db_sugar->where('case_id_c', $case_id);			
				$this->db_sugar->where('cases.deleted', 0);			
				
				$data_sugar2 = $this->db_sugar->get();
				$record_sugar2 = $data_sugar2->result();
				//print_r($record_sugar2);
				if(count($record_sugar2) > 0){
					
					$case_id = $record_sugar2[0]->id_c;				
				}
				if($case_id != '')
				{						
					$record = array(
						'name' => $note_type,
						'modified_user_id' => $user_id,
						'created_by' => $user_id,
						'description' => $note_txt,
						'parent_type' => "Cases",
						'parent_id' => $case_id,
						'assigned_user_id' => "1",
						'team_id' => "1",
						'team_set_id' => "1",
						'migrated_from_logics_c' => 0
					);

					$parent_type = 'Cases';
					$parent_id = $case_id;
					$user_id = $user_id;

					$this->load->library('uuid');
					$uuid = $this->uuid->v4();

					$draftNotes = "
						INSERT INTO draft_notes (
							id, name, date_entered, date_modified,
							modified_user_id, created_by, description,
							deleted, parent_type, parent_id, assigned_user_id,
							team_id, team_set_id
						)
						VALUES (
							'$uuid', '$note_type', NOW(), NOW(),
							'$user_id', '$user_id', '$note_txt',
							0, '$parent_type', '$parent_id', '1',
							'1', '1'
						)
					";

					$result_draftNotes = $this->db_sugar->query($draftNotes);
					
					if($result_draftNotes)
					{
						$logData = '5.  Cases Note Create Success CaseId - ' . $case_id . ", CreatedRecordId - " . $uuid . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
						//$error = 1;
					}
					else
					{
						$error = 1;
						$logData = '5.  Cases Note Create Error CaseId - ' . $case_id . ", Response - " . print_r($result_draftNotes,true) . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','add_note');
					}
				}
				// $logData = '6.  CaseId - ' . $case_id . ", Final CreatedRecordId - " . $retutnCreatedId. PHP_EOL;
				// $this->createCustomLog($logData,'SummaryTab','add_note');
				return $retutnCreatedId;
			}
		}
		else
		{
			$logData = 'End. No CaseID  ' . $case_id . ", Note Text - " . $note_txt . ", Note Type - " . $note_type . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','add_note');
		}	
	} 

	public function publish_note($case_id, $note_id, $uuid) 
	{
		$this->db_sugar->select('description, name, saved_note_id');
		$this->db_sugar->from('draft_notes');
		$this->db_sugar->where(array('id' => $note_id, 'deleted' => '0'));
		$data_sugar = $this->db_sugar->get();
		$record_sugar = $data_sugar->result();

		if (count($record_sugar) > 0) {
			if (!empty($record_sugar[0]->saved_note_id)) {
				return 'Note already published';
			}
			$note_txt = $record_sugar[0]->description;
			$note_type = $record_sugar[0]->name;

			$response = $this->add_note($case_id, $note_txt, $note_type, $uuid);
			$logData = 'Line no 7630 in publish_note method | Note Text - ' . $note_txt . " | Note Type - " . $note_type . " | Response - " . $response . PHP_EOL;
			$this->createCustomLog($logData, 'SummaryTab', 'add_note');


			$update_draft_note = "UPDATE draft_notes SET `saved_note_id` = '$response'  WHERE id='$note_id' ";
			$result_draft_note = $this->db_sugar->query($update_draft_note);
			if ($result_draft_note) {
				$logData = 'Line no 7635 in publish_note method | Draft Note Update Success | Note ID - ' . $note_id . " | Saved Note ID - " . $response . PHP_EOL;
			} else {
				$logData = 'Line no 7635 in publish_note method | Draft Note Update Failed | Note ID - ' . $note_id . " | Saved Note ID - " . $response . PHP_EOL;
			}
			$this->createCustomLog($logData, 'SummaryTab', 'add_note');
			
			return $response;
		} else {
			// Log if no note found
			$this->createCustomLog("No note found with ID $note_id" . PHP_EOL, 'SummaryTab', 'add_note');
			return 'No note found';
		}
	}
	// end Added on 15-07-2025

	public function getAuthToken()
	{
		$userName = $this->session->userdata('uname');
		$password = $this->session->userdata('pwd');
		session_write_close();

		$instance_url = $this->sugar_url . "rest/v11";  
		//print $userName . ', ' . $instance_url ;
		$auth_url = $instance_url . "/oauth2/token";
		$oauth2_token_arguments = array(
			"grant_type" => "password",
			"client_id" => "sugar", 
			"client_secret" => "", 
			"username" => $userName,
			"password" => $password,
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

		$json_arguments = json_encode($oauth2_token_arguments);
		curl_setopt($auth_request, CURLOPT_POSTFIELDS, $json_arguments);
		$oauth2_token_response = curl_exec($auth_request);
		$oauth2_token_response_obj = json_decode($oauth2_token_response);
		//$oauth_token = $oauth2_token_response_obj->access_token;
		//print_r($oauth2_token_response_obj);

		
		return $oauth2_token_response_obj;
	}

	public function storeDataInSugar($func, $url, $record, $id=null)
	{
		$logData = '1. URL - ' . $url . ", Id - " . $id .", record - " . print_r($record,true) . PHP_EOL;
		$this->createCustomLog($logData,'storeDataInSugar','storeDataInSugar_' . $func);
		$oauth_token = '';
		$oauth2_token_response_obj = $this->getAuthToken();
		// print_r($oauth2_token_response_obj);
		//if($oauth2_token_response_obj->error)
		if(property_exists($oauth2_token_response_obj, 'error')) 
		{
			return $oauth2_token_response_obj; //->error_message;
		}
		else
		{
		 	$oauth_token = $oauth2_token_response_obj->access_token;
		 	//return $oauth_token;
		}				
		//$oauth_token = $oauth2_token_response_obj->access_token;

		if($oauth_token != '')
		{
			$instance_url = $this->sugar_url."rest/v11";  
			$url = $instance_url.$url;
			$curl_request = curl_init($url);
			
			// If event is update then pass put method
			if($id)
			{
				curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
			}
			//print 'storeDataInSugar';
			curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($curl_request, CURLOPT_HEADER, false);
			curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"oauth-token: {$oauth_token}"
			));
			
			$json_arguments = json_encode($record);
			$generatedRecord = '';

			try
			{
				curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
				$curl_response = curl_exec($curl_request);

				$logData = '2. URL - ' . $url . ", response - " . $curl_response . PHP_EOL;
				$this->createCustomLog($logData,'storeDataInSugar','storeDataInSugar_' . $func);

				file_put_contents('service_res_json1june.txt',$curl_response);
				$generatedRecord  = json_decode($curl_response);
				curl_close($curl_request);
				//print_r($generatedRecord);
			}
			catch(Exception $ex)
			{
				//print 'exception - ' . $ex;
				return $ex;
			}
			return $generatedRecord; 
		}	
		else
		{
			$logData = '3. URL - ' . $url . ", Auth token issue" . PHP_EOL;
			$this->createCustomLog($logData,'storeDataInSugar','storeDataInSugar_' . $func);
		}	
	}

	public function add_note_pps($case_id, $note_txt, $ppsid) 
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		//$txt = $ppsid . ' ----- ' . $case_id . ' ----- ' . $note_txt . PHP_EOL;
		//file_put_contents('NoteCaseDetails.txt', $txt , FILE_APPEND);

		$logData = '1. PPS Note Start CaseId - ' . $case_id . ', PPSId - ' . $ppsid . ", Note text - " . $note_txt . ", user_id - " . $user_id . PHP_EOL;
		$this->createCustomLog($logData,'PPS','add_note_pps');

		if($case_id != '' && $user_id != ''){
			
			$uuid = $this->gen_uuid();
			
			//print 'sugar_id-' . $sugar_id;
			if($ppsid != '' && $note_txt != '' && $uuid != ''){
				
				$note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
				$note_txt = htmlentities($note_txt);
				
				$sql_sugar = "INSERT INTO notes(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, parent_type, parent_id, assigned_user_id, team_id, team_set_id) VALUES('$uuid', 'Case Review', now(), now(), '$user_id', '$user_id', '$note_txt', 0, 'inves_PPS', '$ppsid', 1, 1, 1)";
				//print $sql_sugar;
				
				$query_sugar = $this->db_sugar->query($sql_sugar);
				//print_r($query_sugar);
				if($query_sugar)
				{
					$logData = '2. PPS Note Main Created Success CaseId - ' . $case_id . ', PPSId - ' . $ppsid . ", Note text - " . $note_txt . PHP_EOL;
					$this->createCustomLog($logData,'PPS','add_note_pps');

					$sql_sugar1 = "INSERT INTO notes_cstm (id_c, migrated_from_logics_c) VALUES('$uuid',0)";
					//print $sql_sugar1;
					
					$query_sugar1 = $this->db_sugar->query($sql_sugar1);
					//print_r($query_sugar);
					if($query_sugar1)
					{
						$logData = '3. PPS Note Cstm Created Success CaseId - ' . $case_id . ', PPSId - ' . $ppsid . ", Note text - " . $note_txt . PHP_EOL;
						$this->createCustomLog($logData,'PPS','add_note_pps');
						return $uuid ; 
					}
					else
					{
						$logData = '3. PPS Note Cstm Created Error CaseId - ' . $case_id . ', PPSId - ' . $ppsid . ", sql_sugar1 - " . $sql_sugar1 . PHP_EOL;
						$this->createCustomLog($logData,'PPS','add_note_pps');
						$err =  $this->db_sugar->error();
						return 0;
						//print_r($err);
					}
				}
				else
				{	
					$err =  $this->db_sugar->error();

					$logData = '3. PPS Note Main Created Error CaseId - ' . $case_id . ', PPSId - ' . $ppsid . ", err - " . $err . ", sql_sugar - " . $sql_sugar . PHP_EOL;
					$this->createCustomLog($logData,'PPS','add_note_pps');

					$txt = $ppsid . ' ----- ' . $case_id . ' ----- ' . $err . PHP_EOL;
					//file_put_contents('NoteCaseDetailsQR.txt', $txt , FILE_APPEND);
					return 0;
					//print_r($err);
				}
			}
			else
			{
				$logData = '2. PPS Note Missing Data2 CaseId - ' . $case_id . ', PPSId - ' . $ppsid . PHP_EOL;
				$this->createCustomLog($logData,'PPS','add_note_pps');
				return 0;
			}
		}
		else
		{
			$logData = '2. PPS Note Missing Data1 CaseId - ' . $case_id . ', PPSId - ' . $ppsid . PHP_EOL;
			$this->createCustomLog($logData,'PPS','add_note_pps');
			return 0;
		}
	}
	
	public function save_summary_note($case_id, $note_id, $note_txt)
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		// $note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
		// $note_txt = htmlentities($note_txt);
		$note_txt = htmlspecialchars($note_txt, ENT_QUOTES, 'UTF-8');
		
		$logData = '1. Note Update Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " .  $note_txt . ", user_id - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','save_summary_note');

		if($case_id != '' && $note_id != '' && $note_txt != '' && $user_id != '')
		{
			//print $note_txt . ' -- ';
			// $note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
			// $note_txt = htmlentities($note_txt); // Commented on 30-01-2024 save_summary_note
			//print $note_txt . ' -- ';
			$note_txt = str_replace("'","\'",$note_txt);

			// Added on 15-07-2025 https://trello.com/c/QES9Q6km/1053-investigations-cr-portal-notes-update
			$sql_draft = "UPDATE draft_notes SET `description` = '$note_txt', date_modified = NOW(), modified_user_id = '$user_id' WHERE id = '$note_id'";
        	$query_draft = $this->db_sugar->query($sql_draft);
			
			if($query_draft)
			{
				$logData = '2. Draft Note Update Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Updated text - " .  $note_txt . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','save_summary_note');

				$this->db_sugar->select('saved_note_id');
				$this->db_sugar->from('draft_notes');
				$this->db_sugar->where(array('id' => $note_id, 'deleted' => '0'));
				$draftResult = $this->db_sugar->get()->row();
				
				if (!empty($draftResult) && !empty($draftResult->saved_note_id)) {
					$saved_note_id = $draftResult->saved_note_id;

					$sql_saved = "UPDATE notes SET `description` = '$note_txt', date_modified = NOW(), modified_user_id = '$user_id' WHERE id = '$saved_note_id'";
					$query_saved = $this->db_sugar->query($sql_saved);

					if ($query_saved) {
						$logData = '3. Saved Note Update Success | Saved Note ID - ' . $saved_note_id . PHP_EOL;
					} else {
						$logData = '3. Saved Note Update Failed | Saved Note ID - ' . $saved_note_id . " | SQL - $sql_saved" . PHP_EOL;
					}
					$this->createCustomLog($logData, 'SummaryTab', 'save_summary_note');
				} else {
					$logData = '3. No saved_note_id found or it is empty | Draft Note ID - ' . $note_id . PHP_EOL;
					$this->createCustomLog($logData, 'SummaryTab', 'save_summary_note');
				}

				return $query_draft;
			}
			else
			{
				$logData = '2. Draft Note Update Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Update sql - " .  $query_draft . PHP_EOL;
				$this->createCustomLog($logData,'SummaryTab','save_summary_note');
			}	// end Added on 15-07-2025

			// $sql = "UPDATE notes SET `description` = '$note_txt', date_modified=now(), modified_user_id='$user_id' WHERE id='$note_id' ";	 //AND parent_type = 'Leads'
			
			// $query = $this->db_sugar->query($sql);
			// if($query)
			// {
			// 	$logData = '2. Note Update Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Updated text - " .  $note_txt . PHP_EOL;
			// 	$this->createCustomLog($logData,'SummaryTab','save_summary_note');
			// 	 return $query ; 
			// }
			// else
			// {
			// 	$logData = '2. Note Update Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Update sql - " .  $sql . PHP_EOL;
			// 	$this->createCustomLog($logData,'SummaryTab','save_summary_note');
			// } // Commented on 15-07-2025	
		}
		else
		{
			$logData = '2. Note Update Missing Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Update sql - " .  $sql . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','save_summary_note');
			return 0;
		}
	}
	
	public function remove_summary_note($case_id, $note_id)
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		$logData = '1. Note Delete Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", user_id - " . $user_id . PHP_EOL;
		$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
		
		if($case_id != '' && $note_id != '' && $user_id != '')
		{
			// Added on 15-07-2025 https://trello.com/c/QES9Q6km/1053-investigations-cr-portal-notes-update
			$this->db_sugar->select('saved_note_id');
			$this->db_sugar->from('draft_notes');
			$this->db_sugar->where('id', $note_id);
			$this->db_sugar->where('deleted', '0');
			$data = $this->db_sugar->get()->row();

			if ($data) 
			{
				if (!empty($data->saved_note_id)) 
				{
					$saved_note_id = $data->saved_note_id;
					$logData = 'Updating notes table (via draft) - Saved Note ID: ' . $saved_note_id . ", User Id - " . $user_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','remove_summary_note');

					$sql_notes = "UPDATE notes SET deleted = 1, date_modified = NOW(), modified_user_id = '$user_id' WHERE id = '$saved_note_id'";
					$this->db_sugar->query($sql_notes);
				}
				$sql_draft = "UPDATE draft_notes SET deleted = 1, date_modified = NOW(), modified_user_id = '$user_id' WHERE id = '$note_id'";
				$query_draft = $this->db_sugar->query($sql_draft);

				if($query_draft) 
				{
					$logData = '2. Draft note delete success - CaseId: ' . $case_id . ", Note Id: " . $note_id . PHP_EOL;
					$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
					return true;
				}
			} 
			else 
			{
				$this->db_sugar->select('id');
				$this->db_sugar->from('notes');
				$this->db_sugar->where('id', $note_id);
				$this->db_sugar->where('deleted', '0');
				$note = $this->db_sugar->get()->row();

				if ($note) 
				{
					$sql_note = "UPDATE notes SET deleted = 1, date_modified = NOW(), modified_user_id = '$user_id' WHERE id = '$note_id'";
					$query_note = $this->db_sugar->query($sql_note);

					if ($query_note) 
					{
						$logData = '2. Direct note delete success - CaseId: ' . $case_id . ", Note Id: " . $note_id . PHP_EOL;
						$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
						return true;
					}
				}
			}
			$logData = '2. Note Delete Error - CaseId: ' . $case_id . ", Note Id: " . $note_id . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
			return true;
		}	
		else 
		{
			$logData = '2. Note Missing Data - CaseId: ' . $case_id . ", Note Id: " . $note_id . PHP_EOL;
			$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
			return false;
		} // end added on 15-07-2025
			// $sql = "UPDATE notes SET `deleted` = 1, date_modified=now(), modified_user_id='$user_id'  WHERE id='$note_id' "; //AND parent_type = 'Leads'	
			
			// $query = $this->db_sugar->query($sql);
			// if($query)
			// {
			// 	$logData = '2. Note Delete Success CaseId - ' . $case_id . ", Note Id - " . $note_id .  PHP_EOL;
			// 	$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
			// 	return $query ; 
			// }
			// else
			// {
			// 	$logData = '2. Note Delete Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Query - " . $sql .  PHP_EOL;
			// 	$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
			// }			
		// }
		// else
		// {
		// 	$logData = '2. Note Missing Data CaseId - ' . $case_id . ", Note Id - " . $note_id .  PHP_EOL;
		// 	$this->createCustomLog($logData,'SummaryTab','remove_summary_note');
		// 	return 0;
		// } // Commented on 15-07-2025
	}
	
	public function save_pps_note($case_id, $note_id, $note_txt)
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		
		$logData = '1. Note Update Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " . $note_txt  . ", user_id - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_note');

		if($case_id != '' && $note_id != '' && $note_txt != '' && $user_id != '')
		{
			$note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
			$note_txt = htmlentities($note_txt);

			$sql = "UPDATE notes SET `description` = '$note_txt', modified_user_id='$user_id', date_modified=now() WHERE id='$note_id'";	
			//print $sql;
			
			$query = $this->db_sugar->query($sql, array($case_id,$note_txt,0,0));
			if($query)
			{
				$logData = '2. Note Update Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " . $note_txt .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_note');
				 return $query ; 
			}
			else
			{
				$logData = '2. Note Update Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", sql - " . $sql .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_note');
			}
		}
		else
		{
			$logData = '2. Note Update Missing Data CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " . $note_txt .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_note');
			return 0;
		}
	}
	
	public function remove_pps_note($case_id, $note_id)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();

		$logData = '1. Note Delete PPS Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id .   PHP_EOL;
		$this->createCustomLog($logData,'PPS','remove_pps_note');
		
		if($case_id != '' && $note_id != '' && $user_id != ''){
			
			$sql = "UPDATE notes SET `deleted` = 1, modified_user_id='$user_id', date_modified=now()  WHERE id='$note_id'";	
			//print $sql;
			$query = $this->db_sugar->query($sql);//, array($case_id,$note_txt,0,0)
			if($query)
			{
				$logData = '2. Note Delete PPS Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','remove_pps_note');
				return $query ; 
			}
			else
			{
				$logData = '2. Note Delete PPS Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", sql - " . $sql .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','remove_pps_note');
			}
		}
		else
		{
			$logData = '2. Note Delete PPS Missing Data CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','remove_pps_note');
			return 0;
		}
	}
	
	public function save_pps_spouse_note($case_id, $note_id, $note_txt)
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		$logData = '1. Note Update PPS Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " . $note_txt . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_spouse_note');

		if($case_id != '' && $note_id != '' && $note_txt != '' && $user_id != ''){
			
			$note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
			$note_txt = htmlentities($note_txt);

			$sql = "UPDATE notes SET `description` = '$note_txt', modified_user_id='$user_id', date_modified=now() WHERE id='$note_id'";	
			//print $sql;
			$query = $this->db_sugar->query($sql, array($case_id,$note_txt,0,0));
			
			if($query)
			{
				$logData = '2. Note Update PPS Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", Note txt - " . $note_txt . ", UserId - " . $user_id .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_spouse_note');
				 return $query ; 
			}
			else
			{
				$logData = '2. Note Update PPS Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", sql - " . $sql . ", UserId - " . $user_id .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_spouse_note');
			}
		}
		else
		{
			$logData = '2. Note Update PPS Missing Data CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_spouse_note');
			return 0;
		}
	}
	
	public function remove_pps_spouse_note($case_id, $note_id)
	{
		$user_id = $this->session->userdata('User_id');	
		session_write_close();
		$logData = '1. Note PPS Sp Delete Start CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id . PHP_EOL;
		$this->createCustomLog($logData,'PPS','remove_pps_spouse_note');

		if($case_id != '' && $note_id != '' && $user_id != '')
		{
			$sql = "UPDATE notes SET `deleted` = 1, modified_user_id='$user_id', date_modified=now() WHERE id='$note_id'";	
			
			//print $sql;
			
			$query = $this->db_sugar->query($sql);//, array($case_id,$note_txt,0,0)
			if($query)
			{
				$logData = '2. Note PPS Sp Delete Success CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id . PHP_EOL;
				$this->createCustomLog($logData,'PPS','remove_pps_spouse_note');
				return $query ; 
			}
			else
			{
				$logData = '2. Note PPS Sp Delete Error CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id . ", SQL - " . $sql . PHP_EOL;
				$this->createCustomLog($logData,'PPS','remove_pps_spouse_note');
			}
		}
		else
		{
			$logData = '2. Note PPS Sp Delete Missing Data CaseId - ' . $case_id . ", Note Id - " . $note_id . ", UserId - " . $user_id . PHP_EOL;
			$this->createCustomLog($logData,'PPS','remove_pps_spouse_note');
			return 0;
		}
	}
	
	public function save_pps_note_spouse($case_id, $note_txt, $ppsid)
	{
		$user_id = $this->session->userdata('User_id');
		session_write_close();
		$logData = '1. Note Create PPS Start CaseId - ' . $case_id . ", PPS Id - " . $ppsid . ", Note txt - " . $note_txt . ", UserId - " . $user_id .  PHP_EOL;
		$this->createCustomLog($logData,'PPS','save_pps_note_spouse');

		//$txt = $ppsid . ' ----- ' . $case_id . ' ----- ' . $note_txt . PHP_EOL;
		//file_put_contents('NoteCaseDetails.txt', $txt , FILE_APPEND);
		if($case_id != '' && $user_id != ''){
			
			$uuid = $this->gen_uuid();
			
			//print 'sugar_id-' . $sugar_id;
			if($ppsid != '' && $note_txt != '' && $uuid != ''){
				
				$note_txt = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
				$note_txt = htmlentities($note_txt);

				$sql_sugar = "INSERT INTO notes(id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, parent_type, parent_id, assigned_user_id, team_id, team_set_id) VALUES('$uuid', 'Case Review', now(), now(), '$user_id', '$user_id', '$note_txt', 0, 'inves_PPS', '$ppsid', 1, 1, 1)";
				//print $sql_sugar;
				$query_sugar = $this->db_sugar->query($sql_sugar);
				//print_r($query_sugar);
				if($query_sugar)
				{
					$logData = '2. Note Create PPS Main Success CaseId - ' . $case_id . ", PPS Id - " . $ppsid . ", Note txt - " . $note_txt . ", UserId - " . $user_id .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','save_pps_note_spouse');

					$sql_sugar1 = "INSERT INTO notes_cstm (id_c, migrated_from_logics_c) VALUES('$uuid',0)";
					
					$query_sugar1 = $this->db_sugar->query($sql_sugar1);
					//print_r($query_sugar);
					if($query_sugar1)
					{
						$logData = '3. Note Create PPS Cstm Success CaseId - ' . $case_id . ", PPS Id - " . $ppsid . ", Note txt - " . $note_txt . ", UserId - " . $user_id .  PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_note_spouse');
						return $uuid ; 
					}
					else
					{
						$logData = '3. Note Create PPS Cstm Error CaseId - ' . $case_id . ", PPS Id - " . $ppsid . ", sql_sugar1 - " . $sql_sugar1 . ", UserId - " . $user_id .  PHP_EOL;
						$this->createCustomLog($logData,'PPS','save_pps_note_spouse');
						$err =  $this->db_sugar->error();
						return 0;
						//print_r($err);
					}
				}
				else
				{					
					$err =  $this->db_sugar->error();
					$logData = '2. Note Create PPS Main Error CaseId - ' . $case_id . ", PPS Id - " . $ppsid . ", err - " . $err . ", sql_sugar - " . $sql_sugar . ", UserId - " . $user_id .  PHP_EOL;
					$this->createCustomLog($logData,'PPS','save_pps_note_spouse');
					//$txt = $ppsid . ' ----- ' . $case_id . ' ----- ' . $err . PHP_EOL;
					//file_put_contents('NoteCaseDetailsQR.txt', $txt , FILE_APPEND);
					return 0;
					//print_r($err);
				}
			}
			else
			{
				$logData = '2. Note Create Missing Data CaseId - ' . $case_id . ", Note txt - " . $note_txt . ", PPS Id - " . $ppsid .  PHP_EOL;
				$this->createCustomLog($logData,'PPS','save_pps_note_spouse');
				return 0;
			}
		}
		else
		{
			$logData = '2. Note Create Missing Data CaseId - ' . $case_id . ", UserId - " . $user_id . ", PPS Id - " . $ppsid .  PHP_EOL;
			$this->createCustomLog($logData,'PPS','save_pps_note_spouse');
			return 0;
		}
	}
	
	//Summary notes and PPS notes
	public function get_notes_all($case_id)
	{	 
		if($case_id != '')		
		{	
			// added on 15-07-2025 https://trello.com/c/QES9Q6km/1053-investigations-cr-portal-notes-update
			$sql = "
					(
					SELECT
						notes.id AS note_id,
						CASE
						WHEN notes.name LIKE '%Case Review%' THEN 'Case Review'
						WHEN notes.name LIKE '%Add Service%' THEN 'Add Service'
						WHEN notes.name LIKE '%AI Generated Note%' THEN 'AI Generated Note'
						ELSE notes.name
						END AS name,
						notes.description AS note_text,
						notes.date_modified AS note_datecreated,
						CONCAT(users.first_name, ' ', users.last_name) AS note_user,
						'notes' AS source
					FROM notes
					INNER JOIN leads_cstm ON leads_cstm.id_c = notes.parent_id
					LEFT JOIN users ON users.id = notes.modified_user_id
					WHERE
						(notes.name LIKE '%Case Review%' OR notes.name LIKE '%Add Service%' OR notes.name LIKE '%AI Generated Note%')
						AND leads_cstm.case_id_c = $case_id
						AND notes.parent_type = 'Leads'
						AND notes.deleted = 0
						AND notes.id NOT IN (
						SELECT saved_note_id FROM draft_notes WHERE saved_note_id IS NOT NULL
						)
					)

					UNION

					(
					SELECT
						notes.id AS note_id,
						notes.name,
						notes.description AS note_text,
						notes.date_modified AS note_datecreated,
						CONCAT(users.first_name, ' ', users.last_name) AS note_user,
						'notes' AS source
					FROM notes
					INNER JOIN contacts_cstm ON contacts_cstm.id_c = notes.parent_id
					LEFT JOIN users ON users.id = notes.modified_user_id
					WHERE
						notes.name = 'Assignment Note'
						AND contacts_cstm.case_id_c = $case_id
						AND notes.parent_type = 'Contacts'
						AND notes.deleted = 0
						AND notes.id NOT IN (
						SELECT saved_note_id FROM draft_notes WHERE saved_note_id IS NOT NULL
						)
					)

					UNION

					(
					SELECT
						draft_notes.id AS note_id,
						CASE
						WHEN draft_notes.name LIKE '%Case Review%' THEN 'Case Review'
						WHEN draft_notes.name LIKE '%Add Service%' THEN 'Add Service'
						WHEN draft_notes.name LIKE '%AI Generated Note%' THEN 'AI Generated Note'
						ELSE draft_notes.name
						END AS name,
						draft_notes.description AS note_text,
						draft_notes.date_modified AS note_datecreated,
						CONCAT(users.first_name, ' ', users.last_name) AS note_user,
						'draft' AS source
					FROM draft_notes
					INNER JOIN leads_cstm ON leads_cstm.id_c = draft_notes.parent_id
					LEFT JOIN users ON users.id = draft_notes.modified_user_id
					WHERE
						(draft_notes.name LIKE '%Case Review%' OR draft_notes.name LIKE '%Add Service%' OR draft_notes.name LIKE '%AI Generated Note%')
						AND leads_cstm.case_id_c = $case_id
						AND draft_notes.parent_type = 'Leads'
						AND draft_notes.deleted = 0
					)

					UNION

					(
					SELECT
						draft_notes.id AS note_id,
						draft_notes.name,
						draft_notes.description AS note_text,
						draft_notes.date_modified AS note_datecreated,
						CONCAT(users.first_name, ' ', users.last_name) AS note_user,
						'draft' AS source
					FROM draft_notes
					INNER JOIN contacts_cstm ON contacts_cstm.id_c = draft_notes.parent_id
					LEFT JOIN users ON users.id = draft_notes.modified_user_id
					WHERE
						draft_notes.name = 'Assignment Note'
						AND contacts_cstm.case_id_c = $case_id
						AND draft_notes.parent_type = 'Contacts'
						AND draft_notes.deleted = 0
					)

					ORDER BY note_datecreated DESC
					";

			// end added on 15-07-2025
		
			/*$this->db_sugar->select("notes.id as note_id, notes.name, notes.description as note_text, notes.date_modified as note_datecreated, concat(users.first_name, ' ', users.last_name) as note_user");   
			//date_format(date_modified,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = notes.parent_id', 'left');
			$this->db_sugar->join('users', 'users.id = notes.modified_user_id', 'left');
			
			$where = "(notes.name = 'Case Review' OR notes.name = 'Add Service' )";
			
			$this->db_sugar->where('leads_cstm.case_id_c', $case_id);			
			$this->db_sugar->where('parent_type', 'Leads');
			$this->db_sugar->where($where);
			$this->db_sugar->where('notes.deleted', 0);
			$this->db_sugar->order_by('notes.date_modified','DESC');						
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			*/

			// $sql = "select notes.id as note_id, notes.name, notes.description as note_text, notes.date_modified as note_datecreated, 
			// concat(users.first_name, ' ', users.last_name) as note_user   
			// from notes
			// left join leads_cstm ON leads_cstm.id_c = notes.parent_id
			// left join users ON users.id = notes.modified_user_id
			// WHERE (notes.name = 'Case Review' OR notes.name = 'Add Service' )
			// AND leads_cstm.case_id_c = $case_id		
			// AND parent_type = 'Leads'
			// AND notes.deleted = 0
			
			
			// UNION 
			
			// select notes.id as note_id, notes.name, notes.description as note_text, notes.date_modified as note_datecreated, 
			// concat(users.first_name, ' ', users.last_name) as note_user   
			// from notes
			// left join contacts_cstm ON contacts_cstm.id_c = notes.parent_id
			// left join users ON users.id = notes.modified_user_id
			// WHERE notes.name = 'Assignment Note'
			// AND contacts_cstm.case_id_c = $case_id		
			// AND parent_type = 'Contacts'
			// AND notes.deleted = 0 
			// order by note_datecreated DESC"; // Commented on 15-07-2025
			//print_r($record);	
			
			$record1 = $this->db_sugar->query($sql);
			$record = $record1->result();

			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;
	}
	
	//State Interview notes 
	public function get_stated_interview_notes($case_id)
	{	 
		if($case_id != '')		
		{			
			$this->db_sugar->select("id as note_id, description as note_text, date_modified as note_datecreated, note_type_c");   
			//date_format(date_modified,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			
			$this->db_sugar->join('notes_cstm', 'notes_cstm.id_c = notes.id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = notes.parent_id', 'left');
			
			$this->db_sugar->where('leads_cstm.case_id_c', $case_id);			
			$this->db_sugar->where('parent_type', 'Leads');
			
			$where = "(notes_cstm.note_type_c='Stated Interview - Personal' OR notes_cstm.note_type_c='Stated Interview - Income')";
			
			$this->db_sugar->where($where);
			$this->db_sugar->where('notes.deleted', 0);
							
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;
	}
	
	public function get_pps_notes_sugar($ppsid)
	{
		if($ppsid != '')		
		{			
			$this->db_sugar->select("notes.id as id, notes.description as note_text, notes.date_modified as note_datecreated, concat(users.first_name, ' ', users.last_name) as note_user"); //date_format(notes.date_entered,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			$this->db_sugar->join('users', 'users.id = notes.modified_user_id', 'left');
			
			//$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = notes.parent_id', 'left');			
			
			//$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
			
			//$this->db_sugar->join('inves_pps', 'inves_pps.id=inves_pps_cstm.id_c', 'left');	
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			//$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('notes.parent_id', $ppsid);
			
			//$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);	
			//$this->db_sugar->where('inves_pps.pps_type', 'Federal Personal');			
			//$this->db_sugar->like('inves_pps.pps_type', 'Federal Personal');	
			
			$this->db_sugar->where('notes.parent_type', 'inves_PPS');
			$this->db_sugar->where('notes.name', 'Case Review');
			$this->db_sugar->where('notes.deleted', 0);					
			//$this->db_sugar->where('cases.deleted', 0);					
			//$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->order_by('notes.date_modified','DESC');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;		
	}
 
	public function get_pps_notes_sugar_premium($ppsid)
	{
		file_put_contents('pps_notes_premium.txt', 'ppsid / '.print_r($ppsid, true)."\n" . PHP_EOL, FILE_APPEND);
		
		if($ppsid != '')		
		{			
			$this->db_sugar->select("notes.id as id, notes.description as note_text, notes.date_modified as note_datecreated, concat(users.first_name, ' ', users.last_name) as note_user"); //date_format(notes.date_entered,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			$this->db_sugar->join('users', 'users.id = notes.modified_user_id', 'left');
			
			$this->db_sugar->where('notes.parent_id', $ppsid);
			
			$this->db_sugar->where('notes.parent_type', 'inves_PPS');
			$this->db_sugar->where('notes.name', 'Case Review');
			$this->db_sugar->where('notes.deleted', 0);					
			//$this->db_sugar->where('cases.deleted', 0);					
			//$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->order_by('notes.date_modified','DESC');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;		
	}
 
	public function get_pps_notes_state_personal($ppsid)
	{
		if($ppsid != '')		
		{
			//note_id,note_text,date_format(note_datecreated,'%M %e,%Y %H:%i:%S %p') as note_datecreated
			$this->db_sugar->select("notes.id as id, notes.description as note_text,  notes.date_modified as note_datecreated, concat(users.first_name, ' ', users.last_name) as note_user"); //date_format(notes.date_entered,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			$this->db_sugar->join('users', 'users.id = notes.modified_user_id', 'left');
			
			//$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = notes.parent_id', 'left');			
			
			//$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');	
			
			//$this->db_sugar->join('inves_pps', 'inves_pps.id=inves_pps_cstm.id_c', 'left');
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			//$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('notes.parent_id', $ppsid);
			
			//$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 0);			
			//$this->db_sugar->where('inves_pps.pps_type', 'State Personal');	
			
			$this->db_sugar->where('notes.parent_type', 'inves_PPS');
			$this->db_sugar->where('notes.name', 'Case Review');
			$this->db_sugar->where('notes.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			//$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->order_by('notes.date_modified','DESC');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;		
	}
	
	public function get_pps_notes_spouse_sugar($ppsid)
	{	
		if($ppsid != '')		
		{
			//note_id,note_text,date_format(note_datecreated,'%M %e,%Y %H:%i:%S %p') as note_datecreated
			$this->db_sugar->select("notes.id as id, notes.description as note_text,  notes.date_modified as note_datecreated, concat(users.first_name, ' ', users.last_name) as note_user"); //date_format(notes.date_entered,'%M %e,%Y %H:%i:%S %p')
			$this->db_sugar->from('notes');
			$this->db_sugar->join('users', 'users.id = notes.modified_user_id', 'left');
			
			//$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = notes.parent_id', 'left');	
			
			//$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');			
			
			//$this->db_sugar->join('inves_pps', 'inves_pps.id=inves_pps_cstm.id_c', 'left');
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			//$this->db_sugar->where('cases_cstm.case_id_c', $caseid);
			$this->db_sugar->where('notes.parent_id', $ppsid);
			
			//$this->db_sugar->where('inves_pps_cstm.pps_spouse_c', 1);			
					
			$this->db_sugar->where('notes.parent_type', 'inves_PPS');
			$this->db_sugar->where('notes.name', 'Case Review');
			$this->db_sugar->where('notes.deleted', 0);					
			//$this->db_sugar->where('cases.deleted', 0);					
			//$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->order_by('notes.date_modified','DESC');
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print_r($record);		
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
		}
		else 
			return 0;
	}
	
	
	
	public function get_case_status($caseid){
		
		if($caseid != '')		
		{
			$this->db_sugar->select("investigation_advocate_statu_c as status");
			$this->db_sugar->from('cases_cstm');
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);						
			$this->db_sugar->where('cases.deleted', 0);						
			
			$data = $this->db_sugar->get();
			$record = $data->result();
						
			//print_r($record);
			
			if(is_array($record) && count($record) > 0){
				return $record[0]->status;
			}
			else 
				return null;
		}
	}
	
	public function delete_revenue_line_items($rid, $child, $parent_id) 
	{
		if($rid != '')
		{
			$sql_sugar_c = "UPDATE revenue_line_items set deleted=1, date_modified=now() ";	
			$sql_sugar_c .= " WHERE id='$rid' ";	

			//print $sql_sugar_c;
			//return true;
			$query_sugar_c = $this->db_sugar->query($sql_sugar_c);
			
			if($query_sugar_c) 
			{

				$sql_sugar_c1 = "UPDATE revenue_line_items INNER JOIN revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id set deleted=1, date_modified=now() ";	
				$sql_sugar_c1 .= " WHERE service_parent_id_c='$rid' ";	

				//print $sql_sugar_c;
				//return true;
				$query_sugar_c1 = $this->db_sugar->query($sql_sugar_c1);
//print $child . ', ' . $parent_id;
				if($child == 1 && $parent_id != '')
				{

					$this->updateParentServicePrice($parent_id);
				}
				
				return true;
			}
			else{
				
				$err =  $this->db_sugar->error();
				print_r($err);
			} 
		}
		else
		{
			return false;
		}
	}
	
	public function get_services($caseid) 
	{
		if($caseid != '')		
		{
			/*SELECT revenue_line_items.id, concat(sale_type_c, ' - ', product_catalog_type_c) as sale_type, concat(product_categories.name, ' - ',revenue_line_items.name) as name , quantity, round(list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by
			FROM `revenue_line_items` 
			LEFT JOIN revenue_line_items_cstm  on revenue_line_items_cstm.id_c=revenue_line_items.id
			LEFT JOIN leads on leads.opportunity_id = revenue_line_items.opportunity_id
			LEFT JOIN leads_cstm on leads.id=leads_cstm.id_c
			LEFT JOIN product_categories ON product_categories.id=revenue_line_items.category_id
			LEFT JOIN users ON users.id=revenue_line_items.created_by
			LEFT JOIN users users1 ON users1.id=revenue_line_items_cstm.user_id1_c
			LEFT JOIN users users2 ON users2.id=revenue_line_items_cstm.user_id2_c
			where leads_cstm.case_id_c = 1014538 and revenue_line_items.deleted=0*/

			//$this->db_sugar->select("revenue_line_items.id, concat(sale_type_c, ' - ', product_catalog_type_c) as sale_type, concat(product_categories.name, ' - ',revenue_line_items.name) as name , quantity, round(list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by");
			
			$this->db_sugar->select("is_package_c, revenue_line_items.id, sale_type_c as sale_type, concat(product_catalog_type_c, ' - ',ptem.name) as name , quantity, round(revenue_line_items.list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by, revenue_line_items_cstm.lead_from_c,
			(select GROUP_CONCAT(concat(product_catalog_type_c, ' - ', ptemc.name) )as name 
			from revenue_line_items as rc
			left join revenue_line_items_cstm as rcc ON rcc.id_c = rc.id
			left join product_templates ptemc ON ptemc.id = rc.product_template_id
			left join product_categories pc  on pc.id = rc.category_id
			left join product_types as pt on pt.id = ptemc.type_id
			where rcc.service_parent_id_c=revenue_line_items.id 
			and rc.deleted=0
			order by rc.date_entered desc) as childServices ");
			
			$this->db_sugar->from('revenue_line_items');
			
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');	
			
			$this->db_sugar->join('product_templates ptem', 'ptem.id = revenue_line_items.product_template_id', 'left');	

			$this->db_sugar->join('product_types_cstm', 'product_types_cstm.id_c = ptem.type_id', 'left');	
			
			$this->db_sugar->join('leads', 'leads.opportunity_id = revenue_line_items.opportunity_id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			
			$this->db_sugar->join('product_categories', 'product_categories.id = revenue_line_items.category_id', 'left');
			
			$this->db_sugar->join('users', 'users.id = revenue_line_items.created_by', 'left');
			
			$this->db_sugar->join('users users1', 'users1.id = revenue_line_items_cstm.user_id1_c', 'left');
			
			$this->db_sugar->join('users users2', 'users2.id = revenue_line_items_cstm.user_id2_c', 'left');
			
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('revenue_line_items.deleted', 0);	

			$where = "(service_parent_id_c IS NULL OR service_parent_id_c='')";
			$this->db_sugar->where($where);
			$this->db_sugar->order_by('revenue_line_items.date_entered',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
	}
	
	public function getChildServices($parent_id)
	{
		if($parent_id != '')		
		{ 
			$this->db_sugar->select("revenue_line_items_cstm.resservice_flag_c, revenue_line_items_cstm.state_list_c, revenue_line_items_cstm.res_reset_c, ptem.id as service_name_id, ptem.name as child_service_name, product_types.id as service_type,product_types.name as service_name, revenue_line_items.id, sale_type_c as sale_type, concat(product_catalog_type_c, ' - ',ptem.name) as name , quantity, round(revenue_line_items.list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by, revenue_line_items_cstm.lead_from_c, years_list_c, period_quarter_c");  
			
			$this->db_sugar->from('revenue_line_items');
			
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');	
			
			$this->db_sugar->join('product_templates ptem', 'ptem.id = revenue_line_items.product_template_id', 'left');	

			//$this->db_sugar->join('leads', 'leads.opportunity_id = revenue_line_items.opportunity_id', 'left');
			
			//$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			
			$this->db_sugar->join('product_categories', 'product_categories.id = revenue_line_items.category_id', 'left');

			$this->db_sugar->join('product_types', 'product_types.id = ptem.type_id', 'left');
			
			$this->db_sugar->join('users', 'users.id = revenue_line_items.created_by', 'left');
			
			$this->db_sugar->join('users users1', 'users1.id = revenue_line_items_cstm.user_id1_c', 'left');
			
			$this->db_sugar->join('users users2', 'users2.id = revenue_line_items_cstm.user_id2_c', 'left');
			
			$this->db_sugar->where('revenue_line_items_cstm.service_parent_id_c', $parent_id);	
			
			$this->db_sugar->where('revenue_line_items.deleted', 0);	

			$this->db_sugar->order_by('revenue_line_items.date_entered',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;
	}

	public function get_unsold_services($caseid) 
	{
		if($caseid != '')		
		{ 
			/*SELECT revenue_line_items.id, concat(sale_type_c, ' - ', product_catalog_type_c) as sale_type, concat(product_categories.name, ' - ',revenue_line_items.name) as name , quantity, round(list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by
			FROM `revenue_line_items` 
			LEFT JOIN revenue_line_items_cstm  on revenue_line_items_cstm.id_c=revenue_line_items.id
			LEFT JOIN leads on leads.opportunity_id = revenue_line_items.opportunity_id
			LEFT JOIN leads_cstm on leads.id=leads_cstm.id_c
			LEFT JOIN product_categories ON product_categories.id=revenue_line_items.category_id
			LEFT JOIN users ON users.id=revenue_line_items.created_by
			LEFT JOIN users users1 ON users1.id=revenue_line_items_cstm.user_id1_c
			LEFT JOIN users users2 ON users2.id=revenue_line_items_cstm.user_id2_c
			where leads_cstm.case_id_c = 1014538 and revenue_line_items.deleted=0*/

			//$this->db_sugar->select("revenue_line_items.id, concat(sale_type_c, ' - ', product_catalog_type_c) as sale_type, concat(product_categories.name, ' - ',revenue_line_items.name) as name , quantity, round(list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by");
			
			$where = "(revenue_line_items.sales_stage IS NULL OR revenue_line_items.sales_stage='')";
			
			$this->db_sugar->select("revenue_line_items.id, sale_type_c as sale_type, concat(product_catalog_type_c, ' - ',ptem.name) as name , quantity, round(revenue_line_items.list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by");  
			
			$this->db_sugar->from('revenue_line_items');
			
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');	
			
			$this->db_sugar->join('product_templates ptem', 'ptem.id = revenue_line_items.product_template_id', 'left');	

			$this->db_sugar->join('leads', 'leads.opportunity_id = revenue_line_items.opportunity_id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			
			$this->db_sugar->join('product_categories', 'product_categories.id = revenue_line_items.category_id', 'left');
			
			$this->db_sugar->join('users', 'users.id = revenue_line_items.created_by', 'left');
			
			$this->db_sugar->join('users users1', 'users1.id = revenue_line_items_cstm.user_id1_c', 'left');
			
			$this->db_sugar->join('users users2', 'users2.id = revenue_line_items_cstm.user_id2_c', 'left');
			
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('revenue_line_items.deleted', 0);	
			$this->db_sugar->where($where);
			
//and (revenue_line_items.sales_stage IS NULL OR revenue_line_items.sales_stage='')			
			$this->db_sugar->order_by('revenue_line_items.date_entered',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
	}
	
	public function get_last_service_sale_type($caseid) 
	{
		if($caseid != '')		
		{
			/*
			select revenue_line_items.id, sale_type_c as sale_type,  revenue_line_items.date_entered
			
			from revenue_line_items
						
			left join revenue_line_items_cstm on revenue_line_items_cstm.id_c = revenue_line_items.id			
			left join leads on leads.opportunity_id = revenue_line_items.opportunity_id			
			left join leads_cstm on leads_cstm.id_c = leads.id			
			where leads_cstm.case_id_c=1125379
			and revenue_line_items.deleted=0
			order by revenue_line_items.date_entered desc LIMIT 0,1
			*/
			$this->db_sugar->select("sale_type_c");  
			
			$this->db_sugar->from('revenue_line_items');
			
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');	
			
			$this->db_sugar->join('leads', 'leads.opportunity_id = revenue_line_items.opportunity_id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('revenue_line_items.deleted', 0);
			$this->db_sugar->order_by('revenue_line_items.date_entered',"desc");
			$this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0 && $record[0]->sale_type_c != ''){
				return $record[0]->sale_type_c;
			}
			else 
				return 0;
			
		}
	}
	
	public function get_services_with_id($rliid) 
	{
		if($rliid != '')		
		{
			/*SELECT concat(sale_type_c, ' - ', product_catalog_type_c) as sale_type, concat(product_categories.name, ' - ',revenue_line_items.name) as name , quantity, round(list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by
			FROM `revenue_line_items` 
			LEFT JOIN revenue_line_items_cstm  on revenue_line_items_cstm.id_c=revenue_line_items.id
			LEFT JOIN leads on leads.opportunity_id = revenue_line_items.opportunity_id
			LEFT JOIN leads_cstm on leads.id=leads_cstm.id_c
			LEFT JOIN product_categories ON product_categories.id=revenue_line_items.category_id
			LEFT JOIN users ON users.id=revenue_line_items.created_by
			LEFT JOIN users users1 ON users.id=revenue_line_items_cstm.user_id1_c
			where leads_cstm.case_id_c = 1014538 and revenue_line_items.deleted=0*/

			$this->db_sugar->select("revenue_line_items.id, product_template_id, revenue_line_items.category_id, sale_type_c, product_catalog_type_c,  concat(product_categories.name, ' - ',revenue_line_items.name) as name , round(quantity) as quantity, round(revenue_line_items.list_price,2) as list_price, revenue_line_items_cstm.discount_reason_c , round(revenue_line_items.discount_amount,2) as discount_amount, round(revenue_line_items_cstm.minimum_payment_c,2) as minimum_payment_c,  revenue_line_items_cstm.years_list_c, revenue_line_items.description,  product_types.id as service_type, revenue_line_items_cstm.for_entity_c as billed_to, revenue_line_items_cstm.override_commission_c as commission, revenue_line_items_cstm.lead_from_c as servicer, recurring_type_c, recurring_date_c, product_templates_cstm.service_status_c, rb.epi_business_revenuelineitems_1epi_business_ida as revenue_biz_id, revenue_line_items_cstm.res_reset_c, revenue_line_items_cstm.period_quarter_c, revenue_line_items_cstm.resservice_flag_c, revenue_line_items_cstm.state_list_c, prodd_productdiscount_cstm.editable_c, list_price_editable_c");
			//concat(users.first_name, ' ',users.last_name) as created_by, users.id as userid, 
			//concat(users1.first_name, ' ',users1.last_name) as approved_by,  users1.id as appbyid, concat(users2.first_name, ' ',users2.last_name) as sold_by, users2.id as soldbyid
			
			$this->db_sugar->from('revenue_line_items');
			 
			$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');	
			
			$this->db_sugar->join('epi_business_revenuelineitems_1_c rb', 'rb.epi_business_revenuelineitems_1revenuelineitems_idb = revenue_line_items.id AND rb.deleted=0', 'left');
			
			$this->db_sugar->join('product_categories', 'product_categories.id = revenue_line_items.category_id', 'left');
			
			$this->db_sugar->join('product_templates', 'product_templates.id = revenue_line_items.product_template_id', 'left');
			
			$this->db_sugar->join('product_templates_cstm', 'product_templates_cstm.id_c=product_templates.id', 'left');
			
			$this->db_sugar->join('product_types', 'product_types.id = product_templates.type_id', 'left');
			
			$this->db_sugar->join('prodd_productdiscount', 'prodd_productdiscount.discount_code = revenue_line_items_cstm.discount_reason_c', 'left');

			$this->db_sugar->join('prodd_productdiscount_cstm', 'prodd_productdiscount_cstm.id_c = prodd_productdiscount.id', 'left');

			//$this->db_sugar->join('users', 'users.id = revenue_line_items.created_by', 'left');
			//$this->db_sugar->join('users users1', 'users1.id = revenue_line_items_cstm.user_id1_c', 'left');
			
			//$this->db_sugar->join('users users2', 'users2.id = revenue_line_items_cstm.user_id2_c', 'left');
			
			$this->db_sugar->where('revenue_line_items.id', $rliid);	
			$this->db_sugar->where('revenue_line_items.deleted', 0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
	}
	
	//PPS data
	public function fetch_pps_personel_sugar($ppsid, $type = '') // type added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	{ 
		// if($type == ''){
		// 	$type == NULL;
		// } // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
		if($ppsid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c, balance_quarter_c, type_pps_c, accrued_assessed_c, levy_garnishment_c, resolution_date_entered_c, monthly_payment_amount_c , monthly_due_date_c, last_payment_date_c, default_date_c, resolution_c,resolution_years_c, seperate_assesment_c, credit_etps_c, multiple_csed_c,");  // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
			// select updated on 07-10-2022  https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
					
			//$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			$this->db_sugar->where('inves_pps.id', $ppsid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			if($type == '')
			{
				$where = " (ppsd_ppsdetail_cstm.type_pps_c IS NULL OR type_pps_c ='')";
			}
			else
			{
				$where = " ppsd_ppsdetail_cstm.type_pps_c = '" . $type . "'";
			}
			$this->db_sugar->where($where); // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->order_by('tax_period',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			file_put_contents('lenpps.txt',$ppsid . ', ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	public function fetch_pps_premium_personel_sugar($ppsid, $type = '') // type added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	{ 
		// if($type == ''){
		// 	$type == NULL;
		// } // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
		if($ppsid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c, balance_quarter_c, type_pps_c, accrued_assessed_c, levy_garnishment_c, resolution_date_entered_c, monthly_payment_amount_c , monthly_due_date_c, last_payment_date_c, default_date_c, resolution_c,resolution_years_c, seperate_assesment_c, credit_etps_c, multiple_csed_c,");  // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
			// select updated on 07-10-2022  https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
		
			$this->db_sugar->where('inves_pps.id', $ppsid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Premium');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			if($type == '')
			{
				$where = " (ppsd_ppsdetail_cstm.type_pps_c IS NULL OR type_pps_c ='')";
			}
			else
			{
				$where = " ppsd_ppsdetail_cstm.type_pps_c = '" . $type . "'";
			}
			$this->db_sugar->where($where); // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->order_by('tax_period',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			file_put_contents('lenpps.txt',$ppsid . ', ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}

	public function fetch_pps_premium_spouse_personel_sugar($ppsid, $type = '') // type added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	{ 
		// if($type == ''){
		// 	$type == NULL;
		// } // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
		if($ppsid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c, balance_quarter_c, type_pps_c, accrued_assessed_c, levy_garnishment_c, resolution_date_entered_c, monthly_payment_amount_c , monthly_due_date_c, last_payment_date_c, default_date_c, resolution_c,resolution_years_c, seperate_assesment_c, credit_etps_c, multiple_csed_c,");  // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
			// select updated on 07-10-2022  https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
		
			$this->db_sugar->where('inves_pps.id', $ppsid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Premium');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',1);
			if($type == '')
			{
				$where = " (ppsd_ppsdetail_cstm.type_pps_c IS NULL OR type_pps_c ='')";
			}
			else
			{
				$where = " ppsd_ppsdetail_cstm.type_pps_c = '" . $type . "'";
			}
			$this->db_sugar->where($where); // Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->order_by('tax_period',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			file_put_contents('lenpps.txt',$ppsid . ', ' . $this->db_sugar->last_query() . PHP_EOL, FILE_APPEND);
			file_put_contents('resoPremiumSp.txt','fetch_pps_premium_spouse_personel_sugar -> '.print_r($record,true) . PHP_EOL, FILE_APPEND);
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				file_put_contents('resoPremiumSp.txt','10224 :: fetch_pps_premium_spouse_personel_sugar -> '.print_r($record,true) . PHP_EOL, FILE_APPEND);
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	public function fetch_pps_state_personel_sugar($ppsid)
	{ 
		if($ppsid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c");
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('inves_pps.id', $ppsid);
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'State Personal');
			$this->db_sugar->like('inves_pps.name', 'State Personal');	
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			$this->db_sugar->order_by('tax_period',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}	
	
	public function fetch_pps_spouse_sugar($ppsid, $type = '')
	{ 
		if($type == ''){
			$type == NULL;
		}
		if($ppsid != '')		
		{
			//$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c, inves_pps_cstm.pps_instructions_c");

			$this->db_sugar->select(" inves_pps.id as ppsid, ppsd_ppsdetail.id, tax_period, round(balance,2) as balance, filing_status, account_status, DATE_FORMAT(csed_date_c,'%m/%d/%Y') AS csed_date_c, ro_recommended, fta_eligible, liens, round(reported_wage,2) as reported_wage, round(reported_se,2) as reported_se, round(reported_other,2) as reported_other, round(gross_monthly_income,2) as gross_monthly_income, round(taxes_withheld,2) as taxes_withheld, tin_c, balance_quarter_c, type_pps_c, accrued_assessed_c, levy_garnishment_c, resolution_date_entered_c, monthly_payment_amount_c , monthly_due_date_c, last_payment_date_c, default_date_c, resolution_c,resolution_years_c, seperate_assesment_c, credit_etps_c, multiple_csed_c");   // updated on 26-03-2024 https://trello.com/c/aDlJs5YL/769-investigations-multiple-csed-indicator
			
			$this->db_sugar->from('ppsd_ppsdetail');
			
			$this->db_sugar->join('ppsd_ppsdetail_cstm', 'ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id', 'left');	
			
			$this->db_sugar->join('inves_pps_ppsd_ppsdetail_1_c', 'inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id', 'left');	

			$this->db_sugar->join('inves_pps', 'inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida', 'left');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			//$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			//$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->where('inves_pps.id', $ppsid);  
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			//$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('ppsd_ppsdetail.deleted', 0);
			$this->db_sugar->where('inves_pps_ppsd_ppsdetail_1_c.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',1);
			
			if($type == '')
			{
				$where = " (ppsd_ppsdetail_cstm.type_pps_c IS NULL OR type_pps_c ='')";
			}
			else
			{
				$where = " ppsd_ppsdetail_cstm.type_pps_c = '" . $type . "'";
			}
			$this->db_sugar->where($where); // Added on 03-11-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->order_by('tax_period',"desc");
			

			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	public function fetch_pps_summary($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, account_status_c, compliance_summary_c, expense_analysis_c, income_analysis_c, asset_analysis_c, disposable_income_c, pps_instructions_c");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Federal Personal');
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	public function fetch_pps_summary_premium($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, account_status_c, compliance_summary_c, expense_analysis_c, income_analysis_c, asset_analysis_c, disposable_income_c, pps_instructions_c");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Premium');
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',0);
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	public function fetch_pps_summary_premium_spouse($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select(" inves_pps.id as ppsid, account_status_c, compliance_summary_c, expense_analysis_c, income_analysis_c, asset_analysis_c, disposable_income_c, pps_instructions_c");
			
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
				
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
			//$this->db_sugar->where('inves_pps.name', 'Federal Personal');
			$this->db_sugar->like('inves_pps.name', 'Premium');
			$this->db_sugar->where('inves_pps_cstm.pps_spouse_c ',1);
			
			$this->db_sugar->order_by('inves_pps.date_entered', 'DESC');  
            $this->db_sugar->limit(1,0);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	public function fetch_all_case_pps($caseid)
	{ 
		if($caseid != '')		
		{
			/*select  inves_pps.name, pps_status, inves_pps.date_modified, 	concat(um.first_name, ' ', um.last_name) as modified_by, concat(ua.first_name, ' ', ua.last_name) as assigned_to
			from inves_pps			
			left join inves_pps_cstm on inves_pps_cstm.id_c = inves_pps.id			
			left join cases_inves_pps_1_c on cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id			
			left join cases_cstm on cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida
			left join users as um on um.id=inves_pps.modified_user_id
			left join users as ua on ua.id=inves_pps.assigned_user_id
			where cases_cstm.case_id_c=	1125379			
			and inves_pps.deleted=0
			and cases_inves_pps_1_c.deleted=0
			*/
			$this->db_sugar->select("inves_pps.pps_type, inves_pps.id, inves_pps.name, pps_status, poa_issues, date(inves_pps.date_modified) as date_modified, 	concat(um.first_name, ' ', um.last_name) as modified_by, concat(ua.first_name, ' ', ua.last_name) as assigned_to, inves_pps.assigned_user_id, biz_poa_issues_new_c as biz_poa_issues, pps_instructions_c , verbal_poa_c, state_c, inves_pps_cstm.civil_penalties_c, inves_pps_cstm.shared_responsibility_c, inves_pps_cstm.passport_certification_c, inves_pps_cstm.passport_certification_years_c, inves_pps_cstm.mfj_cross_reference_c, inves_pps_cstm.issue_not_covered_in_poa_c, inves_pps_cstm.fta_eligible_c,inves_pps_cstm.pps_completed_using_c"); //updated on 18-04-2024 https://trello.com/c/YsTzk5k6/1215-cr-portal-add-required-tax-status-field-in-pps-tab
			// select updated on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
			$this->db_sugar->from('inves_pps');
			
			$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
			
			$this->db_sugar->join('cases', 'cases.id = cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('users as um', 'um.id=inves_pps.modified_user_id', 'left');	
			
			$this->db_sugar->join('users as ua', 'ua.id=inves_pps.assigned_user_id', 'left');			
					
			$this->db_sugar->where('cases_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('inves_pps.deleted', 0);
			$this->db_sugar->where('cases.deleted', 0);
			$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
									
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}
		else 
			return 0;	
	}
	
	//Stated Interview Data
	public function assets_details($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select("name, round(fmv_c,2) as fmv_c, round(loan_balance_c,2) as loan_balance_c, round(equity_c,2) as equity_c");
			$this->db_sugar->from('oa_otherassets');
			$this->db_sugar->join('oa_otherassets_cstm', 'oa_otherassets_cstm.id_c = oa_otherassets.id', 'left');	

			$this->db_sugar->join('fin_overview_oa_otherassets_1_c', 'fin_overview_oa_otherassets_1_c.fin_overview_oa_otherassets_1oa_otherassets_idb = oa_otherassets_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_oa_otherassets_1_c.fin_overview_oa_otherassets_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_oa_otherassets_1_c.fin_overview_oa_otherassets_1fin_overview_ida', 'left');	
			
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);	
			
			$this->db_sugar->where('oa_otherassets.deleted', 0);	
			//$this->db_sugar->order_by('name',"asc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
			
	}
	
	//Dependents
	public function getDependentInfo($caseid)
	{ 
		if($caseid != '')		
		{					
			$this->db_sugar->select("name, age, DATE_FORMAT(birth_date_c,'%m/%d/%Y') as birth_date_c, relationship");
			$this->db_sugar->from('ctr_dependents');
			$this->db_sugar->join('ctr_dependents_cstm', 'ctr_dependents_cstm.id_c = ctr_dependents.id', 'left');
			$this->db_sugar->join('fin_overview_ctr_dependents_1_c', 'fin_overview_ctr_dependents_1_c.fin_overview_ctr_dependents_1ctr_dependents_idb = ctr_dependents.id', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ctr_dependents_1_c.fin_overview_ctr_dependents_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
						
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0);
			$this->db_sugar->where('ctr_dependents.deleted', 0);
			$this->db_sugar->where('fin_overview_ctr_dependents_1_c.deleted', 0);			
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
			
	}

	public function assets_bank_amount($caseid)
	{ 
		if($caseid != '')		
		{
					
			$this->db_sugar->select("round(SUM(market_value_c),2) as market_value_c");
			$this->db_sugar->from('ctr_bank_accounts');
			$this->db_sugar->join('ctr_bank_accounts_cstm', 'ctr_bank_accounts_cstm.id_c = ctr_bank_accounts.id', 'left');	

			$this->db_sugar->join('fin_overview_ctr_bank_accounts_1_c', 'fin_overview_ctr_bank_accounts_1_c.fin_overview_ctr_bank_accounts_1ctr_bank_accounts_idb = ctr_bank_accounts_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ctr_bank_accounts_1_c.fin_overview_ctr_bank_accounts_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ctr_bank_accounts_1_c.fin_overview_ctr_bank_accounts_1fin_overview_ida', 'left');
			
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);	
			
			
			$this->db_sugar->where('ctr_bank_accounts.deleted', 0);
			//$this->db_sugar->where('owner_type_c', 'Person');			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record[0]->market_value_c;
			}
			else 
				return 0;
			
		}
			
	}
	
	public function fin_overview_details($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{			 		
			$this->db_sugar->select("fin_overview.id as fid, county_c, adults_over_65, adults_under_65, health_insurance, prescriptions_copays, healthcare_total, vehicle_own_actual_c, vehicle_operate_actual_c,  rent_payment, utilities_payment_c, telephone_payment_c, real_estate, total_actual_expenses, round(primary_tax_payer_before_tax_c,2) as primary_tax_payer_before_tax_c, round(spouseco_spouse_before_tax_c,2) as spouseco_spouse_before_tax_c,  round(cash_on_hand_c,2) as cash_on_hand_c, public_transportation_client_c, public_transportation_spouse_c, income_type_c, spouse_income_type_c, name_of_current_employer_ind_c, name_of_current_employer_sp_c, length_of_time_current_emp_c, len_of_time_current_emp_sp_c ,  round(primary_tax_payer_before_tax_c,2) as csi"); // round(statedincomespouse_c, 2) as statedincomespouse_c,
			//food_actual_c, housekeeping_actual_c, clothing_actual_c, personal_care_actual_c, total_food_actual_c, misc_actual_c,public_transport_actual_c, gas, water, sewer, trash,
			$this->db_sugar->from('fin_overview');
			$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview.id', 'left');	
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_cstm.id_c', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			//$this->db_sugar->where('logics_case_id_c', $caseid);			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record; 
			}
			else 
				return 0;			
		}			
	}
	
	public function real_estate_details($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{					
			$this->db_sugar->select("round(ctr_real_estate.monthly_payment,2) as monthly_payment, round(ctr_real_estate.balance,2) as balance, round(ctr_real_estate_cstm.fmv_c,2) as fmv_c");
			$this->db_sugar->from('ctr_real_estate');
			$this->db_sugar->join('ctr_real_estate_cstm', 'ctr_real_estate_cstm.id_c = ctr_real_estate.id', 'left');
			
			$this->db_sugar->join('fin_overview_ctr_real_estate_1_c', 'fin_overview_ctr_real_estate_1_c.fin_overview_ctr_real_estate_1ctr_real_estate_idb = ctr_real_estate.id', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ctr_real_estate_1_c.fin_overview_ctr_real_estate_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			//$this->db_sugar->where('ctr_real_estate_cstm.property_type_c', 'Primary');
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ctr_real_estate_1_c.fin_overview_ctr_real_estate_1fin_overview_ida', 'left');
			
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;			
		}			
	}
	
	//Commented and replaced on 29-09-2022 https://trello.com/c/Xgd8eL6C/179-stated-interview-update-needed
	/*public function additional_income_details($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{					
			$this->db_sugar->select("round(SUM(amount_c),2) as extra_income");
			
			$this->db_sugar->from('ai_additional_income_cstm');
			
			$this->db_sugar->join('ai_additional_income', 'ai_additional_income.id = ai_additional_income_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_ai_additional_income_1_c', 'fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1ai_additional_income_idb = ai_additional_income_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			$this->db_sugar->where('ai_additional_income.deleted', 0); 
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left');	
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record[0]->extra_income;
			}
			else 
				return 0;			
		}			
	}*/

	public function additional_income_details($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{	
			$finalAmount=0;				
			// $this->db_sugar->select("round(SUM(amount_c),2) as extra_income");
			$this->db_sugar->select("fin_overview_cstm.primary_tax_payer_before_tax_c,fin_overview_cstm.spouseco_spouse_before_tax_c,fin_overview_cstm.name_of_current_employer_ind_c,fin_overview_cstm.name_of_current_employer_sp_c,ai_additional_income_cstm.amount_month_c,ai_additional_income_cstm.amount_c,ai_additional_income.name");
			
			$this->db_sugar->from('ai_additional_income_cstm');
			
			$this->db_sugar->join('ai_additional_income', 'ai_additional_income.id = ai_additional_income_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_ai_additional_income_1_c', 'fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1ai_additional_income_idb = ai_additional_income_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left'); 

			$this->db_sugar->join('fin_overview', 'fin_overview.id = fin_overview_cstm.id_c', 'left'); 

			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			$this->db_sugar->where('ai_additional_income.deleted', 0); 
			$this->db_sugar->where('fin_overview.deleted', 0); 
			$this->db_sugar->where('fin_overview_ai_additional_income_1_c.deleted', 0); 
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0); 
			
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//echo $this->db_sugar->last_query();
			if(is_array($record) && count($record) > 0){
				$finalAmount=0;
				foreach($record as $key=>$value)
				{
					/*if( $value->amount_month_c!=$value->primary_tax_payer_before_tax_c && $value->amount_month_c!=$value->spouseco_spouse_before_tax_c && $value->name!=$value->name_of_current_employer_ind_c && $value->name!=$value->name_of_current_employer_sp_c)
					{
						$finalAmount += $value->amount_month_c;
						//echo $value->amount_month_c . ", ";
					}*/ // commented on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues

					// added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues

					$val_name = '';
					$fo_name = '';
					$fo_sp_name = '';
					if($value->name != '')
					{
						$val_name = ltrim($value->name,' ');
						$val_name = rtrim($val_name,' ');
					}
					if($value->name_of_current_employer_ind_c != '')
					{
						$fo_name = ltrim($value->name_of_current_employer_ind_c,' ');
						$fo_name = rtrim($fo_name,' ');
					}
					if($value->name_of_current_employer_sp_c != '')
					{
						$fo_sp_name = ltrim($value->name_of_current_employer_sp_c,' ');
						$fo_sp_name = rtrim($fo_sp_name,' ');
					}

					if(isset($value->name) && $val_name != $fo_name && $val_name != $fo_sp_name)
					{
						$finalAmount += $value->amount_month_c;
					}//ended on 13-04-2023
				}
				//echo $finalAmount;
				return $finalAmount;
			}
			else 
				return 0;			
		}			
	}
	//end added on 29-09-2022
	
	public function additional_income_details_new($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{					
			$this->db_sugar->select("fin_overview_cstm.primary_tax_payer_before_tax_c,fin_overview_cstm.spouseco_spouse_before_tax_c,fin_overview_cstm.name_of_current_employer_ind_c,fin_overview_cstm.name_of_current_employer_sp_c,ai_additional_income.id, ai_additional_income.name, ai_additional_income.description, round(ai_additional_income_cstm.amount_month_c,2) as amount_c , ai_additional_income_cstm.income_source_c");// added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
			//$this->db_sugar->select("ai_additional_income.id, ai_additional_income.name, ai_additional_income.description, round(ai_additional_income_cstm.amount_month_c,2) as amount_c , ai_additional_income_cstm.income_source_c");
			
			$this->db_sugar->from('ai_additional_income_cstm');
			
			$this->db_sugar->join('ai_additional_income', 'ai_additional_income.id = ai_additional_income_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_ai_additional_income_1_c', 'fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1ai_additional_income_idb = ai_additional_income_cstm.id_c', 'left');
			
			//$this->db_sugar->join('fin_overview_contacts_1_c', 'fin_overview_contacts_1_c.fin_overview_contacts_1fin_overview_ida = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left'); // commented on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
			
			// $this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = fin_overview_contacts_1_c.fin_overview_contacts_1contacts_idb', 'left'); // commented on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
						
			// $this->db_sugar->where('contacts_cstm.case_id_c', $caseid); // commented on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues

			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left'); // added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left'); // added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues	

			// added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
			$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left'); 

			$this->db_sugar->join('fin_overview', 'fin_overview.id = fin_overview_cstm.id_c', 'left');
			// end  added on 13-04-2023

			$this->db_sugar->where('leads_cstm.case_id_c', $caseid); // added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
			$this->db_sugar->where('ai_additional_income.deleted', 0); 
			$this->db_sugar->where('fin_overview_ai_additional_income_1_c.deleted', 0); 
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0); 
			//$this->db_sugar->where('fin_overview_contacts_1_c.deleted', 0); 
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ai_additional_income_1_c.fin_overview_ai_additional_income_1fin_overview_ida', 'left');	
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);			
						
			

			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				
				// added on 13-04-2023 https://trello.com/c/P5jmDXfi/1695-cr-portal-income-calculation-issues
				$finalArr = array();

				foreach($record as $key=>$value)
				{
					//echo $value->name . ', ' . $value->name_of_current_employer_ind_c;
					$val_name = '';
					$fo_name = '';
					$fo_sp_name = '';
					if($value->name != '')
					{
						$val_name = ltrim($value->name,' ');
						$val_name = rtrim($val_name,' ');
					}
					if($value->name_of_current_employer_ind_c != '')
					{
						$fo_name = ltrim($value->name_of_current_employer_ind_c,' ');
						$fo_name = rtrim($fo_name,' ');
					}
					if($value->name_of_current_employer_sp_c != '')
					{
						$fo_sp_name = ltrim($value->name_of_current_employer_sp_c,' ');
						$fo_sp_name = rtrim($fo_sp_name,' ');
					}
					if(isset($value->name) && $val_name != $fo_name && $val_name != $fo_sp_name)
					{
						$finalArr[] = $value;
					}
				}
				return $finalArr; // end  added on 13-04-2023
				//return $record; //  commented on 13-04-2023
			}
			else 
				return 0;			
		}			
	}

	/*public function wage_details($caseid) //cash_on_hand_amount
	{ 
		if($caseid != '')		
		{					
			$this->db_sugar->select("round(SUM(net_month),2) as wage");
			$this->db_sugar->from('epi_wages');
			$this->db_sugar->join('fin_overview_epi_wages_1_c', 'fin_overview_epi_wages_1_c.fin_overview_epi_wages_1epi_wages_idb = epi_wages.id', 'left');
			$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_epi_wages_1_c.fin_overview_epi_wages_1fin_overview_ida', 'left');	
			$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record[0]->wage;
			}
			else 
				return 0;			
		}			
	}*/
	
	public function additional_monthly_expense($caseid)
	{ 
		if($caseid != '')		
		{
					
			$this->db_sugar->select("ame_additional_monthly_expen_cstm.id_c as add_expense_id, name, round(amount_c,2) as amount_actual_c");
			$this->db_sugar->from('ame_additional_monthly_expen');
			$this->db_sugar->join('ame_additional_monthly_expen_cstm', 'ame_additional_monthly_expen_cstm.id_c = ame_additional_monthly_expen.id', 'left');		
			$this->db_sugar->join('fin_overview_ame_additional_monthly_expen_1_c', 'fin_overview_ame_additional_monthly_expen_1_c.fin_overvief66y_expen_idb = ame_additional_monthly_expen_cstm.id_c', 'left');
			
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ame_additional_monthly_expen_1_c.fin_overview_ame_additional_monthly_expen_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ame_additional_monthly_expen_1_c.fin_overview_ame_additional_monthly_expen_1fin_overview_ida', 'left');
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);	
			
			$this->db_sugar->where('ame_additional_monthly_expen.deleted', 0);
			
			$this->db_sugar->where('fin_overview_ame_additional_monthly_expen_1_c.deleted', 0);					
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0);							
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
			
	}
	
	public function vehicle_expense($caseid)
	{ 
		if($caseid != '')		
		{
					
			$this->db_sugar->select("ctr_vehicles.id, round(ctr_vehicles.monthly_payment,2) as monthly_payment, round(ctr_vehicles.balance,2) as balance, round(ctr_vehicles_cstm.market_value_c,2) as market_value_c");
			$this->db_sugar->from('ctr_vehicles');
			$this->db_sugar->join('ctr_vehicles_cstm', 'ctr_vehicles_cstm.id_c = ctr_vehicles.id', 'left');	
			
			$this->db_sugar->join('fin_overview_ctr_vehicles_1_c', 'fin_overview_ctr_vehicles_1_c.fin_overview_ctr_vehicles_1ctr_vehicles_idb = ctr_vehicles.id', 'left');

			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview_ctr_vehicles_1_c.fin_overview_ctr_vehicles_1fin_overview_ida', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
						
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			
			//$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_ctr_vehicles_1_c.fin_overview_ctr_vehicles_1fin_overview_ida', 'left');
			
			//$this->db_sugar->where('fin_overview_cstm.logics_case_id_c', $caseid);	
			
			$this->db_sugar->where('ctr_vehicles.deleted', 0);	
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0);	
			$this->db_sugar->where('fin_overview_ctr_vehicles_1_c.deleted', 0);	
			$this->db_sugar->order_by('ctr_vehicles.date_entered');	
			
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0){
				return $record;
			}
			else 
				return 0;
			
		}
			
	}	
	
	public function fetch_tax_case_intake($caseid)
	{
		if($caseid != '')		
		{
			$this->db_sugar->select("cid_fed_intake_notes_c, cid_state_intake_notes_c,  cid_fed_debt_amount_c, cid_state_debt_amount_c, scr_tax_years_needed_c, cid_biz_issues_c, cid_fed_garnishment_c, cid_state_garnishment_c, fed_bank_lvy_c, state_bank_levy_c, cid_fed_audit_c, cid_state_audit_c, cid_passport_c, cid_license_issue_c, cid_immigration_c, cid_professional_license_c, cid_fed_deadline_reason_c, cid_state_deadline_reason_c, cid_fed_ro_c, cid_state_ro_c, cid_fed_ro_name_c, state_revenue_officer_name_c, cid_fed_ro_contact_c, cid_state_ro_phone_c, cid_fed_ro_fax_c, cid_state_ro_fax_c, DATE_FORMAT(cid_fed_deadline_date_c, '%m/%d/%Y') as cid_fed_deadline_date_c, DATE_FORMAT(personal_state_deadline_date_c, '%m/%d/%Y') as personal_state_deadline_date_c, fr_survey_reason_c, fr_survey_unfiled_c, fr_survey_incometype_c, fr_survey_incomerange_c, fr_survey_taxprohelp_c, fr_survey_addnotes_c, fr_survey_complete_c"); 
			$this->db_sugar->from('leads_cstm');
			//$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			$this->db_sugar->where('case_id_c', $caseid);						
			
			$data = $this->db_sugar->get();
			$record = $data->result();			
		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}
	
	public function get_service_types($type, $related, $status_check, $entity)
	{ //print $status_check . ', ' . $related;
		if($type != '' && $related != '') 
		{ 
			/*
			$this->db_sugar->select("product_templates.id, product_templates.name, round(list_price,2) as list_price, product_types.id as category_id"); 
			$this->db_sugar->from('product_templates');
			$this->db_sugar->join('product_types', 'product_types.id = product_templates.type_id', 'left');
			$this->db_sugar->where('type_id', $type);	
			$this->db_sugar->where('product_templates.deleted', 0);	
			
			$this->db_sugar->join('product_templates pt', 'rli.product_template_id=pt.id', 'left');
			*/ 
			
			$this->db_sugar->select("id, name, round(list_price,2) as list_price, category_id, year_enable_c, discount_reason_c, period_quarter_c, res_reset_type_c, resservice_flag_c, state_enable_c, list_price_editable_c"); 
			$this->db_sugar->from('product_templates');
			
			$this->db_sugar->join('product_templates_cstm','product_templates_cstm.id_c=product_templates.id');

			$this->db_sugar->where('type_id', $type);	
			//$this->db_sugar->where('related_to_c', $related);
			
			//if($related != '')
			{
				$this->db_sugar->like('related_to_c', $related, 'both'); 
			}
			if($status_check != 1)
			{
				$this->db_sugar->where('service_status_c', 'Active');
			}				
			$this->db_sugar->where('deleted', 0);
				
			if($entity != '')
			{
				if($entity == 'Individual')
				{
					$where = "(service_target_c='Individuals' || service_target_c='Both')";
				}
				else
				{
					$where = "(service_target_c='Both' || service_target_c = 'Businesses')";
				}
				$this->db_sugar->where($where);
			}
			$this->db_sugar->order_by('name');		 				
			
			$data = $this->db_sugar->get();
			//print $this->db_sugar->last_query();
			$record = $data->result();		
		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}
	
	public function get_filtered_service_types($related, $status_check, $entity)
	{//print $type . ', ' . $related;
		if($related != '')
		{ 
			/*
			SELECT `product_types`.`id`, `product_types`.`name`
FROM `product_types`
INNER JOIN `product_templates` ON `product_templates`.`type_id`=`product_types`.`id`
INNER JOIN `product_templates_cstm` ON `product_templates_cstm`.`id_c`=`product_templates`.`id`
WHERE product_templates_cstm.related_to_c LIKE '%BAM%' ESCAPE '!'
AND `product_templates`.`deleted` = 0 AND product_templates_cstm.related_to_c LIKE'%BAM%' AND product_templates_cstm.service_status_c='Active'
GROUP BY `type_id`
ORDER BY `product_types`.`list_order` ASC
			*/ 
			
			$this->db_sugar->select("product_types.id,product_types.name"); 
			$this->db_sugar->from('product_types');
			
			$this->db_sugar->join('product_templates','product_templates.type_id=product_types.id', 'inner');
			$this->db_sugar->join('product_templates_cstm','product_templates_cstm.id_c=product_templates.id', 'inner');

			//$this->db_sugar->where('type_id', $type);	
			
			$this->db_sugar->like('product_templates_cstm.related_to_c', $related, 'both'); 
			
			if($status_check == 0)
			{
				$this->db_sugar->where('product_templates_cstm.service_status_c', 'Active');
			}
			if($status_check == 1)
			{
				$this->db_sugar->where('product_templates_cstm.service_status_c', 'Inactive');
			}	
			
			if($entity != '')
			{
				if($entity == 'Individual')
				{
					$where = "(service_target_c='Individuals' || service_target_c='Both')";
				}
				else
				{
					$where = "(service_target_c='Both' || service_target_c = 'Businesses')";
				}
				$this->db_sugar->where($where);
			}
			
			$this->db_sugar->where('product_templates.deleted', 0);		 				
			$this->db_sugar->group_by('type_id');		 				
			$this->db_sugar->order_by('product_types.list_order', 'ASC');		 				
			
			$data = $this->db_sugar->get();
			//print $this->db_sugar->last_query();
			$record = $data->result();		
		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0;
		}
	}
	
	public function get_service_types_without_id()
	{
		$this->db_sugar->select("id, name, round(list_price,2) as list_price, category_id"); 
		$this->db_sugar->from('product_templates');

		//$this->db_sugar->where('type_id', $type);	
		$this->db_sugar->where('deleted', 0);		 				
		
		$data = $this->db_sugar->get();
		$record = $data->result();		
	
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else 
			return 0;
	}
	
	public function fetch_finance_info($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select("ctax_finance.name, application_date, application_status, recommended, program_type_c, finance_notes_c"); 
			$this->db_sugar->from('ctax_finance');
			$this->db_sugar->join('ctax_finance_cstm', 'ctax_finance_cstm.id_c = ctax_finance.id', 'left');
			$this->db_sugar->join('leads_ctax_finance_1_c', 'leads_ctax_finance_1_c.leads_ctax_finance_1ctax_finance_idb = ctax_finance.id', 'left');
			$this->db_sugar->join('leads', 'leads_ctax_finance_1_c.leads_ctax_finance_1leads_ida = leads.id', 'left');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			//$this->db_sugar->where('leads_cstm.lead_level_c', 'Case Review');	
			//$this->db_sugar->where('leads_cstm.custom_status_c', 'Assignment Ready');	
			//$this->db_sugar->where('leads.status', 'Pending');	
			$this->db_sugar->where('ctax_finance.recommended', 'Recommended'); 
			$this->db_sugar->where('ctax_finance.application_status', 'Approved'); 	 					
			
			$data = $this->db_sugar->get();
			$record = $data->result();			
		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
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
	
	public function fetch_case_fax_list($caseid)
	{ 
		if($caseid != '')		
		{
			$this->db_sugar->select("fax_fax.name, date(fax_fax.date_entered) as date_entered, date(fax_fax.date_modified) as date_modified, fax_number_c, concat(users.first_name, ' ', users.last_name)	as uname, status_c ");  
			
			$this->db_sugar->from('fax_fax');
			
			$this->db_sugar->join('fax_fax_cstm', 'fax_fax_cstm.id_c = fax_fax.id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fax_fax_cstm.module_id_c', 'left');
			
			$this->db_sugar->join('users', 'users.id = fax_fax.created_by', 'left');
			
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			$this->db_sugar->where('fax_fax.deleted', 0);
				 					
			
			$data = $this->db_sugar->get();
			$record = $data->result();			
		
			if(is_array($record) && count($record) > 0)
			{
				return $record;
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
	
	public function delete_extra_expenses($id)
	{
		$uid = $this->session->userdata('User_id');
		session_write_close();
		
		if($id != '' && $uid != '')
		{
			$sql_sugar = "UPDATE fin_overview_ame_additional_monthly_expen_1_c set deleted=1 where fin_overvief66y_expen_idb='" . $id . "'";
			//print $sql_sugar;
			$query_sugar = $this->db_sugar->query($sql_sugar);
				
			if($query_sugar)
			{
				$sql_sugar1 = "UPDATE ame_additional_monthly_expen set deleted=1, date_modified=now(), modified_user_id='" . $uid . "' where id='" . $id . "'";
				//print $sql_sugar1;
				$query_sugar1 = $this->db_sugar->query($sql_sugar1);
			
				return 1 ; 
			}
			else
			{				
				$err =  $this->db_sugar->error();
				print_r($err);
				return 0;
			}							
			//print $sql_sugar;
		}
		else
		{
			return 0;
		}
	}
	
	//Fetch cases for so assignment tab
	public function fetch_so_assignments()
	{
		$this->db_sugar->select(" leads.id, concat(leads.first_name,' ', leads.last_name) as name, leads.assigned_user_id, leads_cstm.lead_level_c, leads_cstm.case_id_c, leads_cstm.language_prequal_c, leads_cstm.cr_fee_c, leads_cstm.lending_partner_eligibility_c, leads_cstm.financial_servicing_eligibil_c, leads_cstm.rel_logics_billing_status_c,  concat(users.first_name,' ',users.last_name) as username,
 
		(SELECT round(SUM((list_price - ifnull(discount_amount, 0) ) * quantity),2) 
		from revenue_line_items 
		left join leads ON leads.opportunity_id=revenue_line_items.opportunity_id 
		left join revenue_line_items_cstm rc ON rc.id_c=revenue_line_items.id 
		left join leads_cstm lc ON lc.id_c=leads.id 
		where rc.sale_type_c='Initial Service' AND lc.case_id_c = leads_cstm.case_id_c AND revenue_line_items.deleted=0) AS initial_fee , derived_table.* ");
		
		$this->db_sugar->from('leads');
		
		$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
		
		$this->db_sugar->join('users', 'users.id = leads.assigned_user_id', 'left');
		
		
		$this->db_sugar->join('(SELECT lc1.case_id_c as derived_id, ctax_finance_cstm.program_type_c, ctax_finance.lender, round(ctax_finance.approved_amount,2) as approved_amount, round(ctax_finance.net_approved,2) as net_approved 
			FROM ctax_finance 
			INNER JOIN ctax_finance_cstm on ctax_finance_cstm.id_c=ctax_finance.id 
			INNER JOIN leads_ctax_finance_1_c on leads_ctax_finance_1_c.leads_ctax_finance_1ctax_finance_idb = ctax_finance.id 
			INNER JOIN leads_cstm as lc1 on leads_ctax_finance_1_c.leads_ctax_finance_1leads_ida = lc1.id_c
			WHERE ctax_finance.deleted=0 
			AND ctax_finance.recommended="Recommended" 
			AND ctax_finance.application_status="Approved" 
			AND leads_ctax_finance_1_c.deleted=0 ) as derived_table ', 'derived_table.derived_id=leads_cstm.case_id_c', 'left');
		
		
		$this->db_sugar->where('leads_cstm.custom_status_c', 'SO Assignment');	
		$this->db_sugar->where('leads.status', 'Pending');	
		 	 					
		$this->db_sugar->where('leads.deleted', 0);		
		
		$data = $this->db_sugar->get();
		$record = $data->result();			
	
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else
		{				
			return 0;
		}
	}
	
	
	
	public function fetch_writing_assignments($language,$payment_status,$team_id)
	{ //$user_ids,
		//$language,$payment_status,$user_ids,$team_id
		
		/*
		SELECT `cases`.`id`, `cases_cstm`.`case_id_c`, DATE_FORMAT(cases.date_entered, '%m/%d/%Y') as date_created, `cases_cstm`.`investigation_advocate_statu_c`, `cases`.`assigned_user_id`, `leads_cstm`.`language_prequal_c`, concat(users.first_name, ' ', users.last_name) as username, `cases_cstm`.`investigation_type_c`, `income_type_c`, `spouse_income_type_c`, `derived_table2`.*, `derived_table3`.*
FROM `leads`
LEFT JOIN `leads_cstm` ON `leads_cstm`.`id_c` = `leads`.`id`
LEFT JOIN `fin_overview_leads_1_c` ON `fin_overview_leads_1_c`.`fin_overview_leads_1leads_idb` = `leads`.`id`
LEFT JOIN `fin_overview_cstm` ON `fin_overview_cstm`.`id_c` = `fin_overview_leads_1_c`.`fin_overview_leads_1fin_overview_ida`
LEFT JOIN `cases_cstm` ON `cases_cstm`.`case_id_c`=`leads_cstm`.`case_id_c`
LEFT JOIN `cases` ON `cases`.`id`=`cases_cstm`.`id_c`
LEFT JOIN `users` ON `users`.`id` = `cases`.`assigned_user_id`
LEFT JOIN `cases_ths_ths_1_c` ON `cases_ths_ths_1_c`.`cases_ths_ths_1cases_ida` = `cases`.`id`
LEFT JOIN `ths_ths` ON `ths_ths`.`id` = `cases_ths_ths_1_c`.`cases_ths_ths_1ths_ths_idb`
LEFT JOIN `ths_ths_cstm` ON `ths_ths_cstm`.`id_c` = `ths_ths`.`id`
LEFT JOIN (SELECT l1.id as derived_id2,  billing_status_c
		FROM aggre_aggregatevalues_cstm  as ag_cstm 
		INNER JOIN aggre_aggregatevalues ag ON ag.id=ag_cstm.id_c
		INNER JOIN leads_aggre_aggregatevalues_1_c as lg on lg.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb=ag_cstm.id_c 
		INNER JOIN leads l1 ON l1.id=lg.leads_aggre_aggregatevalues_1leads_ida
		WHERE ag.deleted=0
		AND lg.deleted=0 AND ag_cstm.logics_status_c <> "CCS" AND ag_cstm.logics_status_c <> "CCS Review" AND ag_cstm.logics_status_c <> "Refunded" AND ag_cstm.logics_status_c <> "Refund Requested" ) as derived_table2 ON `derived_table2`.`derived_id2`=`leads`.`id`
LEFT JOIN (SELECT cases_ths_ths_1_c.cases_ths_ths_1cases_ida,  ths_ths.date_modified as ths_date, ths_record_type, ths_client_type, DATE_FORMAT(ths_report_date_c,"%m/%d/%Y") as ths_report_date_c, location_c 
	FROM ths_ths 
	INNER JOIN ths_ths_cstm ON ths_ths_cstm.id_c = ths_ths.id
	INNER JOIN cases_ths_ths_1_c on cases_ths_ths_1_c.cases_ths_ths_1ths_ths_idb=ths_ths.id
	INNER JOIN cases as c ON c.id=cases_ths_ths_1_c.cases_ths_ths_1cases_ida
	WHERE ths_ths.deleted=0 
	AND cases_ths_ths_1_c.deleted=0 
	AND ths_ths_cstm.location_c= "DMS_SSN" AND ths_record_type = "Individual" GROUP BY ths_ths_cstm.case_id_c) as derived_table3 ON `derived_table3`.`cases_ths_ths_1cases_ida`=`cases`.`id`
WHERE `cases_cstm`.`investigation_advocate_statu_c` = 'Strategy Assigned'
AND `cases`.`assigned_user_id` = 1
AND `leads`.`deleted` = 0
AND `cases`.`deleted` = 0
AND `fin_overview_leads_1_c`.`deleted` = 0
*/
		
		// $this->db_sugar->select(" cases.id, cases_cstm.case_id_c, DATE_FORMAT(cases.date_entered,'%m/%d/%Y') as date_created, cases_cstm.investigation_advocate_statu_c, cases.assigned_user_id, leads_cstm.language_prequal_c, concat(users.first_name,' ',users.last_name) as username, cases_cstm.investigation_type_c,  income_type_c, spouse_income_type_c, derived_table2.*, derived_table3.*,lm.lead_src, concat(leads.first_name,' ',leads.last_name) as client_name ");//added lm.lead_src on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related // Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
			$this->db_sugar->select("inves_pps_cstm.state_c, inves_pps_cstm.split_pps_call_c, cases.name as client_name, leads_cstm.marital_status_c, leads_cstm.filing_status_state_c, cases_cstm.transcripts_only_eligible_c, aggre_aggregatevalues_cstm.logics_folder_c, inves_pps.id as pps_id, cases.id, cases_cstm.case_id_c, DATE_FORMAT(cases.date_entered,'%m/%d/%Y') as date_created, cases_cstm.investigation_advocate_statu_c, cases.assigned_user_id, leads_cstm.language_prequal_c, concat(COALESCE(users.first_name,''),' ',users.last_name) as username, concat(cu.first_name,' ',cu.last_name) as case_user, cases_cstm.investigation_type_c,  income_type_c, spouse_income_type_c, derived_table2.*, pps_type, pps_call_type_c");  // Updated on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments

		$this->db_sugar->from('leads');
		
		$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');

		// Added on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
		$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida=leads.id AND leads_aggre_aggregatevalues_1_c.deleted=0', 'left');			
		$this->db_sugar->join('aggre_aggregatevalues', 'aggre_aggregatevalues.id=leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb AND aggre_aggregatevalues.deleted=0', 'left');			
		$this->db_sugar->join('aggre_aggregatevalues_cstm', 'aggre_aggregatevalues_cstm.id_c=aggre_aggregatevalues.id', 'left');
		// end Added on 30-05-2024

		$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1leads_idb = leads.id AND fin_overview_leads_1_c.deleted=0', 'left');

		// Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
		$this->db_sugar->join('leads_leadm_leadmarketing_1_c llm', 'llm.leads_leadm_leadmarketing_1leads_ida=leads.id AND llm.deleted=0' , 'left'); //added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
		$this->db_sugar->join('leadm_leadmarketing lm', 'lm.id=llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lm.deleted=0', 'left');//added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
		$this->db_sugar->join('leadm_leadmarketing_cstm lmc', 'lmc.id_c=llm.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND lmc.attributable_c=1', 'left');//added on 26-02-2024 https://trello.com/c/ER1LvMsE/1052-cr-portal-view-update-tax-status-related
		// end Commented on 30-05-2024
		
		// AND fin_overview_leads_1_c.deleted=0
		$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida', 'left');
		
		$this->db_sugar->join('cases_cstm', 'cases_cstm.case_id_c=leads_cstm.case_id_c', 'left');
		
		$this->db_sugar->join('cases', 'cases.id=cases_cstm.id_c', 'left');
		
		// Added on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
		$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1cases_ida=cases.id', 'left');
		$this->db_sugar->join('inves_pps', 'inves_pps.id=cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb', 'left');
		$this->db_sugar->join('inves_pps_cstm', 'inves_pps_cstm.id_c = inves_pps.id', 'left');

		$this->db_sugar->join('users', 'users.id = inves_pps.assigned_user_id', 'left');
		$this->db_sugar->join('users as cu', 'cu.id = cases.assigned_user_id', 'left');
		// end added on 30-05-2024

		//$this->db_sugar->join('users', 'users.id = cases.assigned_user_id', 'left'); // Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
		
		
				
		$this->db_sugar->join('(SELECT l1.id as derived_id2,  ag_cstm.billing_status_c
		FROM aggre_aggregatevalues_cstm  as ag_cstm 
		INNER JOIN aggre_aggregatevalues ag ON ag.id=ag_cstm.id_c
		INNER JOIN leads_aggre_aggregatevalues_1_c as lg on lg.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb=ag_cstm.id_c 
		INNER JOIN leads l1 ON l1.id=lg.leads_aggre_aggregatevalues_1leads_ida
		WHERE ag.deleted=0
		AND lg.deleted=0 AND ag_cstm.logics_status_c <> "CCS" AND ag_cstm.logics_status_c <> "CCS Review" AND ag_cstm.logics_status_c <> "Refunded" AND ag_cstm.logics_status_c <> "Refund Requested" ) as derived_table2', 'derived_table2.derived_id2=leads.id', 'left');
		
			
		/*$this->db_sugar->join('(SELECT cases_ths_ths_1_c.cases_ths_ths_1cases_ida,  ths_ths.date_modified as ths_date, ths_record_type, ths_client_type, DATE_FORMAT(ths_report_date_c,"%m/%d/%Y") as ths_report_date_c, location_c 
	FROM ths_ths 
	INNER JOIN ths_ths_cstm ON ths_ths_cstm.id_c = ths_ths.id
	INNER JOIN cases_ths_ths_1_c on cases_ths_ths_1_c.cases_ths_ths_1ths_ths_idb=ths_ths.id
	INNER JOIN cases as c ON c.id=cases_ths_ths_1_c.cases_ths_ths_1cases_ida
	WHERE ths_ths.deleted=0 
	AND cases_ths_ths_1_c.deleted=0 
	AND ths_ths_cstm.location_c= "DMS_SSN" AND (ths_record_type = "Individual" OR ths_record_type="Spouse")   GROUP BY ths_ths_cstm.case_id_c) as derived_table3',  'derived_table3.cases_ths_ths_1cases_ida=cases.id', 'left');*/ // Commented on 30-05-2024 https://trello.com/c/hmjqNbWj/1347-cr-portal-update-writing-assignments-to-match-pps-assignments
		
		$this->db_sugar->where('cases_cstm.investigation_advocate_statu_c', 'Strategy Assigned');	
		$this->db_sugar->where('cases.assigned_user_id', 1);
		$this->db_sugar->where('leads.deleted', 0);		
		$this->db_sugar->where('cases.deleted', 0);		
		$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0);		

		//$language,$payment_status
		if($language != '' && $language != null)
		{
			$this->db_sugar->where('leads_cstm.language_prequal_c', $language);
		}
		if($payment_status != '' && $payment_status != null)
		{
			$this->db_sugar->where('aggre_aggregatevalues_cstm.billing_status_c', $payment_status);
		}
		if($team_id != '' && $team_id != null)
		{
			$this->db_sugar->where('leads.team_id', $team_id);
		}
		/*if(is_array($user_ids) && count($user_ids) > 0)
		{
			$uid_list = '';
			foreach($user_ids as $uid)
			{
				$uid_list .= "'" . $uid . "',";
			}
			$uid_list = rtrim($uid_list,',');
			$where = 'cases.assigned_user_id IN (' . $uid_list . ')';
			$this->db_sugar->where($where);
		}*/
		
		$data = $this->db_sugar->get();
		//print $this->db_sugar->last_query();
		$record = $data->result();			
			
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else
		{				
			return 0;
		}
	}
	
	
	public function update_case_assigned_to($assign_user_id,  $case_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
				
		$instance_url = $this->sugar_url . "rest/v11"; 
		//print 	$instance_url . ', ' . $user_name. ', ' . $user_pwd. ', ' . $uid. ', ' . $assign_user_id;
		//print '--------------';
		//print_r($case_array);
		//return true;
		
		if($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($case_array) > 0)
		{	
			$record = array(
			
					"massupdate_params" => array(
						"uid" => $case_array,						
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
			
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/Cases/MassUpdate";
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
	
	
	public function check_business($caseid)
	{
		if($caseid != '')
		{
			$this->db_sugar->select("epi_business.id, epi_business.name");
			
			$this->db_sugar->from('epi_business');
			
			$this->db_sugar->join('leads_epi_business_1_c', 'leads_epi_business_1_c.leads_epi_business_1epi_business_idb = epi_business.id', 'left');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads_epi_business_1_c.leads_epi_business_1leads_ida', 'left');			
			
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);
			$this->db_sugar->where('leads_epi_business_1_c.deleted', 0);
			$this->db_sugar->where('epi_business.deleted', 0);
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			//print_r($record);
		
			if(is_array($record) && count($record) > 0)
			{
				return json_encode($record);
			}
			else 
				return 0;
		}
		else 
				return 0;
	}
	
	public function productTemplateGet($serviceType)
    {
		if($serviceType != '')
		{			
			$this->db_sugar->select(" id_c,recurring_date_check_c");
			
			$this->db_sugar->from('product_templates_cstm');
			
			$this->db_sugar->where('id_c', $serviceType);
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			if(is_array($record) && count($record) > 0)
			{
				$template_check  = $record[0]->recurring_date_check_c;
				if($template_check=='1')
				{
					$status='1';
				}
				else
				{
					$status='0';
				}
				return $status;
			}
			else
				return 0;			
		}
		else
			return 0;
    }
	
	public function productTemplateFieldCheck($serviceType)
    {
		if($serviceType != '')
		{			
			$this->db_sugar->select(" id_c,year_enable_c, discount_reason_c, period_quarter_c, res_reset_type_c, resservice_flag_c, state_enable_c, list_price_editable_c");
			
			$this->db_sugar->from('product_templates_cstm');
			
			$this->db_sugar->where('id_c', $serviceType);
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else
				return 0;			
		}
		else
			return 0;
	} 
	
	public function PackageOptionCheck($serviceType)
    {
		if($serviceType != '')
		{			
			$this->db_sugar->select(" is_package_c ");
			
			$this->db_sugar->from('product_types_cstm');
			
			$this->db_sugar->where('id_c', $serviceType);
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->is_package_c == 1)
				{
					return 1;
				}
				else
				{
					return 0;						
				}					
			}
			else
				return 0;			
		}
		else
			return 0;
	} 
	
	public function getServiceTypesWithoutPackage($related, $entity)
	{
		if($entity != 'Individual')
		{
			$entity = 'Business';
		}
		/*
		SELECT `product_types`.`id`, `product_types`.`name`
		FROM `product_types`
		INNER JOIN `product_types_cstm` ON `product_types_cstm`.`id_c` = `product_types`.`id`
		INNER JOIN product_templates ON product_templates.type_id=product_types.id 
		AND product_types.deleted='0' 
		INNER JOIN `product_templates_cstm` ON `product_templates_cstm`.`id_c` = `product_templates`.`id`

		WHERE `product_types_cstm`.`is_package_c` !='1'
		AND `product_templates_cstm`.`service_status_c` = 'Active'
		AND `service_target_c` IN('Individuals','Both') 
		AND  product_templates_cstm.related_to_c  LIKE '%CTax%' 
		AND `product_types`.`deleted` = 0
		AND product_templates.deleted='0'
		GROUP BY type_id order by product_types.list_order ASC
		*/

		$this->db_sugar->select(" product_types.id, product_types.name ");
			
		$this->db_sugar->from('product_types');
		
		$this->db_sugar->join('product_types_cstm', 'product_types_cstm.id_c = product_types.id', 'left');	
		$this->db_sugar->join('product_templates', 'product_templates.type_id = product_types.id', 'left');				
		$this->db_sugar->join('product_templates_cstm', 'product_templates_cstm.id_c = product_templates.id', 'left');	
		
		$this->db_sugar->where('product_types_cstm.is_package_c', 0);
		$this->db_sugar->where('product_templates_cstm.service_status_c', 'Active');

		$where = "(service_target_c IN('$entity','Both') )";
		$this->db_sugar->where($where);
		$this->db_sugar->like('product_templates_cstm.related_to_c', $related);
		$this->db_sugar->where('product_types.deleted', 0);
		$this->db_sugar->where('product_templates.deleted', 0);
		$this->db_sugar->group_by('type_id');
		$this->db_sugar->order_by('product_types.list_order', 'ASC');
		//GROUP BY type_id order by product_types.list_order ASC
						
		$data = $this->db_sugar->get();
		$record = $data->result();
		//print $this->db_sugar->last_query();
		if(is_array($record) && count($record) > 0)
		{
			return 	$record;				
		}
		else
			return 0;
	}	
	
	public function checkDiscount($discount_reason)
    {
		if($discount_reason != '')
		{			
			$this->db_sugar->select(" id, discount_code, round(discount_amount,2) as discount_amount, discount_type_c, editable_c");
			
			$this->db_sugar->from('prodd_productdiscount');
			
			$this->db_sugar->join('prodd_productdiscount_cstm', 'prodd_productdiscount_cstm.id_c = prodd_productdiscount.id', 'inner');			
			
			$this->db_sugar->where('prodd_productdiscount.deleted', 0);

			$this->db_sugar->where('prodd_productdiscount.discount_code', $discount_reason);
						
			$data = $this->db_sugar->get();
			$record = $data->result();
			//print $this->db_sugar->last_query();
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else
				return 0;			
		}
		else
			return 0;
	}
	
	/*public function updateSOAssignment($lead_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');		
		
		$instance_url = $this->sugar_url . "rest/v11"; 
		
		if($user_name != '' && $user_pwd != ''  && $uid != '' && count($lead_array) > 0)
		{
			$record = array(
			
				"massupdate_params" => array(
					"uid" => $lead_array,						
					'status' => 'Pending',
					'custom_status_c' => 'SO Assignment',						
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
			$oauth_token = $oauth2_token_response_obj->access_token;
			
			//Create Records - PUT /<module>/id
			if($oauth_token != '')
			{
				$url = $instance_url . "/Leads/MassUpdate" ;
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
				
				echo $curl_request;
			}
		}
		else
		{
			return 0;
		}
	}*/
	
	public function getInitialServiceDD()
	{	

		$curl = curl_init();
		$url = $this->sugar_url . "rest/v11_1/getDropdowns";
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "{\r\n  \"dropdowns\":{\"name\": \"lead_level_list,rli_discount_reason_list,years_list,period_quarter_list,res_reset_list\"}\r\n}",
		CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json"
		),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		$response_array = array();

		if($response != '')
		{ //print 'if ' . $response;
			$response_array_sugar = json_decode($response);
			$response_array['sale_type'] = $response_array_sugar->lead_level_list;
			$response_array['discount_reason'] = $response_array_sugar->rli_discount_reason_list;
			$response_array['years'] = $response_array_sugar->years_list;
			$response_array['quarters'] = $response_array_sugar->period_quarter_list;
			$response_array['reset_rollin'] = $response_array_sugar->res_reset_list;
			//print_r($response_array);
		}
		$sqlquery = "SELECT product_types.id,product_types.name FROM product_types INNER JOIN product_templates ON product_templates.type_id=product_types.id AND product_types.deleted='0' INNER JOIN product_templates_cstm ON product_templates_cstm.id_c=product_templates.id WHERE  product_templates.deleted='0' AND product_templates_cstm.related_to_c LIKE'%CTax%' AND product_templates_cstm.service_status_c='Active' GROUP BY type_id order by product_types.list_order ASC  ";
		//$sqlquery = "select id, name from product_types where deleted=0 ORDER BY list_order";
		
	//$sqlquery = "select id, name, related_to_c, service_status_c from product_templates INNER JOIN product_templates_cstm ON product_templates_cstm.id_c=product_templates.id where deleted=0 ORDER BY name";
		
		$res = $this->db_sugar->query($sqlquery);			
		$record = $res->result();
		
		//while($row = $this->db_sugar->fetchByAssoc($res)) 
		for($i=0; $i<count($record); $i++)
		{
			//print $record[0]->id;
			$service_type[$record[$i]->id] = $record[$i]->name;	
		}
		if(is_array($service_type))
		{
			$response_array['service_type'] = $service_type;
		}
		return $response_array;
//print_r($service_type);
		//print_r($response_array);
		/*if ($err) {
		echo "cURL Error #:" . $err;
		} else {
		echo $response;
		}*/
	}
	
	public function stateName($statecode)
    {	
        $state = array(
						'AL'=>'Alabama',
						'AR'=>'Arkansas',
						'AK'=>'Alaska',
						'AZ'=>'Arizona',
						'CA'=>'California',
						'CO'=>'Colorado',
						'CT'=>'Connecticut',
						'DC'=>'DC',
						'DE'=>'Delaware',
						'FL'=>'FLORIDA',
						'GA'=>'Georgia',
						'HI'=>'Hawaii',
						'ID'=>'Idaho',
						'IL'=>'Illinois',
						'IN'=>'Indiana',
						'IA'=>'Iowa',									
						'KS'=>'Kansas',
						'KY'=>'Kentucky',
						'LA'=>'Louisana',
						'MA'=>'Massachusetss',
						'MD'=>'Maryland',
						'ME'=>'Maine',
						'MI'=>'Michigan',
						'MO'=>'Missouri',
						'MS'=>'Mississippi',
						'MT'=>'Montana',
						'MN'=>'Minnesota',
						'NE'=>'Nebraska',
						'NC'=>'North Carolina',
						'ND'=>'North Dakota',
						'NV'=>'Nevada',														
						'NJ'=>'NEW JERSEY',
						'NH'=>'New Hampshire',
						'NM'=>'New Mexico',
						'NY'=>'New York',
						'OH'=>'Ohio',
						'OK'=>'Oklahoma',
						'OR'=>'Oregon',
						'PA'=>'Pennsylvania',
						'RI'=>'Rhode Island',
						'SD'=>'South Dakota',
						'SC'=>'South Carolina',
						'TN'=>'Tennessee',
						'TX'=>'Texas',
						'UT'=>'Utah',
						'VA'=>'Virginia',
						'VT'=>'Vermont',									
						'WA'=>'Washington',
						'WI'=>'Wisconsin',
						'WV'=>'West Virginia',
						'WY'=>'Wyoming'
					);
		
		if (array_key_exists($statecode, $state))
		{
			$name = $state[$statecode];
		}
		return $name;
		
    }
	
	public function sugar_irs_standards($caseid)
	{		
		$total = 0;
		$irs_stnd_array = array();
		
		$irs_stnd_array['ownership'] = 0;
		$irs_stnd_array['auto_operating'] = 0;
		$irs_stnd_array['fcm'] = '';
		$irs_stnd_array['public_transportation'] = '';
		$irs_stnd_array['under_65'] = '';
		$irs_stnd_array['under_65'] = '';
		
		if($caseid != '')		
		{ 
			$this->db_sugar->select("adults_over_65 as FamilyMembersOver65, adults_under_65 as FamilyMembersUnder65");
			$this->db_sugar->from('fin_overview');
			$this->db_sugar->join('fin_overview_cstm', 'fin_overview_cstm.id_c = fin_overview.id', 'left');
			$this->db_sugar->join('fin_overview_leads_1_c', 'fin_overview_leads_1_c.fin_overview_leads_1fin_overview_ida = fin_overview.id', 'left');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = fin_overview_leads_1_c.fin_overview_leads_1leads_idb', 'left');
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);						
			$this->db_sugar->where('fin_overview.deleted', 0);						
			$this->db_sugar->where('fin_overview_leads_1_c.deleted', 0);						
			//$this->db_sugar->limit(0, 1);
			
			$data = $this->db_sugar->get();
			$record = $data->result();
							
			if(is_array($record) && count($record) > 0)
			{
				if($record[0]->FamilyMembersUnder65 != '')
				{
					$under65 = preg_replace("/[^a-zA-Z0-9]+/", "", $record[0]->FamilyMembersUnder65);
				}
				else
				{
					$under65 = $record[0]->FamilyMembersUnder65;
				}
				if($record[0]->FamilyMembersOver65 != '')
				{
					$over65 = preg_replace("/[^a-zA-Z0-9]+/", "", $record[0]->FamilyMembersOver65); 
				}
				else
				{
					$over65 = $record[0]->FamilyMembersOver65;
				}
				 
				$total = $under65 + $over65;
				//print '65 - ' . $under65 . ' -- ' . $over65;
			}
			//print 'total-'.$total;
			$general_data = $this->general_caseid_details($caseid);
			$county = '';
			$fin_overview = array();
			
			if(is_array($general_data) && count($general_data) > 0){
					
				$statecode = $general_data[0]->State;
				
				$fin_overview = $this->fin_overview_details($caseid);
				//print_r($fin_overview);
				if($fin_overview != 0)
					$county = $fin_overview[0]->county_c;
				//print 'statecode-'.$statecode;
				//print 'county-'.$county;
				if($statecode != '' && $county != '' && $total != ''){
					
					if($total <=5)
						$housing = 'housing_'.$total;
					else
						$housing = 'housing_5';
					
					$this->db_sugar->select("region_c, msa_c, round($housing,2) as $housing");
					$this->db_sugar->from('hou_irsstandardshousing_cstm');
					$this->db_sugar->join('hou_irsstandardshousing', 'hou_irsstandardshousing.id = hou_irsstandardshousing_cstm.id_c', 'left');
					$this->db_sugar->where('hou_irsstandardshousing.name', $county);
					$this->db_sugar->where('hou_irsstandardshousing.state', $statecode);						
					//print $this->db_sugar;
					$data0 = $this->db_sugar->get();
					$record0 = $data0->result();
					//print_r($record0);
					if(is_array($record0) && count($record0) > 0){
						
						$irs_stnd_array['region'] = $record0[0]->region_c;
						$irs_stnd_array['housing'] = $record0[0]->$housing;
						$irs_stnd_array['msa'] = $record0[0]->msa_c;
					}
				}
			}		
			//print_r($irs_stnd_array);
			$vehicle = $this->vehicle_expense($caseid);
			$num_vehicle = 0;
			$total_text = '';
			//print_r($vehicle);
			
			if(is_array($vehicle) && count($vehicle) > 0){
				
				$num_vehicle = count($vehicle);
				if($num_vehicle == 1)
					$total_text = 'one_c';
				if($num_vehicle == 2)
					$total_text = 'two_c';
				if($num_vehicle > 2)
					$total_text = 'two_c';
			}
			//print $total;
			if($total > 0){
				
				$total_text_food = '';
				$total_text_housekeeping = '';
				$total_text_apparel = '';
				$total_text_personal = '';
				$total_text_miscellaneous = '';
				
				if($total == 1){
					$total_text_food = 'food_one_person_c';	
					$total_text_housekeeping = 'housekeeping_one_person_c';	
					$total_text_apparel = 'apparel_one_person_c';	
					$total_text_personal = 'personal_one_person_c';	
					$total_text_miscellaneous = 'miscellaneous_one_person_c';	
				}
				else if($total == 2){
					$total_text_food = 'food_two_persons_c';
					$total_text_housekeeping = 'housekeeping_two_persons_c';
					$total_text_apparel = 'apparel_two_persons_c';
					$total_text_personal = 'personal_two_persons_c';
					$total_text_miscellaneous = 'miscellaneous_two_persons_c';	
				}
				else if($total == 3){
					$total_text_food = 'food_three_persons_c';
					$total_text_housekeeping = 'housekeeping_three_persons_c';
					$total_text_apparel = 'apparel_three_persons_c';
					$total_text_personal = 'personal_three_persons_c';
					$total_text_miscellaneous = 'miscellaneous_three_persons_c';	
				}
				else if($total == 4){
					$total_text_food = 'food_four_persons_c';
					$total_text_housekeeping = 'housekeeping_four_persons_c';
					$total_text_apparel = 'apparel_four_persons_c';
					$total_text_personal = 'personal_four_persons_c';
					$total_text_miscellaneous = 'miscellaneous_four_persons_c';	
				}
				else
				{					
					$total_text_food = 'food_more_than_four_c';	
					$total_text_housekeeping = 'housekeeping_four_persons_c';
					$total_text_apparel = 'apparel_four_persons_c';
					$total_text_personal = 'personal_four_persons_c';
					$total_text_miscellaneous = 'miscellaneous_four_persons_c';	
				}
				//print ', '.$total_text_food;
				$operating_text = '';
				
				if($total_text != ''){
					
					$ownership = 'ownership_'.$total_text;
					
					if(array_key_exists('msa', $irs_stnd_array) && $irs_stnd_array['msa'] != ''){
						
						$operating_text = $irs_stnd_array['msa'].'_'.$total_text;
					}
					else{
						
						if(array_key_exists('region', $irs_stnd_array) && $irs_stnd_array['region'] != '')
						{
							$operating_text = $irs_stnd_array['region'].'_'.$total_text;
						}
					}
				}
				if($total_text_food != ''){
					
					$current_year = date('Y');
					if($total_text != '' && $operating_text != ''){
						
						$this->db_sugar->select("round(food_four_persons_c,2) as food_four, round($total_text_food,2) as $total_text_food, round($total_text_housekeeping,2) as $total_text_housekeeping, round($total_text_apparel,2) as $total_text_apparel,   round($total_text_personal,2) as $total_text_personal, round($total_text_miscellaneous,2) as $total_text_miscellaneous, round(public_transportation_c,2) as public_transportation_c, round($operating_text,2) as $operating_text, round(ownership_one_c,2) as ownership, round(under_65_c,2) as  under_65, round(over_65_c,2) as  over_65");
					}
					else{
						
						$this->db_sugar->select("round(food_four_persons_c,2) as food_four, round($total_text_food,2) as $total_text_food, round($total_text_housekeeping,2) as $total_text_housekeeping, round($total_text_apparel,2) as $total_text_apparel,   round($total_text_personal,2) as $total_text_personal, round($total_text_miscellaneous,2) as $total_text_miscellaneous, round(public_transportation_c,2) as public_transportation_c, round(under_65_c,2) as  under_65, round(over_65_c,2) as  over_65");
					}
					$this->db_sugar->from('irs_irsstandards_cstm');
					$this->db_sugar->join('irs_irsstandards', 'irs_irsstandards.id = irs_irsstandards_cstm.id_c', 'left');
					$this->db_sugar->where('irs_irsstandards.name', $current_year);						
					
					$data1 = $this->db_sugar->get();
					$record1 = $data1->result();
					//print_r($record1);
					if(is_array($record1) && count($record1) > 0){
						
						if($total > 4)
						{
							$food_extra = $total - 4;
							$food_extra_tot = $food_extra * $record1[0]->$total_text_food ;
							$irs_stnd_array['fcm'] = $food_extra_tot + $record1[0]->food_four 
												+ $record1[0]->$total_text_housekeeping 
												+ $record1[0]->$total_text_apparel 
												+ $record1[0]->$total_text_personal
												+ $record1[0]->$total_text_miscellaneous;
						}	 
						else 
						{
							$irs_stnd_array['fcm'] = $record1[0]->$total_text_food 
												+ $record1[0]->$total_text_housekeeping 
												+ $record1[0]->$total_text_apparel 
												+ $record1[0]->$total_text_personal
												+ $record1[0]->$total_text_miscellaneous;
						}
						/*if($num_vehicle > 1){
							$irs_stnd_array['public_transportation'] = $record1[0]->public_transportation_c * 2;
						}
						else{*/
							$irs_stnd_array['public_transportation'] = $record1[0]->public_transportation_c;
						//}
						//print $under65 . ', ' . $over65;
						//print_r($record1);
						if($under65 > 0){
							
							$irs_stnd_array['under_65'] = ($record1[0]->under_65) * $under65;
						}
						else{
							
							$irs_stnd_array['under_65'] = 0; //$record1[0]->under_65;
						}
						if($over65 > 0){
							
							$irs_stnd_array['over_65'] = ($record1[0]->over_65) * $over65;
						}
						else{
							
							$irs_stnd_array['over_65'] = 0; //$record1[0]->over_65;
						}
						if($total_text != '' && $operating_text != ''){
							
							//if($num_vehicle > 0){								
								//$irs_stnd_array['ownership'] = ($record1[0]->ownership) * $num_vehicle;
							//}
							//else{
								
								$irs_stnd_array['ownership'] = $record1[0]->ownership;
							//}
							$irs_stnd_array['auto_operating'] = $record1[0]->$operating_text;
						}
						else{
							
							$irs_stnd_array['ownership'] = 0;
							$irs_stnd_array['auto_operating'] = 0;
						}
					}
				}
			}
		}
		if(count($irs_stnd_array) > 0){
			//print_r($irs_stnd_array);
			return $irs_stnd_array;
		}
		else
			return 0;
	}
	
	public function gen_uuid() 
	{
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	//added on 23-06-2022 https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
	public function getMyAppointments()
	{
		$uid = $this->session->userdata('User_id'); 
		session_write_close();
		
		if($uid != '')
		{
			$array_data = array();
			$array_data['meetings.parent_type'] = 'Cases';
			$array_data['meetings.assigned_user_id'] = $uid;
			$array_data['meetings.deleted'] = '0';
			$array_data['meetings.status'] = 'Scheduled';
			//$array_data['users.deleted'] = '0';

			// $array_data['meetings.deleted'] = '0';
			//DATE_FORMAT(meetings.date_start, '%m-%d-%Y %h:%i %p')
			$this->db_sugar->select("cases_cstm.case_id_c, meetings.id,meetings.name,DATE_FORMAT(meetings.date_start, '%Y-%m-%d %H:%i:%s') as date_start,meetings.status, meetings_cstm.timezone_c"); 
			//CONCAT_WS(' ', users.first_name, users.last_name) AS assign_name,
			$this->db_sugar->from('meetings');	
			$this->db_sugar->join('meetings_cstm','meetings.id = meetings_cstm.id_c');	
			$this->db_sugar->join('cases_cstm','cases_cstm.id_c = meetings.parent_id');				
			$this->db_sugar->where($array_data);
			$this->db_sugar->order_by('meetings.date_entered','desc');	
			//$dataAppointment = $this->db_sugar->get(); 
			
			//return $dataAppointment->result();
			$data = $this->db_sugar->get();
			// print  $this->db_sugar->last_query(); 
			$record = $data->result();
			
			//print_r($record);
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
		{
			return 0;			
		}
	}

	//allAppointmentList
	public function allAppointmentList($caseId)
	{
		$uid = $this->session->userdata('User_id'); 
		session_write_close();
		if($caseId != '' && $uid != '')
		{
			$this->db_sugar->select("id_c"); 
			$this->db_sugar->from('cases_cstm');
			$this->db_sugar->join('cases','cases.id=cases_cstm.id_c', 'INNER');					
			$this->db_sugar->where('case_id_c', $caseId);	
			$this->db_sugar->where('deleted', 0);	
			$recordCase = $this->db_sugar->get(); 
			$record_sugar = $recordCase->result();
			//print  $this->db_sugar->last_query();
			if(is_array($record_sugar) && count($record_sugar) > 0)
			{	
				$caseModuleId = $record_sugar[0]->id_c;
			
				$array_data['meetings.parent_id'] = $caseModuleId;
				$array_data['meetings.parent_type'] = 'Cases';
				$array_data['meetings.assigned_user_id'] = $uid;
				$array_data['meetings.deleted'] = '0';
				//$array_data['users.deleted'] = '0';

				// $array_data['meetings.deleted'] = '0';
	//DATE_FORMAT(meetings.date_start, '%m-%d-%Y %h:%i %p')
				$this->db_sugar->select("meetings.id,meetings.name,DATE_FORMAT(meetings.date_start, '%Y-%m-%d %H:%i:%s') as date_start,meetings.status, meetings_cstm.timezone_c"); 
				//,CONCAT_WS(' ', users.first_name, users.last_name) AS assign_name,
				$this->db_sugar->from('meetings');	
				$this->db_sugar->join('meetings_cstm','meetings.id=meetings_cstm.id_c');	
				//$this->db_sugar->join('users','meetings.assigned_user_id=users.id');				
				$this->db_sugar->where($array_data);
				$this->db_sugar->order_by('meetings.date_entered','desc');	
				$data = $this->db_sugar->get(); 
				//print  $this->db_sugar->last_query();
				return $record_sugar = $data->result();
			
			}
			else{
				return false;
			}
		}
	}

	//get all user list create by akash on 30-03-2022
	public function getAllUsersList()
	{
		$this->db_sugar->select("users.id, concat(users.first_name, ' ', users.last_name) as uname "); 			
		$this->db_sugar->from('users');	
		
		$this->db_sugar->join('acl_roles_users', 'acl_roles_users.user_id = users.id', 'left');
		$this->db_sugar->join('acl_roles', 'acl_roles.id = acl_roles_users.role_id', 'left');

		//$this->db_sugar->where('acl_roles.name', 'Sr. Case Analyst');	
		$this->db_sugar->where('users.deleted', 0);	
		$this->db_sugar->where('users.status', 'Active');		
		$this->db_sugar->where('acl_roles.deleted', 0);		
		$this->db_sugar->where('acl_roles_users.deleted', 0);		
		$this->db_sugar->order_by('uname');
		
		$data = $this->db_sugar->get();
		$record = $data->result();
		
		//print_r($record);
		if(is_array($record) && count($record) > 0)
		{
			return $record;
		}
		else 
			return 0; 			
	}

	public function saveAppointment($dataSend)
	{
		file_put_contents("logAkash.log", 'dataSend: '.print_r($dataSend, true) . "\n", FILE_APPEND);
		$date = date("Y-m-d h:i:s");
		
		if(!empty($dataSend['parent_id']))
		{
			$url = "/Meetings";
			// Call global function for store and update data by paramenters url and record
			$createdMeetings = $this->storeDataInSugar('meetings', $url, $dataSend);
		}
		if(!empty($createdMeetings->id)) 
		{
			file_put_contents("logAkash.log", 'createdMeetings->id: '.print_r($createdMeetings->id, true) . "\n", FILE_APPEND);

			$updateName = "UPDATE meetings SET name='".$dataSend['name']."' where id='".$createdMeetings->id."' ";
			$updateMeetRecord = $this->db_sugar->query($updateName);

			$RelMeetingCase = "INSERT INTO `meetings_cases`(`id`, `meeting_id`, `case_id`, `date_modified`) VALUES (uuid(),'".$createdMeetings->id."', '".$dataSend['parent_id']."', '".$date."')";
			$insertRecordRel = $this->db_sugar->query($RelMeetingCase);

			$getMeetingIdSql = "SELECT id from meetings where repeat_parent_id='".$createdMeetings->id."'";
			$getAllMeetingId = $this->db_sugar->query($getMeetingIdSql);
			$recordMeeting = $getAllMeetingId->result();

			if(count($recordMeeting) > 0)
			{
				
				file_put_contents("logAkash.log", 'recordMeeting: '.print_r($recordMeeting, true) . "\n", FILE_APPEND);

				foreach($recordMeeting as $meetingId)
				{
					$updateNameRemain = "UPDATE meetings SET name='".$dataSend['name']."' where id='".$meetingId->id."' ";
					$updateNameRemainRecord = $this->db_sugar->query($updateNameRemain); 

					$RelMeetingCaseRepeat = "INSERT INTO `meetings_cases`(`id`, `meeting_id`, `case_id`, `date_modified`) VALUES (uuid(),'".$meetingId->id."', '".$dataSend['parent_id']."', '".$date."')";
					$insertRecordRelRepeat = $this->db_sugar->query($RelMeetingCaseRepeat);
				}
			}
			
		}
		if(property_exists($createdMeetings, 'error'))
		{
			file_put_contents("logAkash.log", 'error_message: '.print_r($createdMeetings->error_message, true) . "\n", FILE_APPEND);
			return $createdMeetings->error_message;
		}
		else
		{
			file_put_contents("logAkash.log", 'error_message else : '.print_r(true, true) . "\n", FILE_APPEND);
			return true;
		}

	}

	//end added on 23-06-2022

	//added on 07-07-2022 for editing appointments https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
	public function getAppointmentDetail($id)
	{		
		if($id != '')
		{
			$array_data = array();
			$array_data['meetings.parent_type'] = 'Cases';
			$array_data['meetings.id'] = $id;
			$array_data['meetings.deleted'] = '0';
			
			$this->db_sugar->select("meetings.id,meetings.name,meetings.status,meetings.description,meetings.date_start,meetings.date_end,meetings.reminder_time,meetings.email_reminder_time,meetings.repeat_type,meetings.repeat_interval,meetings.repeat_dow,meetings.repeat_until,meetings.repeat_count,meetings.assigned_user_id,meetings_cstm.timezone_c"); 

			$this->db_sugar->from('meetings');	
			$this->db_sugar->join('meetings_cstm','meetings.id = meetings_cstm.id_c');	
			$this->db_sugar->join('cases_cstm','cases_cstm.id_c = meetings.parent_id');				
			$this->db_sugar->where($array_data);
			$this->db_sugar->order_by('meetings.date_entered','desc');	
			
			$data = $this->db_sugar->get();
			// print  $this->db_sugar->last_query(); 
			$record = $data->result();
			$weekvalue = '';
			$dow = str_split($record[0]->repeat_dow);
			$intdayweek = implode(',',$dow);
			foreach($dow as $keey => $weekint)
			{
				$countEach = $keey+1;
				if($weekint==0)
				{	$weekvalue .= 'Sun'; }
				elseif($weekint==1)
				{	$weekvalue .= 'Mon'; }
				elseif($weekint==2)
				{	$weekvalue .= 'Tue'; }
				elseif($weekint==3)
				{	$weekvalue .= 'Wed'; }
				elseif($weekint==4)
				{	$weekvalue .= 'Thu'; }
				elseif($weekint==5)
				{	$weekvalue .= 'Fri'; }
				elseif($weekint==6)
				{	$weekvalue .= 'Sat'; }

				if($countEach!=count($dow))
				{
					$weekvalue .= ',';
				}
			}

			$record[0]->weekString = $weekvalue;
			
			$timeZone = $record[0]->timezone_c; 
			$startDateTime = $record[0]->date_start;
			$endDateTime = $record[0]->date_end;
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

			if($explode[0]>=0)
			{
				$hours_min = $explode[0].' hours '.$explode[1].' minutes';
				$dataSend['hours_min'] = $explode[0].' hours '.$explode[1].' minutes';
			}
			else{
				$hours_min = $explode[0].' hours -'.$explode[1].' minutes';
				$dataSend['hours_min'] = '-'.$explode[0].' hours -'.$explode[1].' minutes';
			}
		
			if($startDateTime!='' && $startDateTime!='')
			{
				$dateTimeFinal['start'] = date('Y-m-d h:i a',strtotime($hours_min,strtotime($startDateTime)));
				$dateTimeFinal['end'] = date('Y-m-d h:i a',strtotime($hours_min,strtotime($endDateTime)));

				// print_r(date("m-d-Y",strtotime($dateTimeFinal['Start'])));
				// print_r(date("h:i a",strtotime($dateTimeFinal['Start'])));

				$record[0]->dateStart = date("m-d-Y",strtotime($dateTimeFinal['start']));
				$record[0]->timeStart = date("h:i a",strtotime($dateTimeFinal['start']));
				$record[0]->dateEnd = date("m-d-Y",strtotime($dateTimeFinal['end']));
				$record[0]->timeEnd = date("h:i a",strtotime($dateTimeFinal['end']));
			}
			else{
				$record[0]->dateStart = date("m-d-Y");
				$record[0]->timeStart = '';
				$record[0]->dateEnd = date("m-d-Y");
				$record[0]->timeEnd = '';
			}
			
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			else 
				return 0; 
		}
		else 
		{
			return 0;			
		}
	}

	public function editAppointment($dataSend)
	{
		$date = date("Y-m-d h:i:s");
	
		if(!empty($dataSend['parent_id']) && !empty($dataSend['id']))
		{
			$editId = $dataSend['id'];
			$url = "/Meetings/".$editId;
			
			$dataSendEdit['date_start'] = $dataSend['date_start'];
			$dataSendEdit['duration_hours'] = $dataSend['duration_hours'];
			$dataSendEdit['duration_minutes'] = $dataSend['duration_minutes'];
			$dataSendEdit['date_end'] = $dataSend['date_end'];
			$dataSendEdit['reminder_time'] = $dataSend['reminder_time'];
			$dataSendEdit['email_reminder_time'] = $dataSend['email_reminder_time'];
			$dataSendEdit['description'] = $dataSend['description'];
			$dataSendEdit['assigned_user_id'] = $dataSend['assigned_user_id'];
			$dataSendEdit['timezone_c'] = $dataSend['timezone_c'];
			$dataSendEdit['status'] = $dataSend['status']; // added on 21-07-2022

			// Call global function for store and update data by paramenters url and record
			$createdMeetings = $this->storeDataInSugar('meetings', $url, $dataSendEdit, $editId);

			if(!empty($createdMeetings->id))
			{
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	//end added on 07-07-2022

	// Added on 07-10-2022 https://trello.com/c/MoUnzlJb/1576-cr-portal-overhaul
	public function update_pps_info($ppsid, $instruction)
	{
		$logData = '1. PPS Update Start PPSId - ' . $ppsid . ", instruction - " . $instruction . PHP_EOL;
		$this->createCustomLog($logData,'PPS','update_pps_info');

		if($instruction != '')
		{
			$details = json_decode($instruction,true);

			$data = "";
			if($details['civilPenalties'] != "null" && $details['civilPenalties'] != ''){
				$data .= "civil_penalties_c = '".$details['civilPenalties']."',";
			}
			if($details['crossReference'] != "null" && $details['crossReference'] != ''){
				$data .= "mfj_cross_reference_c = '".$details['crossReference']."',";
			}
			if($details['sharedResponsibility'] != "null" && $details['sharedResponsibility'] != ''){
				$data .= "shared_responsibility_c = '".$details['sharedResponsibility']."',";
			}
			if($details['passportCertification'] != "null" && $details['passportCertification'] != ''){
				$data .= "passport_certification_c = '".$details['passportCertification']."',";
			}
			if($details['poaIssue'] != "null" && $details['poaIssue'] != ''){
				$data .= "issue_not_covered_in_poa_c = '".$details['poaIssue']."',";
			}
			if($details['passportCertificationYear'] != "null" && $details['passportCertificationYear'] != ''){
				$data .= "passport_certification_years_c = '".$details['passportCertificationYear']."',";
			}
			if($details['ftaEligibleYear'] != "null" && $details['ftaEligibleYear'] != ''){
				$data .= "fta_eligible_c = '".$details['ftaEligibleYear']."',";
			}
			if($details['ppsCompleted'] != "null" && $details['ppsCompleted'] != ''){
				$data .= "pps_completed_using_c = '".$details['ppsCompleted']."',";
			} //added on 18-04-2024 https://trello.com/c/YsTzk5k6/1215-cr-portal-add-required-tax-status-field-in-pps-tab
			$data = rtrim($data,',');

			if($ppsid != '' && $data != '')
			{	
				$sql = "UPDATE inves_pps_cstm set $data"; 
				$sql .= " WHERE id_c='$ppsid' ";
				
				$query_sugar = $this->db_sugar->query($sql);
				//print_r($query_sugar);
				if($query_sugar)
				{
					$logData = '2. PPS Update Success PPSId - ' . $ppsid . ", data - " . $data . PHP_EOL;
					$this->createCustomLog($logData,'PPS','update_pps_info');
					return 1 ; 
				}
				else
				{					
					$err =  $this->db_sugar->error();
					$logData = '2. PPS Update Error PPSId - ' . $ppsid . ", err - " . $err . ", sql - " . $sql . PHP_EOL;
					$this->createCustomLog($logData,'PPS','update_pps_info');
					//print_r($err);
				}
			}
			else 
			{
				$logData = '2. PPS Update Missing Data PPSId - ' . $ppsid . ", instruction - " . $instruction . ", data - " . $data . PHP_EOL;
				$this->createCustomLog($logData,'PPS','update_pps_info');
				return 0;
			}
		}
		else 
		{
			$logData = '2. PPS Update Missing Data PPSId - ' . $ppsid . ", instruction - " . $instruction . PHP_EOL;
			$this->createCustomLog($logData,'PPS','update_pps_info');
			return 0;
		}
	}

	public function createCustomLog($data,$apiName,$logFor)
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
		$directoryName = $dirName . "/customlogs/DBModel/".$finalYear."/".$finalMonth. "/". $finalDate . "/".$apiName;
		//file_put_contents('createCustomLog.txt', $fileName . ', ' . $directoryName);
		if (!is_dir($directoryName))
		{
			mkdir($directoryName, 0755, true);
		}
		file_put_contents($directoryName."/".$fileName, $finalContent, FILE_APPEND | LOCK_EX);
	}

	// Added on 31-08-2023 https://trello.com/c/PodR8zl0/435-investigation-summary-letter-fixes
	public function get_pps_total_balance($case_id)
	{
		if(!empty($case_id))
		{
			$total_pps_balance = 0;
			$sql = " SELECT
						inves_pps.pps_type,
						inves_pps.id,
						inves_pps.name,
						pps_status,
						poa_issues,
						DATE(inves_pps.date_modified) AS date_modified,
						CONCAT(um.first_name, ' ', um.last_name) AS modified_by,
						CONCAT(ua.first_name, ' ', ua.last_name) AS assigned_to,
						inves_pps.assigned_user_id,
						biz_poa_issues_new_c AS biz_poa_issues,
						pps_instructions_c,
						verbal_poa_c,
						state_c,
						pps_spouse_c
					FROM
						inves_pps
					LEFT JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps.id
					LEFT JOIN cases_inves_pps_1_c ON cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id AND cases_inves_pps_1_c.deleted = 0
					LEFT JOIN cases_cstm ON cases_cstm.id_c = cases_inves_pps_1_c.cases_inves_pps_1cases_ida
					LEFT JOIN cases ON cases.id = cases_cstm.id_c AND cases.deleted = 0
					LEFT JOIN users AS um
					ON
						um.id = inves_pps.modified_user_id
					LEFT JOIN users AS ua
					ON
						ua.id = inves_pps.created_by
					WHERE
						cases_cstm.case_id_c = '".$case_id."' AND inves_pps.deleted = 0  AND (inves_pps.pps_type = 'Federal Personal' OR inves_pps.pps_type = 'Federal Business')
					ORDER BY
						inves_pps.date_entered
					DESC";

			$result = $this->db_sugar->query($sql);
			$record = $result->result();	
			
			if(is_array($record) && count($record) > 0)
			{
				for($i=0; $i<count($record); $i++){
					if($i>3){
						break;
					}else{

						$sql = "SELECT
									inves_pps.id AS ppsid,
									ppsd_ppsdetail.id,
									tax_period,
									ROUND(balance, 2) AS balance,
									filing_status,
									account_status,
									DATE_FORMAT(csed_date_c, '%m/%d/%Y') AS csed_date_c,
									ro_recommended,
									fta_eligible,
									liens,
									ROUND(reported_wage, 2) AS reported_wage,
									ROUND(reported_se, 2) AS reported_se,
									ROUND(reported_other, 2) AS reported_other,
									ROUND(gross_monthly_income, 2) AS gross_monthly_income,
									ROUND(taxes_withheld, 2) AS taxes_withheld,
									tin_c
								FROM
									ppsd_ppsdetail
								LEFT JOIN ppsd_ppsdetail_cstm ON ppsd_ppsdetail_cstm.id_c = ppsd_ppsdetail.id
								LEFT JOIN inves_pps_ppsd_ppsdetail_1_c ON inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1ppsd_ppsdetail_idb = ppsd_ppsdetail.id AND inves_pps_ppsd_ppsdetail_1_c.deleted = 0
								LEFT JOIN inves_pps ON inves_pps.id = inves_pps_ppsd_ppsdetail_1_c.inves_pps_ppsd_ppsdetail_1inves_pps_ida AND inves_pps.deleted = 0
								LEFT JOIN inves_pps_cstm ON inves_pps_cstm.id_c = inves_pps.id
								LEFT JOIN cases_inves_pps_1_c ON cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id AND cases_inves_pps_1_c.deleted = 0
								WHERE
									inves_pps.id = '".$record[$i]->id."' AND ppsd_ppsdetail.deleted = 0
								ORDER BY
									tax_period
								DESC";

						$result = $this->db_sugar->query($sql);
						$result = $result->result();
						if(is_array($result) && count($result) > 0)
						{
							for($k=0; $k<count($result); $k++){
								$total_pps_balance += $result[$k]->balance;
							}
						}
					}
					
				}
			}
		}
		return $total_pps_balance;
	} // end added on 31-08-2023

	//Added on 28-09-2023 https://trello.com/c/apOv1pYa/532-direct-partner-tags-throughout-lead-investigations-and-client-record
	public function fetch_lead_campaign_direct_partner($caseid)
	{
		if($caseid != '')
		{	
			/*$utmCampaign = "";
			$sql  = "SELECT name FROM chp_channelpartnergroup cpg WHERE cpg.deleted = 0";
			$record = $this->db_sugar->query($sql);
			$result = $record->result();		
			
			for($i=0; $i<count($result); $i++)
			{
				if ($result[$i]->name == 'Other' || $result[$i]->name == 'other') {
					$utmCampaign .= "'partner-referral',";
				} else {
					$utmCampaign .= "'" . strtolower($result[$i]->name) . "-referral',";
				}
			}
			$utmCampaign = rtrim($utmCampaign, ',');*/ // Commented on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners
			
			$selectSql = "SELECT
            leadm_leadmarketing.id,
            leadm_leadmarketing.lead_campaign,
            leadm_leadmarketing.lead_src
            FROM
                leadm_leadmarketing
            INNER JOIN leadm_leadmarketing_cstm ON leadm_leadmarketing_cstm.id_c = leadm_leadmarketing.id AND leadm_leadmarketing.deleted=0
            INNER JOIN leads_leadm_leadmarketing_1_c ON leadm_leadmarketing.id = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND leads_leadm_leadmarketing_1_c.deleted = 0
			INNER JOIN leads_cstm on leads_cstm.id_c = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida AND leads_cstm.case_id_c = $caseid
			INNER JOIN leads on leads.id = leads_cstm.id_c AND leads.deleted = 0
            WHERE
			leadm_leadmarketing.lead_campaign LIKE '%partner-referral%' AND leadm_leadmarketing.medium LIKE '%partnership%' AND leadm_leadmarketing_cstm.attributable_c = '1'"; // Updated on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners

			$record = $this->db_sugar->query($selectSql);
			$result = $record->result();
			if(count($result)>0){
				return 'Direct Partner';
			}else{
				return '';
			}
        }
        return '';
	}
	//End on 28-09-2023

	//Added on 08-12-2023 https://trello.com/c/6f8jcha1/742-display-new-opportunity-fields-discovery-part-1-2-tabs-in-cr-portal
	public function get_opportunity_data($caseid)
	{
		if ($caseid != '') {
			$getOpportunityRecord = "
				SELECT
				COALESCE(opportunities_cstm.screen_qualifer_1_c, '') AS screen_qualifer_1_c,
				COALESCE(opportunities_cstm.screen_qualifier_2_c, '') AS screen_qualifier_2_c,
				COALESCE(opportunities_cstm.op_stated_debt_amount_c, '') AS op_stated_debt_amount_c,
				COALESCE(opportunities_cstm.op_issue_reason_c, '') AS op_issue_reason_c,
				COALESCE(opportunities_cstm.op_year_problem_started_c, '') AS op_year_problem_started_c,
				COALESCE(opportunities_cstm.missing_personal_tax_return_c, '') AS missing_personal_tax_return_c,
				COALESCE(opportunities_cstm.pain_points_c, '') AS pain_points_c,
				COALESCE(opportunities_cstm.business_related_c, '') AS business_related_c,
				COALESCE(opportunities_cstm.business_open_c, '') AS business_open_c,
				COALESCE(opportunities_cstm.business_employees_c, '') AS business_employees_c,
				COALESCE(opportunities_cstm.business_type_c, '') AS business_type_c,
				COALESCE(opportunities_cstm.received_notice_c, '') AS received_notice_c,
				COALESCE(opportunities_cstm.irs_letter_c, '') AS irs_letter_c,
				COALESCE(opportunities_cstm.prior_agreement_c, '') AS prior_agreement_c,
				COALESCE(opportunities_cstm.current_ia_amount_c, '') AS current_ia_amount_c,
				COALESCE(opportunities_cstm.past_rez_c, '') AS past_rez_c,
				COALESCE(opportunities_cstm.current_agreement_date_c, '') AS current_agreement_date_c,
				COALESCE(opportunities_cstm.current_ia_affordibility_c, '') AS current_ia_affordibility_c,
				COALESCE(opportunities_cstm.joint_debt_c, '') AS joint_debt_c,
				COALESCE(opportunities_cstm.household_income_types_c, '') AS household_income_types_c,
				COALESCE(opportunities_cstm.household_income_c, '') AS household_income_c,
				COALESCE(opportunities_cstm.household_size_c, '') AS household_size_c,
				COALESCE(opportunities_cstm.goal_outcome_c, '') AS goal_outcome_c,
				COALESCE(opportunities_cstm.prac_questions_c, '') AS prac_questions_c,
				COALESCE(opportunities_cstm.prepared_unfiled_returns_c, '') AS prepared_unfiled_returns_c,
				COALESCE(opportunities_cstm.filed_missing_returns_c, '') AS filed_missing_returns_c,
				COALESCE(opportunities_cstm.return_copies_c, '') AS return_copies_c,
				COALESCE(opportunities_cstm.unfiled_return_balances_c, '') AS unfiled_return_balances_c,
				COALESCE(opportunities_cstm.missing_return_refund_amount_c, '') AS missing_return_refund_amount_c,
				COALESCE(opportunities_cstm.return_filed_date_c, '') AS return_filed_date_c,
				COALESCE(opportunities_cstm.missing_return_balance_c, '') AS missing_return_balance_c,
				COALESCE(opportunities_cstm.internal_notes_c, '') AS internal_notes_c,
				COALESCE(opportunities_cstm.single_spouse_q1_c, '') AS single_spouse_q1_c,
				COALESCE(opportunities_cstm.single_spouse_q2_c, '') AS single_spouse_q2_c,
				COALESCE(opportunities_cstm.single_spouse_q3_c, '') AS single_spouse_q3_c,
				COALESCE(opportunities_cstm.single_spouse_q4_c, '') AS single_spouse_q4_c,
				(
					SELECT COALESCE(ext4, '')
					FROM fields_meta_data
					WHERE name = 'compliance_indicator_c' AND custom_module = 'Opportunities'
				) AS compliance_indicator_c,
				(
                    SELECT COALESCE(ext4, '')
                    FROM fields_meta_data
                    WHERE name = 'biz_employee_verification_c' AND custom_module = 'Opportunities'
                ) AS biz_employee_verification_c

			FROM
				leads
			INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c
			INNER JOIN opportunities ON leads.opportunity_id = opportunities.id
			INNER JOIN opportunities_cstm ON opportunities.id = opportunities_cstm.id_c
			WHERE
				leads.deleted = 0
					AND leads_cstm.case_id_c = '".$caseid."'
			"; // Updated on 25-01-2024 https://trello.com/c/Mq8P8jPc/939-add-internal-notes-field-in-opportunity-to-cr-portal-visibility-under-opportunity-tab

			$LeadsRes = $this->db_sugar->query($getOpportunityRecord);
			$result = $LeadsRes->result();

			// Fields to handle to remove '^' character
			$multiSelectFields = [
				'missing_personal_tax_return_c',
				'op_issue_reason_c',
				'prior_agreement_c',
				'pain_points_c',
				'household_income_types_c',
				'goal_outcome_c',
				'irs_letter_c',
			];

			foreach ($result as $row) {
				if (isset($row->compliance_indicator_c)) {
					$row->compliance_indicator_c = strip_tags($row->compliance_indicator_c);
				}
				if (isset($row->biz_employee_verification_c)) {
					$row->biz_employee_verification_c = strip_tags($row->biz_employee_verification_c);
				}				
				
				// Handle other multi-select fields to remove '^' character
				foreach ($multiSelectFields as $field) {
					if (isset($row->$field) && $row->$field !='') {
						$row->$field = str_replace('^','',$row->$field);
					}
				}
			}
			return $result;
		}
	}
	
	public function get_tax_status_data($case_id, $spouse)
	{
		if($case_id != '')
		{				
			$selectSql = "SELECT
				tsc.tax_status_deep_link_c as deepLink
			FROM
				ts_taxstatus ts
			INNER JOIN ts_taxstatus_cstm tsc ON
				ts.id = tsc.id_c AND for_spouse_c = $spouse
			LEFT JOIN leads_ts_taxstatus_1_c lts ON
				ts.id = lts.leads_ts_taxstatus_1ts_taxstatus_idb AND lts.deleted = 0 AND ts.deleted = 0
			LEFT JOIN leads l ON
				l.id = lts.leads_ts_taxstatus_1leads_ida
			INNER JOIN leads_cstm lc ON
				lc.id_c = l.id AND lc.case_id_c = $case_id
			LEFT JOIN contacts_ts_taxstatus_1_c cts ON
				ts.id = cts.contacts_ts_taxstatus_1ts_taxstatus_idb AND cts.deleted = 0 AND ts.deleted = 0
			WHERE
				(
					lts.leads_ts_taxstatus_1leads_ida = l.id OR cts.contacts_ts_taxstatus_1contacts_ida = l.contact_id
				)";

				$record = $this->db_sugar->query($selectSql);
				$result = $record->result();
				if(count($result)>0){
					if(!empty($result[0]->deepLink)){
						$link = $result[0]->deepLink;
						$resp = array("result"=>"success","message"=>"Data Fetched Successfully.","data"=>$link);
					}
					else{
						$resp = array("result"=>"error","message"=>"Deep Link not found please send Consent Link First.","data"=>null);
					}
				}else{
					$resp = array("result"=>"error","message"=>"Tax Status Record not found.","data"=>null);
				}
			return $resp;
		}
	} // end added on 08-12-2023

	// Added 03-03-2025 for Gong Notes https://trello.com/c/dDJftjdb/2039-cr-portal-gong-notes-to-federal-intake
	public function fetch_gong_notes_details($caseid)
	{
		if ($caseid != '') 
		{
			$this->db_sugar->select('notes.*');
			$this->db_sugar->from('notes');
			$this->db_sugar->join('leads', 'leads.id = notes.parent_id AND leads.deleted = 0', 'inner');
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'inner');
			$this->db_sugar->where('notes.name LIKE', '%Gong%');
			$this->db_sugar->where('notes.deleted', 0);
			$this->db_sugar->where('leads_cstm.case_id_c', $caseid);

			$data = $this->db_sugar->get();
			return ($data->num_rows() > 0) ? $data->result() : 0;
		}
	} // end Added 03-03-2025 

	//added on 24-07-2025
	public function post_record_mycase($case_id)
	{
		file_put_contents("mycaseSync.txt", 'case_id - '.print_r($case_id,true)."\n",FILE_APPEND | LOCK_EX );
		//file_put_contents("mycaseSync.txt", 'case_id - '.print_r($case_id,true)."\n",FILE_APPEND | LOCK_EX );

		if($case_id != '')
		{				
			$selectSql = "SELECT c.id,l.converted,e.email_address,cc.case_id_c,c.first_name,c.last_name,c.phone_mobile,c.phone_work,c.phone_fax,c.birthdate,cc.itin_c,cc.spouseitin_c,cc.ssn_c,c.primary_address_street,c.primary_address_city,c.primary_address_state,c.primary_address_postalcode,cc.spouse_first_name_c,cc.spouse_ssn_c,cc.spouse_email_c,cc.marital_status_c,cc.spouse_birthdate_c,cc.spouse_phone_mobile_c,cc.language_c
			FROM contacts c
			INNER JOIN contacts_cstm cc ON cc.id_c = c.id AND cc.case_id_c = $case_id
			INNER JOIN leads l ON l.contact_id = cc.id_c
			LEFT JOIN email_addr_bean_rel er ON er.bean_id = cc.id_c AND er.primary_address = 1
			LEFT JOIN email_addresses e ON e.id = er.email_address_id 
			WHERE er.bean_module = 'Contacts' 
			AND c.deleted = 0 
			AND e.deleted = 0 
			AND er.deleted = 0 	
			AND (ctax_status_c LIKE 'Active%'  
			OR tap_status_c LIKE 'Active%' 
			OR ctaxplus_status_c LIKE 'Active%') ;";

			$record = $this->db_sugar->query($selectSql);
			$result = $record->result();
			if(count($result)>0)
			{
				if(isset($result[0]->id) && $result[0]->converted == 1)
				{
					$user_name = $this->session->userdata('uname');
					$user_pwd = $this->session->userdata('pwd');
					session_write_close();
					$instance_url = $this->sugar_url . "rest/v11_1"; 
					
					//Login - POST /oauth2/token
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
					$oauth_token = $oauth2_token_response_obj->access_token;
					
					//Create Records - GET /<module>/id
					if($oauth_token != '')
					{
						$contactId = $result[0]->id;
						$url = $instance_url . "/mycase_contact_sync?id=" . urlencode($contactId);

						//Set up the Record details
						
						$curl_request = curl_init();
						curl_setopt($curl_request, CURLOPT_URL, $url);
						curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "GET");
						curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false); // Set true in production
						curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json",
							"oauth-token: $oauth_token"
						));

						$curl_response = curl_exec($curl_request);

						$httpCode = curl_getinfo($curl_request, CURLINFO_HTTP_CODE);
						$error = curl_error($curl_request);

						$updatedRecord  = json_decode($curl_response);
						
						file_put_contents("mycaseSync.txt", 'updatedRecord - '.print_r($updatedRecord,true)."\n",FILE_APPEND | LOCK_EX );
						curl_close($curl_request);
									
						return $updatedRecord;
					}
				}
				else{
					return json_decode(["success"=>"false", "message" => "This lead has not been converted yet. Once it is, Sugar CRM and MyCase will automatically sync all current Individuals, Spouses, and Businesses"]);
				}

			}else{
				return json_encode(["success"=>"false", "message" => "Unable to sync. Case not found. Please contact admin."]);
			}
		}
	} // end added on 24-07-2025
}
