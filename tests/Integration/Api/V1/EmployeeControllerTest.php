<?php

namespace Tests\Integration\Api\V1;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
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
        $position = Position::factory()->create([
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

    /** @test */
    public function it_can_list_all_employees(): void
    {
        $position = Position::factory()->create([
            'name' => 'Senior developer',
            'type' => Position::POSITION_REGULAR,
        ]);

        Employee::factory(10)->create([
            'position_id' => $position->id,
        ]);

        $currentPage = 1;

        $response = $this->getJson(route('api.employee.index', ['page' => $currentPage]))
                         ->assertOk();

        $employees = Employee::with('position')->paginate(6);
        $employeeResource = EmployeeResource::collection($employees)
                                            ->response()
                                            ->getData(true);

        $this->assertEquals($employeeResource, $response->json());

        $currentPage++;

        while($currentPage <= $response->json('meta.last_page')) {
            $response = $this->getJson(route('api.employee.index', ['page' => $currentPage]))
                             ->assertOk();

            $this->assertCount(4, $response->json('data'));

            $currentPage++;
        }
    }

    /** @test */
    public function expecting_not_found_if_employee_id_is_not_valid_when_shoe_employee():void
    {
        $this->getJson(route('api.employee.show', ['employee' => 999999]))
             ->assertNotFound();
    }

    /** @test */
    public function it_can_show_employee(): void
    {
        $position = Position::factory()->create([
            'name' => 'Senior developer',
            'type' => Position::POSITION_REGULAR,
        ]);

        $employee = Employee::factory()->create([
            'position_id' => $position->id,
        ]);

        $response = $this->getJson(route('api.employee.show', ['employee' => $employee]))
             ->assertOk();

        $employeeResource = EmployeeResource::make($employee->load('position'))
                                           ->response()
                                           ->getData(true);

        $this->assertEquals($employeeResource, $response->json());
    }

    /** @test */
    public function it_can_edit_employee(): void
    {
        $position = Position::factory()->create([
            'name' => 'Senior developer',
            'type' => Position::POSITION_REGULAR,
        ]);

        $managementPosition = Position::factory()->create([
            'name' => 'Manager',
            'type' => Position::POSITION_MANAGEMENT,
        ]);

        $employee = Employee::factory()->create([
            'position_id' => $position->id,
        ]);

        $payload = [
            'name' => 'Edited name',
            'position_id' => $managementPosition->id,
            'superior_id' => null,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->addYears(5)->format('Y-m-d'),
        ];

        $response = $this->patchJson(route('api.employee.update', ['employee' => $employee]), $payload)
                         ->assertStatus(Response::HTTP_ACCEPTED);

        $editedEmployee = Employee::where('name', 'LIKE', $payload['name'])->first();
        $employeeResource = EmployeeResource::make($editedEmployee->load(['position']))
                                            ->response()
                                            ->getData(true);

        $this->assertEquals($employeeResource, $response->json());
    }
}
