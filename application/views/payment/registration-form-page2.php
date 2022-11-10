<body>

<div class="container">
  <div class="row mx-0 justify-content-center">
    <div class="col-md-8">
      <form method="POST" class="w-100 rounded-1 p-4 border bg-white"  action="<?php echo base_url().'register/complaintSubmit'; ?>" enctype="multipart/form-data" >
        <label class="d-block mb-4">
          <span class="form-label d-block">Select Complaint Category</span>
          <select  name="type" class="form-control" required>
            <option value="">Select</option>
            <?php foreach($typelist as $type) { ?>
              <option value="<?php echo $type['typeid'] ?>" data-personal="<?php echo $type['personal'] ?>"> <?php echo $type['typename'] ?></option>
              <?php }?>
          </select>
        </label>

        <label class="d-block mb-4">
          <span class="form-label d-block">Complaint Type</span>
          <input class="form-control"  id="personalornot" readonly/>
        </label>

        <label class="d-block mb-4" id="subjectblock" style="display: none !important;;">
          <span class="form-label d-block" >Subject</span>
          <input class="form-control"  name="subject" />
        </label>

        <label class="d-block mb-4">
          <span class="form-label d-block">What's wrong?</span>
          <textarea
            name="description"
            class="form-control"
            rows="3"
            placeholder="Please describe your problem"
          ></textarea>
        </label>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary px-3 rounded-3">
            Submit
          </button>
        </div>


      </form>
    </div>
  </div>
</div>


<script>
$('select').change(function(){
  var id=parseInt($(this).find(':selected').attr('data-personal'));
  var type;
  if(id===1){
  type="Personal";
  }else if(id===0){
  type="Not Personal";
  }else if(id===2){
  type="Custom";
  document.getElementById("subjectblock").style.display="block";
  }
  document.getElementById("personalornot").value=type;
});

</script>
