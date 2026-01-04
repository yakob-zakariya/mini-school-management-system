<?php

namespace App\Filament\Resources\MyGrades\Pages;

use App\Filament\Resources\MyGrades\MyGradesResource;
use Filament\Resources\Pages\ListRecords;

class ListMyGrades extends ListRecords
{
    protected static string $resource = MyGradesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - teachers can't create grades
        ];
    }
}

