<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingSystemSubjectTable extends Migration
{
    public function up()
    {
        Schema::create('grading_system_subject', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the pivot table
            $table->unsignedBigInteger('grading_system_id'); // Reference to grading systems
            $table->unsignedInteger('subject_id'); // Reference to subjects

            // Foreign key constraints
            $table->foreign('grading_system_id')->references('id')->on('grading_systems')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->timestamps(); // For created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('grading_system_subject');
    }
}
