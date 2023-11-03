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

class RecordRegisterController extends Controller
{
    public function view(Request $request) {
        $viewName = "";
        $mapType = "swim";

        if (isset($request->mapType)) {
            $mapType = $request->mapType;
        }

        switch ($mapType) {
            case "swim":
                $viewName = "maps.swimRecordRegisterForm";
                break;
        }

        if ($viewName == "") {
            Log::debug("viewName 미생성 이슈");
        }
        $map_id = (strlen($request->map_id) > 0) ? $request->map_id : '12';
        $sport_code_selected = (isset($request->sport_code)) ? $request->sport_code : "";
        $my_user_attach = '/build/images/people_icon.png';
        return view($viewName, compact('map_id', 'sport_code_selected', 'my_user_attach'));
    }

    public function show(Request $request) {
		$res = \App\Models\SportsRecord::with('user')->get();
		$param = array();

        foreach ($res AS $val) {
            if (is_object($val)) {
                $param[] = array(
                    'id'            => $val->id,
                    'name'          => $val->title,
                    'type'          => 'swim',
                    'record'        => $val->record,
                    'map_id'        => $val->map_id,
                    'user_id'       => $val->user->name,
                    'reg_date'      => $val->created_at,
                );
            }
        }

        return $param;
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
        if ($request->type == "swim") {
            $mapManager = new SwimManager();
        }

        $result = $mapManager->store($request);

        if (false === $result) {
            flash('저장에 실패하였습니다.', 'danger');
            return redirect('/api/record');
        }

        flash('저장되었습니다.', 'success');
        return redirect('api/record?map_id=' . $request->map_id);
    }

}
