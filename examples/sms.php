<?php
include_once('init.php');

$response = $client->sms->send([
  'to' => '79260000121',
  'txt' => 'Here is your message for delivery'
]);

echo "Sms Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->sms->status([
  'id' => 'dc2bac6d-f375-4e19-9a02-ef0148991635',
]);

echo "Sms Status: \n";
print_r($response);