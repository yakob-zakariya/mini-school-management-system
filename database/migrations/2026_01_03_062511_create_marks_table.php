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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->decimal('quiz_marks', 5, 2)->nullable();
            $table->decimal('quiz_max', 5, 2)->default(10);
            $table->decimal('assignment_marks', 5, 2)->nullable();
            $table->decimal('assignment_max', 5, 2)->default(20);
            $table->decimal('midterm_marks', 5, 2)->nullable();
            $table->decimal('midterm_max', 5, 2)->default(20);
            $table->decimal('final_marks', 5, 2)->nullable();
            $table->decimal('final_max', 5, 2)->default(50);
            $table->decimal('total_marks', 5, 2)->nullable();
            $table->decimal('total_max', 5, 2)->default(100);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade_letter')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
