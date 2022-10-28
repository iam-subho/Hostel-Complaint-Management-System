<html>
<head>
<title>My Form</title>
</head>
<body>

<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('admin/systemtask/rolesadd') ?>"/>

<h5>Username</h5>
<input type="text" name="role" value="<?php echo set_value('role'); ?>" size="50" />

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>