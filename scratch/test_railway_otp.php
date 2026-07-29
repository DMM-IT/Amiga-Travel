<?php
$url = 'https://amiga-travel.up.railway.app/api/register/request-otp';
$data = [
    'name' => 'System Test',
    'email' => 'macaraigdrew99@gmail.com', // use their email to see if it sends
    'password' => 'password123'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$status_line = $http_response_header[0];

echo "HTTP Status: " . $status_line . "\n";
echo "Response Body:\n" . $result . "\n";
