<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = Book::query()
        ->where('is_active', true)
        ->when($request->search,   fn($q) => $q->search($request->search))
        ->when($request->kategori, fn($q) => $q->byKategori($request->kategori))
        ->when($request->sort === 'populer', fn($q) => $q->populer())
        ->when($request->sort === 'terbaru', fn($q) => $q->latest())
        ->with(['category', 'reviews'])
        ->paginate(12);

    $kategoris = Category::where('is_active', true)->get();

    return view('books.index', compact('books', 'kategoris'));
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
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['reviews.user', 'borrowings']);
        $user = Auth::user();

        $isAvailable = $book->isAvailable();
        $userHasBorrowed = $user
            ? $book->borrowings()->where('user_id', $user->id)
                    ->where('status', 'dipinjam')->exists()
            : false;

        $userHasFavorited = $user
            ? $book->favorites()->where('user_id', $user->id)->exists()
            : false;

        return view('books.show', compact(
            'book', 'isAvailable', 'userHasBorrowed', 'userHasFavorited'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
