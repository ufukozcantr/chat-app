<?php

// environmental variable must be set
$app_secret = 'a2b15f23df0d08f82a8f';

$app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
$webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];

$body = file_get_contents('php://input');

$expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

if($webhook_signature == $expected_signature) {
    // decode as associative array
    $payload = json_decode( $body, true );
    foreach($payload['events'] as &$event) {
        $data[] = &$event;
        echo $event;
    }
    dd($data);
    header("Status: 200 OK");
}
else {
    header("Status: 401 Not authenticated");
}
