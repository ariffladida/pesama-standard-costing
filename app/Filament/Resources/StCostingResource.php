<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StCostingResource\Pages;
use App\Models\StCosting;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Grid;
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
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // =========================================================================
                        // LAJUR KIRI: KAWASAN INPUT PENGGUNA (2/3 SKRIN)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['lg' => 2])->schema([
                            
                            // 1. INPUT CAMPURAN BALAK
                            Section::make('1. Campuran Balak & Batch (Multiple Log Inputs)')
                                ->description('Kemasukan data batch belian balak untuk purata wajaran kos.')
                                ->icon('heroicon-o-archive-box')
                                ->schema([
                                    Repeater::make('items')
                                        ->relationship('items')
                                        ->schema([
                                            TextInput::make('batch_no')
                                                ->label('Batch No')
                                                ->placeholder('B1')
                                                ->required()
                                                ->columnSpan(2),

                                            Select::make('species_id')
                                                ->label('Spesies Balak')
                                                ->relationship('species', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->columnSpan(4),

                                            Select::make('category')
                                                ->label('Kategori Pasaran')
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
                                                ->label('Kos Balak (RM/Tan)')
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
                                        ])
                                        ->columns(12)
                                        ->defaultItems(1)
                                        ->addActionLabel('+ Tambah Batch / Spesies')
                                        ->reactive()
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),
                                ]),

                            // 2. PELARASAN PROSES & SURCHARGE
                            Section::make('2. Pelarasan Proses Tambahan (Adjust Cost)')
                                ->icon('heroicon-o-wrench-screwdriver')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Toggle::make('has_kd')
                                            ->label('Proses Kiln Drying (KD)')
                                            ->inline(false)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if (!$state) $set('kd_cost_per_ton', 0);
                                                self::recalculateTotals($set, $get);
                                            }),

                                        TextInput::make('kd_cost_per_ton')
                                            ->label('Kos KD / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->visible(fn (Get $get) => $get('has_kd'))
                                            ->reactive()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),
                                    ]),

                                    Grid::make(2)->schema([
                                        Toggle::make('has_cutting')
                                            ->label('Proses Cutting / Potong')
                                            ->inline(false)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if (!$state) $set('cutting_cost_per_ton', 0);
                                                self::recalculateTotals($set, $get);
                                            }),

                                        TextInput::make('cutting_cost_per_ton')
                                            ->label('Kos Potong / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->visible(fn (Get $get) => $get('has_cutting'))
                                            ->reactive()
                                            ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateTotals($set, $get)),
                                    ]),
                                ]),

                            // 3. TETAPAN MARGIN & PENETAPAN HARGA JUALAN
                            Section::make('3. Penetapan Jualan & Aliran Kelulusan')
                                ->icon('heroicon-o-banknotes')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('market_type')
                                            ->label('Pasaran Sasaran')
                                            ->options([
                                                'Local' => 'Local (Markup %)',
                                                'Export' => 'Export (Reverse %)',
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
                                    ]),

                                    Grid::make(2)->schema([
                                        TextInput::make('actual_selling_price_per_ton')
                                            ->label('Harga Jualan Sebenar / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->placeholder('Isi jika berbeza dengan benchmark')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $benchmark = (float) $get('benchmark_price_per_ton');
                                                if ($state && (float)$state < $benchmark) {
                                                    $set('approval_status', 'Pending Approval');
                                                } else {
                                                    $set('approval_status', 'Approved');
                                                }
                                            }),

                                        TextInput::make('approval_status')
                                            ->label('Status Kelulusan Harga')
                                            ->default('Approved')
                                            ->readOnly()
                                            ->extraInputAttributes(fn (Get $get) => [
                                                'class' => $get('approval_status') === 'Pending Approval' ? 'text-amber-400 font-bold' : 'text-emerald-400 font-bold',
                                            ]),
                                    ]),

                                    Textarea::make('down_value_reason')
                                        ->label('Justifikasi Penurunan Harga (Down-Value Justification)')
                                        ->visible(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                                        ->required(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                                        ->placeholder('Sila nyatakan alasan teknikal atau pasaran mengapa harga jualan diturunkan bawah benchmark...'),
                                ]),

                            // 4. PECAHAN GRED AKHIR
                            Section::make('4. Pecahan Kos Mengikut Gred (Grade Breakdown)')
                                ->icon('heroicon-o-squares-2x2')
                                ->schema([
                                    Repeater::make('gradeBreakdowns')
                                        ->relationship()
                                        ->schema([
                                            Select::make('grade_id')
                                                ->label('Gred Kayu')
                                                ->relationship('grade', 'name')
                                                ->required()
                                                ->columnSpan(2),
                                            TextInput::make('cost_per_ton')
                                                ->label('Kos Akhir Gred / Tan (RM)')
                                                ->numeric()
                                                ->prefix('RM')
                                                ->required()
                                                ->columnSpan(2),
                                        ])
                                        ->columns(4)
                                        ->addActionLabel('+ Tambah Gred Kayu'),
                                ]),
                        ]),

                        // =========================================================================
                        // LAJUR KANAN: EXECUTIVE SUMMARY & COA DRILL-DOWN (1/3 SKRIN STICKY)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['lg' => 1])->schema([
                            Section::make('Ringkasan Kos & Benchmark')
                                ->icon('heroicon-o-calculator')
                                ->description('Kiraan automatik berpusat.')
                                ->schema([
                                    TextInput::make('log_cost_per_ton')
                                        ->label('Purata Kos Balak')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->readOnly(),

                                    TextInput::make('manufacturing_cost_per_ton')
                                        ->label('Kos Pembuatan (129 COA)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->readOnly()
                                        ->suffixAction(
                                            FormAction::make('viewCoaDetails')
                                                ->label('Drill-Down')
                                                ->icon('heroicon-m-table-cells')
                                                ->modalHeading('Pecahan 129 Kod Akaun Pembuatan')
                                                ->modalSubmitAction(false)
                                                ->modalContent(fn () => view('filament.modals.coa-breakdown-table', [
                                                    'coas' => CoaItem::all(),
                                                ]))
                                        ),

                                    TextInput::make('total_avg_cost_per_ton')
                                        ->label('Total Base Cost / Tan')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->readOnly(),

                                    TextInput::make('adjusted_cost_per_ton')
                                        ->label('Adjusted Cost / Tan (Siap KD)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->readOnly(),

                                    TextInput::make('benchmark_price_per_ton')
                                        ->label('Harga Benchmark Sasaran')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->readOnly()
                                        ->extraInputAttributes(['class' => 'font-bold text-emerald-400 text-lg']),
                                ]),

                            // SIMULASI PENJIMATAN KOS
                            Section::make('Simulasi Penjimatan (Mitigation)')
                                ->icon('heroicon-o-presentation-chart-line')
                                ->collapsed()
                                ->schema([
                                    Select::make('simulation_cut_percent')
                                        ->label('Potong Kos Fleksibel')
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

                                    TextInput::make('fixed_cost_per_ton')
                                        ->label('Kos Tetap (Fixed)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(fn () => CoaItem::where('cost_type', 'Fixed')->sum('standard_rate_per_ton'))
                                        ->readOnly(),

                                    TextInput::make('variable_cost_per_ton')
                                        ->label('Kos Berubah (Variable)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(fn () => CoaItem::where('cost_type', 'Variable')->sum('standard_rate_per_ton'))
                                        ->readOnly(),
                                ]),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('total_avg_cost_per_ton')->label('Base Cost (RM)')->money('MYR')->sortable(),
                TextColumn::make('adjusted_cost_per_ton')->label('Adjusted (RM)')->money('MYR')->sortable(),
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

        $fixed = (float) $get('fixed_cost_per_ton');
        $variable = (float) $get('variable_cost_per_ton');
        $mfgCost = $fixed + $variable;
        $set('manufacturing_cost_per_ton', number_format($mfgCost, 2, '.', ''));

        $totalBase = $avgLogCost + $mfgCost;
        $set('total_avg_cost_per_ton', number_format($totalBase, 2, '.', ''));

        $kd = $get('has_kd') ? (float) $get('kd_cost_per_ton') : 0;
        $cutting = $get('has_cutting') ? (float) $get('cutting_cost_per_ton') : 0;
        $adjustedCost = $totalBase + $kd + $cutting;
        $set('adjusted_cost_per_ton', number_format($adjustedCost, 2, '.', ''));

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