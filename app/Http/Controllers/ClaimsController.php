<?php

namespace App\Http\Controllers;

use App\Claim;
use App\ClaimFileNumber;
use App\ClaimFlight;
use App\Complainant;
use App\Http\Requests\ClaimFlightRequest;
use App\Http\Requests\ComplainantRequest;
use App\Http\Requests\CreateClaimRequest;
use App\Mail\ClaimRegistered\External;
use App\Mail\ClaimRegistered\Internal;
use App\Transformers\ClaimTransformer;
use App\Transformers\ComplainantTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ClaimsController extends ApiController
{
    const CLAIM_SOURCE = 'INT';

    /**
     * @var ClaimTransformer
     */
    private $claimTransformer;

    /**
     * @var ComplainantTransformer
     */
    private $complainantTransformer;

    /**
     * @param ClaimTransformer $claimTransformer
     * @param ComplainantTransformer $complainantTransformer
     */
    public function __construct(ClaimTransformer $claimTransformer, ComplainantTransformer $complainantTransformer)
    {
        $this->claimTransformer = $claimTransformer;
        $this->complainantTransformer = $complainantTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $claimInput = Input::only(['claim_type_id', 'remarks']);
        $validator = Validator::make($claimInput, (new CreateClaimRequest)->rules());

        if ($validator->fails()) {
            return $this->respondValidationFailed('Parameters failed validation for a claim.', 'claim');
        }

        $complainantInput = Input::only('complainant')['complainant'];

        if(isset($complainantInput['country_id']) && $complainantInput['country_id'] === 0) {
            unset($complainantInput['country_id']);
        }

        $complainantIsValid = $this->validateComplainant($complainantInput);
        if ($complainantIsValid !== true) {
            return $complainantIsValid;
        }

        $flightsInput = Input::only('flights')['flights'];
        $flightsAreValid = $this->validateFlights($flightsInput);
        if ($flightsAreValid !== true) {
            return $flightsAreValid;
        }

        $flights = new Collection();

        array_map(function ($flightInput) use ($flights) {
            $flights->add(new ClaimFlight($flightInput));
        }, $flightsInput);

        $flightOrder = 1;
        $flightsToSave = $flights->each(function ($flight) use (&$flightOrder) {
            $flight->flight_order = $flightOrder;
            $flight->flight_date = new Carbon($flight->flight_date);

            $flightOrder += 1;
        });

        DB::beginTransaction();
        $fileNumber = (new ClaimFileNumber)->getNextFileNumber(self::CLAIM_SOURCE);

        /**
         * @var Claim $claim
         */
        $claim = Claim::create(array_merge($claimInput, [
            'source' => self::CLAIM_SOURCE,
            'file_number' => $fileNumber
        ]));

        $claim->complainant()->create(array_merge($complainantInput, ['claim_id' => $claim->id]));

        $claim->flights()->saveMany($flightsToSave);

        DB::commit();

        $claimWithRelations = Claim::with(
            'claimType',
            'complainant.country',
            'complainant.salutation',
            'flights.departureAirport',
            'flights.destinationAirport',
            'flights.airline',
            'flights.delay')->find($claim->id);

        App::setLocale($claim->complainant->language);
        Mail::to($claim->complainant->email)->send(new External($claim));

        App::setLocale('nl');
        Mail::to(config('mail.from.address'))->send(new Internal($claimWithRelations));

        return $this->respondCreated('Claim successfully created', $claimWithRelations->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $claim = Claim::findOrFail($id);

        return Response::json([
            'data' => $claim->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param array $complainant
     * @return bool|mixed
     */
    private function validateComplainant(array $complainant = null)
    {
        if (is_null($complainant)) {
            $complainant = [];
        }

        $validator = Validator::make($complainant, (new ComplainantRequest)->rules());

        if ($validator->fails()) {
            return $this->respondValidationFailed('Parameters failed validation for a complainant.', 'complainant');
        }

        return true;
    }

    /**
     * @param array|null $flights
     * @return bool|mixed
     */
    private function validateFlights(array $flights = null)
    {
        if ($flights === null || count($flights) === 0) {
            return $this->respondValidationFailed('No flights added to the complaint. Please add at least an initial flight',
                'flights');
        }

        $initialFlights = array_filter($flights, function ($flight) {
            return $flight['is_initial_flight'] === true;
        });

        if (count($initialFlights) === 0) {
            return $this->respondValidationFailed('The initial flight is missing. Please add an initial flight.',
                'flights');
        }

        if (count($initialFlights) > 1) {
            return $this->respondValidationFailed('There can only be one initial flight.', 'flights');
        }

        foreach ($flights as $flight) {
            $flightIsValid = $this->validateFlight($flight);

            if ($flightIsValid !== true) {
                return $flightIsValid;
            }
        }

        return true;
    }

    /**
     * @param array|null $flight
     * @return bool|mixed
     */
    private function validateFlight(array $flight = null)
    {
        if (is_null($flight)) {
            $flight = [];
        }

        $validator = Validator::make($flight, (new ClaimFlightRequest)->rules());

        if ($validator->fails()) {
            $flightOrder = $flight['flight_order'];

            return $this->respondValidationFailed("Parameters failed validation for a flight. (flightOrder = $flightOrder)",
                'flights', $flightOrder);
        }

        return true;
    }
}
