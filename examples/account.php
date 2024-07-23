<?php
include_once('init.php');

$response = $client->account->balance();
echo "Balance : " . $response->balance. "\n";

$response = $client->account->token(['token' => 100]);
echo "Auth Token: " . $response->access_token;

$response = $client->account->tariff();
echo "Tariff Response: ";
print_r($response);

$response = $client->account->blacklist->add(['to' => '70000000000', 'module' => 'ALL', 'comment' => 'test']);
echo "Blacklist Add Response: ";
print_r($response);
$response = $client->account->blacklist->get();
echo "Blacklist Get Response: ";
print_r($response);
$response = $client->account->blacklist->delete(['to' => '70000000000']);
echo "Blacklist Delete Response: ";
print_r($response);

$response = $client->account->limits->set(['type' => 'IP', 'module' => 'ALL', 'value' => '1.1.1.1,8.8.8.8', 'comment' => 'test']);
echo "Limits Set Response: ";
print_r($response);
$response = $client->account->limits->get();
echo "Limits Get Response: ";
print_r($response);
$response = $client->account->limits->delete(['type' => 'IP', 'value' => '8.8.8.8,1.1.1.1']);
echo "Limits Delete Response: ";
print_r($response);