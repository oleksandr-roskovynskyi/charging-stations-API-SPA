<?php

namespace Tests\Unit\Service;

use App\Service\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testResponse()
    {
        $response = new JsonResponse(11);

        self::assertEquals('11', $response->getContent());
        self::assertEquals(200, $response->getStatusCode());
    }
}
