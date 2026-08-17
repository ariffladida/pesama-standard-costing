<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StCostingResource\Pages;
use App\Models\StCosting;
use App\Models\SystemSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StCostingResource extends Resource
{
    protected static ?string $model = StCosting::class;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationGroup = 'Standard Costing';
    protected static ?string $navigationLabel = 'Sawn Timber';
    protected static ?string $modelLabel = 'Sawn Timber Costing';
    protected static ?string $pluralModelLabel = 'Sawn Timber Costings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $defaultTransport = SystemSetting::where('key', 'fixed_transport_cost')->value('value') ?? 68.00;

        return $form->schema([
            Forms\Components\Section::make('Maklumat Asas & Rekod Stok (Stock & Species Info)')
                ->description('Dipadankan mengikut lajur Tally No, Spesies, dan Kategori pasaran.')
                ->icon('heroicon-o-cube')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('batch_no')
                        ->label('Tally No / Batch No')
                        ->placeholder('Cth: B92751/51')
                        ->required(),

                    Forms\Components\Select::make('species_id')
                        ->relationship('species', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Species (Spesies Kayu)'),

                    Forms\Components\Select::make('category')
                        ->label('Category (Pasaran)')
                        ->options([
                            'Local' => 'Local',
                            'Export' => 'Export',
                        ])
                        ->default('Local')
                        ->native(false)
                        ->required(),
                ]),

            Forms\Components\Section::make('Penetapan Kos Balak & Pembuatan (Upstream Costing)')
                ->description('Pengiraan purata kos pembuatan per tan sebelum dipecahkan kepada gred.')
                ->icon('heroicon-o-calculator')
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('log_cost_per_ton')
                        ->label('Log Cost / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->placeholder('1242.85')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotalAvg($get, $set)),

                    Forms\Components\TextInput::make('transport_cost_per_ton')
                        ->label('Fixed Transport / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->default($defaultTransport)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotalAvg($get, $set)),

                    Forms\Components\TextInput::make('total_avg_cost_per_ton')
                        ->label('Total Base Cost / Tan')
                        ->numeric()
                        ->prefix('RM')
                        ->readOnly()
                        ->dehydrated()
                        ->extraInputAttributes(['class' => 'font-bold text-amber-500']),
                ]),

            Forms\Components\Section::make('Pecahan Kos Mengikut Gred (Grade Breakdown)')
                ->description('Nilai ini memadankan lajur Grade dan Cost/ton* RM dalam analisis margin.')
                ->icon('heroicon-o-queue-list')
                ->schema([
                    Forms\Components\Repeater::make('gradeBreakdowns')
                        ->relationship('gradeBreakdowns')
                        ->schema([
                            Forms\Components\Select::make('grade_id')
                                ->relationship('grade', 'name')
                                ->required()
                                ->label('Grade (Gred Kayu)')
                                ->searchable()
                                ->preload()
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('cost_per_ton')
                                ->label('Cost/ton* RM (Kos Akhir Gred)')
                                ->numeric()
                                ->prefix('RM')
                                ->placeholder('1310.85')
                                ->required()
                                ->columnSpan(2),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->addActionLabel('+ Tambah Gred')
                        ->reorderableWithButtons(),
                ]),
        ]);
    }

    public static function calculateTotalAvg(Get $get, Set $set): void
    {
        $log = floatval($get('log_cost_per_ton') ?? 0);
        $transport = floatval($get('transport_cost_per_ton') ?? 0);
        $total = $log + $transport;
        $set('total_avg_cost_per_ton', number_format($total, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch_no')->label('Tally No')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('species.name')->label('Species')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('category')->label('Category')->badge()->color(fn (string $state): string => $state === 'Export' ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('log_cost_per_ton')->label('Log Cost/Tan')->money('MYR')->sortable(),
                Tables\Columns\TextColumn::make('transport_cost_per_ton')->label('Transport/Tan')->money('MYR'),
                Tables\Columns\TextColumn::make('total_avg_cost_per_ton')->label('Total Base/Tan')->money('MYR')->sortable()->weight('bold')->color('warning'),
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
            'index' => Pages\ListStCostings::route('/'),
            'create' => Pages\CreateStCosting::route('/create'),
            'edit' => Pages\EditStCosting::route('/{record}/edit'),
        ];
    }
}