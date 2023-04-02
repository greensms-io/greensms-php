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
        $phoneNum = $this->utility->getRandomPhone(79262716632, 79154286567);
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
