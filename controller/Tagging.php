<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tagging extends CI_Controller {
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->db_sugar = $this->load->database('ctr_sugar', TRUE);
		$this->sugar_url = $this->config->item('sugar_url'); 
		$this->load->model('DBmodel');		
        $this->load->model('TaggingModel');  
	}
	
	public function records()
	{	
		$user_type = $this->session->userdata('user_typeid');	
		session_write_close();	
			
		$data['title'] = "Tagging Tab"; 
		
		$status = '';
		$subStatus = '';
		$screenDateFrom = '';
		$screenDateTo = '';
		$lastDateFrom = '';
		$lastDateTo = '';
//		$leadSrc = '';
		$language = '';
//		$qualified = '';
		$leadsAssign = '';
		$tags = '';
		$lead_level = '';
		$billing_status = '';
		
		$tagging_data = $this->TaggingModel->getTaggingRecords($status,$subStatus,$screenDateFrom,$screenDateTo,$lastDateFrom,$lastDateTo,$language,$leadsAssign,$tags,$lead_level,$billing_status);	
		
		$user_list = $this->TaggingModel->getAllUsers();
		//$batch_list = $this->TaggingModel->getBatchIdDropdown();
		$status_list = $this->TaggingModel->getStatusDropdown();
			
		if(is_array($tagging_data) && count($tagging_data) > 0)
		{
			$data['tagging_data'] = $tagging_data;				
		}
		else 
		{			
			$data['tagging_data'] = 0;
		}
		
		$data['user_list'] = $user_list;
		$data['status_list'] = $status_list;
//		$data['batch_list'] = $batch_list;			
		$data['user_role'] = $this->DBmodel->checkUserRoleAssignment();
		$data['write_assignment_role'] = $this->DBmodel->checkUserRoleWritingAssignment();
		$data['pps_assign_role'] = $this->DBmodel->checkInvestigationManagerRole();
		$data['back_office_admin'] = $this->DBmodel->checkBackOfficeAdminRole();
		$data['tag_list'] = $this->TaggingModel->getTagList();
		$data['no_header'] = 0;
		
		$this->load->view('common/header' ,$data);
		$this->load->view('TaggingView',$data);		
		$this->load->view('common/footer');
	}	
    
	public function addTags()
    {
        $leadid_array = $this->input->post('leadid_array');
        $selectedTag = $this->input->post('selectTag');

        $result = $this->TaggingModel->addLeadTag($leadid_array, $selectedTag);
		
        if($result)
        {
			echo json_encode($result);
        }
	}

    public function removeTag()
    {
        $lead_array = $this->input->post('leadid_array');
        $selectedTagToRemove = $this->input->post('selectTagToRemove');
        //print_r( $lead_array );
        $result = $this->TaggingModel->removeTags($lead_array,$selectedTagToRemove); //$assign_user_id,  
		
        if($result)
        {
			echo json_encode($result);
        }
	}
     
	public function getFilteredData()
    {
        $statusByFilter = $this->input->post('status');
        $subStatusByFilter = $this->input->post('subStatus');
        $screenDateFromByFilter = $this->input->post('screenDateFrom');
        $screenDateToByFilter = $this->input->post('screenDateTo');
        $lastEnqDtFromByFilter = $this->input->post('lastEnqDateFrom');
        $lastEnqDtToByFilter = $this->input->post('lastEnqDateTo');
        $selLangByFilter = $this->input->post('language_filter');
        $leadAssignByFilter = $this->input->post('assign_to_filter');
        $TagsByFilter = $this->input->post('tags');
        $lead_levelByFilter = $this->input->post('lead_level');
		$billing_statusByFilter = $this->input->post('billing_status');
        $tagging_data = $this->TaggingModel->getTaggingRecords($statusByFilter,$subStatusByFilter,$screenDateFromByFilter,$screenDateToByFilter,$lastEnqDtFromByFilter,$lastEnqDtToByFilter,$selLangByFilter,$leadAssignByFilter,$TagsByFilter,$lead_levelByFilter,$billing_statusByFilter);

		//$tagging_data = $this->TaggingModel->getTaggingRecords($status,$subStatus,$screenDate,$lastDate,$language,$leadsAssign,$tags);	
		
			
		if(is_array($tagging_data) && count($tagging_data) > 0)
		{
			echo json_encode($tagging_data);			
		}
		else 
		{			
			return 0;
		}

        /*if($result)
        { print '-------------------';
            echo json_encode($result);
        }*/
    }

	public function selectedLeadStatus()
	{
		$status = $this->input->post('statusChange');
		$result = $this->TaggingModel->subStatusDropdown($status);

		if($result)
        {
            echo $result;
        }
	}
}