<?php
namespace GreenSMS\Tests;

use PHPUnit\Framework\TestCase;

use GreenSMS\Tests\Utility;
use GreenSMS\GreenSMS;

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
        return $response->request_id;
    }

    /**
     * @depends testCanSendMessage
     */
    public function testCanFetchStatus($requestId)
    {
        sleep(2);
        $response = $this->utility->getInstance()->viber->status(['id' => $requestId, 'extended' => true ]);
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
