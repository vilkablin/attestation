<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocationResource\Pages;
use App\Models\LocationPoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = LocationPoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Автомойки';

    protected static ?string $label = 'Точка';
    protected static ?string $pluralLabel = 'Точки автомойки';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('address')
                ->label('Адрес')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('places_count')
                ->label('Количество мест')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('phone')
                ->label('Телефон')
                ->tel()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address')->label('Адрес')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('places_count')->label('Мест'),
                Tables\Columns\TextColumn::make('phone')->label('Телефон'),
                Tables\Columns\TextColumn::make('created_at')->label('Создано')->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // для soft deletes
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
