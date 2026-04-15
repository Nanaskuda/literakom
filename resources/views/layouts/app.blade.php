<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Literakom — Perpustakaan Sekolah')</title>
    <meta name="description" content="@yield('meta_description', 'Perpustakaan digital sekolah — temukan, pinjam, dan baca buku favoritmu.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --forest:  #1a2e1a;
            --forest2: #243524;
            --cream:   #f7f3ed;
            --cream2:  #ede8e0;
            --copper:  #c17f3a;
            --copper2: #d4943f;
            --text:    #1c1c1c;
            --muted:   #6b7060;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
        }
        .font-display { font-family: 'Lora', serif; }
        .bg-forest { background-color: var(--forest); }
        .bg-cream { background-color: var(--cream); }
        .text-copper { color: var(--copper); }
        .border-copper { border-color: var(--copper); }

        /* Flash toast animation */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to   { opacity: 0; }
        }
        .toast-enter { animation: slideIn .3s ease; }
        .toast-exit  { animation: fadeOut .4s ease 3.5s forwards; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--cream2); }
        ::-webkit-scrollbar-thumb { background: var(--forest); border-radius: 3px; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

{{-- ════════════════════════════════════
     NAVBAR
════════════════════════════════════ --}}
<header class="sticky top-0 z-50" style="background:var(--forest);" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group flex-shrink-0">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105"
                     style="background:var(--copper);">
                    <svg class="w-5 h-5" style="color:var(--forest);" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-display text-xl font-bold text-white tracking-wide">Literakom</span>
                    <span class="hidden sm:block text-xs" style="color:rgba(255,255,255,0.45); line-height:1; margin-top:-2px;">Perpustakaan Sekolah</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach ([
                    ['route' => 'home',        'label' => 'Beranda'],
                    ['route' => 'books.index', 'label' => 'Katalog'],
                    ['route' => 'about',       'label' => 'Tentang'],
                    ['route' => 'kontak',      'label' => 'Kontak'],
                ] as $nav)
                    <a href="{{ route($nav['route']) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs($nav['route'])
                                  ? 'text-white bg-white/15'
                                  : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        {{ $nav['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Auth area --}}
            <div class="hidden md:flex items-center gap-2">
                @auth
                    {{-- Notifikasi jatuh tempo --}}
                    @php
                        $dueLoans = auth()->user()->borrowings()
                            ->where('status', 'dipinjam')
                            ->where('tanggal_kembali', '<=', now()->addDays(2))
                            ->count();
                    @endphp

                    @if ($dueLoans > 0)
                        <a href="{{ route('borrowings.riwayat') }}"
                           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                  transition animate-pulse"
                           style="background:rgba(220,53,69,0.25); color:#ff8c94; border:1px solid rgba(220,53,69,0.35);">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $dueLoans }} jatuh tempo
                        </a>
                    @endif

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition"
                                style="background:var(--copper); color:var(--forest);">
                            <div class="w-6 h-6 rounded-full bg-white/30 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->kelas }}</p>
                            </div>
                            <a href="{{ route('borrowings.riwayat') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Riwayat Pinjam
                            </a>
                            <a href="{{ route('favorites.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                Favorit Saya
                            </a>
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}"
                       class="px-4 py-2 text-sm font-medium text-white/80 hover:text-white transition">
                        Masuk
                    </a>
                    {{-- <a href="{{ route('register') }}"
                       class="px-5 py-2 rounded-xl text-sm font-semibold transition hover:opacity-90"
                       style="background:var(--copper); color:var(--forest);">
                        Daftar
                    </a> --}}
                @endauth
            </div>

            {{-- Mobile toggle --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition">
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden border-t pb-4"
         style="border-color:rgba(255,255,255,0.1); background:var(--forest2);">
        <div class="px-4 pt-3 space-y-1">
            @foreach ([
                ['route' => 'home',        'label' => 'Beranda'],
                ['route' => 'books.index', 'label' => 'Katalog'],
                ['route' => 'about',       'label' => 'Tentang'],
                ['route' => 'kontak',      'label' => 'Kontak'],
            ] as $nav)
                <a href="{{ route($nav['route']) }}" @click="mobileOpen = false"
                   class="block px-4 py-2.5 rounded-xl text-sm font-medium transition
                          {{ request()->routeIs($nav['route'])
                              ? 'text-white bg-white/15'
                              : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                    {{ $nav['label'] }}
                </a>
            @endforeach
            <div class="border-t pt-3 mt-2 space-y-1" style="border-color:rgba(255,255,255,0.1);">
                @auth
                    <div class="px-4 py-2 text-xs text-white/40 uppercase tracking-wider font-semibold">Akun</div>
                    <a href="{{ route('borrowings.riwayat') }}" class="block px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/10 rounded-xl">Riwayat Pinjam</a>
                    <a href="{{ route('favorites.index') }}" class="block px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/10 rounded-xl">Favorit Saya</a>
                    <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 rounded-xl">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" class="block px-4 py-2.5 text-sm text-white/70 hover:text-white hover:bg-white/10 rounded-xl">Masuk</a>
                    {{-- <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm font-semibold text-center rounded-xl" style="background:var(--copper); color:var(--forest);">Daftar Sekarang</a> --}}
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- Flash Toast --}}
@if (session('success') || session('error'))
    <div class="fixed top-20 right-4 z-50 space-y-2 pointer-events-none" x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)" x-show="show"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 translate-x-4">
        @if (session('success'))
            <div class="toast-enter toast-exit flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-medium text-white pointer-events-auto"
                 style="background:var(--forest2); border:1px solid rgba(193,127,58,0.4);">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0" style="background:var(--copper);">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="toast-enter toast-exit flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-medium text-white pointer-events-auto"
                 style="background:#3d1515; border:1px solid rgba(220,53,69,0.4);">
                <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                {{ session('error') }}
            </div>
        @endif
    </div>
@endif

{{-- Main Content --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ════════════════════════════════════
     FOOTER
════════════════════════════════════ --}}
<footer style="background:var(--forest); color:rgba(255,255,255,0.6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--copper);">
                        <svg class="w-4 h-4" style="color:var(--forest);" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    </div>
                    <span class="font-display text-lg font-bold text-white">Literakom</span>
                </div>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5);">
                    Perpustakaan digital sekolah yang menghubungkan siswa dengan ilmu pengetahuan tanpa batas.
                </p>
            </div>
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-sm">
                    @foreach ([
                        ['route'=>'home',        'label'=>'Beranda'],
                        ['route'=>'books.index', 'label'=>'Katalog Buku'],
                        ['route'=>'about',       'label'=>'Tentang Kami'],
                        ['route'=>'kontak',      'label'=>'Kontak'],
                    ] as $l)
                        <li>
                            <a href="{{ route($l['route']) }}"
                               class="transition hover:text-white" style="color:rgba(255,255,255,0.5);">
                                {{ $l['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-4">Kontak</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2.5" style="color:rgba(255,255,255,0.5);">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--copper);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                         Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kec. Bogor Bar., Kota Bogor, Jawa Barat 16117
                    </li>
                    <li class="flex items-center gap-2.5" style="color:rgba(255,255,255,0.5);">
                        <svg class="w-4 h-4 flex-shrink-0" style="color:var(--copper);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        literakom@sekolah.sch.id
                    </li>
                    <li class="flex items-center gap-2.5" style="color:rgba(255,255,255,0.5);">
                        <svg class="w-4 h-4 flex-shrink-0" style="color:var(--copper);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        (021) 123-4567
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs"
             style="border-top:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.3);">
            <span>© {{ date('Y') }} Literakom. Hak cipta dilindungi.</span>
            <span>Dibuat untuk kemajuan literasi siswa</span>
        </div>
    </div>
</footer>

@livewireScripts
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
