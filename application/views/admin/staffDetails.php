
<body>
<div class="row">
 <div class="col-md-12">
    <div class="card-body">
        <div class="row show-grid ">
            <div class="col-md-12 bg-secondary text-center"><strong><h4>Details of Staff</h4></strong></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Name </strong></div>
            <div class="col-md-9 "><?php echo $staff['name'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong>Username</strong></div>
            <div class="col-md-9 "><?php echo $staff['username'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong>Email </strong></div>
            <div class="col-md-9 "><?php echo $staff['email'] ?></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong>Mobile</strong></div>
            <div class="col-md-9 "><p><?php echo $staff['mobile'] ?></p></div>
        </div>
        <div class="row show-grid">
            <div class="col-md-3"><strong> Date of Creation </strong></div>
            <div class="col-md-9 "><?php echo date("d/m/Y",$staff['createdate']) ?></div>
        </div>          
    </div>
 </div>

</div>
<div class="clearfix"></div>
 
<div class="row">
 <div class="col-md-12 firstable">
  <?php if(isset($complaintlist)){ ?>
 <label id="list">Filter Complaint: </label>
        <select name="selected" id="select" class="select22">
        <option value="">All</option>
        <?php foreach ($statuslist as $status) { ?>
        <option value="<?php echo $status['statusId']; ?>"> <?php echo $status['status']; ?></option>
        <?php } ?>
        </select>
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
        <td><a class="btn btn-info" href="<?php echo base_url(); ?>admin/admin/getcomplaint/<?php echo ($comp['complaint_id']) ?>/<?php echo ($comp['complaintNo']); ?>" 
        target="_blank">Details </a><?php if($comp['staffname']!='Not Assigned'){ ?>
          <br><a class="btn btn-success" href="<?php echo base_url(); ?>userpanel/chat/<?php echo ($comp['complaint_id']) ?>">Chat</a> 
          <?php } ?></td>
     </tr>

   
   <?php } ?>
      </tbody>
       </table>
    </div>
  <?php } ?>
  
 </div>
</div>


</body>



<script>
var baseurl = "<?php echo base_url(); ?>";
var redirect = "<?php echo current_url(); ?>";
var staffid = "<?php echo base64_encode($staff['staff_id']) ?>";


$(document).ready(function () {
    $('#complaintList').DataTable({      
      pageLength:7,
      "bLengthChange" : false,
    
    });
});

$('.select22').change(function() {
    	ajaxgetTable(this.value);
});

function ajaxgetTable33(status){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/admin/staffbasedcomplaintlist",
		data: {'staff':staffid,'status':status},
		dataType: "JSON",
    success: function(data) {
    
    document.getElementById("complaintfilteredcontent").innerHTML='';
    document.getElementById("complaintfilteredcontent").innerHTML+=data.html;
    },
    complete: function() {
      $('#complaintList').DataTable({      
      pageLength:7,
      "bLengthChange" : false,
    
    });
    }

  });
}

function ajaxgetTable(status){
  $.ajax({
		type: "POST",
		url: baseurl + "admin/admin/staffbasedcomplaintlist",
		data: {'staff':staffid,'status':status},
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

			} else {
				swal({
					title:data.errorP,
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
       pageLength:7,
      "bLengthChange" : false,
    
    });
    }

  });
}

</script>


