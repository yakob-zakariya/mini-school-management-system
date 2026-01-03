<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin (if not exists from filament:make-user)
        $admin = User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'type' => 'admin',
            ]
        );
        $admin->assignRole('super_admin');

        // Create Teachers
        $teachers = [
            [
                'name' => 'yahya zakariya',
                'email' => 'yahya.teacher@school.com',
                'employee_id' => 'T001',
                'phone' => '1234567890',
            ],
            [
                'name' => 'ibrahim zakariya',
                'email' => 'ibrahim.teacher@school.com',
                'employee_id' => 'T002',
                'phone' => '1234567891',
            ],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = User::firstOrCreate(
                ['email' => $teacherData['email']],
                array_merge($teacherData, [
                    'password' => Hash::make('password'),
                    'type' => 'teacher',
                ])
            );
            $teacher->assignRole('teacher');
        }

        // Create Students
        $students = [
            ['name' => 'Alice Brown', 'email' => 'alice@school.com', 'student_id' => 'S001', 'date_of_birth' => '2010-05-15'],
            ['name' => 'Bob Wilson', 'email' => 'bob@school.com', 'student_id' => 'S002', 'date_of_birth' => '2010-08-22'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@school.com', 'student_id' => 'S003', 'date_of_birth' => '2011-03-10'],
            ['name' => 'Diana Miller', 'email' => 'diana@school.com', 'student_id' => 'S004', 'date_of_birth' => '2011-07-18'],
            ['name' => 'Eve Martinez', 'email' => 'eve@school.com', 'student_id' => 'S005', 'date_of_birth' => '2010-12-05'],
        ];

        foreach ($students as $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                array_merge($studentData, [
                    'password' => Hash::make('password'),
                    'type' => 'student',
                    'phone' => '9876543210',
                ])
            );
            $student->assignRole('student');
        }
    }
}
