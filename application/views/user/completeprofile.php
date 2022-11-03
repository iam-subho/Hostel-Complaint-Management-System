 <?php 
 //echo print_r($data) 
 ?>
<body>  
<section class="h-100 bg-dark">  
  <div class="container py-5 h-100">  
    <div class="row d-flex justify-content-center align-items-center h-100">  
      <div class="col">  
        <div class="card card-registration my-2">  
          <div class="row g-0">  
            <div class="col-xl-6 d-none d-xl-block">  
              <img  
                src="jas-1.png"  
                alt="Sample photo"  
                class="img-fluid"  
                style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;"  
              />  
            </div>  
            <div class="col-xl-6">  
              <div class="card-body p-md-5 text-black"> 
              <form method="POST" action="<?php echo base_url('sociallogin/completeprofile') ?>" > 
                <h3 class="mb-5 text-uppercase"> Complete your registration </h3>  
                <div class="row">  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1m">Name</label> 
                      <input type="text" name="name" id="form3Example1m" class="form-control form-control-lg" <?php echo readonlymaker($data['name']) ?>  value="<?php echo $data['name'] ?>" />
                      <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>  
                  </div>
                  <input name="userid"  hidden value="<?php echo base64_encode($data['userid'])?>" />  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1n">Mobile</label>
                      <input type="tel" name="mobile" id="form3Example1n" class="form-control form-control-lg" <?php echo readonlymaker($data['mobile']) ?> value="<?php echo $data['mobile'] ?>"  />    
                      <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                    </div>  
                  </div>  
                </div>  
                <div class="row">  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1m1">Username</label> 
                      <input type="text" name="username" id="username" class="form-control form-control-lg" <?php echo readonlymaker($data['username']) ?>  value="<?php echo $data['username'] ?>" />    
                      <span class="text-danger"><?php echo form_error('username'); ?></span>
                    </div>  
                  </div>  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline"> 
                    <label class="form-label" for="form3Example1n1">Room No</label>   
                      <input type="number" min="1" name="roomno" id="form3Example1n1" class="form-control form-control-lg" <?php echo readonlymaker($data['roomno']) ?> value="<?php echo $data['roomno'] ?>" />  
                      <span class="text-danger"><?php echo form_error('roomno'); ?></span>
                    </div>  
                  </div>  
                </div>
                <div class="form-outline mb-4">  
                <div class="form-outline">
                      <label class="form-label" for="form3Example1m">Email</label>  
                      <input type="text" name="email" id="form3Example1m" <?php echo readonlymaker($data['email']) ?> class="form-control form-control-lg" value="<?php echo $data['email'] ?>" />    
                      <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div> 
                </div>

                <div class="form-outline mb-4">  
                  <label class="form-label" for="form3Example8">Building Name</label> 
                  <select  class="form-control form-control-lg" name="building">
                    <option value="">--</option>
                    <?php foreach($building as $build) { ?>
                        <option value="<?php echo $build['buildingid']?>" <?php echo($build['buildingid']==$data['building'])?'selected':'' ?> > <?php echo $build['buildingname'] ?></option>
                    <?php } ?>
                    </select>  
                    <span class="text-danger"><?php echo form_error('building'); ?></span>
                </div>  
 
                <div class="form-outline mb-4">  
 
                </div>
                <div class="d-flex justify-content-end pt-3">  
                  <button type="submit" class="btn btn-warning btn-lg ms-2"> Submit form </button>  
                </div> 
                </form>
              </div>  
            </div>  
          </div>  
        </div>  
      </div>  
    </div>  
  </div>  
</section>  
</body>  

<?php 

function readonlymaker($data){

    if($data){
        return 'readonly';
    }
}

function readonlymakerselect($data){

    if($data){
        return 'disabled';
    }
}

?>
