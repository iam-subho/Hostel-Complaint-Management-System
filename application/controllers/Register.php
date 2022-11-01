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
        $this->load->model(array('userpanel_model'));
    }



	public function index()
	{
		$this->load->view('payment/registration-form');
	}



    public function complaintSubmit(){
        $user=$this->session->userdata('user');

        $type=$this->input->post('type');
        $description=$this->input->post('description');
        $check=$this->db->select('paymentAmount')->from('complaint_type')->where('typeid',$type)->get()->row_array();
        $status=($check['paymentAmount']>0) ? 2 : 1;
		$time=time();
        $insertArray=array(
            'complaintNo'     => time().''.$user['id'],
            'complaint_type'  =>$type, 
            'subject'         =>'',
            'description'     =>$description,
            'registeredBy'    =>$user['id'],
            'assignedTo'      =>'',
            'complaintStatus' =>$status,
            'complaintDate'   =>$time,
			'lastupdate'	  =>$time,
        );

        $insertid=$this->userpanel_model->addComplaint($insertArray);

        if($check['paymentAmount']>0){
        $_SESSION['insertid']=$insertid;
        $_SESSION['payable_amount'] =$check['paymentAmount'];
        $_SESSION['extrapayment']=0;
        redirect('register/pay');
        }else{
         redirect('userpanel/complaintList');
        }


    }

    public function retrypayment($insertid,$type){
        $user=$this->session->userdata('user');
        $check=$this->db->select('paymentAmount')->from('complaint_type')->where('typeid',$type)->get()->row_array();
        $_SESSION['insertid']=$insertid;
        $_SESSION['payable_amount'] =$check['paymentAmount'];
        $_SESSION['extrapayment']=0;
        redirect('register/pay');
    }

    public function extrapayment($payid){
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
		$api = new Api(RAZOR_KEY, RAZOR_SECRET_KEY);
		
       $url=site_url('/userpanel/complaintList');
	   //echo $url;
		$razorpayOrder = $api->order->create(array(
			'receipt'         => rand(),
			'amount'          => $_SESSION['payable_amount']*100, 
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
		$success = true;
		$error = "payment_failed";
		if (empty($_POST['razorpay_payment_id']) === false) {
			$api = new Api(RAZOR_KEY, RAZOR_SECRET_KEY);
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
		$this->load->view("layout/headerUser");
		$this->load->view('payment/error');
		$this->load->view("layout/footerUser");
	}	
}