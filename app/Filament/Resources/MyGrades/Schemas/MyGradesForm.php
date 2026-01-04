<?php

namespace App\Filament\Resources\MyGrades\Schemas;

use Filament\Schemas\Schema;

class MyGradesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // No form needed - teachers can't create/edit grades
            ]);
    }
}

