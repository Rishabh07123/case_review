<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoronaUploader extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');
		$this->load->model('CoronaModel');
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "CaseReview | CoronaUploader"; 
		$data['no_header'] = 0;
		//$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['user_role'] = $this->DBmodel->checkIfAdmin();
		//$data['write_assignments'] = 0;
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		
		$this->load->view('common/header' ,$data);
		$this->load->view('CoronaUploaderView',$data);		
		$this->load->view('common/footer');
	}

	public function importData()
	{
		$data = $this->input->post('data');
        //$lead_array = $this->input->post('lead_array');
        //print_r($_FILES);
        $result = $this->CoronaModel->importCSVData($data);
		
        if($result)
        {
			echo json_encode($result);
        }
	}

}