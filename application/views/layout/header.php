<style>
.overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 9999;
    background: rgba(255,255,255,0.8) url("<?php echo base_url('assets/loader.gif') ?>") center no-repeat;
}

/* Turn off scrollbar when body element has the loading class */
body.loading{
    overflow: hidden;   
}
/* Make spinner image visible when body element has the loading class */
body.loading .overlay{
    display: block;
}

.overlayer{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 9999;
    background: rgba(255,255,255,0.8) url("<?php echo base_url('assets/page.gif') ?>") center no-repeat;
}
/* Turn off scrollbar when body element has the loading class */
body.pageloader{
    overflow: hidden;   
}
/* Make spinner image visible when body element has the loading class */
body.pageloader .overlayer{
    display: block;
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <!------------------------------------------------------- CSS SOURCE FILES -------------------------------------------->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/fontawesome.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap4.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/sweetalert2.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link href="<?php echo base_url('assets/css/jsRapStar.css');?>" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    
   <!------------------------------------------ JAVASCRIPT SOURCE FILES ------------------------------------------------->
    <script src="<?php echo base_url('assets/js/jquery.min321.js');?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap4.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jsRapStar.js');?>"></script>  <!-- required to implement star rating functionality -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js');?>"></script>


</head>


<?php
$user=$this->session->userdata('admin'); 
if($user){
$this->load->view('layout/sidebar');
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
<div class="toast"  data-autohide="false" style="position:absolute; top:2; right: 0;z-index:1111;">
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

$(document).on({
    ajaxStart: function(){
        $("body").addClass("loading"); 
        console.log("Loading");
    },
    ajaxStop: function(){ 
       // $("body").removeClass("loading"); 
       setTimeout(function(){
     $("body").removeClass("loading");  
 },100);
    }    
});
</script>
<body>
<div class="overlay"></div>
<div class="overlayer"></div>


<!-- global loader before every page load -->

<script type="text/javascript">
$("body").addClass("pageloader");

	 $(window).on('load', function () {
     setTimeout(function(){
     $("body").removeClass("pageloader"); 
 }, 500);
 });
 

</script>