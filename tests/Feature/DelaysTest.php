<?php

namespace Tests\Feature;

use App\Delay;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DelaysTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_should_return_all_available_delays()
    {
        $numberOfDelays = 5;
        $delays = [];

        foreach (range(1, $numberOfDelays) as $id) {
            $delays[] = factory(Delay::class)->create(['id' => $id])->toArray();
        }

        $response = $this->getJson('v1/delays');

        $response->assertSuccessful();

        $delaysFromApi = json_decode($response->baseResponse->getContent(), true)['data'];

        $this->assertCount($numberOfDelays, $delaysFromApi);
        $this->assertEquals($delays, $delaysFromApi, '', 0.0, 10, true);
    }
}
