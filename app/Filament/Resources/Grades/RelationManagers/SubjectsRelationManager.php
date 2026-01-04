<?php

namespace App\Filament\Resources\Grades\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions;
use App\Models\User;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Subject')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),

                TextColumn::make('pivot.teacher_id')
                    ->label('Teacher')
                    ->formatStateUsing(fn($state) => $state ? User::find($state)?->name : 'Not Assigned')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('teacher_id')
                            ->label('Teacher')
                            ->options(User::where('type', 'teacher')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select a teacher'),
                    ]),
            ])
            ->recordActions([
                Actions\EditAction::make()
                    ->form([
                        Select::make('teacher_id')
                            ->label('Teacher')
                            ->options(User::where('type', 'teacher')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select a teacher'),
                    ]),
                Actions\DetachAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
