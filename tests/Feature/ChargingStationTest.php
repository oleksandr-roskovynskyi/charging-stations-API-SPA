<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChargingStationTest extends TestCase
{
    use DatabaseTransactions;

    const ENDPOINT = '/api/v1/charging-stations';

    public function testsChargingStationAreCreatedCorrectly()
    {
        $payload = [
            'name' => 'ChargingStationTest',
            'city' => 'Kyiv',
            'open_from' => '09:00:00',
            'open_to' => '19:00:00',
            'latitude' => 47.45351100,
            'longitude' => 24.30276800,
        ];

        $headers = ['Content-Type' => "application/json"];

        $this->json('POST', self::ENDPOINT, $payload, $headers)
            ->assertStatus(201)
            ->assertJson(['id' => 1, 'name' => 'ChargingStationTest', 'city' => 'Kyiv',
                'open_from' => '09:00:00', 'open_to' => '19:00:00',
                'latitude' => 47.45351100, 'longitude' => 24.30276800]);
    }


}
