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

        foreach ($enrollments as $enrollment) {
            // Create one mark record per enrollment with all exam types
            Mark::create([
                'enrollment_id' => $enrollment->id,

                // Quiz marks (out of 10)
                'quiz_marks' => rand(5, 10),
                'quiz_max' => 10,

                // Assignment marks (out of 20)
                'assignment_marks' => rand(10, 20),
                'assignment_max' => 20,

                // Midterm marks (out of 20)
                'midterm_marks' => rand(10, 20),
                'midterm_max' => 20,

                // Final marks (out of 50)
                'final_marks' => rand(25, 50),
                'final_max' => 50,

                // Total max (10 + 20 + 20 + 50 = 100)
                'total_max' => 100,

                // total_marks, percentage, and grade_letter are auto-calculated by the Mark model
            ]);
        }
    }
}
