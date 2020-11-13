<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class CallTest extends TestCase {

  private $utility = null;

  public function setUp() {
    $this->utility = new Utility();
  }

  public function testCanSendMessage() {
    $phoneNum = $this->utility->getRandomPhone();
    $params = [
      'to' => $phoneNum,
    ];

    $response = $this->utility->getInstance()->call->send($params);
    $this->assertObjectHasAttribute('request_id', $response);
    $this->assertObjectHasAttribute('code', $response);
  }

  public function testCanFetchStatus() {
    $response = $this->utility->getInstance()->call->status(['id' => '1fd4ac4d-6e4f-4e32-b6e4-8087d3466f55', 'extended' => true ]);
    $this->assertObjectHasAttribute('status', $response);
  }

  public function testRaisesValidationException() {
    try {
      $response = $this->utility->getInstance()->call->send([]);
      $this->fail("Shouldn't send Call without parameters");
    } catch(Exception $e) {
      $this->assertObjectHasAttribute('message', $e);
      $this->assertEquals('Validation Error', $e->getMessage());
    }

  }
}