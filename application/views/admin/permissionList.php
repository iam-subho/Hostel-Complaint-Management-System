 <section class="content-header">
        <h3>
            <i class="fas fa-user-lock"></i>Assigned Permission Lists
        </h3>
    </section>
<form method="post" action="<?php echo base_url();?>admin/systemtask/permisssionassign/" id="permissionform">
<input type="hidden" name="roleid" value="<?php echo $roleid; ?>" />
<table id='userList' class="table table-striped table-bordered" border="1">
<thead>
<tr>
    <td>Code</td>
    <td>View</td>
    <td>Add</td>
    <td>Edit</td>
    <td>Delete</td>
</tr>
</thead>
<tbody>
<?php  
foreach($list as $single) {
    $checked='checked';
    $permid=$single['permid'];
    ?>

<tr>
<td><?php echo $single['shortcode'] ?></td>
<input type="hidden" name="permid[]" value="<?php echo $single['permid'] ?>" />
<td><input name="can_view<?php echo $permid ?>" type="checkbox" <?php echo ($single['can_view'] == 1) ? $checked : ''; ?> /></td>
<td><input name="can_add<?php echo $permid ?>" type="checkbox" <?php echo ($single['can_add'] == 1) ? $checked : ''; ?> /></td>
<td><input name="can_edit<?php echo $permid ?>" type="checkbox" <?php echo ($single['can_edit'] == 1) ? $checked : ''; ?> /></td>
<td><input name="can_delete<?php echo $permid ?>" type="checkbox" <?php echo ($single['can_delete'] == 1) ? $checked : ''; ?> /></td>

</tr>

<?php } ?>
<!--<tr><td colspan="5"><button type="submit">Submit</button></td></tr>-->
</tbody>
</table>
<tr><td colspan="5"><button class="btn btn-success btn-sm" style="float: right;" id="submitform">Submit</button></td></tr>
</form>

<script>
var baseurl = "<?php echo base_url(); ?>";

  $(document).ready(function () {
    $('#userList').DataTable({ 
      dom: 'Bfrtip',     
      paging: false,
      info: false,
      "bLengthChange" : false,
    
    });
});
var form = document.getElementById("permissionform");

document.getElementById("submitform").addEventListener("click", function () {
  form.submit();
});
</script>

