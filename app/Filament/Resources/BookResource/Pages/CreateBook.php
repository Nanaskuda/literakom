<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected static ?string $breadcrumb = 'Tambah Buku';

    protected static ?string $title = 'Tambah Buku';

     protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Buku');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
        ->label('Simpan dan Tambah Lainnya');
    }

    protected function getCancelFormAction(): Action
{
    return parent::getCancelFormAction()
        ->label('Batal');
}
}


