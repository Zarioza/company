<?php

namespace Tests\Integration\Model;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PositionModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function positions_table_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('positions', [
                'id', 'name', 'created_at', 'updated_at'
            ])
        );
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $position = Position::factory()
                            ->create([
                                'type' => Position::POSITION_REGULAR,
                            ]);
        Employee::factory()
                ->create([
                    'position_id' => $position->id,
                ]);

        $this->assertInstanceOf(Collection::class, $position->employees);
    }
}
