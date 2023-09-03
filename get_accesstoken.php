<?php

//prod server to server oauth
$accountId = "6xqfqpRqSeGeL88FHhrBbg";
$clientId = 'hNNAoPRzRf6LLaW3l6_32g';
$clientSecret = 'LC5WaPW40J55pVBcvQWUjt9QfUMaNMwc';


$redirectUri = 'https://www.shipmiles.com/test/meeting/callback.php';
$tokenUrl = 'https://zoom.us/oauth/token';

$data = array(
    'grant_type' => 'account_credentials',
    'account_id'=> $accountId
);

$headers = array(
    "Authorization: Basic " . base64_encode($clientId . ':' . $clientSecret),
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

echo "<pre>";
print_r($result);
echo "</pre>";

if ($result && isset($result['access_token'])) {
    $accessToken = $result['access_token'];
    echo '<a href="meeting.php?token='.$accessToken.'"> create meeting </a>';
    exit;
} else {
    echo "Failed to obtain access token ".print_r($result);
}

?>

