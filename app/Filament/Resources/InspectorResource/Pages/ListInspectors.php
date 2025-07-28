<?php

namespace App\Filament\Resources\InspectorResource\Pages;

use App\Filament\Resources\InspectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInspectors extends ListRecords
{
    protected static string $resource = InspectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
