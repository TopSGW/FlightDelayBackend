<?php

namespace Tests\Feature;

use App\Airline;
use App\Airport;
use App\FlightRoutes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AirlineForFlightRouteTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_an_airline_for_a_combination_of_departure_and_destination_airport()
    {
        $airline = factory(Airline::class)->create();
        $departureAirport = factory(Airport::class)->create();
        $destinationAirport = factory(Airport::class)->create();

        factory(FlightRoutes::class)->create([
            'source_airport_id' => $departureAirport->id,
            'destination_airport_id' => $destinationAirport->id,
            'airline_id' => $airline->id,
        ]);

        $response = $this->getJson("v1/airlines-for-flight-route/$departureAirport->id/$destinationAirport->id");

        $response->assertSuccessful();

        $flightRouteDataFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertCount(1, $flightRouteDataFromApi);

        unset($airline['country']);
        unset($airline['source_id']);

        $this->assertEquals($airline->toArray(), $flightRouteDataFromApi[0]);
    }

    /**
     * @test
     */
    public function it_should_return_the_correct_http_status_if_no_airline_was_found_for_the_combination_of_departure_and_destination_airport()
    {
        $airline = factory(Airline::class)->create();
        $departureAirport = factory(Airport::class)->create();
        $destinationAirport = factory(Airport::class)->create();

        factory(FlightRoutes::class)->create([
            'source_airport_id' => $departureAirport->id,
            'destination_airport_id' => $destinationAirport->id,
            'airline_id' => $airline->id,
        ]);

        $destinationAirportId = $destinationAirport->id + 15;

        $response = $this->getJson("v1/airlines-for-flight-route/$departureAirport->id/$destinationAirportId");

        $flightRouteDataFromApi = json_decode($response->baseResponse->getContent(), true);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertArrayHasKey('error', $flightRouteDataFromApi);
    }
}
