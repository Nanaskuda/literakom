<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Book;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $favorites = Favorite::where(
            'user_id', $user->id)
        ->with('book')->get()
        ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Login terlebih dahulu untuk menambahkan buku ke favorit.');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Buku berhasil dihapus dari favorit.');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
            ]);
            $message = 'Buku berhasil ditambahkan ke favorit.';
            }
            
            return back()->with('success', $message);
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
    public function store(StoreFavoriteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
