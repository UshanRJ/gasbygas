<?php

namespace App\Filament\Resources\OutletsResource\Pages;

use App\Filament\Resources\OutletsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutlets extends ListRecords
{
    protected static string $resource = OutletsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
