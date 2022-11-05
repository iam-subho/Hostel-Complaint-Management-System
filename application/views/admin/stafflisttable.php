<table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Name</td>
        <td>Username</td>
        <td>Mobile</td>
        <td>Email</td>
        <td>Department</td>
        <td>Status</td>
        <td>Action</td>
        
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php foreach($stafflist as $user) { ?>
        <?php   
            if($user['status']==1){
              $stat1='Active';
              $stat2='&nbsp;&nbsp;<button class="btn btn-warning btn-sm" onclick="makeactive('.$user['staff_id'].',0)">Make In-Active</button>';
              $status=$stat1.''.$stat2;
            } else if($user['status']==0){
            $stat1='In-Active';
            $stat2='&nbsp;&nbsp;<button class="btn btn-success btn-sm" onclick="makeactive('.$user['staff_id'].',1)">Make Active</button>';
            $status=$stat1.''.$stat2;
            }
        ?>
      <tr>
        <td> <?php echo $user['name']; ?></td>
        <td> <?php echo $user['username']; ?></td>
        <td> <?php echo $user['mobile']; ?></td>
        <td> <?php echo $user['email']; ?></td>
        <td> <?php echo $user['department']; ?></td>
        <td><?php echo $stat1; ?></td>
        <td><a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getuser/<?php echo base64_encode($user['staff_id']) ?>" 
        target="_blank">Details </a><?php echo $stat2 ?></td>

     </tr>

   
   <?php } ?>
      </tbody>
</table>