<style>
	#addratingTablemessage{
  height:30%;
  
  overflow-y: scroll;
}

</style>

  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6"> 
		<?php if(count($history)>0) { ?> <table border="1">
          <tr>
            <td colspan="5">History details for complaint no : <?php echo $compNo; ?> </td>
          </tr>
          <tr>
            <td>Sl No</td>
            <td>Status</td>
            <td>Time</td>
          <tr> <?php $i=1; foreach($history as $single) { ?>
          <tr>
            <td> <?php echo $i ?> </td>
            <td> <?php echo $single['compstatus']?> </td>
            <td> <?php echo date('H:i d-m-Y',$single['statusupdate'])?> </td>
          </tr> <?php $i++; } ?> </tabel> <?php }?> <br>
          <br>
          <br> <?php if(count($extraPayment)>0) { ?> <table border="1">
            <tr>
              <td colspan="5">Extra Payments details for complaint no : <?php echo $compNo; ?> </td>
            </tr>
            <tr>
              <td>Sl No</td>
              <td>Note</td>
              <td>Raised By</td>
              <td>Raised On</td>
              <td>Amount</td>
              <td>Payment Details</td>
            </tr> 
			<?php $i=0;foreach($extraPayment as $payment){ ?> 
			<?php   if($payment['transactionid']==''){
              $paymentDetails="<a href='".base_url()."register/extrapayment/".$payment['extrapaymentid']."'>Pay Now</a>";
              } else{
              $paymentDetails="Transaction ID: ".$payment['transactionid']."<br>Transaction Date: ".date('d-m-Y H:i',$payment['transactionDate'])."";
              }?> 
			 <tr>
              <td> <?php echo ++$i; ?> </td>
              <td> <?php echo $payment['note']?> </td>
              <td> <?php echo $payment['raiseby']?> </td>
              <td> <?php echo date('H:i d-m-Y',$payment['createDate'])?> </td>
              <td> <?php echo $payment['amount']?> </td>
              <td> <?php echo $paymentDetails ?> </td>
            </tr> <?php } ?>
          </table> 
		  
	   <?php } ?> <?php if($complaint){ ?> Add Feedback <br>
          <input type="hidden" name="complaintStars" id="complaintStars" value="<?php echo $complaint['stars'] ?>" />
          <div id="starsReview" data-value="3"></div> 
		  <?php } ?> 
	</div>
    <div class="col-sm-6">
     <div class="overflow-auto" id="addratingTablemessage">
        <hr/>
		<div class="review-block"> 
          <?php foreach($ratingResult as $rating){
             $reviewDate = date("M d, Y",$rating['lastupdate']);?> 
          <div class="row">
            <div class="col-sm-3">
             <img src="<?php echo base_url()?>/assets/icons/user-profile.png" class="img-rounded" height="50" width="50">
            <div class="review-block-date"> <?php echo $reviewDate; ?> </div>
         </div>
        <div class="col-sm-9">
           <div class="review-block-rate"> 
            <?php for ($i = 1; $i <= 5; $i++) {
              $ratingClass = "btn-default btn-grey";
                if($i <= $rating['stars']) {
                $ratingClass = "btn-warning";
                } ?> 
             <button type="button" class="btn btn-xs <?php echo $ratingClass; ?>" aria-label="Left Align">
             <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
             </button> 
	       <?php } ?> 
          </div>
         <div class="review-block-title"> <?php echo $rating['uname']; ?> </div>
         <div class="review-block-description"> <?php echo $rating['feedback']; ?> </div>
       </div>
      </div>
      <hr/> 
      <?php } ?>
     </div>
            
          
        </div>
      </div>
    </div>
  </div>
 


<script>
  var read = <?php echo($complaint['stars'] != NULL) ? 'true' : 'false' ?> ;
  $("#starsReview").rating({
    "emptyStar": "far fa-star fa-3x",
    "halfStar": "fas fa-star-half-alt fa-3x",
    "filledStar": "fas fa-star fa-3x",
    "half": false,
    "readonly": read,
    "value": " <?php echo $complaint['stars'] ?>",
    "click": function(e) {
      console.log(e);
      $("#halfstarsInput").val(e.stars);
      document.getElementById('complaintStars').value = e.stars;
    }
  });

  function fetchRatings() {
    var staffid = <?php echo(base64_encode($complaint['assignedTo'])); ?> 
	$.ajax({
      type: "POST",
      url: baseurl + "userpanel/getStaffReview",
      data: {'staffid': staffid},
      dataType: "JSON",
      beforeSend: function() {},
      success: function(data) {
        //$('.examgroup_result').html(data.result);
        document.getElementById("addratingTablemessage").innerHTML += data.html;
      },
      complete: function() {}
    });
  }
</script>