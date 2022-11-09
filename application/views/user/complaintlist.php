
  <section class="content-header">
        <h3>
            <i class="fa fa-calendar"></i>Complaint List
        </h3>
        <label id="list">Filter Complaint: </label>
        <select name="selected" id="select" class="select22">
        <option value="">All</option>
        <?php foreach ($statuslist as $status) { ?>
        <option value="<?php echo $status['statusId']; ?>"> <?php echo $status['status']; ?></option>
        <?php } ?>
        </select>
    </section>
    <div class="complaintfilteredcontent" id="complaintfilteredcontent">   
<table id='complaintList' class="table table-striped table-bordered" border="1">
  <thead>
    <tr>
        <td>Complaint No</td>
        <td>Complaint Status</td>
        <td>Registered Date</td>
        <td>Last Update</td>
        <td>Complaint Assigned</td>
        <td>Payment Details</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($complaintlist as $comp) { ?>
        <?php
        if($comp['paymentTransactionId']== null && $comp['personal']==1){
        $status="<a href=".base_url()."register/retrypayment/".$comp['complaint_id']."/".$comp['complaint_type'].">Retry Payment</a>";
        $paymentDetails='Payment Not Completed';
        }else if($comp['paymentTransactionId']==0 && $comp['personal']==0){
        $status=$comp['compstatus']; 
        $paymentDetails="Payment Not Required";   
        }else{
        $status=$comp['compstatus'];
        $paymentDetails="Transaction ID: ".$comp['paymentTransactionId']."<br>Transaction Date: ".date('H:i d/m/Y',$comp['paymentDate'])."";
        }    
            
        ?>
      <tr>
        <td> <?php echo $comp['complaintNo']; ?></td>
        <td><?php echo $status; ?></td>
        <td> <?php echo date('H:i d/m/Y',$comp['complaintDate']); ?></td>
        <td> <?php echo date('H:i d/m/Y',$comp['lastupdate']); ?></td>
        <td> <?php echo $comp['staffname']; ?></td>
        <td><?php echo $paymentDetails ?></td>
        <td>
          <a title="Details" href="<?php echo base_url(); ?>userpanel/complaintdetails/<?php echo base64_encode($comp['complaint_id']) ?>/<?php echo base64_encode($comp['complaintNo']); ?>" 
        target="_blank"><i class="fas fa-align-justify" style="font-size:24px"></i>
         </a><?php if($comp['staffname']!='Not Assigned'){ ?>
          <br><a title="Start Chat" href="<?php echo base_url(); ?>userpanel/chat/<?php echo base64_encode($comp['complaint_id']) ?>"><i class="fas fa-comment" style="font-size:24px"></i></a> 
          <?php } ?></td>
     </tr>

   
   <?php } ?>
      </tbody>
</table>
        </div>


<script>
  var baseurl = "<?php echo base_url(); ?>";


  $(document).ready(function () {
    $('#complaintList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
});

$('.select22').change(function() {
    	ajaxgetTable(this.value);
});

function ajaxgetTable(status){
  $.ajax({
		type: "POST",
		url: baseurl + "userpanel/filterbasecomplaintlist",
		data: {'status':status},
		dataType: "JSON",
    success: function(data) {    
    document.getElementById("complaintfilteredcontent").innerHTML='';
    if(parseInt(data.status) === 1) {
      document.getElementById("complaintfilteredcontent").innerHTML+=data.html;
      swal({
					title: "Data Loaded Successfully",
					type: "success",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				}, function() {
					//window.location = redirect;
				});
    }else{
      swal({
					title:"Data Loading Error",
					type: "warning",
					showConfirmButton: true,
					confirmButtonText: "Ok",
					closeOnConfirm: true
				 }, function() {
					//window.location = redirect;
				});
    }
    
    },
    complete: function() {
      $('#complaintList').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
    }

  });
}
</script>




