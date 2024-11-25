<?php

namespace Tests\Feature;

use App\Claim;
use App\ClaimFlight;
use App\Complainant;
use App\Mail\ClaimRegistered\External;
use App\Mail\ClaimRegistered\Internal;
use App\Transformers\ClaimFlightTransformer;
use App\Transformers\ClaimTransformer;
use App\Transformers\ComplainantTransformer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;

class ClaimsTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    /**
     * @test
     */
    public function it_should_be_possible_to_retrieve_a_claim_by_its_reference_number()
    {
        $claim = factory(Claim::class)->create();

        $response = $this->getJson("v1/claims/$claim->id");

        $response->assertSuccessful();

        $claimFromApi = json_decode($response->baseResponse->getContent(), true);
        $this->assertEquals($claim->toArray(), $claimFromApi['data']);
    }

    /**
     * @test
     */
    public function it_should_return_not_found_if_no_claim_exists_with_the_requested_id()
    {
        $claim = factory(Claim::class)->create();

        $id = $claim->id + 41;

        $response = $this->getJson("v1/claims/$id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $claimFromApi = json_decode($response->baseResponse->getContent(), true);
        $this->assertNull($claimFromApi['data']);
    }

    /**
     * @test
     */
    public function it_should_be_possible_to_add_a_new_claim()
    {
        $claimTransformer = new ClaimTransformer();
        $complainantTransformer = new ComplainantTransformer();
        $flightTransformer = new ClaimFlightTransformer();

        $claim = factory(Claim::class)->make();
        $complainant = factory(Complainant::class)->make(['claim_id' => $claim->id]);
        $flight = factory(ClaimFlight::class)->make([
            'claim_id' => $claim->id,
            'is_initial_flight' => true,
            'flight_order' => 1
        ]);

        Mail::fake();

        $response = $this->postJson('v1/claims',
            array_merge(
                $claim->toArray(),
                [
                    'complainant' => $complainant->toArray(),
                    'flights' => [$flight->toArray()],
                    'flight_number_is_known' => false,
                ])
        );

        $response->assertSuccessful();

        Mail::assertSent(External::class);

        Mail::assertSent(Internal::class);

        $apiResult = json_decode($response->baseResponse->getContent(), true);

        $this->assertNotNull($apiResult['data']);
        $this->assertGreaterThan(0, $apiResult['data']['id']);

        $claimFromApi = $claimTransformer->transform($apiResult['data']);
        $claimFromApi['complainant'] = $complainantTransformer->transform($apiResult['data']['complainant']);
        $claimFromApi['flights'] = $flightTransformer->transformCollection($apiResult['data']['flights']);;

        $this->assertEquals($claimFromApi['id'], $claimFromApi['complainant']['claim_id']);

        $claim = $claim->toArray();
        $claim = $claimTransformer->transform($claim);
        $claim['complainant'] = $complainantTransformer->transform($complainant->toArray());
        $claim['flights'] = $flightTransformer->transformCollection([$flight->toArray()]);

        $claim = $this->filterUncomparableClaimFields($claim);
        $claimFromApi = $this->filterUncomparableClaimFields($claimFromApi);

        $this->assertEquals($claim, $claimFromApi);
    }

    /**
     * @test
     */
    public function it_should_have_a_complainant()
    {
        $claim = factory(Claim::class)->make();
        $flight = factory(ClaimFlight::class)->make(['claim_id' => $claim->id]);

        $response = $this->postJson('v1/claims',
            array_merge(
                $claim->toArray(),
                [
                    'flights' => [$flight->toArray()],
                ])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function it_should_have_an_initial_flight()
    {
        $claim = factory(Claim::class)->make();
        $complainant = factory(Claim::class)->make(['claim_id' => $claim->id]);
        $flight = factory(ClaimFlight::class)->make([
            'claim_id' => $claim->id,
            'is_initial_flight' => false,
            'flight_order' => 1
        ]);

        $response = $this->postJson('v1/claims',
            array_merge(
                $claim->toArray(),
                [
                    'complainant' => $complainant->toArray(),
                    'flights' => [$flight->toArray()],
                ])
        );


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function it_should_have_no_more_than_one_initial_flight()
    {
        $claim = factory(Claim::class)->make();
        $complainant = factory(Claim::class)->make(['claim_id' => $claim->id]);
        $firstFlight = factory(ClaimFlight::class)->make([
            'claim_id' => $claim->id,
            'is_initial_flight' => true,
            'flight_order' => 1
        ]);
        $secondFlight = factory(ClaimFlight::class)->make([
            'claim_id' => $claim->id,
            'is_initial_flight' => true,
            'flight_order' => 1
        ]);

        $response = $this->postJson('v1/claims',
            array_merge(
                $claim->toArray(),
                [
                    'complainant' => $complainant->toArray(),
                    'flights' => [$firstFlight->toArray(), $secondFlight->toArray()],
                ])
        );


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array $claim
     * @return array
     */
    private function filterUncomparableClaimFields(array $claim)
    {
        unset($claim['id']);
        unset($claim['created_at']);
        unset($claim['updated_at']);

        unset($claim['complainant']['id']);
        unset($claim['complainant']['claim_id']);
        unset($claim['complainant']['created_at']);
        unset($claim['complainant']['updated_at']);

        foreach ($claim['flights'] as &$flight) {
            unset($flight['id']);
            unset($flight['claim_id']);
            unset($flight['created_at']);
            unset($flight['updated_at']);
        }

        return $claim;
    }
}
