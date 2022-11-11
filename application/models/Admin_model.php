<?php
class Admin_model extends CI_Model{


  /**************************************************************** COMPLAINT FUNCTIONS ************************************************************/
 
 function getComplaintList($complatinid=null,$staff=null,$status=null,$limit=null){
  $this->db->select('complaint.complaintStatus,complaint.subject,complaintstatus.status compstatus, complaint.stars as stars, complaint.feedback as feedback,complaint.assignedTo,
  IFNULL(staff.name,"Not Assigned") as staffname,complaint.registeredBy,complaint.complaint_type,complaint.description,users.name,users.building,building.buildingname,
  users.roomno,complaint_type.typename,complaint_type.personal,complaint.complaintDate,complaint.lastupdate,complaint.paymentTransactionId,
  complaint.paymentDate,complaint.complaint_id,complaint.complaintNo,complaint_type.handler_id,complaint_type.paymentAmount');
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

    if ($limit !=null) {
      $this->db->limit($limit);
    }


    $this->db->order_by('complaint.complaint_id','desc');
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

  function getextraPayment($id,$user=null,$staff=null){
    //this function used to fetch extra payment requirement details for a particular complaint and user
   //we take complaint id and user id of logged in user and return result as array
      $this->db->select('extrapayment.*,complaint.complaint_id,staff.name as raiseby,staff.staff_id');
      $this->db->from('extrapayment');
      $this->db->join('complaint','complaint.complaint_id=extrapayment.complaintid');
      $this->db->join('staff','staff.staff_id=extrapayment.raisedby','left');
      //$this->db->where('complaint.registeredBy',$user);
      $this->db->where('extrapayment.complaintid',$id);
      if($staff!=null){
      $this->db->where('extrapayment.raisedby',$staff);
      }
      $this->db->order_by('extrapayment.extrapaymentid','asc');
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result_array();
   }

   function getextraPaymentTotal($id,$staff=null,$status=null){
    //this function used to fetch extra payment requirement details for a particular complaint and user
   //we take complaint id and user id of logged in user and return result as array
   // 1-paid,2-Not paid
      $this->db->select('IFNULL(SUM(extrapayment.amount),0) as totalextra');
      $this->db->from('extrapayment');
      $this->db->where('extrapayment.complaintid',$id);
      if($staff!=null){                           
      $this->db->where('extrapayment.raisedby',$staff);
      }
      $this->db->order_by('extrapayment.extrapaymentid','asc');
      
      if($status==1){
      $this->db->where('extrapayment.transactionid !=','');
      }else if($status==2){
       $this->db->where('extrapayment.transactionid ',null);
      }
      $this->db->group_by('extrapayment.complaintid');
      $query = $this->db->get();
      ///echo $this->db->last_query();die();
      return $query->row_array();
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
      $this->db->where('complaintid',$comp)->delete('chatroom');
      $this->db->insert('chatroom',$insertArray);
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
  $this->db->join('building','building.buildingid = users.building','left');
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

 function getComplaintListUser($complatinid=null,$userid=null,$status=null){
  $this->db->select('complaint.complaintStatus,complaint.subject,complaintstatus.status compstatus, complaint.stars as stars, complaint.feedback as feedback,complaint.assignedTo,
  IFNULL(staff.name,"Not Assigned") as staffname,complaint.registeredBy,complaint.complaint_type,complaint.description,users.name,users.building,building.buildingname,
  users.roomno,complaint_type.typename,complaint_type.personal,complaint.complaintDate,complaint.lastupdate,complaint.paymentTransactionId,
  complaint.paymentDate,complaint.complaint_id,complaint.complaintNo,complaint_type.handler_id,complaint_type.paymentAmount');
  $this->db->from('complaint');
  $this->db->join('staff','staff.staff_id = complaint.assignedTo','left');
  $this->db->join('users','users.userid= complaint.registeredBy');
  $this->db->join('complaint_type','complaint_type.typeid=complaint.complaint_type');
  $this->db->join('building','building.buildingid=users.building');
  $this->db->join('complaintstatus','complaintstatus.statusId=complaint.complaintStatus');
  if($userid != null){
    $this->db->where('registeredBy',$userid);
  }
  if($status !=null){
    $this->db->where('complaint.complaintStatus',$status);
  }

  $this->db->order_by('complaint.complaint_id','desc');
  $query = $this->db->get();
  if($complatinid!= null){
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


 function getSpecifiTyperWorker($type=null){
  $this->db->select('staff.*,roles.role_name as role,worker_type.type_name as workertype');
  $this->db->from('staff');
  $this->db->join('roles','roles.role_id=staff.role_id');
  $this->db->join('worker_type','worker_type.worker_type_id=staff.worker_type');
  if($type!=null){
  $this->db->where('staff.worker_type',$type);
  }else{
    $this->db->where('staff.role_id',2); 
  }
  $this->db->order_by('staff.staff_id');
  $query= $this->db->get();
  return $query->result_array();
 }

 function getStaffProfile($staffid=null,$status=null){
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
  $query=$this->db->get();
  if($staffid!= null){
    
      return $query->row_array();
  }else{
      return $query->result_array();
  }
  

}

 /*************************************************************************** PAYMENT REPORTS  *************************************************************/

 public function getPaymentReport($userid=null){

  $this->db->select('complaint.complaint_id,complaint.complaintNo,COALESCE(SUM(extrapayment.amount),0) as totalextra,complaint.complaint_type,COALESCE(complaint_type.paymentAmount,0) as compamount');
  $this->db->from('complaint');
  $this->db->join('extrapayment','extrapayment.extrapaymentid=complaint.complaint_id','left');
  $this->db->join('complaint_type','complaint_type.typeid=complaint.complaint_type','left');
  $this->db->where('complaint.assignedTo',$userid);
  $this->db->where('extrapayment.raisedby',$userid);
  $this->db->group_by('complaint.complaint_id');
  $query= $this->db->get();
  echo $this->db->last_query();
  return $query->result_array();
 }


 
}