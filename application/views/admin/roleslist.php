
    <section class="content-header">
        <h3>
            <i class="fas fa-user-shield"></i>Roles List
        </h3>
	    <?php if ($this->rbac->hasPrivilege('roles', 'can_add')) {  ?>
        <a href="#" style="float: right;" class="btn btn-primary btn-sm addpayment" data-toggle="modal" data-target="#addPaymentModal">Add Role</a>
		<?php } ?>
    </section>
	<div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Role Name</td>
        <td>Status</td>
        <td>Action</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($roles as $role) { ?>
        <?php $ctypeid=$role['role_id']; ?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $role['role_name']; ?></td>
        <td> <?php echo ($role['status']==1)?'Active':'In-Active' ?></td>
        <td>
        <?php if ($role['total']==0) {?>
        <button class="btn btn-danger btn-sm" onclick="deleterole(<?php echo $ctypeid ?>)">Delete</button>
        <?php } else if ($role['total']>0 && $role['status']==1){ ?>
          <button class="btn btn-warning btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,0)" title="Make Dective">Deactive</button>
        <?php } else if ($role['total']>0 && $role['status']==0){ ?>
          <button class="btn btn-success btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,1)" title="Make Active">Active</button>
        <?php }?>
		<?php if ($this->rbac->hasPrivilege('permissionassign', 'can_add')) {  ?>
        <a class="btn btn-success btn-sm" href="<?php echo base_url('admin/systemtask/getPermissionList/').''.$ctypeid ?>" title="Add Permission">Permission</a>
		<?php }?>
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-user-shield" style='color:red'></i>Add Role</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Building Name:</label>
            <input type="text" class="form-control" id="rolename">
            <span class="text-danger" id="formerror_role"></span>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxroleadd()">Save</button>
      </div>
    </div>
  </div>
</div>


<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/systemtask/getroleList'); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});
$('.select223').change(function() {
    	ajaxgetTable(this.value);
});

function makeactive(roleid,active){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/roleactivestatus",
		data: {'roleid':roleid,'status':active},
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

function deleterole(roleid){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/roledelete",
		data: {'roleid':roleid},
		dataType: "JSON",
    success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
				swal({
					title: "Role Deleted",
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



function ajaxroleadd() {

	var role = document.getElementById('rolename').value;
  document.getElementById("formerror_role").innerHTML='';
	$.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/rolesadd",
		data: {'role':role},
		dataType: "JSON",
		success: function(data) {
		if(parseInt(data.status) === 1) {
        $('#addPaymentModal').modal('hide');
				swal({
					title: "Role Added",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
                document.getElementById("formerror_role").innerHTML =data.error.role;
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

	});
}
</script>



