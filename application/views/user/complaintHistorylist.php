
<body onload="fetchRatings();">
<div class="row">
 <div class="col-md-12">
    <div class="card-body">
        <div class="row show-grid ">
            <div class="col-md-12 bg-secondary text-center"><strong><h4>Grievance Concerns To Complaint No : <?php echo $compNo ?></h4></strong></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Name Of Complainant </strong></div>
            <div class="col-md-9 "><?php echo $complaint['name'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Date of Receipt </strong></div>
            <div class="col-md-9 "><?php echo date("d/m/Y",$complaint['complaintDate']) ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong>Grievance Type </strong></div>
            <div class="col-md-9 "><?php echo $complaint['typename'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Grievance Description </strong></div>
            <div class="col-md-9 "><p><?php echo $complaint['description'] ?></p></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Current Status </strong></div>
            <div class="col-md-9 "><?php echo $complaint['status'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Date of Action </strong></div>
            <div class="col-md-9 "><?php echo date("d/m/Y",$complaint['lastupdate']) ?></div>
        </div>
                



    </div>
 </div>

</div>
<div class="clearfix"></div>
 
<div class="row">
 <div class="col-md-6 firstable">

 <?php if(count($history)>0) { ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
      <thead style="font-weight:bold;">
      <tr><td colspan="4" class="label" style="text-align: center;">Hisory of Action</td></tr>
        <tr>
          <td>Sn</td>
          <td>Action</td>
          <td>Date</td>
        </tr>
      </thead>
      <tbody>
      <?php $i=1; foreach($history as $single) { ?>
         <tr>
          <td><?php echo $i ?></td>
          <td><?php echo $single['compstatus']?></td>
          <td><?php echo date('H:i d/m/Y',$single['statusupdate'])?></td>
          </tr>

          <?php $i++; } ?>
      </tbody>
    </table>
  </div>
  <?php } ?>


  <?php if(count($extraPayment)>0) { ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="complaintExtrapayment">
      <thead style="font-weight:bold;">
      <tr><td colspan="4" class="label" style="text-align: center;">Extra Payment Details</td></tr>
           <tr>
             <td>Payment Note</td>             
             <td>Raised On</td>
             <td>Amount</td>
             <td>Payment Details</td>
            </tr>
      </thead>
      <tbody>
      <?php $i=0;foreach($extraPayment as $payment){ ?>
           <?php 
            if($payment['transactionid']==''){
               $paymentDetails="<a href='".base_url()."register/extrapayment/".$payment['extrapaymentid']."'>Pay Now</a>";
            } else{
            $paymentDetails="Transaction ID: ".$payment['transactionid']."<br>Transaction Date: ".date('H:i d/m/Y',$payment['transactionDate'])."";
            }   
           ?>
           <tr>
             <td> <div style="font-weight:bold;"><?php echo ++$i; ?>.</div><?php echo $payment['note']?></td>             
             <td><?php echo date('H:i d/m/Y',$payment['createDate'])?></td>
             <td><?php echo RSSIGN; ?> <?php echo $payment['amount']?></td>
             <td><?php echo $paymentDetails ?></td>
           </tr>

          <?php } ?>
      </tbody>
    </table>
  </div>
  <?php } ?>

  <?php if($complaint && $complaint['status']==3 ){ ?>
  <div lable="RatingFormArea" class="w-100 rounded-1 p-4 border bg-white">
    
  <form>
  <div class="form-group">
    <label for="exampleInputEmail1">Add Ratings</label>
    <input type="hidden" name="complaintStars" id="complaintStars" value="<?php echo $complaint['stars']?>" />
    <div id="starsReview"  data-value="3" ></div>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Your Feedback</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
 </form>
 </div>
 <?php } ?> 

 </div>


  <div class="col-md-6">
  <div style="overflow-y:auto;overflow-x: hidden;margin-left:5px" id="addratingTablemessage">
       <?php if($complaint['staffname'] != 'Not Assigned') { ?>
        <section class="content-header">
           <h5>
            <i class="fa fa-file-text-o"></i> Rating and Review of <?php echo $complaint['staffname'] ?>
           </h5>
        </section>
        <?php } ?>
      </div>
 </div>


</div>





</body>


<script>
    var baseurl="<?php echo base_url(); ?>";

    //alert($(".firstable").outerHeight());
    //alert(screen.height);
    var outerheight = $(".firstable").outerHeight();
    var screenheight=screen.height;

    if(outerheight>screenheight){
        screenheight=outerheight;
    }
    //console.log(screenheight);
    //screenheight2=screenheight*0.8;
    var element = document.getElementById("addratingTablemessage");
    element.style.height =screenheight+"px";
    




    var read=<?php echo ($complaint['stars']!=NULL)? 'true':'false' ?>;
  $(document).ready(function(){
    $('#starsReview').jsRapStar({
				step:true,
				value:0,
				length:5,
				starHeight:50,
        enabled:read,
        colorFront: '#000',
        onClick: function (score) {
          var colorcode=generateColor(score);
          this.StarF.css({ color:colorcode });
          document.getElementById('complaintStars').value=score;
          
        }
			});
    });


    function fetchRatings(){
    var staffid='<?php echo (base64_encode($complaint['assignedTo'])); ?>';
    $.ajax({
                type: "POST",
                url: baseurl + "userpanel/getStaffReview",
                data: {'staffid':staffid},
                dataType: "JSON",
                beforeSend: function () {

                },
                success: function (data) {

                    document.getElementById("addratingTablemessage").innerHTML+=data.html;
                    
                },
                complete: function () {
                setRating();
                }
            });
    }


</script>

<script>
  $(document).ready(function () {
    $('#complaintExtrapaymentA').DataTable({      
      pageLength:2,
      "bLengthChange" : false,
       searching:false,
       scrollX:false,
    });
});

function setRating(){
  var rating=document.getElementById("ratingResult").value;
  var color=generateColor(rating);
  $(document).ready(function(){
    $('#starsReviewAvg').jsRapStar({
				step: false,
				value:rating,
				length:5,
				starHeight:50,
        enabled: false,
        colorFront:color,
			});
    });  
}

function generateColor(rat2){
  var color;
  rat=parseFloat(rat2);
  //console.log(rat);
  if(rat< 2){
  color='red';
  }else if(rat >=2 && rat < 3.8){
  color='yellow';
  }else if(rat >=3.8){
  color='green';
  }
  return color;
}
</script>

