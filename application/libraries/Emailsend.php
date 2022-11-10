<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

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
        $this->CI->load->library('customlib');
        $this->perm_category = $this->CI->config->item('perm_category');
        
    }
    
    public function sendemails($data,$html){
        $systeminfo=$this->CI->customlib->getSystemInfo();
        $mail = new PHPMailer(true);
        try{
             $mail->isSMTP();
             //$mail->SMTPDebug = 1;
             $mail->SMTPOptions = array(
                                  'ssl' => array(
                                  'verify_peer' => false,
                                  'verify_peer_name' => false,
                                  'allow_self_signed' => true
                                   )
                                  );
             $mail->Host       = 'smtp.sendgrid.net';                                             
             $mail->SMTPAuth   = true;                                                            
             $mail->Username   = 'apikey';                                                        
             $mail->Password   = $systeminfo['sendgridapkey'];                                     
             $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                                   
             $mail->Port       = 465;
             $mail->setFrom($systeminfo['sendgridfrom'],$systeminfo['sendgridfromname']);
             $mail->addAddress($data['email'],$data['name']);
             $mail->isHTML(true);                                                               
             $mail->Subject = $data['subject'];
             $mail->Body=$html;
             $mail->send();
             //echo 'Message has been sent';
        }catch (Exception $e) {
          //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
         }
    }


    public function sendemails2($data,$html){
        $systeminfo=$this->CI->customlib->getSystemInfo();
        $this->CI->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_user' => 'apikey',
            'smtp_pass' =>$systeminfo['sendgridapkey'],
            'smtp_port' =>465,
            'crlf' => "\r\n",
            'newline' => "\r\n",
            'smtp_crypto'=>'tls',
          ));
          
         // print_r($systeminfo);
          
          $this->CI->email->from($systeminfo['sendgridfrom'],$systeminfo['sendgridfromname']);
          $this->CI->email->to($data['email']);
          $this->CI->email->subject($data['subject']);
          $this->CI->email->message($html);
          $this->CI->email->set_mailtype('html');
          $this->CI->email->send();
          
          //echo $this->CI->email->print_debugger();
          

    }

}
