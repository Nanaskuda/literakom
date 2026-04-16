<?php

namespace App\Filament\Resources\BorrowingResource\Pages;

use App\Filament\Resources\BorrowingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
}
