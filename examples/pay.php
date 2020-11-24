<?php
include_once('init.php');

$response = $client->pay->send([
  'to' => '79260000121',
  'amount' => 10
]);

echo "Pay Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->pay->status([
  'id' => '60f231d9-16ec-4313-842e-6e6853063482',
]);

echo "Pay Status: \n";
print_r($response);
