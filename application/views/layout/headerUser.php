<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <!------------------------------------------------------- CSS SOURCE FILES -------------------------------------------->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap.css');?>" rel="stylesheet">
    
    <link href="<?php echo base_url('assets/css/jsRapStar.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="<?php echo base_url('assets/css/dataTables.bootstrap4.min');?>" rel="stylesheet">
    

    
   




    
   <!------------------------------------------ JAVASCRIPT SOURCE FILES ------------------------------------------------->
    <script src="<?php echo base_url('assets/js/jquery.min321.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jsRapStar.js');?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap4.min.js');?>"></script>
     <!-- required to implement star rating functionality -->
   
</head>


<?php
$user=$this->session->userdata('user'); 
if($user){
$this->load->view('user/sidebar');
}
?>

<?php
 $type=''; $msg='';
 if($this->session->flashdata('flashSuccess')){
 $type="text-success";
 $msg=$this->session->flashdata('flashSuccess');
 }
 if($this->session->flashdata('flashError')){
 $type="text-danger"; 
 $msg=$this->session->flashdata('flashError');  
 }
 if($this->session->flashdata('flashInfo')){
 $type="text-info";
 $msg=$this->session->flashdata('flashInfo');
 }
 if($this->session->flashdata('flashWarning')){
 $type="text-warning";
 $msg=$this->session->flashdata('flashWarning');
 }
?>

<?php if($type):?>
<div class="toast" data-autohide="false" style="position: absolute; top:2; right: 0;">
    <div class="toast-header">
      <strong class="mr-auto  <?php echo $type ?>"><?php echo $msg ?></strong>      
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
<?php endif ?>  

<script>
$(document).ready(function(){
  $('.toast').toast('show');
});


</script>

