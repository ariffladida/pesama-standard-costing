<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MouldingCostingResource\Pages;
use App\Models\MouldingCosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MouldingCostingResource extends Resource
{
    protected static ?string $model = MouldingCosting::class;
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationGroup = 'Standard Costing';
    protected static ?string $navigationLabel = 'Moulding';
    protected static ?string $modelLabel = 'Moulding Costing';
    protected static ?string $pluralModelLabel = 'Moulding Costings';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Produk & Sumber Bahan (Product & Source Info)')
                ->description('Pemilihan punca bahan mentah dan saiz profil  standard.')
                ->icon('heroicon-o-cube')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('source_type')
                        ->label('Category / Punca Bahan Mentah')
                        ->options([
                            'process' => 'Process Sendiri',
                            'purchase' => 'Beli Luar (Purchase)',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('product_size')
                        ->label('Size (Saiz Produk Akhir)')
                        ->options([
                            '28mm x 133mm' => '28 mm x 133 mm',
                            '28mm x 145mm' => '28 mm x 145 mm',
                        ])
                        ->required()
                        ->native(false),
                ]),

            Forms\Components\Section::make('Pengiraan Kos Standard Moulding (Moulding Costing Calculation)')
                ->description('Struktur kos bahan mentah dan kos operasi pembuatan kilang per tan.')
                ->icon('heroicon-o-calculator')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('raw_material_cost_per_ton')
                        ->label('Raw Material Cost / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->placeholder('0.00')
                        ->required()
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
                        ->label('Cost/ton* RM (Jumlah Kos Standard Moulding)')
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
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'process' => 'Process Sendiri',
                        'purchase' => 'Beli Luar',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('product_size')->label('Size (Saiz Akhir)')->sortable()->weight('bold'),
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
            'index' => Pages\ListMouldingCostings::route('/'),
            'create' => Pages\CreateMouldingCosting::route('/create'),
            'edit' => Pages\EditMouldingCosting::route('/{record}/edit'),
        ];
    }
}