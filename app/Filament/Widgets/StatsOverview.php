<?php
// app/Filament/Widgets/StatsOverview.php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;

class StatsOverview extends BaseWidget
{
    protected static bool $isLazy = true;

    protected ?string $heading = 'Statistik Perpustakaan';

    protected function getStats(): array
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
        return [
            'books' => \App\Models\Book::where('is_active', true)->count(),
            'members' => \App\Models\User::where('role', 'member')->where('is_active', true)->count(),
            'borrowed' => \App\Models\Borrowing::where('status', 'dipinjam')->count(),
            'late' => \App\Models\Borrowing::where('status', 'terlambat')->count(),
        ];
    });

        $pendingCount = Borrowing::pending()->count();

        return [
            Stat::make('Menunggu Persetujuan', $pendingCount)
                ->description($pendingCount > 0 ? 'Perlu ditinjau segera' : 'Tidak ada pengajuan baru')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make('Sedang Dipinjam', Borrowing::dipinjam()->count())
                ->description('Buku belum dikembalikan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),

            Stat::make('Terlambat', Borrowing::terlambat()->count())
                ->description('Melewati batas pengembalian')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Total Buku', Book::where('is_active', true)->count())
                ->description('Koleksi aktif perpustakaan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Member Aktif', User::where('role', 'member')->where('is_active', true)->count())
                ->description('Member terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
