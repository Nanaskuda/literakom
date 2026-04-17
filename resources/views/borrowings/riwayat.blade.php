@extends('layouts.app')
@section('title', 'Riwayat Peminjaman — Literakom')

@section('content')
<div style="background:var(--cream); min-height:100vh;">

    {{-- ════ HEADER ════ --}}
    <div style="background:var(--forest);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-16">
            <nav class="flex items-center gap-2 text-xs mb-5" style="color:rgba(255,255,255,0.4);">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <span class="text-white">Riwayat Peminjaman</span>
            </nav>
            <p class="text-xs font-bold uppercase tracking-[0.2em] mb-2" style="color:var(--copper);">Akunmu</p>
            <h1 class="font-display text-4xl font-bold text-white">Riwayat Peminjaman</h1>
        </div>
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" class="w-full h-10 block">
            <path d="M0,20 C480,40 960,0 1440,20 L1440,40 L0,40 Z" fill="#f7f3ed"/>
        </svg>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Notifikasi jatuh tempo ── --}}
        @if ($nearDue->count() > 0)
            <div class="mb-8 rounded-2xl p-5 flex gap-4"
                 style="background:rgba(234,179,8,0.08); border:1.5px solid rgba(234,179,8,0.25);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(234,179,8,0.15);">
                    <svg class="w-5 h-5" style="color:#b45309;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm mb-2" style="color:#92400e;">
                        ⚠️ Perhatian — {{ $nearDue->count() }} buku hampir jatuh tempo!
                    </p>
                    <ul class="space-y-1">
                        @foreach ($nearDue as $due)
                            @php $sisa = $due->sisaHari(); @endphp
                            <li class="text-sm" style="color:#78350f;">
                                <span class="font-semibold">{{ $due->book?->judul }}</span>
                                —
                                @if ($sisa < 0)
                                    <span class="font-bold text-red-600">Terlambat {{ abs($sisa) }} hari!</span>
                                @elseif ($sisa === 0)
                                    <span class="font-bold" style="color:#dc2626;">Harus dikembalikan hari ini!</span>
                                @else
                                    <span>{{ $sisa }} hari lagi ({{ $due->tanggal_kembali->format('d M Y') }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── Summary cards ── --}}
        @php
            $allBorrowings   = auth()->user()->borrowings;
            $activeBorrowings = $allBorrowings->where('status', 'dipinjam');
            $pendingBorrowings = $allBorrowings->where('status', 'pending');
            $doneBorrowings   = $allBorrowings->whereIn('status', ['dikembalikan']);
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @foreach ([
                ['label' => 'Total Pinjam',   'val' => $allBorrowings->count(),     'color' => 'var(--forest)',  'bg' => 'rgba(26,46,26,0.06)'],
                ['label' => 'Menunggu',        'val' => $pendingBorrowings->count(), 'color' => '#92400e',        'bg' => 'rgba(234,179,8,0.08)'],
                ['label' => 'Aktif Dipinjam',  'val' => $activeBorrowings->count(), 'color' => '#166534',        'bg' => 'rgba(34,197,94,0.08)'],
                ['label' => 'Dikembalikan',    'val' => $doneBorrowings->count(),   'color' => 'var(--muted)',   'bg' => 'rgba(107,112,96,0.06)'],
            ] as $s)
                <div class="rounded-2xl p-5 text-center" style="background:{{ $s['bg'] }}; border:1px solid {{ $s['bg'] }};">
                    <div class="font-display text-3xl font-bold mb-1" style="color:{{ $s['color'] }};">{{ $s['val'] }}</div>
                    <div class="text-xs font-medium" style="color:var(--muted);">{{ $s['label'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- ── Tabel riwayat ── --}}
        @if ($borrowings->isEmpty())
            <div class="text-center py-24 rounded-3xl" style="background:white; border:1px solid rgba(26,46,26,0.08);">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="font-display text-2xl font-bold mb-2" style="color:var(--forest);">Belum ada riwayat</h3>
                <p class="text-sm mb-6" style="color:var(--muted);">Kamu belum pernah meminjam buku.</p>
                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl text-sm font-bold transition hover:opacity-90"
                   style="background:var(--copper); color:var(--forest);">
                    Jelajahi Katalog
                </a>
            </div>
        @else
            <div class="rounded-3xl overflow-hidden" style="background:white; border:1px solid rgba(26,46,26,0.08);">

                {{-- Mobile: card list --}}
                <div class="divide-y sm:hidden" style="divide-color:var(--cream2);">
                    @foreach ($borrowings as $b)
                        <div class="p-5">
                            <div class="flex items-start gap-3.5">
                                {{-- Cover mini --}}
                                <div class="w-12 h-16 rounded-xl overflow-hidden flex-shrink-0"
                                     style="background:var(--cream2);">
                                    @if ($b->book?->cover)
                                        <img src="{{ Storage::url($b->book->cover) }}"
                                             alt="{{ $b->book->judul }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center"
                                             style="background:var(--forest);">
                                            <svg class="w-5 h-5 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm truncate" style="color:var(--forest);">
                                        {{ $b->book?->judul ?? '—' }}
                                    </p>
                                    <p class="text-xs mt-0.5" style="color:var(--muted);">
                                        {{ $b->book?->penulis }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2.5 flex-wrap">
                                        @include('components.status_badge', ['borrowing' => $b])
                                        <span class="text-xs" style="color:var(--muted);">
                                            {{ $b->tanggal_pinjam?->format('d M Y') }}
                                        </span>
                                    </div>
                                    @if ($b->isDipinjam() && $b->tanggal_kembali)
                                        <p class="text-xs mt-1.5 {{ $b->isTerlambat() ? 'font-semibold text-red-500' : '' }}"
                                           style="{{ $b->isTerlambat() ? '' : 'color:var(--muted);' }}">
                                            Kembali:
                                            {{ $b->tanggal_kembali->format('d M Y') }}
                                            @if ($b->isTerlambat())
                                                (Terlambat {{ abs($b->sisaHari()) }} hari!)
                                            @elseif ($b->sisaHari() <= 3)
                                                ({{ $b->sisaHari() }} hari lagi)
                                            @endif
                                        </p>
                                    @endif
                                    @if ($b->catatan_admin)
                                        <p class="text-xs mt-1.5 italic" style="color:var(--muted);">
                                            Catatan admin: {{ $b->catatan_admin }}
                                        </p>
                                    @endif
                                    {{-- Aksi ajukan kembali --}}
                                    @if ($b->isDipinjam())
                                        <form method="POST"
                                              action="{{ route('borrowings.ajukanKembali', $b) }}"
                                              class="mt-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs px-3 py-1.5 rounded-lg font-semibold transition hover:opacity-80"
                                                    style="background:rgba(26,46,26,0.08); color:var(--forest);"
                                                    onclick="return confirm('Ajukan pengembalian buku ini?')">
                                                Ajukan Pengembalian
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop: table --}}
                <table class="w-full hidden sm:table">
                    <thead>
                        <tr style="background:var(--cream); border-bottom:2px solid var(--cream2);">
                            <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--muted);">Buku</th>
                            <th class="text-left px-4 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--muted);">Tgl Pinjam</th>
                            <th class="text-left px-4 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--muted);">Jatuh Tempo</th>
                            <th class="text-left px-4 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--muted);">Status</th>
                            <th class="text-left px-4 py-4 text-xs font-bold uppercase tracking-wider" style="color:var(--muted);">Catatan</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:var(--cream2);">
                        @foreach ($borrowings as $b)
                            <tr class="hover:bg-gray-50/50 transition group">
                                {{-- Buku --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-14 rounded-lg overflow-hidden flex-shrink-0"
                                             style="background:var(--cream2);">
                                            @if ($b->book?->cover)
                                                <img src="{{ Storage::url($b->book->cover) }}"
                                                     alt="{{ $b->book->judul }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center"
                                                     style="background:var(--forest);">
                                                    <svg class="w-4 h-4 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('books.show', $b->book_id) }}"
                                               class="font-semibold text-sm hover:underline transition line-clamp-1"
                                               style="color:var(--forest);">
                                                {{ $b->book?->judul ?? '—' }}
                                            </a>
                                            <p class="text-xs mt-0.5" style="color:var(--muted);">
                                                {{ $b->book?->penulis }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tgl pinjam --}}
                                <td class="px-4 py-4">
                                    <span class="text-sm" style="color:var(--text);">
                                        {{ $b->tanggal_pinjam?->format('d M Y') ?? '—' }}
                                    </span>
                                </td>

                                {{-- Jatuh tempo --}}
                                <td class="px-4 py-4">
                                    @if ($b->tanggal_kembali)
                                        <span class="text-sm font-medium
                                            {{ $b->isDipinjam() && $b->isTerlambat()
                                                ? 'text-red-600'
                                                : ($b->isDipinjam() && $b->sisaHari() <= 3
                                                    ? 'text-yellow-600'
                                                    : '') }}"
                                              style="{{ !$b->isDipinjam() || (!$b->isTerlambat() && $b->sisaHari() > 3) ? 'color:var(--text);' : '' }}">
                                            {{ $b->tanggal_kembali->format('d M Y') }}
                                        </span>
                                        @if ($b->isDipinjam())
                                            <div class="text-xs mt-0.5">
                                                @if ($b->isTerlambat())
                                                    <span class="text-red-500 font-semibold">
                                                        +{{ abs($b->sisaHari()) }} hari terlambat
                                                    </span>
                                                @elseif ($b->sisaHari() <= 3)
                                                    <span class="text-yellow-600">{{ $b->sisaHari() }} hari lagi</span>
                                                @else
                                                    <span style="color:var(--muted);">{{ $b->sisaHari() }} hari lagi</span>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-sm" style="color:var(--muted);">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4">
                                    @include('components.status_badge', ['borrowing' => $b])
                                </td>

                                {{-- Catatan admin --}}
                                <td class="px-4 py-4 max-w-[160px]">
                                    @if ($b->catatan_admin)
                                        <p class="text-xs italic line-clamp-2" style="color:var(--muted);">
                                            {{ $b->catatan_admin }}
                                        </p>
                                    @else
                                        <span class="text-xs" style="color:rgba(107,112,96,0.4);">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4">
                                    @if ($b->isDipinjam())
                                        <form method="POST"
                                              action="{{ route('borrowings.ajukanKembali', $b) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="text-xs px-3.5 py-2 rounded-xl font-semibold whitespace-nowrap transition hover:opacity-80"
                                                    style="background:rgba(26,46,26,0.08); color:var(--forest);"
                                                    onclick="return confirm('Ajukan pengembalian buku ini?\nSerahkan fisik bukunya ke petugas perpustakaan.')">
                                                Ajukan Kembali
                                            </button>
                                        </form>
                                    @elseif ($b->isPending())
                                        <span class="text-xs" style="color:var(--muted);">Menunggu...</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $borrowings->links() }}
            </div>
        @endif

        {{-- Link ke katalog --}}
        <div class="mt-6 text-center">
            <a href="{{ route('books.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold transition group"
               style="color:var(--copper);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Katalog
            </a>
        </div>
    </div>
</div>
@endsection
