<?php

namespace App\Filament\Resources\GreenCoffeeResource\Pages;

use App\Filament\Resources\GreenCoffeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGreenCoffees extends ListRecords
{
    protected static string $resource = GreenCoffeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
