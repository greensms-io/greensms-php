<?php

namespace GreenSms\Tests;

use GreenSms\GreenSms;

class Utility {
  public function getInstance() {
    $client = new GreenSms([
      'user' => 'test',
      'pass' => 'test'
    ]);

    return $client;
  }
}
