<?php



use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;
use GreenSMS\Tests\TestCase;

final class HlrTest extends TestCase
{
    private $utility = null;

    public function setUp(): void
    {
        $this->utility = new Utility();
    }

    public function testCanSendMessage()
    {
        $phoneNum = $this->utility->getRandomPhone(79150000000, 79150999999);
        $params = [
          'to' => $phoneNum,
        ];

        $response = $this->utility->getInstance()->hlr->send($params);
        $this->assertObjectHasAttribute('request_id', $response);
        return [
            'id' => $response->request_id,
            'to' => $phoneNum,
        ];
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($params)
    {
        sleep(2);
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
