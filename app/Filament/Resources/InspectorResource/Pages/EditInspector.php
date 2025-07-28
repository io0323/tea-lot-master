<?php

namespace App\Filament\Resources\InspectorResource\Pages;

use App\Filament\Resources\InspectorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInspector extends EditRecord
{
    protected static string $resource = InspectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
