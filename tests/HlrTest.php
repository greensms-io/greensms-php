<?php

use PHPUnit\Framework\TestCase;

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;

final class HlrTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone(79260000111, 79260999999);
        $params = [
          'to' => $phoneNum,
        ];

        $response = $this->utility->getInstance()->hlr->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $params = [
          'id' => $requestId,
          'to' => $this->utility->getRandomPhone()
        ];
        $response = $this->utility->getInstance()->hlr->status($params);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->hlr->send([]);
            $this->fail("Shouldn't send Hlr without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
