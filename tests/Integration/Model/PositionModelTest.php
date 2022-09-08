<?php

namespace Tests\Integration\Model;

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
}
