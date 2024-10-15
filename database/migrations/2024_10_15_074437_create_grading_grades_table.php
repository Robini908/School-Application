<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingGradesTable extends Migration
{
    public function up()
    {
        Schema::create('grading_grades', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('grading_system_id') // Foreign key referencing the grading_systems table
                ->constrained() // Adds a foreign key constraint
                ->onDelete('cascade'); // Delete grades when grading system is deleted
            $table->string('grade', 2); // Grade as a string
            $table->string('remark')->nullable(); // Optional remark
            $table->decimal('gpa', 4, 2); // Updated to allow for GPA with 4 digits in total, 2 after the decimal
            $table->integer('range_from'); // Lower bound of the grade range
            $table->integer('range_to'); // Upper bound of the grade range
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('grading_grades');
    }
}
