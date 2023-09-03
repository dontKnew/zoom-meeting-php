<?php

require_once("helper.php");
// Replace these values with your actual Zoom Meeting SDK credentials
$zoomMeetingSdkSecret = SDK_CLIENT_ID;
$appKey = SDK_CLIENT_SECRET;
$meetingNumber = 86070815481;
$role = 1; // 0 for host, 1 for participant


// Define the JWT header and payload
$header = json_encode([
    'alg' => 'HS256',
    'typ' => 'JWT',
]);

$payload = json_encode([
    'appKey' => $appKey,
    'sdkKey' => $appKey,
    'mn' => $meetingNumber,
    'role' => $role,
    'iat' => time(),
    'exp' => time() + 3600, // Token expiration time (1 hour from the current time)
    'tokenExp' => time() + 3600, // Token expiration time (1 hour from the current time)
]);

// Base64 encode the header and payload
$base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Concatenate the encoded header and payload with a period '.'
$dataToSign = $base64Header . '.' . $base64Payload;

// Generate the HMAC SHA256 signature
$signature = hash_hmac('sha256', $dataToSign, $zoomMeetingSdkSecret, true);
$base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create the final JWT token
$jwtToken = $base64Header . '.' . $base64Payload . '.' . $base64Signature;


$link = "https://www.shipmiles.com/test/meeting/host/meeting.php?name=c2FqaWQ%3D&mn=81937481908&email=&pwd=123&role=0&lang=en-US&signature=".$jwtToken."&china=0&sdkKey=1iVtUWRbSt6kDcKuXYpA";
echo  $link;