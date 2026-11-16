<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BackOfficeModel extends CI_Model 
{	
		/*
		Function for generate a random string 
		Parameters
		Author-
		*/
		function generateRandomString()
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$randomString = '';  
		
			for ($i = 0; $i < 5; $i++) { 
				$index = rand(0, strlen($characters) - 1); 
				$randomString .= $characters[$index]; 
			}
			return $randomString;
		}

		/*
		Function for get auth token from sugar 
		Parameters.
		Author- Tara (19-Nov-2020)
		*/
		function getAuthToken()
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

			/*if($oauth2_token_response_obj->error)
			{
				return $oauth2_token_response_obj->error_message;
			}
			else
			{
				$oauth_token = $oauth2_token_response_obj->access_token;
				return $oauth_token;
			}*/
			//return $oauth_token;
			return $oauth2_token_response_obj;
		}

		/*
		Function for get store and update data in sugar 
		Parameters. url, record, id
		Author- Tara (20-Nov-2020)
		*/
		public function storeDataInSugar($url, $record, $id=null)
		{
			$oauth2_token_response_obj = $this->getAuthToken();
			//print_r($oauth2_token_response_obj);
			if(property_exists($oauth2_token_response_obj, 'error'))
			{
				return $oauth2_token_response_obj;
			}
			else
			{
				$oauth_token = $oauth2_token_response_obj->access_token;
				$instance_url = $this->sugar_url."rest/v11";  
				$url = $instance_url.$url;
				$curl_request = curl_init($url);
				//print $url;
				// If event is update then pass put method
				if($id){
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

				try{
					curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
					$curl_response = curl_exec($curl_request);
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
		}

		public function deleteDataInSugar($url, $record, $id)
		{
			$oauth2_token_response_obj = $this->getAuthToken();
			//print_r($oauth2_token_response_obj);
			if(property_exists($oauth2_token_response_obj, 'error'))
			{
				return $oauth2_token_response_obj;
			}
			else
			{
				if($id != '')
				{
					$oauth_token = $oauth2_token_response_obj->access_token;
					$instance_url = $this->sugar_url."rest/v11";  
					$url = $instance_url.$url;
					$curl_request = curl_init($url);
					//print $url;
					// If event is update then pass put method
					if($id){
						curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "DELETE");
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

					try{
						curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
						$curl_response = curl_exec($curl_request);
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
			}
		}

		/*
		Get all resolution for backoffice detail view page 
		Parameters. CaseId
		Author- 
		*/
		public function getContactIdByCaseId($caseId)
		{
			//$uid = $this->session->userdata('User_id');
			//file_put_contents('createResoContact1.txt',$caseId);
			if($caseId != '')
			{
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('contacts_cstm');	
				$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');				
				$this->db_sugar->where('contacts_cstm.case_id_c', $caseId);	
				$this->db_sugar->where('contacts.deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				//print $this->db_sugar->last_query();
				$record_sugar = $data_sugar->result();

				if(count($record_sugar) > 0)
				{				
					$res_cnt_ida = $record_sugar[0]->id_c;
					//file_put_contents('createResoContact.txt',$res_cnt_ida);
					return 	$res_cnt_ida;		
				}else{
					return false;
				}
			}else{
				return false;
			}
				
		}

		public function general_contact_details($caseid)
		{
			if($caseid != '')		
			{
				$this->db_sugar->select("contacts.id as id, first_name as FirstName, last_name as LastName,  primary_address_state as State, ctax_status_c, tap_status_c, leadm_leadmarketing.lead_src "); 
				$this->db_sugar->from('contacts');
				$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts.id', 'left');
				$this->db_sugar->join('leads_cstm', 'leads_cstm.case_id_c = contacts_cstm.case_id_c', 'left');
				
				$this->db_sugar->join('leadm_leadmarketing_cstm', 'leadm_leadmarketing_cstm.leadid_c = leads_cstm.id_c', 'left');
				$this->db_sugar->join('leadm_leadmarketing', 'leadm_leadmarketing.id = leadm_leadmarketing_cstm.id_c', 'left');
				
				$this->db_sugar->where('contacts_cstm.case_id_c', $caseid);						
				$this->db_sugar->where('contacts.deleted', 0);						
				
				$data = $this->db_sugar->get(); 
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
		
		public function total_fee_details($caseid)
		{
			if($caseid != '')		
			{
				//SELECT
							/*round(SUM(likely_case),2) AS likely_case ,
							SUM(debit_amount_c) AS debit_amount_c
							FROM revenue_line_items
							INNER JOIN revenue_line_items_cstm ON revenue_line_items_cstm.id_c =revenue_line_items.id
                            INNER JOIN leads ON leads.opportunity_id = revenue_line_items.opportunity_id
                            INNER JOIN leads_cstm ON leads_cstm.id_c = leads.id
							WHERE leads_cstm.case_id_c = 1125379
                            AND leads.deleted=0
							AND revenue_line_items.deleted ='0'
							AND dateapproved_c IS NOT NULL 
							 GROUP by dateapproved_c
                            ORDER BY dateapproved_c DESC LIMIT 1 */

				$this->db_sugar->select("round(SUM(likely_case),2) AS likely_case ,	SUM(debit_amount_c) AS debit_amount_c"); 
				$this->db_sugar->from('	revenue_line_items	');
				$this->db_sugar->join('revenue_line_items_cstm', 'revenue_line_items_cstm.id_c = revenue_line_items.id', 'left');
				$this->db_sugar->join('leads', 'leads.opportunity_id = revenue_line_items.opportunity_id', 'left');
				$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
				$this->db_sugar->where('leads_cstm.case_id_c', $caseid);						
				$this->db_sugar->where('dateapproved_c IS NOT NULL');						
				$this->db_sugar->where('revenue_line_items_cstm.service_parent_id_c IS NULL');						
				$this->db_sugar->where('leads.deleted', 0);						
				$this->db_sugar->where('revenue_line_items.deleted', 0);
				//$this->db_sugar->group_by('dateapproved_c'); 
				//$this->db_sugar->order_by('dateapproved_c',"desc");
				//$this->db_sugar->limit(1, 0);						
				//print $this->db_sugar->last
				$data = $this->db_sugar->get(); 
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

		public function get_business_contact($contactId)
		{
			$this->db_sugar->select("epi_business.id, epi_business.name");
			
			$this->db_sugar->from('epi_business');
			
			$this->db_sugar->join('contacts_epi_business_1_c', 'contacts_epi_business_1_c.contacts_epi_business_1epi_business_idb = epi_business.id', 'left');	

			$this->db_sugar->where('contacts_epi_business_1_c.contacts_epi_business_1contacts_ida',$contactId);
			
			$this->db_sugar->where('epi_business.deleted',0);
			
			$this->db_sugar->order_by('epi_business.name',"desc");
			
			$data = $this->db_sugar->get();
			$record = $data->result();
            return $record ; 
		}

		/*
		No Knowledge 
		Parameters. 
		Author- 
		*/

		public function getBOCases()
		{
			/*SELECT round(SUM(likely_case),2) AS total_fees
			from revenue_line_items	
			left join revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id
			left join leads as l ON l..opportunity_id = revenue_line_items.opportunity_id
			left join leads_cstm as lc ON lc.id_c = l.id
			WHERE lc.case_id_c= leads_cstm . case_id_c					
			AND dateapproved_c IS NOT NULL					
			AND l.deleted = 0				
			AND revenue_line_items.deleted = 0 */


				
				
				
				
			// $this->db_sugar->select(" leads_cstm.case_id_c, contacts_cstm.ctax_status_c, contacts_cstm.tap_status_c, aggre_aggregatevalues_cstm.billing_status_c, language_prequal_c as language, DATE_FORMAT(aggre_aggregatevalues_cstm.date_ar_flipped_c, '%m/%d/%Y')  as date_ar_flipped, DATE_FORMAT(aggre_aggregatevalues_cstm.last_status_update_date_c, '%m/%d/%Y')  as status_date, concat(leads.first_name, ' ',leads.last_name) as name, leadm_leadmarketing.lead_src, (SELECT round(SUM(likely_case),2) AS total_fees 
			// from revenue_line_items	
			// left join revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id
			// left join leads as l ON l.opportunity_id = revenue_line_items.opportunity_id
			// left join leads_cstm as lc ON lc.id_c = l.id
			// WHERE lc.case_id_c= leads_cstm . case_id_c					
			// AND dateapproved_c IS NOT NULL					
			// AND l.deleted = 0				
			// AND revenue_line_items.deleted = 0
			// GROUP by dateapproved_c
			// ORDER BY dateapproved_c DESC LIMIT 1) as total_fee ");  
			
			// $this->db_sugar->from('aggre_aggregatevalues');

			// $this->db_sugar->join('aggre_aggregatevalues_cstm', 'aggre_aggregatevalues_cstm.id_c = aggre_aggregatevalues.id', 'left');	

			// $this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb = aggre_aggregatevalues.id', 'left');	
			
			// $this->db_sugar->join('leads', 'leads.id = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida', 'left');	
			
			// $this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');
			
            // $this->db_sugar->join('leads_leadm_leadmarketing_1_c', 'leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida = leads.id', 'left');

			// $this->db_sugar->join('leadm_leadmarketing_cstm', 'leadm_leadmarketing_cstm.id_c = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb', 'left');
			
			// $this->db_sugar->join('leadm_leadmarketing', 'leadm_leadmarketing.id = leadm_leadmarketing_cstm.id_c', 'left');
			
			// $this->db_sugar->join('contacts_cstm', 'contacts_cstm.case_id_c = leads_cstm.case_id_c', 'left');	
			// $this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');	

			// $this->db_sugar->where('aggre_aggregatevalues.deleted',0);
			// $this->db_sugar->where('leads_aggre_aggregatevalues_1_c.deleted',0);
			// $this->db_sugar->where('leads.deleted',0);
			// $this->db_sugar->where('contacts.deleted',0);
			// $this->db_sugar->where('leads_leadm_leadmarketing_1_c.deleted',0);
			// $this->db_sugar->where('activate_for_assignment_c','Ready for Assignment');
			// $this->db_sugar->where('leadm_leadmarketing_cstm.attributable_c','1');
			// $this->db_sugar->order_by('aggre_aggregatevalues_cstm.date_ar_flipped_c',"asc");
			//$this->db_sugar->order_by('aggre_aggregatevalues.date_modified',"desc");

			// $data = $this->db_sugar->get();
			// $record = $data->result();
			//print $this->db_sugar->last_query();
			$sql="SELECT DISTINCT
			contacts_cstm.us_only_c as us_only,
            leads_cstm.case_id_c,
            contacts_cstm.ctax_status_c,
            contacts_cstm.tap_status_c,
            aggre_aggregatevalues_cstm.billing_status_c,
            language_prequal_c AS language,
            DATE_FORMAT(
                aggre_aggregatevalues_cstm.date_ar_flipped_c,
                '%m/%d/%Y'
            ) AS date_ar_flipped,
            DATE_FORMAT(
                aggre_aggregatevalues_cstm.last_status_update_date_c,
                '%m/%d/%Y'
            ) AS status_date,
            CONCAT(
                leads.first_name,
                ' ',
                leads.last_name
            ) AS name,
            leadm_leadmarketing.lead_src,
			leadm_leadmarketing.lead_campaign,
            (
            SELECT
                ROUND(SUM(likely_case),
                2) AS total_fees
            FROM
                revenue_line_items
            LEFT JOIN revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id
            LEFT JOIN leads AS l
            ON
                l.opportunity_id = revenue_line_items.opportunity_id
            LEFT JOIN leads_cstm AS lc
            ON
                lc.id_c = l.id
            WHERE
                lc.case_id_c = leads_cstm.case_id_c AND dateapproved_c IS NOT NULL AND l.deleted = 0 AND revenue_line_items.deleted = 0
            GROUP BY
                dateapproved_c
            ORDER BY
                dateapproved_c
            DESC
        LIMIT 1
        ) AS total_fee
        FROM
            aggre_aggregatevalues
        LEFT JOIN aggre_aggregatevalues_cstm ON aggre_aggregatevalues_cstm.id_c = aggre_aggregatevalues.id
        LEFT JOIN leads_aggre_aggregatevalues_1_c ON leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb = aggre_aggregatevalues.id AND IFNULL(leads_aggre_aggregatevalues_1_c.deleted,0)=0
        LEFT JOIN leads ON leads.id = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida
        LEFT JOIN leads_cstm ON leads_cstm.id_c = leads.id
        LEFT JOIN leads_leadm_leadmarketing_1_c ON leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida = leads.id AND leads_leadm_leadmarketing_1_c.deleted=0
        LEFT JOIN leadm_leadmarketing ON leadm_leadmarketing.id = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb AND leadm_leadmarketing.deleted = 0
        LEFT JOIN leadm_leadmarketing_cstm ON leadm_leadmarketing_cstm.id_c = leadm_leadmarketing.id
        
        LEFT JOIN contacts_cstm ON contacts_cstm.case_id_c = leads_cstm.case_id_c
        LEFT JOIN contacts ON contacts.id = contacts_cstm.id_c
        INNER JOIN cases_cstm ON cases_cstm.case_id_c = leads_cstm.case_id_c
        INNER JOIN cases ON cases.id = cases_cstm.id_c AND cases.deleted = '0'
        WHERE
             aggre_aggregatevalues.deleted = 0  AND leads.deleted = 0 AND contacts.deleted = 0 AND  activate_for_assignment_c = 'Ready for Assignment' 
		GROUP BY leads_cstm.case_id_c        
        ORDER BY
            aggre_aggregatevalues_cstm.date_ar_flipped_c ASC ";
		//AND leads_aggre_aggregatevalues_1_c.deleted = 0
		//AND leadm_leadmarketing_cstm.attributable_c = 1	 // Query updated on 24-05-2023 https://trello.com/c/Dgr9OAA9/ 2312-ip-ready-for-assignment-error	//  lead_campaign added on 19-10-2023 https://trello.com/c/7YM16iRf/612-partner-flags-for-all-partners					
				$record1 = $this->db_sugar->query($sql);
				$record = $record1->result();
				
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

		public function get_services($caseid) 
		{
			if($caseid != '')		
			{
			
				/*$this->db_sugar->select("revenue_line_items.id,  DATE_FORMAT(revenue_line_items_cstm.dateapproved_c, '%m/%d/%Y')  as approved_date, sale_type_c as sale_type, concat(COALESCE(`product_catalog_type_c`,''), ' - ',COALESCE(ptem.name,'')) as name , quantity, round(revenue_line_items.list_price,2) as list_price, discount_reason_c , round(discount_amount,2) as discount_amount, sales_stage, round(minimum_payment_c,2) as minimum_payment_c, revenue_line_items.date_entered, revenue_line_items.description, dateapproved_c , concat(users.first_name, ' ',users.last_name) as created_by, concat(users1.first_name, ' ',users1.last_name) as approved_by, concat(users2.first_name, ' ',users2.last_name) as sold_by, revenue_line_items_cstm.lead_from_c, years_list_c ");  

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
				//$this->db_sugar->where('revenue_line_items.sales_stage', 'Closed Won');
				$where = '(revenue_line_items_cstm.dateapproved_c IS NOT NULL OR revenue_line_items.sales_stage = "Closed Won")';	
				$this->db_sugar->where($where);	
				$this->db_sugar->order_by('revenue_line_items.date_entered',"desc");

				$data = $this->db_sugar->get();
				$record = $data->result();*/

				$sql="SELECT revenue_line_items_linking.rli_id,`revenue_line_items_cstm`.`service_parent_id_c`,`product_types_cstm`.`is_package_c` ,`product_categories`.`parent_id`,`revenue_line_items`.`id`, DATE_FORMAT(revenue_line_items_cstm.dateapproved_c, '%m/%d/%Y') as approved_date, `sale_type_c` as `sale_type`, concat(COALESCE(`product_catalog_type_c`,''), ' - ',COALESCE(ptem.name,'')) as name, `quantity`, round(revenue_line_items.likely_case, 2) as list_price, `discount_reason_c`, round(discount_amount, 2) as discount_amount, `sales_stage`, round(minimum_payment_c, 2) as minimum_payment_c, `revenue_line_items`.`date_entered`, `revenue_line_items`.`description`, `dateapproved_c`, concat(users.first_name, ' ', users.last_name) as created_by, concat(users1.first_name, ' ', users1.last_name) as approved_by, concat(users2.first_name, ' ', users2.last_name) as sold_by, `revenue_line_items_cstm`.`lead_from_c`, `years_list_c`,revenue_line_items_linking.module_name
					FROM `revenue_line_items`
					LEFT JOIN `revenue_line_items_cstm` ON `revenue_line_items_cstm`.`id_c` = `revenue_line_items`.`id`
					LEFT JOIN `product_templates` `ptem` ON `ptem`.`id` = `revenue_line_items`.`product_template_id`
					LEFT JOIN `product_types_cstm` ON `product_types_cstm`.`id_c` = `ptem`.`type_id`
					LEFT JOIN `leads` ON `leads`.`opportunity_id` = `revenue_line_items`.`opportunity_id`
					LEFT JOIN `leads_cstm` ON `leads_cstm`.`id_c` = `leads`.`id`
					LEFT JOIN `product_categories` ON `product_categories`.`id` = `revenue_line_items`.`category_id`
					LEFT JOIN `users` ON `users`.`id` = `revenue_line_items`.`created_by`
					LEFT JOIN `users` `users1` ON `users1`.`id` = `revenue_line_items_cstm`.`user_id1_c`
					LEFT JOIN `users` `users2` ON `users2`.`id` = `revenue_line_items_cstm`.`user_id2_c`
					LEFT JOIN revenue_line_items_linking ON revenue_line_items_linking.rli_id = revenue_line_items.id AND revenue_line_items_linking.deleted=0
					WHERE `leads_cstm`.`case_id_c` = '".$caseid."'
					AND `revenue_line_items`.`deleted` = 0
					AND (`revenue_line_items_cstm`.`dateapproved_c` IS NOT NULL OR revenue_line_items_cstm.dateapproved_c IS NOT NULL)
					GROUP by revenue_line_items.id
					ORDER BY `revenue_line_items_cstm`.`dateapproved_c` DESC,`revenue_line_items_cstm`.`service_parent_id_c`";

					// group by in the above query added on 28-01-2023 https://trello.com/c/2phYZtgT/1656-monitoring-service-boat-cr-portal
					//AND `revenue_line_items`.`sales_stage` = 'Closed Won' 	
				$record1 = $this->db_sugar->query($sql);
				//print $this->db_sugar->last_query();
				$record = $record1->result();

				//print_r($record);
				if(is_array($record) && count($record) > 0){

					// added on 28-01-2023 https://trello.com/c/2phYZtgT/1656-monitoring-service-boat-cr-portal
					//to get multiple records linked with monitoring service
					for($i=0; $i<count($record); $i++)
					{						
						if(!empty($record[$i]->module_name))
						{					
							if($record[$i]->module_name == 'mon_Monitoring' || $record[$i]->module_name == 'Tax Assurance Program' || $record[$i]->module_name == 'reso_Tax_Preparation' || $record[$i]->module_name == 'Tax Preparation')
							{
								$getMultipleIds = "SELECT module_name FROM revenue_line_items_linking WHERE revenue_line_items_linking.rli_id = '" . $record[$i]->rli_id . "' ;";
								$recordMultipleIDs = $this->db_sugar->query($getMultipleIds);
								$recordMIDs = $recordMultipleIDs->result();
								
								if(count($recordMIDs) > 1)
								{
									$moduleNameMon = array();
									for ($j = 0; $j < count($recordMIDs); $j++)
									{
										if($recordMIDs[$j]->module_name == 'mon_Monitoring')
										{
											$recordMIDs[$j]->module_name = 'Tax Assurance Program';
										}
										if($recordMIDs[$j]->module_name == 'reso_Tax_Preparation')
										{
											$recordMIDs[$j]->module_name = 'Tax Preparation';
										}
										if(!in_array($recordMIDs[$j]->module_name, $moduleNameMon))
										{
											array_push($moduleNameMon, $recordMIDs[$j]->module_name);	
										}
									}
									if(in_array("Tax Assurance Program", $moduleNameMon) || in_array("mon_Monitoring", $moduleNameMon))
									{
										$record[$i]->module_name = implode(",", $moduleNameMon);
									}
								}
							} // ended on 28-01-2023 
						}
					}
					return $record;
				}
				else 
					return 0;

			}
		}

		/*########################################
				Resolution Module Code Start
		########################################*/

		/*
			Get all resolution for backoffice detail view page 
			Parameters. CaseId
			Author- 
		*/
		public function fetch_BO_resolution($case_id)
		{	
			$this->db_sugar->select("reso_resolutions.id as tax_prep_id, reso_resolutions_cstm.id_c as reso_cstm_id,contacts_reso_resolutions_1contacts_ida as res_cnt_ida, users.id as practitionar_c ,usr1.id as advocate_c, service_status_c, reso_resolutions_cstm.resolution_escalation_status_c, related_business_c, state_c, reso_resolutions_cstm.collection_division_c, reso_resolutions_cstm.resolution_type_c, revenue_line_items_linking.rli_id"); 
			$this->db_sugar->from('reso_resolutions');
			$this->db_sugar->join('reso_resolutions_cstm', 'reso_resolutions_cstm.id_c=reso_resolutions.id', 'left');	
			$this->db_sugar->join('contacts_reso_resolutions_1_c', 'contacts_reso_resolutions_1_c.contacts_reso_resolutions_1reso_resolutions_idb = reso_resolutions.id', 'left');		
			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_reso_resolutions_1_c.contacts_reso_resolutions_1contacts_ida', 'left');	
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');	
			$this->db_sugar->join('users', 'reso_resolutions_cstm.user_id_c = users.id', 'left');
			$this->db_sugar->join('users as  usr1', 'reso_resolutions_cstm.user_id1_c = usr1.id', 'left');
			$this->db_sugar->join('revenue_line_items_linking', 'revenue_line_items_linking.module_id = reso_resolutions.id AND revenue_line_items_linking.deleted=0', 'left');
			$this->db_sugar->where('contacts_cstm.case_id_c', $case_id);
			$this->db_sugar->where('reso_resolutions.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			$this->db_sugar->where('contacts_reso_resolutions_1_c.deleted', 0);	
			//$this->db_sugar->where('revenue_line_items_linking.deleted', 0);

			$data = $this->db_sugar->order_by('reso_resolutions.date_entered','DESC')->get();
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
		
		/*
			Get all resolution for backoffice detail view page 
			Parameters. CaseId
			Author- 
		*/
		public function createResolution($caseId, $service_status, $collection_division, $resolution_type, $practitionar, $advocate, $state, $business_contact, $rliId, $escalationStatus)
		{
			$error = array();
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();
			//file_put_contents('createReso1.txt',$caseId . ' - ' . $contactId);
			if($contactId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'name' => $randomString,
					'service_status_c' => $service_status,
					'state_c' => $state,
                    'related_business_c' => $business_contact,
					'collection_division_c' => $collection_division,
					'resolution_type_c' => $resolution_type,
					'user_id_c' => $practitionar,
					'user_id1_c' => $advocate,
					'contacts_reso_resolutions_1contacts_ida'=> $contactId,
					'resolution_work_start_date_c'=> date('y-m-d'),
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'resolution_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Resolutions";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar($url, $record);
				//file_put_contents('createReso.txt',print_r($createdRecord,true));
				$moduleId = $createdRecord->id;

				if(!empty($moduleId))
				{
					$moduleName = 'Resolutions';
					$this->createLinkRecordTask($rliId, $moduleId, $moduleName);

					$this->db_sugar->select("contacts_reso_resolutions_1_c.contacts_reso_resolutions_1contacts_ida as contact_id"); 
					$this->db_sugar->from('reso_resolutions');
					$this->db_sugar->join('contacts_reso_resolutions_1_c', 'contacts_reso_resolutions_1_c.contacts_reso_resolutions_1reso_resolutions_idb = reso_resolutions.id', 'inner');
					$this->db_sugar->where('reso_resolutions.deleted', 0);	
					$this->db_sugar->where('contacts_reso_resolutions_1_c.deleted', 0);	
					$this->db_sugar->where('contacts_reso_resolutions_1_c.contacts_reso_resolutions_1reso_resolutions_idb', $moduleId);	

					$dataContact = $this->db_sugar->get();
					$recordContact = $dataContact->row();
					
					if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
					{
						$contact_id = $recordContact->contact_id;
						
						$recordContactUpdate = array(
							'onboarding_status_date_c'=> date('Y-m-d'),
							'onboarding_rci_status_c'=> '',
						);
		
						$urlContact = "/Contacts/" . $contact_id;
							
						// Call global function for store and update data by paramenters url and record
						$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
						
					}
					
				}
				if(property_exists($createdRecord, 'error'))  //if($createdRecord->error)
				{
					return $createdRecord->error_message;
				}
				else
				{
					return true;
				}
				/*if($createdRecord->id != '')
				{
					$error['status'] =  true;
					//$error['msg'] = 'No contact found for this case';
					return $error;
				}
				else
				{
					$error['status'] =  false;
					$error['msg'] = 'Unable to create Resolution Record';
					return $error;
					//return false; 
				}*/
			}
			else
			{
				$error['status'] =  false;
				$error['msg'] = 'No contact found for this case';
				return $error['msg'];
			}
		}	
		
		/*
			Update resolution record for backoffice detail view page 
			Parameters. All form input and resolutionId
			Author- 
		*/
		public function updateResolution($service_status, $collection_division, $resolution_type, $practitionar, $advocate, $resoId, $state, $business_contact, $escalationStatus)
		{
			$error = array();
			$uid = $this->session->userdata('User_id');
			session_write_close();
			
			if($resoId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'service_status_c' => $service_status,
					'state_c' => $state,
                    'related_business_c' => $business_contact,
					'collection_division_c' => $collection_division,
					'resolution_type_c' => $resolution_type,
					'user_id_c' => $practitionar,
					'user_id1_c' => $advocate,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'resolution_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Resolutions/" . $resoId;
					
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->storeDataInSugar($url, $record, $resoId);
				//print 'updatedRecord - ';
				//print_r($updatedRecord);
				
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					file_put_contents('ResoUpdate.txt','line no 715 : service_status - '.$service_status."\n",FILE_APPEND);

					if($service_status=='Completed')
					{

						$this->db_sugar->select("contacts_reso_resolutions_1_c.contacts_reso_resolutions_1contacts_ida as contact_id"); 

						$this->db_sugar->from('reso_resolutions');
						$this->db_sugar->join('contacts_reso_resolutions_1_c', 'contacts_reso_resolutions_1_c.contacts_reso_resolutions_1reso_resolutions_idb = reso_resolutions.id', 'inner');
						$this->db_sugar->where('reso_resolutions.deleted', 0);	
						$this->db_sugar->where('contacts_reso_resolutions_1_c.deleted', 0);	
						$this->db_sugar->where('contacts_reso_resolutions_1_c.contacts_reso_resolutions_1reso_resolutions_idb', $resoId);	

						$dataContact = $this->db_sugar->get();
						$recordContact = $dataContact->row();
						
						if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
						{
							$contact_id = $recordContact->contact_id;
							
							$recordContactUpdate = array(
								'onboarding_status_date_c'=> date('Y-m-d'),
							);
			
							$urlContact = "/Contacts/" . $contact_id;
								
							// Call global function for store and update data by paramenters url and record
							$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
							
						}
					}
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		
		/*
			Delete resolution record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function deleteResolution($caseid,$id)
		{	
			$user_id = $this->session->userdata('User_id');	
			session_write_close();

			if($caseid != '' && $id != '')
			{				
				$record = array(

					'modified_user_id' => $user_id,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'deleted' => 1
				);

				$url = "/reso_Resolutions/" . $id;
					
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->deleteDataInSugar($url, $record, $id);
				//print_r($updatedRecord);
				//deleting from linking table
				$updateLinkedRec = "UPDATE revenue_line_items_linking set deleted=1, date_modified=now() WHERE NOT EXISTS (SELECT id FROM reso_resolutions WHERE deleted = '0' AND id = '".$id."') AND revenue_line_items_linking.module_id='".$id."'";
				
				$exeUpdateLinkedRec = $this->db_sugar->query($updateLinkedRec);
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					return true;
				}				
				
				/*$sql = "UPDATE reso_resolutions set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";	

				$query = $this->db_sugar->query($sql);
				
				if($query)
				{
					$sql2 = "UPDATE contacts_reso_resolutions_1_c set deleted=1, date_modified=now() WHERE contacts_reso_resolutions_1reso_resolutions_idb='$id' ";	
				
					$query2 = $this->db_sugar->query($sql2);
				
					return 1 ; 
				}
				else{
					$err =  $this->db_sugar->error();
				}	*/					
			}
			else
			{
				return 0;
			}
		}	
		/*########################################
				Resolution Module Code End
		########################################*/

		/*########################################
			Tax Preparation Module Code Start
		########################################*/

		/*
			Get all backoffice tax preparation record from sugar 
			Parameters. 
			Author- 
		*/
		public function fetch_BO_tax_prepration($case_id)
		{ 
			$this->db_sugar->select("reso_tax_preparation.id as tax_prep_id, reso_tax_preparation_cstm.return_type_c, reso_tax_preparation_cstm.id_c as tp_cstm_id,tax_year_c,service_status_c,users.id as tax_preparer_c, contacts_cstm.user_id1_c as cadvocate_id, contacts_cstm.user_id3_c as preparer_id, contacts_cstm.user_id2_c as practitionar_id, contacts_cstm.user_id4_c as reviewer_id, contacts_cstm.user_id7_c as escalation_id, reso_tax_preparation_cstm.user_id3_c as advocate_id, reso_tax_preparation_cstm.tax_prep_escalation_status_c, revenue_line_items_linking.rli_id");   // Updated on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned

			$this->db_sugar->from('reso_tax_preparation');
			
			$this->db_sugar->join('reso_tax_preparation_cstm', 'reso_tax_preparation_cstm.id_c=reso_tax_preparation.id', 'left');	
			$this->db_sugar->join('contacts_reso_tax_preparation_1_c', 'contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1reso_tax_preparation_idb = reso_tax_preparation.id', 'left');		

			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1contacts_ida', 'left');
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');	
			$this->db_sugar->join('users', 'reso_tax_preparation_cstm.user_id_c = users.id', 'left');
			$this->db_sugar->join('revenue_line_items_linking', 'revenue_line_items_linking.module_id = reso_tax_preparation.id AND revenue_line_items_linking.deleted=0', 'left');

			// $this->db_sugar->join('`users` `advocate_users`' , 'reso_tax_preparation_cstm.user_id3_c = advocate_users.id', 'left');
			
			$this->db_sugar->where('contacts_cstm.case_id_c', $case_id);
			$this->db_sugar->where('reso_tax_preparation.deleted', 0);	
			$this->db_sugar->where('contacts_reso_tax_preparation_1_c.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			//$this->db_sugar->where('revenue_line_items_linking.deleted', 0);
			
			$data = $this->db_sugar->order_by('reso_tax_preparation.date_entered','DESC')->get();
			$record = $data->result();
			// print  $this->db_sugar->last_query();
			if(is_array($record) && count($record) > 0)
			{
				return $record;

			}

			else
			{				
				return 0;
			}
		}

		//added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
		public function fetch_BO_ctax_plus($case_id)
		{ 
			// $this->db_sugar->select("reso_tax_preparation.id as tax_prep_id, reso_tax_preparation_cstm.return_type_c, reso_tax_preparation_cstm.id_c as tp_cstm_id,tax_year_c,service_status_c,users.id as tax_preparer_c, contacts_cstm.user_id1_c as advocate_id, contacts_cstm.user_id3_c as preparer_id, contacts_cstm.user_id2_c as practitionar_id, contacts_cstm.user_id4_c as reviewer_id, revenue_line_items_linking.rli_id"); 
			$this->db_sugar->select("ctaxp_ctaxplus.id as ctax_plus_id,ctaxp_ctaxplus.name, ctaxp_ctaxplus_cstm.id_c as cp_cstm_id, ctaxp_ctaxplus_cstm.ctaxplus_service_status_c,initial_tp_year_included_c,ctaxp_ctaxplus_cstm.ctaxplus_service_status_c,ctaxp_ctaxplus_cstm.ctaxplusstartdate_c,ctaxp_ctaxplus_cstm.ctaxplus_intro_call_c, revenue_line_items_linking.rli_id"); 

			$this->db_sugar->from('ctaxp_ctaxplus');
			
			$this->db_sugar->join('ctaxp_ctaxplus_cstm', 'ctaxp_ctaxplus_cstm.id_c=ctaxp_ctaxplus.id', 'left');	
			$this->db_sugar->join('contacts_ctaxp_ctaxplus_1_c', 'contacts_ctaxp_ctaxplus_1_c.contacts_ctaxp_ctaxplus_1ctaxp_ctaxplus_idb = ctaxp_ctaxplus.id', 'left');		

			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_ctaxp_ctaxplus_1_c.contacts_ctaxp_ctaxplus_1contacts_ida', 'left');
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');	
			$this->db_sugar->join('users', 'ctaxp_ctaxplus_cstm.user_id_c = users.id', 'left');
			$this->db_sugar->join('revenue_line_items_linking', 'revenue_line_items_linking.module_id = ctaxp_ctaxplus.id AND revenue_line_items_linking.deleted=0', 'left');

			// $this->db_sugar->join('`users` `advocate_users`' , 'ctaxp_ctaxplus_cstm.user_id3_c = advocate_users.id', 'left');
			
			$this->db_sugar->where('contacts_cstm.case_id_c', $case_id);
			$this->db_sugar->where('ctaxp_ctaxplus.deleted', 0);	
			$this->db_sugar->where('contacts_ctaxp_ctaxplus_1_c.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			//$this->db_sugar->where('revenue_line_items_linking.deleted', 0);
			
			$data = $this->db_sugar->order_by('ctaxp_ctaxplus.date_entered','DESC')->get();
			$record = $data->result();
			
			if(is_array($record) && count($record) > 0)
			{
				return $record;

			}

			else
			{				
				return 0;
			}
		}//end added on 04-04-2024

		/*
			Delete tax preparation record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function deleteTaxPrepration($caseid,$id)
		{		
			if($caseid != '' && $id != '')
			{
				$user_id = $this->session->userdata('User_id');
				session_write_close();
				
				$record = array(

					'modified_user_id' => $user_id,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'deleted' => 1
				);

				$url = "/reso_Tax_Preparation/" . $id;
					
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->deleteDataInSugar($url, $record, $id);
				//print_r($updatedRecord);
				//deleting from linking table

				$updateLinkedRec = "UPDATE revenue_line_items_linking set deleted=1, date_modified=now() WHERE NOT EXISTS (SELECT id FROM reso_tax_preparation WHERE deleted = '0' AND id = '".$id."') AND revenue_line_items_linking.module_id='".$id."'";
				
				$exeUpdateLinkedRec = $this->db_sugar->query($updateLinkedRec);
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					return true;
				}			
				/*$sql = "UPDATE reso_tax_preparation set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";	

				$query = $this->db_sugar->query($sql);
				
				if($query)
				{
					$sql2 = "UPDATE contacts_reso_tax_preparation_1_c set deleted=1, date_modified=now() WHERE contacts_reso_tax_preparation_1reso_tax_preparation_idb='$id' ";	
				
					$query2 = $this->db_sugar->query($sql2);
				
					return 1 ; 
				}
				else{
					$err =  $this->db_sugar->error();
				}	*/					
			}
			else
			{
				return 0;
			}
		}

		//added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
		/*
			delete ctax plus record for backoffice detail view page 
			Parameters. 
			Author- Akash
		*/
		public function deleteCtaxPlus($caseid,$id)
		{		
			if($caseid != '' && $id != '')
			{
				$user_id = $this->session->userdata('User_id');
				session_write_close();
				
				$record = array(

					'modified_user_id' => $user_id,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'deleted' => 1
				);

				$url = "/CtaxP_CtaxPlus/" . $id;
					
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->deleteDataInSugar($url, $record, $id);
				//print_r($updatedRecord);
				//deleting from linking table

				$updateLinkedRec = "UPDATE revenue_line_items_linking set deleted=1, date_modified=now() WHERE NOT EXISTS (SELECT id FROM ctaxp_ctaxplus WHERE deleted = '0' AND id = '".$id."') AND revenue_line_items_linking.module_id='".$id."'";
				
				$exeUpdateLinkedRec = $this->db_sugar->query($updateLinkedRec);
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					return true;
				}								
			}
			else
			{
				return 0;
			}
		} // end added on 04-04-2024

		/*
			Create tax preparation record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function createTaxPrepration($caseId ,$tax_year ,$advocate ,$service_status ,$tax_preparer, $return_type, $rliId, $escalationStatus)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();

			if($contactId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'name' => $randomString,
					'tax_year_c' => $tax_year,
					'user_id3_c' => $advocate,
					'service_status_c' => $service_status,
					'user_id_c' => $tax_preparer,
					'contacts_reso_tax_preparation_1contacts_ida'=> $contactId,
					'tax_prep_work_started_c'=> date('Y-m-d'),
					'return_type_c' => $return_type,
					'source_created_c' => "CaseReview",
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'tax_prep_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Tax_Preparation";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar($url, $record);
				$moduleId = $createdRecord->id;
				if(!empty($moduleId))
				{
					$moduleName = 'Tax Preparation';
					$this->createLinkRecordTask($rliId, $moduleId, $moduleName);

					$this->db_sugar->select("contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1contacts_ida as contact_id"); 

					$this->db_sugar->from('reso_tax_preparation');
					$this->db_sugar->join('contacts_reso_tax_preparation_1_c', 'contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1reso_tax_preparation_idb = reso_tax_preparation.id', 'inner');
					$this->db_sugar->where('reso_tax_preparation.deleted', 0);	
					$this->db_sugar->where('contacts_reso_tax_preparation_1_c.deleted', 0);	
					$this->db_sugar->where('contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1reso_tax_preparation_idb', $moduleId);	

					$dataContact = $this->db_sugar->get();
					$recordContact = $dataContact->row();
					
					if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
					{
						$contact_id = $recordContact->contact_id;
						
						$recordContactUpdate = array(
							'onboarding_status_date_c'=> date('Y-m-d'),
							'onboarding_rci_status_c'=> '',
						);
		
						$urlContact = "/Contacts/" . $contact_id;
							
						// Call global function for store and update data by paramenters url and record
						$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
					}
				}
				
				if(property_exists($createdRecord, 'error')) 
				{
					return $createdRecord->error_message;
				}
				else
				{
					if($createdRecord->id != '')
					{
						file_put_contents('TPCreate.txt',$createdRecord->id,FILE_APPEND);
						file_put_contents('TPCreate.txt',print_r($record,true),FILE_APPEND);
						file_put_contents('TPCreate.txt','-----------------------------------------------------------',FILE_APPEND);
						return true;
					}
				}				
			}
			else
			{
				$msg = "Contact Id not found";
				return $msg;
			}
		}

		//added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
		/*
			Create Ctax Plus record for backoffice detail view page 
			Parameters. 
			Author- Akash
		*/
		public function createCtaxPlus($case_id ,$name ,$year_started ,$start_date ,$intro_call, $service_status, $rliId)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($case_id);
			$randomString = $this->generateRandomString();

			if($contactId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'name' => $name,
					'initial_tp_year_included_c' => $year_started,
					'ctaxplusstartdate_c' => $start_date,
					'ctaxplus_intro_call_c' => $intro_call,
					'ctaxplus_service_status_c' => $service_status,
					'contacts_ctaxp_ctaxplus_1contacts_ida'=> $contactId,
					// 'tax_prep_work_started_c'=> date('Y-m-d'),
					'source_created_c' => "CaseReview",
					'source_updated_c' => "CaseReview", 
				);

				$url = "/CtaxP_CtaxPlus";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar($url, $record);
				$moduleId = $createdRecord->id;
				if(!empty($moduleId))
				{
					$moduleName = 'Ctax Plus';
					$this->createLinkRecordTask($rliId, $moduleId, $moduleName);
				}
				
				if(property_exists($createdRecord, 'error')) 
				{
					return $createdRecord->error_message;
				}
				else
				{
					if($createdRecord->id != '')
					{
						file_put_contents('CPCreate.txt',$createdRecord->id,FILE_APPEND);
						file_put_contents('CPCreate.txt',print_r($record,true),FILE_APPEND);
						file_put_contents('CPCreate.txt','-----------------------------------------------------------',FILE_APPEND);
						return true;
					}
				}				
			}
			else
			{
				$msg = "Contact Id not found";
				return $msg;
			}
		} // end added on 04-04-2024 

		/*
			Update tax preparation record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function updateTaxPrepration($tax_prep_id,$tax_year,$advocate ,$service_status ,$tax_preparer,  $return_type, $escalationStatus)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			
			if($tax_prep_id)
			{
				$record = array(
					'modified_user_id' => $uid,
					'tax_prep_id' => $tax_prep_id,
					'tax_year_c' => $tax_year,
					'user_id3_c' => $advocate,
					'service_status_c' => $service_status,
					'user_id_c' => $tax_preparer,
					'return_type_c' => $return_type,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'tax_prep_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Tax_Preparation/" . $tax_prep_id;
				
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->storeDataInSugar($url, $record, $tax_prep_id);
				
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					if($service_status=='Completed')
					{
						$this->db_sugar->select("contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1contacts_ida as contact_id"); 

						$this->db_sugar->from('reso_tax_preparation');
						$this->db_sugar->join('contacts_reso_tax_preparation_1_c', 'contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1reso_tax_preparation_idb = reso_tax_preparation.id', 'inner');
						$this->db_sugar->where('reso_tax_preparation.deleted', 0);	
						$this->db_sugar->where('contacts_reso_tax_preparation_1_c.deleted', 0);	
						$this->db_sugar->where('contacts_reso_tax_preparation_1_c.contacts_reso_tax_preparation_1reso_tax_preparation_idb', $resoId);	

						$dataContact = $this->db_sugar->get();
						$recordContact = $dataContact->row();
						
						if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
						{
							$contact_id = $recordContact->contact_id;
							
							$recordContactUpdate = array(
								'onboarding_status_date_c'=> date('Y-m-d'),
							);
			
							$urlContact = "/Contacts/" . $contact_id;
								
							// Call global function for store and update data by paramenters url and record
							$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
						}
					}

					if($updatedRecord->id != '')
					{
						file_put_contents('TPUpdate.txt',$updatedRecord->id,FILE_APPEND);
						file_put_contents('TPUpdate.txt',print_r($record,true),FILE_APPEND);
						file_put_contents('TPUpdate.txt','-----------------------------------------------------------',FILE_APPEND);
						return true;
					}
				}
			}
			else
			{
				$msg = "Record Id not found";
				return $msg;
			}
		}

		//added on 04-04-2024 https://trello.com/c/tVsw0XhK/1159-ctax-boat-updates-needed
		/*
			Update ctax plus record for backoffice detail view page 
			Parameters. 
			Author- Akash
		*/
		public function updateCtaxPlus($ctax_plus_id,$name ,$year_started ,$start_date ,$intro_call, $service_status)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			file_put_contents('CPUpdate10.txt', $ctax_plus_id );
			if($ctax_plus_id)
			{
				$record = array(
					'modified_user_id' => $uid,
					// 'ctax_plus_id' => $ctax_plus_id,
					'name' => $name,
					'initial_tp_year_included_c' => $year_started,
					'ctaxplusstartdate_c' => $start_date,
					'ctaxplus_intro_call_c' => $intro_call,
					'ctaxplus_service_status_c' => $service_status,
					'source_updated_c' => "CaseReview", 
				);
				file_put_contents('CPUpdate1.txt', $ctax_plus_id . ' - ' .print_r($record,true));
				$url = "/CtaxP_CtaxPlus/" . $ctax_plus_id;
				
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->storeDataInSugar($url, $record, $ctax_plus_id);
				
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					if($updatedRecord->id != '')
					{
						file_put_contents('CPUpdate.txt',$updatedRecord->id,FILE_APPEND);
						file_put_contents('CPUpdate.txt',print_r($record,true),FILE_APPEND);
						file_put_contents('CPUpdate.txt','-----------------------------------------------------------',FILE_APPEND);
						return true;
					}
				}
			}
			else
			{
				$msg = "Record Id not found";
				return $msg;
			}
		} // end added on 04-04-2024 

		/*########################################
			Tax Preparation Module Code End
		########################################*/

		/*########################################
			Ancillary Services Module Code Start
		########################################*/

		/*
			Get all Ancillary Services record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function fetch_BO_anclSrv($case_id)
		{ 
			$this->db_sugar->select("reso_ancillary_services_cstm.id_c as ancl_srvc_cstm_id ,reso_ancillary_services.id as ancl_srvc_id ,service_status_c,service_c,users.id as practitioner_c,usr1.id as advocate_c,usr2.id as tax_preparer_c, reso_ancillary_services_cstm.misc_escalation_status_c, revenue_line_items_linking.rli_id"); 

			$this->db_sugar->from('reso_ancillary_services_cstm');
			
			$this->db_sugar->join('reso_ancillary_services', 'reso_ancillary_services_cstm.id_c=reso_ancillary_services.id', 'left');	

			$this->db_sugar->join('contacts_reso_ancillary_services_1_c', 'contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1reso_ancillary_services_idb = reso_ancillary_services.id', 'left');		

			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1contacts_ida', 'left');
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
			$this->db_sugar->join('users', 'reso_ancillary_services_cstm.user_id_c = users.id', 'left');

			$this->db_sugar->join('users as  usr1', 'reso_ancillary_services_cstm.user_id1_c = usr1.id', 'left');

			$this->db_sugar->join('users as  usr2', 'reso_ancillary_services_cstm.user_id2_c = usr2.id', 'left');
			$this->db_sugar->join('revenue_line_items_linking', 'revenue_line_items_linking.module_id = reso_ancillary_services.id AND revenue_line_items_linking.deleted=0', 'left');

			$this->db_sugar->where('reso_ancillary_services.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			$this->db_sugar->where('contacts_reso_ancillary_services_1_c.deleted', 0);	
			//$this->db_sugar->where('revenue_line_items_linking.deleted', 0);
			
			$this->db_sugar->where('contacts_cstm.case_id_c', $case_id);

			$data = $this->db_sugar->order_by('reso_ancillary_services.date_entered','DESC')->get();
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

		/*
			Create Ancillary Services record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function createAncillaryServices($caseId, $service_status, $service,$practitioner, $advocate, $rliId, $escalationStatus)
		{
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();

			if($contactId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'service_status_c' => $service_status,
					'service_c' => $service,
					'user_id_c' => $practitioner,
					'user_id1_c' => $advocate,
					'contacts_reso_ancillary_services_1contacts_ida'=> $contactId,
					'ancilliary_work_start_date_c'=> date('c'),
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'misc_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Ancillary_Services";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar( $url, $record);
				$moduleId = $createdRecord->id;
				if(!empty($moduleId))
				{
					$moduleName = 'Miscellaneous Resolutions Services';
					$this->createLinkRecordTask($rliId, $moduleId, $moduleName);

					$this->db_sugar->select("contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1contacts_ida as contact_id"); 

					$this->db_sugar->from('reso_ancillary_services');
					$this->db_sugar->join('contacts_reso_ancillary_services_1_c', 'contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1reso_ancillary_services_idb = reso_ancillary_services.id', 'inner');
					$this->db_sugar->where('reso_ancillary_services.deleted', 0);	
					$this->db_sugar->where('contacts_reso_ancillary_services_1_c.deleted', 0);	
					$this->db_sugar->where('contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1reso_ancillary_services_idb', $moduleId);	

					$dataContact = $this->db_sugar->get();
					$recordContact = $dataContact->row();
					
					if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
					{
						$contact_id = $recordContact->contact_id;
						
						$recordContactUpdate = array(
							'onboarding_status_date_c'=> date('Y-m-d'),
							'onboarding_rci_status_c'=> '',
						);
		
						$urlContact = "/Contacts/" . $contact_id;
							
						// Call global function for store and update data by paramenters url and record
						$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
					}
				}
				if(property_exists($createdRecord, 'error')) 
				{
					return $createdRecord->error_message;
				}
				else
				{
					return true;
				}
			}
			else
			{
				$msg = "Contact id not found";
				return $msg;
			}
		}	
		
		/*
			Save Ancillary Services record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function updateAncillaryServices($ancl_srvc_id, $service_status, $service,$practitioner, $advocate, $escalationStatus)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			
			if($ancl_srvc_id)
			{
				$record = array(
					'modified_user_id' => $uid,
					'service_status_c' => $service_status,
					'service_c' => $service,
					'user_id_c' => $practitioner,
					'user_id1_c' => $advocate,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					// 'user_id2_c' => $tax_preparer
					'misc_escalation_status_c' => $escalationStatus,
				);

				$url = "/reso_Ancillary_Services/" . $ancl_srvc_id;
				
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->storeDataInSugar($url, $record,$ancl_srvc_id);
				
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;
				}
				else
				{
					if($service_status=='Completed')
					{
						$this->db_sugar->select("contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1contacts_ida as contact_id"); 

						$this->db_sugar->from('reso_ancillary_services');
						$this->db_sugar->join('contacts_reso_ancillary_services_1_c', 'contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1reso_ancillary_services_idb = reso_ancillary_services.id', 'inner');
						$this->db_sugar->where('reso_ancillary_services.deleted', 0);	
						$this->db_sugar->where('contacts_reso_ancillary_services_1_c.deleted', 0);	
						$this->db_sugar->where('contacts_reso_ancillary_services_1_c.contacts_reso_ancillary_services_1reso_ancillary_services_idb', $resoId);	

						$dataContact = $this->db_sugar->get();
						$recordContact = $dataContact->row();
						
						if(isset($recordContact->contact_id) && !empty($recordContact->contact_id))
						{
							$contact_id = $recordContact->contact_id;
							
							$recordContactUpdate = array(
								'onboarding_status_date_c'=> date('Y-m-d'),
							);
			
							$urlContact = "/Contacts/" . $contact_id;
								
							// Call global function for store and update data by paramenters url and record
							$updatedRecordContact = $this->storeDataInSugar($urlContact, $recordContactUpdate, $contact_id);
						}
					}

					return true;
				}
			}
			else
			{
				$msg = "Record id not found";
				return $msg;
			}
		}

		/*
			Delete Ancillary Services record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function deleteAncillaryServices($caseid,$id)
		{		
			if($caseid != ''  && $id != '')
			{
				$user_id = $this->session->userdata('User_id');
				session_write_close();
				$record = array(

					'modified_user_id' => $user_id,
					'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					'deleted' => 1
				);

				$url = "/reso_Ancillary_Services/" . $id;
					
				// Call global function for deletion data by paramenters url and record
				$updatedRecord = $this->deleteDataInSugar($url, $record, $id);
				//deleting from linking table
				$updateLinkedRec = "UPDATE revenue_line_items_linking set deleted=1, date_modified=now() WHERE NOT EXISTS (SELECT id FROM reso_ancillary_services WHERE deleted = '0' AND id = '".$id."') AND revenue_line_items_linking.module_id='".$id."'";
				
				$exeUpdateLinkedRec = $this->db_sugar->query($updateLinkedRec);
				//print_r($updatedRecord);
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;					
				}
				else
				{
					return true;
				}
				// $sql = "UPDATE reso_ancillary_services set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";

				// $query = $this->db_sugar->query($sql);
				
				// if($query)
				// {
				// 	$sql2 = "UPDATE contacts_reso_ancillary_services_1_c set deleted=1, date_modified=now() WHERE contacts_reso_ancillary_services_1reso_ancillary_services_idb='$id' ";
				
				// 	$query2 = $this->db_sugar->query($sql2);
			}
			else
			{			
				return 0;
			}
		}

		/*########################################
			Ancillary Services Module Code End
		########################################*/

		/*########################################
			Monitoring Module Code Start
		########################################*/

		/*
			Get monitoring records for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function fetch_BO_monitoring($case_id)
		{ 
			//tap_service_c
			$this->db_sugar->select("mon_monitoring_cstm.id_c as mntr_id ,tap_service_status_c,tap_service_started_c, tap_service_c, mon_monitoring_cstm.user_id1_c as practitioner_c,revenue_line_items_linking.rli_id");

			$this->db_sugar->from('mon_monitoring');
			
			$this->db_sugar->join('mon_monitoring_cstm', 'mon_monitoring_cstm.id_c=mon_monitoring.id', 'left');

			$this->db_sugar->join('contacts_mon_monitoring_1_c', 'contacts_mon_monitoring_1_c.contacts_mon_monitoring_1mon_monitoring_idb=mon_monitoring.id ', 'left');	
			
			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_mon_monitoring_1_c.contacts_mon_monitoring_1contacts_ida');
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
			$this->db_sugar->join('users', 'users.id = mon_monitoring_cstm.user_id1_c', 'left');
			$this->db_sugar->join('revenue_line_items_linking', 'revenue_line_items_linking.module_id = mon_monitoring.id  AND revenue_line_items_linking.deleted=0', 'left');

			$this->db_sugar->where('mon_monitoring.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			$this->db_sugar->where('contacts_mon_monitoring_1_c.deleted', 0);	
			//$this->db_sugar->where('revenue_line_items_linking.deleted', 0);

			$this->db_sugar->where('contacts_cstm.case_id_c', $case_id);

			$data = $this->db_sugar->order_by('mon_monitoring.date_entered','DESC')->get();
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

		/*
			Create monitoring record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function createMonitoring($caseId, $tap_service_status_c,  $tap_service_started_c, $tapPractitoner, $rliId) //$tap_service_c
		{
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();

			if($contactId)
			{
				$record = array(
					'modified_user_id' => $uid,
					'tap_service_status_c' => $tap_service_status_c,
					//'tap_service_c' => $tap_service_c,
					'tap_service_started_c' => date('Y-m-d', strtotime($tap_service_started_c)),
					'contacts_mon_monitoring_1contacts_ida'=> $contactId,
					'user_id1_c'=> $tapPractitoner,
					// 'monitoring_work_start_date_c' => date('c')
				);

				$url = "/mon_Monitoring";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar($url, $record);
				$moduleId = $createdRecord->id;
				if(!empty($moduleId))
				{
					$moduleName = 'Tax Assurance Program';
					$this->createLinkRecordTask($rliId, $moduleId, $moduleName);
				}
				if(property_exists($createdRecord, 'error'))  //if($createdRecord->error)
				{
					return $createdRecord->error_message;
				}				
				else
				{
					return true; 
				}
			}
			else
			{
				$msg = "Contact id not found";
				return $msg;
			}
		}	
		
		/*
			Update monitoring record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function updateMonitoring($mntr_id, $tap_service_status_c,  $tap_service_started_c, $tapPractitoner) //$tap_service_c
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			
			if($mntr_id)
			{
				$record = array(
					'modified_user_id' => $uid,
					'tap_service_status_c' => $tap_service_status_c,
					//'tap_service_c' => $tap_service_c,
					'tap_service_started_c' => date('Y-m-d', strtotime($tap_service_started_c)),
					'user_id1_c' => $tapPractitoner
				);

				$url = "/mon_Monitoring/" . $mntr_id;
				
				// Call global function for store and update data by paramenters url and record
				$updatedRecord = $this->storeDataInSugar($url, $record,$mntr_id);
				
				if(property_exists($updatedRecord, 'error'))  //if($createdRecord->error)
				{
					return $updatedRecord->error_message;
				}				
				else
				{
					return true; 
				}
			}
			else
			{
				$msg = "Monitoring record id not found";
				return $msg;
			}
		}

		/*
			Delete monitoring record for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function deleteMonitoring($caseid,$id)
		{		
			if($caseid != '' && $id != '')
			{
				$user_id = $this->session->userdata('User_id');
				session_write_close();
				$record = array(

					'modified_user_id' => $user_id,
					'deleted' => 1
				);

				$url = "/mon_Monitoring/" . $id;
					
				// Call global function for deletion data by paramenters url and record
				$updatedRecord = $this->deleteDataInSugar($url, $record, $id);
				//deleting from linking table
				$updateLinkedRec = "UPDATE revenue_line_items_linking set deleted=1, date_modified=now() WHERE NOT EXISTS (SELECT id FROM mon_monitoring WHERE deleted = '0' AND id = '".$id."') AND revenue_line_items_linking.module_id='".$id."'";
				
				$exeUpdateLinkedRec = $this->db_sugar->query($updateLinkedRec);
				//print_r($updatedRecord);
				if(property_exists($updatedRecord, 'error')) 
				{
					return $updatedRecord->error_message;					
				}
				else
				{
					return true;
				}
				
				// $sql = "UPDATE mon_monitoring set deleted=1, modified_user_id='$user_id', date_modified=now() WHERE id='$id' ";	

				// $query = $this->db_sugar->query($sql);
				
				// if($query)
				// {
				// 	$sql2 = "UPDATE contacts_mon_monitoring_1_c set deleted=1, date_modified=now() WHERE contacts_mon_monitoring_1mon_monitoring_idb='$id' ";	
				
				// 	$query2 = $this->db_sugar->query($sql2);
									
			}	
			else
			{
				return 0;
			}		
			
		}
		
		/*
			Below function is not in use.TBD. 
			Parameters. 
			Author- 
		*/
		/*public function  getDropdowns()
		{

			if($caseid != '' && $uid != '')
			{
				$this->db_sugar->select('contacts_mon_monitoring_1contacts_ida');
				$this->db_sugar->from('contacts_mon_monitoring_1_c');
				$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
				$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_mon_monitoring_1_c.contacts_mon_monitoring_1contacts_ida', 'left');
				$this->db_sugar->where('contacts_cstm.case_id_c', $caseid);			
				$this->db_sugar->where('contacts.deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();
				// Below variable added by Tara.Because if record_sugar count zero then error come variable not found. 19-Nov-2020
				$ida = '';
				if(count($record_sugar) > 0)
				{				
					$ida = $record_sugar[0]->contacts_mon_monitoring_1contacts_ida;				
				}
				
				if($ida != ''){
				
				$record = array(
					'modified_user_id' => $uid,
					'tap_service_status_c' => $tap_service_status_c,
					'tap_service_c' => $tap_service_c,
					'contacts_mon_monitoring_1contacts_ida'=> $ida
				);

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
				// Get Auth Token from sugar instance by user name and password
				$oauth_token = $this->getAuthToken($user_name,$user_pwd);
				
				$url = "/mon_Monitoring/";
				
				// Call global function for store and update data by paramenters url and record
				$record = $this->storeDataInSugar($url, $record);
				
				if($record->id != '')
				{
					return true;
				}
				
				else
				{
					return false; 
				}

				// $instance_url = $this->sugar_url . "rest/v11";  
				
				// $url = $instance_url . "/mon_Monitoring";

				// $curl_request = curl_init($url);
				// curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
				// curl_setopt($curl_request, CURLOPT_HEADER, false);
				// curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
				// curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
				// curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
				// 	"Content-Type: application/json",
				// 	"oauth-token: {$oauth_token}"
				// ));

				// $json_arguments = json_encode($record);
				// curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
				
				// $curl_response = curl_exec($curl_request);
				// file_put_contents('service_res_json1june.txt',$curl_response); 
			
				// $createdRecord = json_decode($curl_response);
				// curl_close($curl_request);	
				
			}
			else
			{
				return 0; 
			} 

		} */

		/*########################################
				Monitoring Module Code End
		########################################*/

		/*########################################
			POA Generation Module Code Start
		########################################*/

		/*
		Function for get all poa generation records from sugar 
		Parameters. 
		Author- Tara (24-Nov-2020)
		*/
		public function fetchBoPoaGeneration($caseId)
		{
			$this->db_sugar->select("poa_poa.id as poa_id,poa_poa_cstm.id_c as poa_cstm_id,form_type,tax_payer,status,poa_source_c"); 

			$this->db_sugar->from('poa_poa');
			
			$this->db_sugar->join('poa_poa_cstm', 'poa_poa_cstm.id_c=poa_poa.id', 'left');	
			$this->db_sugar->join('contacts_poa_poa_1_c', 'contacts_poa_poa_1_c.contacts_poa_poa_1poa_poa_idb = poa_poa.id', 'left');		

			$this->db_sugar->join('contacts_cstm', 'contacts_cstm.id_c = contacts_poa_poa_1_c.contacts_poa_poa_1contacts_ida', 'left');
			$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');
			// $this->db_sugar->join('users', 'poa_poa_cstm.user_id_c = users.id', 'left');

			$this->db_sugar->where('contacts_cstm.case_id_c', $caseId);
			$this->db_sugar->where('poa_poa.deleted', 0);	
			$this->db_sugar->where('contacts.deleted', 0);	
			$this->db_sugar->where('contacts_poa_poa_1_c.deleted', 0);	

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
		/*########################################
			POA Generation Module Code End
		########################################*/

		//Complete Back office creating record in task module
		public function completeBackOfficeTask($caseId, $attIds)
		{
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$z_case = 'baa6810e-794b-11e9-9acb-0a1db36945e2';
			$randomString = $this->generateRandomString();
			//return $caseId;
			if($caseId)
			{
				//contact id
				$this->db_sugar->select('id_c');
				$this->db_sugar->from('contacts_cstm');	
				$this->db_sugar->join('contacts', 'contacts.id = contacts_cstm.id_c', 'left');				
				$this->db_sugar->where('contacts_cstm.case_id_c', $caseId);	
				$this->db_sugar->where('contacts.deleted', 0);			
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();

				if(count($record_sugar) > 0)
				{				
					$contact_id = $record_sugar[0]->id_c;
				}

				//aggregate_id
				$this->db_sugar->select('aggre_aggregatevalues.id as aggid');
				$this->db_sugar->from('aggre_aggregatevalues');	
				$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb = aggre_aggregatevalues.id', 'left');				
				$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida', 'left');				
				$this->db_sugar->join('leads', 'leads.id = leads_cstm.id_c', 'left');
				$this->db_sugar->where('aggre_aggregatevalues.deleted', 0);			
				$this->db_sugar->where('leads.deleted', 0);	
				$this->db_sugar->where('leads_cstm.case_id_c', $caseId);		
				
				$data_sugar = $this->db_sugar->get();
				$record_sugar = $data_sugar->result();

				if(count($record_sugar) > 0)
				{				
					$agg_id = $record_sugar[0]->aggid;
				}
				$name = $caseId . "_BO";

				// Task creation stopped on 23-06-2022
				/*$record = array(

					'created_by' => $uid,
					'modified_user_id' => $uid,
					'name' => $name,
					'contact_id' => $contact_id,
					'parent_id' => $contact_id,
					'category_c' => 'POA',
					'action_type_c'=> 'P2 Amend',
					'assigned_user_id' => $z_case,
					'parent_type' => 'Contacts',
				);
				//file_put_contents('task.txt',print_r($record,true));
				$url = "/Tasks";
				
				// Call global function for store and update data by paramenters url and record
				$createdRecord = $this->storeDataInSugar($url, $record);*/
				//print_r($createdRecord);

				//if($createdRecord->id != '')
				//{
					if($agg_id != '')
					{
						$record_agg = array(

							'activate_for_assignment_c' => "Completed",
							'modified_user_id' => $uid
						);
						//file_put_contents('task.txt',print_r($record,true));
						$url = "/Aggre_AggregateValues/" . $agg_id;
						
						// Call global function for store and update data by paramenters url and record
						$updatedRecord_agg = $this->storeDataInSugar($url, $record_agg, $agg_id);						
					}
					
					
					if($attIds != '' && $attIds != null)
					{
						//print $attIds;
						$createdRecordPOA = $this->sendPOA($caseId, $attIds);
					}
					if(property_exists($updatedRecord_agg, 'error'))  //if($updatedRecord->error)
					{
						return $updatedRecord_agg->error_message;
					}
					else
					{
						return true;
					}
				/*}
				else
				{
					if(property_exists($createdRecord, 'error'))  //if($createdRecord->error)
					{
						return $createdRecord->error_message;
					}
				}*/
			}
			else
			{
				return false;
			}

		}
		
		/*
			Assign Users to multiple record of Tax Preparation for backoffice detail view page 
			Parameters. All form input and tax_prep_id
			Author- 
		*/
		
		public function assignTaxPrepration($caseId,$BO_TP_array,$BO_tax_preparer,$BO_TP_advocate)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();
			$tax_prep_id='';
			$record = array();
			$error_msg = '';

			if($uid !='')
			{			  
			  foreach($BO_TP_array as $key=>$val)
				{
					$tax_prep_id = $val['id'];

					if($tax_prep_id == "new")
					{
						
						$record = array(
							'modified_user_id' => $uid,
							'user_id3_c' => $BO_TP_advocate,
							'user_id_c' => $BO_tax_preparer,
							'name' => $randomString,
							'contacts_reso_tax_preparation_1contacts_ida'=> $contactId,
							'tax_prep_work_started_c'=> date('Y-m-d'),
							'service_status_c' => $val['status'], //"Intro Call",
							'return_type_c' => $val['ret_type'], //"W2 1040",
							'source_created_c' => "CaseReview",
							'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
							);

						if($val['year'] > 0)
						{
							$record['tax_year_c'] = $val['year'];
						}
						$url = "/reso_Tax_Preparation/";
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record);
						file_put_contents('err1BM.txt',print_r($updatedRecord,true) . PHP_EOL, FILE_APPEND);
						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							$this->createLinkRecordTask($val['rliId'], $updatedRecord->id, 'Tax Preparation');
							file_put_contents('TPCreate2.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('TPCreate2.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('TPCreate2.txt','-----------------------------------------------------------',FILE_APPEND); 
							//return true;
						}						
					}
					else{
						
						$record = array(
							'modified_user_id' => $uid,
							'tax_prep_id' => $tax_prep_id,
							'service_status_c' => $val['status'], //"Intro Call",
							'return_type_c' => $val['ret_type'], //"W2 1040",
							'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
						);
						if($BO_TP_advocate !="0")
						{
							$record['user_id3_c'] = $BO_TP_advocate;
						} 
						if($BO_tax_preparer !="0")
						{
							$record['user_id_c'] = $BO_tax_preparer;
						}
						if($val['year'] > 0)
						{
							$record['tax_year_c'] = $val['year'];
						}  
						
						$url = "/reso_Tax_Preparation/" . $tax_prep_id;
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record, $tax_prep_id);

						if(property_exists($updatedRecord, 'error')) //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							file_put_contents('TPUpdate2.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('TPUpdate2.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('TPUpdate2.txt','-----------------------------------------------------------',FILE_APPEND); 
							//return true;
						}
					}
				}
				if($error_msg != '')
				{
					return $error_msg;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		
		/*
			Assign Users to multiple record of resolution for backoffice detail view page 
			Parameters. All form input and resolutionId
			Author- 
		*/
		public function assignResolution($caseId,$BO_R_array,$RS_practitionar ,$BO_TP_advocate)
		{
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$randomString = $this->generateRandomString();
			$error_msg = '';

			if($uid !='')
			{
				foreach($BO_R_array as $key=>$val)
				{
				    //$resoId = $val;
					$resoId = $val['id'];
					
					if($resoId == "new")
					{
						$record = array(
							'modified_user_id' => $uid,
							'name' => $randomString,
							'user_id_c' => $RS_practitionar,
							'user_id1_c' => $BO_TP_advocate,
							'contacts_reso_resolutions_1contacts_ida'=> $contactId,
							'resolution_work_start_date_c'=> date('y-m-d'),
							'service_status_c' => $val['status'], 
							'resolution_type_c' => $val['reso_type'], 
							'collection_division_c' => $val['col_div'], 
							'state_c' => $val['state'], 
							'related_business_c' => $val['biz_id'], 
							'source_created_c' => "CaseReview",
							'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
						);

						$url = "/reso_Resolutions/";
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record);
						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							$this->createLinkRecordTask($val['rliId'], $updatedRecord->id, 'Resolution');

							file_put_contents('ResoCreate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('ResoCreate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('ResoCreate.txt','-----------------------------------------------------------',FILE_APPEND); 	
							//return true;
						}				
					}
					else
					{
						
						$record = array(
							'modified_user_id' => $uid,
							'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
							'service_status_c' => $val['status'],
							);
						if($RS_practitionar !="0")
						{
							$record['user_id_c'] = $RS_practitionar;
						}
						if($BO_TP_advocate !="0")
						{
							$record['user_id1_c'] = $BO_TP_advocate;
						}  
						if($val['reso_type'] != "" && $val['reso_type'] != "0")
						{
							$record['resolution_type_c'] = $val['reso_type'];
						}  
						if($val['col_div'] != "0" && $val['col_div'] != "")
						{
							$record['collection_division_c'] = $val['col_div'];
						}  
						if($val['state'] != "0" && $val['state'] != "")
						{
							$record['state_c'] = $val['state'];
						}  
						if($val['biz_id'] != "0" && $val['biz_id'] != "")
						{
							$record['related_business_c'] = $val['biz_id'];
						}  
						
						$url = "/reso_Resolutions/" . $resoId;
						
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record, $resoId);

						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							file_put_contents('ResoUpdate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('ResoUpdate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('ResoUpdate.txt','-----------------------------------------------------------',FILE_APPEND); 
							//return true;
						}
					}
				}
				if($error_msg != '')
				{
					//print_r($error_msg);
					//die();
					echo $error_msg;
					return;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		
		
		/*
			Assign user to multiple records of Ancillary Services for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		public function assignResolutionService($caseId,$BO_MRS_array,$RS_practitionar ,$BO_TP_advocate)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$error_msg = '';

			if($uid !='')
			{
				foreach($BO_MRS_array as $key=>$val)
				{
					//$ancl_srvc_id = $val;
					$ancl_srvc_id = $val['id'];

					if($ancl_srvc_id == "new")
					{
						$record = array(
						'modified_user_id' => $uid,
						'user_id_c' => $RS_practitionar,
						'user_id1_c' => $BO_TP_advocate,
						'contacts_reso_ancillary_services_1contacts_ida'=> $contactId,
						'ancilliary_work_start_date_c'=> date('c'),
						'service_c' => $val['service'], 
						'service_status_c' => $val['status'], 
						'source_created_c' => "CaseReview",
						'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
						);

						
						$url = "/reso_Ancillary_Services/";
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record);

						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							$this->createLinkRecordTask($val['rliId'], $updatedRecord->id, 'Miscellaneous Resolutions Services');
							file_put_contents('AnclServCreate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('AnclServCreate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('AnclServCreate.txt','-----------------------------------------------------------',FILE_APPEND);
							//return true;
						} 
					}
					else
					{						
						$record = array(
						'modified_user_id' => $uid,
						'source_updated_c' => "CaseReview", //Added on 31-03-2023 https://trello.com/c/DFCSNC0t/1553-service-workflow-staging
					    );
                        if($RS_practitionar !="0")
						{
							$record['user_id_c'] = $RS_practitionar;
						}
						if($BO_TP_advocate !="0")
						{
							$record['user_id1_c'] = $BO_TP_advocate;
						}
						if($val['status'] != "0" && $val['status'] != "")
						{
							$record['service_status_c'] = $val['status'];
						}
						if($val['service'] != "0" && $val['service'] != "")
						{
							$record['service_c'] = $val['service'];
						}
						$url = "/reso_Ancillary_Services/" . $ancl_srvc_id;
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record,$ancl_srvc_id);

						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg =  $updatedRecord->error_message;
						}
						else
						{
							file_put_contents('AnclServUpdate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('AnclServUpdate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('AnclServUpdate.txt','-----------------------------------------------------------',FILE_APPEND); 
							//return true;
						}
					}
				}
				if($error_msg != '')
				{
					return $error_msg;
				}
				else
				{
					return true;
				}				
			}
			else
			{
				return false;
			}
		}
		
		/*
			Assign user to multiple records of Monitoring Services for backoffice detail view page 
			Parameters. 
			Author- 
		*/
		
		public function assignMonitorService($caseId,$BO_MO_array,$RS_practitionar)
		{	
			$error = 0;
			$uid = $this->session->userdata('User_id');
			session_write_close();
			$contactId = $this->getContactIdByCaseId($caseId);
			$error_msg = '';

			if($uid !='')
			{
				foreach($BO_MO_array as $key=>$val)
				{
					//$mntr_id = $val;
					$mntr_id = $val['id'];

					if($mntr_id == "new")
					{
						$record = array(
						'modified_user_id' => $uid,
						'contacts_mon_monitoring_1contacts_ida'=> $contactId,
					    'user_id1_c'=> $RS_practitionar,
						'tap_service_started_c' => date('Y-m-d', strtotime($val['service_date'])), 
						'tap_service_status_c' => $val['status'],
						);

						
						$url = "/mon_Monitoring/";
					
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record);
						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							$this->createLinkRecordTask($val['rliId'], $updatedRecord->id, 'Tax Assurance Program');

							file_put_contents('MonCreate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('MonCreate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('MonCreate.txt','-----------------------------------------------------------',FILE_APPEND); 
							//return true;
						}
					}
					else
					{						
						$record = array(
						'modified_user_id' => $uid,
					    );
                        if($RS_practitionar !="0")
						{
							$record['user_id1_c'] = $RS_practitionar;
						}
						if($val['status'] != "0" && $val['status'] != "")
						{
							$record['tap_service_status_c'] = $val['status'];
						}
						if($val['service_date'] != "")
						{
							$record['tap_service_started_c'] = date('Y-m-d', strtotime($val['service_date']));
						}

						$url = "/mon_Monitoring/" . $mntr_id;
				
						// Call global function for store and update data by paramenters url and record
						$updatedRecord = $this->storeDataInSugar($url, $record,$mntr_id);
						if(property_exists($updatedRecord, 'error'))  //if($updatedRecord->error)
						{
							$error_msg = $updatedRecord->error_message;
						}
						else
						{
							file_put_contents('MonUpdate.txt',$updatedRecord->id,FILE_APPEND);
							file_put_contents('MonUpdate.txt',print_r($record,true),FILE_APPEND);
							file_put_contents('MonUpdate.txt','-----------------------------------------------------------',FILE_APPEND); 
						}
					}
				}
				if($error_msg != '')
				{
					return $error_msg;
				}
				else
				{
					return true;
				}				
			}
			else
			{
				return false;
			}
		}
		

	/*
		funtion to get user from user id
		Parameters. 
		Author- 
	*/	    
	public function getUserById($id)
	{
		$uid = $id;
		
		if($uid != '')
		{
			$this->db_sugar->select("first_name,last_name");
			
			$this->db_sugar->from('users');
			
			//$this->db_sugar->where('users.deleted', 0);		
			$this->db_sugar->where('users.id', $uid); 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			$res_cnt_ida = $record[0]->first_name.' '.$record[0]->last_name;
			return $res_cnt_ida ; 
		}
	}

	public function getGroupUsers($id)
	{
		$uid = $id;
		//print $id;
		$sqlGetGroup="SELECT poa_group_c FROM users_cstm WHERE id_c = '".$uid."'";
		$result = $this->db_sugar->query($sqlGetGroup);
		$rset = $result->result();
		$group = $rset[0]->poa_group_c;

		if($group != '' && $group != null)
		{
			$sqlGetGroupMem = "SELECT id, first_name, last_name FROM users 
								INNER JOIN users_cstm ON id_c = id			
								WHERE (first_name IS NOT NULL OR first_name != '') 
								and (last_name IS NOT NULL OR last_name != '')
								AND poa_group_c LIKE '%" . $group . "%' ORDER BY group_priority_c ASC LIMIT 4";
						
			$resultGetMem = $this->db_sugar->query($sqlGetGroupMem);
			$rsetMem = $resultGetMem->result();

			for($i=0; $i < count($rsetMem) ; $i++)
			{
				$attArray['attIds'][]= $rsetMem[$i]->id;
				$attArray['attNames'][]=' '.ucfirst($rsetMem[$i]->first_name).' '.ucfirst($rsetMem[$i]->last_name);
			}

			if(is_array($attArray) && count($attArray) > 0)
			{
				return $attArray;
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

	public function sendPOA($caseId, $attIDs)
	{ 
		$attID1 = '';
		$attID2 = '';
		$attID3 = '';
		$attID4 = '';

		if($attIDs != '' && $caseId != '')
		{
			$att_array = explode(",",$attIDs);
			$num = count($att_array);

			if($num > 0)
			{
				$attID1 = ltrim($att_array[0]," ");
			}
			if($num > 1)
			{
				$attID2 = ltrim($att_array[1]," ");
			}
			if($num > 2)
			{
				$attID3 = ltrim($att_array[2]," ");
			}
			if($num > 2)
			{				
				$attID4 = ltrim($att_array[3]," ");
			}
			
			$ch=curl_init();
			$url="https://taxprep.ctaxalert.com/CaseReviewPOAGeneration?caseid=" . $caseId. "&attID1=" . $attID1."&attID2=" . $attID2. "&attID3=".$attID3."&attID4=". $attID4 ;

			curl_setopt($ch, CURLOPT_URL, $url);
			
			//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json"
			));
			
			$result = curl_exec($ch);
			$error = curl_error($ch);
			
			curl_close($ch);
		}
	}

	public function getLinkData($rliId, $caseId)
	{
		$getLinkRecord = "SELECT revenue_line_items_linking.id, revenue_line_items_linking.module_name 
							FROM revenue_line_items_linking	
							INNER JOIN revenue_line_items 
							ON revenue_line_items.id = revenue_line_items_linking.rli_id 
							INNER JOIN leads 
							ON leads.opportunity_id = revenue_line_items.opportunity_id 
							INNER JOIN leads_cstm 
							ON leads_cstm.id_c = leads.id 
							WHERE revenue_line_items_linking.deleted = '0' 
							AND revenue_line_items.deleted = '0' 
							AND leads.deleted = '0' 
							AND leads_cstm.case_id_c = '".$caseId."'";
		if(!empty($rliId))
		{
			$getLinkRecord .= "AND rli_id = '".$rliId."'";
		}

		$exegetLinkRecord = $this->db_sugar->query($getLinkRecord);
		$record = $exegetLinkRecord->result();
		if(count($record) > 0)
		{		
			
			if(!empty($rliId))
			{
				$resLinkData = $record[0]->module_name;
			}
			else
			{
				for($i=0; $i < count($record) ; $i++)
				{
					$resLinkData[]= $record[$i]->module_name;
				}
			}
			
			return json_encode($resLinkData);		
		}
		else
		{
			return false;
		}
		
	}
	public function createLinkRecordTask($rliId, $moduleId, $moduleName)
	{
		$date = date("Y-m-d h:i:s");
		$userId = $this->session->userdata('User_id');
		session_write_close();

		if(!empty($rliId) && !empty($moduleId) && !empty($moduleName))
		{
			$insertRecord = "INSERT INTO `revenue_line_items_linking`(`id`, `date_entered`, `date_modified`, `assigned_user_id`, `modified_user_id`, `created_by`, `deleted`, `rli_id`, `module_id`, `module_name`) VALUES (uuid(),'".$date."', '".$date."', '".$userId."', '".$userId."', '".$userId."', '0', '".$rliId."', '".$moduleId."', '".$moduleName."')";

			$exeinsertRecord = $this->db_sugar->query($insertRecord);
		}
		

	}

	public function getNotLinkedTaxInvestigation($caseId)
	{
		

		if(!empty($caseId))
		{
			/*$getRecords = "SELECT revenue_line_items.id FROM revenue_line_items INNER JOIN revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id INNER JOIN leads ON leads.opportunity_id = revenue_line_items.opportunity_id INNER JOIN leads_cstm ON leads_cstm.id_c =leads.id LEFT JOIN revenue_line_items_linking ON revenue_line_items_linking.rli_id= revenue_line_items.id WHERE revenue_line_items_linking.rli_id IS NULL AND revenue_line_items_cstm.product_catalog_type_c = 'Tax Investigation' AND leads.deleted = 0 aND revenue_line_items.deleted = 0  AND leads_cstm.case_id_c = '".$caseId."'";*/

			$getRecords = "SELECT revenue_line_items.id 
							FROM revenue_line_items 
							INNER JOIN revenue_line_items_cstm ON revenue_line_items_cstm.id_c = revenue_line_items.id 
							INNER JOIN leads ON leads.opportunity_id = revenue_line_items.opportunity_id 
							INNER JOIN leads_cstm ON leads_cstm.id_c =leads.id 
							LEFT JOIN revenue_line_items_linking ON revenue_line_items_linking.rli_id= revenue_line_items.id 
							WHERE revenue_line_items_linking.rli_id IS NULL 
							AND leads.deleted = 0 
							AND revenue_line_items.deleted = 0
							AND (date(revenue_line_items_cstm.dateapproved_c) <= '2022-01-01' OR revenue_line_items_cstm.product_catalog_type_c = 'Tax Investigation')  
							AND leads_cstm.case_id_c = '".$caseId."'";

			$record1 = $this->db_sugar->query($getRecords);
			$record = $record1->result();
			
			if(is_array($record) && count($record) > 0)
			{
				return $record;
			}
			return $record;
		}
		

	}

	//added on 20-06-2022 for new role  https://trello.com/c/X37JleuM/1533-we-need-to-carve-out-a-new-sugar-role-for-robert-rodriguez
	public function checkPOACoordinator()
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
			
			$where = '(acl_roles.name = "POA Coordinator")';			
			$this->db_sugar->where($where);	 
			//'1fb2e5b0-794c-11e9-9525-0a1db36945e2'
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			// print  $this->db_sugar->last_query();
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

	public function getBusiness($caseId)
	{
		if(!empty($caseId))
		{
			$getBusiness = "SELECT epi_business_cstm.business_address_street_c, separate_business_address_c, prior_year_bookkeeping_c, prior_year_tax_return_c, other_one_time_service_c, pricing_details_c, epi_business.id as eid, name, trade_name_c, business_apt_no_c, address_c, epi_business_cstm.business_address_city_c, epi_business_cstm.business_address_state_c, epi_business_cstm.business_address_postalcode_c, phone_number_c, round(onboarding_fee_c,2) as onboarding_fee_c, round(monthly_recurring_fee_c,2) as monthly_recurring_fee_c,  monthly_bookkeeping_service_c, invoice_matching_service_c, salesanduse_filingservice_c, payroll_service_c, payroll_timetracking_c, payrollcheck_delivery_c, entity_mgmt_service_c, payable_receivable_service_c, irs_defense_mgmt_c, state_defense_mgmt_c, new_entity_formation_c, catchup_bookkeeping_c, round(startup_costs_c,2) as startup_costs_c, round(total_monthly_cost_c,2) as     total_monthly_cost_c, busi_business_owner_c, annual_corp_tax_prep_serv_c, personal_tax_prep_service_c, entity_type_c, ein_c, accounting_software_qbo_c
            FROM `epi_business`  
            LEFT JOIN epi_business_cstm  ON epi_business.id=epi_business_cstm.id_c 
            LEFT JOIN `epi_business_busin_bizservicesinterview_1_c` ON epi_business_busin_bizservicesinterview_1_c.epi_business_busin_bizservicesinterview_1epi_business_ida = epi_business_cstm.id_c           
            LEFT JOIN `busin_bizservicesinterview_cstm` ON busin_bizservicesinterview_cstm.id_c = epi_business_busin_bizservicesinterview_1_c.epi_busine34c6terview_idb 
            LEFT JOIN `contacts_epi_business_1_c` ON contacts_epi_business_1_c.contacts_epi_business_1epi_business_idb = epi_business_cstm.id_c 
            LEFT JOIN  `contacts_cstm` ON  contacts_epi_business_1_c.contacts_epi_business_1contacts_ida=contacts_cstm.id_c
            LEFT JOIN  `contacts` ON  contacts.id=contacts_cstm.id_c    
            WHERE epi_business.deleted=0 
            AND contacts_epi_business_1_c.deleted=0 
            AND contacts.deleted=0 
            AND contacts_cstm.case_id_c='".$caseId."'";
			
			$record1 = $this->db_sugar->query($getBusiness);
			$record = $record1->result();
			
            return $record ; 
		}
	}

	 // Added on 12-02-2024 https://trello.com/c/SVOwgbtT/934-boat-tax-team-not-being-assigned
	public function Update_Current_BO_User($caseId, $BO_tax_preparer, $BO_practitionar, $BO_TP_advocate, $TP_TaxReviewer, $PA_Escalation)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		session_write_close();
		$contactId = $this->getContactIdByCaseId($caseId);
		//print $BO_tax_preparer .', '.$BO_practitionar .', '. $BO_TP_advocate .', '. $TP_TaxReviewer;
		// die();
		if ($uid != '' && $contactId != '') 
		{
			$record = array(
				'modified_user_id' => $uid,
			);
			//print_r($record);
			if(!empty($BO_TP_advocate) && $BO_TP_advocate != '0')
			{
				$record['user_id1_c'] = $BO_TP_advocate;
			}
			if(!empty($BO_tax_preparer)  && $BO_tax_preparer != '0')
			{
				$record['user_id3_c'] = $BO_tax_preparer;
			}
			if(!empty($BO_practitionar)  && $BO_practitionar != '0')
			{
				$record['user_id2_c'] = $BO_practitionar;
			}
			if(!empty($TP_TaxReviewer)  && $TP_TaxReviewer != '0')
			{
				$record['user_id4_c'] = $TP_TaxReviewer;
			}
			if(!empty($PA_Escalation)  && $PA_Escalation != '0')
			{
				$record['user_id7_c'] = $PA_Escalation;
			}
			// $record = array(
			// 	'modified_user_id' => $uid,
			// 	'user_id1_c' => $BO_TP_advocate,
			// 	'user_id3_c' => $BO_tax_preparer,
			// 	'user_id2_c' => $BO_practitionar,
			// 	'user_id4_c' => $TP_TaxReviewer,
			// );

			$url = "/Contacts/" . $contactId;
				//print $url;
				//print_r($record);
				//die();
			// Call global function for store and update data by paramenters url and record
			$updatedRecord = $this->storeDataInSugar($url, $record, $contactId);
//print_r($updatedRecord);
			if (property_exists($updatedRecord, 'error'))  //if($createdRecord->error)
			{
				return $updatedRecord->error_message;
			} else {
				return true;
			}
		} else {
			$msg = "Contacts record id not found";
			return $msg;
		}
	} // end added on 12-02-2024
}