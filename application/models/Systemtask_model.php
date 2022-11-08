<?php
class Systemtask_model extends CI_Model{


  /*************************************************************** ROLES FUNCTIONS *******************************************************************/

  function getRolesList(){
    $this->db->select('roles.*,count(complaint.complaint_id) as total,staff.staff_id');
    $this->db->from('roles');
    $this->db->join('staff','staff.role_id = roles.role_id','left');
    $this->db->join('complaint', 'complaint.assignedTo=staff.staff_id','left');
    $this->db->group_by('roles.role_id');
    $query=$this->db->get();
    return $query->result_array();
  }

  function getAllRoleList(){
    $this->db->select('roles.*');
    $this->db->from('roles');
    $this->db->where('roles.status',1);
    $this->db->order_by('roles.role_id','desc');
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

   function getpermissionshortcodelist(){
    $this->db->select('*');
    $this->db->from('permissions');
    $query=$this->db->get();
    return $query->result_array();
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
    $this->db->select('complaint_type.*,count(complaint.complaint_id) as total,worker_type.type_name');
    $this->db->from('complaint_type');
    $this->db->join('complaint', 'complaint.complaint_type=complaint_type.typeid','left');
    $this->db->join('worker_type', 'worker_type.worker_type_id=complaint_type.handler_id','left');
    $this->db->group_by('complaint_type.typeid');
    $query=$this->db->get();
    return $query->result_array(); 
  }


  /*************************************************************** BUILDING  **************************************************************/

  function getBuildingList(){
    $this->db->select('building.*,count(complaint.complaint_id) as total, complaint.registeredBy,users.userid,users.building');
    $this->db->from('building');
    $this->db->join('users','users.building=building.buildingid','left');
    $this->db->join('complaint', 'complaint.registeredBy=users.userid','left');
    $this->db->group_by('building.buildingid');
    $query=$this->db->get();
    return $query->result_array(); 
  }

  /*************************************************************** NOTIFICATION **************************************************************/

  function getNotificationList(){
    $this->db->select('emailnotification.*');
    $this->db->from('emailnotification');
    $this->db->order_by('emailnotification.notid');
    $query=$this->db->get();
    return $query->result_array(); 
  }


}