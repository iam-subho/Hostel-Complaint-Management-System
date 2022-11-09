<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emailsend
{

    private $userRoles = array();
    protected $permissions;
    public $perm_category;

    public function __construct()
    {

        $this->CI          = &get_instance();
        $this->permissions = array();
        $this->CI->load->library('email');
        $this->perm_category = $this->CI->config->item('perm_category');
        
    }


    public function sendemails($data,$html){
        $systeminfo=$this->CI->customlib->getSystemInfo();
        $this->CI->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_user' => 'apikey',
            'smtp_pass' =>$systeminfo['sendgridapkey'],
            'smtp_port' => 587,
            'crlf' => "\r\n",
            'newline' => "\r\n"
          ));
          
          $this->CI->email->from($systeminfo['sendgridfrom'],$systeminfo['sendgridfromname']);
          $this->CI->email->to($data['email']);
          $this->CI->email->subject($data['subject']);
          $this->CI->email->message($html);
          $this->CI->email->set_mailtype('html');
          $this->CI->email->send();
          
          //echo $this->email->print_debugger();echo 'hi';

    }

}
