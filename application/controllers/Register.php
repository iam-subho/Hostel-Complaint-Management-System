<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH."libraries/razorpay/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class Register extends User_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helpers('form');
        $this->load->library('form_validation');
		$this->load->library('customlib');
		$this->load->library('emailsend');
        $this->load->model(array('userpanel_model'));
		$this->user=$this->session->userdata('user');

		$this->key=$this->customlib->getSystemInfo()['rakey'];
		$this->secret=$this->customlib->getSystemInfo()['rasecretkey'];
    }



	public function index()
	{
		redirect('register/page1');
	}

	public function page1()
	{
		//complaint registration page 1
		$this->session->set_userdata('top_menu', 'register');
		$userid=$this->user['id'];
		$data['user']=$this->userpanel_model->getUserDetails($userid);
		$data['noreview']=count($this->db->select('*')->from('complaint')->where('registeredBy',$userid)->where('stars ',NULL)->where('complaintStatus',3)->get()->result_array());
		$this->load->view("layout/headerUser");
		$this->load->view('payment/registration-form-page1',$data);
		$this->load->view("layout/footerUser");
	}

	public function page2()
	{
		//complaint registration page2
		$this->session->set_userdata('top_menu', 'register');
		$data['typelist']=$this->userpanel_model->getComplaintType();
		$this->load->view("layout/headerUser");
		$this->load->view('payment/registration-form-page2',$data);
		$this->load->view("layout/footerUser");
	}



    public function complaintSubmit(){
		//complaint submit by the user and insert in database
        $user=$this->session->userdata('user');

        $type=$this->input->post('type',TRUE);
        $description=$this->input->post('description',TRUE);
		$subject=$this->input->post('subject',TRUE);
        $check=$this->db->select('*')->from('complaint_type')->where('typeid',$type)->get()->row_array();
        $status=($check['paymentAmount']>0) ? 2 : 1;
		$time=time();
		$complaintno=time().''.$user['id'];
        $insertArray=array(
            'complaintNo'     => $complaintno,
            'complaint_type'  =>$type, 
            'subject'         =>'',
            'description'     =>$description,
            'registeredBy'    =>$user['id'],
            'assignedTo'      =>'',
            'complaintStatus' =>$status,
            'complaintDate'   =>$time,
			'lastupdate'	  =>$time,
			'subject'	      =>$subject,
        );

        $insertid=$this->userpanel_model->addComplaint($insertArray); //we pass array to model for inserting data in database
		$data['subject']="Complaint registered successfully";
		$data['complainttype']=$check['typename'];
		$data['complaintNo']=$complaintno;
		$data['email']=$user['email'];
		$data['name']=$user['name'];
		$data['description']=$description;
		$notificationStatus=$this->customlib->getNotificationStatus('sendcomplaintconfirmation');
		if($notificationStatus['status']==1){  //checking email sending active or not active
        $html=$this->load->view('email/complaintregister',$data,TRUE);
        $this->emailsend->sendemails($data,$html);    //send email using email library
		}

        if($check['paymentAmount']>0){   //if complaint type is personal the assigning payment session and redirecting payment page
        $_SESSION['insertid']=$insertid;
        $_SESSION['payable_amount'] =$check['paymentAmount'];
        $_SESSION['extrapayment']=0;
        redirect('register/pay');
        }else{
         redirect('userpanel/complaintList');  //if payment not required redirecting to list page
        }


    }

    public function retrypayment($insertid,$type){
		//if payment failed then retying paymnet function
        $user=$this->session->userdata('user');
        $check=$this->db->select('paymentAmount')->from('complaint_type')->where('typeid',$type)->get()->row_array();
        $_SESSION['insertid']=$insertid;
        $_SESSION['payable_amount'] =$check['paymentAmount'];
        $_SESSION['extrapayment']=0;
        redirect('register/pay');
    }

    public function extrapayment($payid){
		//extrapayment payment page loading in the system
        $check=$this->db->select('amount')->from('extrapayment')->where('extrapaymentid',$payid)->get()->row_array();
        $_SESSION['insertid']=$payid;
        $_SESSION['payable_amount'] =$check['amount'];
        $_SESSION['extrapayment']=1;
        redirect('register/pay');
    }

	/**
	 * This function creates order and loads the payment methods
	 */
	public function pay($extra=null)
	{
		   if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
			redirect('register');
		   }
		$api = new Api($this->key,$this->secret);
		
       $url=site_url('/userpanel/complaintList');
	   //echo $url;
		$razorpayOrder = $api->order->create(array(
			'receipt'         => rand(),
			'amount'          => $_SESSION['payable_amount']*100,  //creating order in razorpay using razorpay api 
			'currency'        => 'INR',
			'payment_capture' => 1, // auto capture
			
			
		));


		$amount = $razorpayOrder['amount'];

		$razorpayOrderId = $razorpayOrder['id'];

		$_SESSION['razorpay_order_id'] = $razorpayOrderId;

		$data = $this->prepareData($amount,$razorpayOrderId);

        $this->load->view("layout/headerUser");
		$this->load->view('payment/razorpay',array('data' => $data));
		$this->load->view("layout/footerUser");
		
	}

	/**
	 * This function verifies the payment,after successful payment
	 */
	public function verify()
	{
		//verifying the payment with razorpay usig api 
		$success = true;
		$error = "payment_failed";
		if (empty($_POST['razorpay_payment_id']) === false) {
			$api = new Api($this->key,$this->secret);
		try {
				$attributes = array(
					'razorpay_order_id' => $_SESSION['razorpay_order_id'],
					'razorpay_payment_id' => $_POST['razorpay_payment_id'],
					'razorpay_signature' => $_POST['razorpay_signature']
				);
				$api->utility->verifyPaymentSignature($attributes);
			} catch(SignatureVerificationError $e) {
				$success = false;
				$error = 'Razorpay_Error : ' . $e->getMessage();
			}
		}
		if ($success === true) {
			/**
			 * Call this function from where ever you want
			 * to save save data before of after the payment
			 */
            if($_SESSION['extrapayment']==1){
            $this->setExtrapaymentData();
            }else{
			$this->setRegistrationData();
            }

			redirect(base_url().'register/success');
		}
		else {
			redirect(base_url().'register/paymentFailed');
		}
	}


	public function prepareData($amount,$razorpayOrderId)
	{
		//preparing data creating order in razorpay
        $user=$this->session->userdata('user');
		$data = array(
			"key" => RAZOR_KEY,
			"amount" => $amount,
			"name" => "PUBLIC GRIEVANCE",
			"description" => "PERSONAL GRIEVANCE PAYMENT",
			"image" => "",
			"prefill" => array(
				"name"  =>$user['name'],
				"email"  =>$user['email'],
				"contact" => $user['mobile'],
			),
			"notes"  => array(
				"address"  => "Hello World",
				"merchant_order_id" => rand(),
			),
			"theme"  => array(
				"color"  => "#F37254"
			),
			"order_id" => $razorpayOrderId,
		);
		return $data;
	}

	/**
	 * This function saves your form data to session,
	 * After successfull payment you can save it to database
	 */
	public function setRegistrationData()
	{
        $insertid=$_SESSION['insertid'];
		$time=time();
		$registrationData = array(
			'paymentTransactionId' => $_SESSION['razorpay_order_id'],
			'paymentDate'          =>$time,
            'complaintStatus'      =>1,
			'lastupdate'	       =>$time,
		);
	
        $this->userpanel_model->updatePayment($registrationData,$insertid);

	}


    public function setExtrapaymentData()
	{
		//when extra payment paid then this function call
        $insertid=$_SESSION['insertid'];
        $time=time();
		$registrationData = array(
			'transactionid' => $_SESSION['razorpay_order_id'],
			'transactionDate'          => time(),
		);
	
        $this->userpanel_model->updateExtraPayment($registrationData,$insertid);

	}

	/**
	 * This is a function called when payment successfull,
	 * and shows the success message
	 */
	public function success()
	{
		
		if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
			//redirect('register');
		   }
		$this->load->view("layout/headerUser");
		$this->load->view('payment/success');
		$this->load->view("layout/footerUser");
	}
	/**
	 * This is a function called when payment failed,
	 * and shows the error message
	 */
	public function paymentFailed()
	{
		if(!isset($_SERVER['HTTP_REFERER'])){ //if url is directly requested from url bar then redirect
			redirect('register');
		   }
		$this->load->view("layout/headerUser");
		$this->load->view('payment/error');
		$this->load->view("layout/footerUser");
	}	
}