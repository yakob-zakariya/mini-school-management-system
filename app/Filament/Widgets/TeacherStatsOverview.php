<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TeacherStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return [];
            }

            // Get teacher's subjects
            $teacherSubjects = Subject::where('teacher_id', $user->id)->pluck('id');

            // Get teacher's classes (grades where they teach)
            $teacherGrades = Grade::whereHas('subjects', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->count();

            // Count students enrolled in teacher's subjects
            $studentsCount = Enrollment::whereIn('subject_id', $teacherSubjects)
                ->distinct()
                ->count('student_id');

            // Count marks entered by this teacher
            $marksEntered = Mark::whereIn('subject_id', $teacherSubjects)->count();

            // Calculate average mark for teacher's subjects
            $averageMark = Mark::whereIn('subject_id', $teacherSubjects)->avg('mark');
        } catch (\Exception $e) {
            \Log::error('TeacherStatsOverview Error: ' . $e->getMessage());
            return [];
        }

        return [
            Stat::make('My Classes', $teacherGrades)
                ->description('Classes you teach')
                ->descriptionIcon('heroicon-o-building-library')
                ->color('success')
                ->chart([2, 3, 3, 4, 4, 5, 5]),

            Stat::make('My Subjects', $teacherSubjects->count())
                ->description('Subjects assigned to you')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info')
                ->chart([1, 2, 2, 3, 3, 3, 3]),

            Stat::make('Total Students', $studentsCount)
                ->description('Students in your classes')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning')
                ->chart([10, 15, 20, 25, 30, 35, 40]),

            Stat::make('Marks Entered', $marksEntered)
                ->description('Total marks recorded')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->color('primary')
                ->chart([5, 10, 15, 20, 25, 30, 35]),

            Stat::make('Average Mark', $averageMark ? number_format($averageMark, 1) : 'N/A')
                ->description('Across all your subjects')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('danger')
                ->chart([60, 65, 70, 72, 75, 73, 75]),
        ];
    }

    // Only show for teachers
    public static function canView(): bool
    {
        try {
            $user = auth()->user();
            return $user && $user->hasRole('teacher');
        } catch (\Exception $e) {
            return false;
        }
    }
}
