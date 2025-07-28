<?php

namespace App\Filament\Resources\TeaLotResource\Pages;

use App\Filament\Resources\TeaLotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeaLot extends EditRecord
{
    protected static string $resource = TeaLotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
