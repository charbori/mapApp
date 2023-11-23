<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\MapManagerInterface;

class SwimManager implements MapManagerInterface
{
    function __construct() {

    }

    public function view() {

    }

    public function store(Request $request) {
        $id = (Auth::check()) ? Auth::id() : $request->id;
        $sport_code = (empty($request->sport_code)) ? "50" : $request->sport_code;

        $sports_record = \App\Models\SportsRecord::create([
            "type" => $request->type,
            "record" => $request->record,
            "user_id" => $id,
            "map_id" => $request->map_id,
            "sport_code" => (string) $sport_code
        ]);

        return true;
    }

    public function edit(Request $request) {

    }

    public function mapStore(Request $request) {
        if (Auth::check() === false) {
            //return false;
        }
        $id = Auth::id();
        $map_list = \App\Models\MapList::create([
            "title" => $request->title,
            "type" => 'swim',
            "desc" => $request->desc,
            "longitude" => $request->longitude,
            "latitude" => $request->latitude,
            "user_id" => $id,
            "attachment" => "Y",
            "rank" => "0",
            "player_count" => "0",
            "address" => $request->map_address,
            "tag" => ""
        ]);
        $photos = $request->file('photos');
        $allowedfileExtension=['pdf','jpg','png','jpeg', 'docx','svg','gif'];

        foreach ($photos as $photo) {
            if ($photo->isValid()) {
                $filename = $photo->getClientOriginalName();
                $extension = $photo->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);

                if ($check) {
                    $filepath = date("Ymd") . str_random(32);
                    Storage::disk('minio')->put($filepath, '/');
                    if ($filepath == false) {
                        return false;
                    }
                    $map_attachment_id = DB::table('map_attachemnt')->insertGetId(
                        array(  'map_id'    => $map_list->id,
                                'path'      => $filepath
                    ));
                    \App\Models\MapList::where("id", $map_list->id)->update([
                        "attachment" => $map_attachment_id
                    ]);
                } else {
                    Log::debug("file 체크 에러 : " . $filename . " extension : " . $extension);
                }
            }
        }
        return true;
    }
}
?>
