<!DOCTYPE html>

<head>
    <title>Start Meeting</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.15.2/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.15.2/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

</head>

<body>
    <style>
        .sdk-select {
            height: 34px;
            border-radius: 4px;
        }

        .websdktest button {
            float: right;
            margin-left: 5px;
        }

        #nav-tool {
            margin-bottom: 0px;
        }

        #show-test-tool {
            position: absolute;
            top: 100px;
            left: 0;
            display: block;
            z-index: 99999;
        }

        #display_name {
            width: 250px;
        }


        #websdk-iframe {
            width: 700px;
            height: 500px;
            border: 1px;
            border-color: red;
            border-style: dashed;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            left: 50%;
            margin: 0;
        }
    </style>
    <nav id="nav-tool" class="navbar navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Start Meeting</a>
            </div>
            <div id="navbar" class="websdktest">
                <form class="navbar-form navbar-right" id="meeting_form">
                    <div class="form-group">
                        <input type="text" name="display_name" id="display_name"  maxLength="100"
                            placeholder="Name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="meeting_number" id="meeting_number" value="<?=$_GET['meeting_number'] ?? '' ?>" maxLength="200"
                            style="width:150px" placeholder="Meeting Number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="meeting_pwd" id="meeting_pwd" value="<?=$_GET['meeting_pwd'] ?? ''?>" style="width:150px"
                            maxLength="32" placeholder="Meeting Password" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="meeting_email" id="meeting_email" value="<?=$_GET['meeting_email'] ?? ''?>" style="width:150px"
                            maxLength="32" placeholder="Email option" class="form-control">
                    </div>

                    <div class="form-group">
                        <select id="meeting_role" class="sdk-select">
                            <option value=0 <?=(isset($_GET['role']) && $_GET['role']==0) ?'selected':''?>>Attendee</option>
                            <option value=1 <?=(isset($_GET['role']) && $_GET['role']==1) ?'selected':''?> >Host</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="meeting_china" class="sdk-select">
                            <option value=0 selected>Global</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="meeting_lang" class="sdk-select" >
                            <option value="en-US" selected>English</option>
                        </select>
                    </div>

                    <input type="hidden" value="" id="copy_link_value" />
                    <button type="submit" class="btn btn-primary" id="join_meeting">Join</button>
                    <button type="button" link="" onclick="window.copyJoinLink('#copy_join_link')"
                        class="btn btn-primary" id="copy_join_link">Copy Direct join link</button>

                </form>
            </div>
        </div>
    </nav>
    
    <script src="https://source.zoom.us/2.15.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.15.2.min.js"></script>
    <script src="js/tool.js"></script>
    <script src="js/vconsole.min.js"></script>
    
    <?php require_once "js/index_js.php"; ?>
    <script>
     window.onload = function() {
         <?php if(isset($_GET['role']) && $_GET['role']==0): ?>
            var name = prompt("Please enter your name:");
            document.getElementById('display_name').value = name;
        <?php endif; ?>
        
        <?php if(isset($_GET['name'])): ?>
            document.getElementById('display_name').value = "<?=$_GET['name']?>";
        <?php endif; ?>
        
        document.getElementById("meeting_number").value = "<?=$_GET['meeting_number'] ?? ''?>";
        document.getElementById("meeting_pwd").value = "<?=$_GET['meeting_pwd'] ?? ''?>";
        <?php if(isset($_GET['role'])): ?>
            document.getElementById('join_meeting').click();
        <?php endif; ?>
        
    }
    </script>
</body>

</html>