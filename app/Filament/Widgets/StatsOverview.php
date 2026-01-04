<?php

namespace App\Filament\Widgets;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Students', User::whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })->count())
                ->description('Registered students')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chart([7, 12, 15, 18, 22, 25, 28]),

            Stat::make('Total Teachers', User::whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->count())
                ->description('Active teachers')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info')
                ->chart([3, 5, 6, 7, 8, 9, 10]),

            Stat::make('Total Grades/Classes', Grade::count())
                ->description('Active classes')
                ->descriptionIcon('heroicon-o-building-library')
                ->color('warning')
                ->chart([2, 3, 4, 5, 6, 7, 8]),

            Stat::make('Total Subjects', Subject::count())
                ->description('Available subjects')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary')
                ->chart([5, 7, 9, 11, 13, 15, 17]),

            Stat::make('Total Enrollments', Enrollment::count())
                ->description('Student enrollments')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('danger')
                ->chart([10, 20, 30, 40, 50, 60, 70]),
        ];
    }

    // Only show for admins
    public static function canView(): bool
    {
        return auth()->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }
}

