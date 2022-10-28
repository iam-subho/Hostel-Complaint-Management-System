<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

</html>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  window.OneSignal = window.OneSignal || [];
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

  });
</script>