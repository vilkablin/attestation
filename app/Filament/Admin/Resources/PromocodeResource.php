<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PromocodeResource\Pages;
use App\Models\Promocode;
use App\Models\PromocodeStatus;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromocodeResource extends Resource
{
    protected static ?string $model = Promocode::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Маркетинг';
    protected static ?string $modelLabel = 'Промокод';
    protected static ?string $pluralModelLabel = 'Промокоды';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основные параметры')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Код промокода')
                            ->required()
                            ->maxLength(32)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('discount')
                            ->label('Размер скидки')
                            ->numeric()
                            ->required()
                            ->suffix('%'),

                        Forms\Components\DateTimePicker::make('valid_from')
                            ->label('Действует с')
                            ->required(),

                        Forms\Components\DateTimePicker::make('valid_to')
                            ->label('Действует до')
                            ->required()
                            ->after('valid_from'),

                        Forms\Components\TextInput::make('usage_limit')
                            ->label('Лимит использований')
                            ->numeric()
                            ->minValue(1)
                            ->nullable(),

                        Forms\Components\Select::make('status_id')
                            ->label('Статус')
                            ->options(PromocodeStatus::pluck('name', 'id'))
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Привязан к пользователю')
                            ->searchable()
                            ->options(User::query()->pluck('name', 'id'))
                            ->nullable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->columnSpanFull(),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Код')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Скидка')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('valid_from')
                    ->label('Начало')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('valid_to')
                    ->label('Окончание')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('Использовано')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Лимит')
                    ->formatStateUsing(fn($state) => $state ?? '∞'),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Статус')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        'Active' => 'success',
                        'Expired' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                    ->label('Статус')
                    ->options(PromocodeStatus::pluck('name', 'id')),

                Tables\Filters\Filter::make('active')
                    ->label('Только активные')
                    ->query(fn($query) => $query->where('status_id', 1)
                        ->where('valid_from', '<=', now())
                        ->where('valid_to', '>=', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('apply')
                    ->label('Применить')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn(Promocode $record) => $record->apply())
                    ->hidden(fn(Promocode $record) => !$record->isActive()),
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
            'index' => Pages\ListPromocodes::route('/'),
            'create' => Pages\CreatePromocode::route('/create'),
            'edit' => Pages\EditPromocode::route('/{record}/edit'),
        ];
    }
}
