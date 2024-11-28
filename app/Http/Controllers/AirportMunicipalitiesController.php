<?php

namespace App\Http\Controllers;

use App\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AirportMunicipalitiesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name');

        if (empty(trim($name))) {
            return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
                       ->respondWithError('Please provide the airport name or a part of it for filtering the municipalities.');
        }

        $municipalities = Airport::where('municipality', 'like', "%$name%")
                               ->select(DB::raw('max(id) as id'), 'municipality as name')
                               ->orderBy('name')
                               ->groupBy('municipality')
                               ->limit(10)
                               ->get();

        if ($municipalities->isEmpty()) {
            return $this->respondNotFound("No municipalities found for the given name: $name.");
        }

        return $this->respond(['data' => $municipalities->toArray()]);
    }
}