<?php
use Illuminate\Support\Facades\Route;

Route::get('/api/map', [App\Http\Controllers\MapManageController::class, 'view']);
Route::get('/api/map/test', [App\Http\Controllers\MapManageController::class, 'show']);
Route::get('/api/map/show', [App\Http\Controllers\MapManageController::class, 'show']);
Route::get('/api/map/edit', [App\Http\Controllers\MapRegisterController::class, 'view']);
//Route::post('/api/map/store', [App\Http\Controllers\MapRegisterController::class, 'store']);
Route::post('/api/map/store', [App\Http\Controllers\MapRegisterController::class, 'store']);

Route::get('/api/record', [App\Http\Controllers\RecordManagerController::class, 'view']);
Route::get('/api/record/show', [App\Http\Controllers\RecordManagerController::class, 'show']);
Route::get('/api/record/edit', [App\Http\Controllers\RecordRegisterController::class, 'view']);
Route::post('/api/record/store', [App\Http\Controllers\RecordRegisterController::class, 'store']);
