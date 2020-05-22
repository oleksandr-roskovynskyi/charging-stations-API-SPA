<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::apiResource('/charging-stations', 'ChargingStationController');

    Route::post('charging-stations/city', 'ChargingStationController@getChargingStationsOfCity');

    Route::post('charging-stations/now-open', 'ChargingStationController@getOpeningOfCity');

    Route::post('charging-stations/closest-now-open', 'ChargingStationController@getClosestNowOpen');
});
