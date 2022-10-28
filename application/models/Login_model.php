<?php
class Login_model extends CI_Model{
 
  function validate($email,$password){
    $this->db->where('username',$email);
    $this->db->where('password',$password);
    $result = $this->db->get('staff',1);
    return $result;
  }
 
}