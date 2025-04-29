<?php

namespace App\Filament\Admin\Resources\ServiceResource\RelationManagers;

use App\Models\EmployeeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeesRelationManager extends RelationManager
{
  protected static string $relationship = 'employees';

  protected static ?string $model = EmployeeService::class;

  protected static ?string $title = 'Сотрудники, оказывающие услугу';

  protected static ?string $modelLabel = 'сотрудник';

  protected static ?string $pluralModelLabel = 'сотрудники';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('employee_id')
          ->relationship(
            name: 'employee',
            titleAttribute: 'name',
            modifyQueryUsing: fn(Builder $query) => $query->whereHas('services', fn($q) => $q->where('service_id', $this->getOwnerRecord()->id))
          )
          ->label('Сотрудник')
          ->required()
          ->searchable()
          ->preload(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('employee.name')
          ->label('Имя сотрудника')
          ->searchable(),

        Tables\Columns\TextColumn::make('employee.phone')
          ->label('Телефон'),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Дата назначения')
          ->dateTime()
          ->sortable(),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
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
}
