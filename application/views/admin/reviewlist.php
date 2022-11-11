  
  <section class="content-header">
        <h3>
            <i class="fa fa fa-feed"></i>Reviews & Feedback
        </h3>

    </section>
    <div class="clearfix"></div><div class="clearfix"></div><br>
    <div class="userfilteredcontent" id="userfilteredcontent">
 <table id='userList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Sn</td>
        <td>Complaint No</td>
        <td>Staff Name</td>
        <td>Stars</td>
        <td>Feedback</td>       
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php $i=0; foreach($reviewlist as $review) { ?>
      <?php $ctypeid=$review['complaint_id']?>
      <tr>
        <td> <?php echo ++$i; ?></td>
        <td> <?php echo $review['complaintNo']; ?></td>
        <td> <?php echo $review['name'];?></td>
        <td> 
        <?php
             for($i = 1; $i <= 5; $i++) {
              $ratingClass = "far fa-star";
              if($i <= $review['stars']) {
              $ratingClass = "fa fa-star";
              }
            ?> 
            <span class="<?php echo $ratingClass ?>" style="font-size:24px;color:<?php echo colorreturn($review['stars']) ?>" aria-hidden="true"></span>
             
             <?php } ?> 
        </td>
        <td> <?php echo($review['feedback']) ?>   </td>
     </tr>
     <script>
     
     </script>   
   <?php } ?>
      </tbody>
</table>
</div>

<?php

function colorreturn($rat){
  $color='';
  if($rat< 2){
    $color='red';
    }else if($rat >=2 && $rat < 3.8){
    $color='yellow';
    }else if($rat >=3.8){
    $color='green';
    }
    return $color;

}
?>

<script>
      $(document).ready(function () {
    $('#userList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});


</script>