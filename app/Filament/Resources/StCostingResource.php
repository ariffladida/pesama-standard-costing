<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StCostingResource\Pages;
use App\Models\StCosting;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class StCostingResource extends Resource
{
    protected static ?string $model = StCosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationGroup = 'Standard Costing';
    protected static ?string $navigationLabel = 'Sawn Timber';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. CAMPURAN BALAK & PELBAGAI BATCH
                Section::make('1. Campuran Balak & Batch (Multiple Log Inputs)')
                    ->description('Tambah satu atau lebih baris Batch & Spesies untuk dikira purata kos bahan mentah.')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('batch_no')
                                    ->label('Batch No')
                                    ->placeholder('Cth: Batch 1')
                                    ->required()
                                    ->columnSpan(2),

                                Select::make('species_id')
                                    ->label('Spesies Balak')
                                    ->relationship('species', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(3),

                                Select::make('category')
                                    ->label('Category / Log Grade')
                                    ->options([
                                        'Local' => 'Local Grade',
                                        'Export' => 'Export Prime',
                                        'Reject' => 'Off-Cut / Reject',
                                    ])
                                    ->default('Local')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('volume_ton')
                                    ->label('Kuantiti (Tan)')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $vol = (float) $get('volume_ton');
                                        $cost = (float) $get('log_cost_per_ton');
                                        $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                    })
                                    ->columnSpan(2),

                                TextInput::make('log_cost_per_ton')
                                    ->label('Log Cost / Tan (RM)')
                                    ->numeric()
                                    ->prefix('RM')
                                    ->reactive()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $vol = (float) $get('volume_ton');
                                        $cost = (float) $get('log_cost_per_ton');
                                        $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                    })
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('subtotal_cost')
                                    ->label('Subtotal (RM)')
                                    ->numeric()
                                    ->prefix('RM')
                                    ->readOnly()
                                    ->columnSpan(1),
                            ])
                            ->columns(12)
                            ->defaultItems(1)
                            ->addActionLabel('+ Tambah Batch / Spesies')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                self::recalculateTotals($set, $get);
                            }),
                    ]),

                // 2. STRUKTUR KOS PEMBUATAN & PECAHAN COA
                Section::make('2. Struktur Kos Pembuatan & Pecahan 129 COA')
                    ->description('Kos dikira secara automatik daripada 129 kod akaun pembuatan dengan pengasingan Kos Tetap vs Kos Berubah.')
                    ->schema([
                        TextInput::make('log_cost_per_ton')
                            ->label('Purata Kos Balak / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->readOnly()
                            ->helperText('Dikira secara purata wajaran daripada senarai batch di atas.'),

                        TextInput::make('fixed_cost_per_ton')
                            ->label('Kos Tetap (Fixed COA)')
                            ->numeric()
                            ->prefix('RM')
                            ->default(fn () => CoaItem::where('cost_type', 'Fixed')->sum('standard_rate_per_ton'))
                            ->readOnly(),

                        TextInput::make('variable_cost_per_ton')
                            ->label('Kos Berubah (Variable COA)')
                            ->numeric()
                            ->prefix('RM')
                            ->default(fn () => CoaItem::where('cost_type', 'Variable')->sum('standard_rate_per_ton'))
                            ->readOnly(),

                        TextInput::make('manufacturing_cost_per_ton')
                            ->label('Base Manufacturing Cost / Tan ("Nilai Sedebak")')
                            ->numeric()
                            ->prefix('RM')
                            ->readOnly()
                            ->suffixAction(
                                FormAction::make('viewCoaDetails')
                                    ->label('🔍 Drill-Down COA')
                                    ->icon('heroicon-m-table-cells')
                                    ->modalHeading('Pecahan Terperinci 129 Kod Akaun Pembuatan')
                                    ->modalDescription('Senarai lengkap akaun pembuatan, kategori Fixed/Variable, dan fleksibiliti pengurangan.')
                                    ->modalSubmitAction(false)
                                    ->modalContent(function () {
                                        return view('filament.modals.coa-breakdown-table', [
                                            'coas' => CoaItem::all(),
                                        ]);
                                    })
                            ),

                        TextInput::make('total_avg_cost_per_ton')
                            ->label('Total Base Cost / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->readOnly()
                            ->helperText('Purata Kos Balak + Base Manufacturing Cost'),
                    ])->columns(3),

                // 3. SIMULASI PENGURANGAN KOS & MITIGASI
                Section::make('Simulasi Pengurangan Kos (Cost Reduction & Mitigation)')
                    ->description('Uji arahan penjimatan kos terhadap akaun yang fleksibel sahaja.')
                    ->collapsed()
                    ->schema([
                        Select::make('simulation_cut_percent')
                            ->label('Peratus Pengurangan Kos Fleksibel (%)')
                            ->options([
                                '0' => '0% (Standard Asal)',
                                '5' => 'Potong 5%',
                                '10' => 'Potong 10%',
                                '15' => 'Potong 15%',
                                '20' => 'Potong 20%',
                            ])
                            ->default('0')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $cut = (float) $state / 100;

                                $fixedNon = CoaItem::where('cost_type', 'Fixed')->where('is_reducible', false)->sum('standard_rate_per_ton');
                                $fixedRed = CoaItem::where('cost_type', 'Fixed')->where('is_reducible', true)->sum('standard_rate_per_ton') * (1 - $cut);

                                $varNon = CoaItem::where('cost_type', 'Variable')->where('is_reducible', false)->sum('standard_rate_per_ton');
                                $varRed = CoaItem::where('cost_type', 'Variable')->where('is_reducible', true)->sum('standard_rate_per_ton') * (1 - $cut);

                                $set('fixed_cost_per_ton', number_format($fixedNon + $fixedRed, 2, '.', ''));
                                $set('variable_cost_per_ton', number_format($varNon + $varRed, 2, '.', ''));
                                self::recalculateTotals($set, $get);
                            }),
                    ]),

                // 4. PELARASAN PROSES TAMBAHAN (KD & CUTTING)
                Section::make('3. Pelarasan Kos Proses Tambahan (Adjust Cost / Tan)')
                    ->schema([
                        Toggle::make('has_kd')
                            ->label('Proses Kiln Drying (KD)')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (!$state) $set('kd_cost_per_ton', 0);
                                self::recalculateTotals($set, $get);
                            }),

                        TextInput::make('kd_cost_per_ton')
                            ->label('Kos KD / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->visible(fn (Get $get) => $get('has_kd'))
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        Toggle::make('has_cutting')
                            ->label('Proses Cutting / Potong')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (!$state) $set('cutting_cost_per_ton', 0);
                                self::recalculateTotals($set, $get);
                            }),

                        TextInput::make('cutting_cost_per_ton')
                            ->label('Kos Potong / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->visible(fn (Get $get) => $get('has_cutting'))
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        TextInput::make('adjusted_cost_per_ton')
                            ->label('Adjusted Total Cost / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->readOnly(),
                    ])->columns(3),

                // 5. ALIRAN KERJA MARGIN & BENCHMARK HARGA
                Section::make('4. Penentuan Margin & Harga Jualan Benchmark')
                    ->schema([
                        Select::make('market_type')
                            ->label('Jenis Pasaran')
                            ->options([
                                'Local' => 'Local (Markup %)',
                                'Export' => 'Export (Reverse Calculation)',
                            ])
                            ->default('Local')
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        Select::make('target_margin_percentage')
                            ->label('Margin Sasaran (%)')
                            ->options([
                                '10' => '10%',
                                '15' => '15%',
                                '20' => '20%',
                                '25' => '25%',
                                '30' => '30%',
                            ])
                            ->default('15')
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),

                        TextInput::make('benchmark_price_per_ton')
                            ->label('Price List Benchmark / Tan (RM)')
                            ->numeric()
                            ->prefix('RM')
                            ->readOnly(),

                        TextInput::make('actual_selling_price_per_ton')
                            ->label('Harga Jualan Sebenar / Tan (Jika Diturunkan)')
                            ->numeric()
                            ->prefix('RM')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $benchmark = (float) $get('benchmark_price_per_ton');
                                if ($state && (float)$state < $benchmark) {
                                    $set('approval_status', 'Pending Approval');
                                } else {
                                    $set('approval_status', 'Approved');
                                }
                            }),

                        Textarea::make('down_value_reason')
                            ->label('Alasan Penurunan Harga (Justifikasi Bertulis)')
                            ->visible(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                            ->required(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                            ->columnSpanFull(),
                    ])->columns(3),

                // 6. PECAHAN GRED AKHIR
                Section::make('5. Pecahan Kos Mengikut Gred (Grade Breakdown)')
                    ->schema([
                        Repeater::make('gradeBreakdowns')
                            ->relationship()
                            ->schema([
                                Select::make('grade_id')
                                    ->relationship('grade', 'name')
                                    ->required(),
                                TextInput::make('cost_per_ton')
                                    ->label('Kos Akhir Gred / Tan (RM)')
                                    ->numeric()
                                    ->prefix('RM')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('+ Tambah Gred'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('total_avg_cost_per_ton')->label('Base Cost/Tan (RM)')->money('MYR')->sortable(),
                TextColumn::make('adjusted_cost_per_ton')->label('Adjusted/Tan (RM)')->money('MYR')->sortable(),
                TextColumn::make('benchmark_price_per_ton')->label('Benchmark (RM)')->money('MYR')->sortable(),
                TextColumn::make('market_type')->badge(),
                TextColumn::make('approval_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Approved' => 'success',
                        'Pending Approval' => 'warning',
                        'Rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function recalculateTotals(Set $set, Get $get): void
    {
        // 1. Purata wajaran kos balak
        $items = $get('items') ?? [];
        $totalCost = 0;
        $totalVolume = 0;

        foreach ($items as $item) {
            $vol = (float) ($item['volume_ton'] ?? 0);
            $cost = (float) ($item['log_cost_per_ton'] ?? 0);
            $totalCost += ($vol * $cost);
            $totalVolume += $vol;
        }

        $avgLogCost = $totalVolume > 0 ? ($totalCost / $totalVolume) : 0;
        $set('log_cost_per_ton', number_format($avgLogCost, 2, '.', ''));

        // 2. Base Manufacturing Cost
        $fixed = (float) $get('fixed_cost_per_ton');
        $variable = (float) $get('variable_cost_per_ton');
        $mfgCost = $fixed + $variable;
        $set('manufacturing_cost_per_ton', number_format($mfgCost, 2, '.', ''));

        // 3. Total Base Cost
        $totalBase = $avgLogCost + $mfgCost;
        $set('total_avg_cost_per_ton', number_format($totalBase, 2, '.', ''));

        // 4. Adjusted Cost (KD & Cutting)
        $kd = $get('has_kd') ? (float) $get('kd_cost_per_ton') : 0;
        $cutting = $get('has_cutting') ? (float) $get('cutting_cost_per_ton') : 0;
        $adjustedCost = $totalBase + $kd + $cutting;
        $set('adjusted_cost_per_ton', number_format($adjustedCost, 2, '.', ''));

        // 5. Benchmark Price
        $marginPct = (float) $get('target_margin_percentage') / 100;
        $marketType = $get('market_type');

        if ($marketType === 'Export') {
            $benchmark = ($adjustedCost > 0 && (1 - $marginPct) > 0) ? ($adjustedCost / (1 - $marginPct)) : $adjustedCost;
        } else {
            $benchmark = $adjustedCost * (1 + $marginPct);
        }

        $set('benchmark_price_per_ton', number_format($benchmark, 2, '.', ''));
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