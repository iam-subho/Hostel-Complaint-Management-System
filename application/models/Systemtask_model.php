<?php
class Systemtask_model extends CI_Model{

  function getRolesList(){
    $this->db->select('*');
    $this->db->from('roles');
    $this->db->order_by('role_id');
    $query=$this->db->get();
    return $query->result_array();
  }

  function getPermissionList($id){
    $this->db->select('roles_permissions.*,permissions.perm_short_code as shortcode');
    $this->db->from('roles_permissions');
    $this->db->join('permissions', 'permissions.perm_id=roles_permissions.id');
    $this->db->where('roles_permissions.role_id',$id);
    $this->db->order_by('roles_permissions.id');
    $query=$this->db->get();
    return $query->result_array();

   }


   function getWorkerType($id=null){
    $this->db->select('worker_type.*');
    $this->db->from('worker_type');
    $this->db->order_by('worker_type.worker_type_id');
    if($id!= null){
    $this->db->where('worker_type.worker_type_id',$id);
    }

    $query=$this->db->get();

    if($id!= null){
        return $query->row_array();
    }else{
        return $query->result_array();
    }    

  }

  function getComplaintstatusList(){
    $this->db->select('*');
    $this->db->from('complaintstatus');
    $query=$this->db->get();
    return $query->result_array(); 
  }


  function getComplaintTypeList(){
    $this->db->select('*');
    $this->db->from('complaint_type');
    $query=$this->db->get();
    return $query->result_array(); 
  }


}