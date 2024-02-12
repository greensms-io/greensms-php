<?php

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class WhatsappTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
          'to' => $phoneNum,
          'txt' => '1234 is your verification code.',
          'from' => 'GREENSMS',
          'tag' => 'test-sdk-node'
        ];

        $response = $this->utility->getInstance()->whatsapp->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    /*
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->whatsapp->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }
    */
    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->whatsapp->send([]);
            $this->fail("Shouldn't send Whatsapp without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
