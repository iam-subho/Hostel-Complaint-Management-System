
    <section class="content-header inline">
        <h3>
            <i class="fa fa-home"></i>Complaint Type List
        </h3>
        <?php if ($this->rbac->hasPrivilege('complaint_type', 'can_add')) {  ?>
        <a href="#" style="float: right;" class="btn btn-primary btn-sm addpayment" data-toggle="modal" data-target="#addPaymentModal">Add Complaint Type</a>
        <?php } ?>
    </section>
    <div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Type Name</td>
        <td>Category</td>
        <td>Amount</td>
        <td>Department</td>
        <td>Status</td>
        <td>Action</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($complaintlist as $building) { ?>
      <?php 
        if($building['personal']==1){
          $category='Personal';
        }else if($building['personal']==0){
          $category='Standard';
        }else if($building['personal']==2){
          $category='Custom';
        }
        $ctypeid=($building['typeid']);    //base64_encode
        ?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $building['typename']; ?></td>
        <td> <?php echo $category ?></td>
        <td> <?php echo $building['paymentAmount']?></td>
        <td> <?php echo $building['type_name']?></td>
        <td> <?php echo ($building['status']==1)?'Active':'In-Active' ?></td>
        <td>
          <a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getuser/<?php echo base64_encode($building['typeid']) ?>" 
        target="_blank">Details </a> &nbsp;
        <?php if ($building['total']==0) {?>
        <button class="btn btn-danger" onclick="deletetype(<?php echo $ctypeid ?>)">Delete</button>
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
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-home" style='color:red'></i>Add Complaint Type</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Type Name:</label>
            <input type="text" class="form-control" id="name">
            <span class="text-danger" id="formerror_name"></span>

          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Category:</label>
            <select name="category" required class="form-control" id="category">
            <option value=''>Select</option>
            <option value='0'>Standard</option>
            <option value='1'>Personal</option>
            <option value='2'>Custom</option>
            </select>
            <span class="text-danger" id="formerror_category"></span>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Amount:</label>
            <input type="number" class="form-control" id="amount" min="0" value="0">
            <span class="text-danger" id="formerror_amount"></span>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Department:</label>
            <select name="department" class="form-control" id="department">
            <option value=''>None</option>
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
        <button type="button" class="btn btn-primary" onclick="ajaxtypelistadd()">Save</button>
      </div>
    </div>
  </div>
</div>


<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/systemtask/complainttypelist'); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});
$('#category').change(function() {
    	//ajaxgetTable(this.value);
      if(parseInt(this.value)===0){
      document.getElementById('amount').value=parseInt(0);
      document.getElementById('amount').readOnly=true;
      }else{
      document.getElementById('amount').readOnly=false;
      }

      if(parseInt(this.value)===2){
        document.getElementById('department').value=parseInt(3);
        document.getElementById('department').readOnly=true;
      }else{
        document.getElementById('department').readOnly=false;
        document.getElementById('department').value='';
      }
});

function makeactive(userid,active){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/complainttypeactivestatus",
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

function deletetype(userid){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/complainttypedelete",
		data: {'typeid':userid},
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



function ajaxtypelistadd() {

	var name = document.getElementById('name').value;
  var cat = document.getElementById('category').value;
  var amount = document.getElementById('amount').value;
  var dept = document.getElementById('department').value;
  document.getElementById("formerror_name").innerHTML='';
  document.getElementById("formerror_category").innerHTML='';
  document.getElementById("formerror_amount").innerHTML='';
  document.getElementById("formerror_department").innerHTML='';
	$.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/complainttypelist",
		data: {'name':name,'category':cat,'amount':amount,'department':dept},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {
        $('#addPaymentModal').modal('hide');
				swal({
					title: "Complaint Type Added",
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
  if(data.category){
    document.getElementById("formerror_category").innerHTML=data.category;
  }
  if(data.amount){
    document.getElementById("formerror_amount").innerHTML=data.amount;
  }
  if(data.department){
    document.getElementById("formerror_department").innerHTML=data.department;
  }

}
</script>



