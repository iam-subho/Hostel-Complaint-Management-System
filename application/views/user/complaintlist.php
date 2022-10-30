<table border="1">
    <tr>
        <td>Complaint No</td>
        <td>Complaint Status</td>
        <td>Complaint Date</td>
        <td>Complaint Descritpion</td>
        <td>Complaint Assigned</td>
        <td>Payment Details</td>
        <td>Action<td>
    </tr>
    <?php foreach($complaintlist as $comp) { ?>
        <?php  //echo $comp['paymentTransactionId']; echo '<br>';
        //echo $comp['personal']; echo '<br>';
        //echo ($comp['paymentTransactionId']== null && $comp['personal']==1)?'HI':'Hello';
        if($comp['paymentTransactionId']== null && $comp['personal']==1){
        $status="<a href=".base_url()."register/retrypayment/".$comp['complaint_id']."/".$comp['complaint_type'].">Retry Payment</a>";
        $paymentDetails='Payment Not Completed';
        }else if($comp['paymentTransactionId']==0 && $comp['personal']==0){
        $status=$comp['compstatus']; 
        $paymentDetails="Payment Not Required";   
        }else{
        $status=$comp['compstatus'];
        $paymentDetails="Transaction ID: ".$comp['paymentTransactionId']."<br>Transaction Date: ".date('d-m-Y H:i',$comp['paymentDate'])."";
        }    
            
        ?>
      <tr>
        <td> <?php echo $comp['complaintNo']; ?></td>
        <td> 
          <?php echo $status; ?></td>
        <td> <?php echo date('d-m-Y H:i',$comp['complaintDate']); ?></td>
        <td> <?php echo $comp['description']; ?></td>
        <td> <?php echo $comp['staffname']; ?></td>
        <td><?php echo $paymentDetails ?></td>
        <td><a href="<?php echo base_url(); ?>userpanel/complaintdetails/<?php echo base64_encode($comp['complaint_id']) ?>/<?php echo base64_encode($comp['complaintNo']); ?>" 
        target="_blank">More Details </a><?php if($comp['staffname']!='Not Assigned'){ ?>
          <br><a href="<?php echo base_url(); ?>userpanel/chat/<?php echo base64_encode($comp['complaint_id']) ?>">Chat</a> 
          <?php } ?></td>
     </tr>

   
   <?php } ?>

</table>



