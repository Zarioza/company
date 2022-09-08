<?php

namespace Tests\Integration\Api\V1;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function simple(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_create_employee(): void
    {
        $position = Position::factory()
                           ->create([
                               'name' => 'Senior developer',
                               'type' => Position::POSITION_REGULAR,
                           ]);

        $payload = [
            'name' => 'John Doe',
            'superior_id' => null,
            'position_id' => $position->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $response = $this->postJson(route('api.employee.store'), $payload)
                         ->assertCreated();

        $this->assertDatabaseHas(Employee::class, $payload);

        $employee = Employee::where('name', $payload['name'])->first();

        $employeeResource = EmployeeResource::make($employee->load(['position']))
                                            ->response()
                                            ->getData(true);

        $this->assertEquals($employeeResource, $response->json());
    }

    /** @test */
    public function expecting_unprocessable_if_parameter_is_not_valid_when_create_employee(): void
    {
        $position = Position::factory()
                            ->create([
                                'name' => 'Senior developer',
                                'type' => Position::POSITION_REGULAR,
                            ]);

        $payload = [
            'name' => '',
            'superior_id' => null,
            'position_id' => $position->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $this->postJson(route('api.employee.store'), $payload)
             ->assertUnprocessable();

        $payload = [
            'name' => 'John Doe',
            'superior_id' => 1,
            'position_id' => $position->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $this->postJson(route('api.employee.store'), $payload)
             ->assertUnprocessable();

        $payload = [
            'name' => 'John Doe',
            'superior_id' => null,
            'position_id' => 9999999,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $this->postJson(route('api.employee.store'), $payload)
             ->assertUnprocessable();

        $payload = [
            'name' => 'John Doe',
            'superior_id' => 1,
            'position_id' => $position->id,
            'start_date' => date('Y-d-m'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $this->postJson(route('api.employee.store'), $payload)
             ->assertUnprocessable();

        $payload = [
            'name' => 'John Doe',
            'superior_id' => 1,
            'position_id' => $position->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->subYears(5)->format('Y-m-d'),
        ];

        $this->postJson(route('api.employee.store'), $payload)
             ->assertUnprocessable();
    }
}
