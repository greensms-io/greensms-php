<?php
include_once('init.php');

$response = $client->social->send([
  'to' => '79260000121',
  'txt' => 'Here is your message for delivery',
  'from' => 'Test'
]);

echo "Social Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->social->status([
  'id' => 'caf3efb1-8aca-4387-9ed0-e667d315c5c9',
]);

echo "Social Status: \n";
print_r($response);
