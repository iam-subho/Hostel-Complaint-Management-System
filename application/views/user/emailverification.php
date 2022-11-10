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
                    <h3 class="pt-3 font-weight-bold">Email Verification</h3>
                </div>
                <div class="panel-body p-3">                      
                        <div class="form-group py-1 pb-2">
                            <div class="input-field"> 
                                <span class="far fa-user p-2"></span> 
                                <input type="text" name="otp" id="otp" placeholder="OTP" > 
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" onclick="ajaxsubmit()" style="text-align: center">Verify</button>
                        <div class="text-center pt-4 text-muted" id="resendoption" style="display:none"><a href="#" onclick="resendemail()">Resend Verfication Email</a> </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>





           



<script>
function showresend(){
   document.getElementById('resendoption').style.display="block";
}

setTimeout(showresend, 30000);

function ajaxsubmit(){
var url="<?php echo base_url("login/emailverification")?>";
var redirect="<?php echo base_url("login")?>";

var building = document.getElementById('otp').value;
	$.ajax({
		type: "POST",
		url:url,
		data: {'otp':building},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
				swal({
					title: "OTP Verified Successfully",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
				swal({
					title: data.error,
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

function resendemail(){
var url="<?php echo base_url("login/emailotpresend")?>";

var building = document.getElementById('otp').value;
	$.ajax({
		type: "POST",
		url:url,
		data: {'otp':building},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
				swal({
					title: "OTP Resend Successfully",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
				});

			} else {
				swal({
					title: data.error,
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				 }, function() {
				});
			}
		},

	});

}
</script>