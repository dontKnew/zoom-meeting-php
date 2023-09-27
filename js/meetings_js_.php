<?php
    require_once __DIR__.'/../config/helper.php';
?>
<script>

var client_id = "<?=SDK_CLIENT_ID?>";
var client_secret = "<?=SDK_CLIENT_SECRET?>";
var meeting_number = "<?=$meeting['id']?>";
var name = "<?=$name?>";
var password = "<?=$password?>";
var email = "<?=$email?>";
var leaveUrl = "<?=BASE_URL.'/thank-you.php?who='.$_GET['who'].'&id='.$meeting['id']?>";
var role = 0;

window.addEventListener('DOMContentLoaded', function(event) {
    start();
});


function start(){
  
  var testTool = window.testTool;
  if (testTool.isMobileDevice()) { vConsole = new VConsole();}
  
  
  var meetingConfig = meetingData();
  
  if(meetingConfig.china)
  ZoomMtg.setZoomJSLib("https://jssdk.zoomus.cn/2.15.2/lib", "/av"); // china cdn option'   
  ZoomMtg.preLoadWasm();
  ZoomMtg.prepareJssdk();
  
  function beginJoin() {
    var tmpArgs = testTool.parseQuery();
    ZoomMtg.init({
      leaveUrl: meetingConfig.leaveUrl,
      webEndpoint: meetingConfig.webEndpoint,
      disableCORP: !window.crossOriginIsolated, // default true
      // disablePreview: false, // default false
      externalLinkPage: meetingConfig.leaveUrl,
      success: function () {
        ZoomMtg.i18n.load(meetingConfig.lang);
        ZoomMtg.i18n.reload(meetingConfig.lang);
        ZoomMtg.join({
          meetingNumber: meetingConfig.meetingNumber,
          userName: meetingConfig.userName,
          signature: meetingConfig.signature,
          sdkKey: meetingConfig.sdkKey,
          userEmail: meetingConfig.userEmail,
          passWord: meetingConfig.passWord,
          success: function (res) {
            ZoomMtg.getAttendeeslist({});
            ZoomMtg.getCurrentUser({
              success: function (res) {
                //console.log("success getCurrentUser", res.result.currentUser);
              },
            });
          },
          error: function (res) {
            console.log(res);
          },
        });
      },
      error: function (res) {
        console.log(res);
      },
    });

    ZoomMtg.inMeetingServiceListener('onUserJoin', function (data) {
         console.warn("User Join , Now You cann something function here..");
    });
  
    ZoomMtg.inMeetingServiceListener('onUserLeave', function (data) {
      console.warn("on User Leave , Now You cann something function here..");
    });
  
    ZoomMtg.inMeetingServiceListener('onUserIsInWaitingRoom', function (data) {
      console.warn("user is waiting roomt , Now You cann something function here..");
    });
  
    ZoomMtg.inMeetingServiceListener('onMeetingStatus', function (data) {
      console.warn("send meeting status to somewhere data");
    });
  }
  beginJoin();
};


function getSignature(){
    let result = "";
    ZoomMtg.generateSDKSignature({
        meetingNumber: meeting_number,
        sdkKey: client_id,
        sdkSecret: client_secret,
        role: 0,
        success: function (res) {
          result = res.result;
        },
  });
  
  return result;
}



function meetingData() {
  return {
        sdkKey: client_id,
        meetingNumber: meeting_number,
        userName: name,
        passWord: password,
        leaveUrl: leaveUrl,
        role: role,
        userEmail: email,
        lang: "en",
        signature: getSignature(),
        china: 0,
      };
}
</script>