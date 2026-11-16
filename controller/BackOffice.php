<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackOffice extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		parent::__construct();
		//$this->db2 = $this->load->database('sugartax', TRUE);
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->load->model('DBmodel');
		$this->load->model('BackOfficeModel');
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		
		$data['mycases'] = $this->BackOfficeModel->getBOCases();

		$data['title'] = "CaseReview | BackOffice"; 
		
		/*$my_cases = $this->DBmodel->getMyCases();
			
		if(is_array($my_cases) && count($my_cases) > 0)
		{
			$data['mycases'] = $my_cases;				
		}
		else 
		{			
			$data['mycases'] = 0;
		}*/		
		
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('BackOfficeView',$data);		
		$this->load->view('common/footer');
	}
	
	public function CaseBODetail()
	{
		$case_id = $this->input->post('caseid');
						
		$result = $this->DBmodel->check_case_in_sugar($case_id);
		
		if($result)
		{
			echo $result;
		}
	}
	
	
}