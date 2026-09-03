<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaMouldingFjResource\Pages;
use App\Models\CoaItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;

class CoaMouldingFjResource extends Resource
{
    protected static ?string $model = CoaItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'COA: Moulding & FJ (Excel)';
    protected static ?string $modelLabel = 'Item Akaun';
    protected static ?string $pluralModelLabel = 'Helaian Standard Costing (Moulding & FJ)';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('product_type', 'Moulding_FJ')
            ->orderBy('id', 'asc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('coa_code')
                    ->label('Acc. No.')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('name')
                    ->label('Description')
                    ->required()
                    ->columnSpan(2),

                Forms\Components\Select::make('cost_type')
                    ->label('Category')
                    ->options([
                        'Variable overhead' => 'Variable overhead',
                        'Fixed overhead'    => 'Fixed overhead',
                        'Raw material'      => 'Raw material',
                        'Stock'             => 'Stock',
                    ])
                    ->nullable(),

                Forms\Components\TextInput::make('standard_rate_per_ton')
                    ->label('Cost/ton (RM)')
                    ->numeric()
                    ->prefix('RM')
                    ->default(0.00),

                Forms\Components\TextInput::make('total_cost')
                    ->label('Total cost (RM)')
                    ->numeric()
                    ->prefix('RM')
                    ->default(0.00),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([25, 50, 100, 'all'])
            ->defaultPaginationPageOption(100)
            ->columns([
                // 1. Acc. No.
                TextColumn::make('coa_code')
                    ->label('Acc. No.')
                    ->sortable()
                    ->searchable()
                    ->extraAttributes(fn ($record) => [
                        'class' => empty($record->cost_type) 
                            ? 'font-extrabold text-amber-300 tracking-wider' 
                            : 'font-mono text-slate-300 font-semibold',
                    ]),

                // 2. Description
                TextColumn::make('name')
                    ->label('Description')
                    ->searchable()
                    ->wrap()
                    ->extraAttributes(fn ($record) => [
                        'class' => empty($record->cost_type) 
                            ? 'font-extrabold text-white uppercase text-sm' 
                            : 'text-slate-200',
                    ]),

                // 3. Category
                TextColumn::make('cost_type')
                    ->label('Category')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Variable overhead' => 'warning',
                        'Fixed overhead'    => 'info',
                        'Raw material'      => 'success',
                        'Stock'             => 'danger',
                        default             => 'gray',
                    })
                    ->placeholder('--'),

                // 4. Cost/ton (RM)
                TextInputColumn::make('standard_rate_per_ton')
                    ->label('Cost/ton (RM)')
                    ->rules(['numeric', 'min:0'])
                    ->alignEnd(),

                // 5. Total cost (RM)
                TextInputColumn::make('total_cost')
                    ->label('Total cost (RM)')
                    ->rules(['numeric', 'min:0'])
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cost_type')
                    ->label('Category')
                    ->options([
                        'Variable overhead' => 'Variable overhead',
                        'Fixed overhead'    => 'Fixed overhead',
                        'Raw material'      => 'Raw material',
                        'Stock'             => 'Stock',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoaMouldingFjs::route('/'),
            'create' => Pages\CreateCoaMouldingFj::route('/create'),
            'edit' => Pages\EditCoaMouldingFj::route('/{record}/edit'),
        ];
    }
}