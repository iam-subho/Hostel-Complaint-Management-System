<?php
class Systemtask_model extends CI_Model{


  /*************************************************************** ROLES FUNCTIONS *******************************************************************/

  function getRolesList(){
    $this->db->select('*');
    $this->db->from('roles');
    $this->db->order_by('role_id');
    $query=$this->db->get();
    return $query->result_array();
  }


  /**************************************************************** PERMISSION FUNCTIONS ****************************************************************/

   function getPermissionList($id){
    $this->db->select('permissions.perm_id as permid,permissions.perm_short_code as shortcode');
    $this->db->from('permissions');
    $query=$this->db->get();
    $result=$query->result_array();
    $ans=array();
    foreach ($result as  $value) {
      $value1=array();
      $value1['role_id']=$id;
      $value1['shortcode']=$value['shortcode'];
      $value1['permid']=$value['permid'];
      $value1['can_view']=$this->getPermissions($value['permid'],$id,'can_view');
      $value1['can_add']=$this->getPermissions($value['permid'],$id,'can_add');
      $value1['can_edit']=$this->getPermissions($value['permid'],$id,'can_edit');
      $value1['can_delete']=$this->getPermissions($value['permid'],$id,'can_delete');
      $ans[]=$value1; 
    }
    return $ans;
     
   }

   function getPermissions($permid,$roleid,$permission ){
     $wherearray = array(
         'role_id' =>$roleid,
         'perm_cat_id' =>$permid,
          $permission =>1
       );
       $result=$this->db->select('id')->from('roles_permissions')->where($wherearray)->get()->row_array();
       if($result){
        return 1;
       }
       return 0;

   }

   function assignpermission($permid,$roleid,$data){
    $wherearray = array(
      'role_id' =>$roleid,
      'perm_cat_id' =>$permid,
    );
    $result=$this->db->select('id')->from('roles_permissions')->where($wherearray)->get()->row_array();
    if($result){
    $this->db->where($wherearray)->update('roles_permissions',$data);
    //echo $this->db->last_query();
    }else{
      $data['role_id'] = $roleid;
      $data['perm_cat_id'] =$permid;
      //$this->db->set($data);
      $this->db->insert('roles_permissions',$data); 
      //echo $this->db->last_query();
    }
   }


   /***************************************************************** WORKER FUNCTIONS ****************************************************************/

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

  /***************************************************************  COMPLAINT FUNCTIONS **************************************************************/

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