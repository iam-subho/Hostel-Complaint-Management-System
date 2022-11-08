
<body>
<div class="row">
 <div class="col-md-12">
    <div class="card-body">
        <div class="row show-grid ">
            <div class="col-md-12 bg-secondary text-center"><strong><h4>System Configuration</h4></strong></div>
        </div>
   <div class="clearfix"></div><br>             
    <form>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">System Name</label>
             <div class="col-sm-10">
               <input type="text" name="appname"  class="form-control" id="staticEmail" value="<?php echo $system['appname'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sendgrid Api Key</label>
             <div class="col-sm-10">
               <input type="text" name="sendgridapkey" class="form-control" id="staticEmail" value="<?php echo $system['sendgridapkey'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sendgrid From Name</label>
             <div class="col-sm-10">
               <input type="text" name="sendgridfromname"  class="form-control" id="staticEmail" value="<?php echo $system['sendgridfromname'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sendgrid From Email</label>
             <div class="col-sm-10">
               <input type="text" name="sendgridfrom"  class="form-control" id="staticEmail" value="<?php echo $system['sendgridfrom'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Proxy URL</label>
             <div class="col-sm-10">
               <input type="text" name="proxyurl"  class="form-control" id="staticEmail" value="<?php echo $system['proxyurl'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Proxy Port</label>
             <div class="col-sm-10">
               <input type="text" name="pport"  class="form-control" id="staticEmail" value="<?php echo $system['pport'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Proxy Username</label>
             <div class="col-sm-10">
               <input type="text" name="pusername" class="form-control" id="staticEmail" value="<?php echo $system['pusername'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Proxy Password</label>
             <div class="col-sm-10">
               <input type="text" name="ppassword"  class="form-control" id="staticEmail" value="<?php echo $system['ppassword'] ?>">
             </div>
           </div>
   </form>


    </div>
    
 </div>

</div>

</body>





