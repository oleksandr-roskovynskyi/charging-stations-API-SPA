<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ChargingStationsRepository
{
    public function getOpeningOfCity(string $city): Collection;
}
