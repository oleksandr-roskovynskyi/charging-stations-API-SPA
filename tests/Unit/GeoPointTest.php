<?php declare(strict_types=1);

namespace Tests\Unit;

use App\Models\ChargingStation;
use App\Service\GeoPoint;
use PHPUnit\Framework\TestCase;

class GeoPointTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGeoPointSuccessful()
    {
        $coordination = $this->getChargingStation();

        $geoPoint = $this->getMockBuilder(GeoPoint::class)
            ->setConstructorArgs([$coordination->latitude, $coordination->longitude])
            ->getMock();

        self::assertEquals(0.0, $geoPoint->getLatitude());
        self::assertEquals(0.0, $geoPoint->getLongitude());

        $geoPoint->method('getClosestNowOpenWithUnitOfDistance')
            ->willReturn([]);
    }

    private function getChargingStation(): ChargingStation
    {
        $chargingStation = new ChargingStation();
        $chargingStation->latitude = 46.66792000;
        $chargingStation->longitude = 38.81524700;

        return  $chargingStation;
    }
}
