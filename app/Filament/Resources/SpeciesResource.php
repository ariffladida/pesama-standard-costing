<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeciesResource\Pages;
use App\Models\Species;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpeciesResource extends Resource
{
    protected static ?string $model = Species::class;
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Species';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Spesies Kayu')
                ->description('Daftar atau kemas kini nama dan kod spesies balak / kayu.')
                ->icon('heroicon-o-cube')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Species Name')
                        ->placeholder('Cth: MERANTI, KELAT, KULIM, NEMESU')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('code')
                        ->label('Code (Pilihan)')
                        ->placeholder('Cth: MRT, KLT, KLM')
                        ->maxLength(50),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Species Name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('code')->label('Code')->searchable()->badge()->color('gray')->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')->label('Dicipta')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
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
            'index' => Pages\ListSpecies::route('/'),
            'create' => Pages\CreateSpecies::route('/create'),
            'edit' => Pages\EditSpecies::route('/{record}/edit'),
        ];
    }
}