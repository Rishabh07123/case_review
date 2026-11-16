<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CaseAssignmentModel extends CI_Model
{

	//Fetch cases for case assignment tab
	public function fetch_case_assignments($language, $payment_status, $user_ids, $team_id, $final_recom, $cr_batch_id, $p3_batch_id, $sub_status, $no_payments_made = null) //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
	{
		$this->db_sugar->distinct();
		$this->db_sugar->select("  leads.id,leads_cstm.p3_batch_id_c, concat(leads.first_name,' ', leads.last_name) as name, leads.assigned_user_id, leads_cstm.lead_level_c, leads_cstm.case_id_c, leads_cstm.language_prequal_c, leads_cstm.cr_fee_c, leads_cstm.lending_partner_eligibility_c, leads_cstm.financial_servicing_eligibil_c, concat(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')) as username, cases_cstm.investigation_type_c,  leads_cstm.final_recommendation_type_c, leads_cstm.cr_batch_id_c, leads_cstm.custom_status_c, DATE_FORMAT(cases.date_entered,'%m-%d-%Y')  as sale_date, leads_cstm.add_service_source_c,
		(SELECT round(SUM((list_price - ifnull(discount_amount, 0) ) * quantity),2)
		from revenue_line_items
		left join leads ON leads.opportunity_id=revenue_line_items.opportunity_id
		left join revenue_line_items_cstm rc ON rc.id_c=revenue_line_items.id
		left join leads_cstm lc ON lc.id_c=leads.id
		where rc.sale_type_c='Initial Service' AND lc.case_id_c = leads_cstm.case_id_c AND revenue_line_items.deleted=0) AS initial_fee , derived_table.*,`derived_table2`.* ,

		(SELECT IF(lead_src IS NULL, '', 'Bobby')
		FROM leadm_leadmarketing
		LEFT JOIN leads_leadm_leadmarketing_1_c
		ON leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb = leadm_leadmarketing.id
		LEFT JOIN leadm_leadmarketing_cstm ON leadm_leadmarketing_cstm.id_c = leadm_leadmarketing.id
		WHERE lead_src = 'emersive-marketing'
		AND  leadm_leadmarketing.deleted=0
		AND leads_leadm_leadmarketing_1_c.deleted=0

		AND leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida =  leads.id
		AND leadm_leadmarketing_cstm.attributable_c = 1 LIMIT 0,1) as spl_campaign,

		(SELECT  DATE_FORMAT(bill_bill_payment_cstm.payment_date_c,'%m-%d-%Y')
        FROM bill_bill_payment_cstm
        INNER JOIN bill_bill_payment ON bill_bill_payment.id = bill_bill_payment_cstm.id_c
        INNER JOIN opportunities_bill_bill_payment_1_c ON opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1bill_bill_payment_idb=bill_bill_payment_cstm.id_c
        WHERE opportunities_bill_bill_payment_1_c.deleted=0
        AND bill_bill_payment.deleted=0 AND bill_bill_payment_cstm.bill_status_c = 'queued'
        AND opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1opportunities_ida=leads.opportunity_id
        AND bill_bill_payment_cstm.payment_date_c > CURDATE() ORDER BY bill_bill_payment_cstm.payment_date_c ASC LIMIT 0,1) as payment_date,(
			SELECT
				concat(COALESCE(users.first_name),' ',COALESCE(users.last_name,''))
			FROM
				users
			INNER JOIN
				leads_audit ON leads_audit.before_value_string = users.id
			WHERE
				field_name = 'assigned_user_id' AND leads_audit.parent_id = leads.id
			ORDER BY
				leads_audit.date_created
			DESC
		LIMIT 1
		) AS previousUser, add_service_fee_c
				");  // ,  derived_table_m.* //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services  // Updated on 23-11-2023 https://trello.com/c/Jwka3Weq/713-release-11-23-add-service-source-cr-portal-lead-record // Updated on 08-01-2024 https://trello.com/c/Y6hRbdS4/822-case-review-assignment-portal-add-service-improvements

		$this->db_sugar->from('leads');

		$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c = leads.id', 'left');

		$this->db_sugar->join('cases_cstm', 'cases_cstm.case_id_c=leads_cstm.case_id_c', 'left');

		$this->db_sugar->join('cases', 'cases.id=cases_cstm.id_c', 'left');

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
		/*
			$this->db_sugar->join('(SELECT lead_src  , l.id as mid
			FROM leadm_leadmarketing
			LEFT JOIN leads_leadm_leadmarketing_1_c ON leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leadm_leadmarketing_idb = leadm_leadmarketing.id
			LEFT JOIN leadm_leadmarketing_cstm ON leadm_leadmarketing_cstm.id_c = leadm_leadmarketing.id
			LEFT JOIN leads as l on l.id = leads_leadm_leadmarketing_1_c.leads_leadm_leadmarketing_1leads_ida
			WHERE lead_src="Emersive Marketing" AND leadm_leadmarketing.deleted=0 AND leads_leadm_leadmarketing_1_c.deleted=0 AND l.deleted=0 AND leadm_leadmarketing_cstm.attributable_c = 1) as derived_table_m ', 'derived_table_m.mid=leads.id', 'left');

			  AND ag_cstm.logics_status_c <> "CCS Review"
AND ag_cstm.logics_status_c <> "Refunded" AND ag_cstm.logics_status_c <> "Refund Requested"
			*/

		$this->db_sugar->join('(SELECT l1.id as derived_id2,   ag_cstm.user_id7_c, concat(u.first_name, " ", u.last_name) as temp_user, billing_status_c, logics_status_c, round(percentage_paid_c,2) as percentage_paid_c
 FROM aggre_aggregatevalues_cstm  as ag_cstm
 INNER JOIN aggre_aggregatevalues ag ON ag.id=ag_cstm.id_c
 INNER JOIN leads_aggre_aggregatevalues_1_c as lg on lg.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb=ag_cstm.id_c
 INNER JOIN leads l1 ON l1.id=lg.leads_aggre_aggregatevalues_1leads_ida
 LEFT JOIN users as u ON u.id = ag_cstm.user_id7_c
 WHERE ag.deleted=0
 AND lg.deleted=0
  ) as derived_table2', 'derived_table2.derived_id2=leads.id', 'left');


		//$this->db_sugar->where('leads_cstm.custom_status_c', 'Assignment Ready');
		$this->db_sugar->where('leads.status', 'Pending');
		$this->db_sugar->where('leads.deleted', 0);
		$this->db_sugar->where('cases.deleted', 0);

		//$language,$payment_status
		if ($language != '' && $language != null) {
			$this->db_sugar->where('leads_cstm.language_prequal_c', $language);
		}

		if ($team_id != '' && $team_id != null) {
			$this->db_sugar->where('leads.team_id', $team_id);
		}
		if (is_array($user_ids) && count($user_ids) > 0) {
			$uid_list = '';
			foreach ($user_ids as $uid) {
				$uid_list .= "'" . $uid . "',";
			}
			$uid_list = rtrim($uid_list, ',');
			$where = 'leads.assigned_user_id IN (' . $uid_list . ')';
			$this->db_sugar->where($where);
		}
		if ($final_recom != '' && $final_recom != null) {
			$this->db_sugar->where('leads_cstm.final_recommendation_type_c', $final_recom);
		}
		if ($cr_batch_id != '' && $cr_batch_id != null) {
			$this->db_sugar->where('leads_cstm.cr_batch_id_c', $cr_batch_id);
		}
		if ($p3_batch_id != '' && $p3_batch_id != null) {
			$this->db_sugar->where('leads_cstm.p3_batch_id_c', $p3_batch_id);
		} // If Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
		if ($sub_status != '' && $sub_status != null) {
			$this->db_sugar->where('leads_cstm.custom_status_c', $sub_status);
		} else {
			$where_status = "(leads_cstm.custom_status_c = 'Assignment Ready' || leads_cstm.custom_status_c = 'P3 Assignment Ready')";
			$this->db_sugar->where($where_status);
		}
		/*echo 'no_payments_made - ' . $no_payments_made;
		if($no_payments_made != '' && $no_payments_made != null)
		{
			$this->db_sugar->where(0 >= ('SELECT COUNT(opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1opportunities_ida)
			FROM bill_bill_payment_cstm
			INNER JOIN bill_bill_payment ON bill_bill_payment.id = bill_bill_payment_cstm.id_c
			INNER JOIN opportunities_bill_bill_payment_1_c ON opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1bill_bill_payment_idb=bill_bill_payment_cstm.id_c
			WHERE opportunities_bill_bill_payment_1_c.deleted=0
			AND bill_bill_payment.deleted=0
			AND opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1opportunities_ida = leads.opportunity_id'));
			if($no_payments_made == 'all_above')
			{
				$this->db_sugar->where_in('leads_cstm.lending_partner_eligibility_c', ['Loan', 'Soft Approval', 'Pre Approved']);
			}
			else
			{
				$this->db_sugar->where('leads_cstm.lending_partner_eligibility_c', $no_payments_made);
			}
		}*/
		if ($payment_status != '' && $payment_status != null) {
			if ($payment_status == 'No Payments Made') {
				$this->db_sugar->where('leads.opportunity_id NOT IN ( SELECT opportunities_bill_bill_payment_1_c.opportunities_bill_bill_payment_1opportunities_ida FROM opportunities_bill_bill_payment_1_c WHERE opportunities_bill_bill_payment_1_c.deleted = 0)');
			} else {
				$this->db_sugar->where('billing_status_c', $payment_status);
			}
		}
		$data = $this->db_sugar->get();
		$record = $data->result();
		//print $this->db_sugar->last_query();
		//print_r($record);
		if (is_array($record) && count($record) > 0) {
			return $record;
		} else {
			return 0;
		}
	}

	public function assignTempUser($assign_user_id,  $lead_array)
	{
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');

		//$instance_url = $this->sugar_url . "rest/v11";  case_temp_user
		//print 	$instance_url;
		if ($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($lead_array) > 0) {
			$lead_list =  '';
			foreach ($lead_array as $key => $val) {
				$lead_list .=  "'" . $val . "',";
			}
			$lead_list = rtrim($lead_list, ',');
			//print $lead_list;

			// Get all aggregate record ids
			$sql = "SELECT leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb as agg_id
					FROM leads_aggre_aggregatevalues_1_c
					WHERE leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida IN ($lead_list) AND leads_aggre_aggregatevalues_1_c.deleted=0";
			//print $sql;
			$query_sugar = $this->db_sugar->query($sql);

			$record = $query_sugar->result_array();
			//print_r($record);

			$aggregate_array = array();
			$agg_id_list = ''; // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
			if (is_array($record) && count($record) > 0) {
				for ($i = 0; $i < count($record); $i++) {
					$agg_id_list .=  "'" . $record[$i]['agg_id'] . "',";  // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
					$aggregate_array[] = $record[$i]['agg_id'];
				}
				$agg_id_list = rtrim($agg_id_list, ',');  // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
				$return_resp = 0;  // Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
				//Mass Update Bean
				$uid = $this->session->userdata('User_id');
				$user_name = $this->session->userdata('uname');
				$user_pwd = $this->session->userdata('pwd');
				session_write_close();

				$instance_url =  $this->sugar_url . "rest/v11"; //
				//print 	$instance_url . ', ' . $user_name . ', ' . $user_pwd;
				//return 0;
				if ($user_name != '' && $user_pwd != ''  && $uid != '' && $assign_user_id != '' && count($aggregate_array) > 0) 
				{
					// Added on 13-08-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14

					$agg_sql = "UPDATE aggre_aggregatevalues_cstm INNER JOIN aggre_aggregatevalues ON id = id_c SET user_id7_c = '" . $assign_user_id . "', modified_user_id = '" . $uid . "' WHERE id_c IN (" . $agg_id_list . ");";
					file_put_contents('aggTempUser1.txt',$agg_sql . PHP_EOL . PHP_EOL, FILE_APPEND);

					$query_sugar1 = $this->db_sugar->query($agg_sql);	

					if($query_sugar1)
					{
						$return_resp = 1;
						file_put_contents('aggTempUserSuccess.txt',$agg_sql . PHP_EOL . PHP_EOL, FILE_APPEND);
					}
					else
					{
						file_put_contents('aggTempUserError.txt',$agg_sql . PHP_EOL . PHP_EOL, FILE_APPEND);
					} // end Added on 13-08-2024
					
					//if ($updatedRecord->status == 'done') 
					{
						for ($i = 0; $i < count($aggregate_array); $i++) {
							$sql = "INSERT INTO aggre_aggregatevalues_audit VALUES (uuid(), '" . $aggregate_array[$i] . "', uuid(), now(), 1, NULL, 'case_temp_user_c', 'id', NULL, '" . $assign_user_id . "', NULL, NULL)";
							//print $sql;

							$query_sugar = $this->db_sugar->query($sql);
						}
					}
					return $return_resp ; //$curl_response;
					
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

	public function update_lead_assigned_to($lead_json, $batch_id, $batch_flag) //$assign_user_id, //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services
	{ //print $lead_json;
		$error = 0;
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();

		$instance_url =  $this->sugar_url . "rest/v11"; //
		//print 	$instance_url . ', ' . $user_name . ', ' . $user_pwd;	 //&& $assign_user_id != ''
		$lead_array = array();
		$lead_array = explode(';',$lead_json);

		// added on 30-09-2024 https://trello.com/c/1TOrhCzt/1599-hot-fix-cr-portal-assignments-not-working-after-sugar-14
		if(is_array($lead_array) && count($lead_array) > 0)
		{
			$result_array1 = array_filter($lead_array); 
			$newArray = array();

			foreach($result_array1 as $row1)
			{
				$lead_row_array1 = json_decode($row1);
				// print_r($lead_row_array);
				if(count((array)$lead_row_array1) > 0) 
				{
					$newArray[$lead_row_array1->id] = $lead_row_array1->user;
				}
				//asort($newArray);
			}
			$resultNewArray = array();
    
			foreach ($newArray as $key=>$value) {
				if (!array_key_exists($value, $resultNewArray)) {
					$resultNewArray[$value] = [];
				}
				$resultNewArray[$value][] = $key; //$value;
			}
			
			
			file_put_contents('LA2.txt',print_r($newArray,true));
			file_put_contents('LA3.txt',print_r($resultNewArray,true));
		} // end added on 30-09-2024
		//$lead_array = json_decode($lead_json);
		//print_r($lead_array);
		//return 0;
		if($user_name != '' && $user_pwd != ''  && $uid != '' && count($resultNewArray) > 0) // Updated on 30-09-2024
		{
			//$result_array = array_filter($lead_array);
			// print_r($result_array);
			// die();
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

			if (property_exists($oauth2_token_response_obj, 'error')) {
				//print $oauth2_token_response_obj->error;
				return  $oauth2_token_response_obj->error_message;
			}

			$oauth_token = $oauth2_token_response_obj->access_token;

			foreach($resultNewArray as $user=>$leads)
			{				
				if($batch_flag == 'p3_batch_id')
				{
					if(!empty($user) && $user != 'null' && is_array($leads))
					{
						// $record = array(											
						// 	'assigned_user_id' => $lead_row_array->user,							
						// 	'modified_user_id' => $uid,
						// 	'p3_batch_id_c' => $batch_id,
						// 	'status' => 'Assigned',
						// 	'custom_status_c' => 'New No Contact',
						// );

						$record = array(			
								"massupdate_params" => array(
									"uid" => $leads,						
									'assigned_user_id' => $user,
									'modified_user_id' => $uid,
									'p3_batch_id_c' => $batch_id,
									'status' => 'Assigned',
									'custom_status_c' => 'New No Contact',
							)
						);
					}
					else
					{
						// $record = array(											
						// 	'modified_user_id' => $uid,
						// 	'p3_batch_id_c' => $batch_id,
						// 	'status' => 'Assigned',
						// 	'custom_status_c' => 'New No Contact'
						// );

						$record = array(			
							"massupdate_params" => array(
								"uid" => $leads,
								'modified_user_id' => $uid,
								'p3_batch_id_c' => $batch_id,
								'status' => 'Assigned',
								'custom_status_c' => 'New No Contact',
							)
						);
					}
				}
				else
				{
					if(!empty($user) && $user != 'null' && is_array($leads))
					{
						// $record = array(											
						// 	'assigned_user_id' => $lead_row_array->user,							
						// 	'modified_user_id' => $uid,
						// 	'cr_batch_id_c' => $batch_id,
						// 	'status' => 'Assigned',
						// 	'custom_status_c' => 'New No Contact',
						// );

						$record = array(			
							"massupdate_params" => array(
								"uid" => $leads,						
								'assigned_user_id' => $user,
								'modified_user_id' => $uid,
								'cr_batch_id_c' => $batch_id,
								'status' => 'Assigned',
								'custom_status_c' => 'New No Contact',
							)
						);
					}
					else
					{
						// $record = array(											
						// 	'modified_user_id' => $uid,
						// 	'cr_batch_id_c' => $batch_id,
						// 	'status' => 'Assigned',
						// 	'custom_status_c' => 'New No Contact'
						// );

						$record = array(			
							"massupdate_params" => array(
								"uid" => $leads,
								'modified_user_id' => $uid,
								'cr_batch_id_c' => $batch_id,
								'status' => 'Assigned',
								'custom_status_c' => 'New No Contact',
							)
						);
					}
				} 

				if ($oauth_token != '') 
				{
					$url = $instance_url . "/Leads/MassUpdate"; 
					// . $record_id; Updated on 30-09-2024
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
					file_put_contents('CAfinalAssignResp.txt', $curl_response . PHP_EOL, FILE_APPEND);
					$updatedRecord  = json_decode($curl_response);
					curl_close($curl_request);

					//echo $curl_response;
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
		return true;
		// $error = 0;
		// $uid = $this->session->userdata('User_id');
		// $user_name = $this->session->userdata('uname');
		// $user_pwd = $this->session->userdata('pwd');

		// $instance_url = $this->sugar_url . "rest/v11"; // https://sugarupdate.nablasol.net/

		// if ($user_name != '' && $user_pwd != ''  && $uid != '' && count($lead_array) > 0) {
		// 	//print_r($lead_array);
		// 	$result_array = array_filter($lead_array);
		// 	//print_r($result_array);
		// 	//die();
		// 	$record = array(

		// 		"massupdate_params" => array(
		// 			"uid" => $result_array,
		// 			'status' => 'Assigned',
		// 			'custom_status_c' => 'New No Contact',
		// 			'modified_user_id' => $uid
		// 		)
		// 	);

		// 	$auth_url = $instance_url . "/oauth2/token";

		// 	$oauth2_token_arguments = array(
		// 		"grant_type" => "password",
		// 		"client_id" => "sugar",
		// 		"client_secret" => "",
		// 		"username" => $user_name,
		// 		"password" => $user_pwd,
		// 		"platform" => "mobile"
		// 	);

		// 	$auth_request = curl_init($auth_url);
		// 	curl_setopt($auth_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		// 	curl_setopt($auth_request, CURLOPT_HEADER, false);
		// 	curl_setopt($auth_request, CURLOPT_SSL_VERIFYPEER, 0);
		// 	curl_setopt($auth_request, CURLOPT_RETURNTRANSFER, 1);
		// 	curl_setopt($auth_request, CURLOPT_FOLLOWLOCATION, 0);
		// 	curl_setopt($auth_request, CURLOPT_HTTPHEADER, array(
		// 		"Content-Type: application/json"
		// 	));

		// 	//convert arguments to json
		// 	$json_arguments = json_encode($oauth2_token_arguments);
		// 	curl_setopt($auth_request, CURLOPT_POSTFIELDS, $json_arguments);

		// 	//execute request
		// 	$oauth2_token_response = curl_exec($auth_request);

		// 	//decode oauth2 response to get token
		// 	$oauth2_token_response_obj = json_decode($oauth2_token_response);


		// 	if (property_exists($oauth2_token_response_obj, 'error')) {
		// 		//print $oauth2_token_response_obj->error;
		// 		return  $oauth2_token_response_obj->error_message;
		// 	}

		// 	$oauth_token = $oauth2_token_response_obj->access_token;

		// 	//Create Records - PUT /<module>/id
		// 	if ($oauth_token != '') {
		// 		$url = $instance_url . "/Leads/MassUpdate";
		// 		//Set up the Record details

		// 		$curl_request = curl_init($url);
		// 		curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
		// 		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		// 		curl_setopt($curl_request, CURLOPT_HEADER, false);
		// 		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
		// 		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		// 		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
		// 		curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
		// 			"Content-Type: application/json",
		// 			"oauth-token: {$oauth_token}"
		// 		));

		// 		//convert arguments to json
		// 		$json_arguments = json_encode($record);
		// 		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);

		// 		$curl_response = curl_exec($curl_request);

		// 		$updatedRecord  = json_decode($curl_response);
		// 		curl_close($curl_request);

		// 		echo $curl_response;
		// 	}
		// } else {
		// 	return 0;
		// }
	}

	public function getBatchIdDropdown()
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
			CURLOPT_POSTFIELDS => "{\r\n \"dropdowns\":{\"name\": \"cr_Batch_ID\"}\r\n}",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			$result = json_decode($response);
			//return $result->cr_Batch_ID;

			if (isset($result->cr_Batch_ID)) {
				return $result->cr_Batch_ID;
			} else {
				return null;
			}
		}
	}
}
