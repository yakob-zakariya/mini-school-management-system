<?php

namespace App\Filament\Widgets;

use App\Models\Mark;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return [];
            }

            // Get student's enrollments
            $enrollments = Enrollment::where('student_id', $user->id)->get();

            // Get student's marks
            $marks = Mark::where('student_id', $user->id)->get();

            // Calculate average mark
            $averageMark = $marks->avg('mark');

            // Get highest mark
            $highestMark = $marks->max('mark');

            // Get lowest mark
            $lowestMark = $marks->min('mark');

            // Count subjects
            $subjectsCount = $enrollments->pluck('subject_id')->unique()->count();

            // Determine color based on average
            $averageColor = 'primary';
            if ($averageMark) {
                $averageColor = $averageMark >= 75 ? 'success' : ($averageMark >= 50 ? 'warning' : 'danger');
            }
        } catch (\Exception $e) {
            \Log::error('StudentStatsOverview Error: ' . $e->getMessage());
            return [];
        }

        return [
            Stat::make('My Subjects', $subjectsCount)
                ->description('Enrolled subjects')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info')
                ->chart([3, 4, 5, 5, 6, 6, 6]),

            Stat::make('Total Marks', $marks->count())
                ->description('Marks received')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->chart([5, 8, 12, 15, 18, 20, 22]),

            Stat::make('Average Mark', $averageMark ? number_format($averageMark, 1) : 'N/A')
                ->description('Overall average')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($averageColor)
                ->chart([60, 65, 68, 70, 72, 74, 75]),

            Stat::make('Highest Mark', $highestMark ?? 'N/A')
                ->description('Best performance')
                ->descriptionIcon('heroicon-o-trophy')
                ->color('success')
                ->chart([70, 75, 80, 85, 88, 90, 92]),

            Stat::make('Lowest Mark', $lowestMark ?? 'N/A')
                ->description('Needs improvement')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->chart([40, 45, 50, 52, 55, 58, 60]),
        ];
    }

    // Only show for students
    public static function canView(): bool
    {
        try {
            $user = auth()->user();
            return $user && $user->hasRole('student');
        } catch (\Exception $e) {
            return false;
        }
    }
}
