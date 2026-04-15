@extends('layouts.app')
@section('title', 'Literakom — Perpustakaan Digital Sekolah')

@section('content')

{{-- ════════════════════════════
     HERO
════════════════════════════ --}}
<section class="relative overflow-hidden" style="background:var(--forest); min-height:92vh;">

    {{-- Texture overlay --}}
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>

    {{-- Glow effects --}}
    <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full opacity-10 pointer-events-none"
         style="background:radial-gradient(circle, var(--copper) 0%, transparent 70%); transform:translate(30%, -30%);"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full opacity-8 pointer-events-none"
         style="background:radial-gradient(circle, #4a7c59 0%, transparent 70%); transform:translate(-30%, 30%);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT: Copy --}}
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold mb-8 tracking-wider uppercase"
                     style="background:rgba(193,127,58,0.15); color:var(--copper); border:1px solid rgba(193,127,58,0.3);">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--copper);"></span>
                    Perpustakaan Digital Sekolah
                </div>

                <h1 class="font-display text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-[1.1] mb-6">
                    Jelajahi
                    <span class="block italic" style="color:var(--copper);">Ribuan Buku</span>
                    di Satu Tempat
                </h1>

                <p class="text-lg leading-relaxed mb-10 max-w-lg" style="color:rgba(255,255,255,0.6);">
                    Literakom hadir untuk memudahkan akses koleksi perpustakaan sekolah —
                    pinjam buku, baca e-book, dan tingkatkan literasimu tanpa batas.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mb-14">
                    <a href="{{ route('books.index') }}"
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl font-bold text-sm transition-all hover:scale-105 hover:shadow-2xl"
                       style="background:var(--copper); color:var(--forest); box-shadow:0 8px 32px rgba(193,127,58,0.35);">
                        Jelajahi Katalog
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    @guest
                        {{-- <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-semibold text-sm transition-all hover:bg-white/10"
                           style="border:1.5px solid rgba(255,255,255,0.25); color:rgba(255,255,255,0.85);">
                            Daftar Gratis
                        </a> --}}
                    @endguest
                </div>

                {{-- Stats --}}
                <div class="flex gap-8" style="border-top:1px solid rgba(255,255,255,0.1); padding-top:2rem;">
                    @php
                        $totalBuku   = \App\Models\Book::where('is_active', true)->count();
                        $totalMember = \App\Models\User::where('role', 'member')->count();
                        $totalPinjam = \App\Models\Borrowing::count();
                    @endphp
                    @foreach ([
                        ['val' => $totalBuku,   'label' => 'Koleksi Buku'],
                        ['val' => $totalMember, 'label' => 'Member'],
                        ['val' => $totalPinjam, 'label' => 'Dipinjam'],
                    ] as $stat)
                        <div>
                            <div class="font-display text-3xl font-bold text-white">{{ $stat['val'] }}+</div>
                            <div class="text-xs mt-1" style="color:rgba(255,255,255,0.45);">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Floating book stack --}}
            <div class="hidden lg:flex items-center justify-center relative h-[480px]">
                @foreach ($latestBooks->take(4) as $i => $book)
                    @php
                        $configs = [
                            ['top'=>'0',    'right'=>'60px',  'rotate'=>'rotate-3',   'z'=>'z-40', 'scale'=>'scale-105'],
                            ['top'=>'60px', 'right'=>'160px', 'rotate'=>'-rotate-2',  'z'=>'z-30', 'scale'=>'scale-100'],
                            ['top'=>'120px','right'=>'40px',  'rotate'=>'rotate-1',   'z'=>'z-20', 'scale'=>'scale-95'],
                            ['top'=>'200px','right'=>'200px', 'rotate'=>'-rotate-3',  'z'=>'z-10', 'scale'=>'scale-90'],
                        ];
                        $c = $configs[$i];
                    @endphp
                    <div class="absolute {{ $c['rotate'] }} {{ $c['z'] }} {{ $c['scale'] }}
                                w-40 rounded-2xl overflow-hidden shadow-2xl
                                hover:-translate-y-3 hover:z-50 transition-all duration-300 cursor-pointer"
                         style="top:{{ $c['top'] }}; right:{{ $c['right'] }}; border:1px solid rgba(255,255,255,0.1);">
                        @if ($book->cover)
                            <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}"
                                 class="w-full h-56 object-cover">
                        @else
                            <div class="w-full h-56 flex flex-col items-center justify-center gap-2 p-4 text-center"
                                 style="background:linear-gradient(135deg, #243524, #3a5a3a);">
                                <svg class="w-10 h-10 opacity-30 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-3" style="background:rgba(26,46,26,0.95);">
                            <p class="text-white text-xs font-semibold truncate">{{ $book->judul }}</p>
                            <p class="text-xs mt-0.5 truncate" style="color:var(--copper);">{{ $book->penulis }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-16">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#f7f3ed"/>
        </svg>
    </div>
</section>

{{-- ════════════════════════════
     QUICK SEARCH
════════════════════════════ --}}
<section class="max-w-3xl mx-auto px-4 -mt-1 relative z-10 pb-16">
    <form action="{{ route('books.index') }}" method="GET">
        <div class="flex gap-2 p-2 rounded-2xl shadow-2xl"
             style="background:white; border:1px solid var(--cream2);">
            <div class="flex-1 flex items-center gap-3 px-4">
                <svg class="w-5 h-5 flex-shrink-0" style="color:var(--copper);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search"
                       placeholder="Cari judul, penulis, atau kategori buku..."
                       class="flex-1 py-3.5 text-sm bg-transparent focus:outline-none"
                       style="color:var(--text);">
            </div>
            <button type="submit"
                    class="px-7 py-3 rounded-xl text-sm font-bold transition hover:opacity-90"
                    style="background:var(--forest); color:white;">
                Cari
            </button>
        </div>
    </form>
</section>

{{-- ════════════════════════════
     BUKU TERBARU
════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Koleksi Terbaru</p>
            <h2 class="font-display text-3xl lg:text-4xl font-bold" style="color:var(--forest);">Buku Baru Hadir</h2>
        </div>
        <a href="{{ route('books.index') }}"
           class="hidden md:inline-flex items-center gap-2 text-sm font-semibold group transition"
           style="color:var(--forest);">
            Lihat semua
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
        @foreach ($latestBooks as $book)
            @include('components.book-card', ['book' => $book])
        @endforeach
    </div>
</section>

{{-- ════════════════════════════
     POPULER (dark section)
════════════════════════════ --}}
<section class="py-20" style="background:var(--forest);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Paling Diminati</p>
                <h2 class="font-display text-3xl lg:text-4xl font-bold text-white">Buku Populer</h2>
            </div>
            <a href="{{ route('books.index', ['sort' => 'populer']) }}"
               class="hidden md:inline-flex items-center gap-2 text-sm font-semibold group transition"
               style="color:rgba(255,255,255,0.6);">
                Lihat semua
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($popularBooks as $rank => $book)
                <a href="{{ route('books.show', $book) }}"
                   class="flex gap-4 rounded-2xl p-4 transition-all group hover:scale-[1.01]"
                   style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08);">

                    {{-- Rank --}}
                    <div class="font-display text-5xl font-bold self-center flex-shrink-0 w-8 text-center"
                         style="color:rgba(255,255,255,0.08);">
                        {{ $rank + 1 }}
                    </div>

                    {{-- Cover --}}
                    <div class="w-14 h-20 rounded-xl overflow-hidden flex-shrink-0" style="background:rgba(255,255,255,0.1);">
                        @if ($book->cover)
                            <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6 opacity-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0 py-1">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md"
                              style="background:rgba(193,127,58,0.2); color:var(--copper);">
                            {{ $book->category->nama ?? '-' }}
                        </span>
                        <h3 class="text-white font-semibold text-sm mt-1.5 leading-snug line-clamp-2
                                   group-hover:text-yellow-200 transition">
                            {{ $book->judul }}
                        </h3>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.45);">{{ $book->penulis }}</p>
                        <p class="text-xs mt-2" style="color:rgba(255,255,255,0.3);">{{ $book->borrowings_count ?? 0 }}x dipinjam</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════
     TENTANG SINGKAT
════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="rounded-3xl overflow-hidden grid lg:grid-cols-2"
         style="background:var(--cream2); border:1px solid rgba(26,46,26,0.1);">

        {{-- Left: kategori grid --}}
        <div class="p-8 lg:p-12">
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Jelajahi</p>
            <h2 class="font-display text-3xl font-bold mb-8" style="color:var(--forest);">Kategori Buku</h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($kategoris as $kat)
                    <a href="{{ route('books.index', ['kategori' => $kat->slug]) }}"
                       class="group flex items-center gap-3 p-4 rounded-2xl transition-all hover:scale-105"
                       style="background:white; border:1px solid rgba(26,46,26,0.08);">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 transition"
                             style="background:rgba(26,46,26,0.07);">
                            <svg class="w-5 h-5 transition" style="color:var(--forest);" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color:var(--forest);">{{ $kat->nama }}</p>
                            <p class="text-xs" style="color:var(--muted);">{{ $kat->bookCount() }} buku</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Right: info --}}
        <div class="p-8 lg:p-12 flex flex-col justify-center" style="background:var(--forest);">
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Tentang Kami</p>
            <h2 class="font-display text-3xl font-bold text-white mb-5 leading-tight">
                Perpustakaan yang Tumbuh<br>
                <span class="italic" style="color:var(--copper);">Bersama Siswanya</span>
            </h2>
            <p class="text-sm leading-relaxed mb-8" style="color:rgba(255,255,255,0.6);">
                Literakom adalah perpustakaan digital sekolah yang dirancang untuk mempermudah
                akses koleksi buku, mendukung kebiasaan membaca, dan meningkatkan kualitas
                literasi seluruh warga sekolah.
            </p>
            <div class="grid grid-cols-2 gap-4 mb-8">
                @foreach ([
                    ['icon'=>'📖', 'title'=>'Koleksi Lengkap', 'desc'=>'Ratusan judul dari berbagai genre'],
                    ['icon'=>'⚡', 'title'=>'Pinjam Cepat',    'desc'=>'Proses mudah dan efisien'],
                    ['icon'=>'📱', 'title'=>'Akses di Mana Saja','desc'=>'HP, tablet, dan laptop'],
                    ['icon'=>'🔔', 'title'=>'Pengingat Otomatis','desc'=>'Notifikasi jatuh tempo'],
                ] as $f)
                    <div class="p-3 rounded-xl" style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08);">
                        <div class="text-xl mb-1.5">{{ $f['icon'] }}</div>
                        <p class="text-white text-xs font-semibold">{{ $f['title'] }}</p>
                        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.45);">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('about') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold transition group"
               style="color:var(--copper);">
                Pelajari lebih lanjut
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

@endsection
