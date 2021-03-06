<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ChargingStationsRepository;
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
    private $chargingStationsRepository;

    public function __construct(ChargingStationsRepository $chargingStationsRepository)
    {
        $this->chargingStationsRepository = $chargingStationsRepository;
    }
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $chargingStations = ChargingStation::all();

        return $this->success($chargingStations);
    }

    /**
     * @param ChargingStation $chargingStation
     * @return JsonResponse
     */
    public function show(ChargingStation $chargingStation): JsonResponse
    {
        $chargingStation = ChargingStation::findOrFail($chargingStation->getKey());

        return $this->success($chargingStation);
    }

    /**
     * @param ChargingStationRequest $request
     * @return JsonResponse
     */
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

    /**
     * @param UpdateChargingStationRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateChargingStationRequest $request, $id): JsonResponse
    {
        $chargingStation = ChargingStation::findOrFail($id);
        $chargingStation->update($request->all());

        return $this->success($chargingStation->toArray());
    }

    /**
     * @param ChargingStation $chargingStation
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(ChargingStation $chargingStation): JsonResponse
    {
        $chargingStation->delete();

        return $this->success('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Returns JsonResponse the charging stations of city
     *
     * @param CityChargingStationsRequest $request
     * @return JsonResponse
     */
    public function getChargingStationsOfCity(CityChargingStationsRequest $request): JsonResponse
    {
        $city = $request->get('city');

        $result = ChargingStation::where('city', $city)->get();

        return $this->success($result);
    }

    /**
     * Returns JsonResponse the charging stations of city, which is now open
     *
     * @param CityChargingStationsRequest $request
     * @return JsonResponse
     */
    public function getOpeningOfCity(CityChargingStationsRequest $request): JsonResponse
    {
        $city = $request->get('city');

        return $this->success(
            $this->chargingStationsRepository->getOpeningOfCity($city)
        );
    }

    /**
     * Returns JsonResponse the nearest charging stations, which is now open,
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
