<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class SmsTest extends TestCase
{
    private $utility = null;

    public function setUp()
    {
        $this->utility = new Utility();
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
          'to' => $phoneNum,
          'txt' => 'Text Message Hampshire',
          'from' => 'PHPTest',
          'tag' => 'PHPTest',
          'hidden' => 'Hampshire'
        ];

        $response = $this->utility->getInstance()->sms->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
    }

    public function testCanFetchStatus()
    {
        $response = $this->utility->getInstance()->sms->status(['id' => 'dc2bac6d-f375-4e19-9a02-ef0148991635']);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->sms->send([]);
            $this->fail("Shouldn't send SMS without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
