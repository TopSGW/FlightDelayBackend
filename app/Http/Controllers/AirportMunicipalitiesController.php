<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;

class AirportMunicipalitiesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = Input::get('name');

        if (strlen(trim($name)) === 0) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                        ->respondWithError('Please provide the airport name or a part of it for filtering the municipalities.');
        }

        $municipalities = Airport::where('municipality', 'like', "%$name%")
                                 ->select(DB::raw('max(id) as id'), 'municipality as name')
                                 ->orderBy('name')
                                 ->groupBy('municipality')
                                 ->limit(10)->get();

        if (count($municipalities->toArray()) === 0) {
            return $this->respondNotFound("No municipalities found for the given name: $name.");
        }

        return $this->respond(['data' => $municipalities->toArray()]);
    }
}
