<?php

namespace App\Listeners;

use App\Events\AlarmRead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendAlarmNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AlarmRead  $event
     * @return void
     */
    public function handle(AlarmRead $event)
    {
        $alarm_res = DB::table('alarm')
                            ->where('id', "=", $event->alarm->id)
                            ->update(['alarm_reac' => 'Y']);

        return $alarm_res;
    }
}
