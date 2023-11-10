<?php

namespace App\Http\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SportRecordService
{
    public function __construct() {

    }

    public function getUserRecord($data) {
        if (!is_null($data['user']) && !$data['user']->isEmpty()) {
            $res = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][0])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res_count = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('map_id', $data['map_id'])
            ->where('sport_code', $data['sport_code'][0])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")->count();
            $res2 = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res2_count = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")->count();
        } else {
            $res = \App\Models\SportsRecord::with('user')->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->where('sport_code', $data['sport_code'][0])
            ->where('map_id', $data['map_id'])
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res_count = \App\Models\SportsRecord::with('user')->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->where('sport_code', $data['sport_code'][0])
            ->where('map_id', $data['map_id'])
            ->count();
            $res2 = \App\Models\SportsRecord::with('user')->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res2_count = \App\Models\SportsRecord::with('user')->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->count();
        }

        return array('res'   => $res,
                    'res2'  => $res2,
                    'res_count' => $res_count,
                    'res2_count'=> $res2_count);
    }

    public function getTeamRecord($data) {
        if (!is_null($data['user']) && !$data['user']->isEmpty()) {
            $res = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][0])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res_count = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][0])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")->count();
            $res2 = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")
            ->orderBy('record', 'asc')->offset($data['skip'])->limit(10)->get();
            $res2_count = \App\Models\SportsRecord::with('user')->where('user_id', $data['user'][0]->id)
            ->where('sport_code', $data['sport_code'][1])
            ->where('map_id', $data['map_id'])
            ->whereDate('created_at', $data['month_type'], $data['year']."-07-01")->count();
        } else {
            return array('res'   => collect([]),
                        'res2'  =>  collect([]),
                        'res_count' =>  collect([]),
                        'res2_count'=>  collect([]));
        }

        return array('res'   => $res,
                    'res2'  => $res2,
                    'res_count' => $res_count,
                    'res2_count'=> $res2_count);
    }

    public function getUserMapList($data) {
        $res = collect([]);
        if (isset($data['user'])) {
            $find_date = date('Y-m-d H:i:s',  time() - 15000000);
            $res =  DB::table('sports_record')
                    ->join('map_list', 'sports_record.map_id', '=', 'map_list.id')
                    ->where('sports_record.user_id', $data['user']->id)
                    ->where('sports_record.created_at', '>', $find_date)
                    ->select('sports_record.map_id', 'map_list.title')
                    ->orderBy('sports_record.map_id', 'desc')->groupBy('sports_record.map_id')->limit(3)->get();
        }

        return $res;
    }

    public function getFollowInfos() {
        $now_year = date('Y');
        $now_month = date('m');

        if ((int) $now_month > 6) {
            $created_at_start = $now_year.'-07-01 00:00:00';
            $created_at_end = $now_year.'-12-31 23:59:59';
        } else {
            $created_at_start = $now_year.'-01-01 00:00:00';
            $created_at_end = $now_year.'-06-30 23:59:59';
        }

        $sport_type = "swim";
        $sport_code_arr = array('50', '100', '200', '400', '800', '1500');
        $sport_record_datas = array();
        $sport_icons = array();
        foreach ($sport_code_arr AS $sport_code) {
            $sport_record_datas[$sport_type][$sport_code] = DB::table('follow')->select('*')
                ->join('ranking', 'ranking.user_id', '=', 'follow.id')
                ->join('map_list', 'map_list.id', '=', 'ranking.map_id')
                ->join('users', 'users.id', '=', 'follow.follower')
                ->where('ranking.created_at', '>', $created_at_start)
                ->where('ranking.created_at', '<', $created_at_end)
                ->where('ranking.record_type', '=', $sport_type)
                ->where('ranking.sport_code', '=', $sport_code)
                ->where('follow.id', '=', Auth::id())
                ->orderBy('ranking.created_at','asc')->get();
            if ($sport_type == 'swim') {
                $sport_record_datas[$sport_type]['icons'] = "/build/images/people_icon.png";
            }

            foreach ($sport_record_datas[$sport_type][$sport_code] AS $sport_data) {
                $record_time = strtotime($sport_data->created_at);
                // 날짜변경
                if ($record_time < strtotime("2022-12-30")) {
                    $record_time = "-";
                } else if (60 > strtotime("-1 hours") - $record_time) {
                } else if (3600 > strtotime("-1 day") - $record_time) {
                    $record_diff = strtotime("-1 hours") - strtotime($sport_data->created_at);
                    $sport_data->created_at = (int) ($record_diff/60) . "분전";
                } else if (3600 * 2 > strtotime("-2 day") - $record_time) {
                    $record_diff = strtotime("-1 hours") - strtotime($sport_data->created_at);
                    $sport_data->created_at = (int) ($record_diff/3600) . "시간전";
                } else if (86400 > strtotime("-1 week") - $record_time) {
                    $record_diff = strtotime("-2 day") - strtotime($sport_data->created_at);
                    $sport_data->created_at = "하루전";
                } else if (2592000 > strtotime("-1 month") - $record_time) {
                    $record_diff = strtotime("-1 week") - strtotime($sport_data->created_at);
                    $sport_data->created_at = (int) ($record_diff/86400) . "일전";
                } else if (2592000 < strtotime("-1 month") - $record_time && 2592000 * 12 > strtotime("-12 month") - $record_time) {
                    $record_diff = strtotime("-1 month") - strtotime($sport_data->created_at);
                    $sport_data->created_at = (int) ($record_diff/2592000) . "달전";
                } else {
                    $sport_data->created_at = $sport_data->created_at = date("Y") . "년";
                }

                $path_data = "";
                $res_user_attach = DB::table('user_attachment')
                                    ->where("user_id", '=', $sport_data->follower)
                                    ->get();

                $sport_data->path = '/build/images/people_icon.png';
                if (count($res_user_attach) > 0 && strlen($res_user_attach[0]->path) > 0) {
                    $path_data = $res_user_attach[0]->path;
                    $sport_data->path = (strpos($path_data, "public") !== false) ?
                                        str_replace("public", "/storage", $path_data) : $path_data;
                }

                if (strlen($sport_data->path) > 0) {
                    $sport_data->path = (strpos($sport_data->path, "public") !== false) ?
                                        str_replace("public", "/storage", $sport_data->path) : $sport_data->path;
                }
            }
        }

        return $sport_record_datas;
    }

    public function getRankingList() {
        $now_year = date('Y');
        $now_month = date('m');
        if ((int) $now_month > 6) {
            $created_at_start = '2023-07-01 00:00:00';
            $created_at_end = '2023-12-31 23:59:59';
        } else {
            $created_at_start = '2023-01-01 00:00:00';
            $created_at_end = '2023-06-30 23:59:59';
        }
        $sport_code_arr = array('50', '100', '200', '400', '800', '1500');
        $sport_record_datas = array();
        foreach ($sport_code_arr AS $sport_code) {
            $sport_record_datas[$sport_code] = DB::table('sports_record')->select(DB::raw('count(sport_code) as cnt, user_id, name'))
                ->join('users', 'users.id', '=', 'sports_record.user_id')
                ->where('sports_record.created_at', '>', $created_at_start)
                ->where('sports_record.created_at', '<', $created_at_end)
                ->where('sport_code', '=', $sport_code)
                ->groupBy('user_id')->orderBy('cnt')->get();
        }

                /*
                select count(sport_code) as cnt, user_id
from sports_record
where created_at > '2023-07-01 00:00:00'
and created_at < '2023-12-31 23:59:59'
group by user_id;
                */

        $sport_user_result = array();
        $user_result = array();
        $sport_code = "";
        // sport code 별로 조회해서 계산해야한다.
        // 50m
        foreach ($sport_code_arr AS $sport_code) {
            $sport_datas = $sport_record_datas[$sport_code];
            if (is_object($sport_datas)) {
                foreach ($sport_datas AS $val) {
                    if (empty($sport_user_result[$val->user_id])) {
                        $sport_user_result[$val->user_id] = $val->cnt * (int) $sport_code;
                        $user_result[$val->user_id] = $val->name;
                    } else {
                        $sport_user_result[$val->user_id] += $val->cnt * (int) $sport_code;
                        $user_result[$val->user_id] = $val->name;
                    }
                }
            }
        }
        $datas = array();
        foreach ($sport_user_result AS $key => $val2) {
            $datas[] = array(   'user_id' => $key,
                                'distance' => $val2,
                                'name' => $user_result[$key]);
        }
        return array('res' => $datas);
    }
}
