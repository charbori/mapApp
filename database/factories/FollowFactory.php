<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Decimal;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MapList>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'follower' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
