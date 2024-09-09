<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;

Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('post', PostController::class);
});
Route::post('/post/{id}', [PostController::class, 'update']);
Route::post('/post/store', [PostController::class, 'store'])->name('posts.store');
