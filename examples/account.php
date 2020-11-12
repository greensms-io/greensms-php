<?php

require_once("./vendor/autoload.php");

use GreenSms\GreenSms;

$client = new GreenSms([
  'user' => 'test',
  'pass' => 'test'
]);

$response = $client->account->balance();
echo "Balance : " . $response->balance. "\n";