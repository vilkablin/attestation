<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Управление персоналом';
    protected static ?string $modelLabel = 'Сотрудник';
    protected static ?string $pluralModelLabel = 'Сотрудники';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ФИО')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->tel()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('photo')
                            ->label('Фото')
                            ->image()
                            ->directory('employees'),
                    ])->columns(2),

                Forms\Components\Section::make('Расписание и специализация')
                    ->schema([
                        Forms\Components\Select::make('location_id')
                            ->label('Основная локация')
                            ->relationship('location', 'address')
                            ->required(),

                        Forms\Components\Select::make('services')
                            ->label('Оказываемые услуги')
                            ->relationship('services', 'name')
                            ->multiple()
                            ->preload(),

                        Forms\Components\Section::make('Рабочее расписание')
                            ->schema([
                                Forms\Components\TextInput::make('work_schedule.work_days')
                                    ->label('Рабочие дни подряд')
                                    ->numeric()
                                    ->default(2)
                                    ->required(),

                                Forms\Components\TextInput::make('work_schedule.rest_days')
                                    ->label('Выходные дни подряд')
                                    ->numeric()
                                    ->default(2)
                                    ->required(),

                                Forms\Components\TimePicker::make('work_schedule.work_hours.start')
                                    ->label('Начало работы')
                                    ->required(),

                                Forms\Components\TimePicker::make('work_schedule.work_hours.end')
                                    ->label('Окончание работы')
                                    ->required(),

                                Forms\Components\DatePicker::make('work_schedule.start_date')
                                    ->label('Дата начала цикла смен')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Фото')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('ФИО')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.address')
                    ->label('Локация')
                    ->sortable(),

                Tables\Columns\TextColumn::make('services.name')
                    ->label('Услуги')
                    ->listWithLineBreaks()
                    ->limitList(2),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->relationship('location', 'address')
                    ->label('Фильтр по локации'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
