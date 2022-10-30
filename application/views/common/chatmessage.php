<?php foreach ($messagelist as $message) { ?>


<?php if ($message['whosend']==1) { ?>
  <div class="incoming_msg">
      <div class="incoming_msg_img"><?php if($bool==0){ ?> <img src="<?php echo base_url()?>/assets/icons/staff_profile.png" alt="subhojit"><?php }?> </div>
      <div class="received_msg">
        <div class="received_withd_msg">
          <p><?php echo $message['message']?></p>
          <span class="time_date"><?php echo date("H:i",strtotime($message['timestamp'])) ?>    |    <?php echo date("d-m-Y",strtotime($message['timestamp'])) ?></span></div>
      </div>
    </div>

<?php $bool=1;} else { $bool=0;?>

    <div class="outgoing_msg">
      <div class="sent_msg">
      <p><?php echo $message['message']?></p>
      <span class="time_date"><?php echo date("H:i",strtotime($message['timestamp'])) ?>    |    <?php echo date("d-m-Y",strtotime($message['timestamp'])) ?></span></div>

    </div>

<?php } ?>
<?php } ?>