<?php

namespace App\Filament\Resources\Marks\Pages;

use App\Filament\Resources\Marks\MarksResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarks extends ListRecords
{
    protected static string $resource = MarksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
