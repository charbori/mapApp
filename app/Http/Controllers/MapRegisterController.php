<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BirdManager;

use App\Http\Controllers\SwimManager;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

class MapRegisterController extends Controller
{
    public function view(Request $request) {
        if (Auth::check() === false) {
            flash('로그인 후 등록이 가능합니다.');
            return redirect('api/map?view_type=map');
        }

        $agent = new Agent();
        $view_env = array('agent' => 'pc');
        $viewName = "";
        $mapType = "";

        $mapStoreData = array(
            'longitude' => '',
            'latitude'  => '',
            'address'   => '',
            'tag'       => ''
        );

        if (strlen($request->longitude) > 0) {
            $mapStoreData['longitude'] = $request->longitude;
        }

        if (strlen($request->latitude) > 0) {
            $mapStoreData['latitude'] = $request->latitude;
        }

        if (strlen($request->map_address) > 0) {
            $mapStoreData['map_address'] = $request->map_address;
        }

        if ($agent->isMobile()) {
            $view_name = 'mo.recordList';
            $view_env['agent'] = 'mobile';
        }
        if ($this->is_register_param_err($request)) {
            abort(404);
        }

        if (isset($request->mapType)) {
            $mapType = $request->mapType;
        }

        switch ($mapType) {
            case "map":
                $viewName = "maps.birdRegisterForm";
                break;
            case "swim":
                $viewName = "maps.swimRegisterForm";
                break;
        }

        $my_user_attach = '/build/images/people_icon.png';

        return view($viewName, compact('view_env', 'mapStoreData', 'my_user_attach'));
    }

    public function is_register_param_err(Request $request) {
        if (empty($request->longitude) || empty($request->latitude)) {
            return true;
        }
        return false;
    }

    public function show(Request $request) {
		$mymap = \App\Models\MapList::with('user')->get();
		$mymapattach = \App\Models\MapAttachment::get();
		$marker_data = array();

        foreach ($mymap as $key => $val) {
            if (is_object($val)) {
                $path_val = "";
                if (!$mymapattach->isEmpty()) {
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
                    'path'          => Storage::path($path_val),
                    'lat'           => $val->latitude,
                    'long'          => $val->longitude
                );
            }
        }

        return $marker_data;
    }

    //public function edit(\App\Models\Article $article, $id = '') {
    public function edit(Request $request) {
	    /*
        if (Auth::check() === false) {
            flash("로그인 후 이용가능합니다.");
            return redirect('home');
        }
        $data = array(  "title" => "",
                        "content" => "");
        $article = \App\Models\Article::findOr($id, function() {
            return view('articles.edit', ["title" => "",
                                        "content" => ""]);
        });
        $data["title"] = $article->title;
        $data["content"] = $article->content;
        return view('articles.edit', $data);
	    */
    }

    public function store(Request $request) {
        if (Auth::check() === false) {
            flash('로그인 후 등록이 가능합니다.');
            return redirect('api/map');
        }

        if ($request->type == "map") {
            $mapManager = new BirdManager();
        } else if ($request->type == "swim") {
            $mapManager = new SwimManager();
        }

        $result = $mapManager->store($request);

        if (false === $result) {
            flash('저장에 실패하였습니다.');
            return redirect('api/map');
        }

        flash('저장되었습니다.');
	    $viewEnv = array();
        return redirect('api/map');
    }

    public function mapStore(Request $request) {
        $mapManager = new SwimManager();
        $result = $mapManager->mapStore($request);

        if (false === $result) {
            flash('저장에 실패하였습니다.');
            return redirect('api/map');
        }

        flash('저장되었습니다.');
	    $viewEnv = array();
        return redirect('api/map');
    }
}
