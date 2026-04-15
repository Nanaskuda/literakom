@extends('layouts.app')
@section('title', 'Katalog Buku — Literakom')

@section('content')
<div style="background:var(--cream); min-height:100vh;">

    {{-- ════ HEADER ════ --}}
    <div style="background:var(--forest);" class="pb-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs mb-6" style="color:rgba(255,255,255,0.4);">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white">Katalog</span>
            </nav>

            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Perpustakaan</p>
            <h1 class="font-display text-4xl lg:text-5xl font-bold text-white mb-8">Katalog Buku</h1>

            {{-- Search bar --}}
            <form method="GET" action="{{ route('books.index') }}" id="filterForm">
                <div class="flex gap-2 p-1.5 rounded-2xl max-w-2xl mb-5"
                     style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15);">
                    <div class="flex-1 flex items-center gap-3 px-4">
                        <svg class="w-4.5 h-4.5 flex-shrink-0 w-5 h-5" style="color:rgba(255,255,255,0.5);"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul, penulis, atau kategori..."
                               class="flex-1 py-3 text-sm bg-transparent focus:outline-none text-white placeholder-white/40">
                    </div>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90"
                            style="background:var(--copper); color:var(--forest);">
                        Cari
                    </button>
                </div>

                {{-- Filter pills --}}
                <div class="flex flex-wrap gap-2.5 items-center">

                    {{-- Kategori --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition"
                                style="background:rgba(255,255,255,{{ request('kategori') ? '0.2' : '0.1' }});
                                       color:{{ request('kategori') ? 'var(--copper)' : 'rgba(255,255,255,0.75)' }};
                                       border:1px solid rgba(255,255,255,0.15);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ request('kategori') ? $kategoris->firstWhere('slug', request('kategori'))?->nama ?? 'Kategori' : 'Semua Kategori' }}
                            <svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                             class="absolute top-full left-0 mt-2 w-52 rounded-2xl shadow-2xl py-2 z-30 overflow-hidden"
                             style="background:var(--forest2); border:1px solid rgba(255,255,255,0.1);">
                            <a href="{{ route('books.index', array_merge(request()->except('kategori', 'page'), [])) }}"
                               class="block px-4 py-2.5 text-sm transition {{ !request('kategori') ? 'font-semibold text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                                Semua Kategori
                            </a>
                            @foreach ($kategoris as $kat)
                                <a href="{{ route('books.index', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat->slug])) }}"
                                   class="block px-4 py-2.5 text-sm transition {{ request('kategori') === $kat->slug ? 'font-semibold text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                                    {{ $kat->nama }}
                                    <span class="ml-1 text-xs opacity-50">({{ $kat->bookCount() }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition"
                                style="background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.75);
                                       border:1px solid rgba(255,255,255,0.15);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                            </svg>
                            @php
                                $sortLabel = ['populer' => 'Populer', 'az' => 'A — Z', 'terbaru' => 'Terbaru'];
                            @endphp
                            {{ $sortLabel[request('sort')] ?? 'Terbaru' }}
                            <svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                             class="absolute top-full left-0 mt-2 w-44 rounded-2xl shadow-2xl py-2 z-30"
                             style="background:var(--forest2); border:1px solid rgba(255,255,255,0.1);">
                            @foreach ([''=>'Terbaru', 'populer'=>'Populer', 'az'=>'A — Z'] as $val => $lbl)
                                <a href="{{ route('books.index', array_merge(request()->except('sort', 'page'), $val ? ['sort' => $val] : [])) }}"
                                   class="block px-4 py-2.5 text-sm transition {{ request('sort', '') === $val ? 'font-semibold text-white' : 'text-white/60 hover:text-white hover:bg-white/10' }}">
                                    {{ $lbl }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Reset --}}
                    @if (request()->hasAny(['search', 'kategori', 'sort']))
                        <a href="{{ route('books.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm transition hover:bg-white/20"
                           style="color:rgba(255,255,255,0.5); border:1px solid rgba(255,255,255,0.1);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- wave --}}
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" class="w-full h-10 block">
            <path d="M0,20 C480,40 960,0 1440,20 L1440,40 L0,40 Z" fill="#f7f3ed"/>
        </svg>
    </div>

    {{-- ════ CONTENT ════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Result meta --}}
        <div class="flex items-center justify-between mb-8">
            <p class="text-sm" style="color:var(--muted);">
                Menampilkan
                <span class="font-semibold" style="color:var(--forest);">{{ $books->total() }}</span> buku
                @if (request('search'))
                    untuk "<span class="font-semibold" style="color:var(--forest);">{{ request('search') }}</span>"
                @endif
                @if (request('kategori'))
                    dalam kategori
                    <span class="font-semibold" style="color:var(--forest);">
                        {{ $kategoris->firstWhere('slug', request('kategori'))?->nama }}
                    </span>
                @endif
            </p>
        </div>

        {{-- Empty state --}}
        @if ($books->isEmpty())
            <div class="text-center py-28">
                <div class="text-7xl mb-5">📚</div>
                <h3 class="font-display text-2xl font-bold mb-2" style="color:var(--forest);">Buku Tidak Ditemukan</h3>
                <p class="text-sm mb-8" style="color:var(--muted);">Coba kata kunci atau filter yang berbeda</p>
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl text-sm font-bold transition hover:opacity-90"
                   style="background:var(--forest); color:white;">
                    Lihat Semua Buku
                </a>
            </div>

        {{-- Book grid --}}
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
                @foreach ($books as $book)
                    @include('components.book-card', ['book' => $book])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $books->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
