<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books', BookController::class)
->only(['index', 'show', 'create','store', 'destroy']);

Route::resource('books.reviews', ReviewController::class)
->scoped(['review' => 'book'])
->only(['create','store']);
