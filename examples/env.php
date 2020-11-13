<?php

require_once("./vendor/autoload.php");

use GreenSms\GreenSms;

putenv('GREENSMS_USER=test');
putenv('GREENSMS_PASS=test');

$tokenClient = new GreenSms();

$response = $tokenClient->account->balance();
echo "Balance : " . $response->balance. "\n";

// Unsetting the env variable after use
putenv('GREENSMS_USER');
putenv('GREENSMS_PASS');