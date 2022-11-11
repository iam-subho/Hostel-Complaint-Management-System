<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpanel extends User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('userpanel_model','admin_model','systemtask_model'));
        $this->load->library('emailsend');
        $this->load->library('customlib');
        
      }

      public function index(){
        redirect('userpanel/dashboard');
      }

    public function dashboard() {
        //dashboard loading for user based on userid
        $this->session->set_userdata('top_menu', 'dashboard');
        $user=$this->session->userdata('user');
        $complaintlist=$this->userpanel_model->getComplaintList($user['id'],null,5);
       
    
        $data['complaintlist']=$complaintlist;
        $data['total']=count($this->userpanel_model->getComplaintList($user['id'],null,null));
        $data['pending']=count($this->userpanel_model->getComplaintList($user['id'],1,null));
        $data['closed']=count($this->userpanel_model->getComplaintList($user['id'],3,null));

        $this->load->view("layout/headerUser");
        $this->load->view("user/dashboard_view",$data);
        $this->load->view("layout/footerUser");
    }

   public function complaintList(){  
    //this function is used to show the list of complaint raised by logged in user
    $this->session->set_userdata('top_menu', 'complaint');
    $user=$this->session->userdata('user');
    $complaintlist=$this->userpanel_model->getComplaintList($user['id'],null,null);
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
    $complaintList=$this->userpanel_model->getComplaintList($user['id'],$status,null);
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
   $data['compid']=$compid;

   $this->load->view("layout/headerUser");

   //$this->load->view("user/staffreviewN",$data);
   $this->load->view("user/complaintHistorylist",$data);
   $this->load->view("layout/footerUser");

   }

   public function ajaxeditdescription(){
    // description editing for complaint
    $ide=base64_decode($this->input->post('compid'));
    $user=$this->session->userdata('user');
    $complaint=$this->userpanel_model->getSingleComplaintList($ide,$user['id']);
    if(count($complaint)==0){
        $array=array('status'=>0, 'error' =>'', 'errorP' =>'You try to access other users complaint');
    }else{
        $data['description']=$this->input->post('desc',TRUE);
        $this->db->where('complaint_id',$ide)->update('complaint',$data);
        $this->customlib->insertinhistory($ide,'Complaint Description Edited');
        $array=array('status'=>1, 'error' =>'', 'errorP' =>'');
    }

    echo json_encode($array);

   }

   public function addreviewforcomplaint(){
    //review add for a complaint after complaint get closed
    $user=$this->session->userdata('user');
    $star=$this->input->post('complaintStars',TRUE);
    $feedback=$this->input->post('feedback',TRUE);
    $complaintid=base64_decode($this->input->post('complaintid',TRUE));

    $data['stars']=$star;
    $data['feedback']=$feedback;

    $this->db->where('complaint_id',$complaintid)->where('registeredBy',$user['id'])->update('complaint',$data);
    redirect('userpanel/complaintlist');

   }

   public function chat($ide){
    $this->session->set_userdata('top_menu', 'complaint');
    $user=$this->session->userdata('user');
    $userid=$user['id'];
    $id=base64_decode($ide);     
    //checking complaint is close or not
    $check=$this->db->select('complaintNo')->from('complaint')->where('complaint_id',$id)->where('registeredBy',$userid)->get()->row_array();

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
    //updating chat latest message in the chat box
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
    //sending message from userpanel
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
    //loading a particular staff review assigned to them 

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
    //loading user profile
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
    //updating user profile
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
          $inserArray['emailverified']=0;
          $inserArray['emailotp']=rand(111111,999999);
          
        }

        if($_POST['password']!=''){
            $inserArray['password']=md5($_POST['password']);
        }

        $inserArray['mobile']=$_POST['mobile'];
        $inserArray['name']=$_POST['name'];

        $this->db->where('userid',$userid)->update('users',$inserArray);

        if($_POST['newemail']!=''){
            $inserArray['subject']="Email Verfication";
            $html=$this->load->view('email/sendverification',$inserArray,TRUE);
            $this->emailsend->sendemails($inserArray,$html);
            $this->session->set_userdata('newuserid', $userid);
            redirect('login/emailverification');
        }

        $this->session->set_flashdata('flashSuccess','Profile updated successfully'); //showing flash messages in front end
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



   public function unauthorized(){
    $this->load->view("layout/headerUser");
    $this->load->view("user/unauthorized");
    $this->load->view("layout/footerUser");
   }

   function access_denied() {
    redirect('userpanel/unauthorized');
   }

}