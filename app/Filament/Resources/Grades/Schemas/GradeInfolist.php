<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GradeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('section')
                    ->placeholder('-'),
                TextEntry::make('academic_year')
                    ->numeric(),
                TextEntry::make('teacher.name')
                    ->label('Teacher')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
