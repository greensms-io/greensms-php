<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class CallTest extends TestCase
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
        ];

        $response = $this->utility->getInstance()->call->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        $this->assertObjectHasAttribute('code', $response);
        $requestId = $response->request_id;
        return $requestId;
    }

    public function testCanReceive()
    {
        $phoneNum = $this->utility->getRandomPhone();
        $params = [
            'to' => $phoneNum,
            'toll_free' => 'true'
        ];

        $response = $this->utility->getInstance()->call->receive($params);
        $this->assertObjectHasAttribute('request_id', $response);
        $this->assertObjectHasAttribute('number', $response);
        return $requestId;
    }


    /**
     * @depends testCanSendMessage
     * */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->call->status(['id' => $requestId, 'extended' => true ]);
        $this->assertObjectHasAttribute('status', $response);
    }

    public function testRaisesValidationException()
    {
        try {
            $response = $this->utility->getInstance()->call->send([]);
            $this->fail("Shouldn't send Call without parameters");
        } catch (Exception $e) {
            $this->assertObjectHasAttribute('message', $e);
            $this->assertEquals('Validation Error', $e->getMessage());
        }
    }
}
