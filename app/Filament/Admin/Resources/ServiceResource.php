<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages;

use App\Filament\Admin\Resources\ServiceResource\RelationManagers\EmployeesRelationManager;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Управление услугами';
    protected static ?string $modelLabel = 'Услуга';
    protected static ?string $pluralModelLabel = 'Услуги';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название услуги')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание услуги')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('base_price')
                            ->label('Базовая цена')
                            ->required()
                            ->numeric()
                            ->prefix('₽'),

                        Forms\Components\TextInput::make('base_time')
                            ->label('Базовое время (минуты)')
                            ->required()
                            ->numeric()
                            ->suffix('мин.'),

                        Forms\Components\FileUpload::make('image')
                            ->label('Изображение услуги')
                            ->image()
                            ->directory('services')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Привязка к локациям')
                    ->schema([
                        Forms\Components\Repeater::make('locationServices')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('location_id')
                                    ->label('Локация')
                                    ->relationship('location', 'address')
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->label('Цена на этой локации')
                                    ->numeric()
                                    ->prefix('₽'),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(array $state): ?string => $state['location_id'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Изображение')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Цена')
                    ->money('RUB')
                    ->sortable(),

                Tables\Columns\TextColumn::make('base_time')
                    ->label('Время')
                    ->formatStateUsing(fn($state) => "{$state} мин.")
                    ->sortable(),

                Tables\Columns\TextColumn::make('locationServices.location.address')
                    ->label('Доступна в локациях')
                    ->listWithLineBreaks()
                    ->limitList(2),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locations')
                    ->relationship('locationServices.location', 'address')
                    ->label('Фильтр по локациям'),
            ])
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
    public static function getRelations(): array
    {
        return [
            EmployeesRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
