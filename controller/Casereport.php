<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Casereport extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->db2 = $this->load->database('sugartax', TRUE);
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
			$data['title'] = "CaseReview | CaseReport"; 
			$case_id= $this->input->get('id');
			$data['id'] = $case_id;
			$data['status'] = $this->DBmodel->statusCheck($case_id);
			$this->load->view('common/header_report' ,$data);
			$this->load->view('casereport',$data);
		}
	}
	
	public function check_status()
	{	
		$data['title'] = "CaseReview | CaseReport"; 
		$case_id= $this->input->get('id');
		$data['id'] = $case_id;
		$data['status'] = $this->DBmodel->statusCheck($case_id);
		$this->load->view('common/header_report' ,$data);
		$this->load->view('casereport',$data);		
	}
	
	public function get_data()
	{
		$case_id= $this->input->post('caseid');	
		
		$notes = $this->DBmodel->get_notes_all($case_id);
		
		$sum = $this->DBmodel->general_caseid_details($case_id);
		$aggregate = $this->DBmodel->caseid_aggregate_details($case_id);
		
		$data['checkpps'] = $this->DBmodel->check_pps($case_id);
		$data['checkpps_sp'] = $this->DBmodel->check_pps_spouse($case_id);
		//$data['checkpps_extra'] = $this->DBmodel->check_extra_pps($case_id);
		//$data['checkpps_statepers'] = $this->DBmodel->check_pps_state_pers($case_id);
		
		$pps = $this->DBmodel->fetch_pps_personel_sugar($data['checkpps']);
		$pps_spouse = $this->DBmodel->fetch_pps_spouse_sugar($data['checkpps_sp']);			
		$pps_notes = $this->DBmodel->get_pps_notes_sugar($data['checkpps']);
		$pps_notes_spouse = $this->DBmodel->get_pps_notes_spouse_sugar($data['checkpps_sp']);
		$pps_sum = $this->DBmodel->fetch_pps_summary($case_id);	
		$assets = $this->DBmodel->assets_details($case_id);	
		//$analysis = $this->DBmodel->fetch_analysis($case_id);
		$reported_inc = $this->DBmodel->fetch_reported_income($case_id);
		$finance = $this->DBmodel->fetch_finance_info($case_id); 
		
		$fin_overview = $this->DBmodel->fin_overview_details($case_id);
		$additional_expense = $this->DBmodel->additional_monthly_expense($case_id);
		$sugar_irs_standards = $this->DBmodel->sugar_irs_standards($case_id);
		$vehicles = $this->DBmodel->vehicle_expense($case_id);
		$real_estate = $this->DBmodel->real_estate_details($case_id);
		$data['additional_income'] = $this->DBmodel->additional_income_details($case_id);
		$data['asset_bank_acc'] = $this->DBmodel->assets_bank_amount($case_id);
		$services = $this->DBmodel->get_services($case_id);		
		$data['status'] = $this->DBmodel->get_case_status($case_id);
		
		$account_stat_data = $this->DBmodel->fetch_caseid_data($case_id);
		
		if(is_array($account_stat_data) && count($account_stat_data) > 0)
		{
			$data['account_stat_data'] = $account_stat_data; 
		}
		if(count($sum) > 0){
			$data['summary'] = $sum;
		}
		if(count($aggregate) > 0){
			$data['aggregate'] = $aggregate;
		}		
		if(count($notes) > 0){
			$data['notes'] = $notes;
		} 
		
		if(is_array($pps_notes) && count($pps_notes) > 0){
			$data['pps_notes'] = $pps_notes;
		}
		if(is_array($pps_notes_spouse) && count($pps_notes_spouse) > 0){
			$data['pps_notes_spouse'] = $pps_notes_spouse;
		}
		
		if(is_array($pps) && count($pps) > 0){
			$data['pps'] = $pps;
		}
		if(is_array($pps_spouse) && count($pps_spouse) > 0){
			$data['pps_spouse'] = $pps_spouse;
		}  
		/*if(is_array($analysis) && count($analysis) > 0){
			$data['analysis'] = $analysis; 
		}*/
		if(is_array($assets) && count($assets) > 0){
			$data['assets'] = $assets;
		}
		if(is_array($fin_overview) && count($fin_overview) > 0){
			$data['fin_overview'] = $fin_overview;
		} 
		if(is_array($additional_expense) && count($additional_expense) > 0){
			$data['additional_expense'] = $additional_expense;
		} 		
		if(is_array($vehicles) && count($vehicles) > 0){
			$data['vehicles'] = $vehicles;
		} 
		if(is_array($real_estate) && count($real_estate) > 0){
			$data['real_estate'] = $real_estate;
		}
		if(is_array($sugar_irs_standards) && count($sugar_irs_standards) > 0){
			
			$data['sugar_irs_standards'] = $sugar_irs_standards;
		}
		if($reported_inc != 0 && is_array($reported_inc)  && count($reported_inc) > 0){
			$data['reported_inc'] = $reported_inc;
		}
		if($services != 0 && is_array($services) && count($services) > 0){
			$data['services'] = $services;
		}
		if(is_array($pps_sum) && count($pps_sum) > 0){
			$data['pps_summary'] = $pps_sum;
		}
		if(is_array($finance) && count($finance) > 0){
			$data['finance'] = $finance;
		}
		//print_r($data['reported_inc']);
		
		echo json_encode($data);		
	}
}