<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Mark;
use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = Enrollment::all();
        $examTypes = ['Midterm', 'Final', 'Quiz'];

        foreach ($enrollments as $enrollment) {
            // Create 2-3 marks per enrollment
            for ($i = 0; $i < rand(2, 3); $i++) {
                Mark::create([
                    'enrollment_id' => $enrollment->id,
                    'marks' => rand(50, 100),
                    'total_marks' => 100,
                    'exam_type' => $examTypes[array_rand($examTypes)],
                    'exam_date' => now()->subDays(rand(1, 30)),
                    'remarks' => rand(0, 1) ? 'Good performance' : null,
                ]);
            }
        }
    }
}
