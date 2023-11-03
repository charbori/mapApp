<?php

namespace Tests\Feature\Record;

use Tests\TestCase;
use Ramsey\Uuid\Type\Decimal;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SwimMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_swim_map_registered()
    {
        $user = \App\Models\User::factory()
        ->create(['id' => 3]);

        Auth::login($user);

        $response = $this->post('/api/map/mapStore', [
            "title" => "미진",
            "type" => 'map',
            "desc" => "미진",
            "longitude" => 37.3333,
            "latitude" => 37.3333,
            "id" => $user->id,
            "attachment" => "Y",
            "rank" => "0",
            "map_address" => 'test address',
            "player_count" => "0",
            "tag" => ""
        ]);

        $this->assertDatabaseHas('map_list', [
            "title" => "미진",
            "type" => 'swim',
            "desc" => "미진",
            "longitude" => '37.3333000000',
            "latitude" => '37.3333000000',
            "user_id" => $user->id,
            "attachment" => "Y",
            "address" => "test address",
        ]);
    }
}
