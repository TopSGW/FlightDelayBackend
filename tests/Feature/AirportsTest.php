<?php

namespace Tests\Feature;

use App\Airport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AirportsTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_should_return_airports_by_name()
    {
        $airport = factory(Airport::class)->create(['municipality' => 'Brussel'])->toArray();

        $response = $this->getJson('v1/airports?municipality=bru');

        $response->assertSuccessful();

        $airportFromApi = json_decode($response->baseResponse->getContent(), true)['data'][0];

        unset($airport['source_id']);

        $this->assertEquals($airport, $airportFromApi);
    }

    /**
     * @test
     */
    public function it_should_return_an_error_if_no_name_was_given_to_filter_the_airports()
    {
        $response = $this->getJson('v1/airports?municipality=');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $airportFromApi = json_decode($response->baseResponse->getContent(), true);

        $this->assertArrayHasKey('error', $airportFromApi);
    }

    /**
     * @test
     */
    public function it_should_return_an_error_if_no_airport_was_found_for_the_given_name()
    {
        $airport = factory(Airport::class)->create(['municipality' => 'Brussel'])->toArray();

        $response = $this->getJson('v1/airports?municipality=Par');

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $airportFromApi = json_decode($response->baseResponse->getContent(), true);

        $this->assertArrayHasKey('error', $airportFromApi);
    }
}
