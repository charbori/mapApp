<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Decimal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MapList>
 */
class MapListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->name(),
            'type' => 'swim',
            'desc' => Str::random(10),
            'attachment' => Str::random(10),
            'latitude' => new Decimal(30),
            'longitude' => new Decimal(30),
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'rank' => 1,
            'player_count' => 1,
            'address' => Str::random(10),
            'tag' => Str::random(10),
        ];
    }
}
