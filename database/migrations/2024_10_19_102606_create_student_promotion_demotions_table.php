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
        Schema::create('student_promotion_demotions', function (Blueprint $table) {
            $table->id();

            // Foreign keys with cascading deletes
            $table->unsignedInteger('student_record_id')->constrained('student_records')->onDelete('cascade');
            $table->unsignedInteger('old_class_id')->constrained('my_classes')->onDelete('cascade');
            $table->unsignedInteger('new_class_id')->constrained('my_classes')->onDelete('cascade');
            $table->unsignedInteger('old_section_id')->constrained('sections')->onDelete('cascade');
            $table->unsignedInteger('new_section_id')->constrained('sections')->onDelete('cascade');

            // Other fields
            $table->enum('type', ['promotion', 'demotion']);
            $table->text('reason')->nullable();
            $table->timestamp('event_date')->useCurrent();

            $table->integer('repetition_count')->default(0);
            $table->year('academic_year');
            $table->year('previous_academic_year')->nullable();
            $table->year('next_academic_year')->nullable();

            // Approved by field with null handling
            $table->unsignedInteger('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('remarks')->nullable(); // General remarks on the promotion/demotion

            // Timestamps for created and updated records
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_promotion_demotions');
    }
};
