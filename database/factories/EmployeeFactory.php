<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'superior_id' => null,
            'position_id' => null,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => null,
        ];
    }
}
