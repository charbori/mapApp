<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\SportRecordService;
use App\Models\MapAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class RecordManagerController extends Controller
{

    public function view(Request $request) {
        $agent = new Agent();
        $sport_category = empty($request->sport_category) ? 'player' : 'team';
	    $view_env = array('agent' => 'pc');
        $view_name = 'recordList';

        if ($agent->isMobile()) {
            $view_name = 'mo.recordList';
            $view_env['agent'] = 'mobile';
        }

        $view_map_id = (strlen($request->map_id) == 0) ? '27' : $request->map_id;

        $get_map_data = \App\Models\MapList::where('id', $request->map_id)->get();
        if (count($get_map_data) == 0) {
            flash('기록 조회에 실패하였습니다.');
            return back();
        }

        $my_user_attach = '/build/images/people_icon.png';
        if (Auth::check()) {
            $service_param = array('user' => array());
            $service_param['user'][] = Auth::user();
            $sport_record_service = new SportRecordService();
            $result_user_rank_map_list = $sport_record_service->getUserMapList($service_param);

            if (count($result_user_rank_map_list) > 0) {
                $user_rank_map_list = $result_user_rank_map_list[0];
            }

            $result_rank_infos = $sport_record_service->getFollowInfos();
            $result_rank_list = $result_rank_infos['swim'];
            $res_user_attach = DB::table('user_attachment')
                                    ->where("user_id", '=', Auth::user()->id)
                                    ->get();
            if (count($res_user_attach) > 0 && strlen($res_user_attach[0]->path) > 0) {
                $my_user_attach = $res_user_attach[0]->path;
                $my_user_attach = (strpos($my_user_attach, "public") !== false) ?
                                    str_replace("public", "/storage", $my_user_attach) : $my_user_attach;
                $my_user_attach = $my_user_attach;
            }
        }

        return view($view_name, compact('sport_category', 'view_env', 'view_map_id', 'get_map_data', 'my_user_attach'));
    }

    public function mypage(Request $request) {
        $agent = new Agent();
	    $view_env = array('agent' => 'pc');
        $view_name = 'mypage.myrecord';

        if ($agent->isMobile()) {
            $view_name = 'mypage.myrecord_mo';
            $view_env['agent'] = 'mobile';
        }
        $my_user_attach = '/build/images/people_icon.png';

        return view($view_name, compact('view_env', 'my_user_attach'));
    }

    public function show(Request $request) {
        if (empty($request->map_id)) $map_id = 27;
        else $map_id = $request->map_id;
        if (empty($request->year)) $year = 2023;
        else $year = $request->year;
        if (empty($request->month_type) || $request->month_type == 'first_half') $diffTime = "<";
        else $diffTime = ">=";
        if (empty($request->page)) $skip = 0;
        else $skip = ($request->page - 1) * 10;
        if (empty($request->sport_code) || $request->sport_code == 'short_lane') $sport_code = array('50', '100');
        else if ($request->sport_code == 'middle_lane') $sport_code = array('200', '400');
        else if ($request->sport_code == 'long_lane') $sport_code = array('800', '1500');

        $data = array(  'map_id'        => $map_id,
                        'year'          => $year,
                        'month_type'    => $diffTime,
                        'skip'          => $skip,
                        'sport_code'    => $sport_code,
                        'search_name'   => $request->search_name);

        if (isset($request->search_name)) {
            $user = \App\Models\User::where('name', $request->search_name)->limit(1)->get();
            $data['user'] = $user;
        }
        if (isset($request->group_id)) {
            $user = \App\Models\User::where('group_id', $request->group_id)->get();
            $data['user'] = $user;
        } else if ($request->sport_category == 'team') {
            $user = \App\Models\User::where('group_id', '!=', '0')->get();
            $data['user'] = $user;
        }

        $sportService = new SportRecordService();

        if ($request->sport_category == 'team') {
            $result = $sportService->getTeamRecord($data);
            $sport_category = 'team';
        } else {
            $result = $sportService->getUserRecord($data);
            $sport_category = 'player';
        }

		$param = array('data' => array(), 'data2' => array(), 'count' => 0, 'count2' => 0, 'sport_category' => $sport_category);

        foreach ($result['res'] AS $val) {
            if (is_object($val)) {
                $param['data'][$val->sport_code][] = array(
                    'id'            => $val->id,
                    'type'          => 'swim',
                    'sport_code'    => $val->sport_code,
                    'record'        => (float) $val->record,
                    'user_id'       => $val->user->name,
                    'map_id'        => $val->map_id,
                    'reg_date'      => $val->created_at,
                );
            }
        }

        foreach ($result['res2'] AS $val2) {
            if (is_object($val)) {
                $param['data2'][$val2->sport_code][] = array(
                    'id'            => $val2->id,
                    'type'          => 'swim',
                    'sport_code'    => $val2->sport_code,
                    'record'        => (float) $val2->record,
                    'user_id'       => $val2->user->name,
                    'map_id'        => $val2->map_id,
                    'reg_date'      => $val2->created_at,
                );
            }
        }

        $param['count'] = $result['res_count'];
        $param['count2'] = $result['res2_count'];

        return $param;
    }

    public function getUserRecordMypage(Request $request) {

        $sportService = new SportRecordService();

        $user_data = array();
        if (isset($request->id)) {
            $user_data[] = \App\Models\User::where('id', $request->id)->limit(1)->get();
        } else if (Auth::check()) {
            $user_data[] = Auth::user();
        }
        $data = array(  'user'  => $user_data,
                        'skip'  => '0');
        $result = $sportService->getUserMapList($data);

        $datas = array( 'map_data' => $result,
                        'map_attachment' => array());
        if (count($result) > 0) {
            foreach ($result AS $res) {
                if (strlen($res->map_id) > 0) {
                    $map_attach = MapAttachment::where("map_id", $res->map_id)->first();
                    $datas['map_attachment'][] = (strpos($map_attach->path, "public") !== false) ?
                                        str_replace("public", "/storage", $map_attach->path) : $map_attach->path;
                }
            }
        }
        Log::debug($datas);

        return $datas;
    }

    public function getRecordRanking(Request $request) {
        $sportService = new SportRecordService();

        $result = $sportService->getRankingList();

        return $result;
    }

    public function userRecentRecord(Request $request) {
        if (empty($request->year)) $year = 2023;
        else $year = $request->year;

        $data = array(  'map_id'        => $map_id,
                        'year'          => $year,
                        'month_type'    => $diffTime,
                        'skip'          => $skip,
                        'sport_code'    => $sport_code,
                        'search_name'   => $request->search_name);

        $sportService = new SportRecordService();

        $result = $sportService->getUserRecord($data);

        foreach ($result['res'] AS $val) {
            if (is_object($val)) {
                $param['data'][$val->sport_code][] = array(
                    'id'            => $val->id,
                    'type'          => 'swim',
                    'sport_code'    => $val->sport_code,
                    'record'        => (float) $val->record,
                    'user_id'       => $val->user->name,
                    'map_id'        => $val->map_id,
                    'reg_date'      => $val->created_at,
                );
            }
        }

        foreach ($result['res2'] AS $val2) {
            if (is_object($val)) {
                $param['data2'][$val2->sport_code][] = array(
                    'id'            => $val2->id,
                    'type'          => 'swim',
                    'sport_code'    => $val2->sport_code,
                    'record'        => (float) $val2->record,
                    'user_id'       => $val2->user->name,
                    'map_id'        => $val2->map_id,
                    'reg_date'      => $val2->created_at,
                );
            }
        }

        $param['count'] = $result['res_count'];
        $param['count2'] = $result['res2_count'];

        return $param;
    }
}
