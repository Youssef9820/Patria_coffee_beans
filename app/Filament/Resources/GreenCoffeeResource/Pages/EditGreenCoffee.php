<?php

namespace App\Filament\Resources\GreenCoffeeResource\Pages;

use App\Filament\Resources\GreenCoffeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGreenCoffee extends EditRecord
{
    protected static string $resource = GreenCoffeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
