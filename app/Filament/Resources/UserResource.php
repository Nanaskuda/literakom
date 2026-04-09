<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Member';

    protected static ?string $pluralModelLabel = 'Member';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
    return parent::getEloquentQuery()->where('role', 'member');
    }

    public static function form(Form $form): Form
    {
       return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('no_id')
                    ->label('No. ID')
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('kelas')
                    ->options(['10', '11', '12'])
                    ->required(),

                Forms\Components\Select::make('jurusan')
                    ->options(['RPL' => 'Rekayasa Perangkat Lunak (RPL)', 'TKJ' => 'Teknik Komputer dan Jaringan (TKJ)', 'DKV' => 'Desain Komunikasi Visual (DKV)', 'PSPT' => 'Produksi dan Siaran Program Televisi (PSPT)'])
                    ->required(),

                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('no_telepon')
                    ->tel()
                    ->maxLength(20),

                Forms\Components\Select::make('role')
                    ->options(['admin' => 'Admin', 'member' => 'Member'])
                    ->default('member')
                    ->required(),

                    Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create')
                    ->label('Password')
                    ->helperText('Kosongkan jika tidak ingin mengubah password'),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Aktif'),
            ])->columns(2),
        ]);
    }

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_id')
                    ->label('No. ID')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jurusan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('username')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('book_count')
                    ->label('Total Pinjam')
                    ->alignCenter(),
            ])

            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleActive')
                    ->label(fn ($record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                    ->action(fn ($record) => $record->update(['is_active' => !$record->is_active]))
                    ->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
