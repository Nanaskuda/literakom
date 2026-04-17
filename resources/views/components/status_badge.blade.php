@php
    $isTerlambat = $borrowing->isDipinjam() && $borrowing->isTerlambat();

    $config = match(true) {
        $borrowing->status === 'pending'       => ['label' => 'Menunggu',       'bg' => 'rgba(234,179,8,0.1)',    'color' => '#92400e',  'dot' => '#f59e0b'],
        $borrowing->status === 'dipinjam'
            && $isTerlambat                    => ['label' => 'Terlambat',       'bg' => 'rgba(239,68,68,0.1)',    'color' => '#dc2626',  'dot' => '#ef4444'],
        $borrowing->status === 'dipinjam'      => ['label' => 'Dipinjam',        'bg' => 'rgba(34,197,94,0.1)',   'color' => '#166534',  'dot' => '#22c55e'],
        $borrowing->status === 'ditolak'       => ['label' => 'Ditolak',         'bg' => 'rgba(239,68,68,0.1)',   'color' => '#dc2626',  'dot' => '#ef4444'],
        $borrowing->status === 'dikembalikan'  => ['label' => 'Dikembalikan',    'bg' => 'rgba(107,112,96,0.1)', 'color' => '#6b7060',  'dot' => '#9ca3af'],
        default                                => ['label' => $borrowing->status,'bg' => 'rgba(107,112,96,0.1)', 'color' => '#6b7060',  'dot' => '#9ca3af'],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold"
      style="background:{{ $config['bg'] }}; color:{{ $config['color'] }};">
    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:{{ $config['dot'] }};"></span>
    {{ $config['label'] }}
</span>





















