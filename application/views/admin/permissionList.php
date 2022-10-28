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
<?php  foreach($list as $single) {
    $checked='checked';
    ?>

<tr>
<td><?php echo $single['shortcode'] ?></td>
<td><input type="checkbox" <?php echo ($single['can_view'] == 1) ? $checked : ''; ?> /></td>
<td><input type="checkbox" <?php echo ($single['can_add'] == 1) ? $checked : ''; ?> /></td>
<td><input type="checkbox" <?php echo ($single['can_edit'] == 1) ? $checked : ''; ?> /></td>
<td><input type="checkbox" <?php echo ($single['can_delete'] == 1) ? $checked : ''; ?> /></td>

</tr>

<?php } ?>
</table>

</body>
</html>