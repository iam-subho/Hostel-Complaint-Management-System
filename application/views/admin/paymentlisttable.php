 <table id='paymentlist' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Complaint No</td>
        <td>Complaint Payment</td>
        <td>Extra Payment</td>
        <td>Complaint Date</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php foreach($paymentlist as $payment) { ?>
        <?php

            
        ?>
      <tr>
        <td> <?php echo $payment['complaintNo']; ?></td>
        <td> <?php echo RSSIGN ?> <?php echo $payment['paymentAmount']; ?></td>
        <td> <?php echo RSSIGN ?> <?php echo $payment['amount']; ?></td>
        <td> <?php echo date("H:i d/m/Y",$payment['complaintdate']); ?></td>
        <td>
        <a title="Details" href="<?php echo base_url(); ?>admin/admin/getcomplaint/<?php echo base64_encode($payment['complaint_id']) ?>/<?php echo base64_encode($payment['complaintNo']); ?>" 
        target="_blank"><i class="fas fa-align-justify" style="font-size:24px"></i></a>
        </td>
     </tr>

   
   <?php } ?>
      </tbody>
</table>
