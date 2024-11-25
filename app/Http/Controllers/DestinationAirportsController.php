<?php

namespace App\Http\Controllers;

use App\Airport;
use App\FlightRoutes;
use App\Transformers\AirportTransformer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class DestinationAirportsController extends ApiController
{
    /**
     * @var AirportTransformer
     */
    private $airportTransformer;

    /**
     * @param AirportTransformer $airportTransformer
     */
    public function __construct(AirportTransformer $airportTransformer)
    {
        $this->airportTransformer = $airportTransformer;
    }

    /**
     * @param int $departureAirportId
     * @return mixed
     */
    public function __invoke($departureAirportId)
    {
        $destinationAirportIds = FlightRoutes::where('source_airport_id', '=', $departureAirportId)
                                             ->pluck('destination_airport_id');

        $airports = Airport::whereIn('id', $destinationAirportIds->toArray())
                           ->where('name', 'like', '%' . Input::get('name') . '%')
                           ->get();

        if(count($airports->toArray()) === 0) {
            return $this->respondNotFound('No destination airport found for the given departure airport.');
        }

        return $this->respond(['data' => $this->airportTransformer->transformCollection($airports->toArray())]);
    }
}
