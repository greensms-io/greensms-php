<?php
include_once('init.php');

$response = $client->call->send([
  'to' => '79260000111'
]);

echo "Call Request Id: " . $response->request_id;

$response = $client->call->status([
  'id' => '1fd4ac4d-6e4f-4e32-b6e4-8087d3466f55'
]);

echo "Call Status: \n";
print_r($response);