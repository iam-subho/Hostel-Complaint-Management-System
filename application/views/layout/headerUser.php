<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <!------------------------------------------------------- CSS SOURCE FILES -------------------------------------------->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/jsRapStar.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/fontawesome.min.css');?>" rel="stylesheet">
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
