<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaMouldingFjResource\Pages;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class CoaMouldingFjResource extends Resource
{
    protected static ?string $model = CoaItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'COA: Secondary Plant (Moulding & FJ)';
    protected static ?string $modelLabel = 'COA Secondary Plant';
    protected static ?string $pluralModelLabel = 'COA: Secondary Plant (Moulding & FJ)';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('product_type', 'Moulding_FJ');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('coa_code')
                    ->label('Kod Akaun Secondary (Moulding/FJ)')
                    ->placeholder('Cth: SEC-5001 / MLD-01')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('name')
                    ->label('Keterangan Akaun (Description)')
                    ->placeholder('Cth: DIRECT LABOUR - MOULDING PLANT')
                    ->required(),

                Forms\Components\Select::make('cost_type')
                    ->label('Klasifikasi Kos')
                    ->options([
                        'Fixed' => 'Kos Tetap (Fixed Cost)',
                        'Variable' => 'Kos Berubah (Variable Cost)',
                        'Summary' => 'Header / Subtotal',
                        'Balance' => 'Stok / Baki',
                    ])
                    ->required(),

                Forms\Components\Select::make('basis_type')
                    ->label('Asas Penetapan')
                    ->options([
                        'Contract' => 'Kadar Kontrak Tetap',
                        'Historical' => 'Data Sejarah / Formula',
                        'Summary' => 'Kiraan Ringkasan',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('standard_rate_per_ton')
                    ->label('Kadar Standard / Tan (RM)')
                    ->numeric()
                    ->prefix('RM')
                    ->default(0.00),

                Forms\Components\Toggle::make('is_reducible')
                    ->label('Boleh Dikurangkan (Fleksibel)')
                    ->default(true),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('coa_code')
                    ->label('Kod Akaun')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make('name')
                    ->label('Keterangan Akaun')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('cost_type')
                    ->label('Klasifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Fixed' => 'info',
                        'Variable' => 'warning',
                        'Summary' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('basis_type')
                    ->label('Asas')
                    ->badge(),
                TextColumn::make('standard_rate_per_ton')
                    ->label('Kadar Std / Tan')
                    ->money('MYR')
                    ->sortable(),
                IconColumn::make('is_reducible')
                    ->label('Fleksibel')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cost_type')
                    ->options([
                        'Fixed' => 'Fixed Cost',
                        'Variable' => 'Variable Cost',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoaMouldingFjs::route('/'),
            'create' => Pages\CreateCoaMouldingFj::route('/create'),
            'edit' => Pages\EditCoaMouldingFj::route('/{record}/edit'),
        ];
    }
}