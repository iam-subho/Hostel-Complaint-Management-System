<?php
class Login_model extends CI_Model{
 
  function validate($email,$password){
    $this->db->where('username',$email);
    $this->db->where('password',$password);
    $result = $this->db->get('staff',1);
    return $result;
  }


  function validateuser($email,$password){
    $this->db->where('username',$email);
    $this->db->where('password',$password);
    $result = $this->db->get('users',1);
    return $result;
  }

  function validateoauth($where){
   $this->db->where($where);
   $result = $this->db->get('users',1);
   return $result;
  }

  function insertoauth($insar){
    $this->db->insert('users',$insar);
    $id=$this->db->insert_id();
    $this->db->where('userid',$id);
    $result = $this->db->get('users',1);
    return $result;
  }

  function buildinglist(){
    $this->db->select('*');
    $this->db->from('building');
    $this->db->where('status',1);
    $query=$this->db->get();
    return $query->result_array();
  }
 
}