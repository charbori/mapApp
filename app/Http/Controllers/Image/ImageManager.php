<?php

namespace App\Http\Controllers\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ImageManager
{
    function __construct() {

    }

    public function delete($user_id) {
        DB::table('user_attachment')->where('user_id',$user_id)->delete();
    }

    public function store($photos, $user_id) {
        $allowedfileExtension=['pdf','jpg','png','jpeg', 'docx','svg','gif'];

        $photo = $photos[0];

        if ($photo->isValid()) {
            $filename = $photo->getClientOriginalName();
            $extension = $photo->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);

            if ($check) {
                $filepath = $photo->store('public/photos/', 'local');

                $filepath = str_replace("public", "/storage", $filepath);
                DB::table('user_attachment')->updateOrInsert(
                    array(  'user_id'    => $user_id,
                            'path'      => $filepath
                ));
            } else {
                Log::debug("file 체크 에러 : " . $filename . " extension : " . $extension);
            }
        }
        return true;
    }

}
?>
