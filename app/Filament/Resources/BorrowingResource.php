<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingResource\Pages;
use App\Models\Borrowing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Peminjaman';

    protected static ?string $pluralModelLabel = 'Peminjaman';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
       return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Member')
                   ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('role', 'member')
                    )
                    ->searchable()
                    ->live()
                    ->required()
                    ->helperText(fn ($state) =>
                        $state && \App\Models\User::find($state)?->book_count >= 5
                        ? 'Limit tercapai! User tidak bisa meminjam lebih banyak buku.'
                        : 'Pilih user yang akan meminjam.'
                    )
                    ->rules([
                        fn (Get $get): \Closure => function (string $attribute, $value, \Closure $fail) {
                            // Cari user dan cek book_count-nya
                            $user = \App\Models\User::find($value);

                            if ($user && $user->book_count >= 5) {
                                $fail("User ini sudah meminjam 5 buku. Harap kembalikan buku sebelumnya.");
                            }
                        },
                    ]),

                Forms\Components\Select::make('book_id')
                    ->label('Buku')
                    ->relationship('book', 'judul')
                    ->searchable()
                    ->helperText(fn ($state) =>
                        $state && \App\Models\Book::find($state)?->stok <= 0
                        ? 'Stok habis! Pilih buku lain.'
                        : 'Pilih buku yang akan dipinjam.'
                    )
                    ->live()
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_pinjam')
                    ->default(now())
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_kembali')
                    ->default(now())
                    ->required()
                    ->after('tanggal_pinjam'),

                Forms\Components\DatePicker::make('tanggal_dikembalikan')
                    ->nullable(),

                Forms\Components\Select::make('status')
                    ->options([
                        'dipinjam'    => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat'   => 'Terlambat',
                    ])
                    ->default('dipinjam')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {

        return $table
        ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.kelas')
                    ->label('Kelas'),

                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Buku')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) =>
                        $record->status === 'dipinjam' && Carbon::today()->gt($record->tanggal_kembali)
                            ? 'danger' : null
                    ),

                Tables\Columns\TextColumn::make('status')
                    ->colors([
                        'warning' => 'dipinjam',
                        'success' => 'dikembalikan',
                        'danger'  => 'terlambat',
                    ])
                    ->badge()
                    ->label('Status'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dipinjam'    => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat'   => 'Terlambat',
                    ]),

                Tables\Filters\Filter::make('terlambat')
                    ->label('Jatuh Tempo Hari Ini')
                    ->query(fn ($query) =>
                        $query->where('status', 'dipinjam')
                              ->where('tanggal_kembali', '<', Carbon::today())
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
        ->label('Approve')
        ->color('success')
        ->icon('heroicon-o-check-circle')
        ->visible(fn ($record) => $record->status === 'PENDING')
        ->action(function ($record) {
            DB::transaction(function () use ($record) {
                // 1. Kurangi stok buku
                $record->book->decrement('stock');

                // 2. Update status peminjaman
                $record->update([
                    'status' => 'DIPINJAM',
                    'borrowed_at' => now(),
                ]);
            });
        }),
                Tables\Actions\Action::make('return')
                ->label('Konfirmasi Kembali')
                ->color('info')
                ->icon('heroicon-o-arrow-path')
                ->visible(fn ($record) => $record->status === 'DIPINJAM')
                ->action(function ($record) {
                    DB::transaction(function () use ($record) {
                        // 1. Tambah stok buku kembali
                        $record->book->increment('stock');

                        // 2. Update status
                        $record->update([
                            'status' => 'DIKEMBALIKAN',
                'returned_at' => now(),
            ]);
        });
    })
    ->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBorrowings::route('/'),
            'create' => Pages\CreateBorrowing::route('/create'),
            'edit'   => Pages\EditBorrowing::route('/{record}/edit'),
        ];
    }
}
