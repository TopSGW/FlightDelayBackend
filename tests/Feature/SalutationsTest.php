<?php

namespace Tests\Feature;

use App\Salutation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SalutationsTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_all_available_salutations()
    {
        $numberOfSalutations = 5;
        $salutations = [];

        foreach (range(1, $numberOfSalutations) as $id) {
            $salutations[] = factory(Salutation::class)->create(['id' => $id])->toArray();
        }

        $response = $this->getJson('v1/salutations');

        $response->assertSuccessful();

        $salutationsFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertCount($numberOfSalutations, $salutationsFromApi);
        $this->assertEquals($salutations, $salutationsFromApi, '', 0.0, 10, true);
    }
}
