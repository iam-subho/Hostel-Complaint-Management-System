<form action="<?php echo base_url('admin/systemtask/systemconfigureupdate'); ?>" method="post">
<div class="col-md-12">
<div class="card" >
<div class="card-header text-center">System Configuration</div>
<div class="card-body">
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
    System
   </div>
     <div class="card-body">
       <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">System Name</label>
          <div class="col-sm-10">
              <input type="text" name="appname"  class="form-control" id="staticEmail" value="<?php echo $system['appname'] ?>">
           </div>
          </div>
          <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Email Verification</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="proxyactive" type="radio" <?php echo ($system['emailverification']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="emailverification" type="radio" <?php echo ($system['emailverification']==0)?'checked':'' ?> id="inlineCheckbox2" value="0">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
              </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Default Account Status</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="defaultacstatus" type="radio" <?php echo ($system['defaultacstatus']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="defaultacstatus" type="radio" <?php echo ($system['defaultacstatus']==2)?'checked':'' ?> id="inlineCheckbox2" value="2">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
              </div>
           </div>
    </div>
  </div>
</div>
<div class="clear-fix"></div>   <br>

<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
    SMTP Credentials
   </div>
     <div class="card-body">
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
    </div>
  </div>
</div>
<br>
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
    Proxy Deatils
   </div>
     <div class="card-body">
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Proxy Active</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="proxyactive" type="radio" <?php echo ($system['proxyactive']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="proxyactive" type="radio" <?php echo ($system['proxyactive']==0)?'checked':'' ?> id="inlineCheckbox2" value="0">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
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
    </div>
  </div>
</div>


<br>
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
   Google OAuth Deatils
   </div>
     <div class="card-body">
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Google OAuth Status</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="goauthactive" type="radio" <?php echo ($system['goauthactive']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="goauthactive" type="radio" <?php echo ($system['goauthactive']==0)?'checked':'' ?> id="inlineCheckbox2" value="0">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
              </div>
           </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Client ID</label>
             <div class="col-sm-10">
               <input type="text" name="gclientid"  class="form-control" id="staticEmail" value="<?php echo $system['gclientid'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Client Secret</label>
             <div class="col-sm-10">
               <input type="text" name="gclientsecret"  class="form-control" id="staticEmail" value="<?php echo $system['gclientsecret'] ?>">
             </div>
           </div>
    </div>
  </div>
</div>

<br>
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
   Twitter OAuth Deatils
   </div>
     <div class="card-body">
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Twitter OAuth Status</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="toauthactive" type="radio" <?php echo ($system['toauthactive']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="toauthactive" type="radio" <?php echo ($system['toauthactive']==0)?'checked':'' ?> id="inlineCheckbox2" value="0">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
              </div>
           </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Consumer Key</label>
             <div class="col-sm-10">
               <input type="text" name="tconsumerkey"  class="form-control" id="staticEmail" value="<?php echo $system['tconsumerkey'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Consumer Secret</label>
             <div class="col-sm-10">
               <input type="text" name="tconsumersecret"  class="form-control" id="staticEmail" value="<?php echo $system['tconsumersecret'] ?>">
             </div>
           </div>
    </div>
  </div>
</div>

<br>
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
   Facebook OAuth Deatils
   </div>
     <div class="card-body">
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Facebook OAuth Status</label>
              <div class="col-sm-10">
                 <div class="form-check form-check-inline">
                   <input class="form-check-input" name="foauthactive" type="radio" <?php echo ($system['foauthactive']==1)?'checked':'' ?> id="inlineCheckbox1" value="1">
                   <label class="form-check-label" for="inlineCheckbox1">Active</label>
                 </div>
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" name="foauthactive" type="radio" <?php echo ($system['foauthactive']==0)?'checked':'' ?> id="inlineCheckbox2" value="0">
                  <label class="form-check-label" for="inlineCheckbox2">De Active</label>
                 </div>
              </div>
           </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">App Id</label>
             <div class="col-sm-10">
               <input type="text" name="fappid"  class="form-control" id="staticEmail" value="<?php echo $system['fappid'] ?>">
             </div>
           </div>
           <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">App Secret</label>
             <div class="col-sm-10">
               <input type="text" name="fappsecret"  class="form-control" id="staticEmail" value="<?php echo $system['fappsecret'] ?>">
             </div>
           </div>
    </div>
  </div>
</div>


<br>
<div class="col-md-12">
 <div class="card" >
   <div class="card-header">
    Payment Gateway(Razorpay Gateway)
   </div>
     <div class="card-body">
         <div class="form-group row">
          <label for="staticEmail" class="col-sm-2 col-form-label">Key</label>
            <div class="col-sm-10">
              <input type="text" name="rakey"  class="form-control" id="staticEmail" value="<?php echo $system['rakey'] ?>">
           </div>
          </div>
          <div class="form-group row">
          <label for="staticEmail" class="col-sm-2 col-form-label">Secret Key</label>
            <div class="col-sm-10">
              <input type="text" name="rasecretkey"  class="form-control" id="staticEmail" value="<?php echo $system['rasecretkey'] ?>">
           </div>
          </div>
    </div>
  </div>
 </div>
</div>



<div class="card-footer text-muted" style="float:right"><button class="btn btn-success btn-sm float-right" type="">Submit</button></div>

</div>
</div>
</form>