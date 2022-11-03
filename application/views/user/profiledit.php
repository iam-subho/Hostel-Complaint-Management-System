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
                <h3 class="mb-5 text-uppercase"> Complete your registration </h3>  
                <div class="row">  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1m">Name</label> 
                      <input type="text" id="form3Example1m" class="form-control form-control-lg" value="<?php echo $data['name'] ?>" />
                       
                    </div>  
                  </div>  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1n">Mobile</label>
                      <input type="text" id="form3Example1n" class="form-control form-control-lg" value="<?php echo $data['mobile'] ?>"  />    
                    </div>  
                  </div>  
                </div>  
                <div class="row">  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline">  
                      <label class="form-label" for="form3Example1m1">Username</label> 
                      <input type="text" id="form3Example1m1" class="form-control form-control-lg" value="<?php echo $data['username'] ?>" />    
                    </div>  
                  </div>  
                  <div class="col-md-6 mb-4">  
                    <div class="form-outline"> 
                    <label class="form-label" for="form3Example1n1">Room No</label>   
                      <input type="text" id="form3Example1n1" class="form-control form-control-lg" value="<?php echo $data['roomno'] ?>" />  
                    </div>  
                  </div>  
                </div>
                <div class="form-outline mb-4">  
                <div class="form-outline">
                      <label class="form-label" for="form3Example1m">Email</label>  
                      <input type="text" id="form3Example1m" class="form-control form-control-lg" value="<?php echo $data['email'] ?>" />    
                    </div> 
                </div>

                <div class="form-outline mb-4">  
                  <label class="form-label" for="form3Example8">Building Name</label> 
                  <select <?php echo readonlymaker($data['building']) ?> class="form-control form-control-lg" name="building">
                    <option value="">--</option>
                    <?php foreach($building as $build) { ?>
                        <option value="<?php echo $build['buildingid']?>" <?php echo($build['buildingid']==$data['building'])?'selected':'' ?> > <?php echo $build['buildingname'] ?></option>
                    <?php } ?>
                    </select>  
                </div>  
 
                <div class="form-outline mb-4">  
 
                </div>
                <div class="d-flex justify-content-end pt-3">  
                  <button type="button" class="btn btn-warning btn-lg ms-2"> Submit form </button>  
                </div> 

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

    if(!$data){
        return 'readonly';
    }
}

?>
