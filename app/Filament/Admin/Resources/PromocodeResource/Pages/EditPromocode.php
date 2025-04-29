<?php

namespace App\Filament\Admin\Resources\PromocodeResource\Pages;

use App\Filament\Admin\Resources\PromocodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromocode extends EditRecord
{
    protected static string $resource = PromocodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
