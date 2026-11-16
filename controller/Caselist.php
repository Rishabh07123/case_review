<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caselist extends CI_Controller {

	// Declared explicitly: dynamic properties are deprecated from PHP 8.2
	public $db_sugar;

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
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');		
		session_write_close();	
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		$data['title'] = "CaseReview | CaseList"; 
		
		$my_cases = $this->DBmodel->getMyCases();
			
		if(is_array($my_cases) && count($my_cases) > 0)
		{
			$data['mycases'] = $my_cases;				
		}
		else 
		{			
			$data['mycases'] = 0;
		}
		
		$my_pps_calls = $this->DBmodel->getMyPPSCalls();
			
		if(is_array($my_pps_calls) && count($my_pps_calls) > 0)
		{
			$data['mypps'] = $my_pps_calls;				
		}
		else 
		{			
			$data['mypps'] = 0;
		}
		
		$my_pricing_cases = $this->DBmodel->getMyPricingCases();
		$data['pricing_role'] = $this->DBmodel->checkUserRole();
			
		if(is_array($my_pricing_cases) && count($my_pricing_cases) > 0)
		{
			$data['mypricing'] = $my_pricing_cases;				
		}
		else 
		{			
			$data['mypricing'] = 0;
		} 
		
		$my_transcript_aptt = $this->DBmodel->getMyPPSTranscriptAppts();
			
		if(is_array($my_transcript_aptt) && count($my_transcript_aptt) > 0)
		{
			$data['my_transcript_aptt'] = $my_transcript_aptt;				
		}
		else 
		{			
			$data['my_transcript_aptt'] = 0;
		} 
		
		//added on 23-06-2022 https://trello.com/c/3fjRFeHw/1441-6-23-2022-appointment-setting-for-inv-jordan-sarah
		$my_appointments = $this->DBmodel->getMyAppointments();
			
		if(is_array($my_appointments) && count($my_appointments) > 0)
		{
			$data['appointmentList'] = $my_appointments;				
		}
		else 
		{			
			$data['appointmentList'] = 0;
		} 
		//end new
		
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('caselist',$data);		
		$this->load->view('common/footer');
	}
	
	public function check_caseid_in_sugar()
	{
		$case_id = $this->input->post('caseid');
						
		$result = $this->DBmodel->check_case_in_sugar($case_id);
		
		// always return a clean 1/0 (view JS checks res == 1)
		$this->output
			->set_content_type('text/plain')
			->set_output($result ? '1' : '0');
	}
}