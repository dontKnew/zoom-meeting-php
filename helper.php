<?php
require_once __DIR__."/config.php";

function createMeeting($data){
    $accessToken = getAccessToken();
    if(empty($accessToken)){
       return "access token could not get";
    }
    $base_url = 'https://api.zoom.us/v2';
    
    /*$data = array(
        'topic' => 'Meeting Title',
        'type' => 2, // 2 for scheduled meeting
        'start_time' => '2023-08-30T12:00:00', // Use the desired start time in ISO 8601 format
        'duration' => 60, // Meeting duration in minutes
        'timezone' => 'UTC', // Specify the timezone
        'password' => '', // No password for the meeting
        'settings' => array(
            'host_video' => true,
            'participant_video' => true,
            'join_before_host' => true,
            'auto_recording' => 'local', // 'local' for local recording, 'cloud' for cloud recording, 'none' for no recording
        ),
    );*/
    
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer ".$accessToken, // Get your access token by following the OAuth 2.0 flow
    );
    
    $data = json_encode($data);
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $base_url . '/users/me/meetings');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    
    curl_close($ch);
    $result = json_decode($response, true);
    
    if ($result && isset($result['id'])) {
        return $result['id'];
    } else {
        return false;
    }    
}

function deleteMeeting($meetingId){
    
    
    $accessToken = getAccessToken();
    $baseUrl = 'https://api.zoom.us/v2';
    $endpoint = '/meetings/' . $meetingId;
    
    $headers = array(
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json",
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    
    deleteData($meetingId);
    
}

function getAccessToken(){
    
    if(empty($_SESSION['accessToken'])){
        $accessToken =  newAccessToken();
    }else {
       $accessToken = $_SESSION['accessToken'];
       if(isAccessTokenExpired($accessToken)){
            $accessToken =  newAccessToken();
        }
    }
    return $accessToken;
}

function newAccessToken(){
    
    $tokenUrl = 'https://zoom.us/oauth/token';
    $data = array(
        'grant_type' => 'account_credentials',
        'account_id'=> ACCOUNT_ID
    );
    
    $headers = array(
        "Authorization: Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET),
        "Host: zoom.us"
    );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($result && isset($result['access_token'])) {
        $accessToken = $result['access_token'];
        return $accessToken;
    } else {
        echo  "Failed to obtain access token <pre>";
        print_r($result);
        echo "</pre>";
        exit;
    }
}

function isAccessTokenExpired($accessToken) {
    $tokenParts = explode('.', $accessToken);
    if (count($tokenParts) !== 3) {
        return true; 
    }
    $payload = base64_decode($tokenParts[1]);
    $tokenData = json_decode($payload, true);
    
    if (isset($tokenData['exp']) && $tokenData['exp'] < time()) {
        return true; 
    }
    
    return false; 
}

function readDate($dateString){
    $dateTime = new DateTime($dateString);
    $humanReadableDate = $dateTime->format('d-M-Y g:i A');
    return $humanReadableDate;
}


function meetingURL($params){
    return BASE_URL . '/host/?' . http_build_query($params);
}

function updateMeeting($meetingId, $data) {
    
    $updateData = $data;
    $updateData['settings'] = json_encode($data['settings']);
    updateData($meetingId, $updateData);
    
    $accessToken = getAccessToken(); // Replace with your actual access token
    
    $base_url = 'https://api.zoom.us/v2';
    $update_meeting_url = $base_url . '/meetings/' . $meetingId;
    
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken,
    );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $update_meeting_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // Use PATCH method for updating
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    return true;
}


function getMeeting($meetingId) {
    $accessToken = getAccessToken();
    
    $base_url = 'https://api.zoom.us/v2';
    $get_meeting_url = $base_url . '/meetings/' . $meetingId;
    
    $headers = array(
        "Authorization: Bearer " . $accessToken,
    );
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $get_meeting_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['id'])) {
        return $result;
    } else {
        echo "Failed to retrieve meeting data: " . print_r($result, true);
    }
}

// dd(getMeetingRecordings(85658910254));

function getMeetingRecordings($meetingId) {
    $base_url = 'https://api.zoom.us/v2';
    $meeting_recordings_url = $base_url . '/meetings/' . $meetingId . '/recordings';
    $accessToken = getAccessToken();
    
    $headers = array(
        'Authorization: Bearer ' . $accessToken
    );
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $meeting_recordings_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    return $result;
}

function getParticipentData($meetingId) {
    
    $accessToken = newAccessToken();
    $base_url = 'https://api.zoom.us/v2';
    $meeting_participants_url = $base_url . '/metrics/meetings/' . $meetingId . '/participants';

    $headers = array(
        'Authorization: Bearer ' . $accessToken
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $meeting_participants_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    return $result;
}


function redirect($url){
    echo "<script>window.location.href='".BASE_URL.'/'.$url."'</script>";
    exit;
}



// database operatoin
function getData($meetingId) {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM tbl_meeting WHERE id = '$meetingId'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($conn);
        return $data;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    exit;
}

function getPateintData($id) {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM tbl_users WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($conn);
        
        return $data;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    exit;
}

function getPateintAllData() {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM tbl_users";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        mysqli_close($conn);
        return $data;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    exit;
}

function sendMail($to, $subject, $message){

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <support@onlineopd.com>' . "\r\n";
	$headers .= 'Cc: S_rma11@hotmail.com' . "\r\n";

	return mail($to,$subject,$message,$headers);
}

function getDoctorData($id) {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM tbl_user WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        mysqli_close($conn);
        return $data;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    exit;
}




function insertData($postData) {
    $conn = conn();
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $fields = array();
    $values = array();
    
    $postData['settings'] = json_encode($postData['settings']);
    
    foreach ($postData as $key => $value) {
        $fields[] = $key;
        $values[] = "'$value'";
    }
    $fieldList = implode(', ', $fields);
    $valueList = implode(', ', $values);
    $sql = "INSERT INTO tbl_meeting ($fieldList) VALUES ($valueList)";
    if (mysqli_query($conn, $sql)) {
        $result =  true;
    } else {
        $result =  "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    return $result;
}

function updateData($meetingId, $postData) {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $assignments = array();
    $postData['settings'] = json_encode($postData['settings']);
    foreach ($postData as $key => $value) {
        $assignments[] = "$key = '$value'";
    }
    
    $assignmentList = implode(', ', $assignments);
    
    $sql = "UPDATE tbl_meeting SET $assignmentList WHERE id = $meetingId";
    
    if (mysqli_query($conn, $sql)) {
        $result =  true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        $result = false;
    }
    mysqli_close($conn);
    return $result;
}


function deleteData($meetingId) {
    $conn = conn();
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = "DELETE FROM tbl_meeting WHERE id = '$meetingId'";
    
    if (mysqli_query($conn, $sql)) {
        $result =  true;
    } else {
        $result =  "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    return $result;
}




function conn(){
    return mysqli_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
}



function dd($data,$dump=false){
    if($dump){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }else {
         echo "<pre>";
        print_r($data);
        echo "</pre>";   
    }
    exit;
}


