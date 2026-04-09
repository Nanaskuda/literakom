<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Http\Requests\UpdateBorrowingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Login terlebih dahulu untuk meminjam buku.');
        }

        if (!$book->isAvailable()){
            return back()->with('error', 'Maaf, Buku sedang tidak tersedia.');
        }

        $sudahPinjam = Borrowing::where('user_id', Auth::id())
        ->where ('book_id', $book->id)
        ->where('status', 'dipinjam')
        ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam buku ini.');
        }

        DB::transaction(function () use ($book) {
            Borrowing::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'tanggal_pinjam' => Carbon::today(),
                'tanggal_kembali' => Carbon::today()->addDays(7),
                'status' => 'dipinjam',
            ]);

            $book->decrement('stok');

        });
        return redirect()->route('books.show', $book)->with('success', 'Buku berhasil dipinjam. Harap kembalikan dalam 7 hari.');
    }

    public function kembalikan (Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => Carbon::today(),
            ]);

            $borrowing->book()->increment('stok');
        });
        return redirect()->route('books.show', $borrowing->book)->with('success', 'Buku berhasil dikembalikan. Terima kasih!');
    }

    public function riwayat()
    {
        $user = Auth::user();
        $borrowing = Borrowing::where('user_id', $user->id)
        ->with('book')
        ->latest()
        ->paginate(10);

        return view('borrowings.riwayat', compact('borrowings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        //
    }
}
