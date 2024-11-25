<?php

namespace App\Http\Controllers;

use App\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Support\Facades\Input;

class CountriesController extends ApiController
{
    /**
     * @var CountryTransformer
     */
    private $countryTransformer;

    /**
     * CountriesController constructor.
     * @param CountryTransformer $countryTransformer
     */
    public function __construct(CountryTransformer $countryTransformer)
    {
        $this->countryTransformer = $countryTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = Input::get('name');

        $countries = Country::where('name', 'like', "%$name%")->orderBy('name')->get();

        if(count($countries->toArray()) === 0) {
            return $this->respondNotFound("No country found for the given name: $name");
        }

        return $this->respond(['data' => $this->countryTransformer->transformCollection($countries->toArray())]);
    }
}
