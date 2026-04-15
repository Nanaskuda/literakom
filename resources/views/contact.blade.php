@extends('layouts.app')
@section('title', 'Kontak Kami — Literakom')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
    <div class="grid lg:grid-cols-2 gap-16 items-start">

        {{-- Info Kontak --}}
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-3" style="color:var(--copper);">Hubungi Kami</p>
            <h1 class="font-display text-4xl lg:text-5xl font-bold mb-6" style="color:var(--forest);">Ada Pertanyaan?</h1>
            <p class="text-gray-600 leading-relaxed mb-10 max-w-md">
                Tim pustakawan dan admin kami siap membantu Anda. Silakan hubungi kami melalui saluran di bawah atau isi formulir yang tersedia.
            </p>

            <div class="space-y-8">
                <div class="flex gap-5">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(26,46,26,0.05); color:var(--forest);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm" style="color:var(--forest);">Email Perpustakaan</h4>
                        <p class="text-sm text-gray-500">perpustakaan@smkinfokom.sch.id</p>
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(26,46,26,0.05); color:var(--forest);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm" style="color:var(--forest);">Jam Operasional</h4>
                        <p class="text-sm text-gray-500">Senin - Jumat: 07:00 - 16:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl p-8 lg:p-10 shadow-2xl border border-gray-100 relative overflow-hidden">
            {{-- Accent line --}}
            <div class="absolute top-0 left-0 right-0 h-1.5" style="background:var(--copper);"></div>

            <form action="#" method="POST" class="space-y-5">
                @csrf
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Nama Lengkap</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-800 transition text-sm bg-gray-50" placeholder="Masukkan nama...">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Kelas / Role</label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-800 transition text-sm bg-gray-50" placeholder="Contoh: XII RPL 1">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Subjek</label>
                    <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-800 transition text-sm bg-gray-50" placeholder="Contoh: Bantuan Peminjaman">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Pesan</label>
                    <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-800 transition text-sm bg-gray-50" placeholder="Tuliskan pesan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="w-full py-4 rounded-xl font-bold text-sm transition-all hover:scale-[1.02] shadow-lg"
                        style="background:var(--forest); color:white;">
                    Kirim Pesan
                </button>
            </form>
        </div>

    </div>
</section>
@endsection
