<!doctype html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Razor Pay Integration in CodeIgniter</h5>
                    <form action="<?php echo base_url().'register/complaintSubmit'; ?>" method="post" class="form-signin">
                        <div class="form-label-group">
                            <label for="name">Type <span style="color: #FF0000">*</span></label>
                            <input type="text" name="type" id="name" class="form-control" placeholder="Select Type"  required >
                        </div> <br/>
                        <div class="form-label-group">
                            <label for="email">Description <span style="color: #FF0000">*</span></label>
                            <input type="text" name="description" id="email" class="form-control" placeholder="Description" required>
                        </div> <br/>
                       <br/>
                        <button type="submit" name="sendMailBtn" class="btn btn-lg btn-primary btn-block text-uppercase" >Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>