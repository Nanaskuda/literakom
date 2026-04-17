@extends('layouts.app')
@section('title', 'Favorit Saya — Literakom')

@section('content')
<div style="background:var(--cream); min-height:100vh;">

    {{-- ════ HEADER ════ --}}
    <div style="background:var(--forest);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-16">
            <nav class="flex items-center gap-2 text-xs mb-5" style="color:rgba(255,255,255,0.4);">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white">Favorit Saya</span>
            </nav>
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Koleksiku</p>
            <div class="flex items-end justify-between">
                <h1 class="font-display text-4xl font-bold text-white">Buku Favorit</h1>
                @if ($favorites->total() > 0)
                    <p class="text-sm pb-1" style="color:rgba(255,255,255,0.5);">
                        {{ $favorites->total() }} buku tersimpan
                    </p>
                @endif
            </div>
        </div>
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" class="w-full h-10 block">
            <path d="M0,20 C480,40 960,0 1440,20 L1440,40 L0,40 Z" fill="#f7f3ed"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if ($favorites->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-28 rounded-3xl"
                 style="background:white; border:1px solid rgba(26,46,26,0.08);">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
                     style="background:rgba(239,68,68,0.08);">
                    <svg class="w-10 h-10" style="color:rgba(239,68,68,0.4);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold mb-2" style="color:var(--forest);">Belum ada favorit</h3>
                <p class="text-sm mb-8 max-w-sm mx-auto" style="color:var(--muted);">
                    Simpan buku yang kamu suka dengan menekan ikon hati di halaman detail buku.
                </p>
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl text-sm font-bold transition hover:opacity-90"
                   style="background:var(--copper); color:var(--forest);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Katalog
                </a>
            </div>

        @else
            {{-- Grid buku favorit --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                @foreach ($favorites as $fav)
                    @php $book = $fav->book; @endphp
                    @if ($book)
                        <div class="group relative">
                            {{-- Tombol hapus favorit (pojok kanan atas) --}}
                            <form method="POST"
                                  action="{{ route('favorites.toggle', $book) }}"
                                  class="absolute top-2.5 right-2.5 z-10">
                                @csrf
                                <button type="submit"
                                        title="Hapus dari favorit"
                                        class="w-8 h-8 rounded-full flex items-center justify-center
                                               opacity-0 group-hover:opacity-100 transition-all
                                               hover:scale-110 shadow-lg"
                                        style="background:rgba(239,68,68,0.9);"
                                        onclick="return confirm('Hapus buku ini dari favorit?')">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>

                            {{-- Book card --}}
                            <a href="{{ route('books.show', $book) }}"
                               class="block bg-white rounded-2xl overflow-hidden transition-all duration-300
                                      hover:-translate-y-1.5 hover:shadow-xl lg:flex flex-col h-full"
                               style="border:1px solid rgba(26,46,26,0.08);">

                                {{-- Cover --}}
                                <div class="relative overflow-hidden aspect-[3/4]" style="background:var(--cream2);">
                                    @if ($book->cover)
                                        <img src="{{ Storage::url($book->cover) }}"
                                             alt="{{ $book->judul }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"
                                             style="background:linear-gradient(135deg, var(--forest), #3a5a3a);">
                                            <svg class="w-12 h-12 opacity-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Kategori badge --}}
                                    <div class="absolute bottom-0 left-0 right-0 p-2.5"
                                         style="background:linear-gradient(to top, rgba(0,0,0,0.6), transparent);">
                                        <span class="text-white text-xs font-semibold truncate block">
                                            {{ $book->category?->nama ?? '—' }}
                                        </span>
                                    </div>

                                    {{-- Tersedia/tidak --}}
                                    <div class="absolute top-2.5 left-2.5">
                                        @if ($book->isAvailable())
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-lg"
                                                  style="background:rgba(34,197,94,0.9); color:white;">✓</span>
                                        @else
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-lg"
                                                  style="background:rgba(239,68,68,0.9); color:white;">✗</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Info --}}
                                <div class="p-3.5 flex-1">
                                    <h3 class="text-sm font-bold line-clamp-2 leading-snug mb-1"
                                        style="color:var(--forest);">
                                        {{ $book->judul }}
                                    </h3>
                                    <p class="text-xs truncate" style="color:var(--muted);">{{ $book->penulis }}</p>

                                    {{-- Rating --}}
                                    @if ($book->reviews && $book->reviews->count() > 0)
                                        <div class="flex items-center gap-1 mt-2.5 pt-2.5"
                                             style="border-top:1px solid var(--cream2);">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <svg class="w-3 h-3 {{ $s <= round($book->averageRating()) ? '' : 'opacity-20' }}"
                                                     style="color:var(--copper);" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                            <span class="text-xs ml-0.5" style="color:var(--muted);">
                                                {{ $book->averageRating() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $favorites->links() }}
            </div>

            {{-- Link katalog --}}
            <div class="mt-6 text-center">
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold transition group"
                   style="color:var(--copper);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Jelajahi lebih banyak buku
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
