<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('type', 'teacher')->get();

        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101', 'description' => 'Basic Mathematics'],
            ['name' => 'English', 'code' => 'ENG101', 'description' => 'English Language and Literature'],
            ['name' => 'Science', 'code' => 'SCI101', 'description' => 'General Science'],
            ['name' => 'History', 'code' => 'HIST101', 'description' => 'World History'],
            ['name' => 'Geography', 'code' => 'GEO101', 'description' => 'Physical and Human Geography'],
        ];

        foreach ($subjects as $index => $subjectData) {
            Subject::firstOrCreate(
                ['code' => $subjectData['code']],
                array_merge($subjectData, [
                    'teacher_id' => $teachers[$index % $teachers->count()]->id ?? null,
                ])
            );
        }
    }
}
