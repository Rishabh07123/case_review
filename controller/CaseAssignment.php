<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CaseAssignment extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');		
        $this->load->model('CaseAssignmentModel');  
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "CaseReview | CaseAssignment"; 
		
		$lang = '';
		$pay_status = '';
		$final_recom = '';
        $cr_batch_id = '';
        $sub_status = '';
		$no_payments_made = '';
		$so_assignments = $this->CaseAssignmentModel->fetch_case_assignments($lang, $pay_status,$userid='',$team_id='',$final_recom,$cr_batch_id,$sub_status,$no_payments_made);	
		
		$user_list = $this->DBmodel->getAllSrCaseAnalystUsers();
		$batch_list = $this->CaseAssignmentModel->getBatchIdDropdown();
			
		if(is_array($so_assignments) && count($so_assignments) > 0)
		{
			$data['so_assignments'] = $so_assignments;				
		}
		else 
		{			
			$data['so_assignments'] = 0;
		}
		
		$data['user_list'] = $user_list;
		$data['batch_list'] = $batch_list;			
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('CaseAssignmentView',$data);		
		$this->load->view('common/footer');
	}	
	
	public function get_case_assignment_records()
    {
        $lang = '';
		$pay_status = '';
		$result = $this->CaseAssignmentModel->fetch_case_assignments($lang, $pay_status);	
		
        if($result)
        {
			echo json_encode($result);
        }
    }
	
	public function update_lead_assignment()
    {
		//$assign_user_id = $this->input->post('assign_user_id');
		$batch_id = $this->input->post('batch_id');
        $lead_array = $this->input->post('lead_array');
		$batch_flag = $this->input->post('batch_flag'); //Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services 
        //print_r( $lead_array );
        $result = $this->CaseAssignmentModel->update_lead_assigned_to($lead_array, $batch_id, $batch_flag); //$assign_user_id,  //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services 
		
        if($result)
        {
			echo json_encode($result);
        }
	}
	
	public function tempAssignment()
	{
		$assign_user_id = $this->input->post('assign_user_id');
        $lead_array = $this->input->post('lead_array');
        
        $result = $this->CaseAssignmentModel->assignTempUser($assign_user_id,  $lead_array);
		
        if($result) 
        {
			echo json_encode($result);
        }
	}
	
	public function update_lead_assignment_status()
    {
        $lead_array = $this->input->post('lead_array');
        
        $result = $this->CaseAssignmentModel->update_lead_assigned_status($lead_array);
		//print  $assign_user_id;
		//print_r($lead_array);
        if($result)
        {
            echo json_encode($result);
        }
    }
	
	public function get_filtered_data()
    {
        $language = $this->input->post('language');
        $payment_status = $this->input->post('pay_status');
        $user_ids = $this->input->post('user_ids');
		$team_id = $this->input->post('team_id');
		$final_recom = $this->input->post('final_recomm');
        $cr_batch_id = $this->input->post('cr_batch_id');
		$p3_batch_id = $this->input->post('p3_batch_id'); //Added on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services 
        $sub_status = $this->input->post('sub_status');
		$no_payments_made = $this->input->post('no_payments_made');
        
        $result = $this->CaseAssignmentModel->fetch_case_assignments($language,$payment_status,$user_ids,$team_id,$final_recom,$cr_batch_id,$p3_batch_id, $sub_status, $no_payments_made); //Updated on 26-10-2023 https://trello.com/c/ssf7tP58/552-cr-portal-assignments-add-services 
		//print  $assign_user_id;
		//print_r($lead_array);
        if($result)
        {
            echo json_encode($result);
        }
    }
}