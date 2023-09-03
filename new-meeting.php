<?php 
require_once __DIR__."/helper.php";

$timezones = timezone_identifiers_list();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $data = $_POST;
    $data['type'] = 2;
    if(!empty($data['settings']['host_video'])){
        $data['settings']['host_video'] = true;
    }else {
        $data['settings']['host_video'] = false;
    }
    if(!empty($data['settings']['participant_video'])){
        $data['settings']['participant_video'] = true;
    }else {
        $data['settings']['participant_video'] = false;
    }
    if(!empty($data['settings']['join_before_host'])){
        $data['settings']['join_before_host'] = true;
    }else{
        $data['settings']['join_before_host'] = false;
    }
    if(empty($data['password'])){
        $data['password'] = '';
    }
    
    if(isset($_GET['id'])){
        if(updateMeeting($_GET['id'], $data)){
            header("Location:meetings.php?update=true");   
        }
    }else {
     if(createMeeting($data)){
            header("Location:meetings.php");   
        }   
    }
}
if(isset($_GET['id'])){
    $data = getMeeting($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Zoom Meeting</title>
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
        <a href="meetings.php" class='btn btn-success'> All Meetings List </a>
    </div>
    <h2>Schedule The Zoom Meeting</h2>
    <form action="" method="post">
    <div class='row'>
        <div class="mb-3 col-md-6">
            <label for="topic" class="form-label">Meeting Title<span class='text-danger'>*</span></label>
            <input type="text" class="form-control text-warning fw-bold" id="topic" value="<?=$data['topic'] ?? '' ?>" name="topic" required>
        </div>
        <div class="mb-3 col-md-6">
            <label for="duration" class="form-label">Duration (minutes)<span class='text-danger'>*</span></label>
            <input type="number" class="form-control text-warning fw-bold" id="duration" value="<?=$data['duration'] ?? ''?>" name="duration" required>
        </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Timezone<span class='text-danger'>*</span></label>
            <select class="form-select text-warning fw-bold"  name="timzone">
                   <?php foreach ($timezones as $timezone) : ?>
                    <option value="<?php echo $timezone; ?>" <?=($timezone=='UTC')?'selected':''?>>
                         <?php echo $timezone; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3 col-md-6">
            <label for="start_time" class="form-label">Start Time<span class='text-danger'>*</span></label>
<input type="datetime-local" class="form-control text-warning fw-bold" id="start_time" value="<?= date('Y-m-d\TH:i', strtotime($data['start_time'])) ?? '' ?>" name="start_time" required>
        </div>
        
        <div class="mb-3 form-check col-md-4">
            <input type="checkbox" class="form-check-input text-warning fw-bold" id="host_video" name="settings[host_video]"  <?=(isset($data['settings']['host_video']) && $data['settings']['host_video']=='1' ) ? 'checked':'checked' ?> >     
            <label class="form-check-label" for="host_video">Enable Host Video</label>
        </div>
        <div class="mb-3 form-check col-md-4">
            <input type="checkbox" class="form-check-input text-warning fw-bold" id="participant_video" name="settings[participant_video]" <?=(isset($data['settings']['participant_video']) && $data['settings']['participant_video']=='1' ) ? 'checked':'checked' ?>>
            <label class="form-check-label" for="participant_video">Enable Participant Video</label>
        </div>
        <div class="mb-3 form-check col-md-4">
            <input type="checkbox" class="form-check-input text-warning fw-bold" id="join_before_host" name="settings[join_before_host]" <?=(isset($data['settings']['join_before_host']) && $data['settings']['join_before_host']=='1' ) ? 'checked':'checked' ?>>
            <label class="form-check-label" for="join_before_host">Allow Join Before Host</label>
        </div>
        <div class="mb-3 col-md-6">
            <label for="auto_recording" class="form-label">Auto Recording</label>
            
            <select class="form-select text-warning fw-bold" id="auto_recording" name="settings[auto_recording]">
                <option value="none" <?=(isset($data['settings']['auto_recording']) && $data['settings']['auto_recording']=='none' ) ? 'selected':'' ?> >None</option>
                <option value="local" <?=(isset($data['settings']['auto_recording']) && $data['settings']['auto_recording']=='local' ) ? 'selected':'' ?> >Local</option>
                <option value="cloud" <?=(isset($data['settings']['auto_recording']) && $data['settings']['auto_recording']=='cloud' ) ? 'selected':'' ?>>Cloud</option>
            </select>
        </div>
          <div class="mb-3 col-md-6">
            <label for="start_time" class="form-label">Password</label>
            <input type="text" class="form-control" name="password" value="<?= $data['password'] ?? ''?>" required>
        </div>
        <div class='col-md-12 d-flex justify-content-center'>
            <button type="submit"  class=" btn-block btn btn-outline-warning btn-lg">Submit</button>
        </div>
    </div>
    </form>
</div>

</body>
</html>
