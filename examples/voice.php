<?php
include_once('init.php');

$response = $client->voice->send([
  'to' => '79260000121',
  'txt' => '1221'
]);

echo "Voice Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->voice->status([
  'id' => '41f23094-deda-4cab-ac9c-3ab4f2fee9e6',
]);

echo "Voice Status: \n";
print_r($response);
