<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected static ?string $breadcrumb = 'Tambah Kategori';
    
    protected static ?string $title = 'Tambah Kategori';

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
        ->label('Tambah Kategori');
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
