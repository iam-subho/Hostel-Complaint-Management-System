<style>
* {
  box-sizing: border-box;
}



/* Float four columns side by side */
.column {
  float: left;
  width: 33%;
  padding: 0 10px;
}

.columnlg {
  float: left;
  width:99%;
  padding: 0 10px;
}



/* Remove extra left and right margins, due to padding */
.row {margin: 0 -5px;}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive columns */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
    display: block;
    margin-bottom: 20px;
  }
}

/* Style the counter cards */
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);  
  text-align: center;
  background-color: #f1f1f1;
}
#cardimg {
  width:91px;
  height:91px;
  position: absolute;
  left:0;
  top:0;
  z-index: 100;
}

a:hover{
    text-decoration:none;
}



</style>


                                


<div class="row">
  <div class="column">
    <div class="card text-white bg-warning">
    <div class="card-body" style="height:91px"><img id="cardimg" src="<?php echo  base_url("assets/icons/griev_total.png") ?>" alt="cardimg"><h1><?php echo $total ?></h1></div>      
       
    <div class="card-footer" style="width:100%">Number of Grievance Registered</div>
    </div>
  </div>
  
  <div class="column">
    <div class="card text-white bg-success">
    <div class="card-body" style="height:91px"><img id="cardimg" src="<?php echo  base_url("assets/icons/griev_reg.png") ?>" alt="cardimg"><h1><?php echo $pending ?></h1></div>     
      <div class="card-footer" style="width:100%">Number of Grievance Pending</div>
    </div>
  </div>
  
  <div class="column">
    <div class="card text-white bg-danger">
    <div class="card-body" style="height:91px"><img id="cardimg" src="<?php echo  base_url("assets/icons/griev_closed.png") ?>" alt="cardimg"><h1><?php echo $closed ?></h1></div>       
    <div class="card-footer" style="width:100%">Number of Grievance Closed</div>
    </div>
  </div>
</div>
<br>
    <div class="row">
      <div class="columnlg ">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Recent Complaints</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered" id="complaintList">
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
        <td><a title="Details" href="<?php echo base_url(); ?>admin/admin/getcomplaint/<?php echo base64_encode($comp['complaint_id']) ?>/<?php echo base64_encode($comp['complaintNo']); ?>" 
        target="_blank"><i class="fas fa-align-justify" style="font-size:24px"></i></a>
          <?php if($comp['staffname']!='Not Assigned'){ ?>
          <?php if($levelaccess==2){?>
          <a title="Start Chat" href="<?php echo base_url(); ?>admin/admin/chat/<?php echo base64_encode($comp['complaint_id']) ?>"><i class="fas fa-comment" style="font-size:24px"></i></a> 
          <?php } else {?>
            <br><a title="View Chat" href="<?php echo base_url(); ?>admin/admin/chatstaff/<?php echo base64_encode($comp['complaint_id']) ?>"><i class="fas fa-comment" style="font-size:24px"></i></a>   
          <?php }?>
          <?php } ?>
        </td>
     </tr>

   
   <?php } ?>
      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-footer"><a href="<?php echo base_url("admin/admin/complaintList")?>" style="float:right;text-decoration:none">More</a></div>
              </div>
              
            </div>
        </div>
    </div>



<script>
  $(document).ready(function () {
    $('#complaintListP').DataTable({      
      pageLength:5,
      "bLengthChange" : false,
      "binfo": false,
      "bPaginate": false,
    
    });
});
</script>