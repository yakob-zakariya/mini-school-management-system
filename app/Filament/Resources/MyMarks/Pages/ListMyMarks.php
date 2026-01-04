<?php

namespace App\Filament\Resources\MyMarks\Pages;

use App\Filament\Resources\MyMarks\MyMarksResource;
use Filament\Resources\Pages\ListRecords;

class ListMyMarks extends ListRecords
{
    protected static string $resource = MyMarksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - students can't create marks
        ];
    }
}
