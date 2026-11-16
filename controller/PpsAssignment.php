<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PpsAssignment extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
        $this->load->model('DBmodel');
        $this->load->model('PPSAssignmentModel');        
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "CaseReview | PPS Assignment"; 
		
		$lang = '';
		$pay_status = '';
		$pps_assignments = $this->PPSAssignmentModel->fetchPPSData($lang, $pay_status, $folder='', $stage='', $call_type='', $pps_type='', $mstatus ='', $fstatus = '');	//$userid='',
		
		$user_list = $this->DBmodel->getAllUsers(); //$this->DBmodel->getInvestigationAdvocateUsers();
			
		if(is_array($pps_assignments) && count($pps_assignments) > 0)
		{
			$data['pps_assignments'] = $pps_assignments;				
		}
		else 
		{			
			$data['pps_assignments'] = 0;
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
		$this->load->view('PpsAssignmentView',$data);		
		$this->load->view('common/footer');
	}	
	
	/*public function get_case_assignment_records()
    {
        $lang = '';
		$pay_status = '';
		$result = $this->DBmodel->fetchPPSData($lang, $pay_status);	
		
        if($result)
        {
			echo json_encode($result);
        }
    }*/
	
	public function updatePPSAssignment()
    {
        $assign_user_id = $this->input->post('assign_user_id');
        $case_array = $this->input->post('case_array');
        
        $result = $this->PPSAssignmentModel->updatePPSAssignedTo($assign_user_id,  $case_array);
		
        if($result)
        {
			echo json_encode($result);
        }
    }
	
	public function getFilteredPPSData()
    {
        $language = $this->input->post('language');
        $payment_status = $this->input->post('pay_status');
        //alert(folder + ', ' + stage + ', ' + call_type + ', ' +  pps_type);
		$folder = $this->input->post('folder');
		$stage = $this->input->post('stage');
		$call_type = $this->input->post('call_type');
		$pps_type = $this->input->post('pps_type');
		$mstatus = $this->input->post('mstatus');
		$fstatus = $this->input->post('fstatus');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

        $result = $this->PPSAssignmentModel->fetchPPSData($language,$payment_status, $folder, $stage, $call_type, $pps_type, $mstatus, $fstatus, $start_date, $end_date); //,$team_id  $user_ids,
		//print  $assign_user_id;
		//print_r($lead_array);
        if($result)
        {
            echo json_encode($result);
        }
    }
}