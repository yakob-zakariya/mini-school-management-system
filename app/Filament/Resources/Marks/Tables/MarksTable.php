<?php

namespace App\Filament\Resources\Marks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MarksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('enrollment.subject.name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('enrollment.grade.name')
                    ->label('Grade')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quiz_marks')
                    ->label('Quiz')
                    ->formatStateUsing(fn($record) => ($record->quiz_marks ?? 0) . '/' . $record->quiz_max)
                    ->alignCenter(),

                TextColumn::make('assignment_marks')
                    ->label('Assignment')
                    ->formatStateUsing(fn($record) => ($record->assignment_marks ?? 0) . '/' . $record->assignment_max)
                    ->alignCenter(),

                TextColumn::make('midterm_marks')
                    ->label('Midterm')
                    ->formatStateUsing(fn($record) => ($record->midterm_marks ?? 0) . '/' . $record->midterm_max)
                    ->alignCenter(),

                TextColumn::make('final_marks')
                    ->label('Final')
                    ->formatStateUsing(fn($record) => ($record->final_marks ?? 0) . '/' . $record->final_max)
                    ->alignCenter(),

                TextColumn::make('total_marks')
                    ->label('Total')
                    ->formatStateUsing(fn($record) => ($record->total_marks ?? 0) . '/' . $record->total_max)
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('percentage')
                    ->label('Percentage')
                    ->formatStateUsing(fn($record) => number_format($record->percentage ?? 0, 2) . '%')
                    ->badge()
                    ->color(fn($record): string => match (true) {
                        ($record->percentage ?? 0) >= 90 => 'success',
                        ($record->percentage ?? 0) >= 75 => 'info',
                        ($record->percentage ?? 0) >= 60 => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('grade_letter')
                    ->label('Grade')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'A+', 'A' => 'success',
                        'B+', 'B' => 'info',
                        'C+', 'C' => 'warning',
                        'D' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('grade')
                    ->relationship('enrollment.grade', 'name')
                    ->label('Grade'),

                SelectFilter::make('subject')
                    ->relationship('enrollment.subject', 'name')
                    ->label('Subject'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();

                // If teacher, only show marks for their subjects
                if ($user && $user->hasRole('teacher')) {
                    $query->whereHas('enrollment.grade', function ($q) use ($user) {
                        $q->whereHas('subjects', function ($subQuery) use ($user) {
                            $subQuery->where('grade_subject.teacher_id', $user->id);
                        });
                    });
                }
            });
    }
}
