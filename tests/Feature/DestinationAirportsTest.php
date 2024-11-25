<?php

namespace Tests\Feature;

use App\Airport;
use App\FlightRoutes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DestinationAirportsTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_destination_airports_for_a_departure_airport()
    {
        $departureAirport = factory(Airport::class)->create();
        $destinationAirports = factory(Airport::class)->times(2)->create();

        foreach ($destinationAirports as $destinationAirport) {
            $flightRoutes[] = factory(FlightRoutes::class)->create([
                'source_airport_id' => $departureAirport->id,
                'destination_airport_id' => $destinationAirport->id,
            ]);
        }

        $url = 'v1/destination-airports/' . $departureAirport->id . '?name=' . urlencode(substr($destinationAirports[0]['name'],
                1, 5));

        $response = $this->getJson($url);

        $response->assertSuccessful();

        $destinationAirportsFromApi = collect(json_decode($response->baseResponse->getContent(), true)['data']);
        $destinationAirport = $destinationAirports->toArray()[0];

        unset($destinationAirport['source_id']);

        $this->assertEquals($destinationAirport, $destinationAirportsFromApi->toArray()[0]);
    }

    /**
     * @test
     */
    public function it_should_return_the_correct_http_status_if_no_airline_was_found_for_the_given_departure__airport()
    {
        $departureAirport = factory(Airport::class)->create();
        $destinationAirports = factory(Airport::class)->times(2)->create();

        foreach ($destinationAirports as $destinationAirport) {
            $flightRoutes[] = factory(FlightRoutes::class)->create([
                'source_airport_id' => $departureAirport->id,
                'destination_airport_id' => $destinationAirport->id,
            ]);
        }

        $url = 'v1/destination-airports/' . $departureAirport->id . '?name='
            . urlencode(substr($destinationAirports[0]['name'], 1, 5) . '3Evc');

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $destinationAirportDataFromApi = collect(json_decode($response->baseResponse->getContent(), true));

        $this->assertArrayHasKey('error', $destinationAirportDataFromApi);
    }
}
