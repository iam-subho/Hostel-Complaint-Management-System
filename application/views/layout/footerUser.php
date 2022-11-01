</div>  
    </main>  
    <footer class="footer">  
      <div class="container">  
          <div class="text-center">  
              &copy <?php echo date("Y") ?> &nbsp; Public Grievance 
          </div>  
      </div>  
    </footer>  
    <script>  
    $(document).ready(function() {  
      $('.nav-link-collapse').on('click', function() {  
        $('.nav-link-collapse').not(this).removeClass('nav-link-show');  
        $(this).toggleClass('nav-link-show');  
      });  
    });  
    </script>  


</body> 
</html>
<!-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>  -->
<script>
  /*window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "3d9a5719-0059-4654-8980-b024d661ce21",
      safari_web_id: "web.onesignal.auto.145f18a4-510a-4781-b676-50fa3f7fa700",
      notifyButton: {
        enable: true,
      },
      subdomainName: "publicgrievanc",
    });
    OneSignal.provideUserConsent(true);

  });*/
</script>