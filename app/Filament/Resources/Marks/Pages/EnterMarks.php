<?php

namespace App\Filament\Resources\Marks\Pages;

use App\Filament\Resources\Marks\MarksResource;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class EnterMarks extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string $resource = MarksResource::class;

    protected static ?string $title = 'Enter Marks';

    public ?User $student = null;
    public ?Grade $grade = null;
    public ?array $data = [];

    public function getView(): string
    {
        return 'filament.resources.marks.pages.enter-marks';
    }

    public function mount(): void
    {
        $studentId = request()->query('student');
        $gradeId = request()->query('grade');

        $this->student = User::findOrFail($studentId);
        $this->grade = Grade::findOrFail($gradeId);

        // Load existing marks
        $this->loadExistingMarks();
    }

    protected function loadExistingMarks(): void
    {
        $enrollments = $this->getFilteredEnrollments();

        $formData = [];

        foreach ($enrollments as $enrollment) {
            $mark = $enrollment->marks->first();

            if ($mark) {
                $formData["enrollment_{$enrollment->id}_quiz_marks"] = $mark->quiz_marks;
                $formData["enrollment_{$enrollment->id}_quiz_max"] = $mark->quiz_max;
                $formData["enrollment_{$enrollment->id}_assignment_marks"] = $mark->assignment_marks;
                $formData["enrollment_{$enrollment->id}_assignment_max"] = $mark->assignment_max;
                $formData["enrollment_{$enrollment->id}_midterm_marks"] = $mark->midterm_marks;
                $formData["enrollment_{$enrollment->id}_midterm_max"] = $mark->midterm_max;
                $formData["enrollment_{$enrollment->id}_final_marks"] = $mark->final_marks;
                $formData["enrollment_{$enrollment->id}_final_max"] = $mark->final_max;
                $formData["enrollment_{$enrollment->id}_total_max"] = $mark->total_max;
            }
        }

        $this->schema->fill($formData);
    }

    protected function getFilteredEnrollments()
    {
        $query = $this->student->enrollments()
            ->where('grade_id', $this->grade->id)
            ->with(['subject', 'marks']);

        // If teacher, only show enrollments for subjects they teach in this grade
        $user = auth()->user();
        if ($user && $user->hasRole('teacher')) {
            $query->whereHas('subject', function ($q) use ($user) {
                $q->whereHas('grades', function ($gradeQuery) use ($user) {
                    $gradeQuery->where('grades.id', $this->grade->id)
                        ->where('grade_subject.teacher_id', $user->id);
                });
            });
        }

        return $query->get();
    }

    public function getTitle(): string
    {
        return "Enter Marks for {$this->student->name}";
    }

    public function schema(Schema $schema): Schema
    {
        $enrollments = $this->getFilteredEnrollments();

        $components = [];

        foreach ($enrollments as $enrollment) {
            $components[] = Section::make($enrollment->subject->name)
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make("enrollment_{$enrollment->id}_quiz_marks")
                                ->label('Quiz Marks')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            TextInput::make("enrollment_{$enrollment->id}_quiz_max")
                                ->label('Quiz Max')
                                ->numeric()
                                ->default(10)
                                ->minValue(1),

                            TextInput::make("enrollment_{$enrollment->id}_assignment_marks")
                                ->label('Assignment Marks')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            TextInput::make("enrollment_{$enrollment->id}_assignment_max")
                                ->label('Assignment Max')
                                ->numeric()
                                ->default(20)
                                ->minValue(1),

                            TextInput::make("enrollment_{$enrollment->id}_midterm_marks")
                                ->label('Midterm Marks')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            TextInput::make("enrollment_{$enrollment->id}_midterm_max")
                                ->label('Midterm Max')
                                ->numeric()
                                ->default(20)
                                ->minValue(1),

                            TextInput::make("enrollment_{$enrollment->id}_final_marks")
                                ->label('Final Marks')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            TextInput::make("enrollment_{$enrollment->id}_final_max")
                                ->label('Final Max')
                                ->numeric()
                                ->default(50)
                                ->minValue(1),

                            TextInput::make("enrollment_{$enrollment->id}_total_max")
                                ->label('Total Max')
                                ->numeric()
                                ->default(100)
                                ->minValue(1)
                                ->columnSpanFull(),
                        ]),
                ])
                ->collapsible();
        }

        return $schema->components($components);
    }

    public function save(): void
    {
        $data = $this->schema->getState();
        $user = auth()->user();

        // Debug: Log the raw data
        \Log::info('Raw schema data:', $data);

        // Group data by enrollment ID
        $enrollmentData = [];
        foreach ($data as $key => $value) {
            if (preg_match('/^enrollment_(\d+)_(.+)$/', $key, $matches)) {
                $enrollmentId = $matches[1];
                $field = $matches[2];
                $enrollmentData[$enrollmentId][$field] = $value;
            }
        }

        // Debug: Log the grouped data
        \Log::info('Grouped enrollment data:', $enrollmentData);

        DB::transaction(function () use ($enrollmentData, $user) {
            foreach ($enrollmentData as $enrollmentId => $values) {
                $enrollment = Enrollment::with(['subject', 'grade'])->find($enrollmentId);

                if (!$enrollment) {
                    continue;
                }

                // Authorization check: If teacher, verify they teach this subject in this grade
                if ($user && $user->hasRole('teacher')) {
                    $canEdit = DB::table('grade_subject')
                        ->where('grade_id', $enrollment->grade_id)
                        ->where('subject_id', $enrollment->subject_id)
                        ->where('teacher_id', $user->id)
                        ->exists();

                    if (!$canEdit) {
                        continue; // Skip this enrollment if teacher doesn't teach this subject
                    }
                }

                Mark::updateOrCreate(
                    ['enrollment_id' => $enrollmentId],
                    [
                        'quiz_marks' => $values['quiz_marks'] ?? 0,
                        'quiz_max' => $values['quiz_max'] ?? 10,
                        'assignment_marks' => $values['assignment_marks'] ?? 0,
                        'assignment_max' => $values['assignment_max'] ?? 20,
                        'midterm_marks' => $values['midterm_marks'] ?? 0,
                        'midterm_max' => $values['midterm_max'] ?? 20,
                        'final_marks' => $values['final_marks'] ?? 0,
                        'final_max' => $values['final_max'] ?? 50,
                        'total_max' => $values['total_max'] ?? 100,
                    ]
                );
            }
        });

        Notification::make()
            ->title('Marks saved successfully!')
            ->success()
            ->send();

        $this->loadExistingMarks();
    }

    protected function getSchemaActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Marks')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check'),

            Action::make('cancel')
                ->label('Cancel')
                ->url(route('filament.admin.resources.my-grades.students', ['record' => $this->grade->id]))
                ->color('gray')
                ->icon('heroicon-o-x-mark'),
        ];
    }
}
