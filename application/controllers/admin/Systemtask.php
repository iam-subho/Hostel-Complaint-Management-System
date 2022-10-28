<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} 

Class Systemtask extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helpers('form');
        $this->load->library('form_validation');
        $this->load->model(array('admin_model','systemtask_model'));
    }


    /*******************************************************  Roles **********************************************************************/
   public function getroleList() 
   {

    if (!$this->rbac->hasPrivilege('roles', 'can_view')) {
        $this->access_denied();
    }

    $complaintList=$this->systemtask_model->getRolesList();
    print_r($complaintList);die();

    $this->load->view("layout/header");
    $this->load->view("dashboard_view");
    $this->load->view("layout/footer");


   }


   public function rolesadd(){
    if (!$this->rbac->hasPrivilege('roles', 'can_add')) {
        $this->access_denied();
    }

    if($this->input->server('REQUEST_METHOD') === 'GET'){
        $this->load->view("layout/header");
        $this->load->view("admin/addrole");
        $this->load->view("layout/footer");

    }else{
     $this->form_validation->set_rules('role', 'Roles', 'trim|required|is_unique[roles.role_name]');
     if ($this->form_validation->run() == FALSE){
        $this->load->view("layout/header");
        $this->load->view("admin/addrole");
        $this->load->view("layout/footer");
     }else{
       $data = $this->input->post(NULL, TRUE);
       print_r($data);
    }
   }


   }

   /*********************************************************** Permission ******************************************************/

   public function getPermissionList($id){
    if (!$this->rbac->hasPrivilege('permission', 'can_view')) {
        $this->access_denied();
    }

    $complaintList=$this->systemtask_model->getPermissionList($id);
    //print_r($complaintList);die();

    $data['list']=$complaintList;

    $this->load->view("layout/header");
    $this->load->view("admin/permissionList",$data);
    $this->load->view("layout/footer");

   }

   public function permissionadd(){
    if (!$this->rbac->hasPrivilege('permission', 'can_add')) {
        $this->access_denied();
    }

    if($this->input->server('REQUEST_METHOD') === 'GET'){
        $this->load->view("layout/header");
        $this->load->view("admin/addpermission");
        $this->load->view("layout/footer");

    }else{
     $this->form_validation->set_rules('permission', 'Permission', 'trim|required|is_unique[permissions.perm_short_code]');
     if ($this->form_validation->run() == FALSE){
        $this->load->view("layout/header");
        $this->load->view("admin/addpermission");
        $this->load->view("layout/footer");
     }else{
       $data = $this->input->post(NULL, TRUE);
       print_r($data);
    }
   }


   }

   /******************************************************************** Worker  *************************************************************/

   public function worker_type_list(){
   
    if (!$this->rbac->hasPrivilege('worker', 'can_view')) {
        $this->access_denied();
    }

    $complaintList=$this->systemtask_model->getWorkerType();
    print_r($complaintList);die();

    $this->load->view("layout/header");
    $this->load->view("dashboard_view");
    $this->load->view("layout/footer");


   }

   public function workeradd(){
    if (!$this->rbac->hasPrivilege('worker', 'can_add')) {
        $this->access_denied();
    }

    $this->form_validation->set_rules('typename', 'Typename', 'trim|required|is_unique[worker_type.type_name]');
    if ($this->form_validation->run() == FALSE){
        $this->load->view("layout/header");
        $this->load->view("admin/addstaff");
        $this->load->view("layout/footer");
   }else{
     $data = $this->input->post(NULL, TRUE);
     print_r($data);
   }


   }

   /**************************************************************** COMPLAINT STATUS ****************************************************************/

   public function complaintstatuslist(){
   
    if (!$this->rbac->hasPrivilege('complaintStatus', 'can_view')) {
        $this->access_denied();
    }

    $complaintList=$this->systemtask_model->getComplaintstatusList();
    print_r($complaintList);die();

    $this->load->view("layout/header");
    $this->load->view("dashboard_view");
    $this->load->view("layout/footer");


   }

   public function complaintstatusadd(){
    if (!$this->rbac->hasPrivilege('complaintStatus', 'can_add')) {
        $this->access_denied();
    }

    $this->form_validation->set_rules('status', 'Status', 'trim|required|is_unique[complaintstatus.status]');
    if ($this->form_validation->run() == FALSE){
        $this->load->view("layout/header");
        $this->load->view("admin/addstaff");
        $this->load->view("layout/footer");
   }else{
     $data = $this->input->post(NULL, TRUE);
     print_r($data);
   }


   }

   /*********************************************************** COMPLAINT TYPE ************************************************/


   public function complainttypelist(){
   
    if (!$this->rbac->hasPrivilege('complaint_type', 'can_view')) {
        $this->access_denied();
    }

    $complaintList=$this->systemtask_model->getComplaintTypeList();
    print_r($complaintList);die();

    $this->load->view("layout/header");
    $this->load->view("dashboard_view");
    $this->load->view("layout/footer");


   }

   public function complainttypeadd(){
    if (!$this->rbac->hasPrivilege('complaint_type', 'can_add')) {
        $this->access_denied();
    }

    $this->form_validation->set_rules('type', 'Complaint Typename', 'trim|required|is_unique[complaint_type.typename]');
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
?>