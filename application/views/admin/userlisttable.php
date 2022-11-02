<table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Name</td>
        <td>Username</td>
        <td>Mobile</td>
        <td>Email</td>
        <td>Address</td>
        <td>Status</td>
        <td>Action</td>
        
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php foreach($userlist as $user) { ?>
        <?php   
            if($user['is_active']==1){
              $stat1='Active';
              $stat2='&nbsp;&nbsp;<button class="btn btn-warning btn-sm" onclick="makeactive('.$user['userid'].',2)">Make In-Active</button>';
              $status=$stat1.''.$stat2;
            } else if($user['is_active']==2){
            $stat1='In-Active';
            $stat2='&nbsp;&nbsp;<button class="btn btn-success btn-sm">Make Active</button>';
            $status=$stat1.''.$stat2;
            }else  if($user['is_active']==3){
            $status='Deleted';
            }
        ?>
      <tr>
        <td> <?php echo $user['name']; ?></td>
        <td> <?php echo $user['username']; ?></td>
        <td> <?php echo $user['mobile']; ?></td>
        <td> <?php echo $user['email']; ?></td>
        <td> <?php echo $user['buildingname'].', Room No-'.$user['roomno']; ?></td>
        <td><?php echo $status; ?></td>
        <td><a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getuser/<?php echo base64_encode($user['userid']) ?>/<?php echo base64_encode($user['mobile']); ?>" 
        target="_blank">Details </a></td>

     </tr>

   
   <?php } ?>
      </tbody>
</table>