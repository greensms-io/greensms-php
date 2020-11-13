<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class VoiceTest extends TestCase {

  private $utility = null;

  public function setUp() {
    $this->utility = new Utility();
  }

  public function testCanSendMessage() {
    $phoneNum = $this->utility->getRandomPhone();
    $params = [
      'to' => $phoneNum,
      'txt' => '1127',
      'lang' => 'en'
    ];

    $response = $this->utility->getInstance()->voice->send($params);
    $this->assertObjectHasAttribute('request_id', $response);
  }

  public function testCanFetchStatus() {
    $response = $this->utility->getInstance()->voice->status(['id' => '41f23094-deda-4cab-ac9c-3ab4f2fee9e6']);
    $this->assertObjectHasAttribute('status', $response);
  }

  public function testRaisesValidationException() {
    try {
      $response = $this->utility->getInstance()->voice->send([]);
      $this->fail("Shouldn't send Voice without parameters");
    } catch(Exception $e) {
      $this->assertObjectHasAttribute('message', $e);
      $this->assertEquals('Validation Error', $e->getMessage());
    }

  }
}