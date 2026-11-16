<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CoronaModel extends CI_Model 
{
    public function importCSVData($data)
	{
		//print_r($_FILES);
		$file = $_FILES['file'];
		$importCount = 0;
		$entryArgs = "";
		$csvTemp = fopen($file['tmp_name'], "r");
	
		while (($data = fgetcsv($csvTemp, 1000, ",")) !== FALSE) 
		{
			//$arg = count($data) ;
			//$arg = $arg . PHP_EOL;
			
		
			if(count($data) >= 5)
			{
				$logics_case				= $data[0];
				$final_rec_type				= $data[1];
				$total_cr_fee				= $data[2];
				$package					= $data[3];
				$note						= $data[4];
                $status						= $data[5];
                
				//print $logics_case . ', ' . $final_rec_type . ', ' . $total_cr_fee . ', ' . $package; // . ', ' . $note;
				//$note = htmlspecialchars(utf8_encode($note), ENT_QUOTES);
				if($note != '')
				{
					$note = addslashes($note);	
					$note = mb_convert_encoding($note, 'UTF-8', 'UTF-8');
				}
				try
				{
					$entryArgs = array(
					
						"logics_case" 				=> $logics_case,
						"final_rec_type" 			=> $final_rec_type,
						"total_cr_fee" 				=> $total_cr_fee,				
						"package" 					=> $package,
                        "note" 						=> $note,
                        "status"					=> $status,
					);
				
					if($importCount!=0)
					{
						//print $logics_case . ', ' . $final_rec_type . ', ' . $total_cr_fee . ', ' . $note . '<br>';
						$sheet_data = json_encode($entryArgs);
						if($sheet_data === false || is_null($sheet_data))
						{
							$jerror = json_last_error_msg();
							$txt_err = $logics_case . ' - Error - ' . $jerror . PHP_EOL;
							file_put_contents('NotImportedCases.txt',$txt_err,FILE_APPEND);
						}
						$result = $this->importCSVFileToSugar($sheet_data);
						if($result)
						{
							$importCount++;
						}
					}
					else
					{
						$importCount = 1;
					}
				
				}
				catch (Exception $e) 
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
		}	
		fclose($csvTemp);
		//file_put_contents('count_line.txt',$arg, FILE_APPEND);
		if($importCount>1)
		{
			echo json_encode(array("message" => "success","totalimported" => ($importCount-1)));
		}
		else
		{
			echo json_encode(array("message" => "error","totalimported" => ($importCount-1)));
		}
	
	}
	
	function importCSVFileToSugar($fileData)
	{
		global $db;
		$csvData = json_decode($fileData);			
		
		$lead_module_id = '';
		$case_module_id = '';
		$aggregate_module_id = '';
		$opportunity_id = '';
		$error = 0;
				
		$uid = $this->session->userdata('User_id');
		$user_name = $this->session->userdata('uname');
		$user_pwd = $this->session->userdata('pwd');
		session_write_close();
		
		if(!empty($csvData->logics_case))
		{
			$this->db_sugar->select("leads.id as lead_id, leads.opportunity_id, cases.id as case_id, aggre_aggregatevalues.id as aggregate_id");
			
			$this->db_sugar->from('leads');
			
			$this->db_sugar->join('leads_cstm', 'leads_cstm.id_c=leads.id', 'left');
			
			$this->db_sugar->join('cases_cstm', 'cases_cstm.case_id_c=leads_cstm.case_id_c', 'left');			
			
			$this->db_sugar->join('cases', 'cases.id=cases_cstm.id_c', 'left');
			
			$this->db_sugar->join('leads_aggre_aggregatevalues_1_c', 'leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1leads_ida=leads.id', 'left');			
			
			$this->db_sugar->join('aggre_aggregatevalues', 'aggre_aggregatevalues.id=leads_aggre_aggregatevalues_1_c.leads_aggre_aggregatevalues_1aggre_aggregatevalues_idb', 'left');			
			
			$this->db_sugar->join('aggre_aggregatevalues_cstm', 'aggre_aggregatevalues_cstm.id_c=aggre_aggregatevalues.id', 'left');
			
			$this->db_sugar->where('leads_cstm.case_id_c', $csvData->logics_case);	 
			$this->db_sugar->where('leads.deleted', 0);	 
			$this->db_sugar->where('cases.deleted', 0);	 
			$this->db_sugar->where('leads_aggre_aggregatevalues_1_c.deleted', 0);	 
			$this->db_sugar->where('aggre_aggregatevalues.deleted', 0);	 
			
			$data = $this->db_sugar->get();
			$record = $data->result();
			
			if(is_array($record) && count($record) > 0)
			{
				$lead_module_id = $record[0]->lead_id;
				$case_module_id = $record[0]->case_id;
				$aggregate_module_id = $record[0]->aggregate_id;
				$opportunity_id = $record[0]->opportunity_id;
			}
			else
			{
				return false;
			}
			//print $lead_module_id . ', ' . $case_module_id . ', ' . $aggregate_module_id;
			//return true;
			if ($lead_module_id != '' && $case_module_id != '') 
			{
				$lead_record = array(
					'lead_level_c' => 'Case Review',
					'modified_user_id' => 1,				
					'status' => 'Pending', 
					'custom_status_c' => 'Finance Desk', 
					'final_recommendation_type_c' => $csvData->final_rec_type,
					'cr_fee_c' => $csvData->total_cr_fee,				
				);
				
				if($csvData->status != '' && $csvData->status != null)
				{
					$case_record = array(
						'investigation_advocate_statu_c' =>  $csvData->status,
						'modified_user_id' => 1,				
					);
				}
				else
				{
					$case_record = array(
						'investigation_advocate_statu_c' =>  'Completed',
						'modified_user_id' => 1,				
					);
				}
               // print_r($case_record);
                //die();
				$aggregate_record = array(
					'corona_case_review_c' => 'Yes',
					'modified_user_id' => 1,				
				);
								
				if(!is_array($lead_record) || count($lead_record) <= 0)
				{
					$error = 1;
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
						//Update Lead Record with same auth token
						$url = $instance_url . "/Leads/" . $lead_module_id;
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
						$json_arguments = json_encode($lead_record);
						curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
						
						$curl_response = curl_exec($curl_request);
						
						$updatedRecord  = json_decode($curl_response);
						curl_close($curl_request);
						
						$txtl = $lead_module_id . PHP_EOL;
						file_put_contents('ImportCSVLeads.txt',$txtl, FILE_APPEND);
						
						/*----------------------------------------------*/
						//Update Case Record With same auth token
						
						$url_case = $instance_url . "/Cases/" . $case_module_id;
						//Set up the Record details
						
						$curl_request2 = curl_init($url_case);
						curl_setopt($curl_request2, CURLOPT_CUSTOMREQUEST, "PUT");
						curl_setopt($curl_request2, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
						curl_setopt($curl_request2, CURLOPT_HEADER, false);
						curl_setopt($curl_request2, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl_request2, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl_request2, CURLOPT_FOLLOWLOCATION, 0);
						curl_setopt($curl_request2, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json",
							"oauth-token: {$oauth_token}"
						));

						//convert arguments to json
						$json_arguments = json_encode($case_record);
						curl_setopt($curl_request2, CURLOPT_POSTFIELDS, $json_arguments);
						
						$curl_response = curl_exec($curl_request2);
						
						$updatedRecordCase = json_decode($curl_response);
						curl_close($curl_request2);
						
						$txtc = $case_module_id . PHP_EOL;
						file_put_contents('ImportCSVCase.txt',$txtc, FILE_APPEND);
						
						/*----------------------------------------------*/
						//Update Aggregate Record With same auth token
						if($aggregate_module_id != '')
						{
							$url_aggregate = $instance_url . "/Aggre_AggregateValues/" . $aggregate_module_id;
							//Set up the Record details
							
							$curl_request3 = curl_init($url_aggregate);
							curl_setopt($curl_request3, CURLOPT_CUSTOMREQUEST, "PUT");
							curl_setopt($curl_request3, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
							curl_setopt($curl_request3, CURLOPT_HEADER, false);
							curl_setopt($curl_request3, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($curl_request3, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl_request3, CURLOPT_FOLLOWLOCATION, 0);
							curl_setopt($curl_request3, CURLOPT_HTTPHEADER, array(
								"Content-Type: application/json",
								"oauth-token: {$oauth_token}"
							));

							//convert arguments to json
							$json_arguments = json_encode($aggregate_record);
							curl_setopt($curl_request3, CURLOPT_POSTFIELDS, $json_arguments);
							
							$curl_response = curl_exec($curl_request3);
							
							$updatedRecordAggregate = json_decode($curl_response);
							curl_close($curl_request3);
							
							$txta = $aggregate_module_id . PHP_EOL;
							file_put_contents('ImportCSVAggregate.txt',$txta, FILE_APPEND);
						}
						
						/*----------------------------------------------*/
						//Update All PPS Federal Personal Record With same auth token
						
						//get all pps record id
						$this->db_sugar->select(" inves_pps.id as pps_id");
						
						$this->db_sugar->from('inves_pps');
						
						$this->db_sugar->join('cases_inves_pps_1_c', 'cases_inves_pps_1_c.cases_inves_pps_1inves_pps_idb = inves_pps.id', 'left');
						
						$this->db_sugar->join('cases', 'cases.id = cases_inves_pps_1_c.cases_inves_pps_1cases_ida', 'left');
						
						$this->db_sugar->where('cases.id', $case_module_id);	
						
						$where = '(inves_pps.pps_type="Federal Personal" OR inves_pps.pps_type="Federal Business")';
						$this->db_sugar->where($where);	
						
						$this->db_sugar->where('inves_pps.deleted', 0);
						$this->db_sugar->where('cases.deleted', 0);
						$this->db_sugar->where('cases_inves_pps_1_c.deleted', 0);
						
						$data_pps = $this->db_sugar->get();
						$pps_record = $data_pps->result();
						$pps_record_ids = array();
						//print_r($pps_record);
						if(is_array($pps_record) && count($pps_record) > 0)
						{
							foreach($pps_record as $value)
							{
								$pps_record_ids[] = $value->pps_id;
							}						
						
							$pps_mass_update_record = array(
				
									"massupdate_params" => array(
										"uid" => $pps_record_ids,
										'pps_status' => 'Completed',
										'pps_requested_by_c' => 'Corona PPS',
									)
							);
						
							$pps_url = $instance_url . "/inves_PPS/MassUpdate";
							//Set up the Record details
							
							$curl_request_pps = curl_init($pps_url);
							curl_setopt($curl_request_pps, CURLOPT_CUSTOMREQUEST, "PUT");
							curl_setopt($curl_request_pps, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
							curl_setopt($curl_request_pps, CURLOPT_HEADER, false);
							curl_setopt($curl_request_pps, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($curl_request_pps, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl_request_pps, CURLOPT_FOLLOWLOCATION, 0);
							curl_setopt($curl_request_pps, CURLOPT_HTTPHEADER, array(
								"Content-Type: application/json",
								"oauth-token: {$oauth_token}"
							));

							//convert arguments to json
							$json_arguments = json_encode($pps_mass_update_record);
							curl_setopt($curl_request_pps, CURLOPT_POSTFIELDS, $json_arguments);
							
							$curl_response = curl_exec($curl_request_pps);
							
							$updatedRecord_pps  = json_decode($curl_response);
							curl_close($curl_request_pps);	
													
							//file_put_contents('ImportCSVPPS.txt',print_r($pps_mass_update_record), FILE_APPEND);
						}
						
						/*-------------------------------------*/
						//Create notes 
						$note_txt = $csvData->note . ' CR Fee - ' . $csvData->total_cr_fee . ".";
						
						$note_text = html_entity_decode($note_txt, ENT_QUOTES, "UTF-8");
						//file_put_contents('note.txt',$note_text);
						//$note_text = htmlspecialchars(utf8_encode($note_txt), ENT_QUOTES);
						
						$note_record = array(
						
							'name' => 'Case Review',
							'modified_user_id' => 1,				
							'created_by' => 1, 
							'parent_type' => 'Cases', 
							'parent_id' => $case_module_id,
							'description' => $note_text,				
						);
				
						$note_url = $instance_url . "/Notes";
						//Set up the Record details
						
						$curl_request_note = curl_init($note_url);
						curl_setopt($curl_request_note, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
						curl_setopt($curl_request_note, CURLOPT_HEADER, false);
						curl_setopt($curl_request_note, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl_request_note, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl_request_note, CURLOPT_FOLLOWLOCATION, 0);
						curl_setopt($curl_request_note, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json",
							"oauth-token: {$oauth_token}"
						));

						$json_arguments = json_encode($note_record);
						curl_setopt($curl_request_note, CURLOPT_POSTFIELDS, $json_arguments);
						
						$curl_response = curl_exec($curl_request_note);
						//file_put_contents('service_res_json11.txt',$curl_response); 
						//print $curl_response;
						$createdRecord = json_decode($curl_response);
						curl_close($curl_request_note);

						//Create note in Lead record
						$note_record = array(
						
							'name' => 'Case Review',
							'modified_user_id' => 1,				
							'created_by' => 1, 
							'parent_type' => 'Leads', 
							'parent_id' => $lead_module_id,
							'description' => $note_text,				
						);
				
						$note_url = $instance_url . "/Notes";
						//Set up the Record details
						
						$curl_request_note = curl_init($note_url);
						curl_setopt($curl_request_note, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
						curl_setopt($curl_request_note, CURLOPT_HEADER, false);
						curl_setopt($curl_request_note, CURLOPT_SSL_VERIFYPEER, 0);
						curl_setopt($curl_request_note, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl_request_note, CURLOPT_FOLLOWLOCATION, 0);
						curl_setopt($curl_request_note, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json",
							"oauth-token: {$oauth_token}"
						));

						$json_arguments = json_encode($note_record);
						curl_setopt($curl_request_note, CURLOPT_POSTFIELDS, $json_arguments);
						
						$curl_response = curl_exec($curl_request_note);
						//file_put_contents('service_res_json11.txt',$curl_response); 
						//print $curl_response;
						$createdRecord = json_decode($curl_response);
						curl_close($curl_request_note);
						
						/*---------------------------------------------------*/
						//Create RLI 
						/*if($opportunity_id != '')
						{							
							//get all product details based on recommendation type
							
							$this->db_sugar->select("id as product_id, name as product_name, type_id as product_type_id");
			
							$this->db_sugar->from('product_templates');
							
							$this->db_sugar->where('product_templates.deleted', 0);	 
							
							$where = '(id="c402dd86-910d-11e9-b3a9-0a1db36945e2" OR id="56ca8b04-751b-11ea-b8fd-0a1db36945e2" OR id = "fb355804-6d79-11e9-93c4-521b35c9a2c2" OR id = "149f2d94-6d6e-11e9-b52e-521b35c9a2c2" OR id = "ef632b62-6d6c-11e9-84fe-521b35c9a2c2")';
							
							$this->db_sugar->where($where);
							$records_rli = array();
							
							$data_rli = $this->db_sugar->get();
							$records_rli = $data_rli->result();
							//$pps_record_ids = array();
							//print_r($pps_record);
							
							$discount_reason = '';
							$disc_amt = '';
								
							if($csvData->package == "Yes")
							{
								$discount_reason = "Service Package";
								$disc_amt = 0;
							}
							
							$tax_relief_name = '';
							$tax_relief_id = '';
							$tax_relief_type_id = '';
							
							$unfiled_name = '';
							$unfiled_id = '';
							$unfiled_type_id = '';
							
							$fed_hardship_name = '';
							$fed_hardship_id = '';
							$fed_hardship_type_id  = '';
							
							$inc_based_name = '';
							$inc_based_id = '';
							$inc_based_type_id = '';
							
							$fin_privacy_name = '';
							$fin_privacy_id = '';
							$fin_privacy_type_id  = '';


							if(is_array($records_rli) && count($records_rli) > 0)
							{
								foreach($records_rli as $value)
								{
									//$txt = $value->product_name . PHP_EOL;
									//file_put_contents('product_name1.txt',$txt, FILE_APPEND);			
									if($value->product_name == "Tax Relief Package")
									{
										$tax_relief_name = $value->product_name;
										$tax_relief_id = $value->product_id;
										$tax_relief_type_id = $value->product_type_id;
									}
									if($value->product_name == "Unfiled Returns 2014 - 2019  (If Applicable)")
									{
										$unfiled_name = $value->product_name;
										$unfiled_id = $value->product_id;
										$unfiled_type_id = $value->product_type_id;
									}
									if($value->product_name == "Federal Hardship Plan")
									{
										$fed_hardship_name = $value->product_name;
										$fed_hardship_id = $value->product_id;
										$fed_hardship_type_id = $value->product_type_id;
									}
									if($value->product_name == "Income Based Repayment Plan")
									{
										$inc_based_name = $value->product_name;
										$inc_based_id = $value->product_id;
										$inc_based_type_id = $value->product_type_id;
									}
									if($value->product_name == "Financial Privacy Plan")
									{
										$fin_privacy_name = $value->product_name;
										$fin_privacy_id = $value->product_id;
										$fin_privacy_type_id = $value->product_type_id;
									}
									//$pps_record_ids[] = $value->pps_id;
								}
							}
							
														

							
							//2 should go for all cases
							if($csvData->package != "No")
							{								
								if($unfiled_name != '' && $unfiled_id != '' && $unfiled_type_id != '')
								{
									//$txt1 = $unfiled_name . ', ' . $unfiled_id . ', ' . $unfiled_type_id;
									//file_put_contents('if1_unfiled.txt',$txt1);
								
									$record = array(
										'name' => $unfiled_name,
										'modified_user_id' => 1,
										'created_by' => 1,
										'description' => "",
										'product_template_id' => $unfiled_id, 
										'total_amount' => 0,
										'category_id' => '',
										'list_price' => 0,
										'quantity' => 1,
										'opportunity_id' => $opportunity_id,
										'product_catalog_type_c' => "Preparation",
										'sale_type_c' => "Case Review",
										'discount_reason_c' => $discount_reason,
										'discount_amount' => $disc_amt,
										'lead_from_c' => "CTax",
										'epi_business_revenuelineitems_1epi_business_ida'=> ''
									);
									  
									$rli_url = $instance_url . "/RevenueLineItems";
									//Set up the Record details
									
									$curl_request_rli = curl_init($rli_url);
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
								}
							}
							
							if($csvData->final_rec_type == "Federal Hardship Plan" ||$csvData->final_rec_type == "Income Based Repayment Plan" ||$csvData->final_rec_type == "Financial Privacy Plan")
							{
								$record_diff = array();
								
								if($csvData->final_rec_type == "Federal Hardship Plan" && $fed_hardship_name != '' && $fed_hardship_id != '' && $fed_hardship_type_id != '')
								{
									$record_diff = array(
										'name' => $fed_hardship_name,
										'modified_user_id' => 1,
										'created_by' => 1,
										'description' => "",
										'product_template_id' => $fed_hardship_id, 
										'total_amount' => 0,
										'category_id' => '',
										'list_price' => 0,
										'quantity' => 1,
										'opportunity_id' => $opportunity_id,
										'product_catalog_type_c' => "Resolutions",
										'sale_type_c' => "Case Review",
										'discount_reason_c' => $discount_reason,
										'discount_amount' => $disc_amt,
										'lead_from_c' => "CTax",
										'epi_business_revenuelineitems_1epi_business_ida'=> ''
									);
								}
							
								if($csvData->final_rec_type == "Income Based Repayment Plan" && $inc_based_name != '' && $inc_based_id != '' && $inc_based_type_id != '')
								{
									$record_diff = array(
										'name' => $inc_based_name,
										'modified_user_id' => 1,
										'created_by' => 1,
										'description' => "",
										'product_template_id' => $inc_based_id, 
										'total_amount' => 0,
										'category_id' => '',
										'list_price' => 0,
										'quantity' => 1,
										'opportunity_id' => $opportunity_id,
										'product_catalog_type_c' => "Resolutions",
										'sale_type_c' => "Case Review",
										'discount_reason_c' => $discount_reason,
										'discount_amount' => $disc_amt,
										'lead_from_c' => "CTax",
										'epi_business_revenuelineitems_1epi_business_ida'=> ''
									);
								}
							
								if($csvData->final_rec_type == "Financial Privacy Plan" && $fin_privacy_name != '' && $fin_privacy_id != '' && $fin_privacy_type_id != '')
								{
									$record_diff = array(
										'name' => $fin_privacy_name,
										'modified_user_id' => 1,
										'created_by' => 1,
										'description' => "",
										'product_template_id' => $fin_privacy_id, 
										'total_amount' => 0,
										'category_id' => '',
										'list_price' => 0,
										'quantity' => 1,
										'opportunity_id' => $opportunity_id,
										'product_catalog_type_c' => "Resolutions",
										'sale_type_c' => "Case Review",
										'discount_reason_c' => $discount_reason,
										'discount_amount' => $disc_amt,
										'lead_from_c' => "CTax",
										'epi_business_revenuelineitems_1epi_business_ida'=> ''
									);
								}
								if(is_array($record_diff) && count($record_diff) > 0)
								{
									$rli_url = $instance_url . "/RevenueLineItems";
									//Set up the Record details
									
									$curl_request_rli = curl_init($rli_url);
									curl_setopt($curl_request_rli, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
									curl_setopt($curl_request_rli, CURLOPT_HEADER, false);
									curl_setopt($curl_request_rli, CURLOPT_SSL_VERIFYPEER, 0);
									curl_setopt($curl_request_rli, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($curl_request_rli, CURLOPT_FOLLOWLOCATION, 0);
									curl_setopt($curl_request_rli, CURLOPT_HTTPHEADER, array(
										"Content-Type: application/json",
										"oauth-token: {$oauth_token}"
									));

									$json_arguments = json_encode($record_diff);
									curl_setopt($curl_request_rli, CURLOPT_POSTFIELDS, $json_arguments);
									
									$curl_response = curl_exec($curl_request_rli);
									
									$createdRecord = json_decode($curl_response);
									curl_close($curl_request_rli);
								}
							}
							if($tax_relief_name != '' && $tax_relief_id != '' && $tax_relief_type_id != '')
							{
								$record = array(
									'name' => $tax_relief_name,
									'modified_user_id' => 1,
									'created_by' => 1,
									'description' => "",
									'product_template_id' => $tax_relief_id, 
									'total_amount' => $csvData->total_cr_fee,
									'category_id' => '',
									'list_price' => $csvData->total_cr_fee,
									'quantity' => 1,
									'opportunity_id' => $opportunity_id,
									'product_catalog_type_c' => "Resolutions",
									'sale_type_c' => "Case Review",
									'discount_reason_c' => $discount_reason,
									'discount_amount' => $disc_amt,
									'lead_from_c' => "CTax",
									'epi_business_revenuelineitems_1epi_business_ida'=> ''
								);
								
								$rli_url = $instance_url . "/RevenueLineItems";
								//Set up the Record details
								
								$curl_request_rli = curl_init($rli_url);
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
							}
							
							
						}*/
					}
				}				
			}
			return true;
		}
		else
		{
			return false;
		}
	}

}