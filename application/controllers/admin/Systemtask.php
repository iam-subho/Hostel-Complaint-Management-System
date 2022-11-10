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
        //$this->load->library('my_form_validation');
        $this->load->library('customlib');
        $this->load->model(array('admin_model','systemtask_model'));
    }


    /*******************************************************  Roles **********************************************************************/
   public function getroleList() {
    $this->session->set_userdata('top_menu', 'settings');
    $this->session->set_userdata('sub_menu', 'systemtask/getroleList');

    if (!$this->rbac->hasPrivilege('roles', 'can_view')) {
        $this->access_denied();
    }

    $roleList=$this->systemtask_model->getRolesList();
    $data['roles']=$roleList;
    $this->load->view("layout/header");
    $this->load->view("admin/roleslist",$data);
    $this->load->view("layout/footer");


   }


   public function rolesadd(){
    if (!$this->rbac->hasPrivilege('roles', 'can_add')) {
        $array = array('status' =>0, 'error' =>'You dont have permission to add role');
    }else{
        if (!$this->rbac->hasPrivilege('roles', 'can_add')) {
            $array = array('status' =>0, 'error' =>'','errorP' =>'You dont have permission');
        }else{
         $this->form_validation->set_rules('role', 'Roles', 'trim|required|xss_clean|is_unique[roles.role_name]');
         if($this->form_validation->run() == FALSE){
          $array = array('status' =>0, 'error' => $this->form_validation->error_array(),'errorP'=>'Role Add Failed');
         }else{
          $data = $this->input->post('role', TRUE);
          //print_r($data);
          $insarr=array('role_name' =>$data);
          $this->db->insert('roles',$insarr);
          $array = array('status' =>1, 'error' => '');
         }
      }
    }
        echo json_encode($array);

   }

   public function roledelete(){
    if (!$this->rbac->hasPrivilege('roles', 'can_delete')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to delete');
        
    }else{
        $this->db->where('role_id',$this->input->post('roleid'));
        $this->db->delete('roles');
        $array=array('status' =>1, 'error' =>'');
    }
      echo json_encode($array);

    }

    public function roleactivestatus(){
        if (!$this->rbac->hasPrivilege('roles', 'can_edit')) {
            $array=array('status' =>0, 'error' =>'You are not allowed change');
            
        }
        else{
        $roleid=($this->input->post('roleid'));
        $stat=$this->input->post('status');
        $this->db->where('role_id',$roleid)->update('roles',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }

   /*********************************************************** Permission Group Name ******************************************************/


   public function permissionadd(){
    if (!$this->rbac->hasPrivilege('permission', 'can_add') && !$this->rbac->hasPrivilege('permission', 'can_view')) {
        $this->access_denied();
    }

    $this->session->set_userdata('top_menu', 'settings');
    $this->session->set_userdata('sub_menu', 'systemtask/permissionadd');

    if($this->input->server('REQUEST_METHOD') === 'GET'){
        if (!$this->rbac->hasPrivilege('permission', 'can_view')) {
            $this->access_denied();
        }
        $data['list']=$this->systemtask_model->getpermissionshortcodelist();
        $this->load->view("layout/header");
        $this->load->view("admin/addpermission",$data);
        $this->load->view("layout/footer");

     }else{
        if (!$this->rbac->hasPrivilege('permission', 'can_add')) {
            $array = array('status' =>0, 'error' =>'You dont have permission to Add');
        }
         $this->form_validation->set_rules('permission', 'Permission', 'trim|alpha_dash|xss_clean|required|is_unique[permissions.perm_short_code]');
           if ($this->form_validation->run() == FALSE){
            $array = array('status' =>0, 'error' => validation_errors());
          }else{
            $insarr=array('perm_short_code' =>$this->input->post('permission'));
            $this->db->insert('permissions',$insarr);
            $array = array('status' =>1, 'error' =>''); 
        }
        echo json_encode($array);
      }

    }

    public function permissionshortcodedelete(){
        if (!$this->rbac->hasPrivilege('permission', 'can_delete')) {
            $array = array('status' =>0, 'error' =>'You dont have permission to Delete');
            echo json_encode($array);die();
        }
        $permid=$this->input->post('permissionid',TRUE); 
        $this->db->where('perm_id',$permid);
        $this->db->delete('permissions');

        $this->db->where('perm_cat_id',$permid);
        $this->db->delete('roles_permissions');
        $array = array('status' =>1, 'error' =>''); 
        echo json_encode($array);
    }


    /***************************************************** PERMISSION ASSIGNMENT *******************************/

    public function getPermissionList($id){
        if (!$this->rbac->hasPrivilege('permissionassign', 'can_view')) {
            $this->access_denied();
        }
        $this->session->set_userdata('top_menu', 'settings');
        $this->session->set_userdata('sub_menu', 'systemtask/getroleList');
    
        $complaintList=$this->systemtask_model->getPermissionList($id);  

        $data['list']=$complaintList;
        $data['roleid']=$id;
    
        $this->load->view("layout/header");
        $this->load->view("admin/permissionList",$data);
        $this->load->view("layout/footer");
    
       }

    public function permisssionassign(){
        if (!$this->rbac->hasPrivilege('permissionassign', 'can_add')) {
            $this->access_denied();
        }
        $roleid= $this->input->post('roleid',TRUE);
        $permidlist= $this->input->post('permid',TRUE);
        //print_r($permidlist);die();
        foreach($permidlist as $permid){
            $canview=$this->input->post('can_view'.$permid,TRUE);
            $canadd=$this->input->post('can_add'.$permid,TRUE);
            $canedit= $this->input->post('can_edit'.$permid,TRUE);
            $candelete=$this->input->post('can_delete'.$permid,TRUE);
        $data['can_view']=isset($canview)?1:0;
        $data['can_add']= isset($canadd)?1:0;
        $data['can_edit']=isset($canedit)?1:0;
        $data['can_delete']= isset($candelete)?1:0;
        //print_r($data);die();
        $this->systemtask_model->assignpermission($permid,$roleid,$data);
        }  
        redirect('admin/systemtask/getPermissionList/'.$roleid);
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
   if($this->input->server('REQUEST_METHOD') === 'GET'){
    if (!$this->rbac->hasPrivilege('complaint_type', 'can_view')) {
        $this->access_denied();
    }

    $this->session->set_userdata('top_menu', 'settings');
    $this->session->set_userdata('sub_menu', 'systemtask/complainttypelist');

       $complaintList=$this->systemtask_model->getComplaintTypeList();//print_r($buildinglist);die();
       $data['dept']=$this->systemtask_model->getWorkerType();
       $data['complaintlist'] =$complaintList;
       $this->load->view("layout/header");
       $this->load->view("admin/complainttypelist",$data);
       $this->load->view("layout/footer");
    }else{
        if (!$this->rbac->hasPrivilege('complaint_type', 'can_add')) {
            $array = array('status' =>0, 'errorP' =>'You dont have permission','error' =>'');
        }else{
       $this->form_validation->set_rules('name', 'Complaint Type', 'trim|required|xss_clean|is_unique[complaint_type.typename]');
       $this->form_validation->set_rules('category', 'Category', 'trim|required|numeric');
       $this->form_validation->set_rules('department', 'Department', 'trim|required|numeric');
       $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
       if ($this->form_validation->run() == FALSE){
           $array = array('status' =>0,'errorP' =>'Complaint Type Add Failed', 'error' => $this->form_validation->error_array());            
       }else{
           $insarr=array(
            'typename'  =>$this->input->post('name'),
            'personal'      =>$this->input->post('category'),
            'paymentAmount' =>$this->input->post('amount'),
            'handler_id'    =>$this->input->post('department'),
            'status'      =>1,

           );
           $this->db->insert('complaint_type',$insarr);
           $array = array('status' =>1, 'error' =>'');            
         }
       }

       echo json_encode($array);

      }
    }

    public function complainttypeactivestatus(){
        if (!$this->rbac->hasPrivilege('complaint_type', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $typeid=($this->input->post('userid'));
        $stat=$this->input->post('status');
        $this->db->where('typeid',$typeid)->update('complaint_type',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }

    public function complainttypedelete(){
    if (!$this->rbac->hasPrivilege('complaint_type', 'can_delete')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to delete');
        
    }else{
        $this->db->where('typeid',$this->input->post('typeid'));
        $this->db->delete('complaint_type');
        $array=array('status' =>1, 'error' =>'');
    }
      echo json_encode($array);

    }

   /******************************************************************* BUILDING ***************************************************/

   public function getbuildinglist(){
     //this function used for fetching list of buildings as well as adding new buildings
     //to the database.

     $this->session->set_userdata('top_menu', 'settings');
     $this->session->set_userdata('sub_menu', 'systemtask/getbuildinglist');

    if($this->input->server('REQUEST_METHOD') === 'GET'){
        if (!$this->rbac->hasPrivilege('building', 'can_view')) {
            $this->access_denied();
        }

        $buildinglist=$this->systemtask_model->getBuildingList();//print_r($buildinglist);die();
        $data['buildinglist'] =$buildinglist;
        $this->load->view("layout/header");
        $this->load->view("admin/buidlinglist",$data);
        $this->load->view("layout/footer");
     }else{
        if (!$this->rbac->hasPrivilege('building', 'can_add')) {
            $array = array('status' =>0, 'error' =>'','errorP' =>'You dont have permission');
        }else{
        $this->form_validation->set_rules('name', 'Building Name', 'trim|required|is_unique[building.buildingname]');
        if ($this->form_validation->run() == FALSE){
            $array = array('status' =>0, 'error' => validation_errors(),'errorP' =>'Building Add Failed');            
        }else{
            $insarr=array('buildingname' =>$this->input->post('name'),'status'=>1);
            $this->db->insert('building',$insarr);
            $array = array('status' =>1, 'error' =>'');            
         }
       }

        echo json_encode($array);

     }
   }

   public function buildingdelete(){
    if (!$this->rbac->hasPrivilege('building', 'can_delete')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to delete');
        
    }else{
        $this->db->where('buildingid',$this->input->post('buildingid'));
        $this->db->delete('building');
        $array=array('status' =>1, 'error' =>'');
    }
      echo json_encode($array);

    }

    public function buildingactivestatus(){
        if (!$this->rbac->hasPrivilege('building', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $typeid=($this->input->post('buildingid'));
        $stat=$this->input->post('status');
        $this->db->where('buildingid',$typeid)->update('building',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }

     /***************************************************** DEPARTMENT ***********************************************************************/

     public function departmentlist(){
        if (!$this->rbac->hasPrivilege('department', 'can_view')) {
            $this->access_denied();
        }

        $this->session->set_userdata('top_menu', 'settings');
        $this->session->set_userdata('sub_menu', 'systemtask/department');

        $data['departmentlist'] = $this->systemtask_model->departmentlist();

        $this->load->view('layout/header');
        $this->load->view('admin/departmentlist',$data);
        $this->load->view('layout/footer');
     }

     public function adddepartment(){
        if (!$this->rbac->hasPrivilege('department', 'can_add')) {
            $array = array('status' =>0, 'error' =>'','errorP' =>'You dont have permission');
        }else{
        $this->form_validation->set_rules('name', 'Department Name', 'trim|required|is_unique[worker_type.type_name]');
        if ($this->form_validation->run() == FALSE){
            $array = array('status' =>0, 'error' => validation_errors(),'errorP' =>'Department Add Failed');            
        }else{
            $insarr=array('type_name' =>$this->input->post('name'),'status'=>1);
            $this->db->insert('worker_type',$insarr);
            $array = array('status' =>1, 'error' =>'');            
         }
       }

        echo json_encode($array); 
     }

     
   public function departmentdelete(){
    if (!$this->rbac->hasPrivilege('department', 'can_delete')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to delete');
        
    }else{
        $this->db->where('worker_type_id',$this->input->post('deptid'));
        $this->db->delete('worker_type');
        $array=array('status' =>1, 'error' =>'');
    }
      echo json_encode($array);

    }

    public function departmentactivestatus(){
        if (!$this->rbac->hasPrivilege('department', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $typeid=($this->input->post('deptid'));
        $stat=$this->input->post('status');
        $this->db->where('worker_type_id',$typeid)->update('worker_type',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }

    /***************************************************** NOTIFICATION SETTINGS    ************************************************************/

    public function notification(){
        //function add view and notification type and their status in the system
        $this->session->set_userdata('top_menu', 'settings');
        $this->session->set_userdata('sub_menu', 'systemtask/notification');
   
       if($this->input->server('REQUEST_METHOD') === 'GET'){
           if (!$this->rbac->hasPrivilege('building', 'can_view')) {
               $this->access_denied();
           }
   
           $notificationlist=$this->systemtask_model->getNotificationList();//print_r($buildinglist);die();
           $data['notificationlist'] =$notificationlist;
           $this->load->view("layout/header");
           $this->load->view("admin/notificationlist",$data);
           $this->load->view("layout/footer");
        } 
    }

    public function notificationactivestatus(){
        if (!$this->rbac->hasPrivilege('notification', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $typeid=($this->input->post('notificationid'));
        $stat=$this->input->post('status');
        $this->db->where('notid',$typeid)->update('emailnotification',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }
    /****************************************************SYSTEM CONFIGURATION ********************************************************************/

    public function systemconfigure(){

        $this->session->set_userdata('top_menu', 'settings');
        $this->session->set_userdata('sub_menu', 'systemtask/systemconfigure');
   
       if($this->input->server('REQUEST_METHOD') === 'GET'){
           if (!$this->rbac->hasPrivilege('systemconfigure', 'can_view')) {
               $this->access_denied();
           }
   
           $system=$this->customlib->getSystemInfo();//print_r($system);die();
           $data['system'] =$system;
           $this->load->view("layout/header");
           $this->load->view("admin/systemconfiguration",$data);
           $this->load->view("layout/footer");
        } 

    }

    public function systemconfigureupdate(){
        if (!$this->rbac->hasPrivilege('systemconfigure', 'can_edit')) {
            $this->access_denied();
        }  
        $insertArray=$this->input->post(NULL,TRUE);
        $this->db->where('id',1)->update('systemsetting',$insertArray);
        $system=$this->customlib->getSystemInfo();//print_r($system);die();
        $data['system'] =$system;
        $this->session->set_flashdata('flashSuccess','System Settings Update Success');
        $this->load->view("layout/header");
        $this->load->view("admin/systemconfiguration",$data);
        $this->load->view("layout/footer");
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