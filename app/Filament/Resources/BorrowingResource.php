<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingResource\Pages;
use App\Models\Borrowing;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Peminjaman';
    protected static ?string $pluralModelLabel = 'Peminjaman';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = Borrowing::pending()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    // ───────────────── FORM ─────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Peminjaman')
                ->schema([

                    Forms\Components\Select::make('user_id')
                        ->relationship(
                            name: 'user',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn (Builder $query) =>
                                $query->where('role', 'member')
                        )
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('book_id')
                        ->relationship('book', 'judul')
                        ->searchable()
                        ->required(),

                    // ❌ Tidak boleh diinput manual
                    Forms\Components\DatePicker::make('tanggal_pinjam')
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\DatePicker::make('tanggal_kembali')
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\DatePicker::make('tanggal_dikembalikan')
                        ->disabled(),

                    Forms\Components\Select::make('status')
                        ->options(Borrowing::statusOptions())
                        ->disabled()
                        ->default(Borrowing::STATUS_PENDING),

                ])->columns(2),

            Forms\Components\Section::make('Catatan')
                ->schema([
                    Forms\Components\Textarea::make('catatan')
                        ->rows(2)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('catatan_admin')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // ───────────────── TABLE ─────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable(),

                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Buku')
                    ->limit(35),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->date('d M Y')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->color(fn ($record) =>
                        $record->isTerlambat() ? 'danger' : null
                    ),
                Tables\Columns\TextColumn::make('tanggal_dikembalikan')
                    ->date('d M Y')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($record) => $record->statusLabel())
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->isTerlambat() => 'danger',
                        $record->status === Borrowing::STATUS_PENDING => 'warning',
                        $record->status === Borrowing::STATUS_DIPINJAM => 'success',
                        $record->status === Borrowing::STATUS_DITOLAK => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan Pada')
                    ->since()
                    ->toggleable(),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Borrowing::statusOptions()),

                Tables\Filters\Filter::make('terlambat')
                    ->label('Terlambat')
                    ->query(fn ($query) => $query->terlambat()),
            ])

            ->actions([

                // ─── APPROVE ─────────────────
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->visible(fn ($record) => $record->isPending())
                    ->form([
                        Forms\Components\TextInput::make('durasi')
                            ->numeric()
                            ->default(7)
                            ->minValue(1)
                            ->maxValue(30)
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {

                        if (!$record->book?->isAvailable()) {
                            Notification::make()
                                ->title('Stok habis')
                                ->danger()
                                ->send();
                            return;
                        }

                        DB::transaction(function () use ($record, $data) {

                            $tanggalPinjam = Carbon::today();
                            $durasi = max(1, min(30, (int) $data['durasi']));

                            $record->update([
                                'status' => Borrowing::STATUS_DIPINJAM,
                                'tanggal_pinjam' => $tanggalPinjam,
                                'tanggal_kembali' => $tanggalPinjam->copy()->addDays($durasi),
                                'approved_at' => now(),
                            ]);

                            $record->book->decrement('stok');
                        });

                        Notification::make()
                            ->title('Disetujui')
                            ->success()
                            ->send();
                    }),

                // ─── TOLAK ─────────────────
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn ($record) => $record->isPending())
                    ->form([
                        Forms\Components\Textarea::make('catatan_admin')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {

                        $record->update([
                            'status' => Borrowing::STATUS_DITOLAK,
                            'catatan_admin' => $data['catatan_admin'],
                            'rejected_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Ditolak')
                            ->warning()
                            ->send();
                    }),

                // ─── KONFIRMASI KEMBALI ─────────────────
                Tables\Actions\Action::make('kembali')
                    ->label('Kembalikan')
                    ->color('info')
                    ->visible(fn ($record) => $record->isDipinjam())
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        DB::transaction(function () use ($record) {

                            $record->update([
                                'status' => Borrowing::STATUS_DIKEMBALIKAN,
                                'tanggal_dikembalikan' => Carbon::today(),
                            ]);

                            $record->book->increment('stok');
                        });

                        Notification::make()
                            ->title('Dikembalikan')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowings::route('/'),
            'create' => Pages\CreateBorrowing::route('/create'),
        ];
    }
}
