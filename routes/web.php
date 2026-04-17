<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


// ─── Publik ────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [BookController::class, 'index'])->name('books.index');
Route::get('/buku/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/tentang', fn() => view('about'))->name('about');
Route::get('/kontak', fn() => view('contact'))->name('kontak');

// Route Member
Route::middleware('auth')->group(function () {
    Route::post('/pinjam/{book}', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/kembalikan/{Borrowing}', [BorrowingController::class, 'ajukanKembali'])->name('borrowings.ajukanKembali');
    Route::get('/riwayat', [BorrowingController::class, 'riwayat'])->name('borrowings.riwayat');

    Route::post('/favorit/{book}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');

    Route::post('/review/{book}', [ReviewController::class, 'store'])->name('reviews.store');
});


