<?php
class Login extends Public_Controller {
  function __construct(){
    parent::__construct();
    $this->load->model('login_model');
  }
 
  function index(){
    $this->load->view("layout/header");
    $this->load->view('login_view');
    $this->load->view("layout/footer");
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
            'name'  => $name,
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
        redirect('login');
    }
  }
 
  function logout(){
      $this->session->sess_destroy();
      redirect('login');
  }
 
}