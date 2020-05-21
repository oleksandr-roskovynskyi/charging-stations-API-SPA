<?php

namespace App\Http\Controllers;

use \Illuminate\Http\JsonResponse;
use App\Http\Requests\ChargingStationRequest;
use App\Http\Requests\CityChargingStationsRequest;
use App\Http\Requests\ClosestChargingStationsRequest;
use App\Http\Requests\UpdateChargingStationRequest;
use App\Models\ChargingStation;
use App\Service\GeoPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChargingStationController
 * @package App\Http\Controllers
 */
class ChargingStationController extends ApiController
{
    public function index(): JsonResponse
    {
        $chargingStations = ChargingStation::all();

        return $this->success($chargingStations);
    }

    public function show(ChargingStation $chargingStation): JsonResponse
    {
        $chargingStation = ChargingStation::findOrFail($chargingStation->getKey());

        return $this->success($chargingStation);
    }

    public function store(ChargingStationRequest $request): JsonResponse
    {
        try {
            $chargingStation = ChargingStation::create($request->all());

            $result['data'] = $chargingStation->toArray();
            $result['status'] = true;

        } catch (\Exception $e) {
            $result['status'] = false;
            Log::error($e->getMessage());

            $result['message'] = 'The operation store failed! Something went wrong, the report was sent to technical support!';
        }

        if ($result['status']) {
            return $this->success($result['data'], Response::HTTP_CREATED);
        }

        return $this->fail($result['message']);
    }

    public function update(UpdateChargingStationRequest $request, ChargingStation $chargingStation): JsonResponse
    {
        $chargingStation->update($request->all());

        return $this->success($chargingStation->toArray());
    }

    public function destroy(ChargingStation $chargingStation): JsonResponse
    {
        $chargingStation->delete();

        return $this->success('Deleted!', Response::HTTP_NO_CONTENT);
    }

    public function getChargingStationsOfCity(CityChargingStationsRequest $request): JsonResponse
    {
        $city = $request->get('city');

        $result = ChargingStation::where('city', $city)->get();

        return $this->success($result);
    }

    public function getOpeningOfCity(CityChargingStationsRequest $request): JsonResponse
    {
        $city = $request->get('city');
        $timeNow = Carbon::now()->format('H:i');

        $result =  ChargingStation::where(function ($query) use ($timeNow, $city) {
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

        return $this->success($result);
    }

    /**
     * Returns JsonResponse the nearest charging station, which is now open,
     * by location coordinates (latitude, longitude)
     *
     * To find the exact GPS latitude and longitude coordinates of a point on a map
     * https://www.maps.ie/coordinates.html
     *
     * @param ClosestChargingStationsRequest $request <ul>
     * <li>float <var>latitude</var> Latitude is a geographic coordinate of the user.</li>
     * <li>float <var>longitude</var> Longitude is a geographic coordinate of the user.</li>
     * @return JsonResponse
     */
    public function getClosestNowOpen(ClosestChargingStationsRequest $request): JsonResponse
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        return $this->success((new GeoPoint($latitude, $longitude))->getClosestNowOpenWithUnitOfDistance());
    }
}
