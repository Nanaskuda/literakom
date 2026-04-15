<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
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
            ->when($request->sort === 'az',      fn($q) => $q->orderBy('judul'))
            ->when(!in_array($request->sort, ['populer', 'az']), fn($q) => $q->latest())
            ->with(['category', 'reviews'])
            ->paginate(18);

        $kategoris = Category::where('is_active', true)->get();

        return view('books.index', compact('books', 'kategoris'));
    }

    public function show(Book $book)
    {
        $book->load(['category', 'reviews.user', 'borrowings']);

        $user = auth()->user();

        $isAvailable = $book->isAvailable();

        $userHasBorrowed = $user
            ? $book->borrowings()
                ->where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->exists()
            : false;

        $userHasFavorited = $user
            ? $book->favorites()->where('user_id', $user->id)->exists()
            : false;

        return view('books.show', compact(
            'book', 'isAvailable', 'userHasBorrowed', 'userHasFavorited'
        ));
    }
}
