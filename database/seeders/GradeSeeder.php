<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('type', 'teacher')->get();
        $subjects = Subject::all();

        $grades = [
            ['name' => 'Grade 1', 'section' => 'A', 'academic_year' => 2024],
            ['name' => 'Grade 1', 'section' => 'B', 'academic_year' => 2024],
            ['name' => 'Grade 2', 'section' => 'A', 'academic_year' => 2024],
        ];

        foreach ($grades as $index => $gradeData) {
            $grade = Grade::firstOrCreate(
                [
                    'name' => $gradeData['name'],
                    'section' => $gradeData['section'],
                    'academic_year' => $gradeData['academic_year'],
                ],
                array_merge($gradeData, [
                    'teacher_id' => $teachers[$index % $teachers->count()]->id ?? null,
                ])
            );

            // Attach subjects to grade (first 3 subjects for each grade)
            $grade->subjects()->syncWithoutDetaching($subjects->take(3)->pluck('id'));
        }
    }
}
