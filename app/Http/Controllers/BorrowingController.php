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
            return redirect()->route('login')
                ->with('error', 'Login dulu bro.');
        }

        if (!$book->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        // VALIDASI DURASI
        $request->validate([
            'durasi' => 'required|integer|min:1|max:7',
        ]);

        // CEK DUPLIKASI
        $exists = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('status', [
                Borrowing::STATUS_PENDING,
                Borrowing::STATUS_DIPINJAM,
            ])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah mengajukan atau sedang meminjam buku ini.');
        }

        DB::transaction(function () use ($book, $request) {
            Borrowing::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'status' => Borrowing::STATUS_PENDING,
                'catatan' => 'Durasi pinjam: ' . $request->durasi . ' hari',
            ]);
        });

        return back()->with('success', 'Pengajuan dikirim. Tunggu admin.');
    }

    // ─── APPROVE (biasanya dari Filament)
    public function approve(Borrowing $borrowing, int $durasi)
    {
        
        DB::transaction(function () use ($borrowing, $durasi) {

            $tanggalPinjam = Carbon::today();
            $tanggalKembali = $tanggalPinjam->copy()->addDays($durasi);

            $borrowing->update([
                'status' => Borrowing::STATUS_DIPINJAM,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali' => $tanggalKembali,
                'approved_at' => now(),
            ]);
        });
    }

    // ─── REJECT
    public function reject(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => Borrowing::STATUS_DITOLAK,
            'rejected_at' => now(),
        ]);
    }

    // ─── AJUKAN KEMBALI
    public function ajukanKembali(Borrowing $borrowing)
    {
        abort_if($borrowing->user_id !== Auth::id(), 403);
        abort_if(!$borrowing->isDipinjam(), 400);

        $borrowing->update([
            'catatan' => 'Pengajuan pengembalian pada ' . now()->format('d M Y H:i'),
        ]);

        return back()->with('success', 'Ajukan pengembalian berhasil.');
    }

    // ─── KONFIRMASI ADMIN
    public function konfirmasiPengembalian(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => Borrowing::STATUS_DIKEMBALIKAN,
            'tanggal_dikembalikan' => Carbon::today(),
        ]);
    }

    // ─── RIWAYAT USER
    public function riwayat()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->with('book.category')
            ->latest()
            ->paginate(10);

        $nearDue = Borrowing::where('user_id', Auth::id())
            ->where('status', Borrowing::STATUS_DIPINJAM)
            ->whereDate('tanggal_kembali', '<=', Carbon::today()->addDays(3))
            ->get();

        return view('borrowings.riwayat', compact('borrowings', 'nearDue'));
    }
}
