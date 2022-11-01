<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpanel extends User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('userpanel_model'));
        
      }

    public function dashboard() {
        $this->load->view("layout/headerUser");
        $this->load->view("user/dashboard_view");
        $this->load->view("layout/footerUser");
    }

   public function complaintList(){  
    //this function is used to show the list of complaint raised by logged in user

    $user=$this->session->userdata('user');
    $complaintlist=$this->userpanel_model->getComplaintList($user['id']);
   // print_r($complaintlist);die();

    $data['complaintlist']=$complaintlist;

    $this->load->view("layout/headerUser");
   
    $this->load->view("user/complaintlist",$data);
    $this->load->view("layout/footerUser");


   }


   public function complaintdetails($compid,$compno){
    if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
        redirect('userpanel/complaintList');
       }
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
    $one=count($this->userpanel_model->fetchRatings($staffid,1)); 

    $avg=(float)(1*$one+2*$two+3*$three+4*$four+5*$five)/($one+$two+$three+$four+$five);

    $data['ratingResult']=$rating;
    $data['avg'] =round($avg,1);
    

    $html=$this->load->view("user/staffreview",$data,true);
    $array = array('status' => 1, 'error' => '', 'html' => $html);
    echo json_encode($array);
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