<?php

namespace Tests\Feature\Record;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\MapList;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_record_registered()
    {

        \App\Models\User::factory()
        ->create(['id' => 2]);

        \App\Models\MapList::factory()
        ->create(['user_id' => 2, 'id' => 2]);

        $response = $this->post('/api/record/store', [
            "type" => "swim",
            "record" => "33.3",
            "id" => 2,
            "map_id" => 2,
            "sport_code" => "50"
        ]);

        $this->assertDatabaseHas('sports_record', [
            "type" => "swim",
            "record" => "33.3",
            "user_id" => 2,
            "map_id" => 2,
            "sport_code" => "50"
        ]);
    }
}
