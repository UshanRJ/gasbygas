<?php

namespace App\Filament\Resources\GasCategoryResource\Pages;

use App\Filament\Resources\GasCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGasCategory extends EditRecord
{
    protected static string $resource = GasCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
