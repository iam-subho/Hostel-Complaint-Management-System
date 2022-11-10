<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/google/vendor/autoload.php");



class Login extends Public_Controller {
  function __construct(){
    parent::__construct();
    $this->load->model('login_model');
    $this->load->helper('captcha');
    $this->load->library('form_validation');
    $this->load->library('facebook');
    $this->load->library('session');
    $this->load->library('emailsend');


  }
 
  function admin(){
    $this->session->sess_destroy();
    $this->load->view("layout/header");
    $this->load->view('admin/login_view');
    $this->load->view("layout/footer");
  }

  function index(){
    //$this->session->sess_destroy();
    //session_start();
    unset($_SESSION['user']);
    $captcha_new  =$this->returnCaptcha();
    $data['captchaImage'] =$captcha_new;
    $data['LogonUrlfb'] =  $this->facebook->login_url();
    $data['LogonUrlgm'] =  base_url('sociallogin/oauthgmail');
    $data['LogonUrltw'] =  base_url('sociallogin/oauthtwitter');
    $this->load->view("layout/headerUser");
    $this->load->view('user/userlogin',$data);
    $this->load->view("layout/footerUser");
  }
 
  function auth(){
    $username    = $this->input->post('username',TRUE);
    $password = md5($this->input->post('password',TRUE));
    $validate = $this->login_model->validate($username,$password);
    if($validate->num_rows() > 0){
        $data  = $validate->row_array();
        $name  = $data['name'];
        $email = $data['username'];
        $level = $data['role_id'];
        $sesdata = array(
            'id'        => $data['staff_id'],
            'name'      => $name,
            'email'     => $email,
            'level'     => $level,
            'logged_in' => TRUE
        );
        $this->session->set_userdata('admin',$sesdata);
        if (isset($_SESSION['redirect_to'])) {
          redirect($_SESSION['redirect_to']);
        } else {
          redirect('admin/admin/dashboard');
       }
    }else{
        echo $this->session->set_flashdata('msg','Username or Password is Wrong');
        redirect('login/admin');
    }
  }


  function userauth(){
    $username    = $this->input->post('username',TRUE);
    $password = md5($this->input->post('password',TRUE));

    $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
    $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
    

    if ($this->form_validation->run() == false) {
      $captcha_new  =$this->returnCaptcha();
      $data['captchaImage'] =$captcha_new;
      $data['LogonUrlfb'] =  $this->facebook->login_url();
      $data['LogonUrlgm'] =  base_url('sociallogin/oauthgmail');
      $data['LogonUrltw'] =  base_url('sociallogin/oauthtwitter');
      $this->load->view("layout/headerUser");
      $this->load->view('user/userlogin',$data);
      $this->load->view("layout/footerUser");
    }else{
    $validate = $this->login_model->validateuser($username,$password);
    if($validate->num_rows() > 0){
        $data  = $validate->row_array();
        if($data['emailverified']==0){
        $this->session->set_userdata('newuserid', $data['userid']);
        redirect('login/emailverification');
        }else if($data['status']==2||$data['status']==3){
          $this->load->view("layout/header");    
          $this->load->view('user/statusinactive');
          $this->load->view("layout/footer");
        }else{
          $name  = $data['name'];
          $email = $data['email'];
          $sesdata = array(
              'id'        => $data['userid'],
              'name'      => $name,
              'email'     => $email,
              'mobile'    => $data['mobile'],
              'logged_in' => TRUE
          );
          $this->session->set_userdata('user',$sesdata);
          if (isset($_SESSION['redirect_to'])) {
            redirect($_SESSION['redirect_to']);
          } else {
            redirect('userpanel/dashboard');
         }
        }
    }else{
        echo $this->session->set_flashdata('flashError','Username or Password is Wrong');
        redirect('login');
    }
   }
  }

 
  function logout(){
      $this->session->sess_destroy();
      redirect('login');
  }


  public function check_captcha($captcha)
  {
      if ($captcha != $this->session->userdata('captcha')):
        $this->session->set_flashdata('flashError','Invalid Captcha');
          $this->form_validation->set_message('check_captcha', 'Invalid Captcha');
          return false;
      else:
          return true;
      endif;
  }

  public function refreshCaptcha()
  {
      $captcha = $this->returnCaptcha();
      echo $captcha;
  }

  function returnCaptcha(){
    $captcha_session_file = $this->session->userdata('captchafile');
    if($captcha_session_file){
    //unlink(FCPATH.'assets/captch_img/'.$captcha_session_file);
    }
    $config 			= array(
      //'pool'          =>'0123456789',
      'img_url' 			=> base_url() . 'assets/captch_img/',
      'img_path' 			=> 'assets/captch_img/',
      'img_width'     => '250',
      'img_height'    => 38,
      'word_length'   => 5,
      'font_size'     => 15,
      'expiration'    => 300,
      'colors'        => array(
        'background'     => array(143, 210, 153),
        'border'         => array(220, 255, 255),
        'text'           => array(0, 0, 0),
        'grid'           => array(53, 170, 71)
     )
    );
    unset($_SESSION['captcha']);
    unset($_SESSION['captchafile']);
    $captcha_new=create_captcha($config);
    $this->session->set_userdata('captcha', $captcha_new['word']);
    $this->session->set_userdata('captchafile', $captcha_new['filename']);
    return $captcha_new['image'];

   }

   public function forgetuserpassword(){
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[users.email]');
      if($this->form_validation->run() == false) {
        $email=$this->input->post('email');
        $useremail=$this->db->select('email,name')->from('users')->where('email',$email)->get()->row_array();

        $pass=rand(111111,99999999);

        $data['passkey']=$pass;
        $data['name']=$useremail['name'];
        $data['subject']="Password Recovered Successfully!";
        $data['email']=$email;


        $this->db->where('email',$email)->update('users',array('password'=>md5($pass)));

        $html=$this->load->view('email/sendforget',$data,TRUE);
        $this->emailsend->sendemails($data,$html);
        $array= array('status' =>1,'error' =>'');    

      }else{
       $array= array('status' =>0,'error' =>'');
     }
   echo json_encode($array);

  }

  public function forgetstaffpassword(){
    $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[users.email]');
      if($this->form_validation->run() == false) {
        $email=$this->input->post('email');
        $useremail=$this->db->select('email,name')->from('staff')->where('email',$email)->get()->row_array();

        $pass=rand(111111,99999999);

        $data['passkey']=$pass;
        $data['name']=$useremail['name'];
        $data['subject']="Password Recovered Successfully!";
        $data['email']=$email;


        $this->db->where('email',$email)->update('staff',array('password'=>md5($pass)));

        $html=$this->load->view('email/sendforgetstaff',$data,TRUE);
        $this->emailsend->sendemails($data,$html);
        $array= array('status' =>1,'error' =>'');    

      }else{
       $array= array('status' =>0,'error' =>'');
     }
   echo json_encode($array);

  }

  public function signuppage(){
    $captcha_new  =$this->returnCaptcha();
    $data['captchaImage'] =$captcha_new;
    $data['buildinglist']=$this->login_model->buildinglist();

    $this->load->view("layout/headerUser");
    $this->load->view('user/registrationpage',$data);
    $this->load->view("layout/footerUser");
  }

  public function signupsubmit(){
    $this->form_validation->set_rules('name','Name', 'trim|required|xss_clean');
    $this->form_validation->set_rules('username','Username', 'trim|required|xss_clean|is_unique[users.username]');
    $this->form_validation->set_rules('mobile','Mobile', 'trim|required|xss_clean');
    $this->form_validation->set_rules('email','Email', 'trim|required|xss_clean|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('roomno','Roomno', 'trim|required|xss_clean');
    $this->form_validation->set_rules('building','Building', 'trim|required|xss_clean');
    $this->form_validation->set_rules('password','Building', 'trim|required|xss_clean');

    if ($this->form_validation->run() == false) {
      $data['buildinglist']=$this->login_model->buildinglist();
  
      $this->load->view("layout/headerUser");
      $this->load->view('user/registrationpage',$data);
      $this->load->view("layout/footerUser");
    }else{

      $data=$this->input->post(NULL,TRUE);
      $data['password']=md5($this->input->post('password',TRUE));
      $data['emailverified']=0;
      $data['status']=2;
      $data['emailotp']=rand(111111,999999);
      $data['emailverified']=0;
      $data['creation_date']=time();
      $this->db->insert('users',$data);
      $id= $this->db->insert_id();
      $this->session->set_userdata('newuserid', $id);
      $data['subject']="Email Verfication";
      $html=$this->load->view('email/sendverification',$data,TRUE);
      $this->emailsend->sendemails($data,$html);
      redirect('login/emailverification');
    }
  }

  public function emailverification(){
    $userid=$this->session->userdata('newuserid');
    if($this->input->server('REQUEST_METHOD') === 'GET'){
      $this->load->view("layout/headerUser");
      $this->load->view('user/emailverification');
      $this->load->view("layout/footerUser");
    }else{

      $otp=$this->input->post('otp');
      $user=$this->db->select('emailotp')->from('users')->where('userid',$userid)->get()->row_array();
      $userdbotp=$user['emailotp'];
      //echo $otp;echo "/n";print_r($userdbotp);
      if($userdbotp!=$otp){
        $array=array('status'=>0, 'error' =>'Otp Verification Failed');
      }else{
        $this->db->where('userid',$userid)->update('users',array('emailverified'=>1));
        $array=array('status'=>1, 'error' =>'');
        unset($_SESSION['newuserid']);
      }

      echo json_encode($array);

    }
  }

  public function emailotpresend(){
    $userid=$this->session->userdata('newuserid');
    if($userid==''){
      $array=array('status'=>0, 'error' =>'Otp Resend Failed');
    }else{
      $otp=rand(111111,999999);
      $data=$this->db->select('*')->from('users')->where('userid',$userid)->get()->row_array();
      $data['subject']='New OTP to verify your email';
      $data['emailotp']=$otp;
      $this->db->where('userid',$userid)->update('users',array('emailotp'=>$otp));
      $html=$this->load->view('email/sendverification',$data,TRUE);
      $this->emailsend->sendemails($data,$html);
      $array=array('status'=>1, 'error' =>'');
    }
    echo json_encode($array);
  }
 
}