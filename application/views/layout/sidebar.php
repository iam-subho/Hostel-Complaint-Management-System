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
      content: '+';  
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
        <li class="nav-item <?php echo (strpos($url,'dashboard'))?'active':'' ?>">  
            <a class="nav-link" href="<?php echo base_url(); ?>userpanel/dashboard">Dashboard</a>  
          </li>   
          <li class="nav-item <?php echo activeornot(array('complaintList','chat','getcomplaint')) ?>">
            <a class="nav-link" href="<?php echo site_url('admin/admin/complaintList') ?>">Complaint List</a>  
          </li>  
          <li class="nav-item">  
            <a class="nav-link <?php echo activeornot(array('userList'))?>" href="<?php echo site_url('admin/admin/userList') ?>">User List</a>  
          </li>  
  
          <li class="nav-item">  
            <a class="nav-link" href="#"> Gallery </a>  
          </li>  

         <li class="nav-item">  
            <a class="nav-link nav-link-collapse" href="#" id="hasSubItems" data-toggle="collapse"  data-target="#collapseSubItems2" aria-controls="collapseSubItems2" aria-expanded="false"> System </a>  
         <ul class="nav-second-level collapse" id="collapseSubItems2" data-parent="#navAccordion">
         <?php if ($this->rbac->hasPrivilege('building', 'can_view')) {  ?>  
          <li class="nav-item">  
            <a class="nav-link" href="<?php echo base_url('admin/systemtask/getbuildinglist') ?>">  
              <span class="nav-link-text">Building</span>  
            </a>  
          </li>
          <?php }if ($this->rbac->hasPrivilege('complaint_type', 'can_view')) {  ?>  
          <li class="nav-item">  
          <a class="nav-link" href="<?php echo base_url('admin/systemtask/complainttypelist') ?>">  
              <span class="nav-link-text">Complaint Type</span>  
            </a>  
          </li>  
          <?php }if ($this->rbac->hasPrivilege('roles', 'can_view')) {  ?>
          <li class="nav-item">  
          <a class="nav-link" href="<?php echo base_url('admin/systemtask/getroleList') ?>">  
              <span class="nav-link-text">Roles</span>  
            </a>  
          </li>
          <?php }if ($this->rbac->hasPrivilege('permission', 'can_view')) {  ?>
          <li class="nav-item">  
          <a class="nav-link" href="<?php echo base_url('admin/systemtask/permissionadd') ?>">  
              <span class="nav-link-text">Permission Group</span>  
            </a>  
          </li>
          <?php }?>
        </ul>  
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

  } //echo $active;
 return $active;
}

?>