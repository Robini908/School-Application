<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('range_from');
            $table->integer('range_to');
            $table->string('grade');
            $table->unsignedBigInteger('grading_system_id');
            $table->unsignedInteger('subject_id')->nullable(); // Add subject_id column

            $table->string('remark')->nullable(); // Add remark column
            $table->string('gpa')->nullable(); // Add gpa column

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('grading_system_id')->references('id')->on('grading_systems')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade'); // Foreign key for subject_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grading_ranges');
    }
}
