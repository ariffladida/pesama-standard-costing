<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaItemResource\Pages;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class CoaItemResource extends Resource
{
    protected static ?string $model = CoaItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'COA: Sawmill (Loji Belah)';
    protected static ?string $modelLabel = 'COA Sawmill';
    protected static ?string $pluralModelLabel = 'COA: Sawmill (Loji Belah)';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('product_type', 'Sawmill');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Maklumat Kod Akaun Sawmill (129 Items)')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kod Akaun')
                            ->placeholder('e.g. 6000/000 atau 7100/001')
                            ->required(),

                        TextInput::make('name')
                            ->label('Keterangan')
                            ->required(),

                        Select::make('classification')
                            ->label('Klasifikasi')
                            ->options([
                                'Variable' => 'Variable',
                                'Fixed' => 'Fixed',
                                'Summary' => 'Summary',
                                'Balance' => 'Balance',
                            ])
                            ->default('Variable')
                            ->required(),

                        Select::make('basis')
                            ->label('Asas')
                            ->options([
                                'Historical' => 'Historical',
                                'Summary' => 'Summary',
                                'Output' => 'Output',
                                'Monthly Budget' => 'Monthly Budget',
                            ])
                            ->default('Historical')
                            ->required(),

                        TextInput::make('standard_rate_per_ton')
                            ->label('Kadar Std/Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->default(0.00)
                            ->required(),

                        Toggle::make('is_flexible')
                            ->label('Fleksibel')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultPaginationPageOption(50)
            ->columns([
                TextColumn::make('code')
                    ->label('Kod Akaun')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('classification')
                    ->label('Klasifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Variable' => 'warning',
                        'Fixed' => 'info',
                        'Summary', 'Balance' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('basis')
                    ->label('Asas')
                    ->badge()
                    ->color('success'),

                TextColumn::make('standard_rate_per_ton')
                    ->label('Kadar Std/Tan')
                    ->money('MYR')
                    ->sortable(),

                IconColumn::make('is_flexible')
                    ->label('Fleksibel')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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