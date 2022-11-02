

<body>
<div class="container">
	<div class="row">
		<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
			<div class="card card-signin my-5">
				<div class="card-body">
					<center>
						<img src="<?php echo base_url().'/assets/icons/payment-success.png'?>">
						<h5 class="card-title text-center">Payment successful !</h5>
						<p>Your order ID : <?php echo $_SESSION['razorpay_order_id'];?></p>
						<p>Your Complaint No: <?php echo $this->userpanel_model->getcomplaintNo($_SESSION['razorpay_order_id']) ?></p>
						<a href="<?php echo base_url();?>userpanel/complaintlist" class="btn btn-primary">Complaint List</a>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
