<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;


// Route Publik

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/katalog', [BookController::class, 'index'])->name('books.index');

Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/kontak', function () {
    return view('contact');
})->name('contact');

// Route Member
Route::middleware('auth')->group(function () {
    Route::post('/pinjam/{book}', [BorrowingController::class, 'store'])->name('Borrowings.store');
    Route::patch('/kembalikan/{Borrowing}', [BorrowingController::class, 'kembalikan'])->name('Borrowings.return');
    Route::get('/riwayat', [BorrowingController::class, 'riwayat'])->name('Borrowings.history');

    Route::post('/favorit/{book}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::post('/review/{book}', [ReviewController::class, 'store'])->name('reviews.store');
});


