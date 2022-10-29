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
        $this->load->model(array('admin_model'));
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

        print_r($complaintList);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");
    }

    public function getcomplaint($id)
    {
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $this->access_denied();
        }

        $complaintList=$this->admin_model->getComplaintList($id);

        print_r($complaintList);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");
    }

    /************************************************************** USER SECTION ************************************************************************/

    public function userList(){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        } 
        
        $userlist=$this->admin_model->getUserList();

        print_r($userlist);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

    }


    public function getuser($id){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        } 
        
        $userlist=$this->admin_model->getUserList($id);

        print_r($userlist);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

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