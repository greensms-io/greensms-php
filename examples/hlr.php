<?php
include_once('init.php');

$response = $client->hlr->send([
  'to' => '79150000000',
  'txt' => '1221'
]);

echo "Hlr Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->hlr->status([
  'id' => '70d296f5-ac52-403d-a27b-24829c2faebc',
  'to' => '79150000000'
]);

echo "Hlr Status: \n";
print_r($response);
