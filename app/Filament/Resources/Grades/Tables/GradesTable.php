<?php

namespace App\Filament\Resources\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Grade'),
                TextColumn::make('section')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('academic_year')
                    ->numeric()
                    ->badge()
                    ->color('success'),
                TextColumn::make('teacher.name')
                    ->searchable()
                    ->label('Class Teacher')
                    ->default("Not Assigned"),
                TextColumn::make('subjects.name')
                    ->badge()
                    ->separator(',')
                    ->label('Subjects')
                    ->default("No Subjects"),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
