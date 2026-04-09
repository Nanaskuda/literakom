<?php
// app/Filament/Widgets/StatsOverview.php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

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

        return [
Stat::make('Total Buku', $stats['books'])
            ->description('Koleksi aktif')
            ->descriptionIcon('heroicon-m-book-open')
            ->color('primary'),

        Stat::make('Total Member', $stats['members'])
            ->description('Member aktif')
            ->descriptionIcon('heroicon-m-users')
            ->color('success'),

        Stat::make('Sedang Dipinjam', $stats['borrowed'])
            ->description('Belum kembali')
            ->descriptionIcon('heroicon-m-arrow-path')
            ->color('warning'),

        Stat::make('Terlambat', $stats['late'])
            ->description('Melebihi batas')
            ->descriptionIcon('heroicon-m-exclamation-triangle')
            ->color('danger'),
            ];
    }
}
