<?php

namespace App\Filament\Admin\Resources\PromocodeResource\Pages;

use App\Filament\Admin\Resources\PromocodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromocodes extends ListRecords
{
    protected static string $resource = PromocodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
