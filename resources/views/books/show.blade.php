@extends('layouts.app')
@section('title', $book->judul . ' — Literakom')

@section('content')
<div style="background:var(--cream); min-height:100vh;">

    {{-- ════ TOP BAR ════ --}}
    <div style="background:var(--forest);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <nav class="flex items-center gap-2 text-xs" style="color:rgba(255,255,255,0.4);">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <a href="{{ route('books.index') }}" class="hover:text-white transition">Katalog</a>
                <span>/</span>
                <span class="text-white truncate max-w-[200px]">{{ $book->judul }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid lg:grid-cols-[320px_1fr] gap-10 xl:gap-14">

            {{-- ════════════════════════
                 LEFT COLUMN
            ════════════════════════ --}}
            <div class="space-y-4">

                {{-- Cover --}}
                <div class="rounded-3xl overflow-hidden shadow-2xl aspect-[3/4] max-w-[280px] mx-auto lg:mx-0"
                     style="border:1px solid rgba(26,46,26,0.1);">
                    @if ($book->cover)
                        <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-4 p-8"
                             style="background:linear-gradient(135deg, var(--forest), #3a5a3a);">
                            <svg class="w-20 h-20 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <p class="text-white/30 text-sm text-center">Tidak ada cover</p>
                        </div>
                    @endif
                </div>

                {{-- Action buttons --}}
                <div class="space-y-3 max-w-[280px] mx-auto lg:mx-0">
{{-- Error Message --}}
@if ($errors->any())
    <div class="mb-3 text-sm text-red-500">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

@auth
    @if ($userHasBorrowed)
        @php
            $activeLoan = $book->borrowings()
                ->where('user_id', auth()->id())
                ->where('status', 'dipinjam')
                ->first();
        @endphp

        {{-- STATUS --}}
        <div class="w-full text-center px-5 py-3.5 rounded-2xl text-sm font-semibold"
             style="background:rgba(26,46,26,0.07); color:var(--forest);
                    border:1.5px solid rgba(26,46,26,0.15);">
            ✓ Sedang Kamu Pinjam

            @if ($activeLoan)
                <div class="text-xs mt-1" style="color:var(--muted);">
                    Kembali:
                    <span class="{{ $activeLoan->isTerlambat() ? 'text-red-500 font-semibold' : '' }}">
                        {{ optional($activeLoan->tanggal_kembali)->format('d M Y') }}
                    </span>

                    @if ($activeLoan->isTerlambat())
                        <span class="text-red-500">(Terlambat!)</span>
                    @elseif ($activeLoan->sisaHari() !== null && $activeLoan->sisaHari() <= 2)
                        <span class="text-orange-500">
                            ({{ $activeLoan->sisaHari() }} hari lagi)
                        </span>
                    @endif
                </div>
            @endif
        </div>

        {{-- AJUKAN PENGEMBALIAN --}}
        @if ($activeLoan)
            <form method="POST" action="{{ route('borrowings.ajukanKembali', $activeLoan) }}">
                @csrf
                @method('PATCH')

                <button type="submit"
                        class="w-full px-5 py-3.5 rounded-2xl text-sm font-bold"
                        style="background:var(--forest); color:white;">
                    Ajukan Pengembalian
                </button>
            </form>
        @endif

    @elseif ($book->isAvailable())
        {{-- PINJAM --}}
        <form method="POST" action="{{ route('borrowings.store', $book) }}">
            @csrf

            {{-- FIX: WAJIB ADA --}}
            <input type="hidden" name="durasi" value="7">

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-5 py-4 rounded-2xl
                           text-sm font-bold transition hover:scale-[1.02]"
                    style="background:var(--copper); color:var(--forest);">
                Pinjam Buku Ini
            </button>
        </form>

    @else
        {{-- STOK HABIS --}}
        <div class="w-full text-center px-5 py-4 rounded-2xl text-sm font-semibold"
             style="background:rgba(239,68,68,0.08); color:#dc2626;">
            📵 Stok Habis — Tidak Tersedia
        </div>
    @endif

    {{-- FAVORIT --}}
    <form method="POST" action="{{ route('favorites.toggle', $book) }}">
        @csrf
        <button type="submit"
                class="w-full px-5 py-3.5 rounded-2xl text-sm font-semibold"
                style="background:white; border:1px solid #ddd;">
            {{ $userHasFavorited ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}
        </button>
    </form>

@else
    {{-- GUEST --}}
    <a href="{{ route('filament.admin.auth.login') }}"
       class="block w-full text-center px-5 py-4 rounded-2xl text-sm font-bold"
       style="background:var(--copper); color:var(--forest);">
        Login untuk Meminjam
    </a>
@endauth
                </div>
            </div>

            {{-- ════════════════════════
                 RIGHT COLUMN
            ════════════════════════ --}}
            <div class="space-y-6">

                {{-- ── Info utama ── --}}
                <div class="rounded-3xl p-7 lg:p-9" style="background:white; border:1px solid rgba(26,46,26,0.08);">

                    {{-- Badges --}}
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="text-xs font-bold px-3.5 py-1.5 rounded-full"
                              style="background:rgba(26,46,26,0.08); color:var(--forest);">
                            {{ $book->category->nama ?? '-' }}
                        </span>
                        @if ($book->isAvailable())
                            <span class="text-xs font-semibold px-3.5 py-1.5 rounded-full"
                                  style="background:rgba(34,197,94,0.1); color:#16a34a;
                                         border:1px solid rgba(34,197,94,0.2);">
                                ✓ Tersedia — {{ $book->stok }} eksemplar
                            </span>
                        @else
                            <span class="text-xs font-semibold px-3.5 py-1.5 rounded-full"
                                  style="background:rgba(239,68,68,0.1); color:#dc2626;
                                         border:1px solid rgba(239,68,68,0.2);">
                                ✗ Stok Habis
                            </span>
                        @endif
                        @if ($book->ebook)
                            <span class="text-xs font-semibold px-3.5 py-1.5 rounded-full"
                                  style="background:rgba(59,130,246,0.08); color:#2563eb;
                                         border:1px solid rgba(59,130,246,0.2);">
                                📄 E-Book Tersedia
                            </span>
                        @endif
                    </div>

                    {{-- Judul --}}
                    <h1 class="font-display text-3xl lg:text-4xl font-bold leading-tight mb-2"
                        style="color:var(--forest);">
                        {{ $book->judul }}
                    </h1>
                    <p class="text-base mb-1" style="color:var(--muted);">
                        oleh <span class="font-semibold" style="color:var(--forest);">{{ $book->penulis }}</span>
                    </p>

                    {{-- Rating summary --}}
                    @if ($book->reviews->count() > 0)
                        <div class="flex items-center gap-3 mb-6 mt-4 pb-6"
                             style="border-bottom:1px solid var(--cream2);">
                            <div class="flex gap-0.5">
                                @for ($s = 1; $s <= 5; $s++)
                                    <svg class="w-5 h-5 {{ $s <= round($book->averageRating()) ? '' : 'opacity-15' }}"
                                         style="color:var(--copper);" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-bold" style="color:var(--forest);">{{ $book->averageRating() }}</span>
                            <span class="text-sm" style="color:var(--muted);">dari {{ $book->reviews->count() }} ulasan</span>
                        </div>
                    @else
                        <div class="mb-6 mt-4 pb-6" style="border-bottom:1px solid var(--cream2);"></div>
                    @endif

                    {{-- Detail grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-7">
                        @foreach ([
                            ['label' => 'Penerbit',    'value' => $book->penerbit     ?? '—'],
                            ['label' => 'Tahun Terbit','value' => $book->tahun_terbit ?? '—'],
                            ['label' => 'ISBN',        'value' => $book->isbn         ?? '—'],
                            ['label' => 'Halaman',     'value' => $book->halaman ? $book->halaman . ' hal.' : '—'],
                            ['label' => 'Stok',        'value' => $book->stok . ' eksemplar'],
                            ['label' => 'Kategori',    'value' => $book->category->nama ?? '—'],
                        ] as $detail)
                            <div class="p-4 rounded-2xl" style="background:var(--cream); border:1px solid var(--cream2);">
                                <p class="text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:var(--copper);">
                                    {{ $detail['label'] }}
                                </p>
                                <p class="text-sm font-semibold" style="color:var(--forest);">{{ $detail['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sinopsis --}}
                    @if ($book->sinopsis)
                        <div>
                            <h3 class="font-display text-lg font-bold mb-3" style="color:var(--forest);">Sinopsis</h3>
                            <div class="prose prose-sm max-w-none" style="color:var(--muted); line-height:1.8;">
                                <p>{{ $book->sinopsis }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ── Review & Rating section ── --}}
                <div class="rounded-3xl p-7 lg:p-9" style="background:white; border:1px solid rgba(26,46,26,0.08);">
                    <h3 class="font-display text-2xl font-bold mb-1" style="color:var(--forest);">
                        Ulasan Pembaca
                    </h3>
                    <p class="text-sm mb-7" style="color:var(--muted);">{{ $book->reviews->count() }} ulasan</p>

                    {{-- Form tulis review --}}
                    @auth
                        @php $myReview = $book->reviews->firstWhere('user_id', auth()->id()); @endphp
                        <form method="POST" action="{{ route('reviews.store', $book->id) }}"
                              class="pb-8 mb-8"
                              style="border-bottom:2px dashed var(--cream2);"
                              x-data="{ rating: {{ $myReview?->rating ?? 0 }}, hover: 0 }">
                            @csrf

                            <p class="text-sm font-semibold mb-4" style="color:var(--forest);">
                                {{ $myReview ? 'Edit ulasanmu' : 'Tulis ulasanmu' }}
                            </p>

                            {{-- Star input --}}
                            <div class="flex gap-1.5 mb-4">
                                @for ($s = 1; $s <= 5; $s++)
                                    <button type="button"
                                            @mouseenter="hover = {{ $s }}"
                                            @mouseleave="hover = 0"
                                            @click="rating = {{ $s }}"
                                            class="text-4xl transition-transform hover:scale-110 focus:outline-none leading-none">
                                        <span :style="(hover || rating) >= {{ $s }} ? 'color:var(--copper)' : 'color:var(--cream2)'">★</span>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                            <p class="text-xs mb-4 h-4 transition" style="color:var(--copper);">
                                <span x-show="hover === 1 || (!hover && rating === 1)">Sangat Buruk</span>
                                <span x-show="hover === 2 || (!hover && rating === 2)">Buruk</span>
                                <span x-show="hover === 3 || (!hover && rating === 3)">Cukup</span>
                                <span x-show="hover === 4 || (!hover && rating === 4)">Bagus</span>
                                <span x-show="hover === 5 || (!hover && rating === 5)">Sangat Bagus!</span>
                            </p>

                            <textarea name="komentar" rows="3"
                                      placeholder="Bagikan pendapatmu tentang buku ini..."
                                      class="w-full text-sm rounded-2xl px-5 py-4 resize-none focus:outline-none transition"
                                      style="background:var(--cream); border:1.5px solid var(--cream2);
                                             color:var(--text);">{{ $myReview?->komentar }}</textarea>

                            <div class="flex items-center justify-between mt-3">
                                <p class="text-xs" style="color:var(--muted);">Maks. 1000 karakter</p>
                                <button type="submit"
                                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90"
                                        style="background:var(--forest); color:white;"
                                        :disabled="rating === 0"
                                        :class="rating === 0 && 'opacity-40 cursor-not-allowed'">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mb-8 pb-8 rounded-2xl text-center p-6"
                             style="background:var(--cream); border:1.5px dashed var(--cream2);
                                    border-bottom: 2px dashed var(--cream2);">
                            <p class="text-sm" style="color:var(--muted);">
                                <a href="{{ route('filament.admin.auth.login') }}"
                                   class="font-semibold transition"
                                   style="color:var(--copper);">Login</a>
                                untuk menulis ulasan
                            </p>
                        </div>
                    @endauth

                    {{-- Daftar review --}}
                    @forelse ($book->reviews as $review)
                        <div class="py-5 {{ !$loop->last ? 'border-b' : '' }}"
                             style="border-color:var(--cream2);">
                            <div class="flex items-start gap-3.5">
                                {{-- Avatar --}}
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                                            text-sm font-bold text-white"
                                     style="background:var(--forest);">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-2 flex-wrap">
                                        <div>
                                            <p class="font-semibold text-sm" style="color:var(--forest);">{{ $review->user->name }}</p>
                                            <p class="text-xs" style="color:var(--muted);">{{ $review->user->kelas }}</p>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <svg class="w-4 h-4 {{ $s <= $review->rating ? '' : 'opacity-15' }}"
                                                     style="color:var(--copper);" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if ($review->komentar)
                                        <p class="text-sm mt-2 leading-relaxed" style="color:var(--text);">{{ $review->komentar }}</p>
                                    @endif
                                    <p class="text-xs mt-2" style="color:rgba(107,112,96,0.6);">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12" style="color:var(--muted);">
                            <div class="text-5xl mb-3">💬</div>
                            <p class="text-sm">Belum ada ulasan. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>

            </div>{{-- end right col --}}
        </div>
    </div>
</div>

{{-- ════ E-BOOK MODAL ════ --}}
@if ($book->ebook)
    <div id="ebookModal"
         class="hidden fixed inset-0 z-50 lg:flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.85);"
         onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-3xl w-full max-w-5xl flex flex-col overflow-hidden shadow-2xl"
             style="height:90vh;">
            <div class="flex items-center justify-between px-6 py-4"
                 style="border-bottom:1px solid var(--cream2);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center"
                         style="background:var(--forest);">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm truncate max-w-xs" style="color:var(--forest);">
                            {{ $book->judul }}
                        </h3>
                        <p class="text-xs" style="color:var(--muted);">E-Book Reader</p>
                    </div>
                </div>
                <button onclick="document.getElementById('ebookModal').classList.add('hidden')"
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition hover:bg-gray-100"
                        style="color:var(--muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe src="{{ Storage::url($book->ebook) }}"
                        class="w-full h-full" frameborder="0"
                        title="{{ $book->judul }} E-Book">
                </iframe>
            </div>
        </div>
    </div>
@endif

@endsection
