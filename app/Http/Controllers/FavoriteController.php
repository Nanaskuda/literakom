<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
   // ─── FIXED: hapus .get() sebelum paginate ─────────────────────────
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with(['book.category', 'book.reviews'])
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Login terlebih dahulu untuk menambahkan favorit.');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Buku dihapus dari favorit.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);

        return back()->with('success', 'Buku ditambahkan ke favorit!');
    }
}
