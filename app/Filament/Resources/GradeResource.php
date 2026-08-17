<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Models\Grade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Grades';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Gred Kayu')
                ->description('Daftar atau kemas kini nama dan kod pengenalan gred.')
                ->icon('heroicon-o-tag')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Grade Name')
                        ->placeholder('Cth: LOCAL GRADE 1, S1, S2, BLUE STAIN')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('code')
                        ->label('Code (Pilihan)')
                        ->placeholder('Cth: LG1, S1, BS')
                        ->maxLength(50),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Grade Name')->searchable()->sortable()->weight('bold'),
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
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}