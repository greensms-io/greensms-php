<?php
// include_once('init.php');

require_once("./vendor/autoload.php");

use GreenSms\GreenSms;

$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoidGVzdCIsImlhdCI6MTYwNTc5NjEwOCwiaXNzIjoiYXBpLmdyZWVuc21zLnJ1In0.St8-5fJqQnHx1MFybJ5o4D5VZ-RK3HxcL0DScJsOYec';
$tokenClient = new GreenSms([
  'token' => $token
]);

$response = $tokenClient->account->balance();
echo "Balance : " . $response->balance. "\n";
