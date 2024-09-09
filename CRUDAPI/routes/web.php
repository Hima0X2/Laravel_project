<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/login', function () {
    return view('login');
});
Route::get('/', function () {
    return view('allposts');
});
Route::get('/post/create', function () {
    return view('addpost');
});
Route::post('/post/store', [PostController::class, 'store'])->name('posts.store');
Route::get('/allposts', [PostController::class, 'indexForWeb'])->name('allposts');
Route::get('/post/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/post/{id}', [PostController::class, 'update'])->name('posts.update');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
