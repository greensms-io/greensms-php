<?php
include_once('init.php');

$response = $client->call->send([
  'to' => '79260000111'
]);

echo "Call Send Request Id: " . $response->request_id;

$response = $client->call->receive([
  'to' => '79260000111',
  'toll_free' => 'true',
  'tag' => 'aaeb96d6-cb0e-46f2-8d09-2cd5c9ea4211',
]);

echo "Call Receive Request Id: " . $response->request_id;


$response = $client->call->status([
  'id' => '1fd4ac4d-6e4f-4e32-b6e4-8087d3466f55'
]);

echo "Call Status: \n";
print_r($response);
