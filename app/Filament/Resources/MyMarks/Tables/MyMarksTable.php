<?php

namespace App\Filament\Resources\MyMarks\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyMarksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.subject.name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('enrollment.grade.name')
                    ->label('Grade/Class')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quiz_marks')
                    ->label('Quiz')
                    ->formatStateUsing(fn($record) => "{$record->quiz_marks}/{$record->quiz_max}")
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('assignment_marks')
                    ->label('Assignment')
                    ->formatStateUsing(fn($record) => "{$record->assignment_marks}/{$record->assignment_max}")
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('midterm_marks')
                    ->label('Midterm')
                    ->formatStateUsing(fn($record) => "{$record->midterm_marks}/{$record->midterm_max}")
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('final_marks')
                    ->label('Final')
                    ->formatStateUsing(fn($record) => "{$record->final_marks}/{$record->final_max}")
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('total_marks')
                    ->label('Total')
                    ->formatStateUsing(fn($record) => "{$record->total_marks}/{$record->total_max}")
                    ->alignCenter()
                    ->weight('bold')
                    ->badge()
                    ->color('success'),

                TextColumn::make('percentage')
                    ->label('Percentage')
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '%')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('grade_letter')
                    ->label('Grade')
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'A+', 'A' => 'success',
                        'B+', 'B' => 'info',
                        'C+', 'C' => 'warning',
                        'D' => 'danger',
                        'F' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                // Students can filter by subject if needed
            ])
            ->recordActions([
                // No actions - read-only
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // Only show marks for the logged-in student
                if ($user && $user->hasRole('student')) {
                    $query->whereHas('enrollment', function ($q) use ($user) {
                        $q->where('student_id', $user->id);
                    });
                }
            })
            ->defaultSort('enrollment.subject.name');
    }
}
