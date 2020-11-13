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

  public function getRandomPhone($min = 70000000111, $max = 70009999999) {
    $phoneNum = $this->getRandomNumber($min, $max);
    return $phoneNum;
  }

  public function getRandomNumber($min, $max) {
    return strval(rand($min, $max));
  }
}
