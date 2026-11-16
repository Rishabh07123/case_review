<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Writingassignment extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "CaseReview | Writing Assignment"; 
		
		$lang = '';
		$pay_status = '';
		$write_assignments = $this->DBmodel->fetch_writing_assignments($lang, $pay_status,$team_id='');	//$userid='',
		
		$user_list = $this->DBmodel->getAllUsers();
			
		if(is_array($write_assignments) && count($write_assignments) > 0)
		{
			$data['write_assignments'] = $write_assignments;				
		}
		else 
		{			
			$data['write_assignments'] = 0;
		}
		
		$data['user_list'] = $user_list;	
		$data['user_role'] = $this->DBmodel->checkIfAdmin();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		//print $data['user_role'];
		//die();
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('writingassignment',$data);		
		$this->load->view('common/footer');
	}	
	
	public function get_case_assignment_records()
    {
        $lang = '';
		$pay_status = '';
		$result = $this->DBmodel->fetch_writing_assignments($lang, $pay_status);	
		
        if($result)
        {
			echo json_encode($result);
        }
    }
	
	public function update_case_assignment()
    {
        $assign_user_id = $this->input->post('assign_user_id');
        $case_array = $this->input->post('case_array');
        
        $result = $this->DBmodel->update_case_assigned_to($assign_user_id,  $case_array);
		
        if($result)
        {
			echo json_encode($result);
        }
    }
	
	public function get_filtered_data_case()
    {
        $language = $this->input->post('language');
        $payment_status = $this->input->post('pay_status');
        //$user_ids = $this->input->post('user_ids');
        $team_id = $this->input->post('team_id');
        
        $result = $this->DBmodel->fetch_writing_assignments($language,$payment_status,$team_id); //$user_ids,
		//print  $assign_user_id;
		//print_r($lead_array);
        if($result)
        {
            echo json_encode($result);
        }
    }
}