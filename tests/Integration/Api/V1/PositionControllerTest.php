<?php

namespace Tests\Integration\Api\V1;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PositionControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_not_create_two_positions_with_same_name(): void
    {
        Position::factory()
                ->create([
                    'name' => 'Junior developer',
                ]);

        $payload = [
            'name' => 'Junior developer',
            'type' => Position::POSITION_REGULAR,
        ];

        $this->postJson(route('api.position.store'), $payload)
             ->assertUnprocessable();
    }

    /** @test */
    public function it_can_create_position_with_unique_name(): void
    {
        $payload = [
            'name' => 'Senior developer',
            'type' => Position::POSITION_REGULAR,
        ];

        $response = $this->postJson(route('api.position.store'), $payload)
             ->assertCreated();

        $position = Position::where('name', 'LIKE', 'Senior developer')->first();

        $positionResource = PositionResource::make($position)->response()->getData(true);

        $this->assertEquals($positionResource, $response->json());
    }

    /** @test */
    public function it_can_list_positions(): void
    {
        Position::factory()
                ->create([
                    'type' => Position::POSITION_REGULAR,
                ]);

        Position::factory()
                ->create([
                    'type' => Position::POSITION_REGULAR,
                ]);

        $response = $this->getJson(route('api.position.index'))
                         ->assertOk();

        $responseResource = PositionResource::collection(Position::all())->response()->getData(true);

        $this->assertEquals($responseResource, $response->json());
    }
}
