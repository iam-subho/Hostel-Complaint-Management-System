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
        $this->load->library('email');
        $this->load->library('customlib');
        $this->load->model(array('admin_model','systemtask_model','userpanel_model'));
        $this->staff=$this->session->userdata('admin');
        $this->load->library('emailsend');
    }

    /*********************************************************** DASHBOARD **********************************************************/

    public function dashboard()
    {
        if (!$this->rbac->hasPrivilege('dashboard_view', 'can_view')) {
            $this->access_denied();
        }

        $this->session->set_userdata('top_menu', 'dashboard');
        $this->session->set_userdata('sub_menu', '');

        $staff=$this->staff;
        $staffid=$staff['id'];
        if($staff['level'] ==2){
            $complaintlist=$this->admin_model->getComplaintList(null,$staffid,null,5);
            $data['complaintlist']=$complaintlist;
            $data['total']=count($this->admin_model->getComplaintList(null,$staffid,null,null));
            $data['pending']=count($this->admin_model->getComplaintList(null,$staffid,1,null));
            $data['closed']=count($this->admin_model->getComplaintList(null,$staffid,3,null));
            
        }else{
        $complaintlist=$this->admin_model->getComplaintList(null,null,null,5);
        $data['complaintlist']=$complaintlist;
        $data['total']=count($this->admin_model->getComplaintList(null,null,null,null));
        $data['pending']=count($this->admin_model->getComplaintList(null,null,1,null));
        $data['closed']=count($this->admin_model->getComplaintList(null,null,3,null));
        }
        $data['levelaccess']=$staff['level'];
        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view",$data);
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

        $this->session->set_userdata('top_menu', 'complaintList');
        $this->session->set_userdata('sub_menu', '');

        $staff=$this->staff;

        if($staff['level'] ==2){
         $complaintList=$this->admin_model->getComplaintList(null,$staff['id'],null,null);
        }else{
            $complaintList=$this->admin_model->getComplaintList();
        }

        
        $status=$this->systemtask_model->getComplaintstatusList();

        $data['complaintlist']=$complaintList;
        $data['statuslist']=$status;
        $data['levelaccess']=$staff['level'];

        $this->load->view("layout/header");
        $this->load->view("admin/complaintlist",$data);
        $this->load->view("layout/footer");
    }

    public function filterbasecomplaintlist(){
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $array = array('status' =>0, 'error' =>'', 'errorP' =>'Your are not allowed to view');
        }else{
        //this function filter complaint list based on the status of the complaint 
        $status=$this->input->post('status',TRUE);
        $staff=$this->staff;

        if($staff['level'] ==2){
         $complaintList=$this->admin_model->getComplaintList(null,$staff['id'],$status,null);
        }else{
            $complaintList=$this->admin_model->getComplaintList(null,null,$status,null);
        }

        $data['complaintlist']=$complaintList;
        $data['levelaccess']=$staff['level'];
        $html=$this->load->view("admin/complaintlisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
     }
        echo json_encode($array);
    }

    public function changesStatus(){
        if (!$this->rbac->hasPrivilege('complaintstatusmodification', 'can_edit')) {
            $array = array('status' =>0, 'error' =>'', 'errorP' =>'Status Changed not Allowed');
        }else{
            $staff=$this->staff;
            $complaintid=$this->input->post('complaintid');
            $status=$this->input->post('status');
            if($staff['level'] ==2){
             $complaintList=$this->admin_model->getComplaintList($complaintid,$staff['id'],null,null);
             if(count($complaintList)==0){
             $array = array('status' =>0, 'error' =>'', 'errorP' =>'Complaint not assigned to you');
             }else{
                  if($status==3){
                    $this->db->where('complaintid',$complaintid)->update('chatroom',array('active'=>0));
                   }
               $this->db->where('complaint_id',$complaintid)->update('complaint',array('complaintStatus'=>$status));
               $this->customlib->insertinhistory($complaintid,'Status changed');
               $array = array('status' =>1, 'error' =>'', 'errorP' =>''); 
             }
            }else{
                $complaintList=$this->admin_model->getComplaintList($complaintid,null,null,null);
                if(count($complaintList)==0){
                    $array = array('status' =>0, 'error' =>'', 'errorP' =>'Complaint not exist');
                    }else{
                        $updata['complaintStatus']=$status;
                        if($status==1 || $status==2){
                         $updata['assignedTo']=''; 
                         $this->db->where('complaintid',$complaintid)->delete('chatroom');
                        }
                        if($status==3){
                         $this->db->where('complaintid',$complaintid)->update('chatroom',array('active'=>0));
                        }
                      $this->db->where('complaint_id',$complaintid)->update('complaint',$updata);
                      $this->customlib->insertinhistory($complaintid,'Status changed');
                      $array = array('status' =>1, 'error' =>'', 'errorP' =>''); 
                    }
            }
        } 

        echo json_encode($array);
    }


    public function getcomplaint($ide,$comp)
    {
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $this->access_denied();
        }

        $this->session->set_userdata('top_menu', 'complaintList');
        $this->session->set_userdata('sub_menu', '');

        $id=base64_decode($ide);

        $staff=$this->staff;
        
        

        if($staff['level'] ==2){
         $complaintList=$this->admin_model->getComplaintList($id,$staff['id'],null,null);
         $extraPayment=$this->admin_model->getextraPayment($id,null,$staff['id']);
         $status=$this->systemtask_model->getComplaintstatusList(1);
        }else{
            $complaintList=$this->admin_model->getComplaintList($id,null,null,null);
            $extraPayment=$this->admin_model->getextraPayment($id);
            $status=$this->systemtask_model->getComplaintstatusList();
        }
 
        $handler=($complaintList['handler_id']==3)?'':$complaintList['handler_id'];

        //$complaintList=$this->admin_model->getComplaintList($id);//getting details of single complaint list
        $history=$this->admin_model->getComplaintHistory($id); //getting details of single complaint history
        $workers=$this->admin_model->getSpecifiTyperWorker($handler); //getting list of workers based on the complaint type
        

        //print_r($complaintList);die();
        $data['history']=$history;
        $data['extraPayment']=$extraPayment;
        $data['compNo']=base64_decode($comp);
        $data['complaint']=$complaintList;
        $data['workerlist']=$workers;
        $data['statuslist']=$status;

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
            $array = array('status' =>0, 'error' => '','errorP' => 'You dont have permission');
        }else{
            $staff=$this->staff;


        $note=$this->input->post('note',TRUE);
        $amount=$this->input->post('amount',TRUE);
        $compid=$this->input->post('compid',TRUE);

        $inserArray=array(
            'note' =>$note,
            'complaintid' =>$compid,
            'amount' =>$amount,
            'createDate'=>time(),
        );
        if($staff['level'] ==2){
            $inserArray['raisedby']=$staff['id'];
        }
        $this->db->insert('extrapayment',$inserArray);
        $this->customlib->insertinhistory($compid,'Extrapayment Assigned');
        $array = array('status' => 1, 'error' => '');
       }
        echo json_encode($array);
    }

    /************************************************************** USER SECTION ************************************************************************/

    public function userList(){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'userList');
        $this->session->set_userdata('sub_menu', '');
        
        $userlist=$this->admin_model->getUserList();
        $data['userlist']=$userlist;

        $this->load->view("layout/header");
        $this->load->view("admin/userlist",$data);
        $this->load->view("layout/footer");

    }

    public function filterbaseuserslist(){
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            $array = array('status' =>0, 'error' =>'', 'errorP' => 'You dont have permission');
        }else{        
        $status=$this->input->post('status',TRUE);
        $complaintList=$this->admin_model->getUserList(null,$status);
        $data['userlist']=$complaintList;
        $html=$this->load->view("admin/userlisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
        }
        echo json_encode($array);   
    }


    public function getuser($ide,$mob=null){
        if (!$this->rbac->hasPrivilege('users', 'can_view')) {
            $this->access_denied();
        }

        if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
            redirect('admin/admin/userList');
        }
        
        $id=base64_decode($ide);
        
        $userlist=$this->admin_model->getUserList($id);
        echo $this->db->last_query();
        print_r($userlist);die();

        $this->load->view("layout/header");
        $this->load->view("admin/dashboard_view");
        $this->load->view("layout/footer");

    }

    public function useractivestatus(){
        if (!$this->rbac->hasPrivilege('staff', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $userid=$this->input->post('userid',TRUE); 
        $status=$this->input->post('status',TRUE);
        $uparray=array('status'=>$status,);
        $this->db->where('userid',$userid)->update('users',$uparray);
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }




    /*************************************************************** STAFF SECTION  *****************************************************************/


    public function staffList(){
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            $this->access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'staffList');
        $this->session->set_userdata('sub_menu', '');
        $staffList=$this->admin_model->getStaffList();
        $data['dept']=$this->systemtask_model->getWorkerType();
        $data['roles']=$this->systemtask_model->getAllRoleList();
        $data['stafflist']=$staffList;    
        $this->load->view("layout/header");
        //print_r($staffList);die();
        $this->load->view("admin/stafflist",$data);
        $this->load->view("layout/footer");

    }

    public function filterbasestafflist(){
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            $array = array('status' =>0, 'error' =>'', 'errorP' => 'You dont have permission');
        }else{        
        $status=$this->input->post('status',TRUE);
        $complaintList=$this->admin_model->getStaffList(null,$status);
        //echo $this->db->last_query();
        $data['stafflist']=$complaintList;
        $html=$this->load->view("admin/stafflisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
        }
        echo json_encode($array);   
    }

    public function getstaff($ide){
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            $this->access_denied();
        }    
        $id=base64_decode($ide);
        $staffList=$this->admin_model->getStaffList($id);
        $complaintList=$this->admin_model->getComplaintList(null,$id,null,null);
        $status=$this->systemtask_model->getComplaintstatusList();
        $data['statuslist']=$status;
        if($this->rbac->hasPrivilege('complaintList', 'can_view')){
        $data['complaintlist']=$complaintList;
        }
        $data['staff']=$staffList;
        $this->load->view("layout/header");
        $this->load->view("admin/staffDetails",$data);
        $this->load->view("layout/footer");

    }

    public function staffbasedcomplaintlist(){
        if (!$this->rbac->hasPrivilege('complaintList', 'can_view')) {
            $array = array('status' =>0, 'error' =>'', 'errorP' => 'You dont have permission');
        }else{  
        //this function filter complaint list based on the status of the complaint and staff id
        $status=$this->input->post('status',TRUE);
        $staff=base64_decode($this->input->post('staff',TRUE));
        $complaintList=$this->admin_model->getComplaintList(null,$staff,$status,null);
        $data['complaintlist']=$complaintList;
        $html=$this->load->view("admin/complaintlisttable",$data,true);
        $array = array('status' =>1, 'error' =>'', 'html' => $html);
        }
        echo json_encode($array);
    }

    public function addstaff(){
        if (!$this->rbac->hasPrivilege('staff', 'can_add')) {
            $array = array('status' =>0, 'errorP' =>'You dont have permission','error' =>'');
        }else{
        if($this->input->server('REQUEST_METHOD') === 'GET'){
            $this->access_denied();   
        }else{
            $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[staff.username]');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|xss_clean|integer');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[staff.email]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('department', 'Department', 'trim|required|xss_clean|numeric');
            
            if ($this->form_validation->run() == FALSE){
                $array = array('status' =>0,'errorP' =>'Staff Add Failed', 'error' => $this->form_validation->error_array());
            }else{

                $password=rand(111111,999999999);
                $inserArray=array(
                    'name'        =>$this->input->post('name'),
                    'username'    =>$this->input->post('username'),
                    'email'       =>$this->input->post('email'),
                    'mobile'      =>$this->input->post('mobile'),
                    'role_id'     =>$this->input->post('role'),
                    'worker_type' =>$this->input->post('department'),
                    'status'      =>1,
                    'password'    =>md5($password),
                    'createdate'  =>time(),

                );             
                $this->db->insert('staff',$inserArray);

                $notificationStatus=$this->customlib->getNotificationStatus('staffcredential');

                if($notificationStatus['status']==1){
                $inserArray['passkey']=$password;
                $inserArray['subject']='Welcome email to the registered staff in the system'; 
                $html=$this->load->view('email/sendwelcome',$inserArray,TRUE);   
                $this->emailsend->sendemails($inserArray,$html);
                }

                $array = array('status' =>1, 'error' =>''); 

           }
        }
      }
      
      echo json_encode($array);
    }

    public function staffdelete(){
        if (!$this->rbac->hasPrivilege('staff', 'can_delete')) {
            $array=array('status' =>0, 'error' =>'You are not allowed to delete');
            
        }else{
            $this->db->where('staff_id',$this->input->post('staffid'));
            $this->db->delete('staff');
            $array=array('status' =>1, 'error' =>'');
        }
          echo json_encode($array);
    
    }

    public function staffactivestatus(){
        if (!$this->rbac->hasPrivilege('staff', 'can_edit')) {
        $array=array('status' =>0, 'error' =>'You are not allowed to change');            
        }else{
        $staffid=($this->input->post('userid'));
        $stat=$this->input->post('status');
        $this->db->where('staff_id',$staffid)->update('staff',array('status' =>$stat));
        $array=array('status' =>1, 'error' =>'');
        }
        echo json_encode($array);
    }


    /***************************************************** COMMON ******************************************************************************** */


    public function unauthorized(){
        $this->load->view("admin/unauthorized");  
    }

    function access_denied() {
        redirect('admin/admin/unauthorized');
    }


    /************************************************ CHAT WITH USER BY WORKER ********************************************************************************/

    public function chat($ide){
        $user=$this->staff;
        $userid=$user['id'];
        $id=base64_decode($ide);     
        //checking complaint is close or not
        $check=$this->db->select('complaintNo')->from('complaint')->where('complaint_id',$id)->where('assignedTo',$userid)->get()->row_array();
       
           //checking chatroom details
           $closed=$this->db->select('active,chatid')->from('chatroom')->where('complaintid',$id)->where('staffid',$userid)->get()->row_array();
    
           if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
            redirect('admin/admin/complaintList');
           }
    
           $data['complaintNo']=$check['complaintNo'];
           $data['closed']=$closed['active'];
           $data['chatroomid']=base64_encode($closed['chatid']);
           $data['complaintid']=base64_encode($id);
           $message=$this->userpanel_model->getChatMessage($closed['chatid']);
           //print_r($message);die();
           $data['messagelist']=$message;
           
    
        
            $this->load->view("layout/header");
            $this->load->view("common/chatuiStaff",$data);
            $this->load->view("layout/footer");
        
       }

       public function updatechathistory(){
        $chatid=base64_decode($this->input->post('compid',TRUE));
        $lastid=($this->input->post('lastid',TRUE));
        $user=$this->staff;
        $userid=$user['id']; 
        $chatroom=$this->db->select('active,chatid,userid')->from('chatroom')->where('chatid',$chatid)->where('staffid',$userid)->get()->row_array();
        $message=$this->userpanel_model->getChatMessage($chatroom['chatid'],$lastid);//finding latest message after given certain messageid
        foreach($message as $mess){
            ($lastid<$mess['messageid']) ?$lastid=$mess['messageid']:'';  //finding last message id from result array so that next time we can search for messages
        }                                                                // this id onwards
        $data['messagelist']=$message;
        $html=$this->load->view("common/chatmessageStaff",$data,true);
        $array = array('status' => 1, 'error' => '', 'html' => $html,'lastid'=>$lastid);
        echo json_encode($array);
       }
    
       public function sendChatMessage(){
        $chatid=base64_decode($this->input->post('compid',TRUE));
        $message=($this->input->post('message',TRUE));
        $user=$this->staff;
        $userid=$user['id'];
        $chatroom=$this->db->select('active,chatid,userid')->from('chatroom')->where('chatid',$chatid)->where('staffid',$userid)->where('active',1)->get()->row_array();
    
        $insertArray = array(
            'senderid' => $userid,
            'recieverid' =>$chatroom['userid'],   //insert array for chatmessage table
            'whosend' =>1,
            'chatroomid'=>$chatid,
            'message'=>$message,
        );
    
        $this->db->insert('chatmessage',$insertArray);
        $array = array('status' => 1, 'error' => '');
        echo json_encode($array);
    
       }

       /************************************************ CHAT MESSAGE ACCESS BY STAFF ****************************************************************/

       public function chatstaff($ide){

        $id=base64_decode($ide);     
        //checking complaint is close or not
        $check=$this->db->select('complaintNo,assignedTo')->from('complaint')->where('complaint_id',$id)->get()->row_array();

           $closed=$this->db->select('active,chatid')->from('chatroom')->where('complaintid',$id)->where('staffid',$check['assignedTo'])->get()->row_array();
    
           if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
            redirect('admin/admin/complaintList');
           }
    
           $data['complaintNo']=$check['complaintNo'];
           $data['closed']=$closed['active'];
           $data['chatroomid']=base64_encode($closed['chatid']);
           $data['complaintid']=base64_encode($id);
           $message=$this->userpanel_model->getChatMessage($closed['chatid']);
           //print_r($message);die();
           $data['messagelist']=$message;
           
    
        
            $this->load->view("layout/header");
            $this->load->view("common/chatuiAdmin",$data);
            $this->load->view("layout/footer");
        
       }

       /************************************************ STAFF PROFILE ********************************************************************************/

       public function profile($ide=null,$my=0){
        if (!$this->rbac->hasPrivilege('staffprofile', 'can_view')) {
            $this->access_denied();
        }
        
        $this->session->set_userdata('sub_menu', '');

        $user=$this->staff;
        $userid=$user['id'];

        if($user['level']!=2 && $my==1){
         $this->session->set_userdata('top_menu', 'staffList');
         $userid=base64_decode($ide);
        }else{
         $userid=$user['id'];  
         $this->session->set_userdata('top_menu', 'profile');  
        }

        $data['profile']=$this->admin_model->getStaffProfile($userid,null);//echo $this->db->last_query();
        $data['department']=$this->systemtask_model->departmentlistactive();
        $data['levelaccess']=$user['level'];
        //print_r($data['profile']);die();
        $this->load->view("layout/header");
        $this->load->view("admin/profile",$data);
        $this->load->view("layout/footer");
       }

       public function updateProfile(){
        if (!$this->rbac->hasPrivilege('staffprofile', 'can_edit')) {
            $this->access_denied();
        }
        $user=$this->staff;
        $userid=$user['id'];
        $data['levelaccess']=$user['level'];
        $data['department']=$this->systemtask_model->departmentlistactive();
        $ide=base64_decode($this->input->post('identity'));
        if($user['level']==2){
            $userid=$user['id'];
        }else{
            $userid=$ide;
        }
            $this->form_validation->set_rules('name','Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('username', 'Username', 'trim|min_length[5]|max_length[12]|callback_check_username');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_check_email');
            $this->form_validation->set_rules('mobile','Mobile', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $data['profile']=$this->admin_model->getStaffProfile($userid,null);
                //$this->session->unset('flashdata');
                unset($_SESSION['flashdata']);
                $this->load->view("layout/header");
                $this->load->view("admin/profile",$data);
                $this->load->view("layout/footer");
            }else{
                $inserArray=array();
                if($_POST['username']!=''){
                   $inserArray['username']=$_POST['username']; 
                }
                if($_POST['email']!=''){
                  $inserArray['email']=$_POST['email'];
                }

                if($_POST['department']!=''){
                 $inserArray['worker_type']=$_POST['department'];
                }

                if($_POST['password']!=''){
                    $inserArray['password']=md5($_POST['password']);
                }

                $inserArray['mobile']=$_POST['mobile'];
                $inserArray['name']=$_POST['name'];
                $this->db->where('staff_id',$userid)->update('staff',$inserArray);

                $this->session->set_flashdata('flashSuccess','Profile updated successfully');
                $data['profile']=$this->admin_model->getStaffProfile($userid,null);
                
                
                $this->load->view("layout/header");
                $this->load->view("admin/profile",$data);
                $this->load->view("layout/footer");
                    
            }          
               
        
   }

   function check_username() {
    $user=$this->staff;
    $userid=$user['id'];
    $ide=base64_decode($this->input->post('identity'));
    if($user['level']==2){
        $userid=$user['id'];
    }else{
        $userid=$ide;
    }
    $count=$this->db->select('count(staff_id) as total')->from('staff')->where('staff_id !=',$userid)->where('username',$_POST['newusername'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_username','Username must be unique');
        return FALSE;
    }
    return TRUE;

 }

 function check_email() {
    $user=$this->staff;
    $userid=$user['id'];
    $ide=base64_decode($this->input->post('identity'));
    if($user['level']==2){
        $userid=$user['id'];
    }else{
        $userid=$ide;
    }
    $count=$this->db->select('count(staff_id) as total')->from('staff')->where('staff_id !=',$userid)->where('email',$_POST['email'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_email','Email must be unique');
        return FALSE;
    }
    return TRUE;

 }

 /********************************************************************** USER PROFILE   ********************************************************************/
  public function userprofile($ide=null){
    if (!$this->rbac->hasPrivilege('users', 'can_view')) {
        $this->access_denied();
    }
    $this->session->set_userdata('top_menu', 'users');
    $this->session->set_userdata('sub_menu', '');

     $userid=base64_decode($ide);


    $data['profile']=$this->admin_model->getUserList($userid,null);//echo $this->db->last_query();
    $data['buidlinglist']=$this->systemtask_model->getBuildingListActive();
    //print_r($data['profile']);die();
    $this->load->view("layout/header");
    $this->load->view("admin/userprofile",$data);
    $this->load->view("layout/footer");
   }

   public function updateuserProfile(){
    $this->form_validation->set_rules('name','Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('username', 'Username', 'trim|min_length[5]|max_length[12]|callback_check_username_user');
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_check_email_user');
    $this->form_validation->set_rules('mobile','Mobile', 'trim|required|xss_clean');
    $userid=base64_decode($this->input->post('identity'));

    if ($this->form_validation->run() == false) {
        $data['profile']=$this->admin_model->getUserList($userid,null);
        $this->load->view("layout/header");
        $this->load->view("admin/userprofile",$data);
        $this->load->view("layout/footer");
     }else{

        $inserArray=array();
        if($_POST['username']!=''){
           $inserArray['username']=$_POST['username']; 
        }
        if($_POST['email']!=''){
          $inserArray['email']=$_POST['email'];
        }

        if($_POST['password']!=''){
            $inserArray['password']=md5($_POST['password']);
        }

        $inserArray['mobile']=$_POST['mobile'];
        $inserArray['name']=$_POST['name'];
        $inserArray['roomno']=$_POST['roomno'];
        $inserArray['building']=$_POST['building'];

        $this->db->where('userid',$userid)->update('users',$inserArray);

        $this->session->set_flashdata('flashSuccess','Profile updated successfully');
        $data['profile']=$this->admin_model->getUserList($userid,null);
        $data['buidlinglist']=$this->systemtask_model->getBuildingListActive();
        $this->load->view("layout/header");
        $this->load->view("admin/userprofile",$data);
        $this->load->view("layout/footer");

     }

  }


  function check_username_user() {
    $userid=base64_decode($this->input->post('identity'));
    $count=$this->db->select('count(userid) as total')->from('users')->where('userid !=',$userid)->where('username',$_POST['username'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_username_user','Username must be unique');
        return FALSE;
    }
    return TRUE;

 }

 function check_email_user() {
    $userid=base64_decode($this->input->post('identity'));
    $count=$this->db->select('count(userid) as total')->from('users')->where('userid !=',$userid)->where('email',$_POST['email'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_email_user','Email must be unique');
        return FALSE;
    }
    return TRUE;

 }


 /***************************************************************************** PAYMENT ****************************************************************/

 public function totalpayment(){
    if(!$this->rbac->hasPrivilege('payment_reports', 'can_view')){
        $this->access_denied();
    }
    $this->session->set_userdata('top_menu', 'totalpayment');
    $user=$this->staff;
    $userid=$user['id'];
    $list2=array();
    if($user['level']==2){
        $complist=$this->admin_model->getComplaintList(null,$userid,3,null);
        foreach($complist as $comp){
         $list=array();
         $amount=$this->admin_model->getextraPaymentTotal($comp['complaint_id'],$userid,null)['totalextra'];
         $list['complaint_id']=$comp['complaint_id'];
         $list['complaintdate']=$comp['complaintDate'];
         $list['amount']=($amount=='')?'0':$amount;
         $list['paymentAmount']=$comp['paymentAmount'];
         $list['complaintNo']=$comp['complaintNo'];
         array_push($list2,$list);

        }
        //print_r($list2);
    }else{
        $complist=$this->admin_model->getComplaintList(null,null,null,null);
        foreach($complist as $comp){
         $list=array();
         $amount=$this->admin_model->getextraPaymentTotal($comp['complaint_id'],null,null)['totalextra'];
         $list['complaint_id']=$comp['complaint_id'];
         $list['complaintdate']=$comp['complaintDate'];
         $list['amount']=($amount=='')?'0':$amount;
         $list['paymentAmount']=$comp['paymentAmount'];
         $list['complaintNo']=$comp['complaintNo'];
         array_push($list2,$list);

        }
    }

    $data['paymentlist']=$list2;


    $this->load->view("layout/header");
    $this->load->view("admin/paymentlist",$data);
    $this->load->view("layout/footer");
 }
 
 public function filterbasepaymentlist(){
    $user=$this->staff;
    $userid=$user['id'];
    $list2=array();
    $status=$this->input->post('status');
    if(!$this->rbac->hasPrivilege('payment_reports', 'can_view')){
    $array=array('status'=>0, 'error' =>'', 'errorP' =>'You dont have permission');
    }else{
    if($user['level']==2){
        $complist=$this->admin_model->getComplaintList(null,$userid,3,null);
        foreach($complist as $comp){
         $list=array();
         $amount=$this->admin_model->getextraPaymentTotal($comp['complaint_id'],$userid,$status)['totalextra'];
         $list['complaint_id']=$comp['complaint_id'];
         $list['complaintdate']=$comp['complaintDate'];
         $list['amount']=($amount=='')?'0':$amount;
         $list['paymentAmount']=$comp['paymentAmount'];
         $list['complaintNo']=$comp['complaintNo'];
         array_push($list2,$list);

        }
        //print_r($list2);
    }else{
        $complist=$this->admin_model->getComplaintList(null,null,null,null);
        foreach($complist as $comp){
         $list=array();
         $amount=$this->admin_model->getextraPaymentTotal($comp['complaint_id'],null,$status)['totalextra'];
         $list['complaint_id']=$comp['complaint_id'];
         $list['complaintdate']=$comp['complaintDate'];
         $list['amount']=($amount=='')?'0':$amount;
         $list['paymentAmount']=$comp['paymentAmount'];
         $list['complaintNo']=$comp['complaintNo'];
         array_push($list2,$list);

        }
    }

    $data['paymentlist']=$list2;
    $html=$this->load->view("admin/paymentlisttable",$data,true);
    $array=array('status'=>1, 'error' =>'', 'html' => $html);
    }
    echo json_encode($array);
 }





}



?>