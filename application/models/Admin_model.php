<?php
class Admin_model extends CI_Model{


  /**************************************************************** COMPLAINT FUNCTIONS ************************************************************/
 
 function getComplaintList($complatinid=null,$staff=null){
   $this->db->select('*');
   $this->db->from('complaint');

    if($staff != null){
    $this->db->where('assignedTo',$staff);
    }

    if($complatinid!=null){
    $this->db->where('complaint.complaint_id',$complatinid);
    }

    $this->db->order_by('complaint.complaint_id');
    $query = $this->db->get();
    if($complatinid!= null){
        return $query->row_array();
      }else{
        return $query->result_array();
      }

 }

 /**************************************************************** USERS FUNCTIONS ************************************************************/

 function getUserList($userid=null,$all=null){
  $this->db->select('users.*,buildingname');
  $this->db->from('users');
  $this->db->join('building','building.buildingid = users.building');
  if($all==null){
  $this->db->where('users.is_active !=2');
  }
  if($userid!= null){
  $this->db->where('users.userid',$userid);
  }
  $this->db->order_by('users.userid');
  $query=$this->db->get();
  if($userid!= null){
    return $query->row_array();
  }else{
    return $query->result_array();
  }
 }

 /********************************************************************** STAFF FUNCTIONS **************************************************************/

 function getStaffList($staffid=null){
    $this->db->select('staff.*,roles.role_name as role,worker_type.type_name as workertype');
    $this->db->from('staff');
    $this->db->join('roles','roles.role_id=staff.role_id');
    $this->db->join('worker_type','worker_type.worker_type_id=staff.worker_type');
    $this->db->order_by('staff.staff_id');
    if($staffid!= null){
    $this->db->where('staff.staff_id',$staffid);
    }

    $query=$this->db->get();

    if($staffid!= null){
        return $query->row_array();
    }else{
        return $query->result_array();
    }
    

 }
 
}