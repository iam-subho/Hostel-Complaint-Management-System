<?php
class Admin_model extends CI_Model{


  /**************************************************************** COMPLAINT FUNCTIONS ************************************************************/
 
 function getComplaintList($complatinid=null,$staff=null,$status=null){
  $this->db->select('complaint.complaintStatus,complaintstatus.status compstatus, complaint.stars as stars, complaint.feedback as feedback,complaint.assignedTo,
  IFNULL(staff.name,"Not Assigned") as staffname,complaint.registeredBy,complaint.complaint_type,complaint.description,users.name,users.building,building.buildingname,
  users.roomno,complaint_type.typename,complaint_type.personal,complaint.complaintDate,complaint.lastupdate,complaint.paymentTransactionId,
  complaint.paymentDate,complaint.complaint_id,complaint.complaintNo,complaint_type.handler_id');
  $this->db->from('complaint');
  $this->db->join('staff','staff.staff_id = complaint.assignedTo','left');
  $this->db->join('users','users.userid= complaint.registeredBy');
  $this->db->join('complaint_type','complaint_type.typeid=complaint.complaint_type');
  $this->db->join('building','building.buildingid=users.building');
  $this->db->join('complaintstatus','complaintstatus.statusId=complaint.complaintStatus');

    if($staff != null){
    $this->db->where('assignedTo',$staff);
    }

    if($complatinid!=null){
    $this->db->where('complaint.complaint_id',$complatinid);
    }

    if($status !=null){
      $this->db->where('complaint.complaintStatus',$status);
    }

    $this->db->order_by('complaint.complaint_id');
    $query = $this->db->get();
    if($complatinid!= null){
        return $query->row_array();
      }else{
        return $query->result_array();
      }

 }

 function getComplaintHistory($id,$user=null){
  //this function used to fetch complaint history for a particular complaint and user
  //we take complaint id and user id of logged in user and return result as array
  $this->db->select('complainthistory.*,complaint.complaint_id,complaint.assignedTo');
  $this->db->from('complainthistory');
  $this->db->join('complaint','complaint.complaint_id=complainthistory.complaintId');
  //$this->db->where('complaint.registeredBy',$user);
  $this->db->where('complainthistory.complaintId',$id);
  $this->db->order_by('complainthistory.comphistid','asc');
  $query = $this->db->get();
  //echo $this->db->last_query();
  return $query->result_array();

  }

  function getextraPayment($id,$user=null){
    //this function used to fetch extra payment requirement details for a particular complaint and user
   //we take complaint id and user id of logged in user and return result as array
      $this->db->select('extrapayment.*,complaint.complaint_id,staff.name as raiseby,staff.staff_id');
      $this->db->from('extrapayment');
      $this->db->join('complaint','complaint.complaint_id=extrapayment.complaintid');
      $this->db->join('staff','staff.staff_id=extrapayment.raisedby','left');
      //$this->db->where('complaint.registeredBy',$user);
      $this->db->where('extrapayment.complaintid',$id);
      $this->db->order_by('extrapayment.extrapaymentid','asc');
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result_array();
   }

   function workerassign($comp,$staffid){
      $up=array('assignedTo' => $staffid,'complaintStatus'=>4,'lastupdate'=>time());
      $up2=array('staffid' => $staffid,);
      $this->db->where('complaint_id',$comp)->update('complaint',$up);
      if($this->db->affected_rows()>0){
        $first=1;
      }else{
        $first=0;
       }
     $prev=$this->db->select('*')->from('chatroom')->where('complaintid',$comp)->get()->row_array();
     $useridarr=$this->db->select('registeredBy')->from('complaint')->where('complaint_id',$comp)->get()->row_array();
     $userid=$useridarr['registeredBy'];
     $insertArray=array(
      'complaintid' =>$comp,
      'staffid' => $staffid,
      'userid' =>$userid,
      'active' =>1,
     );
     if($prev){
       $this->db->where('complaintid',$comp)->update('chatroom',$up2);
      }else{
       $this->db->insert('chatroom',$insertArray);
     }

     if($this->db->affected_rows()>0){
       $sec=1;
      }else{
       $sec=0;
     }

     if($sec==1 && $first==1){
       $stat=1;$error='';
      }else{
       $stat=0;$error='Error';  
     }

     $array = array('status' => $stat, 'error' =>$error);
     return $array;

   }
 

 /**************************************************************** USERS FUNCTIONS ************************************************************/

 function getUserList($userid=null,$all=null){
  $this->db->select('users.*,buildingname');
  $this->db->from('users');
  $this->db->join('building','building.buildingid = users.building');
  if($all!=null){
  $this->db->where('users.status',$all);
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

 function getStaffList($staffid=null,$status=null){
    $this->db->select('staff.*,roles.role_name as role,worker_type.type_name as department,count(complaint.complaint_id) as total');
    $this->db->from('staff');
    $this->db->join('roles','roles.role_id=staff.role_id');
    $this->db->join('worker_type','worker_type.worker_type_id=staff.worker_type');
    $this->db->join('complaint','complaint.assignedTo=staff.staff_id','left');
    $this->db->group_by('staff.staff_id');
    if($staffid!= null){
    $this->db->where('staff.staff_id',$staffid);
    }
    if($status!= null){
      $this->db->where('staff.status',$status);
    }
    $this->db->where('roles.role_id !=',1);
    $query=$this->db->get();
    //echo $this->db->last_query();
    if($staffid!= null){
      
        return $query->row_array();
    }else{
        return $query->result_array();
    }
    

 }


 function getSpecifiTyperWorker($type){
  $this->db->select('staff.*,roles.role_name as role,worker_type.type_name as workertype');
  $this->db->from('staff');
  $this->db->join('roles','roles.role_id=staff.role_id');
  $this->db->join('worker_type','worker_type.worker_type_id=staff.worker_type');
  $this->db->where('staff.worker_type',$type);
  $this->db->order_by('staff.staff_id');
  $query= $this->db->get();
  return $query->result_array();
 }
 
}