<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AnthemModel extends CI_Model 
{
    public function importCSVData($data)
	{
		$file = $_FILES['file'];
		
		$importCount = 0;
		$entryArgs = "";
		$csvTemp = fopen($file['tmp_name'], "r");

		$oauth2_token_response_obj = $this->getAuthToken();
		$i=0;
		
		if(property_exists($oauth2_token_response_obj, 'error'))
		{
			if($i==0){
				$oauth2_token_response_obj = $this->getAuthToken();
				$i++;
			}
			
		}
		else
		{
			
			$oauth_token = $oauth2_token_response_obj->access_token;
		}

		if($oauth_token != '')
		{
			fgetcsv($csvTemp); // Ignore heads
			while (($data = fgetcsv($csvTemp, 1000, ",")) !== FALSE) 
			{ 		
				if(count($data) >= 10)
				{
					$payment_date				= $data[0];
					$case_id					= $data[1];
					$rli_id						= $data[2];
					$payment_amount				= $data[3];
					$credit						= $data[4];
					$lender_credit				= $data[5];
					$cc_credit					= $data[6]; 
					$batch_id					= $data[7];
					$batch_date					= $data[8];
					$batch_amount				= $data[9]; 
					$ini_p2_pay					= $data[10]; 
					$testcaseid   =is_numeric($case_id);            
                    file_put_contents('case.txt',$case_id.'---'.$testcaseid, FILE_APPEND);

					if(empty($case_id) )
					{
						$result='case id is blank';
						break;
					}					
					//try
					//{
						$entryArgs = array(					
							"payment_date" 				=> $payment_date,
							"case_id" 					=> $case_id,
							"rli_id" 					=> $rli_id,				
							"payment_amount" 			=> $payment_amount,
							"credit" 					=> $credit,
							"lender_credit"				=> $lender_credit,
							"cc_credit"					=> $cc_credit,
							"batch_id"					=> $batch_id,
							"batch_date"				=> $batch_date,
							"batch_amount"				=> $batch_amount,
							"ini_p2_pay"				=> $ini_p2_pay,
						);
						file_put_contents('count.txt',$importCount.'---', FILE_APPEND);
						//if($importCount != 0)
						{
							$sheet_data = json_encode($entryArgs);
							if($sheet_data === false || is_null($sheet_data))
							{
								$jerror = json_last_error_msg();							
								file_put_contents('NotImportedAnthemCases.txt',$jerror,FILE_APPEND);
							}
							else
							{
								  $result = $this->importCSVFileToSugar($sheet_data,$oauth_token);	
								  if($result!='true') {
									echo json_encode(array("message" => "error", "showthis"=>$result));
									die();
								  }
														
							}					
							
							if($result==true)
							{
								$importCount++;
							}
						}
						// else
						// {
						// 	$importCount = 1;
						// }
					
					// }
					// catch (Exception $e) 
					// {
					// 	echo 'Caught exception: ',  $e->getMessage(), "\n";
					// }
				}
			}
		}else{
			echo json_encode(array("message" => "Auth_token","totalimported" => ($importCount)));
		}	
		fclose($csvTemp);
		//file_put_contents('count_line.txt',$arg, FILE_APPEND);
		file_put_contents('count_line_result.txt',$result, FILE_APPEND);
		if(($importCount>=1) && ($result=='true'))
		{
			echo json_encode(array("message" => "success","totalimported" => ($importCount)));
		}
		else
		{
			echo json_encode(array("message" => "error","totalimported" => ($importCount), "showthis"=>$result));
		}
	
	}

	
	function importCSVFileToSugar($fileData,$oauth_token)
	{
		$csvData = json_decode($fileData);
		
		$instance_url = $this->sugar_url."rest/v11";  
	 	
		$error = 0;

		//Login - POST /oauth2/token
		$auth_url = $instance_url . "/oauth2/token";

		$record_data = array(
			"payment_date" 				=> $csvData->payment_date,
			"case_id" 					=> $csvData->case_id,
			"rli_id" 					=> $csvData->rli_id,				
			"payment_amount" 			=> $csvData->payment_amount,
			"credit" 					=> $csvData->credit,
			"lender_credit"				=> $csvData->lender_credit,
			"cc_credit"					=> $csvData->cc_credit,
			"batch_id"					=> $csvData->batch_id,
			"batch_date"				=> $csvData->batch_date,
			"batch_amount"				=> $csvData->batch_amount,
			"ini_p2_pay"				=> $csvData->ini_p2_pay,
		);
       try{
			//$url = $instance_url. '/InsertPostedPayment';
			$url = $this->sugar_url. "rest/v11_1" . '/InsertPostedPayment';			

			$ch = curl_init();
				
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $record_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					"oauth-token: {$oauth_token}"
					));		

			// Receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec($ch);
			curl_close ($ch);				
			return $server_output;
		   
	   }catch (Exception $e) 
	   {
		   
		     $error = $e->getMessage();
			 file_put_contents('CurlException.txt',$error,FILE_APPEND);
			 return false;
	   }
		
	}

	function getAuthToken()
		{
			 $userName = $this->session->userdata('uname');
			 $password = $this->session->userdata('pwd');
			 session_write_close();
			 $instance_url = $this->sugar_url."rest/v11"; 
						 
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

}