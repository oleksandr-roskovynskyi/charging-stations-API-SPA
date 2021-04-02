<?php

namespace Tests\Feature\Example;

use Tests\TestCase;

/**
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @group example
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
