<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected static ?string $breadcrumb = 'Daftar Buku';

    protected static ?string $title = 'Daftar Buku';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Buku')
                ->icon('heroicon-o-plus'),
        ];
    }
}
