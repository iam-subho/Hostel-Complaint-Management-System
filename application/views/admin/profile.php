
<div class="row">
 <div class="col-md-12">
    <div class="card-body">
        <div class="row show-grid ">
            <div class="col-md-12 bg-secondary text-center"><strong><h4>Profile Modification</h4></strong></div>
        </div>
   <div class="clearfix"></div><br>             
    <form method="post" action="<?php echo base_url('admin/admin/updateProfile') ?>" >
     <input type="hidden" name="identity" id="identity" value="<?php echo base64_encode($profile['staff_id']) ?>" />
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
             <div class="col-sm-10">
               <input type="text" name="name"  class="form-control" id="staticEmail" value="<?php echo $profile['name'] ?>">
               <span class="text-danger"><?php echo form_error('name'); ?></span>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Username</label>
             <div class="col-sm-10">
             <input type="hidden" name="newusername" class="form-control" id="newusername"/>
               <input type="text" name="username" onkeyup="setusername()" class="form-control" id="username" value="<?php echo $profile['username'] ?>">
               <span class="text-danger"><?php echo form_error('newusername'); ?></span>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
             <div class="col-sm-10">
              <input type="hidden" name="newemail" class="form-control" id="newemail"/>
               <input type="text" name="email" onkeyup="setemail()" class="form-control" id="email" value="<?php echo $profile['email'] ?>">
               <span class="text-danger"><?php echo form_error('newemail'); ?></span>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Mobile</label>
             <div class="col-sm-10">
               <input type="text" name="mobile"  class="form-control" id="staticEmail" value="<?php echo $profile['mobile'] ?>">
               <span class="text-danger"><?php echo form_error('mobile'); ?></span>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Role</label>
             <div class="col-sm-10">
             <?php echo $profile['role'] ?>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Department</label>
             <div class="col-sm-10">
             <?php echo $profile['department'] ?>
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Password</label>
             <div class="col-sm-10">
               <input type="text" name="password"  class="form-control" id="staticEmail" value="" placeholder="Left blank if you dont want to change password">
             </div>
           </div>
           <div class="form-group row">
            <button type="submit" class="btn btn-success">Update</button>
            </div>
   </form>


    </div>
    
 </div>

</div>

<script>
  function setemail(){
    email=document.getElementById('email').value;
    document.getElementById('newemail').value=email;

  }
  function setusername(){
    email=document.getElementById('username').value;
    document.getElementById('newusername').value=email;

  }
</script>