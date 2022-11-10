<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif
}

body {
height: 100vh;
background: rgb(2,0,36);
background: radial-gradient(circle, rgba(2,0,36,1) 0%, rgba(97,202,203,1) 44%, rgba(0,212,255,1) 100%);
}

.container {
    margin: 50px auto
}

.panel-heading {
    text-align: center;
    margin-bottom: 10px
}

a:hover {
    text-decoration: none
}

.form-inline label {
    padding-left: 10px;
    margin: 0;
    cursor: pointer
}

.btn.btn-primary {
    margin-top: 20px;
    border-radius: 15px
}

.panel {
    min-height: 380px;
    box-shadow: 20px 20px 80px rgb(218, 218, 218);
    border-radius: 12px
}

.input-field {
    border-radius: 5px;
    padding: 5px;
    display: flex;
    align-items: center;
    cursor: pointer;
    border: 1px solid #ddd;
    color: #4343ff
}

input[type='text'],
input[type='password'] {
    border: none;
    outline: none;
    box-shadow: none;
    width: 100%
}

.fa-eye-slash.btn {
    border: none;
    outline: none;
    box-shadow: none
}

@media(max-width: 360px) {
    body {
        height: 100%
    }

    .container {
        margin: 30px 0
    }

}
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel border bg-white">
                <div class="panel-heading">
                    <h3 class="pt-3 font-weight-bold">Resident Registration</h3>
                </div>
                <div class="panel-body p-3">
                    <form action="<?php echo base_url("login/signupsubmit")?>" method="POST">
                        <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="far fa-user p-2"></span> 
                                <input type="text" name="name" placeholder="Name"  value="<?php echo set_value('name'); ?>" > 
                            </div>
                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="far fa-user p-2"></span> 
                                <input type="text" name="username" placeholder="Username"  value="<?php echo set_value('username'); ?>" > 
                            </div>
                            <span class="text-danger"><?php echo form_error('username'); ?></span>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="fa fa-envelope p-2"></span> 
                                <input type="text" name="email" placeholder="Email"  value="<?php echo set_value('email'); ?>"> 
                            </div>
                            <span class="text-danger"><?php echo form_error('email'); ?></span>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="fa fa-mobile p-2"></span> 
                                <input type="text" name="mobile" placeholder="Mobile"  value="<?php echo set_value('mobile'); ?>" > 
                            </div>
                            <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="fa fa-home p-2"></span> 
                                     <select class="form-control" name="building">
                                        <option value="">--</option>
                                        <?php foreach($buildinglist as $building){ ?>
                                            <option value="<?php echo $building['buildingid']?>"><?php echo $building['buildingname']?></option>
                                        <?php } ?>
                                   </select> 
                            </div>
                            <span class="text-danger"><?php echo form_error('building'); ?></span>
                        </div>
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="fa fa-home p-2"></span> 
                                <input type="text" name="roomno" placeholder="Room No"  value="<?php echo set_value('roomno'); ?>" > 
                            </div>
                            <span class="text-danger"><?php echo form_error('roomno'); ?></span>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-lg-6">
                            <div class="input-field"> 
                                <span class="fas fa-lock px-2"></span> 
                                <input type="password" id="passwordf" name="password" placeholder="Enter your Password" > 
                                <div class="btn bg-white text-muted"><span id="eyeclass" class="far fa-eye-slash"  onclick="passwordhide()"></span> </div> 
                            </div>
                            <span class="text-danger"><?php echo form_error('password'); ?></span>
                        </div>
                        </div>
                        <button class="btn btn-primary" type="submit" style="text-align: center">Register</button>
                        <div class="text-center pt-4 text-muted">You have an account? <a href="<?php echo base_url('login')?>">Signin</a> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





           



<script>
function passwordhide() {

  var x = document.getElementById("passwordf");
  var eye=document.getElementById("eyeclass");
  if (x.type === "password") {
    x.type = "text";
    eyeclass.className ="far fa-eye";
  } else {
    x.type = "password";
    eyeclass.className ="far fa-eye-slash";
  }
}
</script>