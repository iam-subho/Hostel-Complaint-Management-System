
    <section class="content-header">
        <h3>
            <i class="fa fa-home"></i>Notification List
        </h3>

    </section>
    <div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Name</td>
		<td>Description</td>
        <td>Status</td>
        <td>Action</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($notificationlist as $notification) { ?>
      <?php $ctypeid=$notification['notid']?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $notification['name']; ?></td>
		<td> <?php echo $notification['description']; ?></td>
        <td> <?php echo($notification['status']==1)?'Active':'In-Active' ?>
        <td>
        <?php if ($notification['status']==1){ ?>
          <button class="btn btn-warning btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,0)">Make In-Active</button>
        <?php } else if ($notification['status']==0){ ?>
          <button class="btn btn-success btn-sm" onclick="makeactive(<?php echo $ctypeid ?>,1)">Make Active</button>
        <?php }?>
      </td>

     </tr>

   
   <?php } ?>
      </tbody>
</table>
</div>




<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/systemtask/notification'); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});



function makeactive(notificationid,active){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/systemtask/notificationactivestatus",
		data: {'notificationid':notificationid,'status':active},
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



