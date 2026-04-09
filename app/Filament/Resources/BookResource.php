<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;



class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $modelLabel = 'Buku';

    protected static ?string $pluralLabel = 'Buku';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('category');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Buku')
                    ->schema([

                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->disabledOn('edit')
                            ->live(onBlur: true),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'nama')
                            ->getOptionLabelFromRecordUsing(fn($record)
                            => "
                                    <div class='flex items-center gap-2'>
                                        <div class='w-3 h-3 rounded-full' style='background-color: {$record->color}'></div>
                                        <span>{$record->nama}</span>
                                    </div>
                                ")
                            ->allowHtml()
                            ->searchable()
                            ->preload()
                            ->disabledOn('edit')
                            ->live(onBlur: true)
                            ->placeholder('Pilih Kategori')
                            ->noSearchResultsMessage('Kategori tidak ditemukan')
                            ->searchingMessage('Sedang mencari kategori...')
                            ->loadingMessage('Sedang memuat...')
                            ->searchPrompt('Ketik untuk mencari...')
                            ->required(),

                        Forms\Components\TextInput::make('penulis')
                            ->label('Penulis')
                            ->required()
                            ->maxLength(255)
                            ->disabledOn('edit')
                            ->live(onBlur: true),

                        Forms\Components\TextInput::make('penerbit')
                            ->label('Penerbit')
                            ->maxLength(255)
                            ->disabledOn('edit')
                            ->live(onBlur: true)
                            ->required(),

                        Forms\Components\TextInput::make('halaman')
                            ->label('Jumlah Halaman')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->disabledOn('edit')
                            ->live(onBlur: true),

                        Forms\Components\TextInput::make('tahun_terbit')
                            ->label('Tahun Terbit')
                            ->required()
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(now()->year)
                            ->disabledOn('edit')
                            ->live(onBlur: true),

                        Forms\Components\TextInput::make('stok')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0),

                        Forms\Components\TextInput::make('isbn')
                            ->label('ISBN')
                            ->mask('978-9-99999-999-9')
                            ->unique(Book::class, 'isbn', ignoreRecord: true)
                            ->required()
                            ->columnSpanFull()
                            ->disabledOn('edit')
                            ->live(onBlur: true),

                            Forms\Components\Toggle::make('is_active')
                                ->required()
                                ->label('Aktif')
                                ->default(true),

                        Forms\Components\Placeholder::make('barcode_preview')
                            ->label('Preview Barcode')
                            ->content(function ($record) {
                                if (!$record || !$record->isbn) {
                                    return new \Illuminate\Support\HtmlString("
                                    <div class='flex items-center gap-3 p-4 border border-dashed border-gray-300 rounded-xl bg-gray-50/50'>
                                        <div class='p-2 bg-gray-200 rounded-full'>
                                            <svg class='w-5 h-5 text-gray-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='code-bracket-square' />
                                                <path d='M7 7h2v10H7zm4 0h3v10h-3zm5 0h1v10h-1z' />
                                            </svg>
                                        </div>
                                        <span class='text-sm text-gray-500 font-medium italic'>Input ISBN yang valid untuk melihat pratinjau barcode...</span>
                                    </div>
                                ");
                                                    }

                                                    return new \Illuminate\Support\HtmlString("
                                <div class='group relative flex flex-col items-start gap-3 transition-all duration-300'>
                                    <div class='flex items-center gap-2 mb-1'>
                                        <span class='flex h-2 w-2 rounded-full bg-success-500 animate-pulse'></span>
                                        <span class='text-[10px] font-bold uppercase tracking-wider text-gray-400'>Live Preview Generated</span>
                                    </div>

                                    <div class='bg-white p-4 shadow-sm border border-gray-200 rounded-2xl hover:shadow-md transition-shadow duration-300'>
                                        <img src='https://bwipjs-api.metafloor.com/?bcid=isbn&text={$record->isbn}&includetext&scale=3'
                                            alt='Barcode'
                                            class='max-w-full'
                                            style='height: 85px; image-rendering: pixelated;'>
                                    </div>

                                    <p class='text-[11px] text-gray-500 italic px-1'>
                                        * Barcode ini menggunakan format standar <b>ISBN-13</b>
                                    </p>
                                </div>
                            ");
                            }) ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Konten')
                    ->schema([

                        Forms\Components\Textarea::make('sinopsis')
                            ->label('Sinopsis')
                            ->columnSpanFull()
                            ->rows(5),

                        Forms\Components\FileUpload::make('cover')
                            ->label('Sampul')
                            ->image()
                            ->disk('public')
                            ->directory('covers')
                            ->visibility('public')
                            ->imagePreviewHeight('200')
                            ->maxSize(2048)
                            ->live()
                            ->fetchFileInformation(false)
                            ->preserveFilenames(),

                        Forms\Components\FileUpload::make('ebook')
                            ->label('E-Book')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('ebooks')
                            ->disk('public')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(10240),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->width(50)
                    ->height(75)
                    ->disk('public')
                    ->label('Sampul')
                    ->defaultImageUrl(url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRImCbYfPnW4ZHHiXqBb-GIVqeujvJbr3EvcQ&s')),

                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable()
                    ->label('Judul')
                    ->limit(40),

                Tables\Columns\TextColumn::make('penulis')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->formatStateUsing(function ($state, $record) {
                        $hex = $record->category?->color ?? '#64748b';


                        $hex = str_replace('#', '', $hex);


                        $r = hexdec(substr($hex, 0, 2));
                        $g = hexdec(substr($hex, 2, 2));
                        $b = hexdec(substr($hex, 4, 2));


                        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;


                        $textColor = ($yiq >= 128) ? '#000000' : '#ffffff';

                        return new \Illuminate\Support\HtmlString("
                            <span class='px-3 py-1 text-xs font-bold rounded-full shadow-sm'
                                style='background-color: #{$hex}; color: {$textColor}; border: 1px solid rgba(0,0,0,0.1)'>
                                " . e(strtoupper($state)) . "
                            </span>
                        ");
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('isbn')
                    ->label('Barcode ISBN')
                    ->formatStateUsing(fn($state) => $state ? new \Illuminate\Support\HtmlString("
                        <img src='https://bwipjs-api.metafloor.com/?bcid=isbn&text={$state}&includetext'
                            style='height: 50px; width: auto; image-rendering: pixelated;'>
                    ") : '-')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->label('Tahun Terbit')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn($record) => $record->stok > 0 ? 'success' : 'danger')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'nama')
                    ->label('Kategori'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                Tables\Filters\Filter::make('stok_habis')
                    ->label('Stok Habis')
                    ->query(fn($query) => $query->where('stok', 0)),

                Tables\Filters\TrashedFilter::make()
                ->label('Status Arsip')
                ->options([
                    'onlyTrashed' => 'Hanya Arsip',
                    'withTrashed' => 'Termasuk Arsip',
                    'withoutTrashed' => 'Tanpa Arsip',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label('Pratinjau'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah Buku')
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->label('Arsipkan Buku')
                    ->modalHeading(fn ($record) => "Arsipkan Buku {$record->judul}?")
                    ->modalDescription('Apakah Anda yakin ingin mengarsipkan buku ini?')
                    ->successNotificationTitle('Buku Diarsipkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->modalHeading(fn ($record) => "Hapus Permanen Buku {$record->judul}?")
                    ->modalDescription('Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus buku ini secara permanen?')
                    ->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan Buku')
                    ->modalHeading(fn ($record) => "Pulihkan Buku {$record->judul}?")
                    ->modalDescription('Apakah Anda yakin ingin memulihkan buku ini?')
                    ->successNotificationTitle('Buku Dipulihkan'),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
