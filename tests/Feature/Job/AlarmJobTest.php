<?php

namespace Tests\Feature\Job;

use Tests\TestCase;
use App\Models\User;
use App\Models\Alarm;
use App\Models\Follow;
use App\Models\MapList;
use App\Models\SportsRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlarmJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_alarm_test()
    {
        User::factory()->create(['id' => 1]);
        User::factory()->create(['id' => 11]);
        User::factory()->create(['id' => 12]);
        MapList::factory()->create(['user_id' => 1]);
        SportsRecord::factory()->create(['user_id' => 1, 'map_id' => 1]);
        Follow::factory()->create(['user_id' => 1, 'follower' => 11]);
        Follow::factory()->create(['user_id' => 1, 'follower' => 12]);

        $sport_records = DB::table('sports_record')
        ->join('follow','sports_record.user_id','=','follow.user_id')
        ->get();

        foreach ($sport_records as $sport_record) {
            Alarm::factory()->create([
                    'user_id' => $sport_record->user_id,
                ]);
        }

        $count = Alarm::count();

        $this->assertEquals(2, $count);
    }
}
