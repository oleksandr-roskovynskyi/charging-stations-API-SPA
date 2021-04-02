<?php

namespace Tests\Feature\ChargingStation;

use App\Models\ChargingStation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\ChargingStationController
 */
class ChargingStationTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions; //)))

    public const ENDPOINT = '/api/v1/charging-stations';

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute(self::ENDPOINT);
        $this->setBaseModel(ChargingStation::class);
    }

    /**
     * @group start
     * @group example
     */
    public function testCanCreateChargingStation(): void
    {
        $this->create()
            ->assertJsonFragment(["status" => "Success"]);
    }

    public function testCanNotCreateNotValidateChargingStation(): void
    {
        $this->post(self::ENDPOINT, [])
            ->assertStatus(422)
            ->assertJsonCount(6,'errors');
    }

    public function testCanGetChargingStations(): void
    {
        $chargingStations = factory(ChargingStation::class, 2)->create();

        $response = $this->get(self::ENDPOINT);

        $response->assertSee(str_replace('"', '', json_encode($chargingStations[0]->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStations[1]->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStations[1]->city)))
            ->assertSee(Carbon::parse($chargingStations[1]->open_from)->format('H:i'))
            ->assertSee(Carbon::parse($chargingStations[1]->open_to)->format('H:i'))
            ->assertSee($chargingStations[1]->latitude)
            ->assertSee($chargingStations[1]->longitude)
            ->assertStatus(200);
    }

    public function testCanShowChargingStation(): void
    {
        $chargingStation = factory(ChargingStation::class)->create();

        $response = $this->get(self::ENDPOINT. '/' . $chargingStation->id);

        $response->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStation->city)))
            ->assertSee(Carbon::parse($chargingStation->open_from)->format('H:i'))
            ->assertSee(Carbon::parse($chargingStation->open_to)->format('H:i'))
            ->assertSee($chargingStation->latitude)
            ->assertSee($chargingStation->longitude)
            ->assertStatus(200);
    }

    public function testCanUpdateChargingStation(): void
    {
        $chargingStation = factory(ChargingStation::class)->create();

        $chargingStation->name = 'Updated Name';

        $response = $this->put(self::ENDPOINT . '/' . $chargingStation->id, $chargingStation->toArray());

        $this->assertDatabaseHas($chargingStation->getTable(), ['id'=> $chargingStation->id , 'name' => 'Updated Name']);

        $response->assertJsonFragment(["status" => "Success"])
            ->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertStatus(200);
    }

    public function testCanUpdateWithMyUniqueNameChargingStation(): void
    {
        $chargingStation = factory(ChargingStation::class)->create([
            'name' => 'Updated Name',
            'city' => 'Київ'
        ]);

        $chargingStation->name = 'Updated Name';
        $chargingStation->city = 'Бориспіль';

        $response = $this->put(self::ENDPOINT . '/' . $chargingStation->id, $chargingStation->toArray());

        $this->assertDatabaseHas($chargingStation->getTable(), ['id'=> $chargingStation->id , 'city' => 'Бориспіль']);

        $response->assertJsonFragment(["status" => "Success"])
            ->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStation->city)))
            ->assertStatus(200);
    }

    public function testCanNotUpdateWithNotUniqueNameChargingStation(): void
    {
        $chargingStationInDB = factory(ChargingStation::class)->create([
            'name' => 'Unique Name',
        ]);

        $chargingStationInDB2 = factory(ChargingStation::class)->create();

        $chargingStation = factory(ChargingStation::class)->make([
            'name' => 'Unique Name',
        ]);

        $response = $this->put(self::ENDPOINT . '/' . $chargingStationInDB2->id, $chargingStation->toArray());

        $this->assertDatabaseHas($chargingStationInDB->getTable(), ['id'=> $chargingStationInDB->id , 'name' => 'Unique Name']);

        $response->assertJsonFragment(['name' => ['The name has already been taken.']])
            ->assertStatus(422);
    }

    public function testCanDeleteChargingStation(): void
    {
        $this->destroy()
            ->assertStatus(204);
    }

    public function testCanGetChargingStationsOfCity(): void
    {
        $chargingStation = factory(ChargingStation::class)->create();

        $this->post(self::ENDPOINT . '/city', [
                'city' => $chargingStation->city
            ])
            ->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStation->city)))
            ->assertStatus(200);
    }

    public function testCanGetOpeningChargingStationsOfCity(): void
    {
        $chargingStation = factory(ChargingStation::class)->create([
            'open_from' => Carbon::now()->subHours(4),
            'open_to' => Carbon::now()->addHours(4),
        ]);

        $closeChargingStation = factory(ChargingStation::class)->create([
            'open_from' => Carbon::now()->addHours(1),
            'open_to' => Carbon::now()->addHours(22),
        ]);

        $this->post(self::ENDPOINT . '/now-open', [
                'city' => $chargingStation->city
            ])
            ->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertDontSee(str_replace('"', '', json_encode($closeChargingStation->name)))
            ->assertStatus(200);
    }

    public function testCanGetClosestNowOpenChargingStations(): void
    {
        $chargingStation = factory(ChargingStation::class)->create([
            'open_from' => Carbon::now()->subHours(4),
            'open_to' => Carbon::now()->addHours(4),
            'latitude' => 47.68977300,
            'longitude' => 33.62800100
        ]);

        $chargingStation2 = factory(ChargingStation::class)->create([
            'open_from' => Carbon::now()->subHours(4),
            'open_to' => Carbon::now()->addHours(4),
            'latitude' => 49.68977300,
            'longitude' => 23.62800100
        ]);

        $closeChargingStation = factory(ChargingStation::class)->create([
            'open_from' => Carbon::now()->addHours(1),
            'open_to' => Carbon::now()->addHours(22),
        ]);

        $this->post(self::ENDPOINT . '/closest-now-open', [
            'latitude' => $chargingStation->latitude,
            'longitude' => $chargingStation->longitude
        ])
            ->assertSee(str_replace('"', '', json_encode($chargingStation->name)))
            ->assertSee(str_replace('"', '', json_encode($chargingStation->city)))
            ->assertSee(str_replace('"', '', json_encode($chargingStation2->name)))
            ->assertDontSee(str_replace('"', '', json_encode($closeChargingStation->name)))
            ->assertSee($chargingStation->latitude)
            ->assertSee($chargingStation->longitude)
            ->assertJsonFragment(["distance" => "0"])
            ->assertJsonFragment(["distance" => "765.8274611682411"])
            ->assertStatus(200);
    }
}
