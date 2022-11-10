<style>

html,body,.bg{
background-image: url(<?php echo base_url("assets/system_img/loginbg.jpg");?>);
background-size: cover;
background-repeat: no-repeat;

font-family: 'Numans', sans-serif;
}

</style>
<div class="container-fluid" style="">
            <div class="" style="margin-top:5%">
                <div class="rounded d-flex justify-content-center">
                    <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
                        <div class="text-center">
                            <h3 class="text-primary">Create Account</h3>
                        </div>
                        <div class="p-4">
                            <form action="<?php echo base_url("login/signupsubmit")?>" method="post">
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i class="fa fa-user fa-lg" style="font-size:!important 16px"></i></span>
                                    <input type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" placeholder="Name">
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-check fa-lg" style="font-size:16px"></i></span>
                                    </div>
                                    
                                    <input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>" placeholder="Username">
                                    <span class="text-danger"><?php echo form_error('username'); ?></span>
                                   
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i class="fa fa-envelope fa-lg"></i></span>
                                    <input type="email" class="form-control" value="<?php echo set_value('email'); ?>" name="email" placeholder="Email">
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i class="fa fa-mobile fa-lg"></i></span>
                                    <input type="email" class="form-control" value="<?php echo set_value('mobile'); ?>" name="mobile" placeholder="Mobile">
                                    <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                                </div>
                                <div class="input-group mb-3">
                                <div class="input-field">
                                    <span class="input-group-text bg-primary"><i class="fa fa-key fa-lg"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i class="fa fa-home fa-lg"></i></span>
                                    <select class="form-control" name="building">
                                        <option value="">Select Building</option>
                                        <?php foreach($buildinglist as $building){ ?>
                                            <option value="<?php echo $building['buildingid']?>"><?php echo $building['buildingname']?></option>
                                        <?php } ?>
                                   </select>
                                   <span class="text-danger"><?php echo form_error('building'); ?></span>
                                </div>
                                    <div class="input-group mb-3">
                                    <span class="input-group-text bg-primary"><i class="fa fa-home fa-lg"></i></span>
                                    <input type="password" class="form-control" value="<?php echo set_value('roomno'); ?>" name="roomno" placeholder="Room No">
                                    <span class="text-danger"><?php echo form_error('roomno'); ?></span>
                                </div>
                                <div class="d-grid col-12 mx-auto text-center">
                                    <button class="btn btn-primary" type="submit"><span></span> Sign up</button>
                                </div>
                                <p class="text-center mt-3">Already have an account?
                                    <span class="text-primary"><a href="<?php echo base_url("login")?>">Sign in</a></span>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>