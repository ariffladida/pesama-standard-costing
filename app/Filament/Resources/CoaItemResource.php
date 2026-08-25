<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaItemResource\Pages;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class CoaItemResource extends Resource
{
    protected static ?string $model = CoaItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Carta Akaun (129 COA)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('coa_code')
                    ->label('Kod Akaun (Acc. No.)')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label('Keterangan Akaun (Description)')
                    ->required(),
                Forms\Components\Select::make('cost_type')
                    ->label('Klasifikasi (Unit ID)')
                    ->options([
                        'Fixed' => 'Kos Tetap (Fixed Cost)',
                        'Variable' => 'Kos Berubah (Variable Cost)',
                        'Summary' => 'Header / Subtotal',
                        'Balance' => 'Stok / Balance',
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
            ->columns([
                TextColumn::make('coa_code')->label('Kod Akaun')->sortable()->searchable(),
                TextColumn::make('name')->label('Keterangan')->searchable(),
                TextColumn::make('cost_type')->label('Klasifikasi')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Fixed' => 'info',
                        'Variable' => 'warning',
                        'Summary' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('basis_type')->label('Asas')->badge(),
                TextColumn::make('standard_rate_per_ton')->label('Kadar Std/Tan')->money('MYR')->sortable(),
                IconColumn::make('is_reducible')->label('Fleksibel')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cost_type')->options([
                    'Fixed' => 'Fixed Cost',
                    'Variable' => 'Variable Cost',
                    'Summary' => 'Summary Header',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoaItems::route('/'),
            'create' => Pages\CreateCoaItem::route('/create'),
            'edit' => Pages\EditCoaItem::route('/{record}/edit'),
        ];
    }
}