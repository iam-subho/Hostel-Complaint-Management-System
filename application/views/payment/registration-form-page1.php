<body>
<div class="container">
  <div class="row mx-0 justify-content-center">
    <div class="col-md-8">
      <div   class="w-100 rounded-1 p-4 border bg-white" id="form1">
        <label class="d-block mb-4">
          <span class="form-label d-block">Your name</span>
          <input name="email" type="email" class="form-control" value="<?php echo $user['name'] ?>"  placeholder="Subhojit"/>
        </label>

        <label class="d-block mb-4">
          <span class="form-label d-block">Email address</span>
          <input name="email" type="email" class="form-control" value="<?php echo $user['email'] ?>"  placeholder="subhojit@example.com"/>
        </label>

        <label class="d-block mb-4">
          <span class="form-label d-block">Mobile</span>
          <input name="email" type="email" class="form-control" value="<?php echo $user['mobile'] ?>" placeholder="7001667213"/>
        </label>

        <label class="d-block mb-4">
          <span class="form-label d-block">Building & Room</span>
          <input name="email" type="email" class="form-control" value="<?php echo $user['buildingname'].', Room No-'.$user['roomno'] ?>" placeholder="SVBP,30"/>
        </label>

        <div class="mb-3">
          <?php if($noreview==0){?>
          <a href="<?php echo site_url('register/page2')?>" class="btn btn-primary px-3 rounded-3">Next </a>
          <?php } else{?>
          <button class="btn btn-warning px-3 rounded-3" onclick="showwarning()">Next</button>
          <?php }?>
        </div>


      </div>
    </div>
  </div>
</div>

<script>
  var redirect="<?php echo base_url('userpanel/complaintlist') ?>";
  $('#form1 input').attr('readonly', 'readonly');
  function showwarning(){
    swal({
					title:"Please add review to closed complaint",
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				 }, function() {
					window.location = redirect;
				});
  }
</script>
