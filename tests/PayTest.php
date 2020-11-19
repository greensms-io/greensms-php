<?php

use PHPUnit\Framework\TestCase;

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;

final class PayTest extends TestCase
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
          'amount' => 10,
          'tag' => 'PHPTest'
        ];

        $response = $this->utility->getInstance()->pay->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
    }

    public function testCanFetchStatus()
    {
        $response = $this->utility->getInstance()->pay->status(['id' => '60f231d9-16ec-4313-842e-6e6853063482']);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->pay->send([]);
            $this->fail("Shouldn't send Pay without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
