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
use Filament\Forms\Components\Hidden;
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
                        // LAJUR KIRI: KAWASAN INPUT PEGAWAI (2/3 SKRIN)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['lg' => 2])->schema([
                            
                            // 1. INPUT CAMPURAN BALAK (BERSIH TANPA MEDAN CATEGORY)
                            Section::make('1. Campuran Balak & Batch (Log Inputs)')
                                ->description('Kemasukan batch dan kos belian balak untuk purata wajaran kos.')
                                ->icon('heroicon-o-archive-box')
                                ->schema([
                                    Repeater::make('items')
                                        ->relationship('items')
                                        ->schema([
                                            TextInput::make('batch_no')
                                                ->label('Batch No')
                                                ->placeholder('B1')
                                                ->required()
                                                ->columnSpan(3),

                                            Select::make('species_id')
                                                ->label('Spesies Balak')
                                                ->relationship('species', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->columnSpan(5),

                                            TextInput::make('volume_ton')
                                                ->label('Kuantiti (Tan)')
                                                ->numeric()
                                                ->default(1)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Set $set, Get $get, $livewire) {
                                                    $vol = (float) $get('volume_ton');
                                                    $cost = (float) $get('log_cost_per_ton');
                                                    $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                                    self::recalculateTotals($livewire);
                                                })
                                                ->required()
                                                ->columnSpan(2),

                                            TextInput::make('log_cost_per_ton')
                                                ->label('Kos Balak (RM/Tan)')
                                                ->numeric()
                                                ->prefix('RM')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Set $set, Get $get, $livewire) {
                                                    $vol = (float) $get('volume_ton');
                                                    $cost = (float) $get('log_cost_per_ton');
                                                    $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                                    self::recalculateTotals($livewire);
                                                })
                                                ->required()
                                                ->columnSpan(2),

                                            Hidden::make('subtotal_cost')->default(0),
                                        ])
                                        ->columns(12)
                                        ->defaultItems(1)
                                        ->addActionLabel('+ Tambah Batch / Spesies')
                                        ->live()
                                        ->afterStateUpdated(function ($livewire) {
                                            self::recalculateTotals($livewire);
                                        }),
                                ]),

                            // 2. PELARASAN PROSES TAMBAHAN
                            Section::make('2. Pelarasan Proses Tambahan (Adjust Cost)')
                                ->icon('heroicon-o-wrench-screwdriver')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Toggle::make('has_kd')
                                            ->label('Proses Kiln Drying (KD)')
                                            ->inline(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $livewire) {
                                                if (!$state) $set('kd_cost_per_ton', 0);
                                                self::recalculateTotals($livewire);
                                            }),

                                        TextInput::make('kd_cost_per_ton')
                                            ->label('Kos KD / Tan (RM)')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->default(0)
                                            ->visible(fn (Get $get) => (bool) $get('has_kd'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),

                                    Grid::make(2)->schema([
                                        Toggle::make('has_cutting')
                                            ->label('Proses Cutting / Potong')
                                            ->inline(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $livewire) {
                                                if (!$state) $set('cutting_cost_per_ton', 0);
                                                self::recalculateTotals($livewire);
                                            }),

                                        TextInput::make('cutting_cost_per_ton')
                                            ->label('Kos Potong / Tan (RM)')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->default(0)
                                            ->visible(fn (Get $get) => (bool) $get('has_cutting'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),
                                ]),

                            // 3. TETAPAN JUALAN & ALIRAN KELULUSAN
                            Section::make('3. Penetapan Jualan & Aliran Kelulusan')
                                ->icon('heroicon-o-banknotes')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('market_type')
                                            ->label('Pasaran Sasaran')
                                            ->options([
                                                'Local' => 'Local (Markup %)',
                                                'Export' => 'Export (Reverse Margin %)',
                                            ])
                                            ->default('Local')
                                            ->live()
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),

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
                                            ->live()
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),

                                    Grid::make(2)->schema([
                                        TextInput::make('actual_selling_price_per_ton')
                                            ->label('Harga Jualan Sebenar / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->placeholder('Isi jika berbeza dengan benchmark')
                                            ->live(onBlur: true)
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
                                        ->columnSpanFull(),
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
                        // LAJUR KANAN: EXECUTIVE SUMMARY & BENCHMARK (1/3 SKRIN)
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
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('manufacturing_cost_per_ton')
                                        ->label('Kos Pembuatan (129 COA)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(fn () => number_format(
                                            CoaItem::whereNotIn('cost_type', ['Summary', 'Balance'])->sum('standard_rate_per_ton'),
                                            2, '.', ''
                                        ))
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

                                    TextInput::make('total_base_cost_per_ton')
                                        ->label('Total Base Cost / Tan')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('adjusted_cost_per_ton')
                                        ->label('Adjusted Cost / Tan (Siap KD)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('benchmark_price_per_ton')
                                        ->label('Harga Benchmark Sasaran')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly()
                                        ->extraInputAttributes(['class' => 'font-bold text-emerald-400 text-lg']),
                                ]),
                        ]),
                    ]),
            ]);
    }

    public static function recalculateTotals($livewire): void
    {
        $data = $livewire->form->getRawState();

        // 1. Purata wajaran kos balak
        $items = $data['items'] ?? [];
        $totalCost = 0;
        $totalVolume = 0;

        foreach ($items as $item) {
            $vol = (float) ($item['volume_ton'] ?? 0);
            $cost = (float) ($item['log_cost_per_ton'] ?? 0);
            $totalCost += ($vol * $cost);
            $totalVolume += $vol;
        }

        $avgLogCost = $totalVolume > 0 ? ($totalCost / $totalVolume) : 0;
        $livewire->form->fill([
            ...$data,
            'log_cost_per_ton' => number_format($avgLogCost, 2, '.', ''),
        ]);

        // 2. Base Manufacturing Cost
        $mfgCost = (float) ($data['manufacturing_cost_per_ton'] ?? CoaItem::whereNotIn('cost_type', ['Summary', 'Balance'])->sum('standard_rate_per_ton'));

        // 3. Total Base Cost
        $totalBase = $avgLogCost + $mfgCost;

        // 4. Adjusted Cost (KD + Cutting)
        $kd = !empty($data['has_kd']) ? (float) ($data['kd_cost_per_ton'] ?? 0) : 0;
        $cutting = !empty($data['has_cutting']) ? (float) ($data['cutting_cost_per_ton'] ?? 0) : 0;
        $adjustedCost = $totalBase + $kd + $cutting;

        // 5. Benchmark Price
        $marginPct = (float) ($data['target_margin_percentage'] ?? 15) / 100;
        $marketType = $data['market_type'] ?? 'Local';

        if ($marketType === 'Export') {
            $benchmark = ($adjustedCost > 0 && (1 - $marginPct) > 0) ? ($adjustedCost / (1 - $marginPct)) : $adjustedCost;
        } else {
            $benchmark = $adjustedCost * (1 + $marginPct);
        }

        // Kemas kini state form secara serentak
        $data['log_cost_per_ton'] = number_format($avgLogCost, 2, '.', '');
        $data['total_base_cost_per_ton'] = number_format($totalBase, 2, '.', '');
        $data['adjusted_cost_per_ton'] = number_format($adjustedCost, 2, '.', '');
        $data['benchmark_price_per_ton'] = number_format($benchmark, 2, '.', '');

        $livewire->form->fill($data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('total_base_cost_per_ton')->label('Base Cost (RM)')->money('MYR')->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStCostings::route('/'),
            'create' => Pages\CreateStCosting::route('/create'),
            'edit' => Pages\EditStCosting::route('/{record}/edit'),
        ];
    }
}