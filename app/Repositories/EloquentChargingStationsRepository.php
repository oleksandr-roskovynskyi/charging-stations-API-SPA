<?php

namespace App\Repositories;

use App\Models\ChargingStation;
use App\Repositories\Contracts\ChargingStationsRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final class EloquentChargingStationsRepository implements ChargingStationsRepository
{
    public function getOpeningOfCity(string $city): Collection
    {
        $timeNow = Carbon::now()->format('H:i');

        return ChargingStation::query()
            ->where(function ($query) use ($timeNow, $city) {
                $query->whereTime('open_from', '<', $timeNow)
                    ->whereTime('open_to', '>', $timeNow)
                    ->where('city', $city);
            })
            ->orWhere(function ($query) use ($timeNow, $city) {
                $query->whereTime('open_from', '<', $timeNow)
                    ->whereTime('open_to', '<', $timeNow)
                    ->whereRaw('open_to::time < open_from::time')
                    ->where('city', $city);
            })
            ->get();
    }
}
