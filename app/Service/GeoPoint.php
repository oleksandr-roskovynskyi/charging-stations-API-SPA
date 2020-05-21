<?php

namespace App\Service;

use App\Models\ChargingStation;
use Carbon\Carbon;

final class GeoPoint
{
    public const RADIUS_OF_EARTH_KM = 6367;
    public const RADIUS_OF_EARTH_METERS = 6367000;
    public const RADIUS_OF_EARTH_ML = 3956;

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getClosest($unitOfDistance = 'km'): array
    {
        $timeNow = Carbon::now()->format('H:i');

        switch ($unitOfDistance) {
            case 'ml':
                $radiusOfEarth = self::RADIUS_OF_EARTH_ML;
                break;
            case 'm':
                $radiusOfEarth = self::RADIUS_OF_EARTH_METERS;
                break;
            default:
                $radiusOfEarth = self::RADIUS_OF_EARTH_KM;
        }

        return ChargingStation::selectRaw('*,
            ( ' .$radiusOfEarth. ' * acos(
                cos( radians('.$this->getLatitude().') ) * cos( radians( latitude ) )
                * cos( radians( longitude ) - radians('.$this->getLongitude().') )
                + sin( radians('.$this->getLatitude().') )
                * sin( radians( latitude ) )
            )) AS distance')
            ->where(function ($query) use ($timeNow) {
                $query->whereTime('open_from', '<', $timeNow)
                    ->whereTime('open_to', '>', $timeNow);
            })
            ->orWhere(function ($query) use ($timeNow) {
                $query->whereTime('open_from', '<', $timeNow)
                    ->whereTime('open_to', '<', $timeNow)
                    ->whereRaw('open_to::time < open_from::time');
            })
            ->orderByRaw('distance')
            ->limit(30)
            ->get()
            ->toArray();
    }
}
