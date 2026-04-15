@extends('layouts.app')
@section('title', 'Tentang Literakom — Perpustakaan Digital Sekolah')

@section('content')
{{-- Header Section --}}
<section class="relative overflow-hidden pt-20 pb-32" style="background:var(--forest);">
    {{-- Texture & Glow (Sama dengan Home) --}}
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>

    <div class="relative max-w-4xl mx-auto px-4 text-center">
        <p class="text-xs font-bold uppercase tracking-[0.3em] mb-4" style="color:var(--copper);">Mengenal Kami</p>
        <h1 class="font-display text-4xl lg:text-6xl font-bold text-white mb-6">
            Menumbuhkan Budaya Literasi di Era <span class="italic" style="color:var(--copper);">Digital</span>
        </h1>
        <p class="text-lg leading-relaxed mx-auto max-w-2xl" style="color:rgba(255,255,255,0.6);">
            Literakom (Literasi Infokom) adalah inisiatif perpustakaan digital yang dirancang khusus untuk memfasilitasi civitas akademika dalam mengakses ilmu pengetahuan dengan lebih mudah, cepat, dan modern.
        </p>
    </div>
</section>

{{-- Content Section --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 pb-20">
    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Visi & Misi --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 lg:p-12 shadow-xl border border-gray-100">
            <h2 class="font-display text-3xl font-bold mb-6" style="color:var(--forest);">Visi & Fokus Kami</h2>
            <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed space-y-4">
                <p>
                    Lahir dari kebutuhan akan akses informasi yang fleksibel, <strong>Literakom</strong> berupaya menjadi jembatan antara kurikulum sekolah dengan sumber bacaan berkualitas. Kami percaya bahwa perpustakaan bukan sekadar tempat penyimpanan buku, melainkan jantung dari perkembangan intelektual siswa.
                </p>
                <div class="grid md:grid-cols-2 gap-6 mt-8">
                    <div class="p-5 rounded-2xl" style="background:var(--cream);">
                        <h3 class="font-bold text-sm mb-2" style="color:var(--forest);">Modernisasi Akses</h3>
                        <p class="text-xs">Memindahkan batasan fisik perpustakaan ke dalam genggaman layar gadget siswa, kapan pun dan di mana pun.</p>
                    </div>
                    <div class="p-5 rounded-2xl" style="background:var(--cream);">
                        <h3 class="font-bold text-sm mb-2" style="color:var(--forest);">Efisiensi Manajemen</h3>
                        <p class="text-xs">Menyederhanakan proses peminjaman dan pengembalian buku dengan sistem otomasi yang transparan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sekolah Info Card --}}
        <div class="bg-forest rounded-3xl p-8 text-white flex flex-col justify-center relative overflow-hidden">
             {{-- Dekorasi icon buku transparan --}}
            <svg class="absolute -right-10 -bottom-10 w-40 h-40 opacity-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
            </svg>

            <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--copper);">SEKOLAH</p>
            <h3 class="font-display text-2xl font-bold mb-4">SMK Infokom Bogor</h3>
            <p class="text-sm opacity-70 leading-relaxed mb-6">
                Program ini dikembangkan untuk mendukung program literasi sekolah dan mempermudah administrasi perpustakaan secara digital.
            </p>
            <div class="space-y-3">
                <div class="flex items-center gap-3 text-xs">
                    <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">📍</span>
                    <span>Jl. Letjen Ibrahim Adjie No.7, Bogor</span>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">🌐</span>
                    <span>www.smkinfokom-bogor.sch.id</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
