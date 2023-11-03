<?php

namespace App\Jobs;

use App\Models\Alarm;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AlarmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $sports_records = DB::table('sports_record')
        ->select(DB::raw(   'sports_record.type, users.id as `u1_id`, sports_record.created_at, follow.follower, users.name'))
        ->join('follow','sports_record.user_id','=','follow.user_id')
        ->join('users','follow.user_id', '=', 'users.id')
        ->where('sports_record.created_at', '>', 'DATE_SUB(NOW(), INTERVAL 3 HOUR)')
        ->get();

        foreach ($sports_records AS $sports_record) {
            Alarm::create([
                'user_id' => $sports_record->follower,
                'type' => 'AA',
                'content' => $sports_record->name.'님이 새로운 기록을 게시하였습니다.',
                'alarm_reac' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
                'name' => 'users',
                'ref_id' => $sports_record->u1_id
            ]);
        }
    }
}
