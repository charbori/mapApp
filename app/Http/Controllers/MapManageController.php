<?php

namespace App\Http\Controllers;

use App\Http\Service\SportRecordService;
use Illuminate\Http\Request;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use stdClass;

class MapManageController extends Controller
{
    public function view(Request $request) {
        $agent = new Agent();
	    $view_env = array('agent' => 'pc');
        $view_name = 'recordMap';
        $now_month_type = (int) date("m");
        $now_month_type = ($now_month_type > 6) ? 'last_half' : 'before_half';

        if ($agent->isMobile()) {
            $view_name = 'mo.recordHome';
            $view_env['agent'] = 'mobile';
        }

        if ($request->view_type == 'map') {
            $view_name = 'mo.recordMap';
        }

        $user_rank_map_list = array('title' => '',
                                    'map_id' => '');
        $result_rank_list = array('50' => array());
        $user_rank_map_list = (object) $user_rank_map_list;
        $view_map_id = 0;

        $my_user_attach = '/build/images/people_icon.png';
        if (Auth::check()) {
            $service_param = array('user' => array());
            $service_param['user'][] = Auth::user();
            $sport_record_service = new SportRecordService();
            $result_user_rank_map_list = $sport_record_service->getUserMapList($service_param);

            if (is_array($result_user_rank_map_list)) {
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
        return view($view_name, compact('view_env', 'now_month_type', 'user_rank_map_list', 'view_map_id', 'result_rank_list', 'my_user_attach'));
    }

    public function show(Request $request) {
		$marker_data = array();

        if ($request->search) {
            $mymap = \App\Models\MapList::with('user')->where('tag', 'like', "%".$request->search."%")->orWhere('address', 'like', "%".$request->search."%")->get();
            $mymapattach = \App\Models\MapAttachment::get();
        } else if (empty($request->long) || empty($request->lat)) {
            $mymap = \App\Models\MapList::with('user')->get();
            $mymapattach = \App\Models\MapAttachment::get();
        } else {
            $mymap = \App\Models\MapList::with('user', 'sports_record')->whereBetween('latitude', [$request->lat - 0.01, $request->lat + 0.01])->whereBetween('longitude', [$request->long - 0.01, $request->long + 0.01])->get();
            $mymapattach = \App\Models\MapAttachment::get();
        }

        foreach ($mymap as $key => $val) {
            if (is_object($val)) {
                $path_val = "";
                if (isset($mymapattach)) {
                    foreach ($mymapattach AS $val_path) {
                        if ($val_path->map_id == $val->id) {
                            $path_val = $val_path->path;
                        }
                    }
                }

                $marker_data[] = array(
                    'id'            => $val->id,
                    'name'          => $val->title,
                    'type'          => 'A01',
                    'description'   => $val->desc,
                    'user_id'       => $val->user->name,
                    'reg_date'      => $val->created_at,
                    'path'          => Storage::url($path_val),
                    'lat'           => $val->latitude,
                    'long'          => $val->longitude,
                    'player_cnt'    => $val->player_count,
                    'record_cnt'    => $val->rank
                );
            }
        }

        return $marker_data;
    }

    public function search(Request $request) {
        $agent = new Agent();
	    $view_env = array('agent' => 'pc');
        $now_month_type = (int) date("m");
        $now_month_type = ($now_month_type > 6) ? 'last_half' : 'before_half';

        $view_name = 'mo.recordSearch';
        $user_rank_map_list = array();
        $view_map_id = 0;

        if (Auth::check()) {
            $service_param = array('user' => array());
            $service_param['user'][] = Auth::user();
            $sport_record_service = new SportRecordService();
            $result_user_rank_map_list = $sport_record_service->getUserMapList($service_param);
            Log::debug($service_param);
            if ($result_user_rank_map_list) {
                foreach ($result_user_rank_map_list AS $val) {
                    $user_rank_map_list[] = $val;
                }
            }
        }

        return view($view_name, [
            'my_user_attach' => '',
            'view_env' => $view_env,
            'now_month_type' => $now_month_type,
            'user_rank_map_list' => $user_rank_map_list,
            'view_map_id' => $view_map_id
        ]);
    }

}
