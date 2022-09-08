<?php

namespace Tests\Integration\Model;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function employees_table_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('employees', [
                'id', 'superior_id', 'position_id', 'name', 'start_date', 'end_date', 'created_at', 'updated_at'
            ])
        );
    }

    /** @test  */
    public function it_belongs_to_position(): void
    {
        $position = Position::factory()
                            ->create([
                                'type' => Position::POSITION_REGULAR,
                            ]);
        $employee = Employee::factory()
                            ->create([
                                'position_id' => $position->id,
                            ]);
        $this->assertInstanceOf(Position::class, $employee->position);
    }

}
