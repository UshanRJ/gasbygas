<?php

namespace App\Filament\Resources\OutletsResource\Pages;

use App\Filament\Resources\OutletsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutlets extends EditRecord
{
    protected static string $resource = OutletsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
