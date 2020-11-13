<?php
// include_once('init.php');

require_once("./vendor/autoload.php");

use GreenSms\GreenSms;

$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoidGVzdCIsImlhdCI6MTYwNTI1MTE3NiwiaXNzIjoiYXBpLmdyZWVuc21zLnJ1In0.MXQS2km7L4DloOGCtgEHuRrFgNjJ-LcZ6rXJi6_9GK4';
$tokenClient = new GreenSms([
  'token' => $token
]);

$response = $tokenClient->account->balance();
echo "Balance : " . $response->balance. "\n";
