<html>
<head>
<title>My Form</title>
</head>
<body>

<table border="1">
<tr>
    <td>Code</td>
    <td>View</td>
    <td>Add</td>
    <td>Edit</td>
    <td>Delete</td>
</tr>
<form method="post" action="<?php echo base_url();?>admin/systemtask/permisssionassign/" >
<input type="hidden" name="roleid" value="<?php echo $roleid; ?>" />
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
<tr><td colspan="5"><button type="submit">Submit</button></td></tr>
</form>
</table>

</body>
</html>