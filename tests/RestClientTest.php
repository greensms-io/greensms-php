<?php

namespace GreenSMS\Tests;

use GreenSMS\Http\RestClient;
use GreenSMS\Http\RestException;

final class RestClientTest extends TestCase
{
    public function testFail400()
    {
        $client = new RestClient([]);
        $response = $client->request([
            'url'=>'https://gethttpstatus.com/400',
            'method' => 'get',
        ]);

        $this->assertInstanceOf(RestException::class, $response);
        $this->assertEquals('Bad Request', $response->getMessage());
        $this->assertEquals(400, $response->getCode());
    }

    public function testFail500()
    {
        $client = new RestClient([]);
        $response = $client->request([
            'url'=>'https://gethttpstatus.com/500',
            'method' => 'get',
        ]);

        $this->assertInstanceOf(RestException::class, $response);
        $this->assertEquals('Internal Server Error', $response->getMessage());
        $this->assertEquals(500, $response->getCode());
    }

    public function testNativeRequestTimeout ()
    {
        $client = new RestClient([]);
        $response = $client->request([
            'url'=>'https://some.some',
            'method' => 'get',
            'CURLOPT_TIMEOUT_MS' => 2,
        ]);

        $this->assertInstanceOf(RestException::class, $response);
        $this->assertMatchesRegularExpression(
            '/Resolving timed out after (\d){1,3} milliseconds/',
            $response->getMessage()
        );
        $this->assertEquals(0, $response->getCode());
    }
}
