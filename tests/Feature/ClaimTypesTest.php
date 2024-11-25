<?php

namespace Tests\Feature;

use App\ClaimType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClaimTypesTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_all_available_claim_types()
    {
        $numberOfClaimTypes = 3;
        $claimTypes = [];

        foreach (range(1, $numberOfClaimTypes) as $id) {
            $claimTypes[] = factory(ClaimType::class)->create(['id' => $id])->toArray();
        }

        $response = $this->getJson('v1/claim-types');

        $response->assertSuccessful();

        $claimTypesFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertCount($numberOfClaimTypes, $claimTypesFromApi);
        $this->assertEquals($claimTypes, $claimTypesFromApi, '', 0.0, 10, true);
    }
}
