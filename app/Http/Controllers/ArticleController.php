<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    //
    public function show(\App\Models\Article $article, $id) {
        $article = \App\Models\Article::with('comment')->findOrFail($id);

        // 댓글 구현
        $comment_datas = array();
        if (count($article->comment) > 0) {
            $comment_datas = \App\Models\Comment::with('user')->where('article_id', $id)->get();
            foreach ($comment_datas AS $comment) {
                $comment->diffTime = compareDateTime(date('Y-m-d H:i:s'), $comment->updated_at);
            }
        }

        $attachments = DB::table('attachment')->select('path')->where('articles_id', $id)->get();
        $path_datas = array();
        foreach ($attachments as $res) {
            $url = Storage::url($res->path);
            $path_datas[] = $url;
        }

        return view('articles.show', compact('article', 'path_datas', 'comment_datas'));
    }

    public function edit(\App\Models\Article $article, $id = '') {
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
    }

    public function store(Request $request) {
        if (Auth::check() === false) {
            return redirect('home');
        }
        $id = Auth::id();
        $article = \App\Models\Article::create([
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $id
        ]);
        $photos = $request->file('photos');
        $allowedfileExtension=['pdf','jpg','png','jpeg', 'docx','svg','gif'];

        foreach ($photos as $photo) {
            if ($photo->isValid()) {
                Log::debug($photo->getClientOriginalName());

                $filename = $photo->getClientOriginalName();
                $extension = $photo->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);

                if ($check) {
                    $filepath = $photo->store('photos', 'public'); // photos 디렉토리에 저장됨, 자동으로 고유 파일명 생성함
                    DB::insert('insert into attachment (articles_id, path) values (?, ?)', [$article->id, $filepath]);
                } else {
                    Log::debug("file 체크 에러 : " . $filename . " extension : " . $extension);
                }
                /*
                    $path = $request->photo->path();
                    DB::insert('insert into attachment (articles_id, path) values (?, ?)', [$article->id, $path]);
                */
            }
        }

        flash('저장되었습니다.');
        return view('articles.show', compact('article'));
    }
}
