<?php

namespace App\Http\Controllers;

use App\Airline;
use App\FlightRoutes;
use App\Transformers\AirlineTransformer;

class AirlinesForFlightRouteController extends ApiController
{
    /**
     * @var AirlineTransformer
     */
    protected $airlineTransformer;

    /**
     * AirlinesForFlightRouteController constructor.
     * @param AirlineTransformer $airlineTransformer
     */
    public function __construct(AirlineTransformer $airlineTransformer)
    {
        $this->airlineTransformer = $airlineTransformer;
    }

    /**
     * @param $departureAirportId
     * @param $destinationAirportId
     * @return mixed
     */
    public function __invoke(int $departureAirportId, int $destinationAirportId)
    {
        $flightRoutes = FlightRoutes::where('source_airport_id', '=', $departureAirportId)
                                    ->where('destination_airport_id', '=', $destinationAirportId)
                                    ->pluck('airline_id');

        $airlines = Airline::whereIn('id', $flightRoutes)->get();

        if (count($airlines->toArray()) === 0) {
            return $this->respondNotFound('No airline found for the criteria.');
        }

        return $this->respond(['data' => $this->airlineTransformer->transformCollection($airlines->toArray())]);
    }
}
