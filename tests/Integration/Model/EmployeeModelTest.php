<?php

namespace Tests\Integration\Model;

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
}
