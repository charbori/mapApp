<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    //
    public function index() {
        return view('home');
    }

    public function show(Request $request, $articleId = null) {
        $articles = Article::with('user')->with('attachment')->paginate(15);

        foreach ($articles AS $article) {
            if (count($article->attachment) > 0) {
                $article->path = Storage::url($article->attachment[0]->path);
            } else {
                $article->path = "photos/photo_64.png";
            }
        }

        return view('home', compact('articles'));
    }
}
