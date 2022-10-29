<?php

Class Userpanel_model extends CI_Model {


   function addComplaint($data){
    $this->db->insert('complaint', $data);
    $lastid=$this->db->insert_id();
    $history=array(
      'complaintId' => $lastid,
      'compstatus' =>'Complaint Registered in portal',
      'statusupdate'=>time(),
    );
    $this->db->insert('complainthistory', $history);
    return $lastid;
   }

   function updatePayment($registrationData,$insertid){
    $this->db->where('complaint_id',$insertid)->update('complaint',$registrationData);
    $history=array(
      'complaintId' => $insertid,
      'compstatus' =>'Amount Paid successfully',
      'statusupdate'=>time(),
    );
    $this->db->insert('complainthistory', $history);
   }

   function updateExtraPayment($registrationData,$insertid){
      $this->db->where('extrapaymentid',$insertid)->update('extrapayment',$registrationData);
      $check=$this->db->select('complaintid')->from('extrapayment')->where('extrapaymentid',$insertid)->get()->row_array();

      $history=array(
         'complaintId' => $check['complaintid'],
         'compstatus' =>'Extra Amount Paid successfully',
         'statusupdate'=>time(),
       );
       $this->db->insert('complainthistory', $history);
   }

   function getComplaintList($userid){

      $this->db->select('complaint.*,complaintstatus.status as compstatus,complaint_type.*,IFNULL(staff.name,"Not Assigned") as staffname');
      $this->db->from('complaint');
      $this->db->join('complaintstatus','complaintstatus.statusId =complaint.complaintStatus');
      $this->db->join('complaint_type','complaint_type.typeid =complaint.complaint_type');
      $this->db->join('staff','staff.staff_id = complaint.assignedTo','left');
      $this->db->where('complaint.registeredBy',$userid);
      $this->db->order_by('complaint.complaint_id');
      $query = $this->db->get();
      return $query->result_array();

   }

   function getComplaintHistory($id,$user){
   //this function used to fetch complaint history for a particular complaint and user
   //we take complaint id and user id of logged in user and return result as array
   $this->db->select('complainthistory.*,complaint.complaint_id');
   $this->db->from('complainthistory');
   $this->db->join('complaint','complaint.complaint_id=complainthistory.complaintId');
   $this->db->where('complaint.registeredBy',$user);
   $this->db->where('complainthistory.complaintId',$id);
   $this->db->order_by('complainthistory.comphistid','asc');
   $query = $this->db->get();
   //echo $this->db->last_query();
   return $query->result_array();

   }

   function getextraPayment($id,$user){
    //this function used to fetch extra payment requirement details for a particular complaint and user
   //we take complaint id and user id of logged in user and return result as array
      $this->db->select('extrapayment.*,complaint.complaint_id,staff.name as raiseby,staff.staff_id');
      $this->db->from('extrapayment');
      $this->db->join('complaint','complaint.complaint_id=extrapayment.complaintid');
      $this->db->join('staff','staff.staff_id=extrapayment.raisedby');
      $this->db->where('complaint.registeredBy',$user);
      $this->db->where('extrapayment.complaintid',$id);
      $this->db->order_by('extrapayment.extrapaymentid','asc');
      $query = $this->db->get();
      //echo $this->db->last_query();
      return $query->result_array();
   }





}

?>