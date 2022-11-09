<section class="content-header">
        <h3>
            <i class="fas fa-dollar-sign"></i>Payment List
        </h3>
        <label id="list">Filter Complaint: </label>
        <select name="selected" id="select" class="select22">
        <option value="">All</option>
        <option value="1">Paid</option>
        <option value="2">Not Paid</option>
        </select>
    </section>
    <div class="complaintfilteredcontent" id="complaintfilteredcontent">
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
</div>


<script>
var baseurl = "<?php echo base_url(); ?>";

  $(document).ready(function () {
    $('#paymentlist').DataTable({      
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
		url: baseurl + "admin/admin/filterbasepaymentlist",
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
      $('#paymentlist').DataTable({      
      pageLength:10,
      "bLengthChange" : false,
    
    });
    }

  });
}
</script>



