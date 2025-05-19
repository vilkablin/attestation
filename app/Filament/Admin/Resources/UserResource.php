<?php

namespace App\Filament\Admin\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\UserResource\Pages;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Пользователь';
    protected static ?string $pluralModelLabel = 'Пользователи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('phone')
                    ->label('Номер телефона')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('role_id')
                    ->label('Роль')
                    ->relationship('role', 'name')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Аватар')
                    ->image()
                    ->directory('users'),

                Forms\Components\TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Аватар')

                    ->circular()
                    ->defaultImageUrl(url('/images/default.webp')),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label('Роль')
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointments_count')
                    ->label('Кол-во записей')
                    ->counts('appointments'),

                Tables\Columns\TextColumn::make('promocodes_count')
                    ->label('Промокоды')
                    ->counts('promocodes'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'name'),

                Tables\Filters\TernaryFilter::make('has_appointments')
                    ->label('Has Appointments')
                    ->queries(
                        true: fn(Builder $query) => $query->has('appointments'),
                        false: fn(Builder $query) => $query->doesntHave('appointments'),
                    ),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
