<?php

namespace App\Filament\Resources\MyGrades\Pages;

use App\Filament\Resources\MyGrades\MyGradesResource;
use App\Models\Grade;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ViewGradeStudents extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MyGradesResource::class;

    protected static ?string $title = 'View Students';

    public Grade $record;

    public function getView(): string
    {
        return 'filament.resources.my-grades.pages.view-grade-students';
    }

    public function getTitle(): string
    {
        return "Students in {$this->record->name}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('current_grade_id', $this->record->id)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'student');
                    })
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('enrollments_count')
                    ->label('Enrolled Subjects')
                    ->counts('enrollments')
                    ->badge()
                    ->color('info'),

                TextColumn::make('marks_count')
                    ->label('Marks Entered')
                    ->getStateUsing(function ($record) {
                        return $record->enrollments()
                            ->whereHas('marks')
                            ->count();
                    })
                    ->badge()
                    ->color('success'),
            ])
            ->recordActions([
                Action::make('enter_marks')
                    ->label('Enter Marks')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(function ($record) {
                        return route('filament.admin.resources.marks.enter', [
                            'student' => $record->id,
                            'grade' => $this->record->id,
                        ]);
                    }),
            ])
            ->defaultSort('name');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to My Classes')
                ->icon('heroicon-o-arrow-left')
                ->url(MyGradesResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
