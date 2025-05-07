<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AppointmentResource\Pages;
use App\Filament\Admin\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Promocode;

// ..

class AppointmentResource extends Resource
{

    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Операции';
    protected static ?string $modelLabel = 'Запись';
    protected static ?string $pluralModelLabel = 'Записи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Клиент')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('service_id')
                            ->label('Услуга')
                            ->relationship('service', 'name')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('location_id')
                            ->label('Локация')
                            ->relationship('location', 'address')
                            ->required(),

                        Forms\Components\Select::make('employees')
                            ->label('Сотрудники')
                            ->relationship('employees', 'name')
                            ->multiple()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Время и стоимость')
                    ->schema([
                        Forms\Components\DateTimePicker::make('date')
                            ->label('Дата и время начала')
                            ->required()
                            ->minutesStep(15),

                        Forms\Components\TextInput::make('price')
                            ->label('Итоговая цена')
                            ->numeric()
                            ->prefix('₽'),

                        Forms\Components\Select::make('promocode_id')
                            ->label('Промокод')
                            ->relationship('promocode', 'code')
                            ->searchable(),
                    ])->columns(3),

                Forms\Components\Section::make('Дополнительно')
                    ->schema([
                        Forms\Components\Select::make('status_id')
                            ->label('Статус')
                            ->relationship('status', 'name')
                            ->required(), // статус обязательно указывается

                        Forms\Components\Textarea::make('comment')
                            ->label('Комментарий')
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Клиент')
                    ->searchable(),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Услуга'),

                Tables\Columns\TextColumn::make('date')
                    ->label('Дата и время')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Подтверждена' => 'success',
                        'Выполнена' => 'primary',
                        'Отменена' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('RUB'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->label('Статус'),

                Tables\Filters\Filter::make('today')
                    ->label('На сегодня')
                    ->query(fn(Builder $query): Builder => $query->whereDate('date', today())),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('notify')
                    ->icon('heroicon-o-bell')
                    ->label('Уведомить')
                    ->action(fn($record) => $record->notifyUser()),
                Tables\Actions\Action::make('changeStatus')
                    ->label('Подтвердить')
                    ->icon('heroicon-o-check')
                    ->visible(fn($record) => $record->status_id === 1) // только если статус "Ожидает"
                    ->action(function ($record) {
                        $record->update([
                            'status_id' => 2, // Например, 2 — "Подтверждена"
                        ]);
                        // При желании можно уведомить пользователя, отправить событие и т.д.
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->successNotificationTitle('Статус обновлен'),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
