<?php

namespace Tests\Feature;

use App\Airport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AirportMunicipalitiesTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_a_list_of_cities()
    {
        $airports[] = factory(Airport::class)->create(['municipality' => 'Antwerp'])->toArray()['municipality'];
        $airports[] = factory(Airport::class)->create(['municipality' => 'Bombay'])->toArray()['municipality'];
        $airports[] = factory(Airport::class)->create(['municipality' => 'Brussel'])->toArray()['municipality'];
        $airports[] = factory(Airport::class)->create(['municipality' => 'New York'])->toArray()['municipality'];
        $airports[] = factory(Airport::class)->create(['municipality' => 'New Ark'])->toArray()['municipality'];

        $response = $this->getJson('v1/airport-municipalities?name=new');

        $response->assertSuccessful();

        $municipalities = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertEquals(2, count($municipalities));
    }
}
