
    <section class="content-header">
        <h3>
            <i class="fa fa-home"></i>Building List
        </h3>
		<?php if ($this->rbac->hasPrivilege('building', 'can_add')) {  ?>
        <a href="#" class="btn btn-primary btn-sm addpayment" style="float: right;" data-toggle="modal" data-target="#addPaymentModal">Add Building</a>
		<?php } ?>
    </section>
    <div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Buildig Name</td>
        <td>Status</td>
        <td>Action</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($buildinglist as $building) { ?>
      <?php $ctypeid=$building['buildingid']?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $building['buildingname']; ?></td>
        <td> <?php echo($building['status']==1)?'Active':'In-Active' ?>
        <td>
          <a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getuser/<?php echo base64_encode($building['buildingid']) ?>" 
        target="_blank">Details </a> &nbsp;
        <?php if ($building['total']==0) {?>
        <button class="btn btn-danger" href="<?php echo base_url(); ?>" onclick="deletebuilding(<?php echo $ctypeid ?>)">Delete</button>
        <?php } else if ($building['total']>0 && $building['status']==1){ ?>
          <button class="btn btn-warning btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,0)">Make In-Active</button>
        <?php } else if ($building['total']>0 && $building['status']==0){ ?>
          <button class="btn btn-success btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,1)">Make Active</button>
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-home" style='color:red'></i>Add Building</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Building Name:</label>
            <input type="text" class="form-control" id="buildname">
            <span class="text-danger" id="formerror"></span>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxbuildingadd()">Save</button>
      </div>
    </div>
  </div>
</div>


<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/systemtask/getbuildinglist'); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});
$('.select223').change(function() {
    	ajaxgetTable(this.value);
});



function ajaxbuildingadd() {

  var building = document.getElementById('buildname').value;
  document.getElementById("formerror").innerHTML='';
	$.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/getbuildinglist",
		data: {'name':building},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
        $('#addPaymentModal').modal('hide');
				swal({
					title: "Bulding Added",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
        document.getElementById("formerror").innerHTML =data.error;
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

function deletebuilding(buildingid){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/buildingdelete",
		data: {'buildingid':buildingid},
		dataType: "JSON",
    success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
				swal({
					title: "Building Deleted",
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

function makeactive(buildingid,active){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/buildingactivestatus",
		data: {'buildingid':buildingid,'status':active},
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
</script>



