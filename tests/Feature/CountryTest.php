<?php

namespace Tests\Feature;

use App\Country;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CountryTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_all_available_countries()
    {
        $numberOfCountries = 5;
        factory(Country::class)->times($numberOfCountries)->create();

        $response = $this->getJson('v1/countries');

        $response->assertSuccessful();

        $countriesFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertCount($numberOfCountries, $countriesFromApi);
    }

    /**
     * @test
     */
    public function it_should_allow_to_filter_countries_by_name()
    {
        $country = factory(Country::class)->create(['name' => 'Belgium']);

        $response = $this->getJson('v1/countries?name=bel');

        $response->assertSuccessful();

        $countriesFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $country = $country->toArray();
        unset($country['source_id']);

        $this->assertEquals($country, $countriesFromApi[0]);
    }

    /**
     * @test
     */
    public function it_should_return_an_error_if_no_country_was_found_for_the_given_name()
    {
        factory(Country::class)->create(['name' => 'Belgium']);

        $response = $this->getJson('v1/countries?name=fran');

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $countriesFromApi = json_decode($response->baseResponse->getContent(), true);

        $this->assertArrayHasKey('error', $countriesFromApi);
    }
}
