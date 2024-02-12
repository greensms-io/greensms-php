<?php
include_once('init.php');
$response = $client->whatsapp->send([
  'to' => '79260000000',
  'txt' => '1234 is your verification code.',
  'from' => 'GREENSMS',
  'tag' => 'test-sdk-node'
]);
printf("WhatsApp Request ID: %s\n", $response->request_id);

$response = $client->whatsapp->status(['id' => $response->request_id]);
printf("WhatsApp Status: %s\n", var_export($response, 1));
