<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('student_transitions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('student_id')->constrained('student_records')->onDelete('cascade'); // Links to the student
            $table->year('transition_year'); // Academic year of the transition
            $table->enum('transition_type', ['promotion', 'demotion', 'repetition', 'graduation']); // Type of transition
            $table->unsignedInteger('target_class_id')->nullable()->constrained('my_classes')->onDelete('set null'); // Target class (if applicable)
            $table->unsignedInteger('target_section_id')->nullable()->constrained('sections')->onDelete('set null'); // Target section (if applicable)
            $table->text('reason')->nullable(); // Reason for the transition
            $table->unsignedInteger('decision_by')->constrained('users')->onDelete('cascade'); // User who made the decision
            $table->dateTime('decision_date'); // Date and time of the decision
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('student_transitions');
    }
}