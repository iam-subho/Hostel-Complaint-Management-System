
<body onload="setRating();">
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
            <div class="col-md-3"><strong>Address of Complainant </strong></div>
            <div class="col-md-9 "><?php echo $complaint['buildingname'] ?>(Room No-<?php echo $complaint['roomno'] ?>)</div>
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
            <div class="col-md-9 "><?php echo $complaint['compstatus'] ?></div>
        </div>

        <div class="row show-grid">
            <div class="col-md-3"><strong>Assigned To</strong></div>
            <div class="col-md-9" id="assigned">
            <?php echo $complaint['staffname'] ?>&nbsp;
            <?php if($this->rbac->hasPrivilege('complaint_worker_assign','can_view')) { ?>
            <button class="btn btn-info btn-sm" onclick="ediassign()" id="editassignbtn">Edit Worker</button> 
            </div>
            <div class="col-md-9 col-xs-2" id="notassigned" style="display: none !important;">
            <select class="form-control select2 select22" name="worker" id="worker" style="width:auto;">
            </select>
            <?php } ?>
           </div>
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
 <div class="col-md-12 firstable">


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
      <?php if(count($history)>0) { ?>
      <?php $i=1; foreach($history as $single) { ?>
         <tr>
          <td><?php echo $i ?></td>
          <td><?php echo $single['compstatus']?></td>
          <td><?php echo date('H:i d/m/Y',$single['statusupdate'])?></td>
          </tr>

          <?php $i++; } ?>
          <?php } else{ ?>
            <tr><td colspan="4">No Record Found</td></tr>
          <?php } ?>
      </tbody>
    </table>
  </div>
  


<?php if ($this->rbac->hasPrivilege('complaint_extra_payment', 'can_view')) {?>
 
  <div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="complaintExtrapayment">
      <thead style="font-weight:bold;">
      <tr>     
        <td colspan="4" class="label" style="text-align: center;">Extra Payment Details &nbsp;
        <?php if ($this->rbac->hasPrivilege('complaint_extra_payment', 'can_add')) {?>
          <a href="#" class="btn btn-primary btn-sm addpayment" data-toggle="modal" data-target="#addPaymentModal">Add Payment</a>
          <?php } ?>  
        </td></tr>
           
           <tr>
             <td>Payment Note</td>             
             <td>Raised On</td>
             <td>Amount</td>
             <td>Payment Details</td>
            </tr>
      </thead>
      <tbody>
      <?php if(count($extraPayment)>0) { ?>
      <?php $i=0;foreach($extraPayment as $payment){ ?>
           <?php 
            if($payment['transactionid']==''){
               $paymentDetails="Not Paid";
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
          <?php } else{ ?>
            <tr><td colspan="4">No Record Found</td></tr>
          <?php } ?>
      </tbody>
    </table>
  </div>
  
 </div>
 <?php } ?>


 <?php if($complaint && $complaint['complaintStatus']==3 ){ ?>
  <div lable="RatingFormArea" class="w-100 rounded-1 p-4 border bg-white">
    
  <form>
  <div class="form-group">
    <label for="exampleInputEmail1">User Ratings</label>
    <input type="hidden" name="complaintStars" id="complaintStars" value="<?php echo $complaint['stars']?>" />
    <div id="starsReview"  data-value="3" ></div>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">User Feedback</label>
    <textarea class="form-control" disabled id="exampleFormControlTextarea1" rows="3"><?php echo $complaint['feedback']?></textarea>
  </div>
 </form>
 </div>
 <?php } ?> 


</div>


<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="far fa-money-bill-alt" style='color:red'></i>Add Extra Payment</h5>

      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Note:</label>
            <textarea class="form-control" id="exnote"></textarea>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Amount:</label>
            <input type="text" class="form-control" id="examount">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="ajaxextrapayment()">Save</button>
      </div>
    </div>
  </div>
</div>


</body>

<div class="hidden" id="workerlist" style="display: none !important;;">
<option>Select</option>
<?php foreach($workerlist as $worker) { ?>
  <option value="<?php echo $worker['staff_id'] ?>"><?php echo $worker['name'] ?></option>
  <?php } ?>
</div>


<?php if($complaint['staffname'] == 'Not Assigned') { ?>
<script type="text/javascript">
  var code=document.getElementById("workerlist").innerHTML;
  document.getElementById("notassigned").style.display="block";
  document.getElementById("assigned").style.display="none";
  document.getElementById("worker").innerHTML+=code;
</script>

<?php } ?>



<?php if($complaint['complaintStatus']==3){ ?>
<script type="text/javascript">
  function setRating(){
  var rating=document.getElementById("complaintStars").value;
  console.log(rating);
  var color=generateColor(rating);
  $(document).ready(function(){
    $('#starsReview').jsRapStar({
				step: false,
				value:rating,
				length:5,
				starHeight:50,
        enabled: false,
        colorFront:color,
			});
    });  
}
</script>
<?php } else {?>
  <script>
  function setRating(){
  console.log('complaint not closed');
  }
  </script>
<?php }?>

<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect = "<?php echo current_url(); ?>";
var complaint = "<?php echo ($complaint['complaint_id'])?>"
/*
var outerheight = $(".firstable").outerHeight();
var screenheight = screen.height;

if (outerheight > screenheight) {
	screenheight = outerheight;
}
var element = document.getElementById("addratingTablemessage");
element.style.height = screenheight + "px";*/


$(document).ready(function() {
	$('.select2').select2();
});

$(document).ready(function() {
	$('#complaintExtrapaymentA').DataTable({
		pageLength: 2,
		"bLengthChange": false,
		searching: false,
		scrollX: false,
	});
});




function fetchRatings() {

}

function ediassign() {
	var code = document.getElementById("workerlist").innerHTML;
	document.getElementById("notassigned").style.display = "block";
	document.getElementById("assigned").style.display = "none";
	document.getElementById("worker").innerHTML += code;
}

$('.select22').change(function() {
	//document.getElementById("assignbtn").style.display="inline-block";
	ajaxassign(this.value);
});

function ajaxassign(staffid) {

	$.ajax({
		type: "POST",
		url: baseurl + "admin/admin/assignstaff",
		data: {
			'staffid': staffid,
			'compid': complaint
		},
		dataType: "JSON",
		beforeSend: function() {

		},
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {

				swal({
					title: "Worker Assigned Successfully",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
				swal({
					title: "Worker Assigned Failed",
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});
			}

			//window.location.reload();

		},
		complete: function() {

		}
	});
}


function ajaxextrapayment() {
	var amount = document.getElementById('examount').value;
	var note = document.getElementById('exnote').value;
	$('#addPaymentModal').modal('hide');
	$.ajax({
		type: "POST",
		url: baseurl + "admin/admin/ajaxextrapayment",
		data: {
			'amount': amount,
			'compid': complaint,
			'note': note,
		},
		dataType: "JSON",
		success: function(data) {
			//console.log(data.status);
			if (parseInt(data.status) === 1) {

				swal({
					title: "Extra Payment Added",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});

			} else {
				swal({
					title:data.errorP,
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					window.location = redirect;
				});
			}
		},

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


