<?php

use Illuminate\Support\Facades\Route;
use Jenssegers\Agent\Agent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/api/map');
});

$agent = new Agent();
if ($agent->isMobile()) {
    require_once __DIR__.'/route_mo.php';
}

Route::redirect('/home', '/api/map');
Route::redirect('/dashboard', '/api/map');

Route::get('/test', function () {
    return view('test');
});
Route::get('/api/map', [App\Http\Controllers\MapManageController::class, 'view'])->name('dashboard');
Route::get('/api/map/test', [App\Http\Controllers\MapManageController::class, 'show']);
Route::get('/api/map/show', [App\Http\Controllers\MapManageController::class, 'show']);
Route::get('/api/map/edit', [App\Http\Controllers\MapRegisterController::class, 'view']);
//Route::post('/api/map/store', [App\Http\Controllers\MapRegisterController::class, 'store']);
Route::post('/api/map/store', [App\Http\Controllers\MapRegisterController::class, 'store']);
Route::post('/api/map/mapStore', [App\Http\Controllers\MapRegisterController::class, 'mapStore']);

Route::get('/api/search', [App\Http\Controllers\MapManageController::class, 'search']);
Route::get('/api/record', [App\Http\Controllers\RecordManagerController::class, 'view']);
Route::get('/api/record/show', [App\Http\Controllers\RecordManagerController::class, 'show']);
Route::get('/api/record/edit', [App\Http\Controllers\RecordRegisterController::class, 'view']);
Route::post('/api/record/store', [App\Http\Controllers\RecordRegisterController::class, 'store']);
Route::get('/api/record/mypage', [App\Http\Controllers\RecordManagerController::class, 'getUserRecordMypage']);
Route::get('/api/record/ranking', [App\Http\Controllers\RecordManagerController::class, 'getRecordRanking']);


Route::get('/api/follow', [App\Http\Controllers\FollowController::class, 'view']);
Route::post('/api/follow', [App\Http\Controllers\FollowController::class, 'store']);

Route::get('/mypage/record', [App\Http\Controllers\RecordManagerController::class, 'mypage']);
Route::get('/ranking', [App\Http\Controllers\RankingController::class, 'view']);

Route::get('/articles/show/{id}', [App\Http\Controllers\ArticleController::class, 'show']);
Route::get('/articles/edit', [App\Http\Controllers\ArticleController::class, 'edit']);
Route::get('/articles/edit/{id}', [App\Http\Controllers\ArticleController::class, 'edit']);
Route::get('/articles/search', [App\Http\Controllers\ArticleController::class, 'show']);
Route::post('/articles', [App\Http\Controllers\ArticleController::class, 'store']);

Route::post('/reply/store', [App\Http\Controllers\CommentController::class, 'store']);
Route::put('/reply/edit', [App\Http\Controllers\CommentController::class, 'edit']);
Route::delete('/reply/del', [App\Http\Controllers\CommentController::class, 'remove']);

Route::post('/api/user/update', [App\Http\Controllers\Auth\UpdateUserController::class, 'store']);
Route::get('/api/user/update', [App\Http\Controllers\Auth\UpdateUserController::class, 'view'])->name('update');

Route::get('/api/alarm', [App\Http\Controllers\AlarmController::class, 'show']);
Route::get('/metrics', [App\Http\Controllers\PrometheusMetrics::class, 'registerMetrics']);

require __DIR__.'/auth.php';
