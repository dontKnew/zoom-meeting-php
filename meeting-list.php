<?php
require_once "helper.php";
$accessToken = getAccessToken(); 
if(!empty($accessToken)){
    $userId = 'me';
    $base_url = 'https://api.zoom.us/v2';
    $meetings_url = $base_url . '/users/' . $userId . '/meetings';
    $meetings_headers = array(
        'Authorization: Bearer ' . $accessToken
    );
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $meetings_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $meetings_headers);
    
    $meetings_response = curl_exec($ch);
    curl_close($ch);
    $meetings_result = json_decode($meetings_response, true);
    if ($meetings_result && isset($meetings_result['meetings'])) {
        $meetings_list = $meetings_result['meetings'];
    } else {
        return "Failed to get meetings list";
    }    
}

if(isset($_GET['id'])){
    deleteMeeting($_GET['id']);
    header("Location:meetings.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom Meeting List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
body {
    background-color:black;
    color:yellow;
}
</style>
<body>

<div class="container mt-5">
    <div class='d-flex justify-content-center'>
        <a href="new-meeting.php" class='btn btn-primary'> Create New Meeting </a>
    </div>
    <h2>Zoom Meeting List</h2>
    <?php if(isset($_GET['update'])): ?>
        <div class='alert alert-danger py-2 text-center fw-bold'> Meeting Updated Successfully.. </div>
    <?php endif;?>
    <div  style="overflow-x:auto">
    <table class="table table-responsive border table-warning" >
        <thead>
            <tr >
                <th class='bg-warning'>Meeting_ID</th>
                <th class='bg-warning'>Topic</th>
                <th class='bg-warning'>Start_Time</th>
                <th class='bg-warning'>Duration</th>
                <th class='bg-warning'>Timezone</th>
                <th class='bg-warning'>Admin_URL</th>
                <th class='bg-warning'>Public_URL</th>
                <th class='bg-warning'>Zoom_URL</th>
                <th class='bg-warning'>Created_At</th>
                <th class='bg-warning'>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($meetings_list as $meeting) : 
                $params = [
                'name'=>"Doctor",
                'meeting_number' => $meeting['id'],
                'meeting_pwd' => '123',
                'role' => '1'
            ];
            
            $adminA = $params;
            unset($params['name']);
            $params['role'] = 0;
            $publicP = $params;
                
                ?>
                <tr>
                    <td><?php echo $meeting['id']; ?></td>
                    <td><?php echo $meeting['topic']; ?></td>
                    <td><?php echo readDate($meeting['start_time']); ?></td>
                    <td><?php echo $meeting['duration']; ?>mnts.</td>
                    <td><?php echo $meeting['timezone']; ?></td>
                    <td><a href="<?php echo meetingURL($adminA) ?>" class='link-danger' target="_blank">admin</a></td>
                    <td><a href="<?php echo meetingURL($publicP) ?>" class='link-danger' target="_blank">public</a></td>
                    <td><a href="<?php echo $meeting['join_url']; ?>" class='link-danger' target="_blank">zoom</a></td>
                    <td><?php echo readDate($meeting['created_at']); ?></td>
                    <td><a href="?id=<?=$meeting['id']?>"  class='btn btn-danger'>Delete</a> <a href="new-meeting.php?id=<?=$meeting['id']?>"  class='btn btn-success'>Edit</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>

<script>
/*window.onload  = function(){
    
    function getPassword() {
        let password = prompt("Please enter password");
        
        while (password !== 'master') {
            if (password === null || password === "") {
                password = prompt("Please enter password");
            } else {
                password = prompt("Incorrect password. Please try again:");
            }
        }
        return password;
    }
    let validPassword = getPassword();
}*/


</script>
</html>

