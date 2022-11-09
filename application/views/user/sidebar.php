<style>  
    html {  
        position: relative;  
        min-height: 100%;  
    }  
    body {  
        padding-top: 4.5rem;  
        margin-bottom: 4.5rem;  
    }  
    .footer {  
      position: absolute;  
      bottom: 0;  
      width: 100%;  
      height:1.5rem;  
      line-height:1.5rem;  
      background-color: #ccc;  
    }  
    .bg-dark {  
        background-color: #6a9aca!important;  
    }  
    .nav-link:hover {  
      transition: all 0.4s;  
    }  
    .nav-link-collapse:after {  
      float: right;  
      content: '\f067';  
      font-family: 'FontAwesome';  
    }  
    .nav-link-show:after {  
      float: right;  
      content: '-';  
      font-family: 'FontAwesome';  
    }  
    .nav-item ul.nav-second-level {  
      padding-left: 0;  
    }  
    .nav-item ul.nav-second-level > .nav-item {  
      padding-left: 20px;  
    }  
    @media (min-width: 992px) {  
      .sidenav {  
        position: absolute;  
        top: 0;  
        left: 0;  
        width: 230px;  
        height: calc(100vh - 3.5rem);  
        margin-top: 3.5rem;  
        background: #343a40;  
        box-sizing: border-box;  
        border-top: 1px solid rgba(0, 0, 0, 0.3);  
      }  
      .navbar-expand-lg .sidenav {  
        flex-direction: column;  
      }  
      .content-wrapper {  
        margin-left: 230px;  
      }  
      .footer {  
        width: calc(100% - 230px);  
        margin-left: 230px;  
      }  
    }  
    </style>  
    <body> <?php $url=current_url(); ?> 
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">  
      <a class="navbar-brand" href="#">Public Grievance</a>  
      <button  
        class="navbar-toggler"  
        type="button"  
        data-toggle="collapse"  
        data-target="#navbarCollapse"  
        aria-controls="navbarCollapse"  
        aria-expanded="false"  
        aria-label="Toggle navigation">  
        <span class="navbar-toggler-icon"> </span>  
      </button>  
      <div class="collapse navbar-collapse" id="navbarCollapse">  
        <ul class="navbar-nav mr-auto sidenav" id="navAccordion">
        <li class="nav-item <?php echo set_Topmenu('dashboard'); ?>">  
            <a class="nav-link" href="<?php echo base_url(); ?>userpanel/dashboard"><span><i class="fa fa-tachometer-alt" aria-hidden="true"></i> Dashboard</a>  
          </li>   
          <li class="nav-item <?php echo set_Topmenu('complaint'); ?>">
            <a class="nav-link" href="<?php echo base_url(); ?>userpanel/complaintlist"><span><i class="fa fa-calendar"></i></span> Complaint List</a>  
          </li>  
          <li class="nav-item <?php echo set_Topmenu('register'); ?>">  
            <a class="nav-link" href="<?php echo base_url(); ?>register"><span><i class="fa fa-compact-disc"></i></span> Lodge Complaint</a>  
          </li>
          <li class="nav-item <?php echo set_Topmenu('profile'); ?>">  
            <a class="nav-link" href="<?php echo base_url(); ?>userpanel/profile"><span><i class="fa fa-user"></i></span> Profile</a>  
          </li> 

          <li class="nav-item <?php echo set_Topmenu('logout'); ?>">  
            <a class="nav-link" href="<?php echo base_url(); ?>login/logout"><span><i class="fas fa-sign-out-alt"></i></span> Logout</a>  
          </li>    
           
        </ul>  
 
      </div>  
    </nav>  
    <main class="content-wrapper">  
      <div class="container-fluid">
        
      
<?php 
function activeornot($keys){
  $active='';
  $url=current_url();
  foreach($keys as $key){
 
    if(strpos($url,$key)==true){
    $active='active';
    }

  }
 return $active;
}

?>