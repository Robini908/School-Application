<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dorm_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id')->constrained('student_records')->onDelete('cascade');
            $table->unsignedInteger('dorm_id')->constrained('dorms')->onDelete('cascade');
            $table->year('year'); // Track the year/session
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorm_student');
    }
};
