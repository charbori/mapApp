<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;
use App\Http\Controllers\MapManagerInterface;

class BirdManager implements MapManagerInterface
{
    function __construct() {

    }

    public function view() {

    }

    public function store(Request $request) {
        $id = (Auth::check()) ? Auth::user()->id : $request->id;
        if ($id == "") {
            return false;
        }
        $map_list = \App\Models\MapList::create([
            "title" => $request->title,
            "type" => $request->type,
            "desc" => $request->desc,
            "longitude" => $request->longitude,
            "latitude" => $request->latitude,
            "user_id" => $id,
            'address' => $request->map_address,
            "attachment" => "Y"
        ]);
        $photos = $request->file('photos');
        $allowedfileExtension=['pdf','jpg','png','jpeg', 'docx','svg','gif'];

        foreach ($photos as $photo) {
            if ($photo->isValid()) {
                $filename = $photo->getClientOriginalName();
                $extension = $photo->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);

                if ($check) {
                    $filepath = $photo->store('public/photos/', 'local');
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

    public function edit(Request $request) {

    }
}
?>
