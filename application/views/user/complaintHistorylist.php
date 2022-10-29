
<table border="1">
<tr><td colspan="5">History details for complaint no  : <?php echo $compNo; ?></td></tr>
<tr>
    <td>Sl No</td>
    <td>Status</td>
    <td>Time</td>
<tr>

<?php $i=1; foreach($history as $single) { ?>

 <tr>
    <td><?php echo $i ?></td>
    <td><?php echo $single['compstatus']?></td>
    <td><?php echo date('H:i d-m-Y',$single['statusupdate'])?></td>


 </tr>

<?php $i++; } ?>
</tabel>
<br>
<br>
<br>

<?php if(count($extraPayment)>0) { ?>
<table border="1">
    <tr><td colspan="5">Extra Payments details for complaint no : <?php echo $compNo; ?></td></tr>
<tr>
    <td>Sl No</td>
    <td>Note</td>
    <td>Raised By</td>
    <td>Raised On</td>
    <td>Amount</td>
    <td>Payment Details</td>
</tr>

<?php $i=0;foreach($extraPayment as $payment){ ?>
    <?php 
    if($payment['transactionid']==''){
        $paymentDetails="<a href='".base_url()."register/extrapayment/".$payment['extrapaymentid']."'>Pay Now</a>";
    } else{
        $paymentDetails="Transaction ID: ".$payment['transactionid']."<br>Transaction Date: ".date('d-m-Y H:i',$payment['transactionDate'])."";
    }   
    ?>
    <tr>
    <td><?php echo ++$i; ?></td>
    <td><?php echo $payment['note']?></td>
    <td><?php echo $payment['raiseby']?></td>
    <td><?php echo date('H:i d-m-Y',$payment['createDate'])?></td>
    <td><?php echo $payment['amount']?></td>
    <td><?php echo $paymentDetails ?></td>

    </tr>

<?php } ?>
</table>
<?php } ?>