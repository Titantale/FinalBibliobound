<?php


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ExtendController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\RateController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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


//Email
Route::post('/send-notification/{id}', [EmailController::class, 'sendNotification'])->name('send-notification');
Route::post('/sendallnotification', [EmailController::class, 'sendallnotification'])->name('sendallnotification');
Route::post('/custom-notification', [EmailController::class, 'customnotification'])->name('custom-notification');


//Borrow
Route::get('/book/approval', [BorrowController::class, 'approval'])->name('book-approval');
Route::post('/book/{id}/borrow', [BorrowController::class, 'borrowBook'])->name('borrow-book');
Route::post('/borrow/{id}/approve', [BorrowController::class, 'approveBorrow'])->name('approve-borrow');
Route::post('/borrow/{id}/reject', [BorrowController::class, 'rejectBorrow'])->name('reject-borrow');

//Extend
Route::post('/borrow/{id}/request-extension', [ExtendController::class, 'requestExtension'])->name('request-extension');
Route::post('/borrow/{id}/extendapprove', [ExtendController::class, 'extendapprove'])->name('extend-approval');
Route::post('/borrow/{id}/extendreject', [ExtendController::class, 'extendreject'])->name('reject-extend');
Route::get('/extend-approval', [ ExtendController::class, 'approveextend'])->name('approve-extend');

//Mood
Route::get('/moodbased', [MoodController::class, 'mood'])->name('book-mood');
Route::match(['get', 'post'], '/recommend-book', [MoodController::class, 'recommendBook'])->name('recommend-book');
Route::get('/next-recommendation', [ MoodController::class, 'nextRecommendation'])->name('next_recommendation');

//Rate&Review
Route::get('/return/{id}', [ RateController::class, 'rateandreview'])->name('book-rateandreview');
Route::post('/return/{id}/updaterate', [ RateController::class, 'updaterate'])->name('book-updaterate');




//books
Route::get('/book/notify', [BookController::class, 'notify'])->name('book-notify');
Route::get('/book/borrowed', [BookController::class, 'borrowed'])->name('book-borrowed');
Route::get('/book/pending', [BookController::class, 'pending'])->name('book-pending');
Route::get('/book/rejected', [BookController::class, 'rejected'])->name('book-rejected');
Route::get('/book/history', [BookController::class, 'history'])->name('book-history');
Route::get('/book/create', [ BookController::class, 'create'])->name('book-create');
Route::post('/book', [BookController::class, 'store'])->name('book-store');
Route::get('/books', [ BookController::class, 'index'])->name('book-listing');
Route::get('/book/{id}', [ BookController::class, 'show'])->name('book-single');
Route::get('/book/{id}/edit', [ BookController::class, 'edit'])->name('book-edit');
Route::post('/book/{id}', [ BookController::class, 'update'])->name('book-update');
Route::delete('/book/{id}', [ BookController::class, 'destroy'])->name('book-destroy');
Route::get('/books/search', [BookController::class, 'search'])->name('book-search');

Route::get('/recommendations', [BookController::class, 'generateRecommendations'])->name('recommendations');



Route::get('home', [App\Http\Controllers\BookController::class, 'homeindex'])
    ->name('home')
    ->middleware(['auth', 'verified']);

    Route::get('/testingg', function () {
        return view('books.notify');
    });




    

/* Route to display notice that user should verify email first before can proceed*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

/* Route to handle requests generated when the user clicks the email verification link in the email*/
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

/* Route when user request to resend a verification link if the user accidentally loses the first verification link*/
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


