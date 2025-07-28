<?php

namespace App\Filament\Resources\TeaLotResource\Pages;

use App\Filament\Resources\TeaLotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeaLots extends ListRecords
{
    protected static string $resource = TeaLotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
