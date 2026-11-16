<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Oldpps extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->load->model('DBmodel');
		
	}
	
	public function records()
	{
		$current_ip = $this->input->ip_address();
		
		$allow = $this->config->item('allowed_ips');   
		
		if(!in_array($current_ip, $allow)) 
		{
			print "403 forbidden";
			exit();
		}
		else
		{	
			$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
			$data['title'] = "CaseReview | OldPPS"; 
			$case_id= $this->input->get('id');
			$data['id'] = $case_id;
			$data['no_header'] = 1;
			$this->load->view('common/header' ,$data);
			$this->load->view('old_pps_link',$data);
		}
	}
	
	/*public function check_status()
	{	
		$data['title'] = "CaseReview | CaseReport"; 
		$case_id= $this->input->get('id');
		$data['id'] = $case_id;
		$data['status'] = $this->DBmodel->statusCheck($case_id);
		$this->load->view('common/header_report' ,$data);
		$this->load->view('casereport',$data);		
	}*/
	
	public function get_data()
	{
		$case_id= $this->input->post('caseid');	
		
		$sum = $this->DBmodel->general_caseid_details($case_id);

		//$data['checkpps'] = $this->DBmodel->check_pps($case_id);
		//$data['checkpps_sp'] = $this->DBmodel->check_pps_spouse($case_id);
		//$data['checkpps_extra'] = $this->DBmodel->check_extra_pps($case_id);
		//$data['checkpps_statepers'] = $this->DBmodel->check_pps_state_pers($case_id);
		
		//$pps = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps']);
		//$pps_spouse = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp']);			
		//$pps_notes = $this->DBmodel->get_pps_notes_sugar($data['checkpps']);
		//$pps_notes_spouse = $this->DBmodel->get_pps_notes_spouse_sugar($data['checkpps_sp']);
		//$pps_sum = $this->DBmodel->fetch_pps_summary($case_id);			
		
		$old_pps_personal = $this->DBmodel->get_old_pps_personal($case_id);	
		$old_pps_spouse = $this->DBmodel->get_old_pps_spouse($case_id);	
		$old_pps_state = $this->DBmodel->get_old_pps_state($case_id);	
		
		if(count($sum) > 0){
			$data['summary'] = $sum;
		}
		if(count($old_pps_personal) > 0){
			$data['old_pps_personal'] = $old_pps_personal;
		}
		if(count($old_pps_spouse) > 0){
			$data['old_pps_spouse'] = $old_pps_spouse;
		}
		if(count($old_pps_state) > 0){
			$data['old_pps_state'] = $old_pps_state;
		}
		/*if(count($pps_notes) > 0){
			$data['pps_notes'] = $pps_notes;
		}
		if(count($pps_notes_spouse) > 0){
			$data['pps_notes_spouse'] = $pps_notes_spouse;
		}
		
		if(count($pps) > 0){
			$data['pps'] = $pps;
		}
		if(count($pps_spouse) > 0){
			$data['pps_spouse'] = $pps_spouse;
		} 
		if(count($pps_sum) > 0){
			$data['pps_summary'] = $pps_sum;
		}*/
		
		echo json_encode($data);		
	}
	
	
	public function get_pps_detail_personel()
	{
		$ppsid = $this->input->post('ppsid');
					
		$result = $this->DBmodel->fetch_pps_personel_sugar($ppsid);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
	public function get_pps_detail_spouse()
	{
		$ppsid = $this->input->post('ppsid');
					
		$result = $this->DBmodel->fetch_pps_spouse_sugar($ppsid);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
	public function get_pps_detail_state()
	{
		$ppsid = $this->input->post('ppsid');
					
		$result = $this->DBmodel->fetch_pps_state_personel_sugar($ppsid);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
	public function general_pps_detail()
	{
		$ppsid = $this->input->post('ppsid');
					
		$result = $this->DBmodel->fetch_general_pps_detail($ppsid);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
	public function pps_notes()
	{
		$ppsid = $this->input->post('ppsid');
					
		$result = $this->DBmodel->get_pps_notes_sugar($ppsid);
		
		if($result)
		{
			echo json_encode($result);   
		}
	}
	
}