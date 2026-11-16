<?php
// use CodeIgniter\API\ResponseTrait;
// use CodeIgniter\RESTful\ResourceController;
// use Exception;
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control");

class AccountStatusData extends CI_Controller 
{
  
	public function __construct()
	{
		parent::__construct();		
		
		$this->db2 = $this->load->database('sugartax', TRUE);
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		//$this->db_sugar04 = $this->load->database('ctr_sugar04', TRUE);
		
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');		
	}

	public function index()
	{
		$CaseID = $this->request->getVar("caseid");
        // $Type = $this->request->getVar("type");            
        
        if($CaseID != '')
        {                                   
            $response = $this->get_account_data();
            // return $this->respondCreated($response);
        }
	}
	public function get_account_data()
	{
		// $case_id= $this->input->post('caseid');	
		// $case_id= $this->input->get('caseid');	

		// $data = [
		//     'success' => true,
		//     'id' => 123,
		//     'case_id' => $case_id,
		// ];

		// header('Content-Type: application/json');
  		//   	echo json_encode( $data );
		
		$account_stat_data = ''; 
		
		//	$lan
		$case_id= $this->input->post('caseid');	
		
		// $data['is_admin'] = $this->DBmodel->checkIfAdmin();		
		//data from sugartax server
		if($this->db2)
		{			
			$account_stat_data = $this->DBmodel->fetch_caseid_data($case_id);						
		}		
		
		//print_r($reported_inc);
		if(is_array($account_stat_data) && count($account_stat_data) > 0)
		{
			$data['account_stat_data'] = $account_stat_data; 
		}
		else{
			$data['account_stat_data'] = [];
		}
		header('Content-Type: application/json');
    	echo json_encode( $data );
		// return $data;	
		// echo json_encode($data);
	}
}