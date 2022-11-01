<?php if($ratingResult) { ?>
   <div class="row">
    <input type="hidden" id="ratingResult" value="<?php echo $avg ?>"/>
     <div class="container">
     <div class="panel-group">  
    <div class="panel panel-default">  
      <div class="panel-heading" style="font-weight:bold"><?php echo $avg ?>/5</div>  
      <div class="panel-body"><div id="starsReviewAvg" ></div> </div>  
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
              $ratingClass = "btn-default btn-grey";
              if($i <= $rating['stars']) {
              $ratingClass = "btn-warning";
              }
            ?> 
            <button type="button" class="btn btn-xs <?php echo $ratingClass; ?>" aria-label="Left Align">
              <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
            </button> 
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

<?php } ?>

