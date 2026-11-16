<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReadyForAssignment extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');		
        $this->load->model('ReadyForAssignmentModel');  
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "CaseReview | ReadyForAssignment"; 
		//print_r('Hello');exit;
		$lang = '';
		$pay_status = '';
		$so_assignments = $this->ReadyForAssignmentModel->fetch_ready_for_assignments($lang, $pay_status,$userid='',$team_id='');
		$user_list = $this->DBmodel->getAllSrCaseAnalystUsers();
		if(is_array($so_assignments) && count($so_assignments) > 0)
		{
			$data['so_assignments'] = $so_assignments;
		}
		else
		{
			$data['so_assignments'] = 0;
		}
		
		$data['user_list'] = $user_list;
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = 1; //$this->DBmodel->checkInvestigationManagerRole();
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('ReadyForAssignmentView',$data);
		$this->load->view('common/footer');
	}
	
	public function get_case_assignment_records()
    {
        $lang = '';
		$pay_status = '';
		$result = $this->ReadyForAssignmentModel->fetch_ready_for_assignments($lang, $pay_status);	
		
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
        //print_r( $lead_array );
        $result = $this->ReadyForAssignmentModel->update_lead_assigned_to($lead_array, $batch_id); //$assign_user_id,  
		
        if($result)
        {
			echo json_encode($result);
        }
    }
	
	public function tempAssignment()
	{
		$assign_user_id = $this->input->post('assign_user_id');
        $lead_array = $this->input->post('lead_array');
        
        $result = $this->ReadyForAssignmentModel->assignTempUser($assign_user_id,  $lead_array);
		
        if($result)
        {
			echo json_encode($result);
        }
	}

	public function update_lead_assignment_status()
    {
        $lead_array = $this->input->post('lead_array');
        
        $result = $this->ReadyForAssignmentModel->update_lead_assigned_status($lead_array);
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
        
        $result = $this->ReadyForAssignmentModel->fetch_ready_for_assignments($language,$payment_status,$user_ids,$team_id);
		//print  $assign_user_id;
		//print_r($lead_array);
        if($result)
        {
            echo json_encode($result);
        }
    }
}