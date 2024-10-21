<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_transitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transition_uid')->unique();
            $table->uuid('student_id')->index();
            $table->uuid('new_class_id')->constrained('my_classes')->onDelete('cascade');
            $table->uuid('new_section_id')->constrained('sections')->onDelete('cascade');
            $table->enum('transition_type', ['promotion', 'demotion', 'repetition', 'completion']);
            $table->text('reason')->nullable();
            $table->timestamp('event_date')->useCurrent();
            $table->unsignedInteger('repetition_count')->default(0);
            $table->year('academic_year');
            $table->year('next_academic_year')->nullable();
            $table->enum('completion_status', ['active', 'graduated', 'completed'])->nullable();
            $table->uuid('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transitions');
    }
};
