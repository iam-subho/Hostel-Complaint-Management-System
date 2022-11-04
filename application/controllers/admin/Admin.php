<?php 
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} 
Class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helpers('form');
        $this->load->library('form_validation');
        $this->load->model(array('admin_model','systemtask_model'));
    }

    /*********************************************************** DASHBOARD **********************************************************/

    public function dashboard()
    {
        if (!$this->rbac->hasPrivilege('dashboard_view', 'can_view')) {
            $this->access_denied();
        }

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");
    }



    public function dashboard_post()
    {
        if (!$this->rbac->hasPrivilege('dashboard_view', 'can_view')) {
            $this->access_denied();
        }


        $this->load->view("welcome_message");

    }

    /***********************************************************COMPLAINT SECTION***********************************************************************/

    public function complaintList()
    {
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $this->access_denied();
        }

        $complaintList=$this->admin_model->getComplaintList();
        $status=$this->systemtask_model->getComplaintstatusList();

        $data['complaintlist']=$complaintList;
        $data['statuslist']=$status;

        $this->load->view("layout/header");
        $this->load->view("admin/complaintlist",$data);
        $this->load->view("layout/footer");
    }

    public function filterbasecomplaintlist(){
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $this->access_denied();
        }
        //this function filter complaint list based on the status of the complaint 
        $status=$this->input->post('status',TRUE);
        $complaintList=$this->admin_model->getComplaintList(null,null,$status);
        $data['complaintlist']=$complaintList;
        $html=$this->load->view("admin/complaintlisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
        echo json_encode($array);
    }

    public function getcomplaint($ide,$comp)
    {
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $this->access_denied();
        }

        $id=base64_decode($ide);

        $complaintList=$this->admin_model->getComplaintList($id);//getting details of single complaint list
        $history=$this->admin_model->getComplaintHistory($id); //getting details of single complaint history
        $extraPayment=$this->admin_model->getextraPayment($id); //getting details of single complaint extra Payment
        $workers=$this->admin_model->getSpecifiTyperWorker($complaintList['handler_id']); //getting list of workers based on the complaint type

        //print_r($complaintList);die();
        $data['history']=$history;
        $data['extraPayment']=$extraPayment;
        $data['compNo']=base64_decode($comp);
        $data['complaint']=$complaintList;
        $data['workerlist']=$workers;

        $this->load->view("layout/header");
        $this->load->view("admin/complaintDetails",$data);
        $this->load->view("layout/footer");
    }

    public function assignstaff(){
        if (!$this->rbac->hasPrivilege('complaint', 'can_edit')) {
            $this->access_denied();
        }

        $staffid=$this->input->post('staffid');
        $comp=($this->input->post('compid'));
        $this->customlib->insertinhistory($comp,'Worker Assigned');
        $array=$this->admin_model->workerassign($comp,$staffid);
        
        echo json_encode($array);
    }

    public function ajaxextrapayment(){
        if (!$this->rbac->hasPrivilege('complaint_extra_payment', 'can_add')) {
            $this->access_denied();
        }
        $note=$this->input->post('note',TRUE);
        $amount=$this->input->post('amount',TRUE);
        $compid=$this->input->post('compid',TRUE);

        $inserArray=array(
            'note' =>$note,
            'complaintid' =>$compid,
            'amount' =>$amount,
            'createDate'=>time(),
        );
        $this->db->insert('extrapayment',$inserArray);
        $this->customlib->insertinhistory($compid,'Extrapayment Assigned');
        $array = array('status' => 1, 'error' => '');
        echo json_encode($array);
    }

    /************************************************************** USER SECTION ************************************************************************/

    public function userList(){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        } 
        
        $userlist=$this->admin_model->getUserList();
        $data['userlist']=$userlist;

        $this->load->view("layout/header");
        $this->load->view("admin/userlist",$data);
        $this->load->view("layout/footer");

    }

    public function filterbaseuserslist(){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        } 

        
        $status=$this->input->post('status',TRUE);
        $complaintList=$this->admin_model->getUserList(null,$status);
        $data['userlist']=$complaintList;
        $html=$this->load->view("admin/userlisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
        echo json_encode($array);   
    }


    public function getuser($ide,$mob){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        }

        if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
            redirect('admin/admin/userList');
        }
        
        $id=base64_decode($ide);
        
        $userlist=$this->admin_model->getUserList($id);

        print_r($userlist);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

    }

    public function useractivestatus(){
        if (!$this->rbac->hasPrivilege('users', 'can_edit')) {
            $this->access_denied();
        }

        $userid=$this->input->post('userid',TRUE); 
        $status=$this->input->post('status',TRUE);
        $uparray=array(
        'status'=>$status,
        );
        $this->db->where('userid',$userid)->update('users',$uparray);
        $array = array('status' =>1, 'error' =>'');
        echo json_encode($array);
    }




    /*************************************************************** STAFF SECTION  *****************************************************************/


    public function staffList(){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        }    

        $staffList=$this->admin_model->getStaffList();

        print_r($staffList);die();


        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

    }

    public function getstaff($id){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        }    

        $staffList=$this->admin_model->getStaffList($id);

        print_r($staffList);die();


        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

    }

    public function addstaff(){
        if (!$this->rbac->hasPrivilege('staff', 'can_add')) {
            $this->access_denied();
        }

        if($this->input->server('REQUEST_METHOD') === 'GET'){
            $this->load->view("layout/header");
            $this->load->view("admin/addstaff");
            $this->load->view("layout/footer");    
        }else{
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[staff.email]');
            if ($this->form_validation->run() == FALSE){
                $this->load->view("layout/header");
                $this->load->view("admin/addstaff");
                $this->load->view("layout/footer");
           }else{
             $data = $this->input->post(NULL, TRUE);
             print_r($data);
           }
        }

    }


    /***************************************************** COMMON ******************************************************************************** */


    public function unauthorized(){
        $this->load->view("admin/unauthorized");  
    }

    function access_denied() {
        redirect('admin/admin/unauthorized');
    }



}



?>