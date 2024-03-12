<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;

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
    return view('main');
});



Route::get('/member/login', [ MemberController::class, 'login']);

Route::get('/member/register', [ MemberController::class, 'register']);

Route::get('/member',[ MemberController::class, 'index']);

Route::get('/member/profile', [ MemberController::class, 'profile']);

//books

Route::get('/book/approval', [BookController::class, 'approval'])->name('book-approval');

Route::get('/book/mybook', [BookController::class, 'mybook'])->name('book-mybook');

Route::get('/moodbased', [BookController::class, 'mood'])->name('book-mood');

Route::get('/book/create', [ BookController::class, 'create'])->name('book-create');

Route::post('/book', [BookController::class, 'store'])->name('book-store');

Route::get('/books', [ BookController::class, 'index'])->name('book-listing');

Route::get('/book/{id}', [ BookController::class, 'show'])->name('book-single');

Route::get('/book/{id}/edit', [ BookController::class, 'edit'])->name('book-edit');

Route::post('/book/{id}', [ BookController::class, 'update'])->name('book-update');

Route::delete('/book/{id}', [ BookController::class, 'destroy'])->name('book-destroy');

Route::get('/books/search', [BookController::class, 'search'])->name('book-search');






// Route::view('dashboard', 'dashboard')
// 	->name('dashboard')
// 	->middleware(['auth', 'verified']);

Route::view('home', 'home')
	->name('home')
	->middleware(['auth', 'verified']);


