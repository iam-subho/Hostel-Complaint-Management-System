<?php if($ratingResult) { ?>
   <div class="row">
      <input type="hidden" id="ratingResult" value="<?php echo $avg ?>"/>
       <div class="container">
         <div class="panel-group">  
             <div class="panel panel-default">  
                <div class="panel-heading" style="font-weight:bold;display: inline"><div id="starsReviewAvg" ></div><?php echo $avg ?>/5 </div>  
              </div>
          </div>
        </div>
        







     </div> 
   

   

   <div class="row">
     <div class="col-sm-12">
      <hr />
       <div class="review-block"> 
        <?php
        foreach($ratingResult as $rating){
         $reviewDate = date("M d, Y",$rating['lastupdate']);
         ?> 
         <div class="row">
            <div class="col-sm-3">
             <img src="<?php echo base_url()?>/assets/icons/user-profile.png" class="img-rounded" height="50" width="50">
           <div class="review-block-date"> <?php echo $reviewDate; ?> </div>
         </div>
        <div class="col-sm-9">
          <div class="review-block-rate"> 
            <?php
             for($i = 1; $i <= 5; $i++) {
              $ratingClass = "fa fa-star-o";
              if($i <= $rating['stars']) {
              $ratingClass = "fa fa-star";
              }
            ?> 
              <span class="<?php echo $ratingClass ?>" style="font-size:24px;color:<?php echo colorreturn($rating['stars']) ?>" aria-hidden="true"></span>
             
            <?php } ?> 
           </div>
          <div class="review-block-title"> <?php echo $rating['uname']; ?> </div>
          <div class="review-block-description"> <?php echo $rating['feedback']; ?> </div>
        </div>
      </div>
      <hr /> <?php } ?>
    </div>
  </div>
  </div>

<?php } else { ?>
<div class="review-block" style="text-align:center"><h2>No Record Found!</h2></div>
<?php } ?>
<?php

function colorreturn($rat){
  $color='';
  if($rat< 2){
    $color='red';
    }else if($rat >=2 && $rat < 3.8){
    $color='yellow';
    }else if($rat >=3.8){
    $color='green';
    }
    return $color;

}
?>