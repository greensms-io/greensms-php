<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class ViberTest extends TestCase
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
          'cascade' => 'sms'
        ];

        $response = $this->utility->getInstance()->viber->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
    }

    public function testCanFetchStatus()
    {
        $response = $this->utility->getInstance()->viber->status(['id' => '0b18fab4-0c5d-4a8b-8ee4-057a59596c7d']);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->viber->send([]);
            $this->fail("Shouldn't send Viber without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
