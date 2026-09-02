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
                Grid::make(['default' => 1, 'xl' => 12])
                    ->schema([
                        // =========================================================================
                        // BAHAGIAN UTAMA: ALIRAN KERJA INPUT PEGAWAI (8 / 12 KOLUM)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['xl' => 8])->schema([
                            
                            // 1. INPUT BALAK & SPESIES
                            Section::make('1. Campuran Balak Mentah (Log Intake)')
                                ->description('Kemasukan batch dan kos belian balak untuk purata wajaran kos.')
                                ->icon('heroicon-o-archive-box')
                                ->schema([
                                    Repeater::make('items')
                                        ->relationship('items')
                                        ->schema([
                                            TextInput::make('batch_no')
                                                ->label('Batch No')
                                                ->placeholder('B01')
                                                ->required()
                                                ->columnSpan(['default' => 12, 'md' => 2]),

                                            Select::make('species_id')
                                                ->label('Spesies Kayu')
                                                ->relationship('species', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->columnSpan(['default' => 12, 'md' => 4]),

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
                                                ->columnSpan(['default' => 6, 'md' => 3]),

                                            TextInput::make('log_cost_per_ton')
                                                ->label('Kos Kayu (RM/Tan)')
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
                                                ->columnSpan(['default' => 6, 'md' => 3]),

                                            Hidden::make('subtotal_cost')->default(0),
                                        ])
                                        ->columns(12)
                                        ->defaultItems(1)
                                        ->addActionLabel('+ Tambah Batch / Spesies Balak')
                                        ->live()
                                        ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                ]),

                            // 2. RAWATAN & PROSES TAMBAHAN
                            Section::make('2. Rawatan & Proses Tambahan (Value-Add)')
                                ->icon('heroicon-o-wrench-screwdriver')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Toggle::make('has_kd')
                                            ->label('Kiln Drying (KD)')
                                            ->helperText('Aktifkan untuk kos pengeringan relau')
                                            ->inline(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $livewire) {
                                                if (!$state) $set('kd_cost_per_ton', 0);
                                                self::recalculateTotals($livewire);
                                            }),

                                        TextInput::make('kd_cost_per_ton')
                                            ->label('Kadar Kos KD / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->default(0)
                                            ->visible(fn (Get $get) => (bool) $get('has_kd'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),

                                    Grid::make(2)->schema([
                                        Toggle::make('has_cutting')
                                            ->label('Cutting / Pemotongan Khas')
                                            ->helperText('Aktifkan untuk upah belah saiz khas')
                                            ->inline(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, $livewire) {
                                                if (!$state) $set('cutting_cost_per_ton', 0);
                                                self::recalculateTotals($livewire);
                                            }),

                                        TextInput::make('cutting_cost_per_ton')
                                            ->label('Kadar Kos Potong / Tan')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->default(0)
                                            ->visible(fn (Get $get) => (bool) $get('has_cutting'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),
                                ]),

                            // 3. PENETAPAN JUALAN & STATUS KELULUSAN
                            Section::make('3. Polisi Jualan & Kelulusan Harga')
                                ->icon('heroicon-o-banknotes')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('market_type')
                                            ->label('Pasaran Sasaran')
                                            ->options([
                                                'Local' => 'Pasaran Tempatan (Markup %)',
                                                'Export' => 'Eksport (Reverse Margin %)',
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
                                            ->label('Harga Jualan Sebenar / Tan (RM)')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->placeholder('Isi jika rundingan berbeza dgn benchmark')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $benchmark = (float) $get('benchmark_price_per_ton');
                                                if ($state && (float) $state < $benchmark) {
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
                                                'class' => $get('approval_status') === 'Pending Approval' 
                                                    ? 'text-amber-400 font-bold' 
                                                    : 'text-emerald-400 font-bold',
                                            ]),
                                    ]),

                                    Textarea::make('down_value_reason')
                                        ->label('Justifikasi Penurunan Harga (Wajib jika bawah benchmark)')
                                        ->visible(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                                        ->required(fn (Get $get) => $get('approval_status') === 'Pending Approval')
                                        ->columnSpanFull(),
                                ]),

                            // 4. PECAHAN GRED KAYU
                            Section::make('4. Pecahan Kos Akhir Mengikut Gred (Grade Breakdown)')
                                ->icon('heroicon-o-squares-2x2')
                                ->schema([
                                    Repeater::make('gradeBreakdowns')
                                        ->relationship()
                                        ->schema([
                                            Select::make('grade_id')
                                                ->label('Gred Kayu')
                                                ->relationship('grade', 'name')
                                                ->required()
                                                ->columnSpan(6),

                                            TextInput::make('cost_per_ton')
                                                ->label('Kos Akhir Gred (RM/Tan)')
                                                ->numeric()
                                                ->prefix('RM')
                                                ->required()
                                                ->columnSpan(6),
                                        ])
                                        ->columns(12)
                                        ->addActionLabel('+ Tambah Pecahan Gred'),
                                ]),
                        ]),

                        // =========================================================================
                        // BAHAGIAN RINGKASAN: KAD PENGIRAAN DINAMIK (4 / 12 KOLUM)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['xl' => 4])->schema([
                            Section::make('Ringkasan Kos & Benchmark')
                                ->description('Kiraan masa nyata berpusat.')
                                ->icon('heroicon-o-calculator')
                                ->schema([
                                    TextInput::make('log_cost_per_ton')
                                        ->label('Purata Kos Balak / Tan')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly()
                                        ->extraInputAttributes(['class' => 'font-semibold text-slate-200']),

                                    TextInput::make('manufacturing_cost_per_ton')
                                        ->label('Kos Pembuatan (129 COA)')
                                        ->helperText('Boleh ditaip manual atau dijana dari 129 COA.')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(function () {
                                            $total = CoaItem::whereNotIn('cost_type', ['Summary', 'Balance'])->sum('standard_rate_per_ton');
                                            // Jika pangkalan data COA masih 0, guna nilai default piawai industri (RM 177.00)
                                            return $total > 0 ? number_format($total, 2, '.', '') : '177.00';
                                        })
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire))
                                        ->suffixAction(
                                            FormAction::make('viewCoaDetails')
                                                ->label('Drill-Down')
                                                ->icon('heroicon-m-table-cells')
                                                ->modalHeading('Perincian 129 Kod Akaun Pembuatan')
                                                ->modalSubmitAction(false)
                                                ->modalContent(fn () => view('filament.modals.coa-breakdown-table', [
                                                    'coas' => CoaItem::all(),
                                                ]))
                                        ),

                                    TextInput::make('total_base_cost_per_ton')
                                        ->label('Total Base Cost / Tan')
                                        ->helperText('Purata Balak + Kos Pembuatan')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('adjusted_cost_per_ton')
                                        ->label('Adjusted Cost / Tan')
                                        ->helperText('Termasuk caj proses KD & Potong')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('benchmark_price_per_ton')
                                        ->label('Harga Benchmark Sasaran')
                                        ->helperText('Garis panduan harga jualan minimum')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly()
                                        ->extraInputAttributes([
                                            'class' => 'font-bold text-emerald-400 text-xl border-emerald-500/50 bg-emerald-950/20'
                                        ]),
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

        // 2. Kos Pembuatan
        $mfgCost = isset($data['manufacturing_cost_per_ton']) && is_numeric($data['manufacturing_cost_per_ton'])
            ? (float) $data['manufacturing_cost_per_ton']
            : 177.00;

        // 3. Total Base Cost
        $totalBase = $avgLogCost + $mfgCost;

        // 4. Adjusted Cost (KD + Cutting)
        $kd = !empty($data['has_kd']) ? (float) ($data['kd_cost_per_ton'] ?? 0) : 0;
        $cutting = !empty($data['has_cutting']) ? (float) ($data['cutting_cost_per_ton'] ?? 0) : 0;
        $adjustedCost = $totalBase + $kd + $cutting;

        // 5. Margin Benchmark
        $marginPct = (float) ($data['target_margin_percentage'] ?? 15) / 100;
        $marketType = $data['market_type'] ?? 'Local';

        if ($marketType === 'Export') {
            $benchmark = ($adjustedCost > 0 && (1 - $marginPct) > 0) ? ($adjustedCost / (1 - $marginPct)) : $adjustedCost;
        } else {
            $benchmark = $adjustedCost * (1 + $marginPct);
        }

        // Tulis semula ke form
        $data['log_cost_per_ton'] = number_format($avgLogCost, 2, '.', '');
        $data['manufacturing_cost_per_ton'] = number_format($mfgCost, 2, '.', '');
        $data['total_base_cost_per_ton'] = number_format($totalBase, 2, '.', '');
        $data['adjusted_cost_per_ton'] = number_format($adjustedCost, 2, '.', '');
        $data['benchmark_price_per_ton'] = number_format($benchmark, 2, '.', '');

        // Semakan kelulusan jika harga jualan sebenar wujud
        if (!empty($data['actual_selling_price_per_ton'])) {
            $actual = (float) $data['actual_selling_price_per_ton'];
            $data['approval_status'] = ($actual < $benchmark) ? 'Pending Approval' : 'Approved';
        }

        $livewire->form->fill($data);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
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