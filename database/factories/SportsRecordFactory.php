<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Decimal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SportsRecord>
 */
class SportsRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => 'swim',
            'record' => new Decimal(30),
            'user_id' => 1,
            'map_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'sport_code' => Str::random(10),
        ];
    }
}
