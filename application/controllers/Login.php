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

  }
 
  function admin(){
    $this->session->sess_destroy();
    $this->load->view("layout/header");
    $this->load->view('admin/login_view');
    $this->load->view("layout/footer");
  }

  function index(){
    $this->session->sess_destroy();
    $captcha_new  =$this->returnCaptcha();
    $data['captchaImage'] =$captcha_new;
    $data['LogonUrlfb'] =  $this->facebook->login_url();
    $data['LogonUrlgm'] =  base_url('sociallogin/oauthgmail');
    $data['LogonUrltw'] =  base_url('sociallogin/oauthtwitter');
    $this->load->view("layout/headerUser");
    $this->load->view('user/userlogin_view',$data);
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
      $this->load->view("layout/headerUser");
      $this->load->view('user/userlogin_view',$data);
      $this->load->view("layout/footerUser");
    }else{
    $validate = $this->login_model->validateuser($username,$password);
    if($validate->num_rows() > 0){
        $data  = $validate->row_array();
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
      'pool'          =>'0123456789',
      'img_url' 			=> base_url() . 'assets/captch_img/',
      'img_path' 			=> 'assets/captch_img/',
      'img_width'     => '250',
      'img_height'    => 38,
      'word_length'   => 2,
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
 
}