<?php

use PHPUnit\Framework\TestCase;

use GreenSms\Tests\Utility;
use GreenSms\GreenSms;

final class SocialTest extends TestCase
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

        $response = $this->utility->getInstance()->social->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
    }

    public function testCanFetchStatus()
    {
        $response = $this->utility->getInstance()->social->status(['id' => 'caf3efb1-8aca-4387-9ed0-e667d315c5c9']);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->social->send([]);
            $this->fail("Shouldn't send Social without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
