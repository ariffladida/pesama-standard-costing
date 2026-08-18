<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FjCostingResource\Pages;
use App\Models\FjCosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FjCostingResource extends Resource
{
    protected static ?string $model = FjCosting::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Standard Costing';
    protected static ?string $navigationLabel = 'Finger Joint';
    protected static ?string $modelLabel = 'Finger Joint Costing';
    protected static ?string $pluralModelLabel = 'Finger Joint Costings';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Produk & Kategori Bahan (Product & Source Info)')
                ->description('Pemilihan punca bahan mentah (termasuk Off-Cut) dan spesifikasi profil saiz Finger Joint.')
                ->icon('heroicon-o-cube')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('source_type')
                        ->label('Category / Kategori Bahan Mentah')
                        ->options([
                            'process' => 'Process',
                            'purchase' => 'Purchase (Beli Luar)',
                            'off_cut' => 'Off Cut (Kos Bahan Mentah RM0)',
                        ])
                        ->required()
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if ($state === 'off_cut') {
                                $set('raw_material_cost_per_ton', '0.00');
                            }
                            self::calculateTotal($get, $set);
                        }),

                    Forms\Components\TextInput::make('product_size')
                        ->label('Size (Saiz Produk Akhir)')
                        ->placeholder('Cth: 21mm x 44mm atau 19mm x 43mm')
                        ->required(),
                ]),

            Forms\Components\Section::make('Pengiraan Kos Standard Finger Joint (Finger Joint Costing Calculation)')
                ->description('Pengiraan kos bahan dan kos pemprosesan sambungan jejari per tan.')
                ->icon('heroicon-o-calculator')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('raw_material_cost_per_ton')
                        ->label('Raw Material Cost / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->disabled(fn (Get $get) => $get('source_type') === 'off_cut')
                        ->dehydrated()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),

                    Forms\Components\TextInput::make('mfg_cost_per_ton')
                        ->label('Manufacturing Cost / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->placeholder('0.00')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotal($get, $set)),

                    Forms\Components\TextInput::make('total_cost_per_ton')
                        ->label('Cost/ton* RM (Jumlah Kos Standard Finger Joint)')
                        ->numeric()
                        ->prefix('RM')
                        ->readOnly()
                        ->dehydrated()
                        ->columnSpanFull()
                        ->extraInputAttributes(['class' => 'font-bold text-lg text-amber-500']),
                ]),
        ]);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $raw = floatval($get('raw_material_cost_per_ton') ?? 0);
        $mfg = floatval($get('mfg_cost_per_ton') ?? 0);
        $set('total_cost_per_ton', number_format($raw + $mfg, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_type')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'process' => 'success',
                        'purchase' => 'warning',
                        'off_cut' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'process' => 'Process',
                        'purchase' => 'Purchase Luar',
                        'off_cut' => 'Off Cut (RM0)',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('product_size')->label('Size (Saiz Akhir)')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('raw_material_cost_per_ton')->label('Raw Material Cost/Tan')->money('MYR')->sortable(),
                Tables\Columns\TextColumn::make('mfg_cost_per_ton')->label('Mfg Cost/Tan')->money('MYR')->sortable(),
                Tables\Columns\TextColumn::make('total_cost_per_ton')->label('Cost/ton* RM')->money('MYR')->sortable()->weight('bold')->color('warning'),
                Tables\Columns\TextColumn::make('created_at')->label('Tarikh Ditambah')->dateTime('d/m/Y')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFjCostings::route('/'),
            'create' => Pages\CreateFjCosting::route('/create'),
            'edit' => Pages\EditFjCosting::route('/{record}/edit'),
        ];
    }
}