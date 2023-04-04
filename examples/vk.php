<?php
include_once('init.php');

$response = $client->vk->send([
  'to' => '79260000121',
  'txt' => '1221',
  'from' => 'GreenSMS',
  'cascade' => 'sms'
]);

echo "VK Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->vk->status([
  'id' => 'caf3efb1-8aca-4387-9ed0-e667d315c5c9',
]);

echo "VK Status: \n";
print_r($response);
