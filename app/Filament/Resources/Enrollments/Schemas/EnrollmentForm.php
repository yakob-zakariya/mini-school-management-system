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
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()  // ← Makes it reactive
                    ->label('Grade'),

                Select::make('subject_id')
                    ->label('Subject')
                    ->required()
                    ->searchable()
                    ->options(function (callable $get) {
                        $gradeId = $get('grade_id');
                        if (!$gradeId) {
                            return \App\Models\Subject::pluck('name', 'id');
                        }

                        // Only show subjects assigned to the selected grade
                        return \App\Models\Subject::whereHas('grades', function ($query) use ($gradeId) {
                            $query->where('grades.id', $gradeId);
                        })->pluck('name', 'id');
                    })
                    ->helperText('Only subjects assigned to the selected grade are shown'),

                TextInput::make('academic_year')
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->label('Academic Year'),
            ]);
    }
}
