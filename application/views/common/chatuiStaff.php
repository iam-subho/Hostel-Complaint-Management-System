<style>
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}

.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}


.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  width: 100%;
}

.megspadding {
    padding: 30px 0px 0 0px;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height:50px;
  width: 100%;
}

.input_msg_closed input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color:red;
  font-size:30px;
  min-height:50px;
  width: 100%;
}

.type_msg {
    border-top: 1px solid #c4c4c4;
    position: relative;
    width: 100%;
 }
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 100px 0;}
.msg_history {
  height: 500px;
  width: 100%;
  overflow-y: auto;
}

</style>
<?php 
//echo $complaintid;
$lastid=1;
$this->session->set_userdata('bool',0);

?>
<div class="container">
<h3 class=" text-center">Conversation for Complaint No: <?php echo $complaintNo ?></h3>
<div class="messaging">
      <div class="inbox_msg">

        <div class="mesgs">
          <div class="megspadding">
          <div class="msg_history" id="addlatestmessage">

        
        <?php $bool=0; foreach ($messagelist as $message) { 
            
        ($lastid<$message['messageid']) ?$lastid=$message['messageid']:'';   
        ?>


        <?php if ($message['whosend']==2) { ?>
          <div class="incoming_msg">
              <div class="incoming_msg_img"><?php if($this->session->userdata('bool')==0){ ?> <img src="<?php echo base_url()?>/assets/icons/user-profile.png" alt="subhojit"><?php }?> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p><?php echo $message['message']?></p>
                  <span class="time_date"><?php echo date("H:i",strtotime($message['timestamp'])) ?>    |    <?php echo date("d-m-Y",strtotime($message['timestamp'])) ?></span></div>
              </div>
            </div>

        <?php $this->session->set_userdata('bool',1);} else { $this->session->set_userdata('bool',0);?>

            <div class="outgoing_msg">
              <div class="sent_msg">
              <p><?php echo $message['message']?></p>
              <span class="time_date"><?php echo date("H:i",strtotime($message['timestamp'])) ?>    |    <?php echo date("d-m-Y",strtotime($message['timestamp'])) ?></span></div>

            </div>

        <?php } ?>
        <?php } ?>
          </div></div>
          <div class="type_msg">
            <?php if($closed==1) { ?>
            <div class="input_msg_write">
              <input type="text" class="write_msg" id="wmsg" placeholder="Type a message" />
              <button class="msg_send_btn" id='msg_send_btn' type="button" title="Send" onclick="sendMessage()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
            <?php } else { ?>
            <div class="input_msg_closed">
            <input type="text" class="write_msg" placeholder="Chat Closed" disabled readonly />
            </div>
           <?php } ?>     
          </div>
        
      
      
      
     <input type="hidden" id='lastid' name="lastid" value="<?php echo $lastid; ?>" /> 
    </div></div>
    </body>


    <script>
    var baseurl="<?php echo base_url(); ?>";
    var scroll=0;

    inverval_timer = setInterval(function() { 
                    //console.log("5 seconds completed");
                    getUpdateChat();
                   }, 5000); 


   document.getElementById('wmsg').addEventListener('keydown', (event) => {
     if(event.keyCode == 13) {
        document.getElementById("msg_send_btn").click();  //id of the button to be clicked
    }
   })             
                   
                   
    function sendMessage(){
        var message=document.getElementById('wmsg').value;
        $.ajax({
            type: "POST",
            url: baseurl + "admin/admin/sendChatMessage",
            data: {'compid':'<?php echo $chatroomid?>','message':message},
            dataType: "JSON",
            beforeSend: function () {

            },
            success: function (data) {
            document.getElementById('wmsg').value='';
            getUpdateChat();
            scroll=1;
            },
            complete: function () {

           }
        });
    }

    function getUpdateChat() {

            //document.getElementById("addlatestmessage").innerHTML='';
            var lastid=document.getElementById('lastid').value;

            //console.log(lastid);
            $.ajax({
                type: "POST",
                url: baseurl + "admin/admin/updatechathistory",
                data: {'compid':'<?php echo $chatroomid?>','lastid':lastid},
                dataType: "JSON",
                beforeSend: function () {

                },
                success: function (data) {
                   
                    document.getElementById("addlatestmessage").innerHTML+=data.html;
                    document.getElementById('lastid').value=data.lastid;
                    if(Boolean(scroll)){
                    $(".msg_history").stop().animate({ scrollTop: $(".msg_history")[0].scrollHeight}, 1000);
                    }
                    scroll=0;
                },
                complete: function () {

                }
            });
        
    }
    </script>