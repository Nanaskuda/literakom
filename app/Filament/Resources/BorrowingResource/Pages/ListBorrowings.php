<?php

namespace App\Filament\Resources\BorrowingResource\Pages;

use App\Filament\Resources\BorrowingResource;
use App\Models\Borrowing;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListBorrowings extends ListRecords
{
    protected static string $resource = BorrowingResource::class;

    protected static ?string $title = 'Daftar Peminjaman';

    protected static ?string $breadcrumb = 'Daftar Peminjaman';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Peminjaman')
            ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->badge(Borrowing::count()),

            'pending' => Tab::make('Menunggu')
                ->badge(Borrowing::pending()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $q) => $q->pending()),

            'dipinjam' => Tab::make('Dipinjam')
                ->badge(Borrowing::dipinjam()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $q) => $q->aktif()),

            'terlambat' => Tab::make('Terlambat')
                ->badge(Borrowing::terlambat()->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn(Builder $q) => $q->terlambat()),

            'dikembalikan' => Tab::make('Dikembalikan')
                ->modifyQueryUsing(fn(Builder $q) =>
                    $q->where('status', Borrowing::STATUS_DIKEMBALIKAN)
                ),

            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn(Builder $q) =>
                    $q->where('status', Borrowing::STATUS_DITOLAK)
                ),
        ];
    }
}
