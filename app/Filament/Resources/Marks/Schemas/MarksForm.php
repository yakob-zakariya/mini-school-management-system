<?php

namespace App\Filament\Resources\Marks\Schemas;

use App\Models\Enrollment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MarksForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('enrollment_id')
                    ->label('Student & Subject')
                    ->options(function () {
                        $user = auth()->user();

                        // If teacher, only show enrollments for their subjects
                        if ($user && $user->hasRole('teacher')) {
                            return Enrollment::query()
                                ->whereHas('grade', function ($query) use ($user) {
                                    $query->whereHas('subjects', function ($q) use ($user) {
                                        $q->where('grade_subject.teacher_id', $user->id);
                                    });
                                })
                                ->with(['student', 'subject', 'grade'])
                                ->get()
                                ->mapWithKeys(function ($enrollment) {
                                    return [
                                        $enrollment->id =>
                                        $enrollment->student->name .
                                            ' - ' .
                                            $enrollment->subject->name .
                                            ' (' . $enrollment->grade->name . ')'
                                    ];
                                });
                        }

                        // Admin sees all
                        return Enrollment::with(['student', 'subject', 'grade'])
                            ->get()
                            ->mapWithKeys(function ($enrollment) {
                                return [
                                    $enrollment->id =>
                                    $enrollment->student->name .
                                        ' - ' .
                                        $enrollment->subject->name .
                                        ' (' . $enrollment->grade->name . ')'
                                ];
                            });
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),

                Section::make('Quiz')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('quiz_marks')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get) => $get('quiz_max') ?? 10)
                                    ->default(0),

                                TextInput::make('quiz_max')
                                    ->label('Max Marks')
                                    ->numeric()
                                    ->default(10)
                                    ->minValue(1)
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make('Assignment')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('assignment_marks')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get) => $get('assignment_max') ?? 20)
                                    ->default(0),

                                TextInput::make('assignment_max')
                                    ->label('Max Marks')
                                    ->numeric()
                                    ->default(20)
                                    ->minValue(1)
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make('Midterm Exam')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('midterm_marks')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get) => $get('midterm_max') ?? 20)
                                    ->default(0),

                                TextInput::make('midterm_max')
                                    ->label('Max Marks')
                                    ->numeric()
                                    ->default(20)
                                    ->minValue(1)
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),

                Section::make('Final Exam')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('final_marks')
                                    ->label('Marks Obtained')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(fn(Get $get) => $get('final_max') ?? 50)
                                    ->default(0),

                                TextInput::make('final_max')
                                    ->label('Max Marks')
                                    ->numeric()
                                    ->default(50)
                                    ->minValue(1)
                                    ->required(),
                            ]),
                    ])
                    ->collapsible()
                    ->columns(2),

                TextInput::make('total_max')
                    ->label('Total Max Marks')
                    ->numeric()
                    ->default(100)
                    ->required()
                    ->helperText('Total of all max marks (default: 10+20+20+50=100)')
                    ->columnSpanFull(),
            ]);
    }
}
