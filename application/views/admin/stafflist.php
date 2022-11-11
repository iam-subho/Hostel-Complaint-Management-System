
    <section class="content-header">
        <h3>
            <i class="fas fa-user-tie"></i>Staff List
        </h3>
        <label id="list">Filter Staff: </label>
        <select name="selected" id="select" class="select22">
        <option value="">All</option>
        <option value="1">Active</option>
        <option value="0">In-Active</option>
        </select>
        <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) {  ?>
        <a href="#" style="float: right;" class="btn btn-primary btn-sm addpayment" data-toggle="modal" data-target="#addPaymentModal">Add Staff</a>
		<?php } ?>
    </section>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Name</td>
        <td>Username</td>
        <td>Mobile</td>
        <td>Email</td>
        <td>Department</td>
        <td>Status</td>
        <td>Action</td>
        
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php foreach($stafflist as $user) { ?>
        <?php   
            if($user['status']==1){
              $stat1='Active';
              $stat2='&nbsp;&nbsp;<button class="btn btn-warning btn-sm" onclick="makeactive('.$user['staff_id'].',0)" title="Make In-Active">In-Active</button>';
              $status=$stat1.''.$stat2;
            } else if($user['status']==0){
              $stat1='In-Active';
              $stat2='&nbsp;&nbsp;<button class="btn btn-success btn-sm" onclick="makeactive('.$user['staff_id'].',1)" title="Make Active">Active</button>';
              $status=$stat1.''.$stat2;
            }
        ?>
      <tr>
        <td> <?php echo $user['name']; ?></td>
        <td> <?php echo $user['username']; ?></td>
        <td> <?php echo $user['mobile']; ?></td>
        <td> <?php echo $user['email']; ?></td>
        <td> <?php echo $user['department']; ?></td>
        <td><?php echo $stat1; ?></td>
        <td>
        <?php if ($user['total']==0) {?>
        <button class="btn btn-danger btn-sm" onclick="deletestaff(<?php echo $user['staff_id'] ?>)">Delete</button>
        <?php } ?>
        <a class="btn btn-info btn-sm" href="<?php echo base_url(); ?>admin/admin/getstaff/<?php echo base64_encode($user['staff_id']) ?>/<?php echo base64_encode($user['mobile']); ?>" 
        target="_blank">Details </a>
        <?php if($this->rbac->hasPrivilege('staff', 'can_edit')){?>
        <?php echo $stat2 ?>
        <a class="btn btn-primary btn-sm" href="<?php echo base_url();?>admin/admin/profile/<?php echo base64_encode($user['staff_id']) ?>/1">Edit</a>
        <?php } ?>
        </td>

     </tr>

   
   <?php } ?>
      </tbody>
</table>
</div>


<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user-tie" style='color:red'></i>Add New Staff</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Staff Name:</label>
            <input type="text" class="form-control" id="name">
            <span class="text-danger" id="formerror_name"></span>

          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Username</label>
            <input type="text" class="form-control" id="username">
            <span class="text-danger" id="formerror_username"></span>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Mobile</label>
            <input type="number" class="form-control" id="mobile">
            <span class="text-danger" id="formerror_mobile"></span>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Email</label>
            <input type="text" class="form-control" id="email">
            <span class="text-danger" id="formerror_email"></span>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Roles:</label>
            <select name="role" class="form-control" id="role">
            <?php foreach ( $roles as $role) { ?>
            <option value="<?php echo $role['role_id'] ?>"   >  <?php echo $role['role_name']?></option>
            <?php } ?>
            </select>
            <span class="text-danger" id="formerror_role"></span>
          </div>

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Department:</label>
            <select name="department" class="form-control" id="department">
            <?php foreach ( $dept as $department) { ?>
            <option value="<?php echo $department['worker_type_id'] ?>"   >  <?php echo $department['type_name']?></option>
            <?php } ?>
            </select>
            <span class="text-danger" id="formerror_department"></span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxstaffadd()">Save</button>
      </div>
    </div>
  </div>
</div>




<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/admin/staffList'); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});
$('.select22').change(function() {
    	ajaxgetTable(this.value);
});


function deletestaff(userid){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/admin/staffdelete",
		data: {'staffid':userid},
		dataType: "JSON",
    success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
				swal({
					title: "Complaint Type Deleted",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
				swal({
					title:data.error,
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

function ajaxgetTable(status){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/admin/filterbasestafflist",
		data: {'status':status},
		dataType: "JSON",
    success: function(data) {
      document.getElementById("userfilteredcontent").innerHTML='';
      
    if(parseInt(data.status) === 1) {
      document.getElementById("userfilteredcontent").innerHTML+=data.html;
				swal({
					title: "Data Loaded Successfully",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					//window.location = redirect;
				});

			} else {
				swal({
					title:data.errorP,
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				 }, function() {
					//window.location = redirect;
				});
			}
    },
    complete: function() {
      $('#userList').DataTable({      
       pageLength:10,
      "bLengthChange" : false,
    
    });
    }

  });
}

function makeactive(userid,active){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/admin/staffactivestatus",
		data: {'userid':userid,'status':active},
		dataType: "JSON",
    success: function(data) {
      if (parseInt(data.status) === 1) {
				swal({
					title: "Status Changed",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
				swal({
					title:data.error,
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

function ajaxstaffadd() {

var name = document.getElementById('name').value;
var username = document.getElementById('username').value;
var mobile = document.getElementById('mobile').value;
var email = document.getElementById('email').value;
var role = document.getElementById('role').value;
var dept = document.getElementById('department').value;

document.getElementById("formerror_name").innerHTML='';
document.getElementById("formerror_username").innerHTML='';
document.getElementById("formerror_mobile").innerHTML='';
document.getElementById("formerror_email").innerHTML='';
document.getElementById("formerror_role").innerHTML='';
document.getElementById("formerror_department").innerHTML='';
$.ajax({
  type: "POST",
  url: baseurl + "admin/admin/addstaff",
  data: {'name':name,'username':username,'mobile':mobile,'email':email,'role':role,'department':dept},
  dataType: "JSON",
  success: function(data) {
    //console.log(data.status);
    if (parseInt(data.status) === 1) {
      $('#addPaymentModal').modal('hide');
      swal({
        title: "Staff Added",
        type: "success",
        showConfirmButton: true,
        confirmButtonText: "Ok",
        closeOnConfirm: true
      }, function() {
        window.location = redirect;
      });

    } else {
      setError(data.error);
      swal({
        title: data.errorP,
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



function setError(data){
if(data.name){
  document.getElementById("formerror_name").innerHTML =data.name;
}
if(data.username){
  document.getElementById("formerror_username").innerHTML=data.username;
}
if(data.mobile){
  document.getElementById("formerror_mobile").innerHTML=data.mobile;
}
if(data.email){
  document.getElementById("formerror_email").innerHTML=data.email;
}
if(data.role){
  document.getElementById("formerror_role").innerHTML=data.role;
}
if(data.department){
  document.getElementById("formerror_department").innerHTML=data.department;
}

}
</script>



