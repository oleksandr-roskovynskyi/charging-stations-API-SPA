<?php

namespace Tests\Unit\Service;

use App\Service\JsonResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\JsonResponse
 */
class JsonResponseTest extends TestCase
{
    /**
     * @group start
     */
    public function testResponseInt()
    {
        $response = new JsonResponse(11);

        self::assertEquals('11', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testResponseWithCode()
    {
        $response = new JsonResponse(11, 203);

        self::assertEquals('11', $response->getContent());
        self::assertEquals(203, $response->getStatusCode());
    }

    public function testResponseNull()
    {
        $response = new JsonResponse(null);

        self::assertEquals('null', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testResponseString()
    {
        $response = new JsonResponse('11');

        self::assertEquals('"11"', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testResponseObject()
    {
        $object = new \stdClass();
        $object->string = '11';
        $object->integer = 11;
        $object->none = null;

        $response = new JsonResponse($object);

        self::assertEquals('{"string":"11","integer":11,"none":null}', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }
}
