<?php


use GreenSMS\Http\RestException;
use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class VkTest extends TestCase
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
            'from' => 'GreenSMS',
            'cascade' => 'voice,viber,sms',
        ];

        $response = $this->utility->getInstance()->vk->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->vk->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->vk->send([]);
            $this->fail("Shouldn't send Vk without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
    
    public function testCommaSeparatedInStrictValidationFailure()
    {
        $this->expectException(RestException::class);

        $this->utility->getInstance()->vk->send([
            'to' => $this->utility->getRandomPhone(),
            'txt' => 'Test Message',
            'from' => 'GreenSMS',
            'cascade' => 'voice,viber,invalid',
        ]);
    }
}
