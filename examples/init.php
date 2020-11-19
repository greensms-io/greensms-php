<?php

require_once("./vendor/autoload.php");

use GreenSMS\GreenSMS;

$client = new GreenSMS([
  'user' => 'test',
  'pass' => 'test'
]);
