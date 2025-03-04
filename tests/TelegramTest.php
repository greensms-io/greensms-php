<?php

use GreenSMS\Http\RestException;
use GreenSMS\Tests\Utility;
use GreenSMS\Tests\TestCase;

final class TelegramTest extends TestCase
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
            'txt' => '1127',
        ];

        $response = $this->utility->getInstance()->telegram->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        try {
            $this->utility->getInstance()->telegram->status(['id' => $requestId, 'extended' => true ]);
        } catch (RestException $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Message not found', $e->getMessage());
        }
    }

    public function testRaisesValidationException()
    {
        try {
            $this->utility->getInstance()->telegram->send([]);
            $this->fail("Shouldn't send Telegram without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
