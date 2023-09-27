<!DOCTYPE html>

<head>
    <title>PHP Master Meeting</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.15.2/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.15.2/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="origin-trial" content="">
</head>

<style>
.custom-img {
    position: absolute;
    top: 20px;
    right:20px;
    }
</style>
<body>

    <script src="https://source.zoom.us/2.15.2/lib/vendor/react.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/react-dom.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/redux.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/redux-thunk.min.js"></script>
    <script src="https://source.zoom.us/2.15.2/lib/vendor/lodash.min.js"></script>
    <script src="https://source.zoom.us/zoom-meeting-2.15.2.min.js"></script>
    <script src="js/tool.js"></script>
    
    <script src="js/vconsole.min.js"></script>
    <?php require_once __DIR__."/js/meeting_js_.php"; ?>

    <script>
        var img = document.createElement('img');
        img.src = 'https://5.imimg.com/data5/HO/QZ/ZA/SELLER-3945255/php-master-course-500x500.png';
        img.className = 'custom-img';
        document.body.appendChild(img);
    </script>
</body>


</html>