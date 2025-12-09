<?php

namespace App\Filament\Resources\GreenBeanResource\Pages;

use App\Filament\Resources\GreenBeanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGreenBeans extends ListRecords
{
    protected static string $resource = GreenBeanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
