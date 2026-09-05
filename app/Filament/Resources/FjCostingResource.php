<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FjCostingResource\Pages;
use App\Models\FjCosting;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class FjCostingResource extends Resource
{
    protected static ?string $model = FjCosting::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-center-left';
    protected static ?string $navigationGroup = 'Standard Costing';
    protected static ?string $navigationLabel = 'Finger Joint (FJ)';
    protected static ?string $modelLabel = 'FJ Costing';
    protected static ?string $pluralModelLabel = 'Finger Joint (FJ) Costings';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'xl' => 12])
                    ->schema([
                        // =========================================================================
                        // LAJUR KIRI: INPUT PENGGUNA (8 / 12 KOLUM)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['xl' => 8])->schema([
                            
                            // 1. SPESIFIKASI PRODUK FJ & KOS KD (WAJIB)
                            Section::make('1. Spesifikasi Produk Finger Joint (Short Length)')
                                ->description('Penetapan punca bahan short length, saiz profil dan kos Kiln Dried (KD).')
                                ->icon('heroicon-o-tag')
                                ->schema([
                                    Grid::make(3)->schema([
                                        Select::make('category')
                                            ->label('Punca Kayu (Short Length)')
                                            ->options([
                                                'Off-Cut Recovery (Kilang Sendiri)' => 'Off-Cut Recovery (Kilang Sendiri)',
                                                'Short Length Belian Luar'           => 'Short Length Belian Luar',
                                            ])
                                            ->default('Off-Cut Recovery (Kilang Sendiri)')
                                            ->required(),

                                        Select::make('profile_size')
                                            ->label('Saiz Profil Tetap FJ')
                                            ->options([
                                                '1-1/4" x 5-1/2" (28mm x 133mm)' => '1-1/4" x 5-1/2" (28mm x 133mm)',
                                                '1-1/4" x 6" (28mm x 145mm)'     => '1-1/4" x 6" (28mm x 145mm)',
                                                'Custom Finger Jointed Profile'  => 'Custom Finger Jointed Profile',
                                            ])
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        // KD Cost / Tan - WAJIB ADA (Bukan pilihan)
                                        TextInput::make('kd_cost_per_ton')
                                            ->label('Kos KD / Tan (Wajib)')
                                            ->helperText('Caj Kiln Drying bagi short length')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->default(120.00)
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),
                                ]),

                            // 2. REPEATER ITEM BAHAN MENTAH (TANPA MERANTI)
                            Section::make('2. Bahan Mentah & Batch Intake (Short Length)')
                                ->description('Kemasukan batch kayu. Spesies Meranti dikecualikan secara automatik.')
                                ->icon('heroicon-o-archive-box')
                                ->schema([
                                    Repeater::make('items')
                                        ->relationship('items')
                                        ->schema([
                                            TextInput::make('batch_no')
                                                ->label('Batch No')
                                                ->placeholder('FJ-B01')
                                                ->required()
                                                ->columnSpan(['default' => 12, 'md' => 2]),

                                            Select::make('species_id')
                                                ->label('Spesies Kayu (Bukan Meranti)')
                                                ->relationship('species', 'name', modifyQueryUsing: function (Builder $query) {
                                                    // Menapis keluar spesies Meranti
                                                    $query->where('name', 'NOT LIKE', '%meranti%');
                                                })
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
                                                    $cost = (float) $get('raw_cost_per_ton');
                                                    $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                                    self::recalculateTotals($livewire);
                                                })
                                                ->required()
                                                ->columnSpan(['default' => 6, 'md' => 3]),

                                            TextInput::make('raw_cost_per_ton')
                                                ->label('Kos Bahan (RM/Tan)')
                                                ->numeric()
                                                ->prefix('RM')
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Set $set, Get $get, $livewire) {
                                                    $vol = (float) $get('volume_ton');
                                                    $cost = (float) $get('raw_cost_per_ton');
                                                    $set('subtotal_cost', number_format($vol * $cost, 2, '.', ''));
                                                    self::recalculateTotals($livewire);
                                                })
                                                ->required()
                                                ->columnSpan(['default' => 6, 'md' => 3]),

                                            Hidden::make('subtotal_cost')->default(0),
                                        ])
                                        ->columns(12)
                                        ->defaultItems(1)
                                        ->addActionLabel('+ Tambah Batch Short Length')
                                        ->live()
                                        ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                ]),

                            // 3. POLISI JUALAN & KELULUSAN HARGA
                            Section::make('3. Penetapan Margin & Harga Jualan')
                                ->icon('heroicon-o-banknotes')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('market_type')
                                            ->label('Pasaran Sasaran')
                                            ->options([
                                                'Local'  => 'Pasaran Tempatan (Markup %)',
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
                                            ->default('20')
                                            ->live()
                                            ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire)),
                                    ]),

                                    Grid::make(2)->schema([
                                        TextInput::make('actual_selling_price_per_ton')
                                            ->label('Harga Jualan Sebenar / Tan (RM)')
                                            ->numeric()
                                            ->prefix('RM')
                                            ->placeholder('Isi jika berbeza dgn benchmark')
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
                        ]),

                        // =========================================================================
                        // LAJUR KANAN: RINGKASAN KOS & BENCHMARK DENGAN COA FJ (4 / 12 KOLUM)
                        // =========================================================================
                        Grid::make(1)->columnSpan(['xl' => 4])->schema([
                            Section::make('Ringkasan Kos & Benchmark')
                                ->description('Kiraan masa nyata berpusat.')
                                ->icon('heroicon-o-calculator')
                                ->schema([
                                    TextInput::make('raw_material_cost_per_ton')
                                        ->label('Purata Kos Bahan Mentah / Tan')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly()
                                        ->extraInputAttributes(['class' => 'font-semibold text-slate-200']),

                                    TextInput::make('manufacturing_cost_per_ton')
                                        ->label('Kos Pembuatan (COA FJ - 75 Ton)')
                                        ->helperText('Berdasarkan helaian 81 COA Finger Joint.')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default('687.86')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($livewire) => self::recalculateTotals($livewire))
                                        ->suffixAction(
                                            FormAction::make('viewCoaDetails')
                                                ->label('Drill-Down')
                                                ->icon('heroicon-m-table-cells')
                                                ->modalHeading('Pesama Timber Corporation - Standard Costing Sheet (FJ)')
                                                ->modalWidth('7xl')
                                                ->modalSubmitAction(false)
                                                ->modalContent(fn () => view('filament.modals.coa-breakdown-table', [
                                                    'coas' => CoaItem::where('product_type', 'Moulding_FJ')->orderBy('id', 'asc')->get(),
                                                ]))
                                        ),

                                    TextInput::make('total_cost_per_ton')
                                        ->label('Total Standard Cost / Tan')
                                        ->helperText('Bahan Mentah + Kos KD + Kos Pembuatan (FJ)')
                                        ->numeric()
                                        ->prefix('RM')
                                        ->default(0.00)
                                        ->readOnly(),

                                    TextInput::make('benchmark_price_per_ton')
                                        ->label('Harga Benchmark Sasaran')
                                        ->helperText('Harga jualan asas mengikut margin sasaran')
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

        // 1. Purata wajaran kos bahan mentah
        $items = $data['items'] ?? [];
        $totalCost = 0;
        $totalVolume = 0;

        foreach ($items as $item) {
            $vol = (float) ($item['volume_ton'] ?? 0);
            $cost = (float) ($item['raw_cost_per_ton'] ?? 0);
            $totalCost += ($vol * $cost);
            $totalVolume += $vol;
        }

        $avgRawCost = $totalVolume > 0 ? ($totalCost / $totalVolume) : 0;

        // 2. Kos KD Wajib
        $kdCost = (float) ($data['kd_cost_per_ton'] ?? 0);

        // 3. Kos Pembuatan FJ (COA)
        $mfgCost = isset($data['manufacturing_cost_per_ton']) && is_numeric($data['manufacturing_cost_per_ton'])
            ? (float) $data['manufacturing_cost_per_ton']
            : 687.86;

        // 4. Total Standard Cost = Raw + KD + Manufacturing
        $totalStandard = $avgRawCost + $kdCost + $mfgCost;

        // 5. Kiraan Benchmark Margin
        $marginPct = (float) ($data['target_margin_percentage'] ?? 20) / 100;
        $marketType = $data['market_type'] ?? 'Local';

        if ($marketType === 'Export') {
            $benchmark = ($totalStandard > 0 && (1 - $marginPct) > 0) ? ($totalStandard / (1 - $marginPct)) : $totalStandard;
        } else {
            $benchmark = $totalStandard * (1 + $marginPct);
        }

        // Tulis semula ke borang
        $data['raw_material_cost_per_ton'] = number_format($avgRawCost, 2, '.', '');
        $data['manufacturing_cost_per_ton'] = number_format($mfgCost, 2, '.', '');
        $data['total_cost_per_ton'] = number_format($totalStandard, 2, '.', '');
        $data['benchmark_price_per_ton'] = number_format($benchmark, 2, '.', '');

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
                TextColumn::make('profile_size')->label('Saiz Profil FJ')->searchable()->badge(),
                TextColumn::make('kd_cost_per_ton')->label('Kos KD (RM)')->money('MYR')->sortable(),
                TextColumn::make('total_cost_per_ton')->label('Standard Cost (RM)')->money('MYR')->sortable(),
                TextColumn::make('benchmark_price_per_ton')->label('Benchmark (RM)')->money('MYR')->sortable(),
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
            'index' => Pages\ListFjCostings::route('/'),
            'create' => Pages\CreateFjCosting::route('/create'),
            'edit' => Pages\EditFjCosting::route('/{record}/edit'),
        ];
    }
}