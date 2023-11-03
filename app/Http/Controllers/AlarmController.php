<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Service\SportRecordService;

class AlarmController extends Controller
{
    public function show(Request $request) {
        $guest_id = Auth::check() ? Auth::user()->id : $request->id;

        $date = date("Y-m-d H:i:s", strtotime("-3 Months"));
        $alarm_res = DB::table('alarm')
                            ->where('user_id', '=', $guest_id)
                            ->where('created_at', ">", $date)
                            ->get();
        return $alarm_res;
    }

}
