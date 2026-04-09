<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestLoans extends BaseWidget
{
    protected static ?string $heading = 'Peminjaman Terbaru';

    protected static bool $isLazy = true;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Borrowing::query()
                ->with(['user:id,name', 'book:id,judul'])
                    ->latest('tanggal_pinjam')
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Member'),
                Tables\Columns\TextColumn::make('book.judul')->label('Buku')->limit(40),
                Tables\Columns\TextColumn::make('tanggal_kembali')->date('d M Y')->label('Jatuh Tempo'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'dipinjam',
                        'success' => 'dikembalikan',
                        'danger'  => 'terlambat',
                    ]),
            ]);
    }
}
