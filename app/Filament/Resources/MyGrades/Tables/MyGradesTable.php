<?php

namespace App\Filament\Resources\MyGrades\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyGradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Grade/Class')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('subjects_count')
                    ->label('My Subjects')
                    ->counts([
                        'subjects' => function (Builder $query) {
                            $user = auth()->user();
                            if ($user) {
                                $query->where('grade_subject.teacher_id', $user->id);
                            }
                        }
                    ])
                    ->badge()
                    ->color('info'),

                TextColumn::make('subjects')
                    ->label('Teaching Subjects')
                    ->formatStateUsing(function ($record) {
                        $user = auth()->user();
                        if (!$user) {
                            return '-';
                        }

                        $subjects = $record->subjects()
                            ->wherePivot('teacher_id', $user->id)
                            ->pluck('name')
                            ->join(', ');

                        return $subjects ?: '-';
                    })
                    ->wrap(),

                TextColumn::make('students_count')
                    ->label('Total Students')
                    ->counts('students')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('view_students')
                    ->label('View Students')
                    ->icon('heroicon-o-users')
                    ->url(fn($record) => route('filament.admin.resources.my-grades.students', ['record' => $record->id]))
                    ->openUrlInNewTab(false),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Only show grades where the teacher teaches at least one subject
                if ($user && $user->hasRole('teacher')) {
                    $query->whereHas('subjects', function ($q) use ($user) {
                        $q->where('grade_subject.teacher_id', $user->id);
                    });
                }
            })
            ->defaultSort('name');
    }
}
