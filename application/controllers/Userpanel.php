<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpanel extends User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('userpanel_model','admin_model','systemtask_model'));
        $this->load->library('emailsend');
        
      }

    public function dashboard() {
        $this->session->set_userdata('top_menu', 'dashboard');
        $user=$this->session->userdata('user');
        $complaintlist=$this->userpanel_model->getComplaintList($user['id']);
       // print_r($complaintlist);die();
    
        $data['complaintlist']=$complaintlist;
        $data['total']=count($complaintlist);
        $data['pending']=count($this->userpanel_model->getComplaintList($user['id'],1));
        $data['closed']=count($this->userpanel_model->getComplaintList($user['id'],3));

        $this->load->view("layout/headerUser");
        $this->load->view("user/dashboard_view",$data);
        $this->load->view("layout/footerUser");
    }

   public function complaintList(){  
    //this function is used to show the list of complaint raised by logged in user
    $this->session->set_userdata('top_menu', 'complaint');
    $user=$this->session->userdata('user');
    $complaintlist=$this->userpanel_model->getComplaintList($user['id']);
   // print_r($complaintlist);die();

    $data['complaintlist']=$complaintlist;
    $status=$this->systemtask_model->getComplaintstatusList();
    $data['statuslist']=$status;

    $this->load->view("layout/headerUser");
   
    $this->load->view("user/complaintlist",$data);
    $this->load->view("layout/footerUser");


   }

   public function filterbasecomplaintlist(){

    //this function filter complaint list based on the status of the complaint 
    $status=$this->input->post('status',TRUE);
    $user=$this->session->userdata('user');
    $complaintList=$this->userpanel_model->getComplaintList($user['id'],$status);
    $data['complaintlist']=$complaintList;
    $html=$this->load->view("user/complaintlisttable",$data,true);
    $array = array('status' =>1, 'error' =>'', 'html' => $html);
    echo json_encode($array);
}


   public function complaintdetails($compid,$compno){
    if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
        redirect('userpanel/complaintList');
       }
       $this->session->set_userdata('top_menu', 'complaint');
   //this function used to display complaint history and extra payment details from database.
   $ide=base64_decode($compid);
   $user=$this->session->userdata('user');
   
   //Finding Complaint History for a given complaint
   $history=$this->userpanel_model->getComplaintHistory($ide,$user['id']);
   //finding Extra Payment details for a given complaint
   $extraPayment=$this->userpanel_model->getextraPayment($ide,$user['id']);
   //taking status information and given stars feedback for a given complaint
   $complaint=$this->userpanel_model->getSingleComplaintList($ide,$user['id']);

   //print_r($complaint);

   $staffid=$complaint['assignedTo'];

   $rating=$this->userpanel_model->fetchRatings($staffid);
   $data['ratingResult']=$rating;

   $data['history']=$history;
   $data['extraPayment']=$extraPayment;
   $data['compNo']=base64_decode($compno);
   $data['complaint']=$complaint;

   $this->load->view("layout/headerUser");

   //$this->load->view("user/staffreviewN",$data);
   $this->load->view("user/complaintHistorylist",$data);
   $this->load->view("layout/footerUser");

   }

   public function chat($ide){
    $this->session->set_userdata('top_menu', 'complaint');
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $id=base64_decode($ide);     
    //checking complaint is close or not
    $check=$this->db->select('complaintNo')->from('complaint')->where('complaint_id',$id)->where('registeredBy',$userid)->where('complaintStatus !=',3)->get()->row_array();

     /*if($check['complaintNo'] ==''){
        //echo $this->db->last_query();
        //echo $check['complaintNo'];die();
        //$this->access_denied();
       }*/

       //checking chatroom details
       $closed=$this->db->select('active,chatid')->from('chatroom')->where('complaintid',$id)->where('userid',$userid)->get()->row_array();

       if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
        redirect('userpanel/complaintList');
       }

       $data['complaintNo']=$check['complaintNo'];
       $data['closed']=$closed['active'];
       $data['chatroomid']=base64_encode($closed['chatid']);
       $data['complaintid']=base64_encode($id);
       $message=$this->userpanel_model->getChatMessage($closed['chatid']);
       //print_r($message);die();
       $data['messagelist']=$message;

    
        $this->load->view("layout/headerUser");
        $this->load->view("common/chatui",$data);
        $this->load->view("layout/footerUser");
    
   }

   public function updatechathistory(){
    $chatid=base64_decode($this->input->post('compid',TRUE));
    $lastid=($this->input->post('lastid',TRUE));
    $user=$this->session->userdata('user');
    $userid=$user['id']; 
    $chatroom=$this->db->select('active,chatid,staffid')->from('chatroom')->where('chatid',$chatid)->where('userid',$userid)->get()->row_array();
    $message=$this->userpanel_model->getChatMessage($chatroom['chatid'],$lastid);//finding latest message after given certain messageid
    foreach($message as $mess){
        ($lastid<$mess['messageid']) ?$lastid=$mess['messageid']:'';  //finding last message id from result array so that next time we can search for messages
    }                                                                // this id onwards
    $data['messagelist']=$message;
    $html=$this->load->view("common/chatmessage",$data,true);
    $array = array('status' => 1, 'error' => '', 'html' => $html,'lastid'=>$lastid);
    echo json_encode($array);
   }

   public function sendChatMessage(){
    $chatid=base64_decode($this->input->post('compid',TRUE));
    $message=($this->input->post('message',TRUE));
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $chatroom=$this->db->select('active,chatid,staffid')->from('chatroom')->where('chatid',$chatid)->where('userid',$userid)->where('active',1)->get()->row_array();

    $insertArray = array(
        'senderid' => $userid,
        'recieverid' =>$chatroom['staffid'],   //insert array for chatmessage table
        'whosend' =>2,
        'chatroomid'=>$chatid,
        'message'=>$message,
    );

    $this->db->insert('chatmessage',$insertArray);
    $array = array('status' => 1, 'error' => '');
    echo json_encode($array);

   }

   public function getStaffReview(){

    $staffid=base64_decode($this->input->post('staffid',TRUE));

    $rating=$this->userpanel_model->fetchRatings($staffid);
    $data['totalrating'] = count($rating);
    $five=count($this->userpanel_model->fetchRatings($staffid,5));
    $four=count($this->userpanel_model->fetchRatings($staffid,4));
    $three=count($this->userpanel_model->fetchRatings($staffid,3));
    $two=count($this->userpanel_model->fetchRatings($staffid,2));
    $one=count($this->userpanel_model->fetchRatings($staffid,1));  //echo print_r($rating)>0;die();
    if(count($rating)>0){
    $avg=(float)(1*$one+2*$two+3*$three+4*$four+5*$five)/($one+$two+$three+$four+$five);
    $data['avg'] =round($avg,1); 
    }
    $data['ratingResult']=$rating;
    
    

    $html=$this->load->view("user/staffreview",$data,true);
    $array = array('status' => 1, 'error' => '', 'html' => $html);
    echo json_encode($array);
   }


   public function profile(){
    $this->session->set_userdata('top_menu', 'profile');
    $this->session->set_userdata('sub_menu', '');
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $data['profile']=$this->admin_model->getUserList($userid,null);
    $this->load->view("layout/headerUser");
    $this->load->view("user/userprofile",$data);
    $this->load->view("layout/footerUser");
   }

   public function updateProfile(){
    $this->form_validation->set_rules('name','Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('newusername', 'Username', 'trim|min_length[5]|max_length[12]|callback_check_username');
    $this->form_validation->set_rules('newemail', 'Email', 'trim|valid_email|callback_check_email');
    $this->form_validation->set_rules('mobile','Mobile', 'trim|required|xss_clean');
    $user=$this->session->userdata('user');
    $userid=$user['id'];
     if ($this->form_validation->run() == false) {
        $data['profile']=$this->admin_model->getUserList($userid,null);
        $this->load->view("layout/headerUser");
        $this->load->view("user/userprofile",$data);
        $this->load->view("layout/footerUser");
     }else{

        $inserArray=array();
        if($_POST['newusername']!=''){
           $inserArray['username']=$_POST['newusername']; 
        }
        if($_POST['newemail']!=''){
          $inserArray['email']=$_POST['newemail'];
        }

        if($_POST['password']!=''){
            $inserArray['password']=md5($_POST['password']);
        }

        $inserArray['mobile']=$_POST['mobile'];
        $inserArray['name']=$_POST['name'];

        $this->db->where('userid',$userid)->update('users',$inserArray);

        $this->session->set_flashdata('flashSuccess','Profile updated successfully');
        $data['profile']=$this->admin_model->getUserList($userid,null);
        $this->load->view("layout/headerUser");
        $this->load->view("user/userprofile",$data);
        $this->load->view("layout/footerUser");

     }
   }


   function check_username() {
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $count=$this->db->select('count(userid) as total')->from('users')->where('userid !=',$userid)->where('username',$_POST['newusername'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_username','Username must be unique');
        return FALSE;
    }
    return TRUE;

 }

 function check_email() {
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $count=$this->db->select('count(userid) as total')->from('users')->where('userid !=',$userid)->where('email',$_POST['newemail'])->get()->row_array();
    if ($count['total']>0) {
        $this->form_validation->set_message('check_email','Email must be unique');
        return FALSE;
    }
    return TRUE;

 }

   public function opensidebar(){
    $this->load->view("layout/headerUser");
    $this->load->view("user/sidebar");
    $this->load->view("layout/footerUser"); 
   }

   public function unauthorized(){
    $this->load->view("layout/headerUser");
    $this->load->view("user/unauthorized");
    $this->load->view("layout/footerUser");
   }

   function access_denied() {
    redirect('userpanel/unauthorized');
   }

}