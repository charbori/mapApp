<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request) {
        if (Auth::check() === false) {
            flash("로그인 후 이용가능합니다.");
            return '로그인 후 이용가능합니다.';
        }

        // validate 실패시 form 제출화면으로 이동한다. (자동 뒤로가기)
        // validator::make 수동 생성시에는 validate() 추가시 가능하다.
        $validator = Validator::make($request->all(), [
            'reply_content' => 'required',
            'article_id' => 'required'
        ]);

        if ($validator->fails()) {
            Log::info('DB comment create validator fail');
            return 'validate fail';
        }

        $result = \App\Models\Comment::create([
            "content" => $request->reply_content,
            "user_id" => Auth::id(),
            "article_id" => $request->article_id
        ]);

        if ($result) {
            return 'success';
        } else {
            Log::error('DB comment create error');
            return "fail";
        }
    }

    public function edit(Request $request) {

        if (Auth::check() === false) {
            flash("로그인 후 이용가능합니다.");
            return '로그인 후 이용가능합니다.';
        }

        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
            'article_id' => 'required',
            'reply_content' => 'required',
        ]);

        if ($validator->fails()) {
            Log::info('DB comment edit validator fail');
            return 'edit fail';
        }
        Log::info('DB comment edit run '. $request->article_id . ' ' . $request->comment_id . ' ' . $request->reply_content);

        $result =\App\Models\Comment::where('article_id', $request->article_id)->find($request->comment_id);

        if ($result) {
            $result->update(['content' => $request->reply_content]);
            return json_encode(array('result' => 'success', 'data' => $request->reply_content));
        } else {
            return json_encode(array('result' => 'fail', 'data' => ''));
        }
    }

    public function remove(Request $request) {

        if (Auth::check() === false) {
            flash("로그인 후 이용가능합니다.");
            return '로그인 후 이용가능합니다.';
        }

        $validator = Validator::make($request->all(), [
            'comment_id' => 'required',
            'article_id' => 'required'
        ]);

        if ($validator->fails()) {
            Log::info('DB comment delete validator fail');
            return 'delete fail';
        }
        Log::info('DB comment delete run '. $request->article_id . ' ' . $request->comment_id);

        $result =\App\Models\Comment::where('article_id', $request->article_id)->find($request->comment_id);
        if ($result) {
            $result->delete();
            return 'success';
        } else {
            return 'fail';
        }
    }

}
