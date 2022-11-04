<?php 
//print_r($list); 
?>
<section class="content-header">
        <h3>
            <i class="fas fa-shield-alt"></i>Permission List
        </h3>
        <?php if ($this->rbac->hasPrivilege('permission', 'can_add')) {  ?>
        <a href="#" class="btn btn-primary btn-sm addpayment" style="float: right;" data-toggle="modal" data-target="#addPaymentModal">Add Permission</a>
        <?php } ?>
    </section>
    <div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Permssion Short Code</td>
        <td>Action</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($list as $single) { ?>
        <?php $ctypeid=$single['perm_id']; ?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $single['perm_short_code']; ?></td>
        <td><a class="btn btn-danger btn-sm" style="cursor:pointer" onclick="deletepermission(<?php echo $ctypeid?>)">Delete</a></td>

     </tr>

   
   <?php } ?>
      </tbody>
</table>
</div>


<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-shield-alt" style='color:red'></i>Add Permission</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Permission Short Code :</label>
            <input type="text" class="form-control" id="shortcode">
            <span class="text-danger" id="formerror"></span>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxpermissionshortcodeadd()">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect= "<?php echo base_url('admin/systemtask/permissionadd'); ?>";
  $(document).ready(function () {
    $('#userList').DataTable({ 
      dom: 'Bfrtip',     
      //paging: false,
      //info: false,
      "bLengthChange" : false,
    
    });
});

function ajaxpermissionshortcodeadd() {

var shortcode = document.getElementById('shortcode').value;
document.getElementById("formerror").innerHTML='';
$.ajax({
    type: "POST",
    url: baseurl + "admin/systemtask/permissionadd",
    data: {'permission':shortcode},
    dataType: "JSON",
    success: function(data) {
        //console.log(data.status);
        if (parseInt(data.status) === 1) {
        $('#addPaymentModal').modal('hide');
            swal({
                title: "New Permission Added",
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
                title: "New Permission Add Failed",
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

function deletepermission(id){
    $.ajax({
    type: "POST",
    url: baseurl + "admin/systemtask/permissionshortcodedelete",
    data: {'permissionid':id},
    dataType: "JSON",
    success: function(data) {
        if (parseInt(data.status) === 1) {
            swal({
                title: "Permission Deleted",
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
</script>