<?php
include_once('init.php');

$response = $client->viber->send([
  'to' => '79260000121',
  'txt' => 'Here is your message for delivery'
]);

echo "Viber Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->viber->status([
  'id' => '0b18fab4-0c5d-4a8b-8ee4-057a59596c7d',
]);

echo "Viber Status: \n";
print_r($response);
