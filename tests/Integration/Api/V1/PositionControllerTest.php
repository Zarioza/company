<?php

namespace Tests\Integration\Api\V1;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PositionControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function simple(): void
    {
        $this->assertTrue(true);
    }

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

        $resourceResponse = PositionResource::collection(Position::all())->response()->getData(true);

        $this->assertEquals($resourceResponse, $response->json());
    }

    /** @test */
    public function expecting_not_found_if_position_not_exists_when_edit_position(): void
    {
        Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $payload = [
            'name' => 'Lead developer',
        ];

        $this->patchJson(route('api.position.update', ['position' => 999999]), $payload)
             ->assertNotFound();
    }

    /** @test */
    public function expecting_unprocessable_if_name_or_type_not_valid_when_edit_position(): void
    {
        $position = Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $payload = [
            'name' => 'Senior developer',
        ];

        $this->patchJson(route('api.position.update', ['position' => $position]), $payload)
             ->assertUnprocessable();

        $payload = [
            'type' => 'invalid type',
        ];

        $this->patchJson(route('api.position.update', ['position' => $position]), $payload)
             ->assertUnprocessable();
    }

    /** @test */
    public function it_can_update_existing_position(): void
    {
        $position = Position::factory()
                            ->create([
                                'name' => 'Senior developer',
                                'type' => Position::POSITION_REGULAR,
                            ]);

        $payload = [
            'name' => 'Lead developer',
            'type' => Position::POSITION_MANAGEMENT,
        ];

        $response = $this->patchJson(route('api.position.update', ['position' => $position]), $payload)
             ->assertStatus(Response::HTTP_ACCEPTED);

        $resourceResponse = PositionResource::make($position->refresh())
                                            ->response()
                                            ->getData(true);

        $this->assertEquals($resourceResponse, $response->json());
    }

    /** @test */
    public function expecting_not_found_if_position_not_exists_when_show_position(): void
    {
        Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $this->getJson(route('api.position.show', ['position' => 999999]))
             ->assertNotFound();
    }

    /** @test */
    public function it_can_show_single_position(): void
    {
        $position = Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $response = $this->getJson(route('api.position.show', ['position' => $position]))
             ->assertOk();

        $resourceResponse = PositionResource::make($position)
                                            ->response()
                                            ->getData(true);

        $this->assertEquals($resourceResponse, $response->json());
    }

    /** @test */
    public function expecting_not_found_if_position_not_exists_when_delete_position(): void
    {
        Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $this->deleteJson(route('api.position.destroy', ['position' => 999999]))
             ->assertNotFound();
    }

    /** @test */
    public function it_can_delete_position(): void
    {
        $position = Position::factory()
                ->create([
                    'name' => 'Senior developer',
                    'type' => Position::POSITION_REGULAR,
                ]);

        $this->deleteJson(route('api.position.destroy', ['position' => $position]))
             ->assertNoContent();
    }
}
