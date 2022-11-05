
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
        <td>Complaint By</td>
        <td>Complaint Status</td>
        <td>Registered Date</td>
        <td>Last Update</td>
        <td>Complaint Assigned</td>
        <td>Payment Details</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody id="complaintContent">
    <?php foreach($complaintlist as $comp) { ?>
        <?php
        if($comp['paymentTransactionId']== null && $comp['personal']==1){
        $status="Not Paid";
        $paymentDetails='Not Paid';
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
        <td> <?php echo $comp['name']; ?></td>
        <td><?php echo $status; ?></td>
        <td> <?php echo date('H:i d/m/Y',$comp['complaintDate']); ?></td>
        <td> <?php echo date('H:i d/m/Y',$comp['lastupdate']); ?></td>
        <td> <?php echo $comp['staffname']; ?></td>
        <td><?php echo $paymentDetails ?></td>
        <td><a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getcomplaint/<?php echo base64_encode($comp['complaint_id']) ?>/<?php echo base64_encode($comp['complaintNo']); ?>" 
        target="_blank">Details </a><?php if($comp['staffname']!='Not Assigned'){ ?>
          <br><a class="btn btn-success" href="<?php echo base_url(); ?>userpanel/chat/<?php echo base64_encode($comp['complaint_id']) ?>">Chat</a> 
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
		url: baseurl + "admin/admin/filterbasecomplaintlist",
		data: {'status':status},
		dataType: "JSON",
    success: function(data) {
    
    document.getElementById("complaintfilteredcontent").innerHTML='';
    document.getElementById("complaintfilteredcontent").innerHTML+=data.html;
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



