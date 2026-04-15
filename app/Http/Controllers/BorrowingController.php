<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BorrowingController extends Controller
{

    public function store(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Login terlebih dahulu untuk meminjam buku.');
        }

        if (!$book->isAvailable()) {
            return back()->with('error', 'Maaf, buku sedang tidak tersedia.');
        }

        $sudahPinjam = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Kamu sudah meminjam buku ini.');
        }

        DB::transaction(function () use ($book) {
            Borrowing::create([
                'user_id'         => Auth::id(),
                'book_id'         => $book->id,
                'tanggal_pinjam'  => Carbon::today(),
                'tanggal_kembali' => Carbon::today()->addDays(7),
                'status'          => 'dipinjam',
            ]);
            $book->decrement('stok');
        });

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Buku berhasil dipinjam! Harap kembalikan dalam 7 hari.');
    }

    public function kembalikan(Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status'               => 'dikembalikan',
                'tanggal_dikembalikan' => Carbon::today(),
            ]);
            $borrowing->book()->increment('stok');
        });

        return redirect()
            ->route('books.show', $borrowing->book)
            ->with('success', 'Buku berhasil dikembalikan. Terima kasih!');
    }

    // ─── RIWAYAT (bug fixed: $borrowing → $borrowings) ───────────────
    public function riwayat()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->with('book.category')
            ->latest()
            ->paginate(10);

        return view('borrowings.riwayat', compact('borrowings'));
    }
}
