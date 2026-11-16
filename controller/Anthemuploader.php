<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anthemuploader extends CI_Controller {

	/**
	 * Index Page for this controller.
	*/ 
	 
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');
		$this->load->model('AnthemModel');
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');
		session_write_close();		
			
		$data['title'] = "CaseReview | AnthemUploader"; 
		$data['no_header'] = 0;
		//$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['user_role'] = $this->DBmodel->checkIfAdmin();
		//$data['write_assignments'] = 0;
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		
		$this->load->view('common/header' ,$data);
		$this->load->view('anthemuploader',$data);
		$this->load->view('common/footer');
	}

	public function importData()
	{
		$data = $this->input->post('data');
		//file_put_contents('importData.txt',print_r($data,true));
        //$lead_array = $this->input->post('lead_array');
        $result = $this->AnthemModel->importCSVData($data);
		
        if($result)
        {
			echo json_encode($result);
        }
	}

}