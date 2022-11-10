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
        <div class="offset-md-2 col-lg-5 col-md-7 offset-lg-4 offset-md-3">
            <div class="panel border bg-white">
                <div class="panel-heading">
                    <h3 class="pt-3 font-weight-bold">Login</h3>
                </div>
                <div class="panel-body p-3">
                  <form method="post" action="<?php echo site_url('login/userauth') ?>" >
                        <div class="form-group py-2">
                            <div class="input-field"> 
                                <span class="far fa-user p-2"></span> 
                                <input type="text" name="username" placeholder="Username"  value="<?php echo set_value('username'); ?>" > 
                            </div>
                            <span class="text-danger"><?php echo form_error('username'); ?></span>
                        </div>
                        <div class="form-group py-1 pb-2">
                            <div class="input-field"> 
                                <span class="fas fa-lock px-2"></span> 
                                <input type="password" id="passwordf" name="password" placeholder="Enter your Password" > 
                                <div class="btn bg-white text-muted"><span id="eyeclass" class="far fa-eye-slash"  onclick="passwordhide()"></span> </div> 
                            </div>
                            <span class="text-danger"><?php echo form_error('password'); ?></span>
                        </div>
                        <div class="form-group py-2">
                            <div class="input-field"><div class="captcha_image"> 
							<?php if(isset($captchaImage)) echo $captchaImage; ?></div>
                            <div class="btn bg-white text-muted"><span class="fa fa-refresh" onclick="refreshCaptcha()" style="cursor:pointer"></span></div>
						
                            </div>
                        </div>
                        <div class="form-group py-1 pb-2">
                            <div class="input-field"> 
                                <span class="far fa-image px-2"></span> 
                                <input type="text" name="captcha" placeholder="Enter Captcha" > 
                            </div>
                        </div>
                        <div class="form-inline"> <a href="#" data-toggle="modal" data-target="#forgetModal" class="font-weight-bold">Forgot password?</a> </div>
                        <button class="btn btn-primary btn-block mt-3" type="submit">Login</button>
                        <div class="text-center pt-4 text-muted">Don't have an account? <a href="#">Sign up</a> </div>
                    </form>
                </div>
                <div class="mx-3 my-2 py-2 bordert">
                    <div class="text-center py-3"> 
                    <?php if($this->customlib->getSystemInfo()['foauthactive']==1) { ?>
                    <a href="<?php echo $LogonUrlfb ?>"><span><i class="fab fa-facebook-square fa-3x"></i></span></a>
                    <?php } if($this->customlib->getSystemInfo()['goauthactive']==1) { ?>
				    <a href="<?php echo $LogonUrlgm ?>"><span><i class="fab fa-google-plus-square fa-3x"></i></span></a>
                    <?php } if($this->customlib->getSystemInfo()['toauthactive']==1) {?>
                    <a href="<?php echo $LogonUrltw ?>"><span><i class="fab fa-twitter-square fa-3x"></i></span></a>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="forgetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user" style='color:red'></i>Forget Password</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Registered Email Address</label>
            <input type="text" class="form-control" id="email">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxforgetpassword()">Save</button>
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

var baseurl = "<?php echo base_url(); ?>";
var redirect = "<?php echo current_url(); ?>";


    function refreshCaptcha(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('login/refreshCaptcha'); ?>",
            data: {},
            success: function(captcha){
                $(".captcha_image").html(captcha);
            }
        });
    }


function ajaxforgetpassword() {
	var email = document.getElementById('email').value;
	
	$.ajax({
		type: "POST",
		url: baseurl + "login/forgetuserpassword",
		data: {email:email},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			$('#forgetModal').modal('hide');
			if (parseInt(data.status) === 1) {

				swal({
					title: "Check your email address",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					//window.location = redirect;
				});

			} else {
				swal({
					title:'Email is not registered',
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					//window.location = redirect;
				});
			}
		},

	});
}




</script>