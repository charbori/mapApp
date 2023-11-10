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
use App\Events\AlarmRead;
use App\Jobs\AlarmJob;

class FollowController extends Controller
{
    //
    public function index() {
        return view('home');
    }

    public function view(Request $request) {
        $view = "sns.user_follower";
        $guest_id = Auth::check() ? Auth::user()->id : 0;
        $user_data = User::with("user_attachment")->where("id", $request->id)
                    ->first();
        $result_rank_list = array();
        $my_user_attach = array();

        $follow_cnt = DB::table('follow')
                            ->where('user_id', '=', $user_data['id'])
                            ->count('id');

        $follower_cnt = DB::table('follow')
                            ->where('follower', '=', $user_data['id'])
                            ->count('id');
        $badge_cnt = 0;

        $is_followed = DB::table('follow')
                            ->where ('follower', '=', $request->id)
                            ->count('id');

        $is_my_follow = $guest_id == $request->id;

        if ((int) date('m') > 6) {
            $month_type = ">";
        } else {
            $month_type = "<";
        }

        if ($user_data['id'] > 0) {
            $user_data_param = collect([]);
            if (!$user_data->isEmpty()) {
                foreach($user_data AS $val) {
                    $user_data_param = $val;
                }
            }
            $service_param = array('user' => $user_data_param,
                                    'month_type' => $month_type,
                                    'year' => date("Y"),
                                    'skip' => 0);
            $service_param['sport_code'][0] = '50';
            $service_param['sport_code'][1] = '100';
            $sport_record_service = new SportRecordService();
            $result_user_rank_map_list = $sport_record_service->getUserMapList($service_param);

            $t_result_rank_list = array();
            foreach ($result_user_rank_map_list AS $res_map_data) {
                $service_param['map_id'] = $res_map_data->map_id;
                $t_result_rank_list[] = $sport_record_service->getUserRecord($service_param);
            }

            if (count($t_result_rank_list) > 0) {
                foreach ($t_result_rank_list[0]['res'] AS $rank_list_user) {
                    $result_rank_list[] = $rank_list_user;
                }
            }

            $res_user_attach = DB::table('user_attachment')
                                    ->where("user_id", '=', $user_data['id'])
                                    ->get();
            if (count($res_user_attach) > 0 && strlen($res_user_attach[0]->path) > 0) {
                $my_user_attach = $res_user_attach[0]->path;
                $my_user_attach = (strpos($my_user_attach, "public") !== false) ?
                                    str_replace("public", "/storage", $my_user_attach) : $my_user_attach;
                $my_user_attach = $my_user_attach;
            }
        }

        $user_data['path'] = "/build/images/people_icon.png";
        if (strlen($user_data['user_attachment']->path) > 0) {
            $user_data['path'] = (strpos($user_data['user_attachment']->path, "public") !== false) ?
            str_replace("public", "/storage", $user_data['user_attachment']->path) : $user_data['path'];
        }


        if ($request->has('alarm_id')) {
            $send_msg = DB::table('alarm')->where('id',$request->alarm_id)->first();
            AlarmRead::dispatch($send_msg);
        }

        return view($view, [
            'user_data' => $user_data,
            'my_user_attach' => $user_data['path'],
            'follow_cnt' => $follow_cnt,
            'follower_cnt' => $follower_cnt,
            'badge_cnt' => $badge_cnt,
            'result_rank_list' => $result_rank_list,
            'my_user_attach' => $my_user_attach,
            'is_followed' => $is_followed,
            'is_my_follow' => $is_my_follow
        ]);
    }

    public function store(Request $request) {
        if ($request->type == 'follow') {
            $res = DB::table('follow')->insert([
                'user_id' => Auth::user()->id,
                'reg_date' => date('Y-m-d H:i:s'),
                'follower' => $request->user_id,
            ]);
        } else {
            $res = DB::table('follow')->where('user_id', Auth::user()->id)
            ->where('follower', $request->user_id)
            ->delete();
        }

        return $res;
    }
}
