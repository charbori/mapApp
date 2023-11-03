<?php

namespace App\Http\Controllers;

use Monolog\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BirdManager;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\SwimManager;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;

class RankingController extends Controller
{
    public function view(Request $request) {
        $viewName = "ranking.ranking";
        $ranking_data = array();

        $response = Http::get('http://172.30.1.92:8080/ranking');

        if ($response->ok() && count($response->json()) > 0) {
            $ranking_data = $response->json();
            Log::debug($response->json());
        }

        return view($viewName, compact('ranking_data'));
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

    public function edit(Request $request) {


    }

    public function store(Request $request) {

    }

}
