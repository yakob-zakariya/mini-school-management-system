<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


                Select::make('student_id')
                    ->relationship('student', 'name', fn($query) => $query->where('type', 'student'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Student'),

                Select::make('grade_id')
                    ->relationship('grade', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->label('Grade'),
                Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Subject'),

                TextInput::make('academic_year')
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->label('Academic Year'),
            ]);
    }
}
