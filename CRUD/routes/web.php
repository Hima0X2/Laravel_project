<?php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/products/create', [ProductController::class,'create'])->name('create');
Route::post('/products', [ProductController::class,'store'])->name('store');
Route::get('/products', [ProductController::class, 'index'])->name('index');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('destroy');
