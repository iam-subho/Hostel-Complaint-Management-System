<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpanel extends User_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('userpanel_model'));
        
      }

    public function dashboard() {
        $this->load->view("layout/header");
        $this->load->view("user/dashboard_view");
        $this->load->view("layout/footer");
    }

   public function complaintList(){  
    //this function is used to show the list of complaint raised by logged in user

    $user=$this->session->userdata('user');
    $complaintlist=$this->userpanel_model->getComplaintList($user['id']);
   // print_r($complaintlist);die();

    $data['complaintlist']=$complaintlist;

    $this->load->view("layout/header");
    $this->load->view("user/complaintlist",$data);
    $this->load->view("layout/footer");


   }


   public function complaintdetails($ide,$compno){
   //this function used to display complaint history and extra payment details from database.
   $compid=base64_decode($ide);
   $user=$this->session->userdata('user');
   

   $history=$this->userpanel_model->getComplaintHistory($ide,$user['id']);
   //print_r( $history);die();
   $extraPayment=$this->userpanel_model->getextraPayment($ide,$user['id']);
   //print_r($extraPayment);die();

   $data['history']=$history;
   $data['extraPayment']=$extraPayment;
   $data['compNo']=$compno;

   $this->load->view("layout/header");
   $this->load->view("user/complaintHistorylist",$data);
   $this->load->view("layout/footer");

   }

}