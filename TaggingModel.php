<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TaggingModel extends CI_Model 
{    
    public function getTaggingRecords($status,$subStatus,$screenDateFrom,$screenDateTo,$lastDateFrom,$lastDateTo,$language,$leadsAssign,$tags, $lead_level='', $billing_status='') 
    {
        /*$sql = "SELECT DISTINCT leads.id, concat(leads.first_name,' ', leads.last_name) as name, leads.status, leads_cstm.case_id_c, leads_cstm.custom_status_c, round(leads_cstm.cid_fed_debt_amount_c,2) as debt_amount , leads_cstm.cr_fee_c, DATE_FORMAT(leads_cstm.date_screen_completed_c , '%d-%m-%Y') as screen_date, DATE_FORMAT(leads_cstm.lastinquirydate_c , '%d-%m-%Y') as lastinquirydate , tags.name as tagName, tags.id as tagId
                ,concat(users.first_name,' ',users.last_name) as username FROM leads 
                LEFT JOIN leads_cstm ON leads_cstm.id_c = leads.id 
                LEFT JOIN tag_bean_rel ON tag_bean_rel.bean_id = leads.id 
                LEFT JOIN tags ON tags.id = tag_bean_rel.tag_id 
                LEFT JOIN users ON users.id = leads.assigned_user_id 
                WHERE leads.deleted = 0 
                AND tag_bean_rel.deleted = 0
                AND tags.deleted = 0"; */

        $sql = "SELECT DISTINCT leads.id, leads_cstm.lead_level_c, concat(leads.first_name,' ', leads.last_name) as name, leads.status, leads_cstm.case_id_c, leads_cstm.custom_status_c, round(leads_cstm.cid_fed_debt_amount_c,2) as debt_amount , leads_cstm.language_prequal_c as language_val, leads_cstm.cr_fee_c, DATE_FORMAT(leads_cstm.date_screen_completed_c , '%d-%m-%Y') as screen_date, DATE_FORMAT(leads_cstm.lastinquirydate_c , '%d-%m-%Y') as lastinquirydate , concat(users.first_name,' ',users.last_name) as username,
                entit_entitypaymentsummary_cstm.billingstatus_c, GROUP_CONCAT( tags.name ) as tagName

                FROM leads 
                LEFT JOIN leads_cstm ON leads_cstm.id_c = leads.id 
                LEFT JOIN users ON users.id = leads.assigned_user_id 
                LEFT JOIN tag_bean_rel ON tag_bean_rel.bean_id = leads.id AND tag_bean_rel.deleted = 0
                LEFT JOIN tags ON tags.id = tag_bean_rel.tag_id AND tags.deleted = 0
                LEFT JOIN opportunities_entit_entitypaymentsummary_1_c ON opportunities_entit_entitypaymentsummary_1opportunities_ida = leads.opportunity_id AND opportunities_entit_entitypaymentsummary_1_c.deleted=0 
                LEFT JOIN entit_entitypaymentsummary ON entit_entitypaymentsummary.id = opportunities_entit_entitypaymentsummary_1_c.opportunit116asummary_idb AND entit_entitypaymentsummary.deleted=0
                LEFT JOIN entit_entitypaymentsummary_cstm ON entit_entitypaymentsummary_cstm.id_c = entit_entitypaymentsummary.id   
                WHERE leads.deleted = 0  
                AND targeted_services_c != 'Business and Accounting'";

        if($lead_level != '' && $lead_level != null)
        {
            $sql.= ' AND leads_cstm.lead_level_c="'.$lead_level.'"';
        }
        if($status != '' && $status != null)
        {
            $sql.= ' AND leads.status="'.$status.'"';
        }
        else
        {
            $sql.= ' AND leads.status= "New Lead" ';
        }
        if($subStatus != '' && $subStatus != null)
        {
            $sql.= ' AND leads_cstm.custom_status_c="'.$subStatus.'"';
        }
        if($billing_status != '' && $billing_status != null)
        {
            $sql.= ' AND entit_entitypaymentsummary_cstm.billingstatus_c = "'.$billing_status.'"';
        }
        if($screenDateFrom != '' && $screenDateFrom != null && $screenDateFrom != '0000-00-00' && $screenDateTo != '' && $screenDateTo != null && $screenDateTo != '0000-00-00')
        {
            $sql.= ' AND DATE(leads_cstm.date_screen_completed_c) between "'.$screenDateFrom.'" and "'.$screenDateTo.'" ';
        }
        if($lastDateFrom != '' && $lastDateFrom != null && $lastDateFrom != '0000-00-00' && $lastDateTo != '' && $lastDateTo != null && $lastDateTo != '0000-00-00')
        {
            $sql.= ' AND DATE(leads_cstm.lastinquirydate_c) between "'.$lastDateFrom.'" and "'.$lastDateTo.'"';
        }
        if($language != '' && $language != null)
        {
            $sql.= ' AND leads_cstm.language_prequal_c="'.$language.'"';
        }
        else
        {
            $sql.= ' AND leads_cstm.language_prequal_c = "English" ';
        }
        if(is_array($leadsAssign) && count($leadsAssign) > 0)
        {
            $uid_list = '';
            foreach($leadsAssign as $uid)
            {
                $uid_list .= "'" . $uid . "',";
            }
            $uid_list = rtrim($uid_list,',');

            $sql.= ' AND leads.assigned_user_id IN (' . $uid_list . ')';
        }

        if(is_array($tags) && count($tags) > 0)
        {
            $tagid_list = '';
            foreach($tags as $tid)
            {
                $tagid_list .= "'" . $tid . "',";
            }
            $tagid_list = rtrim($tagid_list,',');

            $sql.= ' AND tags.name IN (' . $tagid_list . ')';
        }

        $sql.= ' GROUP BY leads.id ORDER BY leads.date_modified DESC limit 1000 '; //
        //print $sql;
        $data = $this->db_sugar->query($sql);
        $record = $data->result();			
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

        return $oauth2_token_response_obj;
    }

    public function storeDataInSugar($oauth_token, $url, $record, $id=null)
    {
        if($oauth_token != '' && $url != '' && is_array($record))
        {    
            $instance_url = $this->sugar_url."rest/v11";  
            $url = $instance_url.$url;
            $curl_request = curl_init($url);

            // If event is update then pass put method
            if($id){
                //print $id;
                curl_setopt($curl_request, CURLOPT_CUSTOMREQUEST, "PUT");
            }

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

    public function removeTags($lead_array,$tagList)
    {
        //        print_r($lead_array); echo nl2br("\n");
        $error = 0;
        $uid = $this->session->userdata('User_id');
        $user_name = $this->session->userdata('uname');
        $user_pwd = $this->session->userdata('pwd');
        session_write_close();
        $instance_url = $this->sugar_url . "rest/v11";  

        if(count($lead_array) > 0 && count($tagList) > 0)
        {
            $tagid_list =  '';
            $leadid_list =  '';
            $tag_array = array();

            //            foreach($lead_array as $key=>$val)
            //            {
            //                $leadid_list = "'" . $val . "',";
            //            }
            $leadid_list = implode("','",$lead_array);

            if($leadid_list != '')
            { 
                //                $leadid_list = rtrim($leadid_list,',');
                foreach($tagList as $keey=>$value)
                {
                    $selTag = "SELECT id from tags where name='".$value."' ";
                    $qryTag = $this->db_sugar->query($selTag);
                    $record = $qryTag->row();
                    //print_r($record); echo nl2br("\n");
                    $tagId[] = $record->id;
                }
                $tagid_list = implode("','",$tagId);

                if(count($tagId) > 0 && $tagid_list != '')
                {
                    $updateTagbin = "UPDATE tag_bean_rel SET deleted='1', date_modified= now() WHERE bean_id IN ( '". $leadid_list. "') and tag_id IN ( '". $tagid_list. "')"; //, date_modified=''
                    $result_delete_rel = $this->db_sugar->query($updateTagbin);

                    $lastUpdate = $this->db_sugar->affected_rows();
                    
                    if($lastUpdate>0)
                    {
                        return 1;
                    }
                    elseif($lastUpdate==0)
                    {
                        return "No Tags Found";
                    }
                    else
                    {
                        return $this->db_sugar->error;
                    }
                }
                else
                {
                    return "No Tags Found";
                }
                /*$tagBinSql = "SELECT * from tag_bean_rel WHERE tag_bean_rel.bean_id IN ( ". $leadid_list. ") AND deleted=0;";
                $selectquery = $this->db_sugar->query($tagBinSql);
                $selectTagbinRel = $selectquery->result();

                foreach($selectTagbinRel as $key=>$tagIds)
                {
                    $tag_array[] = $tagIds->tag_id;
                }*/             

            }
            else
            {
                return "No Lead Found";
            }

            //return true;
            /*if($user_name != '' && $user_pwd != ''  && $uid != '' && count($tag_array) > 0)
            {	
                $record = array(
                    "massupdate_params" => array(
                        "uid" => $tag_array,						
                        'deleted' => 1,		 				
                        'modified_user_id' => $uid
                    )
                );

                $oauth2_token_response_obj = $this->getAuthToken();

                if(property_exists($oauth2_token_response_obj, 'error'))
                {
                    return  $oauth2_token_response_obj->error_message;
                }
                $json_arguments = json_encode($record);

                $oauth_token = $oauth2_token_response_obj->access_token;

                //Create Records - PUT /<module>/id
                if($oauth_token != '')
                {
                    $url = $instance_url . "/Tags/MassUpdate";
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

                    curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);

                    $curl_response = curl_exec($curl_request);

                    $updatedRecord  = json_decode($curl_response);
                    curl_close($curl_request);	

                    //echo $curl_response;
                    echo 1;
                }
            }*/

        }
        else
        {				
            return 0;
        }	
    }


    public function addLeadTag($leadid_array, $selectedTagArray)
    {
        $error = 0;
        $uid = $this->session->userdata('User_id');
        session_write_close();
        $error_msg = '';
        $tagArray = array();
        //print_r($selectedTagArray); die();

        if(count($selectedTagArray) > 0 && count($leadid_array) > 0)
        {
            foreach($selectedTagArray as $tag)
            {
                $tagArray[]['name'] = $tag;
            }
        }
        else
        {
            return "No Lead or Tag found.";
        }
        //print_r($tagArray); die();

        $oauth2_token_response_obj = $this->getAuthToken();
        if(property_exists($oauth2_token_response_obj, 'error'))
        { 
            return $oauth2_token_response_obj;
        }
        else
        {
            $oauth_token = $oauth2_token_response_obj->access_token;
            $instance_url = $this->sugar_url."rest/v11";                          

            $record = array(
                "massupdate_params" => array(
                    "uid" => $leadid_array,						
                    'tag' => $tagArray,
                    "tag_type" => "1"	 				
                    //'modified_user_id' => $uid
                )
            );

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

                if($updatedRecord->status = "done")
                    return 1;
                else
                    return $updatedRecord->error;

            }
            else
            {
                return "Auth token not found";
            }
        }
    }

    public function getTagList()
    {
        $tagQuery = "SELECT DISTINCT name from tags where deleted=0";

        $query = $this->db_sugar->query($tagQuery);
        $result = $query->result();
        return $result;
    }

    public function getStatusDropdown()
    {	
        $this->sugar_url = $this->config->item('sugar_url');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->sugar_url . "rest/v11_1/getDropdowns",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\r\n \"dropdowns\":{\"name\": \"lead_status_dom\"}\r\n}",
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
            return $response;
        }
    }

    public function subStatusDropdown($status)
    {
        //$url='https://ctaxinhouse1.nablasol.net/rest/v11_1/get-ddl-value-by-dependent-dropdown/';
        $url= $this->sugar_url . 'rest/v11_1/get-ddl-value-by-dependent-dropdown/';

        $jsonData = ['data'=>['custom_status_c',$status,'Lead']];
        // $jsonData = ['data'=>['Action Type','Client Call']];

        $postfields = json_encode($jsonData);

        // print_r($postfields); 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-KEY: '.$apiKey, 'Content-Type: multipart/form-data'));
        //curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");	
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);	
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        )); 
        // $result=curl_exec ($ch);

        // print $result;
        // curl_close ($ch);

        // if ($err) {
        // return "cURL Error #:" . $err;
        // } else {
        // return $result;
        // }


        $response = curl_exec($ch);
        $err = curl_error($ch);

        // curl_close($ch);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
        curl_close ($ch);
    }

    public function getAllUsers()
    {
        $this->db_sugar->select("users.id, concat(users.first_name, ' ', users.last_name) as uname "); 			
        $this->db_sugar->from('users');	

        $this->db_sugar->where('users.deleted', 0);	
        $this->db_sugar->where('users.status', 'Active');		

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