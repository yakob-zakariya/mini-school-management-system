<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Grade Name')
                    ->placeholder('e.g., Grade 1, Grade 10'),
                TextInput::make('section')
                    ->maxLength(255)
                    ->label('Section')
                    ->placeholder('e.g., A, B, C'),

                TextInput::make('academic_year')
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->label('Academic Year')
                    ->placeholder('e.g., 2024'),

                Select::make('teacher_id')
                    ->relationship('teacher', 'name', fn($query) => $query->where('type', 'teacher'))
                    ->searchable()
                    ->preload()
                    ->label('Class Teacher')
                    ->placeholder('Select a class teacher'),

                // Repeater::make('subjectsAssignments')
                //     ->columnSpanFull()
                //     ->relationship('subjects')
                //     ->schema([
                //         Select::make('id')
                //             ->label('Subject')
                //             ->options(\App\Models\Subject::pluck('name', 'id'))
                //             ->required()
                //             ->searchable(),

                //         Select::make('teacher_id')
                //             ->label('Teacher')
                //             ->options(\App\Models\User::where('type', 'teacher')->pluck('name', 'id'))
                //             ->searchable()
                //             ->placeholder('Select teacher for this subject'),

                //     ])
                //     ->columns(2)
                //     ->label('Subjects & Teachers')
                //     ->helperText('Assign subjects and their teachers for this grade'),

            ]);
    }
}
