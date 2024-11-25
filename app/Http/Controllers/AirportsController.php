<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Transformers\AirportTransformer;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

class AirportsController extends ApiController
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $municipality = Input::get('municipality');

        if (strlen(trim($municipality)) === 0) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                        ->respondWithError('Please provide the airport municipality or a part of it for filtering the airports.');
        }

        $airports = Airport::where('municipality', 'like', "%$municipality%")->orderBy('municipality')->get();

        if(count($airports->toArray()) === 0) {
            return $this->respondNotFound("No airports found for the given municipality: $municipality.");
        }

        return $this->respond(['data' => $this->airportTransformer->transformCollection($airports->toArray())]);
    }
}
