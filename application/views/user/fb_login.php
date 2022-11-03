<html>
<head>
  <title>Login with Facebook</title>
</head>
<body>
<?php if (isset($fb['id']) && !empty($fb['id'])) { ?>
   
        <a href="<?php echo base_url('sociallogin/logoutfb') ?>">Logout from facebook</a>
    
        <?php } else { ?>
    
        <a href="<?php echo $LogonUrl ?>"><img src="<?php echo base_url('assets_signi/flogin.png') ?>"></a>
    

        <?php } ?>

        <?php
        // print_r($fb); 
        if (isset($fb['id']) && !empty($fb['id'])) {
            ?>
   
                <table class="table">
                    <tr>
                        <td>Id: </td>
                        <td><?php echo $fb['id'] ?></td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td><?php echo $fb['first_name'] . ' ' . $fb['last_name'] ?></td>
                    </tr>
                    <tr>
                        <td>Profile Pic</td>
                        <td><img src="<?php echo $fb['picture']['data']['url']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Email: </td>
                        <td><?php echo $fb['email'] ?></td>
                    </tr>
                </table>
           
<?php } ?>
</body>
</html>