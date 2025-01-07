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
        Schema::table('dorm_student', function (Blueprint $table) {
            // Drop the incorrect column if it exists
            if (Schema::hasColumn('dorm_student', 'student_record_id')) {
                $table->dropForeign(['student_record_id']); // Drop foreign key first
                $table->dropColumn('student_record_id');
            }
    
            // Add the correct column
            if (!Schema::hasColumn('dorm_student', 'student_id')) {
                $table->foreignId('student_id')->constrained('student_records')->onDelete('cascade');
            }
        });
    }
    
    public function down()
    {
        Schema::table('dorm_student', function (Blueprint $table) {
            // Revert the changes if needed
            if (Schema::hasColumn('dorm_student', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }
    
            if (!Schema::hasColumn('dorm_student', 'student_record_id')) {
                $table->foreignId('student_record_id')->constrained('student_records')->onDelete('cascade');
            }
        });
    }
};
