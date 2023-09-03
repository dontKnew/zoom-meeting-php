<?php
require_once __DIR__.'/../../config.php'
?>
<script>

window.addEventListener('DOMContentLoaded', function(event) {
  console.log('DOM fully loaded and parsed');
  websdkready();
});

function websdkready() {
  var testTool = window.testTool;
  var CLIENT_ID = "<?=SDK_CLIENT_ID?>";
  var CLIENT_SECRET = "<?=SDK_CLIENT_SECRET?>";
  
  document
    .getElementById("join_meeting")
    .addEventListener("click", function (e) {
      e.preventDefault();
      var meetingConfig = testTool.getMeetingConfig();
      if (!meetingConfig.mn || !meetingConfig.name) {
        alert("Meeting number or username is empty");
        return false;
      }

      
      testTool.setCookie("meeting_number", meetingConfig.mn);
      testTool.setCookie("meeting_pwd", meetingConfig.pwd);

      var signature = ZoomMtg.generateSDKSignature({
        meetingNumber: meetingConfig.mn,
        sdkKey: CLIENT_ID,
        sdkSecret: CLIENT_SECRET,
        role: meetingConfig.role,
        success: function (res) {
          console.log(res.result);
          meetingConfig.signature = res.result;
          meetingConfig.sdkKey = CLIENT_ID;
          var joinUrl = "/test/meeting/host/meeting.php?" + testTool.serialize(meetingConfig);
          console.log(joinUrl);
          window.open(joinUrl, '__blank');
        },
      });
    });

  function copyToClipboard(elementId) {
    var aux = document.createElement("input");
    aux.setAttribute("value", document.getElementById(elementId).getAttribute('link'));
    document.body.appendChild(aux);  
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
  }
    
  // click copy jon link button
  window.copyJoinLink = function (element) {
    var meetingConfig = testTool.getMeetingConfig();
    if (!meetingConfig.mn || !meetingConfig.name) {
      alert("Meeting number or username is empty");
      return false;
    }
    var signature = ZoomMtg.generateSDKSignature({
      meetingNumber: meetingConfig.mn,
      sdkKey: CLIENT_ID,
      sdkSecret: CLIENT_SECRET,
      role: meetingConfig.role,
      success: function (res) {
        console.log(res.result);
        meetingConfig.signature = res.result;
        meetingConfig.sdkKey = CLIENT_ID;
        var joinUrl =
          testTool.getCurrentDomain() +
          "/test/meeting/host/meeting.php?" +
          testTool.serialize(meetingConfig);
        document.getElementById('copy_link_value').setAttribute('link', joinUrl);
        copyToClipboard('copy_link_value');
        
      },
    });
  };

}
</script>