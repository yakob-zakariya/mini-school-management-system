<?php

namespace App\Filament\Resources\MyMarks\Pages;

use App\Filament\Resources\MyMarks\MyMarksResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMyMarks extends EditRecord
{
    protected static string $resource = MyMarksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
