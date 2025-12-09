<?php

namespace App\Filament\Resources\GreenBeanResource\Pages;

use App\Filament\Resources\GreenBeanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGreenBean extends EditRecord
{
    protected static string $resource = GreenBeanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
