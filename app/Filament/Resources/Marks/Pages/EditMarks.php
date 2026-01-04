<?php

namespace App\Filament\Resources\Marks\Pages;

use App\Filament\Resources\Marks\MarksResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarks extends EditRecord
{
    protected static string $resource = MarksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
