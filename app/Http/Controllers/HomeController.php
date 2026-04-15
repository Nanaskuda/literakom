<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $latestBooks = Book::where('is_active', true)
            ->with(['category', 'reviews'])
            ->latest()
            ->take(10)
            ->get();

        $popularBooks = Book::where('is_active', true)
            ->with(['category'])
            ->withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->take(6)
            ->get();

        $kategoris = Category::where('is_active', true)
            ->withCount('books')
            ->get();

        return view('home', compact('latestBooks', 'popularBooks', 'kategoris'));
    }
}
