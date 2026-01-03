<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'teacher', 'student'])->default('student')->after('email');

            $table->string('phone')->nullable()->after('type');
            $table->text('address')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->string('employee_id')->nullable()->unique()->after('date_of_birth'); // for teachers
            $table->string('student_id')->nullable()->unique()->after('employee_id'); // for students
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'phone', 'address', 'date_of_birth', 'employee_id', 'student_id']);
        });
    }
};
