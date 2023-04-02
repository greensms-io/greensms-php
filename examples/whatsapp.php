<?php
include_once('init.php');

$response = $client->whatsapp->send([
  'to' => '79150000000',
  'txt' => 'GreenSMS Node SDK',
  'from' => '79150000000',
  'tag' => 'test-sdk-node'
]);

echo "Whatsapp Request Id: " . $response->request_id;
echo "\n\n";

$response = $client->whatsapp->status([
  'id' => '79442f1f-17a8-42bb-9f6f-4affc8788e7e',
]);

echo "Whatsapp Status: \n";
print_r($response);

$response = $client->whatsapp->webhook([
  'url' => 'http://test.url',
]);

echo "Whatsapp Webhook: \n";
print_r($response);
